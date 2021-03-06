<?php

if (!function_exists('getIntranetIP')) {
	/**
	 * 获取本地机子内网ip
	 * @return array
	 */
	function getIntranetIP()
	{
		return gethostbynamel(exec("hostname"));
	}
}

if (!function_exists('is_json')) {
	/**
	 * 判断数据是合法的json数据: (PHP版本大于5.3)
	 *
	 * @param $string
	 * @return bool
	 *
	 */
	function is_json($string)
	{
		json_decode($string);

		return (json_last_error() == JSON_ERROR_NONE);
	}
}
if (!function_exists('is_not_json')) {
    /**
     * 判断数据不是JSON格式
     *
     * @param $str
     * @return bool
     *
     */
    function is_not_json($str)
    {
        return !is_json($str);
    }
}

if (!function_exists('create_qr_code')) {
    /**
     * 利用google api生成二维码图片
     *
     * @param string $content 二维码内容参数
     * @param string $size 生成二维码的尺寸，宽度和高度的值
     * @param string $lev 可选参数，纠错等级
     * @param string $margin 生成的二维码离边框的距离
     * @return string
     *
     */
    function create_qr_code($content, $size = '200', $lev = 'L', $margin = '0')
    {
        $content = urlencode($content);
        return '<img src="http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&amp;cht=qr&chld=' . $lev . '|' . $margin . '&amp;chl=' . $content . '"  widht="' . $size . '" height="' . $size . '" />';
    }
}

if (!function_exists('is_https')) {
    /**
     * 判断是不是https
     *
     * @create 2019-01-29 12:04:26
     * @return bool
     *
     */
    function is_https()
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }

        return false;
    }
}

if (!function_exists('get_curl_data')) {
    /**
     * 请求远程数据
     *
     * @param string $url
     * @param array $param
     * @return mixed
     *
     */
    function get_curl_data($url, $param = [])
    {
        // 创建一个cURL资源
        $ch = curl_init();

        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//绕过ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($param)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        }
        // 抓取URL并把它传递给浏览器
        $res = curl_exec($ch);
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);

        return $res;
    }
}

if (!function_exists('imgToBase64')) {
    /**
     * 获取图片的Base64编码(不支持url)
     *
     * @date 2017-02-20 19:41:22
     * @param string $img_file 传入本地图片地址
     * @return string
     *
     */
    function imgToBase64($img_file)
    {

        $img_base64 = '';
        if (file_exists($img_file)) {
            $app_img_file = $img_file; // 图片路径
            $img_info     = getimagesize($app_img_file); // 取得图片的大小，类型等

            //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
            $fp = fopen($app_img_file, "r"); // 图片是否可读权限

            if ($fp) {
                $filesize     = filesize($app_img_file);
                $content      = fread($fp, $filesize);
                $file_content = chunk_split(base64_encode($content)); // base64编码

                switch ($img_info[2]) {
                    //判读图片类型

                    case 1:
                        $img_type = "gif";
                        break;
                    case 2:
                        $img_type = "jpg";
                        break;
                    case 3:
                        $img_type = "png";
                        break;
                    case 17:
                        $img_type = "x-icon";
                        break;
                    default :
                        return null;
                }
                $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

            }
            fclose($fp);
        }

        return $img_base64; //返回图片的base64
    }
}

if (!function_exists('isMobile')) {
    /**
     *移动端判断
     *
     * @return bool
     *
     */
    function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile',
            ];
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if (
                (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos(
                        $_SERVER['HTTP_ACCEPT'],
                        'text/html'
                    ) === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos(
                            $_SERVER['HTTP_ACCEPT'],
                            'text/html'
                        )))
            ) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('git_second')) {
    /**
     * 输入字符串的时：分：秒 返回秒数
     *
     * @param int $time
     * @return string
     *
     */
    function git_second($time)
    {
        $h = substr($time, -8, 2) * 3600;
        $m = substr($time, -5, 2) * 60;
        $s = substr($time, -2, 2);
        return $h + $m + $s;
    }
}

