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
        100009 => '添加失败！fail to add!',
        100010 => '编号传递过程中遗失，刷新重试!Lost in the number transfer process, refresh retry!',
        100011 => '账号密码错误，刷新重试或联系工作人员!Account password error, refresh retry or contact staff!',




        200001 => '删除四年规划失败!Delete four-year plan failed!',
        200002 => '无效的四年规划ID!Invalid four-year program ID!',


        300001 => '通知接收人的ID必须存在！The ID of the notification receiver must exist！',
        300002 => '通知接主题必须存在！The notification subject must exist！',
        300003 => '通知接内容必须存在！Notification must exist！',
        300004 => '通知发送失败，请联系来思安学院管理人员！The notification failed to be sent. Please contact the administrator of si an college！',
        300005 => '找不到该信息！The information could not be found！',
        300006 => '删除失败！fail to delete！',


        900001 => 'API密码错误！API password error！',
        900002 => '获取openid失败！',
        900003 => '无效的参数！Invalid arguments！',

    );

    /**
     * 添加行为记录
     * param type 类型
     * param user_id 用户id
     * param user_name 用户的名称
     * param action 动作行为
     * param id 操作ID
     * param path 操作控制器方法
     *
     *could not be converted to string
     */
    public function addActionLog($type=1,$user_id = 0,$user_name=' ',$action=' ',$id=1)
    {
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['type'] = $type;
        $data['user_id'] = $user_id;
        $data['user_name'] = $user_name;
        $data['action'] = $action;
        $data['recode_id'] = $id;
        $result = self::create($data);

     }

}