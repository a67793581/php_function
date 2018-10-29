<?php

if (!function_exists('is_json')) {
    /**
     * 判断数据是合法的json数据: (PHP版本大于5.3)
     * @param $string
     * @return bool
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
     * @param $str
     * @return bool
     */
    function is_not_json($str)
    {
        return is_null(json_decode($str));
    }
}

if (!function_exists('create_erweima')) {
    /**
     * 利用google api生成二维码图片
     * @param string $content 二维码内容参数
     * @param string $size 生成二维码的尺寸，宽度和高度的值
     * @param string $lev 可选参数，纠错等级
     * @param string $margin 生成的二维码离边框的距离
     * @return string
     */
    function create_erweima($content, $size = '200', $lev = 'L', $margin = '0')
    {
        $content = urlencode($content);
        $image = '<img src="http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&amp;cht=qr&chld=' . $lev . '|' . $margin . '&amp;chl=' . $content . '"  widht="' . $size . '" height="' . $size . '" />';
        return $image;
    }
}

if (!function_exists('get_curl_data')) {
    /**
     * 请求远程数据
     * @param string $url
     * @param array $param
     * @return mixed
     */
    function get_curl_data($url, $param = array())
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
     * @date 2017-02-20 19:41:22
     *
     * @param string $img_file 传入本地图片地址
     *
     * @return string
     */
    function imgToBase64($img_file)
    {

        $img_base64 = '';
        if (file_exists($img_file)) {
            $app_img_file = $img_file; // 图片路径
            $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

            //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
            $fp = fopen($app_img_file, "r"); // 图片是否可读权限

            if ($fp) {
                $filesize = filesize($app_img_file);
                $content = fread($fp, $filesize);
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
     * @return bool
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
            $clientkeywords = array('nokia',
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
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('git_second')) {
    /**
     * 输入字符串的时：分：秒 返回秒数
     * @param int $time
     * @return string
     */
    function git_second($time)
    {
        $h = substr($time, -8, 2) * 3600;
        $m = substr($time, -5, 2) * 60;
        $s = substr($time, -2, 2);
        $res = $h + $m + $s;
        return $res;
    }
}

if (!function_exists('diffBetweenTwoDays')) {
    /**
     * 获取日期天数差。
     * @param string $day1
     * @param string $day2
     * @return string
     */
    function diffBetweenTwoDays($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }
}

if (!function_exists('get_time')) {
    /**
     * 获取时间，采用标准时区。
     * @return string
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
     * @param int $time
     * @return string
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
     * @param int $time
     * @param int $time_str
     * @return string
     */
    function get_date_str($time, $time_str)
    {
        date_default_timezone_set('PRC');
        return date("Y-m-d", strtotime('-' . $time . ' days', strtotime($time_str)));
    }
}

if (!function_exists('get_uuid')) {
    /**
     * 获取唯一id
     * @return string
     */
    function get_uuid()
    {
        return md5(uniqid(md5(microtime(true)), true));
    }
}

if (!function_exists('getMillisecond')) {
    /**
     * 获取毫秒级别的时间戳
     */
    function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode(" ", microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        return $time;
    }
}

if (!function_exists('get_date_after')) {
    /**
     * 将时间戳 格式化 获取日期
     * @param int $time
     * @return string
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
     * @param string $str
     * @return string
     */
    function check_phone($str)
    {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^((\(\d{2,3}\))|(\d{3}[\-]?))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}([\-]?\d{1,4})?$/", $str);
    }
}

if (!function_exists('check_mail')) {
    /**
     * 检查数据的参数是不是邮箱参数。
     * @param string $str
     * @return string
     */
    function check_mail($str)
    {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^[A-Z_a-z0-9-\.]+@([A-Z_a-z0-9-]+\.)+[a-z0-9A-Z]{2,4}$/", $str);
    }
}

if (!function_exists('check_int')) {
    /**
     * 检查数据的参数是不是数字参数。
     * @param string $str
     * @return string
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
     * @param string $str
     * @return string
     */
    function get_cn($str)
    {
        preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $str, $matches);
        $cn = join('', $matches[0]);
        return $cn;
    }

}

if (!function_exists('get_int')) {
    /**
     * 正则提取数字
     * @param string $str
     * @return string
     */
    function get_int($str)
    {
        preg_match_all('/[\d]+/u', $str, $matches);
        $int = join('', $matches[0]);
        return $int;
    }
}

if (!function_exists('get_from_ip')) {
    /**
     * 获取ip
     * @return string
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
     * @return string
     * @author jiaozi<jiaozi@iyenei.com>
     *
     */
    function sign($params)
    {
        ksort($params);
        $sign = '';
        foreach ($params as $key => $val) {
            $sign .= $key . $val;
        }
        $sign .= 'keysecret' . $this->appSecret;
        $sign = md5($sign);
        return $sign;
    }
}

if (!function_exists('transformTime')) {
    /**
     * 时间戳
     * @param int $time
     * @return string
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
            '2592000' => '月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
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
// 时间标准格式化
    function timeFormat($timestamp = 0, $format = 'Y-m-d H:i:s')
    {
        if (!$timestamp || !is_numeric($timestamp)) {
            return [
                'type' => 'unix_timestamp',
                'value' => 0,
                'alias' => '-',
                'amaze_time' => ''
            ];
        }
        return [
            'type' => 'unix_timestamp',
            'value' => $timestamp,
            'alias' => date($format, $timestamp),
            'amaze_time' => transformTime($timestamp)
        ];
    }

}

if (!function_exists('pack_input_params')) {
    /**
     * 过滤字符串单双引号
     * @param string $str 用逗号分隔的多个参数
     * @return mixed
     */
    function pack_input_params($str)
    {
        if (empty($str)) {
            return $str;
        }

        return strip_quotes(trim($str));
    }
}

if (!function_exists('array_to_object')) {
    /**
     * 数组转为对象
     * @param array $e
     * @return mixed
     */
    function array_to_object($e)
    {
        if (gettype($e) != 'array')
            return null;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object') {
                $e [$k] = (object)$this->array_to_object($v);
            }
        }
        return (object)$e;
    }
}

if (!function_exists('object_to_array')) {
    /**
     * 对象转为数组
     * @param object $obj
     * @return array
     */
    function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr [$key] = $val;
        }
        if (!isset($arr)) {
            $arr = array();
        }
        return $arr;
    }

}

if (!function_exists('get_good_str')) {
    /**
     * 获取字符过滤 用反斜线转义字符串
     * @param string $ary
     * @return string
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
     * Strip Quotes
     *
     * 从字符串中移除单引号和双引号
     *
     * @param    string $str
     * @return    string
     */
    function strip_quotes($str)
    {
        return str_replace(array('"', "'"), '', $str);
    }
}