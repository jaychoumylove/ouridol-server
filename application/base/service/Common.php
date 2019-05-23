<?php

namespace app\base\service;

use app\api\model\Cfg;
use app\base\model\Appinfo;

class Common
{
    /**接口数据返回 */
    public static function res($res)
    {
        if (isset($res['code'])) {
            $return['code'] = $res['code'];
        } else {
            $return['code'] = 0;
        }
        if (isset($res['msg'])) {
            $return['msg'] = $res['msg'];
        } else {
            $return['msg'] = CodeSign::getMsg($return['code']);
        }

        if (isset($res['data'])) {
            $return['data'] = $res['data'];
        } else {
            $return['data'] = [];
        }

        header('Access-Control-Allow-Origin:*');
        header('Content-Type:application/json');
        die(json_encode($return, JSON_UNESCAPED_UNICODE));
    }

    /**服务端基础curl */
    public static function request($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        if ($data) {
            //设置post方式提交
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $res = curl_exec($curl);
        curl_close($curl);

        $p_res = json_decode($res, true);
        if ($p_res) {
            return $p_res;
        } else {
            return $res;
        }
    }

    /**加密uid生成token */
    public static function setSession($uid)
    {
        $salt = Cfg::getCfg('salt');
        $token =  base64_encode($salt . base64_encode(time() . '&' . $_SERVER['REMOTE_ADDR'] . '&' . $uid));
        return $token;
    }

    /**解密token获得uid */
    public static function getSession($token)
    {
        $salt = Cfg::getCfg('salt');
        $code = base64_decode(str_replace($salt, '', base64_decode($token)));
        $arr = explode('&', $code);

        try {
            $time = $arr[0];
            $ip = $arr[1];
            $uid = $arr[2];

            if ($time < time() - 3600 * 2) {
                // token超过2小时表示已过期，请重新登录
                return false;
            } else if ($ip != $_SERVER['REMOTE_ADDR']) {
                // ip不一致
                return false;
            } else {
                return $uid;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**将array转换为xml */
    public static function toXml($data)
    {
        $xml = "<xml>";
        foreach ($data as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**将xml转换为array */
    public static function fromXml($xml)
    {
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }

    /**产生随机字符串 */
    public static function getRandCode($len = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $len; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取微信APP信息
     * @param string $w appid或type 默认miniapp
     */
    public static function getAppinfo($w)
    {
        if ($w) {
            $appinfo = Appinfo::get(['appid' => $w]);
            if (!$appinfo) $appinfo = Appinfo::get(['type' => $w]);
            if (!$appinfo) $appinfo = Appinfo::get(['type' => 'miniapp']);
        } else {
            $appinfo = Appinfo::get(['type' => 'miniapp']);
        }

        return $appinfo;
    }
}