if (!function_exists('diffBetweenTwoDays')) {
    /**
     * 获取日期天数差。
     *
     * @param string $day1
     * @param string $day2
     * @return string
     *
     */
    function diffBetweenTwoDays($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp     = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }

        return ($second1 - $second2) / 86400;
    }
}

if (!function_exists('get_time')) {
    /**
     * 获取时间，采用标准时区。
     *
     * @return string
     *
     */
    function get_time()
    {
        date_default_timezone_set('Asia/Shanghai');

        return date("Y-m-d H:i:s");
    }
}

if (!function_exists('get_date')) {
    /**
     * 将时间戳 格式化 获取日期
     *
     * @param int $time
     * @return string
     *
     */
    function get_date($time)
    {
        date_default_timezone_set('PRC');

        return date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - (int)$time, date("y")));
    }
}

if (!function_exists('get_date_str')) {
    /**
     * 将时间戳 格式化 获取日期
     *
     * @param int $time
     * @param int $time_str
     * @return string
     *
     */
    function get_date_str($time, $time_str)
    {
        date_default_timezone_set('PRC');

        return date("Y-m-d", strtotime('-' . $time . ' days', strtotime($time_str)));
    }
}

if (!function_exists('get_distance')) {
    /**
     * 计算两点地理坐标之间的距离
     *
     * @param float $longitude1 起点经度
     * @param float $latitude1 起点纬度
     * @param float $longitude2 终点经度
     * @param float $latitude2 终点纬度
     * @param Int $unit 单位 1:米 2:公里
     * @param Int $decimal 精度 保留小数位数
     * @return float
     */
    function get_distance($longitude1, $latitude1, $longitude2, $latitude2, $unit = 2, $decimal = 10)
    {

        $EARTH_RADIUS = 6370.996; // 地球半径系数

        $radLat1 = $latitude1 * M_PI / 180.0;
        $radLat2 = $latitude2 * M_PI / 180.0;

        $radLng1 = $longitude1 * M_PI / 180.0;
        $radLng2 = $longitude2 * M_PI / 180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $distance = $distance * $EARTH_RADIUS;

        if ($unit == 1) {
            $distance = $distance * 1000;
        }

        return round($distance, $decimal);
    }
}

if (!function_exists('is_date')) {
    /**
     * 判断是否为日期格式
     *
     * @param string $time 时间字符串
     * @return boolean
     */
    function is_date($time)
    {
        return strtotime($time);
    }
}

if (!function_exists('getMillisecond')) {
    /**
     * 获取毫秒级别的时间戳
     *
     *
     */
    function getMillisecond()
    {
        //获取毫秒的时间戳
        $time  = explode(" ", microtime());
        $time  = $time[1] . ($time[0] * 1000);
        $time2 = explode(".", $time);
        $time  = $time2[0];

        return $time;
    }
}

if (!function_exists('get_date_second')) {
    /**
     * 把秒换成时分秒
     *
     * @create 2019-01-24 15:21:21
     * @param $seconds
     * @return float|int|string
     *
     */
    function get_date_second($seconds)
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds - $h * 3600) / 60);
        $s = ($seconds - $h * 3600 - $m * 60);
        $m = $m >= 10 ? $m : '0' . $m;
        $s = $s >= 10 ? $s : '0' . $s;
        $s = $h . ':' . $m . ':' . $s;

        return $s;
    }
}

if (!function_exists('get_date_after')) {
    /**
     * 将时间戳 格式化 获取日期
     *
     * @param int $time
     * @return string
     *
     */
    function get_date_after($time)
    {
        date_default_timezone_set('PRC');

        return date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + (int)$time, date("y")));
    }
}

if (!function_exists('check_phone')) {
    /**
     * 检查数据的参数是不是电话参数。
     *
     * @param string $str
     * @return string
     *
     */
    function check_phone($str)
    {
        if (empty($str)) {
            return true;
        }

        return preg_match(
            "/^((\(\d{2,3}\))|(\d{3}[\-]?))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}([\-]?\d{1,4})?$/",
            $str
        );
    }
}

