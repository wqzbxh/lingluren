<?php 
namespace app\common\model;
use think\Model;


/**
 * 来思安小程序模型
 * @author wqzbxh
 * @date Wed Aug 01 2018 17:56:03 GMT+0800 (中国标准时间)
 * @version 1.0
 */

class Applet extends Model
{
    /**
     * 获取openid
     * @param appid 小程序appid
     * @param secret 小程序秘钥
     *
     */
    public function getOpenID($appid,$secret,$code)
    {
        $output = array();
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}