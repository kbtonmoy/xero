SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `add_ons`;
CREATE TABLE IF NOT EXISTS `add_ons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `add_on_name` varchar(255) NOT NULL,
  `unique_name` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `installed_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  `purchase_code` varchar(100) NOT NULL,
  `module_folder_name` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`unique_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `ad_config`;
CREATE TABLE IF NOT EXISTS `ad_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section1_html` longtext,
  `section1_html_mobile` longtext,
  `section2_html` longtext,
  `section3_html` longtext,
  `section4_html` longtext,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `alexa_info`;
CREATE TABLE IF NOT EXISTS `alexa_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(250) NOT NULL,
  `reach_rank` varchar(150) DEFAULT NULL,
  `country` varchar(150) DEFAULT NULL,
  `country_rank` varchar(150) DEFAULT NULL,
  `traffic_rank` varchar(150) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `checked_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`checked_at`,`domain_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `alexa_info_full`;
CREATE TABLE IF NOT EXISTS `alexa_info_full` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `alexa_rank` varchar(150) DEFAULT NULL COMMENT 'alexa_info',
  `card_geography_country` varchar(150) DEFAULT NULL COMMENT 'alexa_info',
  `bounce_rate` varchar(255) DEFAULT NULL COMMENT 'alexa_info',
  `alexa_rank_spend_time` varchar(255) DEFAULT NULL COMMENT 'alexa_info',
  `site_search_traffic` varchar(255) NOT NULL,
  `total_sites_linking_in` varchar(255) NOT NULL,
  `total_keyword_opportunities_breakdown` varchar(255) NOT NULL,
  `keyword_opportunitites_values` text NOT NULL,
  `similar_sites` text NOT NULL,
  `similar_site_overlap` text NOT NULL,
  `keyword_top` text NOT NULL,
  `top_keywords` text NOT NULL,
  `search_traffic` text NOT NULL,
  `share_voice` text NOT NULL,
  `keyword_gaps` text NOT NULL,
  `keyword_gaps_trafic_competitor` text NOT NULL,
  `keyword_gaps_search_popularity` text NOT NULL,
  `easyto_rank_keyword` text NOT NULL,
  `easyto_rank_relevence` text NOT NULL,
  `easyto_rank_search_popularity` text NOT NULL,
  `buyer_keyword` text NOT NULL,
  `buyer_keyword_traffic_to_competitor` text NOT NULL,
  `buyer_keyword_organic_competitor` text NOT NULL,
  `optimization_opportunities` text NOT NULL,
  `optimization_opportunities_search_popularity` text NOT NULL,
  `optimization_opportunities_organic_share_of_voice` text NOT NULL,
  `refferal_sites` text NOT NULL,
  `refferal_sites_links` text NOT NULL,
  `top_keywords_search_traficc` text NOT NULL,
  `top_keywords_share_of_voice` text NOT NULL,
  `site_overlap_score` text NOT NULL,
  `similar_to_this_sites` text NOT NULL,
  `similar_to_this_sites_alexa_rank` text NOT NULL,
  `card_geography_countryPercent` text NOT NULL,
  `site_metrics` text NOT NULL,
  `site_metrics_domains` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `searched_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`searched_at`,`domain_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `announcement`;
CREATE TABLE IF NOT EXISTS `announcement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 means all',
  `is_seen` enum('0','1') NOT NULL DEFAULT '0',
  `seen_by` text NOT NULL COMMENT 'if user_id = 0 then comma seperated user_ids',
  `last_seen_at` datetime NOT NULL,
  `color_class` varchar(50) NOT NULL DEFAULT 'primary',
  `icon` varchar(50) NOT NULL DEFAULT 'fas fa-bell',
  `status` enum('published','draft') NOT NULL DEFAULT 'draft',
  PRIMARY KEY (`id`),
  KEY `for_user_id` (`user_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `antivirus_scan_info`;
