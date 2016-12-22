<?php

/**
 * Created by PhpStorm.
 * User: szl4zsy
 * Date: 12/20/2016
 * Time: 11:24 AM
 */
require_once "Common/tplFunc.php";

class MainController
{
    public $_viewName='index';      //需要显示的模板名称
    public $_varList=array();       //初始化变量组，会传递给前端模板
    public $isFileCache=FILE_CACHE;
    public $cache_time=0;           //memcache缓存时间，0代表没有缓存
    /**
     * @param $viewName     显示的模板名称
     */
    function setViewName($viewName){
        $this->_viewName=$viewName;
    }

    /**
     * @param $name
     * @param $value
     * 添加一个变量到前端模板中
     */
    function addVar($name,$value){
        $this->_varList[$name]=$value;
    }

    /**
     * 运行所有事宜
     */
    function run(){
        if($this->cache_time>0)
        {
            if($getVars=get_cache($this->_viewName))
            {
                echo '使用了memcache缓存';
                extract($getVars);
            }else{
                set_cache($this->_viewName,$this->_varList,$this->cache_time);
                extract($this->_varList);
            }
        }else{
            extract($this->_varList);
        }
        ob_start();
        include('MVC/V/'.VIEW_PATH.'header.tpl');
        include('MVC/V/'.VIEW_PATH.'/'.$this->_viewName.'.tpl');
        include('MVC/V/'.VIEW_PATH.'footer.tpl');
        $get_contents=ob_get_contents();
        ob_clean();
        if($this->isFileCache)
        {
            $file_name=md5($_SERVER['REQUEST_URI']);
            if(file_exists(CACHE_PATH.$file_name))
            {
                //这里还需要一个判断，如果类或者view页面做了更改就更新缓存内容
                echo 'use file cache';
                echo file_get_contents(CACHE_PATH.$file_name);
            }else{
                $cacheContent=$this->generateTpl($get_contents);
                file_put_contents(CACHE_PATH.$file_name,$get_contents);
                echo $cacheContent;
            }
        }
        echo $this->generateTpl($get_contents);
    }

    function generateTpl($content)
    {
        $content=$this->genInclude($content);
        $content=$this->genForeach($content);
        $content=$this->genSimpleVars($content);
        echo $content;
    }
    function genInclude($content)
    {
        if($content!='')
        {
            if(preg_match_all("/\{include\s+([\w\.]{3,50})\}/",$content,$result))
            {
                $result=$result[1];
                foreach($result as $r)
                {
                    if(file_exists(VIEW_INCLUDE_PATH.$r))
                    {
                        $temp=file_get_contents(VIEW_INCLUDE_PATH.$r);
                        $content=preg_replace("/\{include\s+[\w\.]{3,50}\}/",$temp,$content);
                    }
                }
            }
        }
        return $content;
    }

    /**
     * @param $content
     *
     * {blue('testVars')} {content}
     */
    function genSimpleVars($content)
    {
        //varObject0匹配整个函数/变量，varObject1匹配函数名，varObject2匹配变量名
        //如果0，1有，2没有，说明是带变量函数；如果0,1没有，2有，说明是一个变量
        if(preg_match_all("/\{(?<varObject0>[^\{]*?\(\'(?<varObject1>[\w\.]{1,30})\'\))\}|{(?<varObject2>[\w\.]{1,30}?)}/is",$content,$result))
        {
            $varObject0=$result['varObject0'];
            $varObject1=$result['varObject1'];
            $varObject2=$result['varObject2'];
//            var_dump($result);
            foreach($result[0] as $r)
            {
                $var0=current($varObject0);
                $var1=current($varObject1);
                $var2=current($varObject2);
                if($var0 && $var1 && !$var2)    //说明是带变量函数
                {
                    if(array_key_exists($var1,$this->_varList))
                    {
                        $replaceR=str_replace($var1,$this->_varList[$var1],$r);
                        $replaceR=str_replace(array('{','}'),'',$replaceR);
                        eval('$last='.$replaceR.';');
                        if($last)
                        {
                            $content=str_replace($r,$last,$content);
                        }
                    }
                }
                else    //说明就是个变量
                {
                    if(array_key_exists($var2,$this->_varList))
                    {
                        $content=str_replace('{'.$var2.'}',$this->_varList[$var2],$content);
                    }
                }
                $var0=next($varObject0);
                $var1=next($varObject1);
                $var2=next($varObject2);
            }
        }
        return $content;
    }
    function genForeach($content){
        global $foreach_id;
        $content=preg_replace_callback("/\{(foreach)\:([a-zA-Z]{1,30})\s+(name\=[a-zA-Z]{1,30})\}/",'foreachCallBack',$content);
        foreach($foreach_id as $fid)        //有几个foreach，根据唯一的id循环几次
        {
            $pattern="/\{foreach\:(?<varObject>\w{1,30})\:name\=(?<varName>\w{1,30})\:".$fid."\}/";
            if(preg_match_all($pattern,$content,$contentResult)){
                $varObject=$contentResult['varObject'][0];  //取出循环变量
                $varName=$contentResult['varName'][0];      //取出循环中的别名
                if($this->_varList[$varObject])     //判断要循环的变量是否已经存在了varList中
                {
                    $pattern="/\{foreach\:".$varObject."\:name=".$varName."\:".$fid."\}(?<replaceContent>.*?)\{\/foreach\}/is";
                    if(preg_match($pattern,$content,$result))
                    {
                        $replaceResult=$result['replaceContent'];   //这些是循环中间的内容
                        $finalRes='';
                        foreach($this->_varList[$varObject] as $row)    //循环这个模板需要的变量
                        {
                            //row就是变量中的每一组数据
                            $finalRes.=$this->genForeachVars($replaceResult,$varName,$row);
                        }
                    }
                }
                $content=preg_replace("/\{foreach\:".$varObject."\:name=".$varName."\:".$fid."\}(?<replaceContent>.*?)\{\/foreach\}/is",$finalRes,$content);
            }
        }
        return $content;
    }

    /**
     * @param $content  循环中的内容
     * @param $varName  在循环中循环变量别名
     * @param $var      本次循环中的需要循环的变量中的一组值
     */
    function genForeachVars($content,$varName,$var)
    {
        if(preg_match_all("/{(.*?)}/is",$content,$result))
        {
            $result=$result[1];
            foreach($result as $r)      //取出了出每次循环时，{}里面的内容
            {
                $pattern="/".$varName."\.(?<varValue>[a-zA-Z]{1,30})/";
                if(preg_match($pattern,$r,$result))
                {
//                    var_dump($result['varValue']);
                    $varValue=$result['varValue'];
                    if($r==$varName.'.'.$varValue)
                    {
                        //说明外面没套别的东西 没有函数
                        if(isset($var[$varValue]))
                        {
                            $content=preg_replace("/\{".$varName."\.".$varValue."\}/",$var[$varValue],$content);
                        }
                    }
                    else
                    {
                        if(isset($var[$varValue]))
                        {
                            $temp=preg_replace("/".$varName."\.".$varValue."/",$var[$varValue],$r);
                            eval('$temp='.$temp.';');
                            $content=str_replace('{'.$r.'}',$temp,$content);
                        }
                    }
                }
            }
            return $content;
        }
    }
}
$foreach_id=array();
function foreachCallBack($match)
{
    $id=md5(rand());
    global $foreach_id;
    $foreach_id[]=$id;
    return '{'.$match[1].':'.$match[2].':'.$match[3].':'.$id.'}';
}
