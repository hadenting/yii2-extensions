<?php
/**
 * User: hadenting
 * Date: 2017/5/16
 * Time: 9:33
 */

namespace hadenting\helpers;


class Validator
{
    /**
     * 是否为空值
     */
    public static function isEmpty($str)
    {
        $str = trim($str);
        return !empty($str) ? true : false;
    }

    /**
     * 数字验证
     * param:$flag : int是否是整数，float是否是浮点型
     */
    public static function isNum($str, $flag = 'float')
    {
        if (!self::isEmpty($str)) return false;
        if (strtolower($flag) == 'int') {
            return ((string)(int)$str === (string)$str) ? true : false;
        } else {
            return ((string)(float)$str === (string)$str) ? true : false;
        }
    }

    /**
     * 名称匹配，如用户名，目录名等
     * @param:string $str 要匹配的字符串
     * @param:$chinese 是否支持中文,默认支持，如果是匹配文件名，建议关闭此项（false）
     * @param:$charset 编码（默认utf-8,支持gb2312）
     */
    public static function isName($str, $chinese = true, $charset = 'utf-8')
    {
        if (!self::isEmpty($str)) return false;
        if ($chinese) {
            $pattern = (strtolower($charset) == 'gb2312') ? "/^[" . chr(0xa1) . "-" . chr(0xff) . "A-Za-z0-9_-]+$/" : "/^[x{4e00}-x{9fa5}A-Za-z0-9_]+$/u";
        } else {
            $pattern = '/^[A-za-z0-9_-]+$/';
        }
        return preg_match($pattern, $str) ? true : false;
    }

    /**
     * 邮箱验证
     */
    public static function isEmail($str)
    {
        if (!self::isEmpty($str)) return false;
        $pattern = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
        return preg_match($pattern, $str) ? true : false;
    }

    //手机号码验证
    public static function isMobile($str)
    {
        $pattern = "/^(13|15|18)[0-9]{9}$|14[57]{1}[0-9]{8}$/";
        return preg_match($pattern, $str) ? true : false;
    }

    /**
     * URL验证，纯网址格式，不支持IP验证
     */
    public static function isUrl($str)
    {
        if (!self::isEmpty($str)) return false;
        return preg_match('#(http|https|ftp|ftps)://([w-]+.)+[w-]+(/[w-./?%&=]*)?#i', $str) ? true : false;
    }

    /**
     * 验证中文
     * @param:string $str 要匹配的字符串
     * @param:$charset 编码（默认utf-8,支持gb2312）
     */
    public static function isChinese($str, $charset = 'utf-8')
    {
        if (!self::isEmpty($str)) return false;
        $pattern = (strtolower($charset) == 'gb2312') ? "/^[" . chr(0xa1) . "-" . chr(0xff) . "]+$/"
            : "/^[x{4e00}-x{9fa5}]+$/u";
        return preg_match($pattern, $str) ? true : false;
    }

    /**
     * UTF-8验证
     */
    public static function isUtf8($str)
    {
        if (!self::isEmpty($str)) return false;
        return (preg_match("/^([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){1}/", $word)
            == true || preg_match("/([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){1}$/", $word)
            == true || preg_match("/([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){2,}/", $word)
            == true) ? true : false;
    }

    /**
     * 验证长度
     * @param: string $str
     * @param: int $type(方式，默认min <= $str <= max)
     * @param: int $min,最小值;$max,最大值;
     * @param: string $charset 字符
     */
    public static function length($str, $type = 3, $min = 0, $max = 0, $charset = 'utf-8')
    {
        if (!self::isEmpty($str)) return false;
        $len = mb_strlen($str, $charset);
        switch ($type) {
            case 1: //只匹配最小值
                return ($len >= $min) ? true : false;
                break;
            case 2: //只匹配最大值
                return ($max >= $len) ? true : false;
                break;
            default: //min <= $str <= max
                return (($min <= $len) && ($len <= $max)) ? true : false;
        }
    }

    /**
     * 验证密码
     * @param string $value
     * @param int $length
     * @return boolean
     */
    public static function isPWD($value, $minLen = 6, $maxLen = 16)
    {
        $pattern = '/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{' . $minLen . ',' . $maxLen . '}$/';
        $v = trim($value);
        if (empty($v))
            return false;
        return preg_match($pattern, $v) ? true : false;
    }

    /**
     * 验证用户名
     * @param string $value
     * @param int $length
     * @return boolean
     */
    public static function isNames($value, $minLen = 2, $maxLen = 16, $charset = 'ALL')
    {
        if (empty($value))
            return false;
        switch ($charset) {
            case 'EN':
                $pattern = '/^[_\w\d]{' . $minLen . ',' . $maxLen . '}$/iu';
                break;
            case 'CN':
                $pattern = '/^[_\x{4e00}-\x{9fa5}\d]{' . $minLen . ',' . $maxLen . '}$/iu';
                break;
            default:
                $pattern = '/^[_\w\d\x{4e00}-\x{9fa5}]{' . $minLen . ',' . $maxLen . '}$/iu';
        }
        return preg_match($pattern, $value) ? true : false;
    }

    /**
     * 匹配日期
     * @param string $value
     */
    public static function checkDate($str)
    {
        $dateArr = explode("-", $str);
        if (is_numeric($dateArr[0]) && is_numeric($dateArr[1]) && is_numeric($dateArr[2])) {
            if (($dateArr[0] >= 1000 && $timeArr[0] <= 10000) && ($dateArr[1] >= 0 && $dateArr[1] <= 12) && ($dateArr[2] >= 0 && $dateArr[2] <= 31))
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * 匹配时间
     * @param string $value
     */
    public static function checkTime($str)
    {
        $timeArr = explode(":", $str);
        if (is_numeric($timeArr[0]) && is_numeric($timeArr[1]) && is_numeric($timeArr[2])) {
            if (($timeArr[0] >= 0 && $timeArr[0] <= 23) && ($timeArr[1] >= 0 && $timeArr[1] <= 59) && ($timeArr[2] >= 0 && $timeArr[2] <= 59))
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * 匹配手机号
     * @param $str
     * @return int 成功返回 1 ，否则返回 0 。
     */
    public static function isNumber($str)
    {
        return preg_match('/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/', $str) ? true : false;
    }
}