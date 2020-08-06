<?php
/**
 * Created by PhpStorm.
 * @author carlo<284474102@qq.com>
 */

class BaseTransform
{
	private $alphabet;

	private $base;
	public function __construct(string $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_'){
		$this -> alphabet = $alphabet;//设置进制
		$this -> base = strlen($alphabet);//计算进制数
	}

	/**
	 * 编码
	 * @param int $id
	 * @return string
	 */
	public function encode(int $id)
	{
		$shortenedId = '';

		if($id == 0){
			$shortenedId = $this -> alphabet{0};
		}
		while($id > 0){
			$remainder = $id % $this -> base;
			$id = ($id-$remainder) / $this -> base;
			if($remainder == 0){
				$shortenedId = $this -> alphabet{0} . $shortenedId;
			}else{
				$shortenedId = $this -> alphabet{$remainder} . $shortenedId;
			}
		}
		return $shortenedId;
	}

	/**
	 * 解码
	 * @param string $base_num
	 * @return float|int
	 */
	public function decode(string $base_num){
		$raw_base = $base_num;
		$data_length = strlen($raw_base);
		$dec_data = 0;
		for($i = 0; $i < $data_length; $i++){//每一位分别读取
			$multiply = 1;
			for($j = $data_length - 1; $j > $i; $j--){//构造当前位需要乘的数
				if($i == ($data_length - 1)){
					$multiply = 1;
				}else{
					$multiply = $multiply * $this -> base;
				}
			}
			$dec_data += strpos($this -> alphabet, $raw_base{$i}) * $multiply;    //计算当前位数代表的十进制数，然后累加
		}
		return $dec_data;
	}
}
