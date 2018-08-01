<?php 
namespace app\common\model;
use think\Model;

/**
 * 用户模型
 * @author Wqzbxh
 * @date 2018-08-31 14:11
 * @version 1.0
 */
class ImageRecord extends Model
{

    // 表名
    
    // 外部编号头
    private $key = 'IMG';
    
    //获取外部编号  头字母 + 时间戳 + 8位随机数
    public function getEntityId() {
        return $this->key . time() . rand(10000000,99999999);
    }
    
    private function getLetter(){
        $letterCode = '';
        for($i=1;$i<=4;$i++){
            $letterCode .= chr(rand(97,122));
        }
        return $letterCode;
    }


    /**
     * 检查目录是否可写
     * @param  string   $path    目录
     * @return boolean
     */
    protected function checkPath($path)
    {
        if (is_dir($path)) {
            return true;
        }
    
        if (mkdir($path, 0755, true)) {
            return true;
        } else {
            $this->error = "目录 {$path} 创建失败！";
            return false;
        }
    }
    
    /**
     * 添加图片记录到数据库
     */
    
    public function addImageRecord($data)
    {
        if(empty($data) == false && is_array($data)){
            return self::create($data);
        }else{
            return false;
        }
    }
    
    /**
     * 上传文件方法
     * 
     *
     */

    public function uploadImageAction($file,$filepath)
    {
        $returnArray = array();
        if(empty($file) && is_array($file) && $file['size'] < 0){
            $returnArray = array(
                'code' => 0,
                'info' => '没有图片被上传！',
            );
        }else{
            self::checkPath($filepath);
            if($file['size'] < 4097152){
                if($file['type'] == 'image/bmp' || $file['type'] == 'image/gif' || $file['type'] == 'image/jpeg' || $file['type'] == 'image/png' || $file['type'] == 'application/pdf'){
                    $filename = self::getEntityId();
                    $imageMoveResult = move_uploaded_file($file['tmp_name'],$filepath.$filename.$file['name']);
                    if($imageMoveResult){
                        $data['tmp_name'] = $file['tmp_name'];
                        $data['size'] = $file['size'];
                        $data['path'] = $filepath.$filename.$file['name'];
                        $data['create_time'] = date('Y-m-d H:i:s');
                        $result = self::addImageRecord($data);
                        if($result){
                            $returnArray['info'] = $result['id'];
                        }
                    }
                }else{
                    $returnArray = array(
                        'code' => 0,
                        'info' => '图片类型错误！',
                    );
                }
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '图片超过2MB！'
                );
            }
    
            if(empty($returnArray) == false){
                $returnArray['code'] = 1;
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '没有图片被上传！',
                );
            }
        }
    
        return $returnArray;
    }
    
    
    public function ossClient()
    {
        Vendor('OSS.autoload');
        $accessKeyId = "LTAIYg0S1PVN0uOa";
        $accessKeySecret = "QSqntGS8HJBZD9A5eyam7NzGfL7EJh";
        $endpoint = "oss-cn-beijing.aliyuncs.com";
    
        try {
            $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
            return $ossClient;
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }
    
    
    /**
     * 上传临时图片到服务器
     */
    public function upImageTemp(){
        $imageArray = array();
        $letterCode = self::getLetter();
       $imageMoveResult = '';
        $indexModel = new \app\admin\model\Index();
        if(!empty($_FILES) || is_array($_FILES)  || $_FILES['size'] > 0){
            $filePath = 'public' . DS . 'luntan' . DS . 'imageTemp' .date('Y-m-d') .DS ;
            self::checkPath($filePath);
            $fileRow =  reset($_FILES);
            if($fileRow['type'] == 'image/jpeg' || $fileRow['type'] == 'image/gif' || $fileRow['type'] == 'image/bmp' || $fileRow['type'] == 'image/png'){
                $imageMoveResult = move_uploaded_file($fileRow['tmp_name'], $filePath.$letterCode.$fileRow['name']);  
            }
            if($imageMoveResult){
                $imageArray = array(
                    'code' => 1,
                    'msg' => $indexModel::ERROR_CODE[1],
                    'data' =>  $filePath.$letterCode.$fileRow['name']
                );
            }else{
                $imageArray = array(
                    'code' => 800001,
                    'msg' => $indexModel::ERROR_CODE[800001],
                    'data' => array()
                );
            }
        }else{
            $imageArray = array(
                'code' => 800002,
                'msg' => $indexModel::ERROR_CODE[800002],
                'data' => array()
            );
        }
        
        return $imageArray;
    }
}