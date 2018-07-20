<?php
namespace app\admin\controller;
use think\Controller;


class Login extends Controller
{

    public function _initialize()
    {
        $this->user_name = '嗨嗨';
        $this->user_id = 1;
    }
}
