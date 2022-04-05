<?php
/**
 * 模拟get进行url请求
 * @param string $url
 * @return bool|string
 */
function httpGet($url) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_ENCODING,'gzip');

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

/**
 * 模拟post进行url请求
 * @param string $url
 * @param array $post_data
 * @param string $dataType
 * @return bool|mixed
 */
function request_post($url = '', $post_data = array(),$dataType='json') {
    if (empty($url) || empty($post_data)) {
        return false;
    }
    $curlPost = $post_data;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($dataType=='json'){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                'Content-Length: ' . strlen($curlPost)
            )
        );
    }
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    return curl_exec($ch);
}