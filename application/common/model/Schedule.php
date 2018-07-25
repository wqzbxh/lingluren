<?php 
namespace app\common\model;
use think\Model;

/**
 * 嘀友用户模型
 * @author zhuyong
 * @date 2017-12-11 10:22
 * @version 2.0
 */

class Schedule extends Model
{

    //模糊查询字段
    public $fuzzy_query = 'schedule_name|name';


    //规划管理返回字段 四年计划
    public $return_field_type1 = 's.id,s.type,s.schedule_name,s.user_id,s.t_id,u.name';

    //规划管理返回字段
    public $return_field = 'id,type,schedule_name,user_id,t_id';


}