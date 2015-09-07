<?php
header("Content-type:text/html;Charset=utf-8");
require_once '../lib/config.php';
require_once '_check.php';
require_once 'autoload.php';
#始终附带用户IP向谷歌提交验证
$secret = '';
$recaptcha = new \ReCaptcha\ReCaptcha($secret);
$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
if ($resp->isSuccess()){
   #如果响应成功
   #权限检查
   if(!$oo->is_able_to_check_in()){
       $transfer_to_add = 0;
   }else {
       if ($oo->unused_transfer() < 2048 * $tomb) {
           $transfer_to_add = rand(1024, 2048);
       } else {
           $transfer_to_add = rand($check_min, $check_max);
       }
       $oo->add_transfer($transfer_to_add*$tomb);
       $oo->update_last_check_in_time();
       $b = '<script>alert("获得了'.$transfer_to_add.'MB流量")</script><meta http-equiv="refresh" content="0;url=/user">';
   }
}else{
   #如果响应不成功
    $transfer_to_add = 0;
    $b = '<script>alert("人机验证失败")</script><meta http-equiv="refresh" content="0;url=/user">';
}
echo $b;
