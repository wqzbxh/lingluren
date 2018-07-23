<?php
namespace app\common\model;
use think\Model;

/**
 * Class Errors
 * @package app\common\model
 * @author wanghaiyang
 *
 */
class ActionLog extends Model
{

    private $table_name = 'action_log';
    /**
     * 错误返回代码
     */
    const ERRORCODE = array(
        1 => "SUCCESS",
        100001 => '该系统找不到此用户!The system can\'t find this user!',
        100002 => '删除用户信息失败!Deletion of user information failed!',
        100003 => '用户名不能为空！User name cannot be empty!',
        100004 => '密码不能为空！Password cant be empty!',
        100005 => '账号暂时不能登陆，请联系管理员恢复账号！The account cannot be logged in temporarily, please contact the administrator to restore the account!',
        100006 => '姓氏不能为空！FirstName cannot be empty!',
        100007 => '名称不能为空！LastName cannot be empty!',
        100008 => '手机号不能为空！Cell phone number cannot be empty!',
        100009 => '账号暂时不能登陆，请联系管理员恢复账号！The account cannot be logged in temporarily, please contact the administrator to restore the account!'
    );

    /**
     * 添加行为记录
     * param type 类型
     * param user_id 用户id
     * param user_name 用户的名称
     * param action 动作行为
     *
     */
    public function addActionLog($type=1,$user_id=0,$user_name='',$action='')
    {
        $data['create_time'] = dete('Y-m-d H:i:s');
        $data['type'] = $type;
        $data['user_id'] = $user_id;
        $data['user_name'] = $user_name;
        $data['action'] = $action;
        $result = self::create($data);
     }

}