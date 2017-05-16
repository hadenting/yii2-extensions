<?php
/**
 * User: hadenting
 * Date: 2017/5/11
 * Time: 15:03
 */

namespace hadenting\helpers;

use yii\helpers\BaseArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class ArrayHelper
 * @package common\helpers
 */
class ArrayHelper extends BaseArrayHelper
{
    /**
     * 数组转对象
     * @param $array 数组
     * @return object|void
     */
    public static function arrayToObject($array)
    {
        if (gettype($array) != 'array') return;
        foreach ($array as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $array[$k] = (object)static::arrayToObject($v);
        }
        return (object)$array;
    }

    /**
     * 对象转数组
     * @param $e
     * @return array|void
     */
    public static function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array)static::objectToArray($v);
        }
        return $e;
    }

    /**
     * 返回JSON
     * @param int $code
     * @param string $message
     * @param array $data
     * @param bool|true $is_do_walk
     * @return mixed
     */
    public static function showResult($code = 200, $message = '', $data = [], $is_do_walk = true)
    {
        $result = [
            'status' => strval($code),
            'message' => $message,
        ];
        if (!empty($data)) {
            $result['data'] = $data;
        }
        return str_replace('":null', '":""', json_encode($result));
    }

    /**
     * 返回异常json数组
     * @param int $status
     * @param string $message
     * @return array
     */
    public static function JsonError($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message
        ];
    }

    static public function cut_utf8str($source, $length)
    {
        $return_str = '';
        $i = 0;
        $n = 0;
        $strlength = strlen($source);
        while (($n < $length) && ($i < $strlength)) {
            $tem_str = substr($source, $i, 1);
            $ascnum = ord($tem_str);
            if ($ascnum >= 224) {
                $return_str .= substr($source, $i, 3);
                $i += 3;
                $n++;
            } elseif ($ascnum >= 192) {
                $return_str .= substr($source, $i, 2);
                $i += 2;
                $n++;
            } elseif ($ascnum >= 65 && $ascnum <= 90) {
                $return_str .= substr($source, $i, 1);
                $i += 1;
                $n++;
            } else {
                $return_str .= substr($source, $i, 1);
                $i += 1;
                $n += 0.5;
            }
        }
        if ($strlength > $length) {
            $return_str .= '...';
        }
        return $return_str;
    }

    /**
     * curl get 请求
     * @param $url
     * @return mixed
     */
    public static function getRequire($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * @param $lat
     * @param $lng
     * @return string
     * 数据库计算经纬度
     */
    public static function getDistinces($lat1, $lng1, $lat2, $lng2)
    {
        return '6378.138*2*ASIN(SQRT(POW(SIN((' . $lat1 . '*PI()/180-' . $lat2 . '*PI()/180)/2),2)+COS(' . $lat1 . '*PI()/180)*COS(' . $lat2 . '*PI()/180)*POW(SIN((' . $lng1 . '*PI()/180-' . $lng2 . '*PI()/180)/2),2)))*1000';
    }
}