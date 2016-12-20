<?php

/**
 * Created by PhpStorm.
 * User: szl4zsy
 * Date: 12/20/2016
 * Time: 1:11 PM
 */
class indexController extends MainController
{
    function index()
    {
        $users=[
            ['username'=>'mark','userPass'=>'7777777y'],
            ['username'=>'zhu','userPass'=>'31415926red'],
            ['username'=>'red','userPass'=>'31415926zyq'],
        ];
        $this->addVar('users',$users);
    }
}