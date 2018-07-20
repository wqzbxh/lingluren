<?php
namespace app\admin\controller;
use think\Controller;


class Index extends Login
{
    public function index()
    {
        return $this->fetch('index');
    }
    /**
     * 测试
     */
    public function test()
    {
        return $this->fetch('test');
    }
}
