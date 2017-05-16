<?php
/**
 * User: hadenting
 * Date: 2017/5/11
 * Time: 15:03
 */

namespace hadenting\helpers;

use yii\helpers\BaseStringHelper;


/**
 * Class StringHelper
 * @package common\helpers
 */
class StringHelper extends BaseStringHelper
{
    /**
     * 生成GUID（UUID）
     * @access public
     * @return string
     * @author knight
     */
    public static function createGuid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
            return $uuid;
        }
    }

    /**
     * md5加密
     * @param $str
     * @return string
     */
    static public function MyMd5($str)
    {
        return strtolower(md5($str));
    }

    /**
     * 校验日期格式是否正确
     *
     * @param string $date 日期
     * @param string $formats 需要检验的格式数组
     * @return boolean
     */
    static public function checkDateIsValid($date, $formats = array("Y-m-d", "Y/m/d", "Y-n-j", "Y/n/j"))
    {
        $unixTime = strtotime($date);
        if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
            return false;
        }

        //校验日期的有效性，只要满足其中一个格式就OK
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }
        return false;
    }

    // 计算中文字符串长度
    static public function utf8_strlen($string = null)
    {
        // 将字符串分解为单元
        preg_match_all("/./us", $string, $match);
        // 返回单元个数
        return count($match[0]);
    }

    /**
     * 日期转换为用“x分钟前”显示
     * @param $time 时间戳
     * @return string
     */
    static function checktime($time)
    {
        $date1_stamp = time();
        $date2_stamp = $time;
        list($date_1['Y'], $date_1['m'], $date_1['d']) = explode("-", date('Y-m-d', $date1_stamp));
        list($date_1['H'], $date_1['i'], $date_1['s']) = explode(":", date('H:i:s', $date1_stamp));
        list($date_2['Y'], $date_2['m'], $date_2['d']) = explode("-", date('Y-m-d', $date2_stamp));
        list($date_2['H'], $date_2['i'], $date_2['s']) = explode(":", date('H:i:s', $date2_stamp));
        $year = $date_1['Y'] - $date_2['Y'];
        $month = $date_1['m'] - $date_2['m'];
        $day = $date_1['d'] - $date_2['d'];
        if ($year > 0) return $year . '年前';
        if ($month > 0) return $month . '月前';
        if ($day > 0) return $day . '天前';
        $time = $date1_stamp - $date2_stamp;
        if ($time > 3600) return (int)($time / 3600) . '小时前';
        if ($time > 60) return (int)($time / 60) . '分钟前';
        return '刚刚';
    }

    /**
     * 日期转年龄
     * @param $birthday
     * @return int
     */
    public static function age($birthday)
    {
        return (int)((time() - strtotime($birthday)) / (86400 * 365));
    }

    /**
     * 获取model的异常
     * @param $model \yii\base\Model
     * @return mixed
     */
    public static function GetModelFirstError($model)
    {
        $errors = $model->getFirstErrors();
        return current($errors);
    }

    /**
     * 生成订单号 x+ymdHis+xxx
     * @return string
     */
    static public function generateOrderCode()
    {
        $start = self::generateStr(1);
        $end = date('ymdHis');
        $ends = rand(100, 999);
        return $start . $end . $ends;
    }

    /**
     * 生成随机字符串
     * @param $length
     * @return string
     */
    static public function generateStr($length)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($str) - 1;
        $strs = '';
        for ($i = 0; $i < $length; $i++) {
            $strs .= $str[rand(0, $max)];
        }
        return $strs;
    }

    static public function foo(&$v, $k, $kname)
    {
        $v = array_combine($kname, array_slice($v, 0));
    }

    static public function explodeLocations($str)
    {
        $arr = explode('|', trim($str, '|'));
        array_walk($arr, function (&$val, $key) {
            $val = explode(',', $val);
        });
        return $arr;
    }

    static function gmt_iso8601($time)
    {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }
}