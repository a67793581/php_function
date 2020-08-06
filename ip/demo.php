<?php
/**
 * Created by PhpStorm.
 * User: 蒋炳杰
 * @create 2019-03-21 16:36:10
 * @author jiangbingjie<jiangbinjie@i3020.com>
 */
require_once 'Ip_location.php';
$ip_location = new Ip_location();
$ip = "180.76.6.130";
$location = $ip_location->getlocation($ip);
var_dump($location);
