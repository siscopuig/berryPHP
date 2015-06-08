<div class="content">
    <h1>Forgot password</h1>
    <div class="messages"><?php $this->renderMessages(); ?></div>
    <div class="wrapper-settings">
        <form class="form-box" method="post" name="register_form"
              action="<?php echo Config::get('BASE_URL');?>login/setNewUserPassword">
            <ul class="form-style">
                <li>
                    <input type="hidden" name="user_name" value="<?php echo Sanitize::escape($this->user_name);?>" />
                    <input type="hidden" name="user_password_reset_hash" value="<?php echo $this->user_password_reset_hash;?>" />
                </li>
                <li>
                    <label>new password<span class="required">*</span></label>
                    <input type="password" name="user_password" class="field-input-normal"
                           autocomplete="off" placeholder="new password"/>
                </li>
                <li>
                    <label>new password again<span class="required">*</span></label>
                    <input type="password" name="user_password_again" class="field-input-normal"
                           autocomplete="off" placeholder="new password again"/>
                </li>
                <li><input type="submit" value="submit" /></li>
            </ul>
        </form>
    </div><!-- end wrapper -->
</div><!-- end content -->












