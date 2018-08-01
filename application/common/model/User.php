<?php 
namespace app\common\model;
use think\Model;

/**
 * 嘀友用户模型
 * @author zhuyong
 * @date 2017-12-11 10:22
 * @version 2.0
 */

class User extends Model
{
    
    // 表名

    
    //所有键值
    private $table_key = array(
    );    
    
    //模糊查询字段
    public $fuzzy_query = 'a.nick_name|b.nick_name|b.name|a.telephone|a.name|a.address';
    
    //嘀友APP接口错误状态码

    //学生管理返回字段
    public $s_field = 'a.id,a.nick_name,a.name,a.telephone,a.address,a.sex,a.r_id,b.name as pname,t.name as tname';


    //家长管理返回字段
    public $f_field = 'a.id,a.nick_name,a.name,a.telephone,a.address,a.sex,a.r_id,b.name as sname';

    // 老师管理返回字段
    public $t_field = 'id,nick_name,name,telephone,address,sex,s_id,t_id,f_id';

    /**
     * 获取规划表中学生的名称fodeach方法案例1
     */

    public function getUserName($array)
    {
        foreach ($array as $scheduleRow){
            if($scheduleRow['user_id']){
                $userRows = self::where(array("id"=>array("in",$scheduleRow['user_id'])))
                    ->field('name')
                    ->select()
                    ->toArray();
                $join_student = '';
                foreach ($userRows as $userRow){
                    $join_student = $userRow['name'].",".$join_student;
                }
                $scheduleRow['sname'] = $join_student;
            }
            $PlanningList[] = $scheduleRow;
        }
        return $PlanningList;
    }
    
}