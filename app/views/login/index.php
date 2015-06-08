<div class="content">


    <div class="wrapper">
        <h1>Login</h1>
        <div class="messages">
            <?php $this->renderMessages(); ?>
        </div>
        <!-- We want to call a function run() inside our class  /controller/Login.php
         submit button will call it. 	 -->
        <form  class="form-box" action="<?php echo Config::get('BASE_URL'); ?>login/loginAction" method="post">
            <ul class="form-style">
                <li><label>Username<span class="required">*</span></label>
                    <input type="text" name="user_name" class="field-input-normal"
                           autocomplete="off" placeholder="username"/>
                    <!-- link to request username -->
                    <a class="forgot-link"
                       href="<?php echo Config::get('BASE_URL');?>login/showFormRequestUsername">I forgot my username</a>
                </li>
                <li>
                    <label>Password<span class="required">*</span></label>
                    <input type="password" name="user_password" class="field-input-normal"
                           autocomplete="off" placeholder="password"/>
                    <!-- link to request new password -->
                    <a class="forgot-link"
                       href="<?php echo Config::get('BASE_URL');?>login/showFormRequestPassword">I forgot my password</a>
                </li>
                <li>
                    <input type="submit" value="Submit"/>
                </li>
                <li>
                    <h4 class="header-login">no account yet?</h4>
                    <div class="register-box">
                        <a class="register-link" href="<?php echo Config::get('BASE_URL'); ?>register">Register</a>
                    </div>
                </li>
            </ul>
        </form
    </div>
</div>



