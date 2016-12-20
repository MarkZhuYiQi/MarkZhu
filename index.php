<?php
/**
 * Created by PhpStorm.
 * User: szl4zsy
 * Date: 12/20/2016
 * Time: 11:13 AM
 * port:8787
 */
require 'Common/config.php';
require 'Common/functions.php';
require('MVC/C/MainController.class.php');

$get_control=isset($_GET['controller'])?trim($_GET['controller']):'index';
$get_method=isset($_GET['method'])?trim($_GET['method']):'index';

if(file_exists('MVC/C/'.$get_control.'Controller.class.php'))
{
    require('MVC/C/'.$get_control.'Controller.class.php');
    $get_control=$get_control.'Controller';
    $controller=new $get_control();
    if(method_exists($controller,$get_method))
    {
        $controller->$get_method();
        $controller->run();
    }
    else
    {
        echo '方法不存在!';
    }

}