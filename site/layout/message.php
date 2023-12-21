<?php 
$message ='';
$classType='';
if(!empty($_SESSION['success'])){
    $message =$_SESSION['success'];
    // xóa phần tử có key success
    unset($_SESSION['success']);
    $classType='alert-success';
}
if(!empty($_SESSION['error'])){
    $message =$_SESSION['error'];
    // xóa phần tử có key success
    unset($_SESSION['error']);
    $classType='alert-danger';
}
if($message):

?>

<!-- .alert.alert-success -->
<div class="alert <?=$classType?> mt-3 text-center"><?=$message?></div>
<?php endif?>