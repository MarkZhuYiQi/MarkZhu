<?php

/**
 * Created by PhpStorm.
 * User: szl4zsy
 * Date: 12/20/2016
 * Time: 11:24 AM
 */

class MainController
{
    public $_viewName='index';      //需要显示的模板名称
    public $_varList=array();
    function setViewName($viewName){
        $this->_viewName=$viewName;
    }
    function addVar($name,$value){
        $this->_varList[$name]=$value;
    }

    function run(){
        ob_start();
        include('MVC/V/'.VIEW_PATH.'header.tpl');
        include('MVC/V/'.VIEW_PATH.'/'.$this->_viewName.'.tpl');
        include('MVC/V/'.VIEW_PATH.'footer.tpl');
        $get_contents=ob_get_contents();
        ob_clean();
        $this->generateTpl($get_contents);
    }


    function generateTpl($content)
    {
        $content=$this->genInclude($content);
        $content=$this->genForeach($content);
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
        $pattern="/\{".$varName."\.(?<varValue>[a-zA-Z]{1,30})\}/";
        if(preg_match_all($pattern,$content,$result))
        {
            $varValue=$result['varValue'];      //varValue即为user.username中的username,是个数组,保存所有变量
            foreach($varValue as $item)
            {
                if(isset($var[$item]))
                {
                    $content=preg_replace("/\{".$varName."\.".$item."\}/",$var[$item],$content);
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