if (!function_exists('check_mail')) {
    /**
     * 检查数据的参数是不是邮箱参数。
     *
     * @param string $str
     * @return string
     *
     */
    function check_mail($str)
    {
        if (empty($str)) {
            return true;
        }

        return preg_match("/^[A-Z_a-z0-9-.]+@([A-Z_a-z0-9-]+\.)+[a-z0-9A-Z]{2,4}$/", $str);
    }
}

if (!function_exists('check_int')) {
    /**
     * 检查数据的参数是不是数字参数。
     *
     * @param string $str
     * @return string
     *
     */
    function check_int($str)
    {
        if (empty($str)) {
            return true;
        }

        return preg_match("/^\d+$/", $str);
    }
}

if (!function_exists('get_cn')) {
    /**
     * 正则提取中文
     *
     * @param string $str
     * @return string
     *
     */
    function get_cn($str)
    {
        preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $str, $matches);
        return join('', $matches[0]);
    }
}

if (!function_exists('get_int')) {
    /**
     * 正则提取数字
     *
     * @param string $str
     * @return string
     *
     */
    function get_int($str)
    {
        preg_match_all('/[\d]+/u', $str, $matches);
        return join('', $matches[0]);
    }
}

if (!function_exists('get_from_ip')) {
    /**
     * 获取ip
     *
     * @return string
     *
     */
    function get_from_ip()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}

if (!function_exists('sign')) {
    /**
     *  签名
     *
     * @param $params
     * @param $secret_key
     * @return string
     *
     * @author jiangbingjie<jiangbinjie@i3020.com>
     */
    function sign(array $params, $secret_key)
    {
        unset($params['sign']);
        ksort($params);
        $params['secret_key'] = $secret_key;
        return md5(http_build_query($params));
    }
}

if (!function_exists('desensitize')) {
    /**
     * 对字符串脱敏
     * @param $string
     * @param int $start
     * @param int $length
     * @param string $re
     * @return string
     */
    function desensitize($string, $start = 0, $length = 0, $re = '*')
    {
        if (empty($string) || empty($length) || empty($re)) return $string;
        $end     = $start + $length;
        $strlen  = mb_strlen($string);
        $str_arr = array();
        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < $end)
                $str_arr[] = $re;
            else
                $str_arr[] = mb_substr($string, $i, 1);
        }
        return implode('', $str_arr);
    }
}

if (!function_exists('is_serialized')) {
    /**
     * 判断是否虚拟化数据
     * @param $data
     * @return bool
     */
    function is_serialized($data)
    {
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        if (!preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }

        return false;
    }
}

if (!function_exists('get_uuid')) {
    /**
     * 获取唯一id
     *
     * @param string $prefix
     * @return string
     */
    function get_uuid($prefix = '')
    {
        return uniqid($prefix, true);
    }
}

if (!function_exists('guid')) {
    /**
     * 返回guid
     * @return string
     */
    function guid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $char_id = strtoupper(md5(uniqid(rand(0, getrandmax()), true)));
            $hyphen  = chr(45);
            return substr($char_id, 0, 8) . $hyphen
                . substr($char_id, 8, 4) . $hyphen
                . substr($char_id, 12, 4) . $hyphen
                . substr($char_id, 16, 4) . $hyphen
                . substr($char_id, 20, 12);
        }
    }
}

if (!function_exists('transformTime')) {
    /**
     * 时间戳换算成距今时间
     *
     * @param int $time
     * @return string
     *
     */
    function transformTime($time)
    {
        if (!is_numeric($time)) {
            return '必须是数字';
        }
        $t = time() - $time;
        if ($t <= 0) {
            return '1秒前';
        }
        $f = [
            '31536000' => '年',
            '2592000'  => '月',
            '604800'   => '星期',
            '86400'    => '天',
            '3600'     => '小时',
            '60'       => '分钟',
            '1'        => '秒',
        ];
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v . '前';
            }
        }

        return '错误';
    }
}

