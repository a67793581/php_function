<?php


include 'BaseTransform.php';
$b = new BaseTransform();
$e = $b->encode(0);
$d = $b->decode($e);
var_dump($e,$d);

