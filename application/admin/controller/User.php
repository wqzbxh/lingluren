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

    public function _initialize()
    {
       if(session('mitangUser')){
//        /  var_dump(session('mitangUser')['r_id']);
           $this->uername = session('mitangUser')['name'];
           $this->userId = session('mitangUser')['id'];
           $this->rId = session('mitangUser')['r_id'];
       }else{
           $this->redirect('login/login');
       }
       if(session('mitangUser')){
            $this->assign('roletype',session('mitangUser')['r_id']);
        }
       // parent::_initialize(); // TODO: Change the autogenerated stub
    }

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
        $type = isset($_GET['type']) ? $_GET['type'] : 2;
        $limit =10;
        $offset = $page * $limit;
        if(empty($search)){
            switch ($type){
                case 1:
                     if($this->rId == 0){
                         $userRows = $userModel
                             ->alias('a')
                             ->join('user b','a.f_id = b.id',"LEFT" )
                             ->join('user t','a.t_id = t.id',"LEFT" )
                             ->where('a.id','in',session('mitangUser')['s_id'])
                             ->where('a.create_time','between time',[$start_time,$end_time])
                             ->limit($offset,$limit)
                             ->field($userModel->s_field)
                             ->order('a.id desc')
                             ->select()->toArray();
                     }else{
                        $userRows = $userModel
                            ->alias('a')
                            ->join('user b','a.f_id = b.id',"LEFT" )
                            ->join('user t','a.t_id = t.id',"LEFT" )
                            ->where(array('a.is_show'=>1,'a.is_del'=>0,'a.r_id'=>$type))
                            ->where('a.create_time','between time',[$start_time,$end_time])
                            ->field($userModel->s_field)
                            ->limit($offset,$limit)
                            ->order('a.id desc')
                            ->select()
                            ->toArray();
                     }
                    break;

                case 2:
                    if($this->rId == 0){
                        $userRows = $userModel
                            ->alias('a')
                            ->join('user b','a.s_id = b.id',"LEFT" )
                            ->where(array('a.is_show'=>1,'a.is_del'=>0,'a.r_id'=>$type,'a.t_id'=>$this->userId))
                            ->where('a.create_time','between time',[$start_time,$end_time])
                            ->field($userModel->f_field)
                            ->limit($offset,$limit)
                            ->order('a.id desc')
                            ->select()
                            ->toArray();
                    }else{
                        $userRows = $userModel
                            ->alias('a')
                            ->join('user b','a.s_id = b.id',"LEFT" )
                            ->where(array('a.is_show'=>1,'a.is_del'=>0,'a.r_id'=>$type))
                            ->where('a.create_time','between time',[$start_time,$end_time])
                            ->field($userModel->f_field)
                            ->limit($offset,$limit)
                            ->order('a.id desc')
                            ->select()
                            ->toArray();
                    }
                    break;

                case 0:
                    $userRows = $userModel
                        ->alias('a')
                        ->where(array('a.is_show'=>1,'a.is_del'=>0,'a.r_id'=>$type))
                        ->where('a.create_time','between time',[$start_time,$end_time])
                        ->field($userModel->t_field)
                        ->limit($offset,$limit)
                        ->order('a.id desc')
                        ->select()
                        ->toArray();
                    break;

                default:
                    $userRows = $userModel
                        ->alias('a')
                        ->join('user b','a.s_id = b.id',"LEFT" )
                        ->where(array('a.is_show'=>1,'a.is_del'=>0,'a.r_id'=>$type))
                        ->where('a.create_time','between time',[$start_time,$end_time])
                        ->field($userModel->f_field)
                        ->limit($offset,$limit)
                        ->order('a.id desc')
                        ->select()
                        ->toArray();


            }

        }else{
            switch ($type) {
                case 1:
                    if ($this->rId == 0) {
                        $userRows = $userModel
                            ->alias('a')
                            ->join('user b', 'a.f_id = b.id', "LEFT")
                            ->join('user t', 'a.t_id = t.id', "LEFT")
                            ->where('a.id', 'in', session('mitangUser')['s_id'])
                            ->where('a.create_time', 'between time', [$start_time, $end_time])
                            ->where($userModel->fuzzy_query, 'like', '%' . $search . '%')
                            ->limit($offset, $limit)
                            ->field($userModel->s_field)
                            ->order('a.id desc')
                            ->select()->toArray();
                    } else {
                        $userRows = $userModel
                            ->alias('a')
                            ->join('user b', 'a.f_id = b.id', "LEFT")
                            ->join('user t', 'a.t_id = t.id', "LEFT")
                            ->where($userModel->fuzzy_query, 'like', '%' . $search . '%')
                            ->where(array('a.is_show' => 1, 'a.is_del' => 0, 'a.r_id' => $type))
                            ->where('a.create_time', 'between time', [$start_time, $end_time])
                            ->field($userModel->s_field)
                            ->limit($offset, $limit)
                            ->order('a.id desc')
                            ->select()
                            ->toArray();
                    }
                    break;

                case 2:
                    if($this->rId == 0){
                        $userRows = $userModel
                            ->alias('a')
                            ->join('user b','a.s_id = b.id',"LEFT" )
                            ->where(array('a.is_show'=>1,'a.is_del'=>0,'a.r_id'=>$type,'a.t_id'=>$this->userId))
                            ->where($userModel->fuzzy_query, 'like', '%' . $search . '%')
                            ->where('a.create_time','between time',[$start_time,$end_time])
                            ->field($userModel->f_field)
                            ->limit($offset,$limit)
                            ->order('a.id desc')
                            ->select()
                            ->toArray();
                    }else{
                        $userRows = $userModel
                            ->alias('a')
                            ->join('user b','a.s_id = b.id',"LEFT" )
                            ->where(array('a.is_show'=>1,'a.is_del'=>0,'a.r_id'=>$type))
                            ->where($userModel->fuzzy_query, 'like', '%' . $search . '%')
                            ->where('a.create_time','between time',[$start_time,$end_time])
                            ->field($userModel->f_field)
                            ->limit($offset,$limit)
                            ->order('a.id desc')
                            ->select()
                            ->toArray();
                    }
                    break;

                case 0:

                    break;

                default:

            }
        }
        $count = sizeof($userRows);
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
     *  新增父母用户
     * @param id 学生用户编号
     */
    public function addParents()
    {
        $actionModel = new \app\common\model\ActionLog();
        if(!empty($_GET['id'])){
            $this->assign('s_id',$_GET['id']);
            return $this->fetch('add_parents');
        }else{
            echo "<script>alert('".$actionModel::ERRORCODE[100010]."');location.href='../../index.html?type=1'</script>";
        }
    }


    /**
     * 修改用户
     * param id 用户自增编号
     */
    public function edit()
    {
        echo "<h1>暂不支持编辑</h1>";
        $returnArray = array();
        $actionLogModel = new \app\common\model\ActionLog();
        if(!empty($_POST['id'])){
            $userModel =new \app\common\model\User();
            $userRow = $userModel->get($_POST['id']);
        }else{

        }
    }



    /**
     * 添加用户
     * userAction
     */

    public function addAction()
    {
        $returnArray = array();
        $street_address = '';
        $mailing_street_address = '';
        $actionModel = new \app\common\model\ActionLog();


        if (!empty($_POST['FirstName'])) {
            $xing = $_POST['FirstName'];
        } else {
            $returnArray = array(
                'code' => 100006,
                'mssg' => $actionModel::ERRORCODE[100006],
                'data' => array()
            );
        }

        if (isset($_POST['LastName'])) {
            $ming = $_POST['LastName'];
            $data['name'] = $xing.$ming;
        } else {
            $returnArray = array(
                'code' => 100007,
                'mssg' => $actionModel::ERRORCODE[100007],
                'data' => array()
            );
        }

        $data['sex'] = isset($_POST['sex']) ? $_POST['sex'] : 0;
        $data['nationality'] = isset($_POST['nationality']) ? $_POST['nationality'] : '中国';


        if (!empty($_POST['telephone'])) {
            $data['telephone'] = $_POST['telephone'];
            $data['password'] = sha1($data['telephone']);
        }else{
            $returnArray = array(
                'code' => 100008,
                'mssg' => $actionModel::ERRORCODE[100008],
                'data' => array()
            );
        }

        if(!empty($_POST['birth_date'])){
             $data['birth_date'] = $_POST['birth_date'];
        }

        if(!empty($_POST['passport_no'])){
            $data['passport_no'] = $_POST['passport_no'];
        }

        if(!empty($_POST['passport_issue_date'])){
            $data['passport_issue_date'] = $_POST['passport_issue_date'];
        }

        if(!empty($_POST['passport_expiry_date'])){
            $data['passport_expiry_date'] = $_POST['passport_expiry_date'];
        }

        if(!empty($_POST['emergency_contact'])){
            $data['emergency_contact'] = $_POST['emergency_contact'];
        }

        if(!empty($_POST['emergency_telephone'])){
            $data['emergency_telephone'] = $_POST['emergency_telephone'];
        }

        if(!empty($_POST['parent_name'])){
            $parentName = $_POST['parent_name'];
        }

        if(!empty($_POST['parent_last_name'])){
            $parentLastName = $_POST['parent_last_name'];
        }

        if(!empty($_POST['parent_telephone'])){
            $data['parent_telephone'] = $_POST['parent_telephone'];
        }
      //  var_dump($_POST);exit;
        if(!empty($_POST['parent_birth_date'])){
            $data['parent_birth_date'] = $_POST['parent_birth_date'];
        }

        if(!empty($_POST['parent_company'])){
            $data['parent_company'] = $_POST['parent_company'];
        }

        if(!empty($_POST['parent_job'])) {
            $data['parent_job'] = $_POST['parent_job'];
        }

        if (!empty($_POST['parent_nationality'])) {
            $data['nationality'] = $_POST['parent_nationality'];
        }


        if (!empty($_POST['street_address'])) {
            $street_address = $_POST['street_address'];
        }

        if (!empty($_POST['permanent_zip_code'])) {
            $data['email'] = $_POST['permanent_zip_code'];
        }

        if (!empty($_POST['permanent_province']) && !empty($_POST['permanent_city']) && !empty($_POST['permanent_district']) ) {
            $data['address'] = $_POST['permanent_province'].'|'.$_POST['permanent_city'].'|'.$_POST['permanent_district'].'|'.$street_address;
        }

        if (!empty($_POST['permanent_telephone'])) {
            $data['telephone'] = $_POST['permanent_telephone'];
            $data['password'] = sha1($data['telephone']);
        }

        if (!empty($_POST['permanent_date'])) {
            $data['permanent_date'] = $_POST['permanent_date'];
        }



        if (!empty($_POST['mailing_street_address'])) {
            $data['mailing_street_address'] = $_POST['mailing_street_address'];
        }

        if (!empty($_POST['mailing_code'])) {
            $data['mailing_code'] = $_POST['mailing_code'];
        }


        if (!empty($_POST['mailing_province']) && !empty($_POST['mailing_city']) && !empty($_POST['mailing_district']) ) {
            $data['mailing_street_address'] = $_POST['mailing_province'].'|'.$_POST['mailing_city'].'|'.$_POST['mailing_district'].'|'.$street_address;
        }

        if (!empty($_POST['mailing_telephone'])) {
            $data['mailing_telephone'] = $_POST['mailing_telephone'];
        }

        if (!empty($_POST['mailing_valid_date'])) {
            $data['mailing_valid_date'] = $_POST['mailing_valid_date'];
        }

        if (!empty($_POST['major_field'])) {
            $data['major_field'] = $_POST['major_field'];
        }

        if (!empty($_POST['introductions'])) {
            $data['introductions'] = $_POST['introductions'];
        }


        $data['r_id'] = $_POST['r_id'];
        $data['s_id'] = isset($_POST['s_id']) ? $_POST['s_id'] : 0;
        $data['t_id'] = $this->userId;
        $data['create_time'] = date('Y-m-d H:i:s');
        if(empty($returnArray)){
            $userModel = new \app\common\model\User();
            $userResult = $userModel->create($data);
            if($userResult){
                $userResult = $userResult->toArray();
                switch ($data['r_id']){
                    case 1:
                        $teacherRow = $userModel->get($this->userId)->toArray();
                        if($teacherRow){
                            $teacherResult = $userModel->where(array('id'=>$this->userId))->update(array('s_id'=>$userResult['id'].','.$teacherRow['s_id']));
                            $teacherResult = $userModel->get($this->userId)->toArray();
                            session('mitangUser',$teacherResult);
                            $actionModel->addActionLog(1,$this->userId,$this->uername,'增加学生的ID',$userResult['id']);
                        }
                        echo "<script>alert('".$actionModel::ERRORCODE[1]."');location.href='../index.html?type=1'</script>";
                        break;
                    case 2:
                        $row = $userResult;
                        $userModel->where(array('id'=>$data['s_id']))->update(array('f_id'=>$row['id']));
                        $actionModel->addActionLog(1,$this->userId,$this->uername,'增加家长的iD',$userResult['id']);
                        echo "<script>alert('".$actionModel::ERRORCODE[1]."');location.href='index.html'</script>";
                        break;
                    case 0:
                        echo "<script>alert('".$actionModel::ERRORCODE[1]."');location.href='../index.html?type=0'</script>";
                        break;
                    default:
                        echo "<script>alert('".$actionModel::ERRORCODE[1]."');location.href='index.html'</script>";
                }

            }else{
                return $actionModel::ERRORCODE[100009];
            }
        }else{
            var_dump($returnArray);
            echo "<script>alert('".$actionModel::ERRORCODE[100009]."');location.href='index.html'</script>";
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

                $actionLogModel->addActionLog(1,$this->userId,$this->uername,request()->action().request()->controller(),$_POST['id']);
                //  $actionLogModel->addActionLog(1,$this->user_id,$this->username,'删除了编号为'.$_POST['id'].'的用户');
            }else{
                $returnArray = array(
                    'code' => 100002,
                    'mssg' => $actionLogModel::ERRORCODE[200001],
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
                'code' => 100001,
                'mssg' => $actionLogModel::ERRORCODE[200002],
                'data' => array()
            );
        }

        return $returnArray;
    }

    /**
     * 添加导师
     *
     */
    public function teacherAdd()
    {
        return $this->fetch('teacher_add');
    }
}
