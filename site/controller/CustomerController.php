<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class CustomerController
{
    //hiển thị thông tin tài khoản
    function show()
    {
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        require 'view/customer/show.php';
    }

    //cập nhật
    function updateInfo()
    {
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        $customer->setName($_POST['fullname']);
        $customer->setMobile($_POST['mobile']);
        $password = $_POST['password'];
        $current_password = $_POST['current_password'];
        if ($password && $current_password) {
            // kiểm tra password hiện tại đúng chưa
            if (!password_verify($current_password, $customer->getPassword())) {
                $_SESSION['error'] = 'Sai mật khẩu hiện tại';
                header('location: ?c=customer&a=show');
                exit;
            }
            // mã hóa mật khẩu 
            //thành 1 chuỗi
            $encode_password = password_hash($password, PASSWORD_BCRYPT);
            $customer->setPassword($encode_password);
        }
        if (!$customerRepository->update($customer)) {
            $_SESSION['error'] = $customerRepository->getError();
            header('location: ?c=customer&a=show');
            exit;
        }
        $_SESSION['success'] = 'đã cập nhật thành công';
        $_SESSION['name'] = $customer->getName();
        header('location: ?c=customer&a=show');
    }

    // hiển thị địa chỉ giao hành mặc định
    function shippingDefault()
    {
        require 'view/customer/shippingDefault.php';
    }
    // hiển thị địa chỉ giao hành mặc định
    function orders()
    {
        require 'view/customer/orders.php';
    }
    // hiển thị địa chỉ giao hành mặc định
    function orderDetail()
    {
        require 'view/customer/orderDetail.php';
    }
    function register()
    {
        // var_dump($_POST);
        $secret = GOOGLE_RECAPTCHA_SECRET;
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $remoteIp = '127.0.0.1';

        $gRecaptchaResponse = $_POST['g-recaptcha-response'];


        $resp = $recaptcha->setExpectedHostname('godashop.com')

            ->verify($gRecaptchaResponse, $remoteIp);

        if (!$resp->isSuccess()) {
            // Verified!

            $errors = $resp->getErrorCodes();
            //implode là chuyển hàm array thành chuỗi
            $_SESSION['error'] = implode('<br>', $errors);
            header('location: /');
            exit;
        }
        $data = [];
        $data["name"] = $_POST['fullname'];
        $data["password"] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $data["mobile"] = $_POST['mobile'];
        $data["email"] = $_POST['email'];
        $data["login_by"] = 'form';
        $data["shipping_name"] = $_POST['fullname'];
        $data["shipping_mobile"] = $_POST['mobile'];
        $data["ward_id"] = null;
        $data["is_active"] = 0;
        $data["housenumber_street"] = '';
        $customerRepository = new CustomerRepository();
        if (!$customerRepository->save($data)) {
            $_SESSION['error'] = $customerRepository->getError();
            header('location: /');
            exit;
        }
        $email = $data["email"];
        $_SESSION['success'] = "Đã tạo tài khoản thành công";

        // Gửi mail kích hoạt tài khoản
        $emailService = new EmailService();
        $to = $email;
        $domain = get_domain();
        $key = JWT_KEY;
        $payload = [
            'email' => $email,

        ];


        $jwt = JWT::encode($payload, $key, 'HS256');
        $activeAccountLink = get_domain_site() . "?c=customer&a=active&token=$jwt";

        $subject = 'Godashop - Verify account';
        $content = " Dear $email, <br> Vui lòng click vào link bên dưới để kích hoạt tài khoản <br> <a href='$activeAccountLink'> Active Account</a> <br> được gởi từ trang web $domain";

        $emailService->send($to, $subject, $content);

        header('location: /');
    }
    function active(){
        // giải mã lấy email và active account tương ứng với email đó
        $jwt = $_GET['token'];
        $key = JWT_KEY;
        $decoded = JWT::decode($jwt,new key($key,'HS256'));
        $email = $decoded->email;
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);

        $customer->setIsActive(1);
        if (!$customerRepository->update($customer)) {
            $_SESSION['error'] = $customerRepository->getError();
            header('location: /');
            exit;
        }
        $_SESSION['success'] = "Tài khoản $email đã được kích hoạt";
        header('location: /');
        exit;

    }
    function notExistingEmail()
    {
        $email = $_GET['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        if (!empty($customer)) {
            echo 'false';
            return;
        }
        echo 'true';
    }
    // mã hóa
    function test1()
    {
        $key = 'con bò';
        $payload = [
            'email' => 'abc@gmail.com',

        ];


        $jwt = JWT::encode($payload, $key, 'HS256');
        echo $jwt;
    }
    // giải mã
    function test2()
    {
        $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImFiY0BnbWFpbC5jb20ifQ.iawrdZExtfF3Etly3zc80cWSRSxxSVIUEj-N2rDYBt8';
        $key = 'con bò';
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        print_r($decoded);
    }
}