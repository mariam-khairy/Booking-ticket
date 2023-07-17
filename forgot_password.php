<?php
ob_start();

include_once __DIR__."\app/database/config.php";
include_once __DIR__."\app/database/operations.php";
include_once __DIR__."\app/models/user.php";


if($_POST){

$errors = [];

$user = new User();


        // Validate email
        if (empty($_POST['email'])) {
            $errors['email'] = "Please enter an email address";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address";
        } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST['email'])) {
            $errors['email'] = "Please enter a valid email address in the correct format";
        } elseif ($user->setEmail($_POST['email'])->unique()) {
            $errors['email'] = "This email is already in used";
        }

        if(empty($errors)){
            $user_object = new User();
            $user_object->setEmail($_POST['email']);
            $result = $user_object->get_user_by_email();
            if($result){
                $user = $result->fetch_object();
                $code = rand(10000, 99999);
                $user_object->setCode($code);
                $result_update = $user_object->update_code_by_email();
                if($result_update){
                    
                }else{
                    $errors['some_wronf'] = "Somthing wrong";
                }
            }else{
                $errors['wrong_email'] = "This Email Not Exists";
            }
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
                    <h4> </h4>
                </a>
            </div>
            <div class="tab-content">
                <div id="lg1" class="tab-pane active">
                    <div class="login-form-container">
                        <div class="login-register-form">
                            <form method="post">
                            <?php if(isset($errors['wrong_email'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?php echo $errors['wrong_email']; ?>
                                    </div>
                                    <?php endif; ?>
                                <input name="email" type="email" name="email" placeholder="Enter Your Email Address"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <?php if(isset($errors['email'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?php echo $errors['email']; ?>
                                    </div>
                                    <?php endif; ?>
                                <div class="button-box">
                                    <button type="submit"><span>Verify Email Address</span></button>
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



<style>
    /* Styling for the login-register-area */
.login-register-area {
  background-color: #f5f5f5;
}

/* Styling for the container within the login-register-area */
.login-register-area .container {
  max-width: 960px;
  margin: 0 auto;
}

/* Styling for the login-register-wrapper */
.login-register-wrapper {
  background-color: #fff;
  border-radius: 10px;
  padding: 40px;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
}

/* Styling for the login-register-tab-list */
.login-register-tab-list {
  margin-bottom: 30px;
}

/* Styling for the active tab */
.login-register-tab-list .active h4 {
  color: #333;
  font-weight: bold;
}

/* Styling for the form input */
.login-register-form input[type=email] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 20px;
}

/* Styling for the button */
.login-register-form button[type=submit] {
  background-color: #333;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  cursor: pointer;
}

/* Styling for the button hover state */
.login-register-form button[type=submit]:hover {
  background-color: #555;
}

</style>

<?php
ob_end_flush();
?>