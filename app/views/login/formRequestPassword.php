<div class="content">
    <h1>Request password</h1>
    <div class="wrapper">
        <div class="messages"><?php $this->renderMessages(); ?></div>

        <form class="form-box" method="post" name="register_form"
              action="<?php echo Config::get('BASE_URL');?>login/resetUserPassword">
            <ul class="form-style">
                <li>
                    <label>email<span class="required">*</span></label>
                    <input type="text" name="user_email" class="field-input-normal"
                           autocomplete="off" placeholder="Your email"/>
                </li>
                <li><input type="submit" value="Submit"/></li>
            </ul>
        </form>
    </div><!-- end wrapper -->
</div><!-- end content -->