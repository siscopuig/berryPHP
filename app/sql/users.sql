
#---------- mvc ---------------

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_active` tinyint(1) NOT NULL DEFAULT '0',
  `user_type` enum('owner','admin','default') COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_filename` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_original_filename` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_has_picture` tinyint(1) NOT NULL DEFAULT '0',
  `user_remember_me_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_creation_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_last_login_timestamp` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_password`, `user_email`, `user_active`, `user_type`, `user_filename`, `user_original_filename`, `user_has_picture`, `user_remember_me_token`, `user_creation_timestamp`, `user_last_login_timestamp`) VALUES
  (1, 'sisco', 'password', 'demo@demo.com', 1, 'owner', NULL, NULL, 0, NULL, '2015-05-01 17:31:25', 1422209189);


-- --------------------------------------------------------

--
-- Old Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` char(40) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(100) DEFAULT NULL,
  `ori_name` varchar(100) DEFAULT NULL,
  `path_image` varchar(60) DEFAULT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`firstname`),
  UNIQUE KEY `email` (`email`),
  KEY `login` (`email`,`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `username`, `email`, `password`, `dateAdded`, `filename`, `ori_name`, `path_image`, `active`) VALUES
  (1, 'Sisco', 'Puig', 'sisco', 'sisco@siscopuig.com', 'password', '2015-04-30 20:56:02', '/mvc/public/images/resized/27d273e3e3c8306b3da5d520c8f39d6db85852471430434005.jpg', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_auth`
--

CREATE TABLE `user_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` enum('default','admin','owner') NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `user_auth`
--
