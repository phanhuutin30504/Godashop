<?php 


class ContactController {
    // hiển thị form liên hệ
 function form(){
   require 'view/contact/form.php';


 }
 function sendEmail(){
  $name =$_POST['fullname'];
  $email =$_POST['email'];
  $mobile =$_POST['mobile'];
  $message =$_POST['content'];

  $domain = get_domain();

  
  $emailService = new EmailService();
  $to = SHOP_OWNER;
  $subject = APP_NAME .'- Liên hệ';
  $content = "Xin chào chủ cửa hàng,<br> 
  Dưới đây là thông tin liên hệ <br>
  Tên: $name,<br>
  Email: $email,<br>
  Mobile: $mobile,<br>
  Nội dung: $message,<br>
  Được gửi từ trang web: $domain";
  $emailService->send($to,$subject,$content);
 } 

}


?>