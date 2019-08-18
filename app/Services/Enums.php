<?php

namespace App\Services;

class Enums
{
  const groups = [
    'root', 'admin', 'sale',
  ];

  const saleTrackTypes = [
    '垂直输送机设计图基本参数表',
  ];

  const saleTrackStatuses = [
    '确认中', '已确认', '拜访中',
    '投标中', '已投标', '已取消', '已归档',
  ];

  const fileStatus = [
    'uploading', 'uploaded'
  ];

  const projectTypes = [
    'sale'
  ];

  const designSchemaTypes = [
    'hoister'
  ];

  const quotationTypes = [
    'mechanics', 'electronic control', 'engineering'
  ];
}
