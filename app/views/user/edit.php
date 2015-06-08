<div class="content">
    <h1>Edit user role</h1>
    <div class="wrapper">
        <div class="left-box-content">
            <form method="post" class="form-box"
                  action="<?php echo Config::get('BASE_URL');?>user/changeUserRole/<?= $this->user['user_id'] ?>">
                <ul class="form-style">
                    <li>
                        <label for="username">username</label>
                        <input type="text" name="user_name" id="username" value="<?php echo $this->user['user_name'];?>"/>
                    </li>
                    <li>
                        <label for="role">user role</label>
                        <select name="user_account_role" id="role" >
                            <?php if (Session::get('user_account_role') == 'default') { ?>
                                <option value="admin"<?php if($this->user['user_account_role'] == 'admin') echo 'selected';?>>Admin</option>
                                <option value="owner"<?php if($this->user['user_account_role'] == 'owner') echo 'selected';?>>Owner</option>
                                <?php  } elseif (Session::get('user_account_role') == 'admin') { ?>
                                    <option value="default"<?php if($this->user['user_account_role'] == 'default') echo 'selected';?>>Default</option>
                                    <option value="owner"<?php if($this->user['user_account_role'] == 'owner') echo 'selected';?>>Owner</option>
                                        <?php } elseif (Session::get('user_account_role') == 'owner') { ?>
                                            <option value="default"<?php if($this->user['user_account_role'] == 'default') echo 'selected';?>>Default</option>
                                            <option value="admin"<?php if($this->user['user_account_role'] == 'admin') echo 'selected';?>>Admin</option>
                                        <?php }  ?>
                        </select>
                    </li>
                    <li><input type="submit" value="change user role" /></li>
                </ul>
            </form>
        </div>
        <div class="right-box-content">
            <div class="list-user-role">
                <table class="table-profile">
                    <thead>
                    <tr>
                        <td>picture</td>
                        <td>id</td>
                        <td>username</td>
                        <td>role</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php if ($this->user['user_has_picture'] == 1) :?>
                            <td class="user-block"><img class="rounded" src="<?php echo $this->user['user_filename'] ;?>" alt="image"/></td>
                        <? else : ?>
                            <td class="user-block">
                                <img class="rounded" src="<?php echo Config::get('BASE_URL');?>app/public/images/default_profile.jpg" alt="image"/>
                            </td>
                        <?php endif; ?>

                        <td class="user-block"> <? echo $this->user['user_id'] ?>   </td>
                        <td class="user-block"> <? echo $this->user['user_name'] ?> </td>
                        <td class="user-block"> <? echo $this->user['user_account_role'] ?> </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>