if (!function_exists('timeFormat')) {
    /**
     * 时间标准格式化
     *
     * @param int $timestamp
     * @param string $format
     * @return array
     */
    function timeFormat($timestamp = 0, $format = 'Y-m-d H:i:s')
    {
        if (!$timestamp || !is_numeric($timestamp)) {
            return [
                'type'       => 'unix_timestamp',
                'value'      => 0,
                'alias'      => '-',
                'amaze_time' => '',
            ];
        }

        return [
            'type'       => 'unix_timestamp',
            'value'      => $timestamp,
            'alias'      => date($format, $timestamp),
            'amaze_time' => transformTime($timestamp),
        ];
    }
}

if (!function_exists('pack_input_params')) {
    /**
     * 过滤字符串单双引号
     *
     * @param string $str 用逗号分隔的多个参数
     * @return mixed
     *
     */
    function pack_input_params($str)
    {
        if (empty($str)) {
            return $str;
        }

        return strip_quotes(trim($str));
    }
}

if (!function_exists('replace_special_char')) {
    /**
     * 过滤非法字符
     * replace_special_char
     *
     * @param $strParam
     * @return null|string|string[]
     *
     */
    function replace_special_char($strParam)
    {
        $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\（|\）|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\||\s+/";

        return preg_replace($regex, "_", $strParam);
    }
}

if (!function_exists('array_to_object')) {
    /**
     * 数组转为对象
     *
     * @param array $e
     * @return mixed
     *
     */
    function array_to_object($e)
    {
        if (gettype($e) != 'array') {
            return null;
        }
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object') {
                $e [$k] = (object)array_to_object($v);
            }
        }

        return (object)$e;
    }
}

if (!function_exists('object_to_array')) {
    /**
     * 对象转为数组
     *
     * @param  $obj
     * @return array
     *
     */
    function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val        = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
            $arr [$key] = $val;
        }
        if (!isset($arr)) {
            $arr = [];
        }

        return $arr;
    }
}

if (!function_exists('array_filter_null')) {
    /**
     * 过滤数组null值
     *
     * @param array $data
     * @return array
     *
     */
    function array_filter_null(array $data)
    {
        foreach ($data as $k => $v) {
            if ($v == null) {
                unset($data[$k]);
            }
        }

        return $data;
    }
}

if (!function_exists('get_good_str')) {
    /**
     * 获取字符过滤 用反斜线转义字符串
     *
     * @param string $ary
     * @return string
     *
     */
    function get_good_str($ary)
    {
        if (empty($ary)) {
            return null;
        }

        if (is_numeric($ary)) {
            $ary = trim($ary);

            return $ary;
        }

        if (is_string($ary)) {
            if (!is_null(json_decode($ary))) {
                return $ary;
            }

            return addslashes(strip_tags(trim($ary)));
        }

        return $ary;
    }
}

if (!function_exists('strip_quotes')) {
    /**
     * 从字符串中移除单引号和双引号
     *
     * @param string $str
     * @return    string
     *
     */
    function strip_quotes($str)
    {
        return str_replace(['"', "'"], '', $str);
    }
}

if (!function_exists('get_ids')) {
    /**
     * 获取符合mysql IN的 id数组
     * get_ids
     *
     * @param $array
     * @param $value
     * @param $key
     * @return array
     *
     */
    function get_ids($array, $value, $key = null)
    {
        return array_values(array_unique(array_filter(array_column($array, $value, $key))));
    }
}

if (!function_exists('calculate_summation')) {
    /**
     * 合计
     * calculate_summation
     *
     * @param array $data //原始数据
     * @param $field //需要计算的字段
     * @return array
     *
     */
    function calculate_summation(array $data, $field)
    {
        $field = explode(',', $field);
        $total = [];
        //初始值
        foreach ($field as $v) {
            $total[$v] = 0;
        }

        foreach ($data as $k => $v) {
            foreach ($total as $key => $vv) {
                $total[$key] += $v[$key];
            }
        }

        return $total;
    }
}

