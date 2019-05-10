<?php

/**
 * 代码日志记录类
 * Class Logger
 *
 * @create 2018-12-05 11:42:58
 * @author jiangbingjie<jiangbinjie@i3020.com>
 */
class Logger
{
    static $log = [];

    /**
     * @var string
     * @author jiangbingjie<jiangbinjie@i3020.com>
     */
    private $log_name = '';

    /**
     * @var
     * @author jiangbingjie<jiangbinjie@i3020.com>
     */
    private $fileHandle;

    /**
     * Logger constructor.
     *
     * @param $log_file
     * @throws \Exception
     */
    protected function __construct($log_file)
    {
        if (empty($log_file) && ! is_numeric($log_file)) {
            throw new Exception('文件名不可以是空值,并且不能包含特殊字符，如有特殊字符会被替换为下划线', '001');
        }
        $log_file = $this->replace_special_char($log_file);
        defined('LOG_PATH') or define('LOG_PATH', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR); // 日志目录
        $this->log_name = LOG_PATH.$log_file.date('_Y_m_d').'.log';
    }

    /**
     * 过滤非法字符
     * replace_special_char
     *
     * @param $strParam
     * @return null|string|string[]
     * @author jiangbingjie<jiangbinjie@i3020.com>
     */
    private function replace_special_char($strParam)
    {
        $regex = "/\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\（|\）|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\;|\'|\`|\-|\=|\\\|\||\s+/";

        return preg_replace($regex, "_", $strParam);
    }

    /**
     * i
     *
     * @param string $log_file | 填写文件名如果有斜杆自动会创建文件夹
     * @param array|string $data | 填写内容字符串或数组
     * @param int $level
     * @return array | @返回错误信息 如果没有错误则为null
     * @author jiangbingjie<jiangbinjie@i3020.com>
     */
    static public function i($data, $log_file = 'default', $level = 0)
    {
        try {
            if (! isset(Logger::$log[$log_file])) {
                Logger::$log[$log_file] = new Logger($log_file);
            }
            Logger::$log[$log_file]->log($data, $level);
        } catch (Exception $e) {
            return ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }
    }

    /**
     * log
     *
     * @param array $data
     * @param int $level
     * @throws Exception
     * @author jiangbingjie<jiangbinjie@i3020.com>
     */
    protected function log($data = [], $level = 0)
    {

        if (empty($data) && ! is_numeric($data)) {
            throw new Exception('$data 不能为空', '001');
        }

        if (! is_string($data) && ! is_array($data)) {
            throw new Exception('$data 必须是字符串或数组格式', '001');
        }

        if (! is_numeric($level)) {
            throw new Exception('$level 必须是整数', '001');
        }

        if (is_array($data)) {
            $data = var_export($data, true);
        }

        $data = date('Y-m-d H:i:s')."\t【{$level}】\t{$data}\r\n";
        fwrite($this->getFileHandle(), $data);
    }

    /**
     * getFileHandle
     *
     * @return bool|resource
     * @author jiangbingjie<jiangbinjie@i3020.com>
     */
    protected function getFileHandle()
    {
        if (null === $this->fileHandle) {
            if (empty($this->log_name)) {
                trigger_error("no log file spcified.");
            }
            $logDir = dirname($this->log_name);
            if (! is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            $this->fileHandle = fopen($this->log_name, "ab");
        }

        return $this->fileHandle;
    }
}

?>