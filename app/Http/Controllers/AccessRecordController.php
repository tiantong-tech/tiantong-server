<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\IP;
use App\Models\Device;
use App\Models\AccessRecord;

class AccessRecordController extends Controller
{
  public function devicesBlacklistScan()
  {
    $agents = DB::table('devices as d')
      ->whereNotExists(function ($query) {
        $query->select(DB::raw(1))
          ->from('access_records as ar')
          ->whereRaw('ar.device_id = d.id');
      })
      ->whereNotExists(function ($query) {
        $query->select(DB::raw(1))
          ->from('device_blacklist as db')
          ->whereRaw('db.user_agent = d.user_agent');
      })
      ->distinct()
      ->pluck('user_agent');

    $data = [];
    $now = Carbon::now();
    foreach ($agents as $agent) {
      $data[] = [
        'user_agent' => $agent,
        'created_at' => $now
      ];
    }
    DB::table('device_blacklist')->insert($data);

    return $this->success([
      'message' => 'success_to_scan_devices',
      'devices' => sizeof($agents)
    ]);
  }

  public function devicesBlackListConfirm()
  {
    /**
     * 1. 对 is_confirmed 为 false 的名单执行确认
     * 2. 如果该 user_agent 出现访问量 (access_records 中存在记录)，则移除黑名单
     * 3. 否则，如果超过观察期，将其确认
     * 3. 如果 device not exists，超过观察期将其确认
     */
    function getQuery () {
      return DB::table('device_blacklist as db')
        ->where('is_confirmed', false);
    }
    $exists = function ($query) {
      $query->select(DB::raw(1))
        ->from('devices as d')
        ->whereRaw('d.user_agent = db.user_agent')
        ->whereExists(function ($query) {
          $query->select(DB::raw(1))
          ->from('access_records as ar')
          ->whereRaw('ar.device_id = d.id');
        });
    };

    $unconfirmed = getQuery()
      ->whereExists($exists)
      ->delete();
    $confirmed = getQuery()
      ->whereNotExists($exists)
      ->where('created_at', '<=', Carbon::now()->subMonth(1))
      ->update(['is_confirmed' => true]);

    return $this->success([
      'message' => 'success to confirm device blacklist',
      'confirmed' => $confirmed,
      'unconfirmed' => $unconfirmed
    ]);
  }

  public function devicesBlackListClear()
  {
    $rows = DB::table('devices as d')
      ->whereExists(function ($query) {
        $query->select(DB::raw(1))
          ->from('device_blacklist as db')
          ->where('is_confirmed', true)
          ->whereRaw('db.user_agent = d.user_agent');
      })
      ->delete();

    return $this->success([
      'message' => 'success to clear device blacklist',
      'devices' => $rows
    ]);
  }

  public function getDevices()
  {
    $columns = [
      'd.id',
      DB::raw('count(distinct(ar.ip_id)) as ips'),
      DB::raw('count(ar) as counts'),
      DB::raw('count(distinct(ar.resource)) as resources'),
      'd.created_at',
      DB::raw('max(ar.created_at) as last_recorded_at')
    ];

    $data = DB::table('devices as d')
      ->select($columns)
      ->orderBy('counts', 'desc')
      ->leftJoin('access_records as ar', 'ar.device_id', 'd.id')
      ->groupBy('d.id')
      ->page();

    return $data;
  }

  public function getIPs()
  {
    $columns = [
      'ip.id', 'ip.address', 'ip.province', 'ip.city',
      DB::raw('count(ar) as counts'),
      DB::raw('count(distinct(ar.device_id)) as devices'),
      DB::raw('count(distinct(ar.resource)) as resources'),
      DB::raw('min(ar.created_at) as created_at'),
      DB::raw('max(ar.created_at) as last_recorded_at')
    ];

    $data = DB::table('ip')
      ->select($columns)
      ->orderBy('counts', 'desc')
      ->leftJoin('access_records as ar', 'ar.ip_id', 'ip.id')
      ->groupBy('ip.id')
      ->page();

    return $data;
  }

  public function searchAccessRecords()
  {
    $columns = [
      'ip.address as ip', 'ip.province', 'ip.city',
      'name', 'type', 'resource',
      'ar.created_at'
    ];
    // $data = AccessRecord::with('ip')
    //   ->select('ip.address as ip')
    //   ->paginate();

    $data = DB::table('access_records as ar')
      ->select($columns)
      ->orderBy('ar.created_at', 'desc')
      ->leftJoin('ip', 'ip.id', 'ar.ip_id')
      ->page();

    return $data;
  }

  /**
   * 生成设备 key
   * 调用后将生成一个唯一的设备 key
   * key 值通过哈希加密，由数据库做唯一约束
   * 发生碰撞直接报错，暂时牺牲个别用户体验
   */
  public function generateDeviceKey()
  {
    $device = new Device;
    $device->user_agent = Request::header('user-agent');
    $device->save();
    $device->key = Hash::make($device->id);
    $device->update();

    return $this->success([
      'key' => $device->key
    ]);
  }

  /**
   * 记录设备访问
   * 需要提供设备 key，资源名
   */
  public function accessRecord()
  {
    $type = $this->get('type', 'string');
    $resource = $this->get('resource', 'string');
    $deviceKey = Request::header('Authorization');
    $device = Device::where('key', $deviceKey)->first();

    if (!$deviceKey || !$device) {
      return $this->failure('fail_to_record_access', 401);
    }

    $record = new AccessRecord;
    $record->ip_id = $this->recordIp(Request::ip());
    $record->device_id = $device->id;
    $record->resource = $resource;
    $record->name = 'yuchdbn';
    $record->type = $type ? $type : 'page';
    $record->save();

    return $this->success('success_to_access_record');
  }

  /**
   * 记录 IP 定位
   * 假设 IP 对应的定位 L 会随时间改变，T 为 IP 的记录时间
   * 当 IP 不存在，则获取定位，否则检查 T
   * 如果 T 超过 s 时，重新检查定位 L
   * 定位 L 发生变化时，将重新计算新的 IP
   * 若 L 未发生变化，更新 T
   */
  private function recordIp($address)
  {
    function createIp ($address, $location) {
      $ip = new IP;
      $ip->province = $location->province;
      $ip->city = $location->city;
      $ip->address = $address;
      $ip->updated_at = Carbon::now();
      $ip->save();

      return $ip;
    }

    $ip = IP::where('address', $address)
      ->orderBy('updated_at', 'desc')
      ->first();

    if (!$ip) {
      // 录入 IP 定位
      $ip = createIp($address, $this->getIpLocation($address));
    } else if ($ip->updated_at < time() - 3600 * 24) {
      // 检查已录入的 IP 定位
      $location = $this->getIpLocation($address);
      if (
        $location->city === $ip->city &&
        $location->province === $ip->province
      ) {
        // 定位未发生改变
        $ip->updated_at = Carbon::now();
        $ip->save();
      } else {
        // 定位发生改变
        // 极少数情况下发生
        $ip = createIp($address, $location);
      }
    }

    return $ip->id;
  }

  private function getIpLocation($ip)
  {
    $key = env('GAODE_LOCATION_KEY');

    $client = new Client();
    $data = $client
      ->get("https://restapi.amap.com/v3/ip?key=$key&ip=$ip")
      ->getBody()
      ->getContents();
    $data = json_decode($data);
    $result = [];
    foreach (['province', 'city'] as $key) {
      if (gettype($data->$key) !== 'string') {
        $data->$key = '';
      }
    }

    return $data;
  }
}