if (!function_exists('array_sort_tag')) {
    /***
     * 数组排序(两个参数)
     *
     * @param $arr
     * @param $key1
     * @param string $type1
     * @param $key2
     * @param string $type2
     * @return array
     */
    function array_sort_tag($arr, $key1, $type1, $key2, $type2)
    {
        $arr = array_sort($arr, $key1, $type1);
        $arr = array_values($arr);

        $key1name = $arr[0][$key1];
        $temp     = [];
        $i        = 0;
        $length   = count($arr);
        foreach ($arr as $k => $v) {
            if ($v[$key1] == $key1name) {
                $temp[] = $arr[$k];
                if ($k == $length - 1) {
                    $temp = array_sort($temp, $key2, $type2);
                    $temp = array_values($temp);
                    foreach ($temp as $key => $val) {
                        $arr[$i] = $val;
                        $i++;
                    }
                }
            } else {
                $temp = array_sort($temp, $key2, $type2);
                $temp = array_values($temp);
                foreach ($temp as $key => $val) {
                    $arr[$i] = $val;
                    $i++;
                }
                $key1name = $v[$key1];
                $temp     = [];
                $temp[]   = $arr[$k];
            }
        }

        return $arr;
    }
}

if (!function_exists('array_sort')) {
    /***
     * 排序
     *
     * @param $arr
     * @param $keys
     * @param string $type
     * @return array
     */
    function array_sort($arr, $keys, $type = 'desc')
    {
        $keys_value = $new_array = [];
        foreach ($arr as $k => $v) {
            $keys_value[$k] = $v[$keys];//把所有该键值存到$keys_value
        }
        if ($type == 'asc') {
            asort($keys_value);//对键值排序 $k不动
        } else {
            arsort($keys_value);
        }
        reset($keys_value);
        foreach ($keys_value as $k => $v) {
            $new_array[$k] = $arr[$k]; //取出该键值下的其他的值
        }

        return $new_array;
    }
}

if (!function_exists('paging')) {
    /**
     * 分页
     * paging
     *
     * @create 2018-11-30 20:31:15
     * @param $data
     * @param $limit
     * @return array
     *
     */
    function paging($data, $limit)
    {
        if (empty($data) or !is_array($data)) {
            return [];
        }
        $result = [];
        for ($i = 0; $i < ceil(count($data) / $limit); $i++) {
            for ($n = 0; $n < $limit; $n++) {
                $num = ($i * $limit) + $n;
                if (isset($data[$num])) {
                    $result[$i][] = $data[$num];
                }
            }
        }

        return $result;
    }
}

if (!function_exists('my_rmdir')) {
    /**
     * 递归删除目录及目录下的所有文件
     * my_rmdir
     *
     * @param string $path
     *
     */
    function my_rmdir($path)
    {
        $op = dir($path);
        while (false != ($item = $op->read())) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            $path = (string)$op->path . '/' . $item;
            if (is_dir($path)) {
                my_rmdir($path);
                rmdir($path);
            } else {
                unlink($path);
            }
        }
    }
}

if (!function_exists('week')) {
    /**
     * 获取本周第一天的当前时间戳
     * week
     *
     * @create 2018-12-12 14:42:00
     * @param string $format
     * @return false|int
     *
     */
    function week($format = '')
    {
        $w    = date('w');
        $week = $w == 0 ? 'last week ' : 'this week ';

        return strtotime($week . $format);
    }
}

if (!function_exists('get_full_hour_time')) {
    /**
     * 获取一天内的24小时
     *
     * @create 2019-02-18 12:48:56
     * @param string $start_hour
     * @param string $end_hour
     * @return array
     */
    function get_full_hour_time($start_hour = '00', $end_hour = '23')
    {
        $data = [];
        $i    = 0;
        date_default_timezone_set('Asia/Shanghai');
        echo $begin = git_second($start_hour . ':00:00');
        echo ':';
        echo $end = git_second($end_hour . ':59:59');

        while (true) {
            if ($end < $begin) {
                break;
            }
            $data[$i]['date']  = explode(':', get_date_second($end))[0];
            $data[$i]['start'] = $end - 3599;
            $data[$i]['end']   = $end;

            $end = strtotime("-1 hour", $end);
            $i++;
        }

        return $data;
    }
}

