<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Activity;
use App\Exceptions\HttpException;

class ActivityController extends Controller
{
  public function update()
  {
    $data = $this->via([
      'name' => 'string',
      'notes' => 'string'
    ]);

    $activity = $this->getActivity();
    $activity->fill($data);
    $activity->updated_at = Carbon::now();
    $activity->save();

    return $this->success('success to update activity information');
  }

  protected function getActivity()
  {
    $id = $this->get('id');
    $file = Activity::find($id);
    if (!$activity) {
      throw new HttpException('fail to find activity by id', 404);
    }

    return $activity;
  }
}
