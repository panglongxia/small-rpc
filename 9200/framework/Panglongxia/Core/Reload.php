<?php


namespace Panglongxia\Core;


class Reload
{
    use Singleton;
    protected $watchDirs = []; //要监控的文件夹

    public $md5Flag; //上次计算的MD5值

    public function setWatchDirs(array $dirs)
    {
        $this->watchDirs = $dirs;
        return $this;
    }

    public function isReload()
    {
        $md5 = $this->getMd5();
        if ($md5 != $this->md5Flag) {
            $this->md5Flag = $md5;
            return true;
        }
        return false;
    }

    protected function getMd5()
    {
        $md5 = '';
        foreach ($this->watchDirs as $dir) {
            $md5 .= self::md5file($dir);
        }
        return md5($md5);
    }

    protected function md5File($dir)
    {
        //遍历文件夹当中的所有文件,得到所有的文件的md5散列值
        if (!is_dir($dir)) {
            return '';
        }
        $md5File = array();
        $d = dir($dir);
        while (false !== ($entry = $d->read())) {
            if ($entry !== '.' && $entry !== '..') {
                if (is_dir($dir . '/' . $entry)) {
                    //递归调用
                    $md5File[] = self::md5File($dir . '/' . $entry);
                } elseif (substr($entry, -4) === '.php') {
                    $md5File[] = md5_file($dir . '/' . $entry);
                }
                $md5File[] = $entry;
            }
        }
        $d->close();
        return md5(implode('', $md5File));
    }

}