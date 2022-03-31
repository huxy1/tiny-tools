# 企业微信天气机器人

<img src="https://image-hosting-1252553681.cos.ap-guangzhou.myqcloud.com/IMG_2930.PNG" alt="IMG_2930" width="375" />

利用企业微信提供的群机器人webhook，向群内发送天气预报的信息。

## 使用前提

- 创建企业微信群机器人
- 开通[和风天气](https://dev.qweather.com/)开发者账号，创建应用
- 开通腾讯云云函数

## 使用方法

1. 配置config.php

   ```php
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
   ```

2. 上传文件到云函数

   ```
   serverless/weather/daily-forecas
   ├── config.php
   ├── index.php
   └── requestHelper.php
   ```

3. 创建触发器

<img src="https://image-hosting-1252553681.cos.ap-guangzhou.myqcloud.com/image-20220331175926727.png" alt="image-20220331175926727" style="zoom: 33%;" />

示例中创建的是定时触发触发器