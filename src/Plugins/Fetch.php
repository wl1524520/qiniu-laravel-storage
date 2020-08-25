<?php
/**
 * Created by PhpStorm.
 * User: ZhangWB
 * Date: 2015/4/21
 * Time: 16:42
 */

namespace wl1524520\QiniuStorage\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

/**
 * Class Fetch
 * 调用qiniu的fetch指令 <br>
 * $disk        = \Storage::disk('qiniu'); <br>
 * $re          = $disk->getDriver()->fetch('http://abc.com/foo.jpg', 'bar.jpg'); <br>
 * @package wl1524520\QiniuStorage\Plugins
 */
class Fetch extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'fetch';
    }

    public function handle($url, $key)
    {
        return $this->filesystem->getAdapter()->fetch($url, $key);
    }
}
