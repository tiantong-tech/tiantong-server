<?php

namespace App\Services;

class Enums
{
  const roles = [
    'root', 'admin', 'sale',
  ];

  const saleTrackTypes = [
    '垂直输送及设计图基本参数表',
  ];

  const saleTrackStatuses = [
    '确认中', '拜访中', '投标中',
    '已投标', '已取消', '已归档',
  ];
}