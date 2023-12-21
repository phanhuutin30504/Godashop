<?php
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
        if(!$customerRepository->update($customer)){
            $_SESSION['error']= $customerRepository->getError();
            header('location: ?c=customer&a=show');
            exit;
        }
        $_SESSION['success']= 'đã cập nhật thành công';
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
}