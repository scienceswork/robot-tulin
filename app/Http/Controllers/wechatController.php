<?php

namespace App\Http\Controllers;

use EasyWeChat\Message\News;
use Illuminate\Http\Request;
use Log;

class wechatController extends Controller
{
    /*微信客户端验证*/
    public function wechatValidate()
    {
        // 获得wechat对象实例
//        $wechat = app('wechat');
//        $wechat->server->setMessageHandler(function ($message) {
//            return '欢迎来到拓学路';
//        });
        // 进行验证并且返回response
//        return $wechat->server->serve();
        $app = app('wechat');
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // 获取用户的openid
            $openid = $message->FromUserName;
            // 获取消息创建的时间
            $time = $message->CreateTime;
            switch ($message->MsgType) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    $data = robotInfo($message->Content, $openid, $time);
                    // 判断返回类型
                    if ($data['code'] == 100000) {
                        // code为100000表示文本类型
                        return $data['text'];
                    } elseif ($data['code'] == 200000) {
                        // code为200000表示为链接类型
                        return $data['text'] . '：' . $data['url'];
                    } elseif ($data['code'] == 302000) {
                        // code为302000表示新闻类
                        $newsArr = [];
                        $length = count($data['list']);
                        for ($i = 0; $i < $length; $i++) {
                            $news = new News([
                                'title' => $data['list'][$i]['article'],
                                'description' => $data['list'][$i]['source'],
                                'image' => $data['list'][$i]['icon'],
                                'url' => $data['list'][$i]['detailurl']
                            ]);
                            $newsArr[] = $news;
                            return $newsArr;
                        }
                    } else {
                        return "这个问题太深奥了，机器人已经无能为力了~";
                    }
                    break;
                case 'image':
                    return $message->PicUrl;
                    break;
                case 'voice':
                    return '暂时处理不了语音信息哦~';
                    break;
                case 'video':
                    return '暂时处理不了视频信息哦~';
                    break;
                case 'location':
                    return '发个位置给我，想要约我么~';
                    break;
                case 'link':
                    return '呜呜~暂时打不开这个链接';
                    break;
                default:
                    return '收到您的消息';
                    break;
            }
        });
        return $server->serve();
    }

    public function getUsers()
    {
        $wechat = app('wechat');
        $userService = $wechat->user;
        $users = $userService->lists();
        return $users;
    }
}