CREATE TABLE IF NOT EXISTS `antivirus_scan_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(250) NOT NULL,
  `google_status` varchar(100) DEFAULT NULL,
  `macafee_status` varchar(100) DEFAULT NULL,
  `norton_status` varchar(100) DEFAULT NULL,
  `scanned_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `scanned_at` (`scanned_at`),
  KEY `scan_info` (`user_id`,`scanned_at`,`domain_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `backlink_generator`;
CREATE TABLE IF NOT EXISTS `backlink_generator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `response_code` varchar(50) DEFAULT NULL,
  `status` enum('successful','failed') NOT NULL DEFAULT 'successful',
  `user_id` int(11) NOT NULL,
  `generated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `backlink_generator` (`user_id`,`generated_at`,`domain_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `backlink_search`;
CREATE TABLE IF NOT EXISTS `backlink_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `backlink_count` varchar(100) NOT NULL,
  `searched_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`searched_at`,`domain_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `bitly_url_shortener`;
CREATE TABLE IF NOT EXISTS `bitly_url_shortener` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `long_url` text,
  `short_url` text,
  `short_url_id` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `google_safety_api` text,
  `moz_access_id` varchar(100) DEFAULT NULL,
  `moz_secret_key` varchar(100) DEFAULT NULL,
  `mobile_ready_api_key` varchar(100) NOT NULL,
  `virus_total_api` varchar(255) NOT NULL,
  `bitly_access_token` varchar(255) NOT NULL,
  `rebrandly_api_key` varchar(255) NOT NULL,
  `facebook_app_id` varchar(255) NOT NULL,
  `facebook_app_secret` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `config_proxy`;
CREATE TABLE IF NOT EXISTS `config_proxy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `proxy` varchar(100) DEFAULT NULL,
  `port` varchar(20) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `admin_permission` varchar(100) NOT NULL DEFAULT 'only me',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `email_config`;
CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_type` enum('Default','tls','ssl') NOT NULL DEFAULT 'Default',
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `email_smtp_config`;
CREATE TABLE IF NOT EXISTS `email_smtp_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(200) CHARACTER SET latin1 NOT NULL,
  `smtp_host` varchar(200) CHARACTER SET latin1 NOT NULL,
  `smtp_port` varchar(100) CHARACTER SET latin1 NOT NULL,
  `smtp_user` varchar(100) CHARACTER SET latin1 NOT NULL,
  `smtp_password` varchar(100) CHARACTER SET latin1 NOT NULL,
  `smtp_type` enum('Default','tls','ssl') CHARACTER SET latin1 NOT NULL DEFAULT 'Default',
  `status` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `deleted` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `email_template_management`;
CREATE TABLE IF NOT EXISTS `email_template_management` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `template_type` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'fas fa-folder-open',
  `tooltip` text NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `email_template_management` (`id`, `title`, `template_type`, `subject`, `message`, `icon`, `tooltip`, `info`) VALUES
(1, 'Signup Activation', 'signup_activation', '#APP_NAME# | Account Activation', '<p>To activate your account please perform the following steps :</p>\r\n<ol>\r\n<li>Go to this url : #ACTIVATION_URL#</li>\r\n<li>Enter this code : #ACCOUNT_ACTIVATION_CODE#</li>\r\n<li>Activate your account</li>\r\n</ol>', 'fas fa-skating', '#APP_NAME#,#ACTIVATION_URL#,#ACCOUNT_ACTIVATION_CODE#', 'When a new user open an account'),
(2, 'Reset Password', 'reset_password', '#APP_NAME# | Password Recovery', '<p>To reset your password please perform the following steps :</p>\r\n<ol>\r\n<li>Go to this url : #PASSWORD_RESET_URL#</li>\r\n<li>Enter this code : #PASSWORD_RESET_CODE#</li>\r\n<li>reset your password.</li>\r\n</ol>\r\n<h4>Link and code will be expired after 24 hours.</h4>', 'fas fa-retweet', '#APP_NAME#,#PASSWORD_RESET_URL#,#PASSWORD_RESET_CODE#', 'When a user forget login password'),
(3, 'Change Password', 'change_password', 'Change Password Notification', 'Dear #USERNAME#,<br/> \r\nYour <a href="#APP_URL#">#APP_NAME#</a> password has been changed.<br>\r\nYour new password is: #NEW_PASSWORD#.<br/><br/> \r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-key', '#APP_NAME#,#APP_URL#,#USERNAME#,#NEW_PASSWORD#', 'When admin reset password of any user'),
(4, 'Subscription Expiring Soon', 'membership_expiration_10_days_before', 'Payment Alert', 'Dear #USERNAME#,\r\n<br/> Your account will expire after 10 days, Please pay your fees.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-clock', '#APP_NAME#,#APP_URL#,#USERNAME#', '10 days before user subscription expires'),
(5, 'Subscription Expiring Tomorrow', 'membership_expiration_1_day_before', 'Payment Alert', 'Dear #USERNAME#,<br/>\r\nYour account will expire tomorrow, Please pay your fees.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-stopwatch', '#APP_NAME#,#APP_URL#,#USERNAME#', '1 day before user subscription expires'),
(6, 'Subscription Expired', 'membership_expiration_1_day_after', 'Subscription Expired', 'Dear #USERNAME#,<br/>\r\nYour account has been expired, Please pay your fees for continuity.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fas fa-user-clock', '#APP_NAME#,#APP_URL#,#USERNAME#', 'Subscription is already expired of a user'),
(7, 'Paypal Payment Confirmation', 'paypal_payment', 'Payment Confirmation', 'Congratulations,<br/> \r\nWe have received your payment successfully.<br/>\r\nNow you are able to use #PRODUCT_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>\r\nThank you,<br/>\r\n<a href="#SITE_URL#">#APP_NAME#</a> Team', 'fab fa-paypal', '#APP_NAME#,#CYCLE_EXPIRED_DATE#,#PRODUCT_SHORT_NAME#,#SITE_URL#', 'User pay through Paypal & gets confirmation'),
(8, 'Paypal New Payment', 'paypal_new_payment_made', 'New Payment Made', 'New payment has been made by #PAID_USER_NAME#', 'fab fa-cc-paypal', '#PAID_USER_NAME#', 'User pay through Paypal & admin gets notified'),
(9, 'Stripe Payment Confirmation', 'stripe_payment', 'Payment Confirmation', 'Congratulations,<br/>\r\nWe have received your payment successfully.<br/>\r\nNow you are able to use #APP_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>\r\nThank you,<br/>\r\n<a href="#APP_URL#">#APP_NAME#</a> Team', 'fab fa-stripe-s', '#APP_NAME#,#CYCLE_EXPIRED_DATE#,#PRODUCT_SHORT_NAME#,#SITE_URL#', 'User pay through Stripe & gets confirmation'),
(10, 'Stripe New Payment', 'stripe_new_payment_made', 'New Payment Made', 'New payment has been made by #PAID_USER_NAME#', 'fab fa-cc-stripe', '#PAID_USER_NAME#', 'User pay through Stripe & admin gets notified');



DROP TABLE IF EXISTS `expired_domain_list`;
CREATE TABLE IF NOT EXISTS `expired_domain_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `auction_type` enum('pre_release','pending_delete','public_auction') CHARACTER SET latin1 DEFAULT NULL,
  `auction_end_date` datetime DEFAULT NULL,
  `sync_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_end_date` (`auction_end_date`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `facebook_rx_config`;
CREATE TABLE IF NOT EXISTS `facebook_rx_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `app_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_secret` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `fb_simple_support_desk`;
CREATE TABLE IF NOT EXISTS `fb_simple_support_desk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ticket_title` text NOT NULL,
  `ticket_text` longtext NOT NULL,
  `ticket_status` enum('1','2','3') CHARACTER SET latin1 NOT NULL DEFAULT '1' COMMENT '1=> Open. 2 => Closed, 3 => Resolved',
  `display` enum('0','1') NOT NULL DEFAULT '1',
  `support_category` int(11) NOT NULL,
  `last_replied_by` int(11) NOT NULL,
  `last_replied_at` datetime NOT NULL,
  `last_action_at` datetime NOT NULL COMMENT 'close resolve reopen etc',
  `ticket_open_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `support_category` (`support_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `fb_support_category`;
CREATE TABLE IF NOT EXISTS `fb_support_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `fb_support_category` (`id`, `category_name`, `user_id`, `deleted`) VALUES
(1, 'Billing', 1, '0'),
(2, 'Technical', 1, '0'),
(3, 'Query', 1, '0');


DROP TABLE IF EXISTS `fb_support_desk_reply`;
CREATE TABLE IF NOT EXISTS `fb_support_desk_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_reply_text` longtext NOT NULL,
  `ticket_reply_time` datetime NOT NULL,
  `reply_id` int(11) NOT NULL COMMENT 'ticket_id',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `forget_password`;
CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ip_domain_info`;
CREATE TABLE IF NOT EXISTS `ip_domain_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `isp` varchar(100) DEFAULT NULL,
  `organization` varchar(100) DEFAULT NULL,
  `domain_name` varchar(250) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `time_zone` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` int(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `searched_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `searched_at` (`searched_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ip_same_site`;
CREATE TABLE IF NOT EXISTS `ip_same_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `website` longtext,
  `user_id` int(11) NOT NULL,
  `searched_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `searched_at` (`searched_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ip_v6_check`;
CREATE TABLE IF NOT EXISTS `ip_v6_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` text CHARACTER SET utf8,
  `ipv6` varchar(200) DEFAULT NULL,
  `searched_at` datetime NOT NULL,
  `ip` varchar(200) DEFAULT NULL,
  `is_ipv6_support` varchar(10) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `keyword_position`;
CREATE TABLE IF NOT EXISTS `keyword_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `keyword` varchar(250) NOT NULL,
  `location` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `proxy` text,
  `google_position` varchar(100) DEFAULT NULL,
  `google_top_site_url` longtext,
  `bing_position` varchar(100) DEFAULT NULL,
  `bing_top_site_url` text,
  `yahoo_position` varchar(100) DEFAULT NULL,
  `yahoo_top_site_url` text,
  `searched_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `searched_at` (`searched_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `keyword_position_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `google_position` varchar(100) NOT NULL,
  `bing_position` varchar(100) NOT NULL,
  `yahoo_position` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `keyword_position_set`;
CREATE TABLE IF NOT EXISTS `keyword_position_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(250) NOT NULL,
  `website` varchar(250) CHARACTER SET latin1 NOT NULL,
  `language` varchar(250) CHARACTER SET latin1 NOT NULL,
  `country` varchar(250) CHARACTER SET latin1 NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `last_scan_date` date NOT NULL,
  `deleted` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `keyword_suggestion`;
CREATE TABLE IF NOT EXISTS `keyword_suggestion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `google_suggestion` text,
  `bing_suggestion` text,
  `yahoo_suggestion` text,
  `wiki_suggestion` text,
  `amazon_suggestion` text,
  `searched_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `searched_at` (`searched_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `link_analysis`;
CREATE TABLE IF NOT EXISTS `link_analysis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `external_link_count` varchar(50) DEFAULT NULL,
  `internal_link_count` varchar(50) DEFAULT NULL,
  `nofollow_count` varchar(50) DEFAULT NULL,
  `do_follow_count` varchar(50) DEFAULT NULL,
  `external_link` longtext,
  `internal_link` longtext,
  `searched_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `searched_at` (`searched_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `login_config`;
CREATE TABLE IF NOT EXISTS `login_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) DEFAULT NULL,
  `api_key` varchar(250) DEFAULT NULL,
  `google_client_id` text,
  `google_client_secret` varchar(250) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `add_ons_id` int(11) NOT NULL,
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  `header_text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

INSERT INTO `menu` (`id`, `name`, `icon`, `url`, `serial`, `module_access`, `have_child`, `only_admin`, `only_member`, `add_ons_id`, `is_external`, `header_text`) VALUES
(1, 'Dashboard', 'fa fa-fire', 'dashboard', 1, '', '0', '0', '0', 0, '0', ''),
(2, 'System', 'fas fa-laptop-code', '', 9, '', '1', '0', '0', 0, '0', 'Administration'),
(3, 'Subscription', 'fas fa-coins', '', 13, '', '1', '1', '0', 0, '0', ''),
(12, 'Analysis Tools', 'fas fa-chart-bar', 'menu_loader/analysis_tools', 17, '1,2,3,4,5,6,7,8', '0', '0', '0', 0, '0', 'SEO Tools'),
(14, 'Utlities', 'fas fa-ellipsis-h', 'menu_loader/utlities', 25, '12,13', '0', '0', '0', 0, '0', ''),
(15, 'URL Shortner', 'fas fa-cut', 'menu_loader/url_shortner', 29, '18', '0', '0', '0', 0, '0', ''),
(16, 'Keyword Tracking', 'fas fa-map-marker-alt', 'menu_loader/keyword_position_tracking', 33, '16', '0', '0', '0', 0, '0', ''),
(17, 'Security Tools', 'fa fa-shield', 'menu_loader/security_tools', 37, '10', '0', '0', '0', 0, '0', ''),
(19, 'Code Minifier', 'fa fa-object-group', 'menu_loader/code_minifier', 45, '17', '0', '0', '0', 0, '0', ''),
(20, 'Widgets', 'fas fa-puzzle-piece', 'native_widgets/get_widget', 54, '14', '0', '0', '0', 0, '0', '');

DROP TABLE IF EXISTS `menu_child_1`;
CREATE TABLE IF NOT EXISTS `menu_child_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `have_child` enum('1','0') NOT NULL DEFAULT '0',
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;


INSERT INTO `menu_child_1` (`id`, `name`, `url`, `serial`, `icon`, `module_access`, `parent_id`, `have_child`, `only_admin`, `only_member`, `is_external`) VALUES
(1, 'Settings', 'admin/settings', 1, 'fas fa-sliders-h', '', 2, '0', '1', '0', '0'),
(2, 'Social Apps & APIs', 'social_apps/index', 5, 'fas fa-hands-helping', '', 2, '0', '0', '0', '0'),
(3, 'Cron Job', 'cron_job/index', 9, 'fas fa-clipboard-list', '', 2, '0', '1', '0', '0'),
(4, 'Language Editor', 'multi_language/index', 13, 'fas fa-language', '', 2, '0', '1', '0', '0'),
(5, 'Add-on Manager', 'addons/lists', 17, 'fas fa-plug', '', 2, '0', '1', '0', '0'),
(6, 'Check Update', 'update_system/index', 21, 'fas fa-leaf', '', 2, '0', '1', '0', '0'),
(7, 'Package Manager', 'payment/package_manager', 1, 'fas fa-shopping-bag', '', 3, '0', '1', '0', '0'),
(8, 'User Manager', 'admin/user_manager', 5, 'fas fa-users', '', 3, '0', '1', '0', '0'),
(9, 'Announcement', 'announcement/full_list', 9, 'far fa-bell', '', 3, '0', '1', '0', '0'),
(10, 'Payment Accounts', 'payment/accounts', 13, 'far fa-credit-card', '', 3, '0', '1', '0', '0'),
(11, 'Earning Summary', 'payment/earning_summary', 17, 'fas fa-tachometer-alt', '', 3, '0', '1', '0', '0'),
(12, 'Transaction Log', 'payment/transaction_log', 27, 'fas fa-history', '', 3, '0', '1', '0', '0'),
(17, 'Theme Manager', 'themes/lists', 19, 'fas fa-palette', '', 2, '0', '1', '0', '0'),
(18, 'Native API', 'native_api/index', 5, 'fas fa-home', '', 2, '0', '0', '0', '0'),
(19, 'Junk Files', 'admin/delete_junk_file', 22, 'fas fa-trash', '', 2, '0', '1', '0', '0');


DROP TABLE IF EXISTS `menu_child_2`;
CREATE TABLE IF NOT EXISTS `menu_child_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `module_access` varchar(255) NOT NULL,
  `parent_child` int(11) NOT NULL,
  `only_admin` enum('1','0') NOT NULL DEFAULT '1',
  `only_member` enum('1','0') NOT NULL DEFAULT '0',
  `is_external` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) DEFAULT NULL,
  `add_ons_id` int(11) NOT NULL,
  `extra_text` varchar(50) NOT NULL DEFAULT 'month',
  `limit_enabled` enum('0','1') NOT NULL DEFAULT '1',
  `bulk_limit_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;



INSERT INTO `modules` (`id`, `module_name`, `add_ons_id`, `extra_text`, `limit_enabled`, `bulk_limit_enabled`, `deleted`) VALUES
(1, 'Visitor Analysis', 0, '', '1', '1', '0'),
(2, 'Website Analysis', 0, 'Month', '1', '1', '0'),
(3, 'Social Network Analysis', 0, '', '1', '1', '0'),
(4, 'Rank & Index Analysis', 0, 'Month', '1', '1', '0'),
(5, 'Domain Analysis', 0, 'Month', '1', '1', '0'),
(6, 'IP Analysis', 0, 'Month', '1', '1', '0'),
(7, 'Link Analysis', 0, 'Month', '1', '1', '0'),
(8, 'Keyword Analysis', 0, 'Month', '1', '1', '0'),
(10, 'Security Tools', 0, 'Month', '1', '1', '0'),
(12, 'Plagiarism Check', 0, 'Month', '1', '1', '0'),
(13, 'Utilities', 0, '', '1', '1', '0'),
(14, 'Native Widget', 0, '', '1', '1', '0'),
(15, 'Native API', 0, 'Month', '1', '1', '0'),
(16, 'Keyword Position Tracking', 0, '', '1', '1', '0'),
(17, 'Code Minifier', 0, '', '1', '1', '0'),
(18, 'URL Shortener', 0, 'Month', '1', '1', '0');


DROP TABLE IF EXISTS `moz_info`;
CREATE TABLE IF NOT EXISTS `moz_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `mozrank_subdomain_normalized` varchar(150) NOT NULL,
  `mozrank_subdomain_raw` varchar(150) NOT NULL,
  `mozrank_url_normalized` varchar(150) NOT NULL,
  `mozrank_url_raw` varchar(150) NOT NULL,
  `http_status_code` varchar(150) NOT NULL,
  `domain_authority` varchar(150) NOT NULL,
  `page_authority` varchar(150) NOT NULL,
  `external_equity_links` varchar(150) NOT NULL,
  `links` varchar(150) NOT NULL,
  `user_id` int(11) NOT NULL,
  `checked_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `native_api`;
CREATE TABLE IF NOT EXISTS `native_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `native_widgets`;
CREATE TABLE IF NOT EXISTS `native_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `domain_code` varchar(250) NOT NULL,
  `js_code` text NOT NULL,
  `table_name` text NOT NULL,
  `add_date` date NOT NULL,
  `dashboard` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `package`;
CREATE TABLE IF NOT EXISTS `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(250) NOT NULL,
  `module_ids` varchar(250) NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `validity` int(11) NOT NULL,
  `validity_extra_info` varchar(255) NOT NULL DEFAULT '1,M',
  `is_default` enum('0','1') NOT NULL DEFAULT '0',
  `visible` enum('0','1') NOT NULL DEFAULT '1',
  `highlight` enum('0','1') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `validity_extra_info`, `is_default`, `visible`, `highlight`, `deleted`) VALUES
(1, 'Trial', '9,17,5,6,8,16,7,15,14,12,4,10,3,18,13,1,2', '{"9":"0","17":"0","5":"0","6":"0","8":"0","16":"0","7":"0","15":"0","14":"0","12":"0","4":"0","10":"0","3":"0","18":"0","13":"0","1":"1","2":"5"}', '{"9":"0","17":"0","5":"0","6":"0","8":"0","16":"0","7":"0","15":"0","14":"0","12":"0","4":"0","10":"0","3":"0","18":"0","13":"0","1":"1","2":"5"}', 'Trial', 7, '1,W', '1', '1', '0', '0');


DROP TABLE IF EXISTS `page_status`;
CREATE TABLE IF NOT EXISTS `page_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `user_id` varchar(222) CHARACTER SET latin1 NOT NULL,
  `http_code` varchar(20) CHARACTER SET latin1 NOT NULL,
  `status` varchar(50) CHARACTER SET latin1 NOT NULL,
  `total_time` varchar(50) CHARACTER SET latin1 NOT NULL,
  `namelookup_time` varchar(50) CHARACTER SET latin1 NOT NULL,
  `connect_time` varchar(50) CHARACTER SET latin1 NOT NULL,
  `speed_download` varchar(50) CHARACTER SET latin1 NOT NULL,
  `check_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `check_date` (`check_date`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `payment_config`;
CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) NOT NULL,
  `paypal_payment_type` enum('manual','recurring') NOT NULL DEFAULT 'manual',
  `paypal_mode` enum('live','sandbox') NOT NULL DEFAULT 'live',
  `stripe_secret_key` varchar(150) NOT NULL,
  `stripe_publishable_key` varchar(150) NOT NULL,
  `currency` enum('USD','AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','RUB','SGD','SEK','CHF','VND','BDT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `manual_payment` enum('no','yes') NOT NULL DEFAULT 'no',
  `manual_payment_instruction` text,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `paypal_error_log`;
CREATE TABLE IF NOT EXISTS `paypal_error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `call_time` datetime DEFAULT NULL,
  `ipn_value` text,
  `error_log` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rebrandly_url_shortener`;
CREATE TABLE IF NOT EXISTS `rebrandly_url_shortener` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `long_url` text NOT NULL,
  `short_url` text NOT NULL,
  `short_url_id` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `domainId` varchar(255) NOT NULL,
  `slashtag` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `search_engine_index`;
CREATE TABLE IF NOT EXISTS `search_engine_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `google_index` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `bing_index` varchar(20) DEFAULT NULL,
  `yahoo_index` varchar(20) DEFAULT NULL,
  `checked_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `checked_at` (`checked_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `social_info`;
CREATE TABLE IF NOT EXISTS `social_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `user_id` varchar(222) NOT NULL,
  `reddit_score` varchar(222) DEFAULT NULL,
  `reddit_up` varchar(222) DEFAULT NULL,
  `reddit_dowon` varchar(222) DEFAULT NULL,
  `linked_in_share` varchar(222) DEFAULT NULL,
  `buffer_share` varchar(222) DEFAULT NULL,
  `fb_like` varchar(222) DEFAULT NULL,
  `fb_share` varchar(222) DEFAULT NULL,
  `fb_comment` varchar(222) DEFAULT NULL,
  `fb_comment_plugin` varchar(255) NOT NULL,
  `google_plus_count` varchar(222) DEFAULT NULL,
  `xing_share_count` varchar(222) DEFAULT NULL,
  `pinterest_pin` varchar(255) NOT NULL,
  `search_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`search_at`,`domain_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `transaction_history`;
CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_status` varchar(200) NOT NULL,
  `first_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `paypal_email` varchar(200) NOT NULL,
  `receiver_email` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `payment_date` varchar(100) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cycle_start_date` date NOT NULL,
  `cycle_expired_date` date NOT NULL,
  `package_id` int(11) NOT NULL,
  `stripe_card_source` text NOT NULL,
  `paypal_txn_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `transaction_history_manual`;
CREATE TABLE IF NOT EXISTS `transaction_history_manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `paid_amount` varchar(255) NOT NULL,
  `paid_currency` char(4) NOT NULL,
  `additional_info` longtext NOT NULL,
  `filename` varchar(255) NOT NULL,
  `status` enum('0','1') DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thm_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `update_list`;
CREATE TABLE IF NOT EXISTS `update_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `files` text NOT NULL,
  `sql_query` text NOT NULL,
  `update_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `usage_log`;
CREATE TABLE IF NOT EXISTS `usage_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(99) NOT NULL,
  `address` text NOT NULL,
  `user_type` enum('Member','Admin') NOT NULL,
  `status` enum('1','0') NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `purchase_date` datetime NOT NULL,
  `last_login_at` datetime NOT NULL,
  `activation_code` varchar(20) DEFAULT NULL,
  `expired_date` datetime NOT NULL,
  `bot_status` enum('0','1') NOT NULL DEFAULT '1',
  `package_id` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  `brand_logo` text,
  `brand_url` text,
  `vat_no` varchar(100) DEFAULT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK','BRL') NOT NULL DEFAULT 'USD',
  `time_zone` varchar(255) DEFAULT NULL,
  `company_email` varchar(200) DEFAULT NULL,
  `paypal_email` varchar(100) NOT NULL,
  `paypal_subscription_enabled` enum('0','1') NOT NULL DEFAULT '0',
  `last_payment_method` varchar(50) NOT NULL,
  `last_login_ip` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;



INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `address`, `user_type`, `status`, `add_date`, `purchase_date`, `last_login_at`, `activation_code`, `expired_date`, `bot_status`, `package_id`, `deleted`, `brand_logo`, `brand_url`, `vat_no`, `currency`, `time_zone`, `company_email`, `paypal_email`, `paypal_subscription_enabled`, `last_payment_method`, `last_login_ip`) VALUES
(1, 'Xerone IT', 'admin@gmail.com', '01729853645', '2595DKJkdtu359847fhBUidh8rd8bbee67ab0', 'Holding No. 127, 1st Floor, Gonok Para', 'Admin', '1', '2019-08-25 12:00:00', '0000-00-00 00:00:00', '2020-02-18 08:47:40', NULL, '0000-00-00 00:00:00', '1', 0, '0', '', NULL, NULL, 'USD', '', NULL, '', '0', '', '192.168.10.13');


DROP TABLE IF EXISTS `user_login_info`;
CREATE TABLE IF NOT EXISTS `user_login_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `login_time` datetime NOT NULL,
  `login_ip` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `version`;
CREATE TABLE IF NOT EXISTS `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `current` enum('1','0') NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version` (`version`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `virustotal`;
CREATE TABLE IF NOT EXISTS `virustotal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(255) NOT NULL,
  `response_code` varchar(50) NOT NULL,
  `permalink` tinytext NOT NULL,
  `verbose_msg` varchar(255) NOT NULL,
  `positives` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `scans` longtext NOT NULL,
  `scanned_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain_name` (`domain_name`,`scanned_at`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `visitor_analysis_domain_list`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_domain_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(200) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `js_code` text NOT NULL,
  `add_date` date NOT NULL,
  `dashboard` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `visitor_analysis_domain_list_data`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_domain_list_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `date_time` datetime NOT NULL,
  `referrer` varchar(200) NOT NULL,
  `visit_url` text NOT NULL,
  `cookie_value` varchar(200) NOT NULL,
  `session_value` varchar(200) NOT NULL,
  `is_new` int(11) NOT NULL,
  `last_scroll_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date_time` (`date_time`,`domain_list_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `website_analysis_info`;
CREATE TABLE IF NOT EXISTS `website_analysis_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `search_at` datetime NOT NULL,
  `screenshot` longtext,
  `domain_name` varchar(250) NOT NULL,
  `alexa_rank` varchar(150) DEFAULT NULL COMMENT 'alexa_info',
  `card_geography_country` varchar(150) DEFAULT NULL COMMENT 'alexa_info',
  `bounce_rate` varchar(255) DEFAULT NULL COMMENT 'alexa_info',
  `alexa_rank_spend_time` varchar(255) DEFAULT NULL COMMENT 'alexa_info',
  `site_search_traffic` varchar(255) NOT NULL,
  `total_sites_linking_in` varchar(255) NOT NULL,
  `total_keyword_opportunities_breakdown` varchar(255) NOT NULL,
  `keyword_opportunitites_values` text NOT NULL,
  `similar_sites` text NOT NULL,
  `similar_site_overlap` text NOT NULL,
  `keyword_top` text NOT NULL,
  `top_keywords` text NOT NULL,
  `search_traffic` text NOT NULL,
  `share_voice` text NOT NULL,
  `keyword_gaps` text NOT NULL,
  `keyword_gaps_trafic_competitor` text NOT NULL,
  `keyword_gaps_search_popularity` text NOT NULL,
  `easyto_rank_keyword` text NOT NULL,
  `easyto_rank_relevence` text NOT NULL,
  `easyto_rank_search_popularity` text NOT NULL,
  `buyer_keyword` text NOT NULL,
  `buyer_keyword_traffic_to_competitor` text NOT NULL,
  `buyer_keyword_organic_competitor` text NOT NULL,
  `optimization_opportunities` text NOT NULL,
  `optimization_opportunities_search_popularity` text NOT NULL,
  `optimization_opportunities_organic_share_of_voice` text NOT NULL,
  `refferal_sites` text NOT NULL,
  `refferal_sites_links` text NOT NULL,
  `top_keywords_search_traficc` text NOT NULL,
  `top_keywords_share_of_voice` text NOT NULL,
  `site_overlap_score` text NOT NULL,
  `similar_to_this_sites` text NOT NULL,
  `similar_to_this_sites_alexa_rank` text NOT NULL,
  `card_geography_countryPercent` text NOT NULL,
  `site_metrics` text NOT NULL,
  `site_metrics_domains` text NOT NULL,
  `status` varchar(100) NOT NULL COMMENT 'alexa_info',
  `title` text,
  `h1` text COMMENT 'meta tag info',
  `h2` text COMMENT 'meta tag info',
  `h3` text COMMENT 'meta tag info',
  `h4` text COMMENT 'meta tag info',
  `h5` text COMMENT 'meta tag info',
  `h6` text COMMENT 'meta tag info',
  `blocked_by_robot_txt` varchar(20) DEFAULT NULL COMMENT 'meta tag info',
  `meta_tag_information` text COMMENT 'meta tag info',
  `blocked_by_meta_robot` varchar(20) DEFAULT NULL COMMENT 'meta tag info',
  `nofollowed_by_meta_robot` varchar(20) DEFAULT NULL COMMENT 'meta tag info',
  `one_phrase` text COMMENT 'meta tag info',
  `two_phrase` text COMMENT 'meta tag info',
  `three_phrase` text COMMENT 'meta tag info',
  `four_phrase` text COMMENT 'meta tag info',
  `total_words` int(11) NOT NULL DEFAULT '0',
  `dmoz_listed_or_not` varchar(150) DEFAULT NULL,
  `fb_total_share` varchar(150) DEFAULT NULL,
  `fb_total_like` varchar(150) DEFAULT NULL,
  `fb_total_comment` varchar(150) DEFAULT NULL,
  `google_back_link_count` varchar(150) DEFAULT NULL,
  `yahoo_back_link_count` varchar(150) DEFAULT NULL,
  `bing_back_link_count` varchar(150) DEFAULT NULL,
  `google_index_count` varchar(150) DEFAULT NULL,
  `google_page_rank` varchar(150) DEFAULT NULL,
  `bing_index_count` varchar(150) DEFAULT NULL,
  `yahoo_index_count` varchar(150) DEFAULT NULL,
  `whois_is_registered` varchar(150) DEFAULT NULL,
  `whois_tech_email` varchar(150) DEFAULT NULL,
  `whois_admin_email` varchar(150) DEFAULT NULL,
  `whois_name_servers` varchar(150) DEFAULT NULL,
  `whois_created_at` date NOT NULL,
  `whois_changed_at` date NOT NULL,
  `whois_expire_at` date NOT NULL,
  `whois_registrar_url` varchar(150) DEFAULT NULL,
  `whois_registrant_name` varchar(150) DEFAULT NULL,
  `whois_registrant_organization` varchar(150) DEFAULT NULL,
  `whois_registrant_street` varchar(150) DEFAULT NULL,
  `whois_registrant_city` varchar(150) DEFAULT NULL,
  `whois_registrant_state` varchar(150) DEFAULT NULL,
  `whois_registrant_postal_code` varchar(150) DEFAULT NULL,
  `whois_registrant_email` varchar(150) DEFAULT NULL,
  `whois_registrant_country` varchar(150) DEFAULT NULL,
  `whois_registrant_phone` varchar(150) DEFAULT NULL,
  `whois_admin_name` varchar(150) DEFAULT NULL,
  `whois_admin_street` varchar(150) DEFAULT NULL,
  `whois_admin_city` varchar(150) DEFAULT NULL,
  `whois_admin_postal_code` varchar(150) DEFAULT NULL,
  `whois_admin_country` varchar(150) DEFAULT NULL,
  `whois_admin_phone` varchar(150) DEFAULT NULL,
  `googleplus_share_count` varchar(150) DEFAULT NULL,
  `pinterest_pin` varchar(150) DEFAULT NULL,
  `stumbleupon_total_view` varchar(150) DEFAULT NULL,
  `stumbleupon_total_comment` varchar(150) DEFAULT NULL,
  `stumbleupon_total_like` varchar(150) DEFAULT NULL,
  `stumbleupon_total_list` varchar(150) DEFAULT NULL,
  `linkedin_share_count` varchar(150) DEFAULT NULL,
  `buffer_share_count` varchar(150) DEFAULT NULL,
  `reddit_score` varchar(150) DEFAULT NULL,
  `reddit_ups` varchar(150) DEFAULT NULL,
  `reddit_downs` varchar(150) DEFAULT NULL,
  `xing_share_count` varchar(150) DEFAULT NULL,
  `moz_subdomain_normalized` varchar(150) DEFAULT NULL,
  `moz_subdomain_raw` varchar(150) DEFAULT NULL,
  `moz_url_normalized` varchar(150) DEFAULT NULL,
  `moz_url_raw` varchar(150) DEFAULT NULL,
  `moz_http_status_code` varchar(150) DEFAULT NULL,
  `moz_domain_authority` varchar(150) DEFAULT NULL,
  `moz_page_authority` varchar(150) DEFAULT NULL,
  `moz_external_equity_links` varchar(150) DEFAULT NULL,
  `moz_links` varchar(150) DEFAULT NULL,
  `ipinfo_isp` varchar(150) DEFAULT NULL,
  `ipinfo_ip` varchar(150) DEFAULT NULL,
  `ipinfo_city` varchar(150) DEFAULT NULL,
  `ipinfo_region` varchar(150) DEFAULT NULL,
  `ipinfo_country` varchar(150) DEFAULT NULL,
  `ipinfo_time_zone` varchar(150) DEFAULT NULL,
  `ipinfo_longitude` varchar(150) DEFAULT NULL,
  `ipinfo_latitude` varchar(150) DEFAULT NULL,
  `macafee_status` varchar(150) DEFAULT NULL,
  `norton_status` varchar(150) DEFAULT NULL,
  `google_safety_status` varchar(150) DEFAULT NULL,
  `avg_status` varchar(150) DEFAULT NULL,
  `similar_site` text,
  `mobile_ready_data` longtext,
  `sites_in_same_ip` longtext,
  `completed_step_count` int(11) NOT NULL,
  `completed_step_string` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`domain_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `website_ping`;
CREATE TABLE IF NOT EXISTS `website_ping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `blog_name` varchar(100) DEFAULT NULL,
  `blog_url` varchar(250) DEFAULT NULL,
  `blog_url_to_ping` text,
  `blog_rss_feed_url` text,
  `ping_url` text NOT NULL,
  `response` varchar(100) NOT NULL,
  `ping_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`ping_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `whois_search`;
CREATE TABLE IF NOT EXISTS `whois_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `tech_email` varchar(250) CHARACTER SET latin1 NOT NULL,
  `admin_email` varchar(250) CHARACTER SET latin1 NOT NULL,
  `registrant_email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `registrant_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `registrant_organization` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `registrant_street` text CHARACTER SET latin1 NOT NULL,
  `registrant_city` varchar(100) CHARACTER SET latin1 NOT NULL,
  `registrant_state` varchar(100) CHARACTER SET latin1 NOT NULL,
  `registrant_postal_code` varchar(20) CHARACTER SET latin1 NOT NULL,
  `registrant_country` varchar(100) CHARACTER SET latin1 NOT NULL,
  `registrant_phone` varchar(20) CHARACTER SET latin1 NOT NULL,
  `registrar_url` text CHARACTER SET latin1 NOT NULL,
  `admin_name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `admin_street` text CHARACTER SET latin1 NOT NULL,
  `admin_city` varchar(50) CHARACTER SET latin1 NOT NULL,
  `admin_postal_code` varchar(25) CHARACTER SET latin1 NOT NULL,
  `admin_country` varchar(50) CHARACTER SET latin1 NOT NULL,
  `admin_phone` varchar(25) CHARACTER SET latin1 NOT NULL,
  `is_registered` varchar(50) CHARACTER SET latin1 NOT NULL,
  `namve_servers` varchar(250) CHARACTER SET latin1 NOT NULL,
  `created_at` date NOT NULL,
  `changed_at` varchar(250) CHARACTER SET latin1 NOT NULL,
  `expire_at` varchar(250) CHARACTER SET latin1 NOT NULL,
  `scraped_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `scraped_time` (`scraped_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `transaction_history_manual`
  ADD CONSTRAINT `thm_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `visitor_analysis_domain_list_data` DROP INDEX `date_time`, ADD INDEX `date_time` (`date_time`, `domain_list_id`, `is_new`) USING BTREE;
ALTER TABLE `visitor_analysis_domain_list_data` ADD INDEX `only_user_id` (`user_id`);
ALTER TABLE `visitor_analysis_domain_list_data` ADD INDEX `user_and_date` (`date_time`, `user_id`);
ALTER TABLE `website_analysis_info` ADD `screenshot_error` TEXT NOT NULL AFTER `completed_step_string`, ADD `google_api_error` TEXT NOT NULL AFTER `screenshot_error`;
DELETE FROM `menu_child_1` WHERE `menu_child_1`.`url` = 'admin/delete_junk_file';

alter table `transaction_history_manual` add column `transaction_id` varchar(150) not null after `package_id`;
ALTER TABLE `config` ADD `access` ENUM('only_me','all_users') NOT NULL DEFAULT 'only_me' AFTER `facebook_app_secret`;
ALTER TABLE `website_analysis_info` ADD `loadingexperience_metrics` TEXT NOT NULL AFTER `mobile_ready_data`, ADD `originloadingexperience_metrics` TEXT NOT NULL AFTER `loadingexperience_metrics`, ADD `lighthouseresult_configsettings` TEXT NOT NULL AFTER `originloadingexperience_metrics`, ADD `lighthouseresult_audits` LONGTEXT NOT NULL AFTER `lighthouseresult_configsettings`, ADD `lighthouseresult_categories` TEXT NOT NULL AFTER `lighthouseresult_audits`;
ALTER TABLE `website_analysis_info` DROP `mobile_ready_data`;