if (!function_exists('get_full_day_time')) {
    /**
     * 获取某段时间内完整自然天的时间戳
     *
     * @create 2019-02-18 12:48:56
     * @param int $begin
     * @param int $end
     * @return array
     *
     */
    function get_full_day_time($begin, $end)
    {
        $data = [];
        $i    = 0;
        while (true) {
            if ($end < $begin) {
                break;
            }
            $data[$i]['date']  = date('Y-m-d', $end);
            $data[$i]['start'] = strtotime(date('Y-m-d', $end) . ' 00:00:00');
            $data[$i]['end']   = strtotime(date('Y-m-d', $end) . ' 23:59:59');

            $end = strtotime("-1 day", $end);
            $i++;
        }

        return $data;
    }
}

if (!function_exists('get_full_week_time')) {
    /**
     *  获取某段时间内完整自然周的时间戳
     *
     * @param int $start_time
     * @param int $end_time
     * @return array
     */
    function get_full_week_time($start_time, $end_time)
    {
        $end  = $end_time;
        $data = [];
        while (true) {
            if (date('w', $end) == 0) {
                $begin = strtotime("-1 week", $end) + 1;
                if (date('Y-m-d', $begin) >= date('Y-m-d', $start_time)) {
                    $data[] = ['start_time' => $begin, 'end_time' => $end];
                }
                if (($begin - $start_time) < 7 * 24 * 60 * 60) //剩下的时间少于7天
                {
                    break;
                }
                $end = strtotime("-1 week", $end);
            } else {
                $end = strtotime("-1 day", $end);
                if (date('Y-m-d', $end) <= date('Y-m-d', $start_time)) {
                    break;
                }
            }
        }

        return $data;
        //$array_count = count($data);
        /*if ($array_count) {
            return ['start_time' => $data[$array_count - 1]['start_time'], 'end_time' => $data[0]['end_time']];
        }
        return ['start_time' => 0, 'end_time' => 0];*/
    }
}

if (!function_exists('get_full_month_time')) {
    /**
     * 获取某段时间内完整自然月的时间戳
     *
     * @param $start_time
     * @param $end_time
     * @return array
     */
    function get_full_month_time($start_time, $end_time)
    {
        $end  = strtotime(date('Y-m-d 23:59:59', $end_time));
        $data = [];
        while (true) {
            if (date('Y-m-t', $end) == date('Y-m-d', $end)) {
                $begin = strtotime(date('Y-m-01 00:00:00', $end));
                if (date('Y-m-d', $begin) >= date('Y-m-d', $start_time)) {
                    $data[] = ['start_time' => $begin, 'end_time' => $end];
                }
                if (date('Y-m', $start_time) == date('Y-m', $begin)) //取完了最后一个月的数据
                {
                    break;
                }
                $begin = strtotime('-1 month', strtotime(date('Y-m-01 00:00:00', $end)));
                $end   = strtotime(date('Y-m-t', $begin)) + 86399;
            } else {
                $end = strtotime("-1 day", $end);
                if (date('Y-m-d', $end) <= date('Y-m-d', $start_time)) {
                    break;
                }
            }
        }

        return $data;
    }
}

if (!function_exists('generate_tree')) {
    /**
     * 无限极非递归生成树
     *
     * @param array $items 例如：[1=>['id' => 1, 'pid' => 0, 'name' => 'AA'],2=>['id' => 2, 'pid' => 1, 'name' => 'BB']] key值必须和id值保持一致
     * @return array
     */
    function generate_tree($items)
    {
        $tree = [];
        foreach ($items as $item) {
            if (isset($items[$item['pid']])) {
                $items[$item['pid']]['son'][] = &$items[$item['id']];
            } else {
                $tree[] = &$items[$item['id']];
            }
        }

        return $tree;
    }
}

if (!function_exists('add_tree')) {
    /**
     * 动态添加数据
     *
     * @param array $items 例如：[1=>['id' => 1, 'pid' => 0, 'name' => 'AA'],2=>['id' => 2, 'pid' => 1, 'name' => 'BB']] key值必须和id值保持一致
     * @param int $pid
     * @param string $name
     */
    function add_tree(&$items, $pid, $name)
    {
        $num = count($items) + 1;
        if ($num == 0) {
            $num = 0;
        }
        $items[$num] = [
            'id'   => $num,
            'pid'  => $pid,
            'name' => $name,
        ];
    }
}

