<?php

namespace weather\dailyForecas;
class Config
{
// 城市代码,可在 https://github.com/qwd/LocationList/blob/master/China-City-List-latest.csv 中查询
    const CITY_LOCATION = 'xxx';
// 和风天气应用的key https://dev.qweather.com/docs/resource/get-key/
    const QWEATHER_KEY = 'xxx';
// 生活指数的类型ID 多个英文逗号分隔 https://dev.qweather.com/docs/resource/indices-info/
    const WEATHER_INDICES_TYPE = '1,3,9,13';

// 企业微信群机器人webhook key
    const WECOM_ROBOT_KEY = 'xxx';
}