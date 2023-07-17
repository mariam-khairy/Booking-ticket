<?php
$title = "Reset Password";
include_once "layouts/header.php";
include_once "app/middleware/guest.php";
if(empty($_SESSION['user_email'])){
    header('location:login.php');die;
}
include_once "app/requests/validiation.php";
include_once "app/models/User.php";

if ($_POST) {
$errors= [];

$password_validation = new validation('password', $_POST['password']);
$password_required_result = $password_validation->required();
if (empty($password_required_result)) {
$password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
$passwoed_regex_result = $password_validation->regex($password_pattern);
if (empty($passwoed_regex_result)) {
$password_confirm_result = $password_validation->confimation($_POST['password_confirmation']);
if (!empty($password_confirm_result)) {
$errors['password']['confirm'] = $password_confirm_result;
}
} else {
$errors['password']['regex'] = "Minimum eight and maximum 15 characters, at least one uppercase letter, one lowercase letter, one number and one special character";
}
} else {
$errors['password']['required'] = $password_required_result;
}

$confirm_password_validation = new validation('password confirmation', $_POST['password_confirmation']);
$confirm_password_required_result = $confirm_password_validation->required();
if (!empty($confirm_password_required_result)) {
$errors['confirm']['required'] = $confirm_password_required_result;
}

if (empty($errors)) {
$user_object = new User();
$user_object->setpassword($_POST['password']);
$user_object->setEmail($_SESSION['user_email']);
$update_password_result = $user_object->update_password_by_email();
if ($update_password_result) {
unset($_SESSION['user_email']);
$success = "Your Password Has Been Successfully Updated";
header('Refresh:3; url=login.php');
}
} else {
$errors['password']['wronge'] = "Somthing went wronge";
}
}
?>

<div class="login-register-area ptb-100">
<div class="container">
<div class="row">
<div class="col-lg-7 col-md-12 ml-auto mr-auto">
<div class="login-register-wrapper">
<div class="login-register-tab-list nav">
<a class="active" data-toggle="tab" href="#lg1">
<h4> <?=  $title ?> </h4>
</a>
</div>
<div class="tab-content">
<div id="lg1" class="tab-pane active">
<div class="login-form-container">
<div class="login-register-form">
<?php
if(isset($success)){
echo "<div class='alert alert-success'>$success</div>";
}
?>
<form method="post">
<input type="password" name="password" placeholder=" Enter Your New Password">
<?php
if(!empty($errors['password'])){
foreach($errors['password'] as $key=>$value){
echo "<div class='alert alert-danger'>$value</div>";
}
}
?>
<input type="password" name="password_confirmation" placeholder="Confirm Password">
<?php
if(!empty($errors['confirm'])){
foreach($errors['confirm'] as $key=>$value){
echo "<div class='alert alert-danger'>$value</div>";
}
}
?>
<div class="button-box">
<div class="login-toggle-btn">
<input type="checkbox" name="remember_me">
<a href="forget_password.php">Forgot Password?</a>
</div>
<button type="submit" name="login"><span><?= $title ?></span></button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<?php
include_once "layouts/footer_scripts.php";
?>