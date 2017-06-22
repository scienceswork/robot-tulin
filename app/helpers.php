<?php

/*回复文本消息*/
if (!function_exists('robotInfo')) {
    /**
     * 回复文本消息，传入文本
     * @param $message string 文本消息
     * @param $openid string 用户的openid
     * @param $time string 时间
     * @return mixed
     */
    function robotInfo($message, $openid, $time)
    {
        // 获得GuzzleHttp客户端，用来模拟发送请求
        $client = new \GuzzleHttp\Client();
        // 组装发送数据
        $data = [
            'key' => config('robot.key'),
            'info' => $message,
            'userid' => $openid
        ];
        // 发送post请求
        $res = $client->request('POST', config('robot.api'), [
            'json' => $data
        ]);
        // 获得返回的消息
        $result = json_decode($res->getBody(), true);
        $result['message'] = $message;
        $result['time'] = $time;
        $result['openid'] = $openid;
        // 将聊天结果存入数据中
        $file = storage_path('data/' . date('Y-m-d', time()) . '.txt');
        file_put_contents($file, json_encode($result) . PHP_EOL, FILE_APPEND);
        return $result;
    }
}