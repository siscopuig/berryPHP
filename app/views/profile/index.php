<?php
/*
 *  Since 5.4 PHP version http://php.net/manual/en/language.basic-syntax.phptags.php
 *  The tag <?= is always available regardless of the short_open_tag ini setting.
 * */
?>
<?php //var_dump($this->user);  ?>
<div class="content">
    <h1>User profile</h1>

    <div class="wrapper-settings">
        <!-- userUpdateProfilePicture() -->
        <div class="profile-picture">
            <?php if(Session::get('user_has_picture') == 1)  :?>
                <img class="rounded" src=" <?php echo Session::get('user_filename'); ?>" alt="image"/>
            <?php else: ?>
                <img class="rounded"
                     src=" <?php echo Config::get('BASE_URL');?>app/public/images/default_profile.jpg" alt="image"/>
            <?php endif; ?>
        </div>

        <div class="profile-picture-edit">
            <a href="#" class="style show-profile">edit</a>
            <div class="hidden profile-form">
                <form class="form-box" enctype="multipart/form-data"
                      action="<?php echo Config::get('BASE_URL');?>profile/uploadProfilePictureAction" method="post" >
                    <input name="file" type="file" />
                    <input type="submit"  value="upload"/>

                </form>
            </div>
        </div>
        <ul class="profile-setting-list">
            <!-- userUpdateUsername() -->
            <li class="leftcol"><?php echo Session::get('user_name');?><span
                    class="rightcol"><a href="#" class="style show-username">edit</a></span>
                <div class="hidden username-form">
                    <form action="<?php echo Config::get('BASE_URL'); ?>user/editUsernameAction" method="post">
                        <label for="username"></label>
                        <input type="text" id="username" name="user_name" autocomplete="off"
                               value="<?php echo Session::get('user_name');?>"/>
                        <input type="submit" value="Save">
                    </form>
                </div>
            </li>
            <!-- userUpdateEmail() -->
            <li class="leftcol"><?php echo Session::get('user_email');?><span
                    class="rightcol"><a href="#" class="style show-email">edit</a></span>
                <div class="hidden email-form">
                    <form action="<?php echo Config::get('BASE_URL'); ?>user/updateUserEmail" method="post">
                        <label for="email"></label>
                        <input type="email" id="email" name="user_email" autocomplete="off"
                               value="email"/>
                        <input type="email" id="email" name="user_email_again" autocomplete="off"
                               value="email again"/>
                        <input type="submit" value="Save">
                    </form>
                </div>
            </li>
            <!-- userUpdatePassword() -->
            <li class="leftcol">password<span class="rightcol"><a href="#" class="style show-password">edit</a></span>
                <div class="hidden password-form">
                    <form action="<?php echo Config::get('BASE_URL'); ?>user/editUserPasswordAction" method="post">
                        <label for="email"></label>
                        <input type="email" id="email" name="user_password" autocomplete="off"
                               value="password"/>
                        <input type="email" id="email" name="user_password_again" autocomplete="off"
                               value="password again"/>
                        <input type="submit" value="Save">
                    </form>
                </div>
            </li>
        </ul>
        <div class="messages"><?php $this->renderMessages(); ?></div>
    </div><!-- end wrapper -->
</div><!-- end content -->












