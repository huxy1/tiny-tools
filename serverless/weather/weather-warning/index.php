<?php

require '../vendor/autoload.php';
require 'requestHelper.php';

use TencentCloudBase\TCB;

function main($event, $context)
{
    $db = init_db();

    // 要预警的地区id
    $location = 'xxx';//'101200113';
    // 和风天气api key
    $key = 'xxx';
    $webhookKey = 'xxx';
    // 获取天气预警api
    $queryWarningApi = "https://devapi.qweather.com/v7/warning/now?location=$location&key=$key";
    $warningResult = httpGet($queryWarningApi);
    $warningData = json_decode($warningResult, true);

    if ($warningData['code'] == 200 && !empty($warningData['warning'])) {
        $linkUrl = $warningData['fxLink'];
        $markdownContent = "[天气预警]($linkUrl)\n";
        $warningIds = array_column($warningData['warning'], 'id');
        $_ = $db->command;
        $hadPushRecord = $db->collection('WeatherWarningRecords')->where(['warningId' => $_->in($warningIds)])->count();
        if ($hadPushRecord['total'] < count($warningIds)) {
            foreach ($warningData['warning'] as $item) {
                $warningTitle = $item['typeName'] . $item['level'] . '预警';
                $detailTitle = $item['title'];
                $text = $item['text'];
                $markdownContent = $markdownContent . '------------------------------' . "\n" .
                    "<font color=\"warning\">" . "$warningTitle </font>\n" .
                    "> **$detailTitle** \n" .
                    "$text\n\n";

                saveWarning($item, $db);
            }

            $pushData = [
                'msgtype' => 'markdown',
                'markdown' => [
                    'content' => $markdownContent
                ]
            ];
            request_post("https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=$webhookKey", json_encode($pushData, '320'), 'json');
        }
    }
}

/**
 * @param $item
 * @param $db
 * @return void
 */
function saveWarning($item, $db): void
{
    $warningId = $item['id'];
    $record = $db->collection('WeatherWarningRecords')->where(['warningId' => $warningId])->count();
    if ($record['total'] == 0) {
        $db->collection('WeatherWarningRecords')->add([
            'warningId' => $warningId,
            'title' => $item['title'],
        ]);
    }
}

function init_db()
{

    // 初始化资源
    // 云函数下不需要secretId和secretKey。
    // env如果不指定将使用默认环境
    $tcb = new Tcb([
        'secretId' => "xxx",
        'secretKey' => "xxx",
        'env' => "xx-xx"
    ]);

    return $tcb->getDatabase();
}
