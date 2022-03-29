<?php
include('requestHelper.php');
//include('config-local.php');
include('config.php');

use weather\dailyForecas\Config;
//use weather\dailyForecas\ConfigLocal as Config;

    function main_handler($event, $context) {
        $location = Config::CITY_LOCATION;
        $qeatherKey = Config::QWEATHER_KEY;
        $wecomWebhookKey = Config::WECOM_ROBOT_KEY;
        $weatherIndicesType = Config::WEATHER_INDICES_TYPE;

        $query3dApi = "https://devapi.qweather.com/v7/weather/3d?location=$location&key=$qeatherKey";
        $indices1dApi = "https://devapi.qweather.com/v7/indices/1d?location=$location&key=$qeatherKey&type=$weatherIndicesType";

        $weatherData3dRes = httpGet($query3dApi);
        $weatherData3d = json_decode($weatherData3dRes, true);

        $indices1dRes = httpGet($indices1dApi);
        $indices1dData = json_decode($indices1dRes, true);

        if ($weatherData3d['code'] == 200) {
            $linkUrl = $weatherData3d['fxLink'];
            $date = date('Y-m-d H:i:s', strtotime($weatherData3d['updateTime']));
            $markdownContent = "[$date 天气预览]($linkUrl)\n";
            foreach ($weatherData3d['daily'] as $index => $daily) {
                $textDay = $daily['textDay'];
                $tempMin = $daily['tempMin'];
                $tempMax = $daily['tempMax'];
                $windScaleDay = $daily['windScaleDay'];
                $dayNames = ['今天', '明天', '后天'];
                $markdownContent = $markdownContent . '------------------------------' . "\n".
                    $dayNames[$index] . " $textDay  $tempMin" . "°到" . "$tempMax" . "°" . " 风力 $windScaleDay" . "级\n";
            }

            if ($indices1dData['code'] == 200) {
                $indicesLink = $indices1dData['fxLink'];
                $markdownContent = $markdownContent . '------------------------------'  . "\n" . "[天气指数]($indicesLink) \n";
                foreach ($indices1dData['daily'] as $item) {
                    $category = $item['category'];
                    $name = str_replace('指数', '', $item['name']);
                    $text = $item['text'];
                    $markdownContent = $markdownContent . '------------------------------' . "\n".
                        "<font color=\"warning\">" . "今日$category$name </font>\n" .
                        "> $text\n\n";
                }
            }

            $pushData = array(
                'msgtype' => 'markdown',
                'markdown' => [
                    'content' => $markdownContent
                ]
            );
            request_post("https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=$wecomWebhookKey", json_encode($pushData,'320'), 'json');
            return $pushData;
        }
    }

main_handler('','');