<!-- @TODO divide and wrap div content, left side form, right side display feedback messages   -->

<div class="content">
    <h1>Register</h1>
    <div class="wrapper">
        <div class="messages">
            <?php $this->renderMessages(); ?>
        </div>


        <!-- This is going to Register Controller class -->
        <form class="form-box" method="post" name="register_form"
              action="<?php echo Config::get('BASE_URL');?>register/registerNewUserAction">

            <ul class="form-style">
                <li><label>Username<span class="required">*</span></label>
                    <input type="text" name="user_name" class="field-input-normal" placeholder="username"
                           autocomplete="off" value="<?php echo Sanitize::get('user_name');?>"/>
                </li>
                <li><label>Mail<span class="required">*</span></label>
                    <input type="text" name="user_email" class="field-input-normal" placeholder="email"
                           autocomplete="off" value="<?php echo Sanitize::get('user_email');?>"/>

                <li>
                    <label>Password<span class="required">*</span></label>
                    <input type="password" name="user_password" class="field-input-normal" placeholder="password"/>
                </li>
                <li>
                    <label>Password<span class="required">*</span></label>
                    <input type="password" name="user_password_again" class="field-input-normal" placeholder="password"/>
                </li>
                <li><input type="submit" value="Submit"/></li>
            </ul>
        </form>
    </div>
</div>







	
