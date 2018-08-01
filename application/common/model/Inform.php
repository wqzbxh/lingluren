<?php 
namespace app\common\model;
use think\Model;

/**
 * 来思安通知模型
 * @author wqzbxh
 * @date Wed Aug 01 2018 17:56:03 GMT+0800 (中国标准时间)
 * @version 1.0
 */

class Inform extends Model
{

    //模糊查询字段
    public $fuzzy_query = 'content|theme';


    //规划管理返回字段 四年计划a
    public $return_field= 'a.id,a.content,a.theme,a.create_time,a.send_time,a.address';

    //规划管理返回字段
    //public $return_field = 'id,type,schedule_name,user_id,t_id';


}