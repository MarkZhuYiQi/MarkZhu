<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/20
 * Time: 21:20
 */

/**
 * @param $key      设置键
 * @param $value    设置值
 * @param $expire   过期时间
 */
function set_cache($key,$value,$expire)
{
    $m=new Memcache();
    $m->connect(CACHE_IP,CACHE_PORT);
    $m->set($key,$value,0,$expire);
}
function get_cache($key)
{
    $m=new Memcache();
    $m->connect(CACHE_IP,CACHE_PORT);
    return $m->get($key);
}