<?php 
class InformationController{
   // chính sách đổi trả
    function returnPolicy(){
        require 'view/information/returnPolicy.php';

    }

    function paymentPolicy(){
        require 'view/information/paymentPolicy.php';
    }
    // chính sách giao hàng
    function deliveryPolicy(){
        require 'view/information/deliveryPolicy.php';
    }
}

?>