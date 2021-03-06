<?php
namespace app\admin\controller;
use think\Controller;

/**
 * Class Inform
 * @package app\admin\controller
 * 通知
 */

class Inform extends Login
{



    const SEX_TYPE = array(
        0  => '未知',
        1  => '男',
        2  => '女',
    );

    public function _initialize()
    {
       if(session('mitangUser')){
          // var_dump(session('mitangUser'));
           $this->userId = session('mitangUser')['id'];
           $this->username = session('mitangUser')['name'];
           $this->r_id = session('mitangUser')['r_id'];
       }else{
           $this->redirect('login/login');
       }
        if(session('mitangUser')){
            $this->assign('roletype',session('mitangUser')['r_id']);
        }
       // parent::_initialize(); // TODO: Change the autogenerated stub
    }

     /**
      * 首页
      * 列表
      */
     public function index()
     {

         $informModel = new \app\common\model\Inform();
         $search = isset($_POST['search']) ? $_POST['search'] : null;
         $page = isset($_POST['page']) ? $_POST['page'] : 1;
         $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : '2018-01-01 00:00:00';
         $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : '2028-01-01 00:00:00';
         $page = isset($_GET['page']) ? $_GET['page'] : 0;
         $type = isset($_GET['type']) ? $_GET['type'] : 1;
         $limit =10;
         $offset = $page * $limit;

//
//         if($informRows){
//
//         }

         switch ($this->r_id){
             case 0:
                 if(empty($search)){
                     $informRows = $informModel
                         ->alias('a')
                         ->where(array('a.is_del'=>0,'a.is_show'=>1,'a.from_user_id'=>$this->userId))
                         ->where('a.create_time','between time',[$start_time,$end_time])
                         ->limit($offset,$limit)
                         ->field($informModel->return_field)
                         ->select()
                         ->toArray();
                     $count = $informModel ->alias('a') ->where(array('a.is_del'=>0,'a.is_show'=>1,'a.from_user_id'=>$this->userId)) ->where('a.create_time','between time',[$start_time,$end_time])->field($informModel->return_field)->count();
                 }else{
                     $informRows = $informModel
                         ->alias('a')
                         ->where(array('a.is_del'=>0,'a.is_show'=>1,'a.from_user_id'=>$this->userId))
                         ->where('a.create_time','between time',[$start_time,$end_time])
                         ->where($informModel->fuzzy_query, 'like', '%' . $search . '%')
                         ->limit($offset,$limit)
                         ->field($informModel->return_field)
                         ->select()
                         ->toArray();
                     $count = $informModel ->alias('a') ->where(array('a.is_del'=>0,'a.is_show'=>1,'a.from_user_id'=>$this->userId))->where($informModel->fuzzy_query, 'like', '%' . $search . '%')->where('a.create_time','between time',[$start_time,$end_time])->field($informModel->return_field)->count();
                 }


                 break;
             case 4:
                 if(empty($search)){
                     $informRows = $informModel
                         ->alias('a')
                         ->where(array('a.is_del'=>0,'a.is_show'=>1))
                         ->where('a.create_time','between time',[$start_time,$end_time])
                         ->limit($offset,$limit)
                         ->field($informModel->return_field)
                         ->select()
                         ->toArray();
                     $count = $informModel->alias('a')->where(array('a.is_del'=>0,'a.is_show'=>1))->where('a.create_time','between time',[$start_time,$end_time])->field($informModel->return_field)->select()  ->toArray();
                 }else{
                     $informRows = $informModel
                         ->alias('a')
                         ->where(array('a.is_del'=>0,'a.is_show'=>1))
                         ->where('a.create_time','between time',[$start_time,$end_time])
                         ->where($informModel->fuzzy_query, 'like', '%' . $search . '%')
                         ->limit($offset,$limit)
                         ->field($informModel->return_field)
                         ->select()
                         ->toArray();
                     $count = $informModel->alias('a')->where(array('a.is_del'=>0,'a.is_show'=>1))->where('a.create_time','between time',[$start_time,$end_time])->where($informModel->fuzzy_query, 'like', '%' . $search . '%')->field($informModel->return_field)->select()  ->toArray();
                 }
                 break;

         }


         $pageList =ceil($count / $limit);
         $this->assign('list',$informRows);
         $this->assign('type',$type);
         $this->assign('page',$page);
         $this->assign('count',$count);
         $this->assign('page_list',$pageList);
         return $this->fetch('index');
     }

