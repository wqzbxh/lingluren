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
    public $fuzzy_query = 'nick_name|telephone|name|address';
    
    //嘀友APP接口错误状态码


    public $field = 'id,nick_name,name,telephone,address,sex,r_id';



    /**
     * 嘀友用户手机号加密
     * @param string $telephone
     * @return array
     */
    public function diuberEncode($telephone)
    {
        $returnArray = array();
        if($telephone){
            if(strlen($telephone) == 11){
                $final = '';
                $beforeStr = substr($telephone, 0, 3);
                $middleStr = substr($telephone, 3, 4);
                $afterStr = substr($telephone, 7, 4);
                $lastPhone = $beforeStr . $afterStr . $middleStr;
                for($i=0;$i<11;$i++){
                    if($lastPhone[$i] == '0'){
                        $final = $final . '1';
                    }elseif($lastPhone[$i] == '1'){
                        $final = $final . '4';
                    }elseif($lastPhone[$i] == '2'){
                        $final = $final . '5';
                    }elseif($lastPhone[$i] == '3'){
                        $final = $final . '3';
                    }elseif($lastPhone[$i] == '4'){
                        $final = $final . '2';
                    }elseif($lastPhone[$i] == '5'){
                        $final = $final . '9';
                    }elseif($lastPhone[$i] == '6'){
                        $final = $final . '6';
                    }elseif($lastPhone[$i] == '7'){
                        $final = $final . '8';
                    }elseif($lastPhone[$i] == '8'){
                        $final = $final . '7';
                    }elseif($lastPhone[$i] == '9'){
                        $final = $final . '0';
                    }
                }
                
                $s1 = $final[0];
                $phone1 = substr($final,0,(int)$s1).$s1.substr($final, (int)$s1);
                $s2 = $phone1[9];
                $phone2 = substr($phone1,0,(int)$s2).$s2.substr($phone1, (int)$s2);
                $s3 = $phone2[12];
                $phone = substr($phone2,0,(int)$s3).$s3.substr($phone2, (int)$s3);
                
                $returnArray = array(
                    'code' => 1,
                    'info' => $phone
                );
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '手机号不正确！'
                );
            }
        }else{
            $returnArray = array(
                'code' => 0,
                'info' => '手机号不能为空！'
            );
        }
        
        return $returnArray;
    }
    
    
    
    /**
     * 嘀友用户手机号解密
     * @param string $telephone
     * @return array
     */
    public function diuberDecode($telephone)
    {
        $returnArray = array();
        if($telephone){
            if(strlen($telephone) == 14){
                $s1 = $telephone[13];
                $phone1 = substr($telephone,0,(int)$s1).substr($telephone, (int)$s1 + 1);
                $s2 = $phone1[10];
                $phone2 = substr($phone1,0,(int)$s2).substr($phone1, (int)$s2 + 1);
                $s3 = $phone2[0];
                $telephone = substr($phone2,0,(int)$s3).substr($phone2, (int)$s3 + 1);
                
                $final = '';
                for($i=0;$i<11;$i++){
                    if($telephone[$i] == '0'){
                        $final = $final . '9';
                    }elseif($telephone[$i] == '1'){
                        $final = $final . '0';
                    }elseif($telephone[$i] == '2'){
                        $final = $final . '4';
                    }elseif($telephone[$i] == '3'){
                        $final = $final . '3';
                    }elseif($telephone[$i] == '4'){
                        $final = $final . '1';
                    }elseif($telephone[$i] == '5'){
                        $final = $final . '2';
                    }elseif($telephone[$i] == '6'){
                        $final = $final . '6';
                    }elseif($telephone[$i] == '7'){
                        $final = $final . '8';
                    }elseif($telephone[$i] == '8'){
                        $final = $final . '7';
                    }elseif($telephone[$i] == '9'){
                        $final = $final . '5';
                    }
                }
                $beforeStr = substr($final, 0, 3);
                $middleStr = substr($final, 3, 4);
                $afterStr = substr($final, 7, 4);
                $lastPhone = $beforeStr . $afterStr . $middleStr;
                $returnArray = array(
                    'code' => 1,
                    'info' => $lastPhone
                );
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '手机号不正确！'
                );
            }
        }else{
            $returnArray = array(
                'code' => 0,
                'info' => '手机号不能为空！'
            );
        }
        
        return $returnArray;
    }
    
    
    /**
     *  校验请求是否合法
     */
    public function verifyAccessToken($timeStamp, $token, $diuberId)
    {
        $code = 0;
        if($timeStamp && $token && $diuberId){
            $diuberUser = self::get(array('diuberId'=>$diuberId));
            if($diuberUser){
                $diuberUser = $diuberUser->toArray();
                if($diuberUser['token']){
                    $verifyToken = md5($timeStamp.substr($diuberUser['token'], 5,16));
                    if($verifyToken == $token){
                        $code = 1;
                    }
                }
            }
        }
        
        return $code;
    }
    
    

    
    
    /**
     * 计算两个坐标之间的距离
     * @param unknown $lat1 纬度
     * @param unknown $lng1 经度
     * @param unknown $lat2 纬度
     * @param unknown $lng2 经度
     * @param bool $miles 是否返回米（默认米）
     * @return number
     */
    function distance($lat1, $lng1, $lat2, $lng2, $miles = true)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;
        $r = 6372.797;
        // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat/2)*sin($dlat/2)+cos($lat1)*cos($lat2)*sin($dlng/2)*sin($dlng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        
        return ($miles ? $km : ($km * 1000));
    }

    
}