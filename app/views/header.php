<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Define title -->
		<title></title>

		<!-- CSS FILES -->		
		<link href="<?php echo Config::get('BASE_URL'); ?>app/public/css/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Config::get('BASE_URL'); ?>app/public/css/profile.css" rel="stylesheet" type="text/css">

        <!-- Javascript files -->
		<script src="<?php echo Config::get('BASE_URL'); ?>app/public/js/jquery.js" type="text/javascript"></script>
		<script src="<?php echo Config::get('BASE_URL'); ?>app/public/js/custom.js" type="text/javascript"></script>
		<?php
			// This is inside the view dashboard controller
			if(isset($this->js)) {
				foreach($this->js as $js) {
					echo '<script type="text/javascript" src="'.Config::get('BASE_URL').'app/views/'.$js.'"></script>';
				}
			}
		?>
	</head>
	<!-- BODY -->
	<!-- MAIN NAVIGATION -->
	<body>
		<div id="wrap">
			<div id="header">
                <div id="header-left-box">
                    <ul class="nav">
                        <!-- NOTE THESE LINKS DON'T HAVE AN EXTENSION FILE -->
                        <!-- Here we check if the user loggedIn. If not (false) -->
                        <?php if (Session::get('user_logged_in') == false):?>
                            <li><a href="<?php echo Config::get('BASE_URL'); ?>index">Home</a></li>
<!--                            <li><a href="--><?php //echo Config::get('BASE_URL'); ?><!--help">Help</a></li>-->
                            <li><a href="<?php echo Config::get('BASE_URL'); ?>test">Test</a></li>
                        <?php endif; ?>

                        <!-- Here we check the level of authorisation of the user with sessions. if owner
                         will appear a link users to access	and change privileges for other users -->
                        <?php if (Session::get('user_logged_in') == true):?>
                            <?php if (Session::get('user_account_role') == 'owner'):?>
                                <li><a href="<?php echo Config::get('BASE_URL'); ?>user/index">Users</a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo Config::get('BASE_URL'); ?>dashboard">Dashboard</a></li>
                            <li><a href="<?php echo Config::get('BASE_URL'); ?>profile">Profile</a></li>
                            <li><a href="<?php echo Config::get('BASE_URL'); ?>login/logout">Logout</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo Config::get('BASE_URL'); ?>login">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div id="header-right-box">
                    <?php if(Session::get('user_logged_in') == true)  :?>
                        <a class="settings-link" href="#"><?php echo Session::get('user_name');?></a>
                    <?php endif; ?>
                    <?php if(Session::get('user_has_picture') == 1)  :?>
                        <img class="rounded" src=" <?php echo Session::get('user_filename'); ?>" alt="image"/>
                    <?php else: ?>
                        <img class="rounded" src=" <?php echo Config::get('BASE_URL');?>app/public/images/default_profile.jpg" alt="image"/>
                    <?php endif; ?>
                </div><!-- end header-right-box -->
			</div><!-- end header -->
