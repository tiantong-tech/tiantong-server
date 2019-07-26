<?php

namespace App\Http\Controllers;

use Hash;
use Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\IP;
use App\Models\Device;
use App\Models\AccessRecord;

class AccessRecordController extends Controller
{
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
    $record->name = 'yuchuan';
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