     /**
      * 发布消息
      */
     public function publishTheNews(){
//        读出该用户下所有的人员名单
         $userModel = new \app\common\model\User();
         switch ($this->r_id){
             case 0 :
                 $rows = $userModel->find($this->userId);
                 if($rows){
                     $row = $rows->toArray();
//             获取该用户下所有的学生
                     $studentsRow = $userModel
                         ->where(array('is_del'=>0))
                         ->where('id','in',$row['s_id'])
                         ->select()
                         ->toArray();
//             获取该用户下所有的家长
                     $parentRows = $userModel
                         ->where(array('is_del'=>0))
                         ->where('id','in',$row['f_id'])
                         ->select()
                         ->toArray();
                 }
                 break;
             case 4:
//             获取该用户下所有的学生
                 $studentsRow = $userModel
                     ->where(array('is_del'=>0,'r_id'=>1))
                     ->select()
                     ->toArray();
//             获取该用户下所有的家长
                 $parentRows = $userModel
                     ->where(array('is_del'=>0,'r_id'=>1))
                     ->select()
                     ->toArray();
//             获取该用户下所有的老师
                 $teacherRows = $userModel
                     ->where(array('is_del'=>0,'r_id'=>1))
                     ->select()
                     ->toArray();
                 $this->assign('teacherRows',$teacherRows);
                break;
         }

         $this->assign('studentsRow',$studentsRow);
         $this->assign('parentRows',$parentRows);
         $this->assign('type',1);
         return $this->fetch('publish_the_news');
     }


    /**
     * 发布通知
     */
    public function addAction()
    {
        $returnArray = array();
        $actionlogModel = new \app\common\model\ActionLog();

        if(!empty($_POST['recipient'])){
            $dataArr = explode(',',$_POST['recipient']);
        }else{
            echo "<script>alert('".$actionlogModel::ERRORCODE[300001]."');location.href='publishTheNews'</script>";
            return;
        }
        if(!empty($_POST['theme'])){
            $data['theme'] = $_POST['theme'];
        }else{
            echo "<script>alert('".$actionlogModel::ERRORCODE[300002]."');location.href='publishTheNews'</script>";
            return;
        }
        if(!empty($_POST['content'])){
            $data['content'] = $_POST['content'];
        }else{
            echo "<script>alert('".$actionlogModel::ERRORCODE[300003]."');location.href='publishTheNews'</script>";
            return;
        }

        if($_POST['start_time']){
            $data['start_time'] = $_POST['start_time'];
        }
        if($_POST['seat']){
            $data['address'] = $_POST['seat'];
        }
        if($_POST['send_status'] == 2){
            $data['is_send'] = $_POST['send_status'];
            $data['send_time'] = $_POST['send_time'];
        }
        $data['from_user_id'] = $this->userId;
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['from_user_id'] = $this->userId;
        if(empty($_FILES['img_file']) == false){
            $imageModel = new \app\common\model\ImageRecord();
            $filePath = 'public' . DS . 'laisien' . DS . 'inform' .date('Y-m-d') .DS ;
            $imgRecod = $imageModel ->uploadImageAction($_FILES['img_file'],$filePath);
            if($imgRecod['info']){
                $data['img_id'] = $imgRecod['info'];
            }
        }

        if(empty($returnArray)){
            foreach ($dataArr as $row){
                $data['to_user_id'] = $row;
                $datas[] = $data;
            }
            $informModel = new \app\common\model\Inform();
            $allInfo = $informModel->insertAll($datas);
            $data['type'] = 2;
            $data['to_user_id'] = $_POST['recipient'];
            $single =  $informModel->insert($data);
            if($allInfo && $single){
                echo "<script>alert('".$actionlogModel::ERRORCODE[1]."');location.href='index'</script>";
            }else{
                echo "<script>alert('".$actionlogModel::ERRORCODE[300004]."');location.href='index'</script>";
            }
        }else{
            echo "<script>alert('".$actionlogModel::ERRORCODE[300003]."');location.href='publishTheNews'</script>";
            return;
        }
    }

    /**
     * 删除通知
     *
     */

    public function delAction()
    {
        $actionLogModel = new \app\common\model\ActionLog();
        $informModel = new \app\common\model\Inform();
        $returnArray = array();
        if(!empty($_POST['id'])){
            $informRow = $informModel->where('id',$_POST['id'])->update(array('is_del'=>1,'update_time'=>date('Y-m-d H:i:s')));
            if($informRow){
                $returnArray = array(
                    'code' => 1,
                    'mssg' => $actionLogModel::ERRORCODE[1],
                    'data' => array()
                );
                $actionLogModel->addActionLog(1,$this->userId,$this->username,request()->action().request()->controller(),$_POST['id']);
                //  $actionLogModel->addActionLog(1,$this->user_id,$this->username,'删除了编号为'.$_POST['id'].'的用户');
            }else{
                $returnArray = array(
                    'code' => 300006,
                    'mssg' => $actionLogModel::ERRORCODE[300006],
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
                'code' => 300005,
                'mssg' => $actionLogModel::ERRORCODE[300005],
                'data' => array()
            );
        }

        return $returnArray;
    }

}
