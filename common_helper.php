<?php

/**
 * 请求远程数据
 * @param type $url
 * @param type $parm
 * @return type
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

/**
 * 获取图片的Base64编码(不支持url)
 * @date 2017-02-20 19:41:22
 *
 * @param $img_file 传入本地图片地址
 *
 * @return string
 */
function imgToBase64($img_file) {

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

                case 1: $img_type = "gif";
                    break;
                case 2: $img_type = "jpg";
                    break;
                case 3: $img_type = "png";
                    break;
                case 17: $img_type = "x-icon";
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

/**
 * 输入字符串的时：分：秒 返回秒数
 * @param type $str
 * @return string
 */
function git_second($time) {
    $h = substr($time,-8,2)*3600;
    $m = substr($time,-5,2)*60;
    $s = substr($time,-2,2);
    $res = $h+$m+$s;
    return $res;
}

    /**
     * 获取时间，采用标准时区。
     * @param type $str
     * @return string
     */
    function get_time()
    {
        date_default_timezone_set('Asia/Shanghai');
        return date("Y-m-d H:i:s");
    }

    /**
     * 将时间戳 格式化
     * @param time $str
     * @return string
     */
    function get_date($time)
    {
        date_default_timezone_set('PRC');
        return date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - (int) $time, date("y")));
    }

    /**
     * 将时间戳 格式化
     * @param time $str
     * @return string
     */
    function get_date_str($time, $time_str)
    {
        date_default_timezone_set('PRC');
        return date("Y-m-d", strtotime('-' . $time . ' days', strtotime($time_str)));
    }

    /**
     * 将时间戳 格式化
     * @param time $str
     * @return string
     */
    function get_date_after($time)
    {
        date_default_timezone_set('PRC');
        return date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + (int) $time, date("y")));
    }
    /**
     * 检查数据的参数是不是电话参数。
     * @param type $str
     * @return string
     */
    function check_phone($str)
    {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^((\(\d{2,3}\))|(\d{3}[\-]?))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}([\-]?\d{1,4})?$/", $str);
    }

    /**
     * 检查数据的参数是不是邮箱参数。
     * @param type $str
     * @return string
     */
    function check_mail($str)
    {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^[A-Z_a-z0-9-\.]+@([A-Z_a-z0-9-]+\.)+[a-z0-9A-Z]{2,4}$/", $str);
    }

    /**
     * 检查数据的参数是不是数字参数。
     * @param type $str
     * @return string
     */
    function check_int($str)
    {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^\d+$/", $str);
    }

    /**
     * 正则提取中文
     * @return string
     */
    function get_cn($str)
    {
        preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $str, $matches);
        $cn = join('', $matches[0]);
        return $cn;
    }


    /**
     * 正则提取数字
     * @return string
     */
    function get_int($str)
    {
        preg_match_all('/[\d]+/u', $str, $matches);
        $int = join('', $matches[0]);
        return $int;
    }
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
        foreach($params as $key => $val)
        {
            $sign .= $key.$val;
        }
        $sign .= 'keysecret'.$this->appSecret;
        $sign = md5($sign);
        return $sign;
    }