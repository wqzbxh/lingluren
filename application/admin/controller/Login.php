<?php
namespace app\admin\controller;
use think\Controller;


class Login extends Controller
{

    public function _initialize()
    {
      if(session('mitangUser') && request()->action() == 'login' ){
          $this->userId = session('mitangUser')['id'];

         $this->redirect('index/index');
      }else{
          self::login();
      }
    }

    /**
     * 用户登录
     * @param name
     * @param password
     */
    public function login()
    {
        return $this->fetch('login');
    }


    /**
     * 登录动作
     * @param name
     * @param password
     */
    public function loginAction()
    {
        $returnArray = array();
        $actionLogModel = new \app\common\model\ActionLog();

        if(!empty($_POST['mi_name'])){
            $username = $_POST['mi_name'];
        }else{
            $returnArray = array(
                'code' => 100003,
                'mssg' => $actionLogModel::ERRORCODE[100003],
                'data' => array()
            );
            return $returnArray;
        }

        if(!empty($_POST['mi_password'])){
            $password = sha1($_POST['mi_password']);
        }else{
            $returnArray = array(
                'code' => 100004,
                'mssg' => $actionLogModel::ERRORCODE[100004],
                'data' => array()
            );
            return $returnArray;
        }

        if(empty($returnArray)){
            $userModel = new \app\common\model\User();
            $userReultRow = $userModel->where(array('telephone'=>$username,'password'=>$password,'is_del'=>0))->find();
           var_dump($userReultRow);
            if($userReultRow){
                //判断是否为禁止登陆
                if($userReultRow['is_show'] == 1){
                    session('mitangUser',$userReultRow);
                    if(session('mitangUser')){
                        $this->redirect('login/login');
                    }
                }else{
                    $returnArray = array(
                        'code' => 100005,
                        'mssg' => $actionLogModel::ERRORCODE[100005],
                        'data' => array()
                    );
                    return $returnArray;
                }
            }else{
                echo "<script>alert('".$actionLogModel::ERRORCODE[100011]."');location.href='http://local.mitang.com/admin/login/login.html'</script>";
            }
        }

    }


    /**
     * 登出
     */
    public function signOut()
    {
        if(session('mitangUser')){
            session('mitangUser',null);
            $this->redirect('login/login');
        }else{

            $this->redirect('login/login');
        }

    }

    /**
     * 读取session信息
     */
    public function getUserInfo()
    {
      return  session('mitangUser');
    }





}
