<!-- This form administrate privileges to the users  -->

<div class="content">
    <h1>User Management roles</h1>
    <div class="wrapper">
        <table class="table-profile">
            <thead>
            <tr>
                <td>id</td>
                <td>username</td>
                <td>type account</td>
                <td>picture</td>
                <td>Edit</td>
                <td>Delete</td>
            </tr>
            </thead>
            <?php foreach($this->users as $users => $user) { ?>
                <tbody>
                <tr>
                    <?php if ($user['user_has_picture'] == 1)  :?>
                        <td class="user-block"><img class="rounded" src="<?php echo $user['user_filename'] ;?>" alt="image"/></td>
                    <?php else: ?>
                        <td class="user-block">
                            <img class="rounded" src="<?php echo Config::get('BASE_URL');?>app/public/images/default_profile.jpg" alt="image"/>
                        </td>
                    <?php endif; ?>

                    <td class="user-block"> <? echo $user['user_id'] ?>   </td>
                    <td class="user-block"> <? echo $user['user_name'] ?> </td>
                    <td class="user-block"> <? echo $user['user_account_role'] ?> </td>
                    <td class="user-block">
                        <a class="edit" href=" <?php echo Config::get('BASE_URL').'user/ShowEditUserRole/' . $user['user_id'] ?>">Edit</a>
                    </td>
                    <td class="user-block"><a class="del"  href=" <?php echo Config::get('BASE_URL').'user/deleteUser/' .$user['user_id'] ?>">Delete</a></td>
                </tr>
                </tbody>
            <?php } ?>
        </table>
    </div>
</div>