<?php 
class AuthController {
    function login()
    {
       $email =$_POST['email'];
       $password =$_POST['password'];
       $customerRepository = new CustomerRepository();
       $customer =$customerRepository->findEmail($email);
       if(!$customer){
        $_SESSION['error'] = "Email $email không tồn tại trong hệ thống";
        header('location: /');
        exit;

       }
       // kiểm tra mật khẩu đúng không
       // password_verify(password chưa mã hóa, password đã mã hóa)
       if(!password_verify($password,$customer->getPassword())){
        $_SESSION['error'] = "Sai mật khẩu";
        header('location: /');
        exit;
       }
       // kiểm tra tài khoản kích hoạt chưa
       // password_verify(password chưa mã hóa, password đã mã hóa)
       if(!$customer->getIsActive()){
        $_SESSION['error'] = "Lỗi: Tài khoản $email chưa được kích hoạt";
        header('location: /');
        exit;
       }
      $_SESSION['email'] =$email;
      $_SESSION['name'] =$customer->getName();
      header('location: /');
    }
    function logout()
    {
     session_destroy();
     header('location: /');
        
    }
}

?>