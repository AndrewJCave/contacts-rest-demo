/* Create Tables */
-- CREATE TABLE users (id INT UNSIGNED NOT NULL AUTO_INCREMENT, access_key VARCHAR(32), activated TINYINT UNSIGNED DEFAULT 1 , admin_id INT UNSIGNED, `role` CHAR(5), first_name VARCHAR(50), last_name VARCHAR(50), bio VARCHAR, photo VARCHAR(50), phone VARCHAR(20), mobile VARCHAR(25), job_title VARCHAR(50), company VARCHAR(50), address VARCHAR(100), city VARCHAR(20), username VARCHAR(100), password VARCHAR(64), email VARCHAR(100), timezone VARCHAR(30), `language` VARCHAR(10), theme VARCHAR(20), kbd_shortcuts TINYINT UNSIGNED DEFAULT 1 , created_on DATETIME, updated_on DATETIME, site_title VARCHAR(255), site_description VARCHAR, currency CHAR(3), last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP  NOT NULL, PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `contacts` (
   `id` INT(10) unsigned NOT NULL AUTO_INCREMENT, 
   `user_id` INT UNSIGNED, 
   `title` VARCHAR(20), 
   `first_name` VARCHAR(50), 
   `last_name` VARCHAR(50), 
   `company` VARCHAR(50), 
   `job_title` VARCHAR(50), 
   `email` VARCHAR(100), 
   `phone` VARCHAR(30), 
   `mobile` VARCHAR(25), 
   `web_site` VARCHAR(100), 
   PRIMARY KEY (`id`),
   KEY `user_id` (`user_id`)
   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
   
--
-- Table structure for table `contacts_notes`
--

CREATE TABLE IF NOT EXISTS `contacts_notes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `contact_id` int(10) NOT NULL,
  `note` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

/* Insert Data */

-- Contacts
INSERT INTO rest_demo.contacts (user_id, title, first_name, last_name, company, job_title, email, phone, mobile, web_site) 
	VALUES (4, 'Mr', 'Daniel', 'Beacon', 'Beacon Paper', 'Director', 'dan.beacon@beaconpaper.com', '03 20458712', '0419876543', 'www.beacons.com');
INSERT INTO rest_demo.contacts (user_id, title, first_name, last_name, company, job_title, email, phone, mobile, web_site) 
	VALUES (5, 'Miss', 'Felicity', 'Hillsey', 'Talent Seekers', 'HR Manager', 'felicity@talentseekers.net', '07 32167892', '0401122334', 'http://www.talentseeking.net');
INSERT INTO rest_demo.contacts (user_id, title, first_name, last_name, company, job_title, email, phone, mobile, web_site)  
	VALUES (6, 'Mrs', 'Janet', 'Ann', 'Miller', 'Arnotts', 'Procurement Officer', 'janet.a.miller@arnotts.org.au', '02 23450987', NULL, 'http://arnotts.org.au');
INSERT INTO rest_demo.contacts (user_id, title, first_name, last_name, company, job_title, email, phone, mobile, web_site)  
	VALUES (5, 'Mr', 'David', NULL, 'Jones', 'Papermate Stationary', 'Sales Executive', 'davey.jones@papermate.co.jp', '08 22334455', '04199887766', 'http://www.papermate.co.jp');
	
	
-- Users
INSERT INTO rest_demo.users (access_key, activated, admin_id, `role`, first_name, last_name, bio, photo, phone, mobile, job_title, company, address, city, username, password, email, timezone, `language`, theme, kbd_shortcuts, created_on, updated_on, site_title, site_description, currency, last_login) 
	VALUES ('333150392628e77749d252b5d61a7c38', 1, 1, 'super', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SBOAdmin', '57c9c68e8e49733d3dfcef28f9e9730afd72159df89728e72e2f5c89c0b134f5', NULL, 'Europe/Istanbul', 'en', 'white', 1, '2015-02-06 04:07:39.0', NULL, NULL, NULL, 'USD', '2015-10-02 07:53:31.0');
INSERT INTO rest_demo.users (access_key, activated, admin_id, `role`, first_name, last_name, bio, photo, phone, mobile, job_title, company, address, city, username, password, email, timezone, `language`, theme, kbd_shortcuts, created_on, updated_on, site_title, site_description, currency, last_login) 
	VALUES ('78bc388921b363e5ea8d67a22cca3806', 1, 1, 'admin', 'Administrator', 'One', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Admin1', '51f57f2717a4b48d5e8fed4bd467056f09d6673281765c41cd4be3a3f3d20840', 'admin1@demo.com', 'Europe/Istanbul', 'en', 'white', 1, '2015-02-06 04:11:05.0', NULL, NULL, NULL, 'USD', '2015-02-06 12:11:05.0');
INSERT INTO rest_demo.users (access_key, activated, admin_id, `role`, first_name, last_name, bio, photo, phone, mobile, job_title, company, address, city, username, password, email, timezone, `language`, theme, kbd_shortcuts, created_on, updated_on, site_title, site_description, currency, last_login) 
	VALUES ('ffcfc4b339a762d3b328248214954de0', 1, 1, 'admin', 'Administrator', 'Two', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Admin2', 'a6adf9fc91c80fc644c462cda21837c5bc85a3112ac6512047131a8411792f39', 'admin2@demo.com', 'Europe/Istanbul', 'en', 'white', 1, '2015-02-06 04:11:57.0', NULL, NULL, NULL, 'USD', '2015-02-06 12:11:57.0');
INSERT INTO rest_demo.users (access_key, activated, admin_id, `role`, first_name, last_name, bio, photo, phone, mobile, job_title, company, address, city, username, password, email, timezone, `language`, theme, kbd_shortcuts, created_on, updated_on, site_title, site_description, currency, last_login) 
	VALUES ('f8839ec363fa6473ca18a2904c96e4d5', 1, 2, 'user', 'Staff', 'One', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Staff1', 'e10e2f7be33d77d4c7bc1dc860f5420fca22aa06e7d743ef17735719049c6802', 'staff1@demo.com', 'Europe/Istanbul', 'en', 'white', 1, '2015-02-06 04:13:02.0', NULL, NULL, NULL, 'USD', '2015-02-06 12:13:02.0');
INSERT INTO rest_demo.users (access_key, activated, admin_id, `role`, first_name, last_name, bio, photo, phone, mobile, job_title, company, address, city, username, password, email, timezone, `language`, theme, kbd_shortcuts, created_on, updated_on, site_title, site_description, currency, last_login) 
	VALUES ('82e0d2f9cbef6cceb44278feac8991c4', 1, 3, 'user', 'Staff', 'Two', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Staff2', '05e3ed5dacb6f7774552a710c1c121fca0a1261b6d31788fb2aeb5dd031d1d0e', 'staff2@demo.com', 'Europe/Istanbul', 'en', 'white', 1, '2015-02-06 04:13:46.0', NULL, NULL, NULL, 'USD', '2015-02-06 12:13:46.0');
	