if (!function_exists('is_cli')) {

    function is_cli()
    {
        return PHP_SAPI === 'cli';
    }

}

if (!function_exists('validation_filter_id_card')) {
    /**
     * 检测身份证是否合法
     * @param $id_card
     * @return bool
     */
    function validation_filter_id_card($id_card)
    {
        if (strlen($id_card) == 18) {
            return id_card_checksum18($id_card);
        } elseif ((strlen($id_card) == 15)) {
            return id_card_checksum18(id_card_15to18($id_card));
        } else {
            return false;
        }
    }
}

if (!function_exists('id_card_verify_number')) {
    /**
     * 计算身份证校验码，根据国家标准GB 11643-1999
     * @param $id_card_base
     * @return false|string
     */
    function id_card_verify_number($id_card_base)
    {
        if (strlen($id_card_base) != 17) {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum           = 0;
        for ($i = 0; $i < strlen($id_card_base); $i++) {
            $checksum += substr($id_card_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        return $verify_number_list[$mod];
    }
}

if (!function_exists('id_card_15to18')) {
    /**
     * 将15位身份证升级到18位
     * @param $id_card
     * @return false|string
     */
    function id_card_15to18($id_card)
    {
        if (strlen($id_card) != 15) {
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($id_card, 12, 3), array('996', '997', '998', '999')) !== false) {
                $id_card = substr($id_card, 0, 6) . '18' . substr($id_card, 6, 9);
            } else {
                $id_card = substr($id_card, 0, 6) . '19' . substr($id_card, 6, 9);
            }
        }
        $id_card = $id_card . id_card_verify_number($id_card);
        return $id_card;
    }
}

if (!function_exists('id_card_checksum18')) {
    /**
     * 18位身份证校验码有效性检查
     * @param $id_card
     * @return bool
     */
    function id_card_checksum18($id_card)
    {
        if (strlen($id_card) != 18) {
            return false;
        }
        $idcard_base = substr($id_card, 0, 17);
        if (id_card_verify_number($idcard_base) != strtoupper(substr($id_card, 17, 1))) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('deleteBOM')) {
    /**
     * 删除BOM
     *
     * @param $value
     * @return string
     */
    function deleteBOM($value)
    {
        return trim($value, chr(239) . chr(187) . chr(191));
    }
}

if (!function_exists('stringParser')) {
    /**
     * 字符串分析器
     * @param $string
     * @param $replacer
     * @return string|string[]
     * @author carlo<284474102@qq.com>
     */
    function stringParser($string, $replacer)
    {
        return str_replace(array_keys($replacer), array_values($replacer), $string);
    }
}

if (!function_exists('get_dir')) {
    /**
     * 获取文件夹内容
     * @param $path
     * @return array
     */
    function get_dir($path)
    {
        $arr = [];
        if (is_dir($path)) {
            $data = scandir($path);
            foreach ($data as $value) {
                if ($value != '.' && $value != '..') {
                    $sub_path = $path . "/" . $value;
                    if (is_dir($sub_path)) {
                        $temp = get_dir($sub_path);
                        $arr  = array_merge($temp, $arr);
                    } else {
                        $arr[] = $sub_path;
                    }
                }
            }
        }
        return $arr;
    }
}

if (!function_exists('paging')) {
    /**
     * 数组分页
     * @param int $page
     * @param int $perPage
     * @param array $array
     * @param string $order
     * @return array
     * @author carlo<284474102@qq.com>
     */
    function paging($page, $perPage, array $array, $order = 'asc')
    {
        $page    = (empty($page)) ? 1 : $page;
        $perPage = (empty($perPage)) ? 10 : $perPage;
        $start   = ($page - 1) * $perPage;

        if ($order == 'desc') {
            $array = array_reverse($array);
        }
        return array_slice($array, $start, $perPage);
    }
}
