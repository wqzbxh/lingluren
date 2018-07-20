<?php
namespace app\admin\controller;
use think\Controller;


class User extends Login
{



    const SEX_TYPE = array(
        0  => '未知',
        1  => '男',
        2  => '女',
    );
    /**
     * return mixed
     * parameter search 查找字段
     * param page 页码
     * param start_time 开始时间
     * param end_time 结束时间
     * param type 类型1家长2学生3老师0管理员
     */
    public function index()
    {


        $userModel = new \app\common\model\User();
        $search = isset($_POST['search']) ? $_POST['search'] : null;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : '2018-01-01 00:00:00';
        $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : '2028-01-01 00:00:00';
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $type = isset($_POST['type']) ? $_POST['type'] : 2;
        $limit =10;
        $offset = $page * $limit;
        if(empty($search)){
            $userRows = $userModel->where(array('is_show'=>1,'is_del'=>0,'r_id'=>$type))->where('create_time','between time',[$start_time,$end_time])->field($userModel->field)->limit($offset,$limit)->select()->toArray();
            $count  = $userModel->where(array('is_show'=>1,'is_del'=>0,'r_id'=>$type))->where('create_time','between time',[$start_time,$end_time])->field($userModel->field)->limit($offset,$limit)->count();
        }else{
            $userRows = $userModel->where(array('is_show'=>1,'is_del'=>0,'r_id'=>$type))->where($userModel->fuzzy_query,'like','%'.$search.'%')->where('create_time','between time',[$start_time,$end_time])->field($userModel->field)->limit($offset,$limit)->select()->toArray();
            $count = $userModel->where(array('is_show'=>1,'is_del'=>0,'r_id'=>$type))->where($userModel->fuzzy_query,'like','%'.$search.'%')->where('create_time','between time',[$start_time,$end_time])->field($userModel->field)->limit($offset,$limit)->count();
        }
        $pageList =ceil($count / $limit);
        $this->assign('list',$userRows);
        $this->assign('r_id',$type);
        $this->assign('page',$page);
        $this->assign('count',$count);
        $this->assign('page_list',$pageList);
        return $this->fetch('index');
    }



    /**
     *  新增用户
     */
    public function add()
    {
        return $this->fetch('add');
    }



    /**
     * 修改用户
     * param id 用户自增编号
     */
    public function edit()
    {
        $returnArray = array();
        $actionLogModel = new \app\common\model\ActionLog();
        if(!empty($_POST['id'])){
            $userModel =new \app\common\model\User();
            $userRow = $userModel->get($_POST['id']);
        }else{

        }
    }


    /**
     * 删除用户
     * parm id
     */
    public function delAction()
    {
        $actionLogModel = new \app\common\model\ActionLog();
        $userModel = new \app\common\model\User();
        $returnArray = array();
        if(!empty($_POST['id'])){
            $userModel = new \app\common\model\User();
            $userRow = $userModel->where('id',$_POST['id'])->update(array('is_del'=>1,'update_time'=>date('Y-m-d H:i:s')));
            if($userRow){
                $returnArray = array(
                    'code' => 1,
                    'mssg' => $actionLogModel::ERRORCODE[1],
                    'data' => array()
                );
                $actionLogModel->addActionLog(1,$this->user_id,$this->username,'删除了编号为'.$_POST['id'].'的用户');
            }else{
                $returnArray = array(
                    'code' => 100002,
                    'mssg' => $actionLogModel::ERRORCODE[100002],
                    'data' => array()
                );
            }
        }else{
           $returnArray = array(
                'code' => 100001,
                'mssg' => $actionLogModel::ERRORCODE[100001],
                'data' => array()
           );
        }
        return $returnArray;
    }
}
