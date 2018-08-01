<?php
namespace app\api\controller;
use think\model;

/*
 * 老师小程序接口
 */
class Teacher
{

    /**
     * 密钥加密
     */
    public function checkApi()
    {
        if(empty($_POST['key']) || empty($_POST['secret_key']) || $_POST['key'] != 'mitang2018' || $_POST['secret_key'] != 'd1a79$fKBl1f&cY!e&5WWDFG#Cl%Lkyp'){
            $actionLogModel = new \app\common\model\ActionLog();
            $returnArray = array(
                'code' => 900001,
                'msg' => $actionLogModel::ERRORCODE[$actionLogModel],
                'data' => array()
            );
            return json_encode($returnArray);
            exit;
        }
    }

    /**
     *code换取openid
     *code
     */
    public function getOpenId(){
        self::checkApi();
        $actionLogModel = new \app\common\model\ActionLog();
        if(!empty($_POST['code']) && !empty($_POST['appid']) && !empty($_POST['secret'])){
            $appletModel = new \app\common\model\Applet();
            $result = $appletModel->getOpenID($_POST['appid'],$_POST['secret'],$_POST['code']);
            if($result){
                $returnArray = array(
                    'code' => 1 ,
                    'msg' => $actionLogModel::ERRORCODE[1],
                    'data' => $result
                );
            }else{
                $returnArray = array(
                    'code' => 1 ,
                    'msg' => $actionLogModel::ERRORCODE[1],
                    'data' => $result
                );
            }
        }
    }
}
