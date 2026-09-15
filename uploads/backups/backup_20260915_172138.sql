-- Generated backup — 2026-09-15 17:21:38
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `tbl_activity_submissions`;
CREATE TABLE `tbl_activity_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `answers` longtext DEFAULT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_activity_submissions` VALUES ('1','50','2','{\"50\":\"adasasdqweqwe\"}','0','2026-08-23 11:55:16');

DROP TABLE IF EXISTS `tbl_admin_permissions`;
CREATE TABLE `tbl_admin_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page_key` varchar(50) NOT NULL,
  `allowed` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_page` (`user_id`,`page_key`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_admin_permissions` VALUES ('1','2','teacher_users','1');
INSERT INTO `tbl_admin_permissions` VALUES ('2','2','student_users','1');
INSERT INTO `tbl_admin_permissions` VALUES ('3','2','Adminsubjects','1');
INSERT INTO `tbl_admin_permissions` VALUES ('4','2','Adminsections','1');
INSERT INTO `tbl_admin_permissions` VALUES ('5','2','Reports','1');
INSERT INTO `tbl_admin_permissions` VALUES ('6','2','subject_access','1');

DROP TABLE IF EXISTS `tbl_arrange_steps_results`;
CREATE TABLE `tbl_arrange_steps_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `game_title` varchar(255) NOT NULL,
  `step_text` varchar(255) NOT NULL,
  `submitted_position` int(11) NOT NULL,
  `correct_position` int(11) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `category` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `tbl_arrange_steps_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tbl_students` (`id`),
  CONSTRAINT `tbl_arrange_steps_results_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `tbl_lessons` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `tbl_assignment_submissions`;
CREATE TABLE `tbl_assignment_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `points_earned` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `graded_at` datetime DEFAULT NULL,
  `status` enum('submitted','late','missing') DEFAULT 'submitted',
  PRIMARY KEY (`id`),
  KEY `assignment_id` (`assignment_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_assignment_submissions` VALUES ('3','2','2','uploads/submissions/6a905245b348e_membership-record-1.pdf','','2026-08-27 09:05:41','100','','2026-08-27 09:15:31','submitted');

DROP TABLE IF EXISTS `tbl_assignments`;
CREATE TABLE `tbl_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL DEFAULT 0,
  `teacher_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `task` text DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` enum('pdf','docs','ppt') DEFAULT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL,
  `points` int(11) DEFAULT 100,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_assignments` VALUES ('1','1','3','1','Seatwork','Computer System Servicing','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London','Essay Works','Read first before answering.','Summary of the revision.pdf','uploads/assignments/6a3f60a1533f3_Summary of the revision.pdf','','2026-06-26 23:33:21','2026-06-29','23:59:00','100','2026-06-27 13:33:21');
INSERT INTO `tbl_assignments` VALUES ('2','1','3','1','Seatwork','Computer Assignment','This is the computer assignment assembly','Essay','Write a short essay explaining what a cooperative is and why cooperatives are important to their members and the community.\r\n\r\nIn your essay, discuss the following:\r\n\r\nWhat is a cooperative?\r\n1. What are the main purposes of a cooperative?\r\n2. What benefits can members receive from joining a cooperative?\r\n3. How can a cooperative help the community?\r\n4. Why is active member participation important to the success of a cooperative?\r\n\r\nWrite your answer in 2–3 paragraphs using your own words. Provide at least one example of how a cooperative can help its members or community.','BSIS-CourseModules-GEC2000-SIGNED.pdf','uploads/assignments/6a8a5b2dae1e2_BSIS-CourseModules-GEC2000-SIGNED.pdf','','2026-08-22 20:30:05','2026-09-23','12:00:00','100','2026-08-23 10:30:05');

DROP TABLE IF EXISTS `tbl_dragdrop_results`;
CREATE TABLE `tbl_dragdrop_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `game_title` varchar(255) NOT NULL,
  `student_id` int(11) NOT NULL,
  `item_label` varchar(255) NOT NULL,
  `student_answer` varchar(255) NOT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `completed_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_dd_item` (`lesson_id`,`game_title`,`student_id`,`item_label`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `tbl_enrollment_invitations`;
CREATE TABLE `tbl_enrollment_invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `grade_level_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` enum('pending','accepted','expired') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `accepted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_enrollment_invitations` VALUES ('1','73b4cf560f3c3e99cbe9dcf5b7f6011700261e3e46409827c0be407d2325bfa8','1','1','2','3','rogelioamoyan123@gmail.com','2','accepted','2026-06-27 08:48:25','2026-07-04 02:48:25',NULL);

DROP TABLE IF EXISTS `tbl_grade_level`;
CREATE TABLE `tbl_grade_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_grade_level` VALUES ('1','Grade 11');
INSERT INTO `tbl_grade_level` VALUES ('2','Grade 12');

DROP TABLE IF EXISTS `tbl_interactive_contents`;
CREATE TABLE `tbl_interactive_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) DEFAULT NULL,
  `type` enum('text','image','video','quiz','activity','flashcard','drag_drop','arrange_steps') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `arrange_category` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `key_idea` text DEFAULT NULL,
  `body` text DEFAULT NULL,
  `question` text DEFAULT NULL,
  `question_type` varchar(50) DEFAULT NULL,
  `choice_a` varchar(255) DEFAULT NULL,
  `choice_b` varchar(255) DEFAULT NULL,
  `choice_c` varchar(255) DEFAULT NULL,
  `choice_d` varchar(255) DEFAULT NULL,
  `correct_ans` varchar(10) DEFAULT NULL,
  `model_answer` text DEFAULT NULL,
  `passing_score` int(11) DEFAULT NULL,
  `total_points` int(11) DEFAULT NULL,
  `card_front` text DEFAULT NULL,
  `card_back` text DEFAULT NULL,
  `card_type` varchar(50) DEFAULT NULL,
  `dragdrop_category` varchar(255) DEFAULT NULL,
  `dragdrop_category_description` text DEFAULT NULL,
  `dragdrop_item_label` varchar(255) DEFAULT NULL,
  `dragdrop_item_subtitle` varchar(255) DEFAULT NULL,
  `dragdrop_item_image` varchar(255) DEFAULT NULL,
  `step_order` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_interactive_contents` VALUES ('1','1','image','Image Captions',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a3f58d1ac3f1.jpg','317180131_2220360938125629_2050753479785055038_n.jpg','jpg','2026-06-26 23:00:01');
INSERT INTO `tbl_interactive_contents` VALUES ('2','3','video','Dota 2 Tournament',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'https://www.youtube.com/watch?v=t_tCBdPPZXc',NULL,'url','2026-06-26 23:00:01');
INSERT INTO `tbl_interactive_contents` VALUES ('3','13','',NULL,NULL,NULL,'qweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasd','Understanding Culture Society and Politics',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 04:12:17');
INSERT INTO `tbl_interactive_contents` VALUES ('4','13','',NULL,NULL,NULL,NULL,'qweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 04:12:17');
INSERT INTO `tbl_interactive_contents` VALUES ('5','13','','Software Layer',NULL,NULL,NULL,'qweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasdqweqweqweqweasdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 04:12:17');
INSERT INTO `tbl_interactive_contents` VALUES ('6','13','video','Hardware Tutorial',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'https://www.youtube.com/watch?v=nnMnB6yqv4M',NULL,'url','2026-08-19 04:12:17');
INSERT INTO `tbl_interactive_contents` VALUES ('7','13','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a858181e8674.jpg','653707766_26228841010101886_8794212298714127669_n.jpg','jpg','2026-08-19 04:12:17');
INSERT INTO `tbl_interactive_contents` VALUES ('8','13','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a858181e8de2.jpg','653707766_26228841010101886_8794212298714127669_n.jpg','jpg','2026-08-19 04:12:17');
INSERT INTO `tbl_interactive_contents` VALUES ('9','14','',NULL,NULL,NULL,'eqweqweqwe','qweqweqw',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 04:13:05');
INSERT INTO `tbl_interactive_contents` VALUES ('10','14','',NULL,NULL,NULL,NULL,'qweqweqweqweqweqwe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 04:13:05');
INSERT INTO `tbl_interactive_contents` VALUES ('11','15','',NULL,NULL,NULL,'sdsdsdsd','sdsdsdsd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 04:14:46');
INSERT INTO `tbl_interactive_contents` VALUES ('12','16','',NULL,NULL,NULL,NULL,'qweqwewqeqwe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 06:08:52');
INSERT INTO `tbl_interactive_contents` VALUES ('13','16','',NULL,NULL,NULL,NULL,'qweqweqweqweqwe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 06:08:52');
INSERT INTO `tbl_interactive_contents` VALUES ('14','16','',NULL,NULL,NULL,NULL,'qweqweqweqwe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 06:08:52');
INSERT INTO `tbl_interactive_contents` VALUES ('15','16','',NULL,NULL,NULL,NULL,'qweqweqweqweqwe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 06:08:52');
INSERT INTO `tbl_interactive_contents` VALUES ('16','17','text',NULL,NULL,NULL,NULL,'asdasdadsas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 09:41:54');
INSERT INTO `tbl_interactive_contents` VALUES ('17','17','text',NULL,NULL,NULL,NULL,'asdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 09:41:54');
INSERT INTO `tbl_interactive_contents` VALUES ('18','17','text',NULL,NULL,NULL,NULL,'asdadsasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 09:41:54');
INSERT INTO `tbl_interactive_contents` VALUES ('19','18','text','What is a Cooperative?',NULL,NULL,'A cooperative is built around people working together to meet their common needs and achieve shared goals.','A cooperative is an organization that is owned and controlled by people who voluntarily join together to meet their common economic, social, or community needs. Unlike organizations that primarily focus on serving outside investors, cooperatives are formed to benefit their members.\r\n\r\nMembers contribute to the cooperative, participate in its activities, and share responsibility for its success. Through cooperation, members can access services, resources, and opportunities that may be difficult to obtain individually.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('20','18','text',NULL,NULL,NULL,NULL,'Cooperatives are created because people often have needs that can be addressed more effectively by working together. Members may combine their resources to obtain financial services, purchase goods, market products, or access other services.\r\n\r\nThe cooperative provides a way for members to participate in an organization where they can contribute to decisions and benefit from its services.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('21','18','text','How Does a Cooperative Work?',NULL,NULL,'The success of a cooperative depends on both effective management and active member participation.','A cooperative operates through the participation of its members. Members contribute resources, use the services provided by the cooperative, and participate in important decisions. The cooperative is managed according to established policies, rules, and principles.\r\n\r\nThe success of a cooperative depends not only on its officers and employees but also on the active participation and responsibility of its members.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('22','19','text','Purpose of Cooperatives',NULL,NULL,NULL,'The main purpose of a cooperative is to provide services and opportunities that respond to the common needs of its members. Depending on its type, a cooperative may provide savings and loan services, agricultural support, consumer products, housing services, employment opportunities, or other forms of assistance.\r\n\r\nRather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals. Rather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.\r\n\r\n- Rather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.\r\n- Rather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.\r\n- Rather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('23','19','text',NULL,NULL,NULL,NULL,'Membership in a cooperative can provide various benefits. Members may gain access to services, financial opportunities, education, training, and other programs offered by the cooperative.\r\n\r\nMembers can also participate in decision-making and contribute to the direction of the organization.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('24','19','text',NULL,NULL,NULL,NULL,'Cooperatives can contribute to community development by creating economic opportunities, supporting local activities, providing services, and encouraging people to work together. A strong cooperative can help improve the well-being of its members while contributing to the development of the community.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('25','20','text','Consumer Cooperative',NULL,NULL,NULL,'A consumer cooperative is formed to provide goods or services to its members. Members can purchase products or access services through the cooperative, often with the goal of meeting their common needs.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('26','20','text','Credit or Financial Cooperative',NULL,NULL,NULL,'A credit or financial cooperative provides financial services to its members. These may include savings, loans, and other financial services. Members contribute to the cooperative and may access financial services according to its policies and requirements.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('27','20','text',NULL,NULL,NULL,NULL,'Agricultural cooperatives are formed by people involved in agriculture. Members may work together to purchase supplies, process products, market agricultural goods, or improve access to resources and services.\r\n\r\nAgricultural cooperatives are formed by people involved in agriculture. Members may work together to purchase supplies, process products, market agricultural goods, or improve access to resources and services.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_contents` VALUES ('28','21','text','What is a Cooperative?',NULL,NULL,NULL,'A cooperative is an organization that is owned and controlled by people who voluntarily join together to meet their common economic, social, or community needs. Unlike organizations that primarily focus on serving outside investors, cooperatives are formed to benefit their members.\r\n\r\nMembers contribute to the cooperative, participate in its activities, and share responsibility for its success. Through cooperation, members can access services, resources, and opportunities that may be difficult to obtain individually.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('29','21','text','Why Do Cooperatives Exist?',NULL,NULL,NULL,'Cooperatives are created because people often have needs that can be addressed more effectively by working together. Members may combine their resources to obtain financial services, purchase goods, market products, or access other services.\r\n\r\nThe cooperative provides a way for members to participate in an organization where they can contribute to decisions and benefit from its services.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('30','21','text','How Does a Cooperative Work?',NULL,NULL,NULL,'A cooperative operates through the participation of its members. Members contribute resources, use the services provided by the cooperative, and participate in important decisions. The cooperative is managed according to established policies, rules, and principles.\r\n\r\nThe success of a cooperative depends not only on its officers and employees but also on the active participation and responsibility of its members. The success of a cooperative depends not only on its officers and employees but also on the active participation and responsibility of its members.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('31','22','text','Purpose of Cooperatives',NULL,NULL,'The primary purpose of a cooperative is to serve the common needs of its members.','The main purpose of a cooperative is to provide services and opportunities that respond to the common needs of its members. Depending on its type, a cooperative may provide savings and loan services, agricultural support, consumer products, housing services, employment opportunities, or other forms of assistance.\r\n\r\nRather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('32','22','text',NULL,NULL,NULL,NULL,'Membership in a cooperative can provide various benefits. Members may gain access to services, financial opportunities, education, training, and other programs offered by the cooperative.\r\n\r\nMembers can also participate in decision-making and contribute to the direction of the organization.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('33','22','video','Hardware vs Software | What\'s the difference?',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'https://www.youtube.com/watch?v=IkH7qDaWzSU',NULL,'url','2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('34','22','text','Benefits to the Community',NULL,NULL,NULL,'Cooperatives can contribute to community development by creating economic opportunities, supporting local activities, providing services, and encouraging people to work together. A strong cooperative can help improve the well-being of its members while contributing to the development of the community.\r\n\r\nCooperatives can contribute to community development by creating economic opportunities, supporting local activities, providing services, and encouraging people to work together. A strong cooperative can help improve the well-being of its members while contributing to the development of the community.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('35','23','text','Consumer Cooperative',NULL,NULL,'Cooperative membership provides opportunities for members to access services while participating in the organization.','Membership in a cooperative can provide various benefits. Members may gain access to services, financial opportunities, education, training, and other programs offered by the cooperative.\r\n\r\nMembers can also participate in decision-making and contribute to the direction of the organization.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('36','23','image','Hardware vs Software',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a869872db0c4.jpg','images (4).jpg','jpg','2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('37','23','text','Credit or Financial Cooperative',NULL,NULL,NULL,'A credit or financial cooperative provides financial services to its members. These may include savings, loans, and other financial services. Members contribute to the cooperative and may access financial services according to its policies and requirements.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('38','23','text','Agricultural Cooperative',NULL,NULL,'Agricultural cooperatives help members work together to improve agricultural activities and opportunities.','Agricultural cooperatives are formed by people involved in agriculture. Members may work together to purchase supplies, process products, market agricultural goods, or improve access to resources and services.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('39','24','text','Consumer Cooperative',NULL,NULL,NULL,'A consumer cooperative is formed to provide goods or services to its members. Members can purchase products or access services through the cooperative, often with the goal of meeting their common needs.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('40','24','text','Credit or Financial Cooperative',NULL,NULL,NULL,'A credit or financial cooperative provides financial services to its members. These may include savings, loans, and other financial services. Members contribute to the cooperative and may access financial services according to its policies and requirements.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('41','24','text',NULL,NULL,NULL,NULL,'Agricultural cooperatives are formed by people involved in agriculture. Members may work together to purchase supplies, process products, market agricultural goods, or improve access to resources and services.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('42','24','text',NULL,NULL,NULL,NULL,'There are many other types of cooperatives, including housing cooperatives, workers\' cooperatives, transport cooperatives, and multipurpose cooperatives. Each type is organized according to the needs and goals of its members.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('43','24','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is a cooperative?','multiple_choice','An organization owned and controlled by its members','A business owned only by the government','A company controlled by outside investors','A temporary community activity','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('44','24','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is one of the main purposes of a cooperative?','multiple_choice','To serve the common needs of its members','To compete with all other organizations','To benefit only its officers','To eliminate member participation','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('45','24','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Why is member participation important in a cooperative?','multiple_choice','Members help contribute to the success and decision-making of the cooperative','Members are not allowed to make decisions','Only employees are responsible for the cooperative','Members are only required to pay fees','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('46','24','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Which type of cooperative provides financial services such as savings and loans?','multiple_choice','Agricultural cooperative','Consumer cooperative','Financial or credit cooperative','Housing cooperative','c',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('47','24','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'How can cooperatives contribute to the community?','multiple_choice','By supporting members and creating opportunities for community development','By preventing members from participating','By focusing only on individual interests','By avoiding community activities','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('48','25','text','Purpose of Cooperatives',NULL,NULL,NULL,'The main purpose of a cooperative is to provide services and opportunities that respond to the common needs of its members. Depending on its type, a cooperative may provide savings and loan services, agricultural support, consumer products, housing services, employment opportunities, or other forms of assistance.\r\n\r\nRather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('49','25','text','Benefits to Members',NULL,NULL,'Cooperative membership provides opportunities for members to access services while participating in the organization.','Membership in a cooperative can provide various benefits. Members may gain access to services, financial opportunities, education, training, and other programs offered by the cooperative.\r\n\r\nMembers can also participate in decision-making and contribute to the direction of the organization.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('50','25','activity','Understanding the Importance of Cooperatives',NULL,'Write a short essay explaining what a cooperative is and why cooperatives are important to their members and the community.\r\n\r\nIn your essay, discuss the following:\r\n\r\nWhat is a cooperative?\r\nWhat are the main purposes of a cooperative?\r\nWhat benefits can members receive from joining a cooperative?\r\nHow can a cooperative help the community?\r\nWhy is active member participation important to the success of a cooperative?\r\n\r\nWrite your answer in 2–3 paragraphs using your own words. Provide at least one example of how a cooperative can help its members or community.',NULL,NULL,'What is a cooperative, why is it important to its members and the community, and how can active member participation contribute to the success of a cooperative?','essay',NULL,NULL,NULL,NULL,NULL,'A cooperative is an organization formed by people who voluntarily work together to meet their common needs and goals. Members contribute to and participate in the cooperative while also having opportunities to benefit from its services. Cooperatives may provide different services such as savings, loans, consumer products, agricultural support, or other services depending on their purpose.\r\n\r\nCooperatives are important because they allow people to work together and access opportunities that may be difficult to obtain individually. They can support their members through financial services, education, training, and other programs while also contributing to community development. Active participation is important because members share responsibility for the success and direction of the cooperative.',NULL,'10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_contents` VALUES ('51','26','text',NULL,NULL,NULL,'A cooperative is built around people working together to meet their common needs and achieve shared goals.','A cooperative is an organization that is owned and controlled by people who voluntarily join together to meet their common economic, social, or community needs. Unlike organizations that primarily focus on serving outside investors, cooperatives are formed to benefit their members.\r\n\r\nMembers contribute to the cooperative, participate in its activities, and share responsibility for its success. Through cooperation, members can access services, resources, and opportunities that may be difficult to obtain individually.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('52','26','text','Why Do Cooperatives Exist?',NULL,NULL,NULL,'Cooperatives are created because people often have needs that can be addressed more effectively by working together. Members may combine their resources to obtain financial services, purchase goods, market products, or access other services.\r\n\r\nThe cooperative provides a way for members to participate in an organization where they can contribute to decisions and benefit from its services.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('53','26','text',NULL,NULL,NULL,NULL,'A cooperative operates through the participation of its members. Members contribute resources, use the services provided by the cooperative, and participate in important decisions. The cooperative is managed according to established policies, rules, and principles.\r\n\r\nThe success of a cooperative depends not only on its officers and employees but also on the active participation and responsibility of its members.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('54','26','text',NULL,NULL,NULL,NULL,'A cooperative operates through the participation of its members. Members contribute resources, use the services provided by the cooperative, and participate in important decisions. The cooperative is managed according to established policies, rules, and principles.\r\n\r\nThe success of a cooperative depends not only on its officers and employees but also on the active participation and responsibility of its members.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('55','27','text','Purpose of Cooperatives',NULL,NULL,NULL,'The main purpose of a cooperative is to provide services and opportunities that respond to the common needs of its members. Depending on its type, a cooperative may provide savings and loan services, agricultural support, consumer products, housing services, employment opportunities, or other forms of assistance.\r\n\r\nRather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('56','27','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a86c82e4b654.jpg','images (4).jpg','jpg','2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('57','27','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a86c82e4c78b.jpg','653707766_26228841010101886_8794212298714127669_n.jpg','jpg','2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('58','27','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a86c82e4cdf2.webp','Senior-High-School.webp','webp','2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('59','28','text','Consumer Cooperative',NULL,NULL,NULL,'A consumer cooperative is formed to provide goods or services to its members. Members can purchase products or access services through the cooperative, often with the goal of meeting their common needs.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('60','28','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is a cooperative?','multiple_choice','An organization owned and controlled by its members','A business owned only by the government','A company controlled by outside investors','A temporary community activity','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('61','28','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is one of the main purposes of a cooperative?','multiple_choice','To serve the common needs of its members','To compete with all other organizations','To benefit only its officers','To eliminate member participation','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('62','28','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Why is member participation important in a cooperative?','multiple_choice','Members help contribute to the success and decision-making of the cooperative','Members are not allowed to make decisions','Only employees are responsible for the cooperative','Members are only required to pay fees','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('63','28','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Which type of cooperative provides financial services such as savings and loans?','multiple_choice','Agricultural cooperative','Consumer cooperative','Financial or credit cooperative','Housing cooperative','c',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('64','28','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'How can cooperatives contribute to the community?','multiple_choice','By supporting members and creating opportunities for community development','By preventing members from participating','By focusing only on individual interests','By avoiding community activities','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('65','28','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is one of the main purposes of a cooperative?','multiple_choice','An organization owned and controlled by its members','Members are not allowed to make decisions','Financial or credit cooperative','Housing cooperative','b',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('66','28','quiz','Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Which type of cooperative provides financial services such as savings and loans?','multiple_choice','Members help contribute to the success and decision-making of the cooperative','By preventing members from participating','Only employees are responsible for the cooperative','By avoiding community activities','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('67','29','text','Consumer Cooperative',NULL,NULL,NULL,'A consumer cooperative is formed to provide goods or services to its members. Members can purchase products or access services through the cooperative, often with the goal of meeting their common needs.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('68','29','text','Credit or Financial Cooperative',NULL,NULL,NULL,'A credit or financial cooperative provides financial services to its members. These may include savings, loans, and other financial services. Members contribute to the cooperative and may access financial services according to its policies and requirements.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('69','29','text',NULL,NULL,NULL,NULL,'Agricultural cooperatives are formed by people involved in agriculture. Members may work together to purchase supplies, process products, market agricultural goods, or improve access to resources and services.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('70','29','flashcard',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cooperative','An organization owned and controlled by members who voluntarily work together to meet their common economic, social, or community needs.','term_definition',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('71','29','flashcard',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Member Participation','The active involvement of cooperative members in activities, decision-making, and other responsibilities that contribute to the success of the cooperative.','term_definition',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('72','29','flashcard',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cooperative Member','A person who voluntarily joins a cooperative, contributes according to its requirements, uses its services, and participates in its activities and decisions.','term_definition',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('73','29','flashcard',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'What is a cooperative?','A cooperative is an organization owned and controlled by members who voluntarily work together to meet their common economic, social, or community needs.','question_answer',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('74','29','text','Agricultural Cooperative',NULL,NULL,NULL,'Agricultural cooperatives are formed by people involved in agriculture. Members may work together to purchase supplies, process products, market agricultural goods, or improve access to resources and services.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('75','29','text',NULL,NULL,NULL,NULL,'Membership in a cooperative can provide various benefits. Members may gain access to services, financial opportunities, education, training, and other programs offered by the cooperative.\r\n\r\nMembers can also participate in decision-making and contribute to the direction of the organization.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_contents` VALUES ('76','30','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a87b559c6ece.jpg','images (4).jpg','jpg','2026-08-20 20:18:01');
INSERT INTO `tbl_interactive_contents` VALUES ('77','30','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a87b559c76ee.jpg','checking.jpg','jpg','2026-08-20 20:18:01');
INSERT INTO `tbl_interactive_contents` VALUES ('78','30','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a87b559c7d56.webp','Senior-High-School.webp','webp','2026-08-20 20:18:01');
INSERT INTO `tbl_interactive_contents` VALUES ('79','30','image','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a87b559c834a.jpg','Receipt-SAV-WDR-20260326-B7C754.jpg','jpg','2026-08-20 20:18:01');
INSERT INTO `tbl_interactive_contents` VALUES ('80','32','image','asdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a87e4133ae3f.jpg','images (4).jpg','jpg','2026-08-20 23:37:23');
INSERT INTO `tbl_interactive_contents` VALUES ('81','32','image','asdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a87e4133bd36.jpeg','ILEARN - Page 7.jpeg','jpeg','2026-08-20 23:37:23');
INSERT INTO `tbl_interactive_contents` VALUES ('82','32','image','asdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a87e4133c81d.jpg','images (4).jpg','jpg','2026-08-20 23:37:23');
INSERT INTO `tbl_interactive_contents` VALUES ('83','33','text',NULL,NULL,NULL,NULL,'xcxcxcxc',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-21 01:42:28');
INSERT INTO `tbl_interactive_contents` VALUES ('84','33','text',NULL,NULL,NULL,NULL,'xcxcxcxcx',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-21 01:42:28');
INSERT INTO `tbl_interactive_contents` VALUES ('85','35','text',NULL,NULL,NULL,NULL,'<h4 style=\"margin: 0px; font-weight: 600; color: rgb(33, 37, 41); font-size: 18px;\">Computer System Servicing<br></h4><span style=\"color: rgb(128, 128, 128); font-size: 15px;\">Computer System Servicing (CSS) provides interactive lessons, activities, simulations, and assessments that help students develop essential computer hardware, software, and networking skills.</span>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-23 14:44:45');
INSERT INTO `tbl_interactive_contents` VALUES ('86','35','text','Computer System Servicing',NULL,NULL,NULL,'<h4 style=\"margin: 0px; font-weight: 600; color: rgb(33, 37, 41); font-size: 18px;\">Computer System Servicing<br></h4><span style=\"color: rgb(128, 128, 128); font-size: 15px;\">Computer System Servicing (CSS) provides interactive lessons, activities, simulations, and assessments that help students develop essential computer hardware, software, and networking skills.</span>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-23 14:44:45');
INSERT INTO `tbl_interactive_contents` VALUES ('87','35','quiz','asd',NULL,'asd',NULL,NULL,'asd','multiple_choice','asd','asd','asd','asd','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-23 14:44:45');
INSERT INTO `tbl_interactive_contents` VALUES ('88','36','text',NULL,NULL,NULL,NULL,'<p data-start=\"1969\" data-end=\"2251\" class=\"PDq2pG_selectionAnchorContainer\">A cooperative is an organization that is owned and controlled by people who voluntarily join together to meet their common economic, social, or community needs. Unlike organizations that primarily focus on serving outside investors, cooperatives are formed to benefit their members.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\"></span></p>\r\n<p data-start=\"2253\" data-end=\"2489\">Members contribute to the cooperative, participate in its activities, and share responsibility for its success. Through cooperation, members can access services, resources, and opportunities that may be difficult to obtain individually.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('89','36','text',NULL,NULL,NULL,'Cooperation allows individuals to achieve goals that may be more difficult to accomplish alone.','<p data-start=\"2716\" data-end=\"2957\" class=\"PDq2pG_selectionAnchorContainer\">Cooperatives are created because people often have needs that can be addressed more effectively by working together. Members may combine their resources to obtain financial services, purchase goods, market products, or access other services.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\"></span></p>\r\n<p data-start=\"2959\" data-end=\"3105\">The cooperative provides a way for members to participate in an organization where they can contribute to decisions and benefit from its services.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('90','36','text',NULL,NULL,NULL,NULL,'<p data-start=\"3323\" data-end=\"3588\" class=\"PDq2pG_selectionAnchorContainer\">A cooperative operates through the participation of its members. Members contribute resources, use the services provided by the cooperative, and participate in important decisions. The cooperative is managed according to established policies, rules, and principles.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\"></span></p>\r\n<p data-start=\"3590\" data-end=\"3737\">The success of a cooperative depends not only on its officers and employees but also on the active participation and responsibility of its members.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('91','36','image','This is the sample hardware vs software',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a8d7877bf1bd.jpg','images (4).jpg','jpg','2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('92','36','image','This is the sample hardware vs software',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a8d7877c0a3f.jpg','ILMS.jpg','jpg','2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('93','36','image','This is the sample hardware vs software',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'/learning_management/uploads/lessons/images/img_6a8d7877c259d.jpg','653707766_26228841010101886_8794212298714127669_n.jpg','jpg','2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('94','37','text',NULL,NULL,NULL,NULL,'<p data-start=\"4148\" data-end=\"4462\" class=\"PDq2pG_selectionAnchorContainer\">The main purpose of a cooperative is to provide services and opportunities that respond to the common needs of its members. Depending on its type, a cooperative may provide savings and loan services, agricultural support, consumer products, housing services, employment opportunities, or other forms of assistance.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\"></span></p>\r\n<p data-start=\"4464\" data-end=\"4598\">Rather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('95','37','text','Benefits to Members',NULL,NULL,NULL,'<p data-start=\"4793\" data-end=\"4980\" class=\"PDq2pG_selectionAnchorContainer\">Membership in a cooperative can provide various benefits. Members may gain access to services, financial opportunities, education, training, and other programs offered by the cooperative.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\"></span></p>\r\n<p data-start=\"4982\" data-end=\"5082\">Members can also participate in decision-making and contribute to the direction of the organization.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('96','37','text',NULL,NULL,NULL,NULL,'Cooperatives can contribute to community development by creating economic opportunities, supporting local activities, providing services, and encouraging people to work together. A strong cooperative can help improve the well-being of its members while contributing to the development of the community.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('97','37','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is a cooperative?','multiple_choice','An organization owned and controlled by its members','A business owned only by the government','A company controlled by outside investors','A temporary community activity','a',NULL,'3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('98','37','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is one of the main purposes of a cooperative?','multiple_choice','To serve the common needs of its members','To compete with all other organizations','To benefit only its officers','To eliminate member participation','a',NULL,'3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('99','37','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Why is member participation important in a cooperative?','multiple_choice','Members help contribute to the success and decision-making of the cooperative','Members are not allowed to make decisions','Only employees are responsible for the cooperative','Members are only required to pay fees','a',NULL,'3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('100','37','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Which type of cooperative provides financial services such as savings and loans?','multiple_choice','Agricultural cooperative','Consumer cooperative','Financial or credit cooperative','Housing cooperative','c',NULL,'3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('101','37','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'How can cooperatives contribute to the community?','multiple_choice','By supporting members and creating opportunities for community development','By preventing members from participating','By focusing only on individual interests','By avoiding community activities','a',NULL,'3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('102','38','video','HARDWARE VS SOFTWARE | Difference Between Hardware And Software',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'https://www.youtube.com/watch?v=yMDVGUYWz8U',NULL,'url','2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('103','38','text',NULL,NULL,NULL,NULL,'A consumer cooperative is formed to provide goods or services to its members. Members can purchase products or access services through the cooperative, often with the goal of meeting their common needs.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('104','38','text','Credit or Financial Cooperative',NULL,NULL,NULL,'A credit or financial cooperative provides financial services to its members. These may include savings, loans, and other financial services. Members contribute to the cooperative and may access financial services according to its policies and requirements.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('105','39','text',NULL,NULL,NULL,NULL,'A credit or financial cooperative provides financial services to its members. These may include savings, loans, and other financial services. Members contribute to the cooperative and may access financial services according to its policies and requirements.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('106','39','activity','Understanding the Importance of Cooperatives',NULL,'Write a short essay explaining what a cooperative is and why cooperatives are important to their members and the community.\r\n\r\nIn your essay, discuss the following:\r\n\r\nWhat is a cooperative?\r\nWhat are the main purposes of a cooperative?\r\nWhat benefits can members receive from joining a cooperative?\r\nHow can a cooperative help the community?\r\nWhy is active member participation important to the success of a cooperative?\r\n\r\nWrite your answer in 2–3 paragraphs using your own words. Provide at least one example of how a cooperative can help its members or community.',NULL,NULL,'What is a cooperative, why is it important to its members and the community, and how can active member participation contribute to the success of a cooperative?','essay',NULL,NULL,NULL,NULL,NULL,'A cooperative is an organization formed by people who voluntarily work together to meet their common needs and goals. Members contribute to and participate in the cooperative while also having opportunities to benefit from its services. Cooperatives may provide different services such as savings, loans, consumer products, agricultural support, or other services depending on their purpose.\r\n\r\nCooperatives are important because they allow people to work together and access opportunities that may be difficult to obtain individually. They can support their members through financial services, education, training, and other programs while also contributing to community development. Active participation is important because members share responsibility for the success and direction of the cooperative.',NULL,'100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('107','39','activity','Applying Cooperative Principles in Real Life',NULL,'Write a short essay explaining how cooperative principles can be applied in real-life situations. Write your answer in 2–3 paragraphs using your own words. Provide at least one example of how members can practice cooperation, responsibility, or democratic participation in a cooperative.',NULL,NULL,'How can cooperative principles and values be applied in the daily activities of a cooperative, and why is it important for members to practice cooperation, responsibility, and active participation?','essay',NULL,NULL,NULL,NULL,NULL,'Cooperative principles and values can be applied through the active participation and responsible actions of members. Members can practice cooperation by working together toward common goals, participating in meetings, sharing ideas, and supporting other members. They can also demonstrate responsibility by following the cooperative\'s rules, using its services properly, and fulfilling their responsibilities as members.\r\n\r\nPracticing these principles is important because a cooperative depends on the involvement and cooperation of its members. When members actively participate in decision-making and support the activities of the cooperative, they help strengthen the organization and improve its ability to serve the needs of its members and community.',NULL,'100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('108','39','text',NULL,NULL,NULL,NULL,'Thank you so much !',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_contents` VALUES ('109','40','text',NULL,NULL,NULL,NULL,'<div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; color: rgb(10, 26, 46); overflow-wrap: break-word; word-break: break-word; max-width: 100%;\"><p data-start=\"4148\" data-end=\"4462\" class=\"PDq2pG_selectionAnchorContainer\" style=\"margin: 0px 0px 1rem; padding: 0px;\">The main purpose of a cooperative is to provide services and opportunities that respond to the common needs of its members. Depending on its type, a cooperative may provide savings and loan services, agricultural support, consumer products, housing services, employment opportunities, or other forms of assistance.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\" style=\"margin: 0px; padding: 0px;\"></span></p><p data-start=\"4464\" data-end=\"4598\" style=\"margin: 0px 0px 1rem; padding: 0px;\">Rather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.</p></div><div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; color: rgb(10, 26, 46); overflow-wrap: break-word; word-break: break-word; max-width: 100%;\"><div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; overflow-wrap: break-word; word-break: break-word; max-width: 100%;\"><p data-start=\"4793\" data-end=\"4980\" class=\"PDq2pG_selectionAnchorContainer\" style=\"margin: 0px 0px 1rem; padding: 0px;\">Membership in a cooperative can provide various benefits. Members may gain access to services, financial opportunities, education, training, and other programs offered by the cooperative.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\" style=\"margin: 0px; padding: 0px;\"></span></p><p data-start=\"4982\" data-end=\"5082\" style=\"margin: 0px 0px 1rem; padding: 0px;\">Members can also participate in decision-making and contribute to the direction of the organization.</p></div><div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; overflow-wrap: break-word; word-break: break-word; max-width: 100%;\"><div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; overflow-wrap: break-word; word-break: break-word; max-width: 100%;\">Cooperatives can contribute to community development by creating economic opportunities, supporting local activities, providing services, and encouraging people to work together. A strong cooperative can help improve the well-being of its members while contributing to the development of the community.</div></div></div>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('110','40','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is a cooperative?','multiple_choice','An organization owned and controlled by its members','A business owned only by the government','A company controlled by outside investors','A temporary community activity','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('111','40','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is one of the main purposes of a cooperative?','multiple_choice','To serve the common needs of its members','To compete with all other organizations','To benefit only its officers','To eliminate member participation','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('112','40','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Why is member participation important in a cooperative?','multiple_choice','Members help contribute to the success and decision-making of the cooperative','Members are not allowed to make decisions','Only employees are responsible for the cooperative','Members are only required to pay fees','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('113','40','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Which type of cooperative provides financial services such as savings and loans?','multiple_choice','Agricultural cooperative','Consumer cooperative','Financial or credit cooperative','Housing cooperative','c',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('114','40','quiz','Module 1 Quiz: Introduction to Cooperatives',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'How can cooperatives contribute to the community?','multiple_choice','By supporting members and creating opportunities for community development','By preventing members from participating','By focusing only on individual interests','By avoiding community activities','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('115','41','text',NULL,NULL,NULL,NULL,'<div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; color: rgb(10, 26, 46); overflow-wrap: break-word; word-break: break-word; max-width: 100%;\"><p data-start=\"4148\" data-end=\"4462\" class=\"PDq2pG_selectionAnchorContainer\" style=\"margin: 0px 0px 1rem; padding: 0px;\">The main purpose of a cooperative is to provide services and opportunities that respond to the common needs of its members. Depending on its type, a cooperative may provide savings and loan services, agricultural support, consumer products, housing services, employment opportunities, or other forms of assistance.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\" style=\"margin: 0px; padding: 0px;\"></span></p><p data-start=\"4464\" data-end=\"4598\" style=\"margin: 0px 0px 1rem; padding: 0px;\">Rather than focusing only on individual benefit, cooperatives encourage members to work together for shared economic and social goals.</p></div><div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; color: rgb(10, 26, 46); overflow-wrap: break-word; word-break: break-word; max-width: 100%;\"><div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; overflow-wrap: break-word; word-break: break-word; max-width: 100%;\"><p data-start=\"4793\" data-end=\"4980\" class=\"PDq2pG_selectionAnchorContainer\" style=\"margin: 0px 0px 1rem; padding: 0px;\">Membership in a cooperative can provide various benefits. Members may gain access to services, financial opportunities, education, training, and other programs offered by the cooperative.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\" style=\"margin: 0px; padding: 0px;\"></span></p><p data-start=\"4982\" data-end=\"5082\" style=\"margin: 0px 0px 1rem; padding: 0px;\">Members can also participate in decision-making and contribute to the direction of the organization.</p></div><div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; overflow-wrap: break-word; word-break: break-word; max-width: 100%;\"><div class=\"lesson-text-card\" style=\"margin: 0px 0px 20px; padding: 0px; line-height: 1.85; overflow-wrap: break-word; word-break: break-word; max-width: 100%;\">Cooperatives can contribute to community development by creating economic opportunities, supporting local activities, providing services, and encouraging people to work together. A strong cooperative can help improve the well-being of its members while contributing to the development of the community.</div></div></div>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('116','41','quiz','Module 2 Quiz: Hardware and Software',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is computer hardware?','multiple_choice','The physical components of a computer','Programs used by a computer','Files stored on a computer','Internet services','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('117','41','quiz','Module 2 Quiz: Hardware and Software',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Which of the following is an example of hardware?','multiple_choice','Microsoft Word','Windows','Keyboard','Google Chrome','c',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('118','41','quiz','Module 2 Quiz: Hardware and Software',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is computer software?','multiple_choice','The physical parts of a computer','Programs and instructions that tell a computer what to do','A computer\'s power supply','A computer\'s monitor','b',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('119','41','quiz','Module 2 Quiz: Hardware and Software',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'Which of the following is an example of system software?','multiple_choice','Windows','Keyboard','Printer','Mouse','a',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('120','41','quiz','Module 2 Quiz: Hardware and Software',NULL,'Read each question carefully and select the best answer. Choose only one answer for each question.',NULL,NULL,'What is the main purpose of an operating system?','multiple_choice','To provide instructions for assembling a computer','To manage computer hardware and software resources','To physically store computer components','To connect only printers to a computer','b',NULL,'75',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_contents` VALUES ('121','42','text',NULL,NULL,NULL,NULL,'asdasdasdasdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-29 07:44:21');
INSERT INTO `tbl_interactive_contents` VALUES ('122','42','text',NULL,NULL,NULL,NULL,'asdasdasdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-29 07:44:21');
INSERT INTO `tbl_interactive_contents` VALUES ('123','43','text','asdasdasd',NULL,NULL,NULL,'asdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-29 08:17:29');
INSERT INTO `tbl_interactive_contents` VALUES ('124','43','text',NULL,NULL,NULL,NULL,'asdasdasd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-29 08:17:29');
INSERT INTO `tbl_interactive_contents` VALUES ('125','44','drag_drop','Hardware Components Matching',NULL,'Drag each components',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Proccessing',NULL,'Proccessor',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-29 21:31:30');
INSERT INTO `tbl_interactive_contents` VALUES ('126','44','drag_drop','Hardware Components Matching',NULL,'Drag each components',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'White',NULL,'Black',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-29 21:31:30');
INSERT INTO `tbl_interactive_contents` VALUES ('127','45','drag_drop','asd',NULL,'asd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'asd',NULL,'cpu',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-29 21:31:30');
INSERT INTO `tbl_interactive_contents` VALUES ('128','45','drag_drop','asd',NULL,'asd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'das',NULL,'sdsds',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-29 21:31:30');
INSERT INTO `tbl_interactive_contents` VALUES ('129','46','text',NULL,NULL,NULL,NULL,'<strong data-start=\"723\" data-end=\"744\">Computer hardware</strong> refers to the physical components of a computer system that can be seen and touched. These components perform different functions, such as entering information, processing data, displaying results, and storing files. Understanding the purpose of each hardware component is important when assembling, maintaining, and troubleshooting a computer system.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:48:32');
INSERT INTO `tbl_interactive_contents` VALUES ('130','47','text',NULL,NULL,NULL,NULL,'<strong data-start=\"723\" data-end=\"744\">Computer hardware</strong> refers to the physical components of a computer system that can be seen and touched. These components perform different functions, such as entering information, processing data, displaying results, and storing files. Understanding the purpose of each hardware component is important when assembling, maintaining, and troubleshooting a computer system.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:48:35');
INSERT INTO `tbl_interactive_contents` VALUES ('131','48','text',NULL,NULL,NULL,NULL,'<strong data-start=\"723\" data-end=\"744\">Computer hardware</strong> refers to the physical components of a computer system that can be seen and touched. These components perform different functions, such as entering information, processing data, displaying results, and storing files. Understanding the purpose of each hardware component is important when assembling, maintaining, and troubleshooting a computer system.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:52:28');
INSERT INTO `tbl_interactive_contents` VALUES ('132','48','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices',NULL,'Keyboard',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:52:28');
INSERT INTO `tbl_interactive_contents` VALUES ('133','48','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices',NULL,'Mouse',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:52:28');
INSERT INTO `tbl_interactive_contents` VALUES ('134','48','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Processing Components',NULL,'CPU (Processor)',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:52:28');
INSERT INTO `tbl_interactive_contents` VALUES ('135','48','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices',NULL,'Monitor',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:52:28');
INSERT INTO `tbl_interactive_contents` VALUES ('136','48','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices',NULL,'Printer',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:52:28');
INSERT INTO `tbl_interactive_contents` VALUES ('137','48','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Storage Devices',NULL,'Hard Disk Drive (HDD)',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 10:52:28');
INSERT INTO `tbl_interactive_contents` VALUES ('138','49','text',NULL,NULL,NULL,NULL,'<strong data-start=\"723\" data-end=\"744\">Computer hardware</strong> refers to the physical components of a computer system that can be seen and touched. These components perform different functions, such as entering information, processing data, displaying results, and storing files. Understanding the purpose of each hardware component is important when assembling, maintaining, and troubleshooting a computer system.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 11:14:16');
INSERT INTO `tbl_interactive_contents` VALUES ('139','49','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices',NULL,'Keyboard','Used to type text, numbers, and commands.',NULL,NULL,NULL,NULL,NULL,'2026-08-30 11:14:16');
INSERT INTO `tbl_interactive_contents` VALUES ('140','49','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices',NULL,'Monitor',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 11:14:16');
INSERT INTO `tbl_interactive_contents` VALUES ('141','49','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Processing Components',NULL,'CPU',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 11:14:16');
INSERT INTO `tbl_interactive_contents` VALUES ('142','49','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Storage Devices',NULL,'HDD',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 11:14:16');
INSERT INTO `tbl_interactive_contents` VALUES ('143','49','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Power & Cooling Components',NULL,'CPU Cooling Fan',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 11:14:16');
INSERT INTO `tbl_interactive_contents` VALUES ('144','50','text',NULL,NULL,NULL,NULL,'<strong data-start=\"723\" data-end=\"744\">Computer hardware</strong> refers to the physical components of a computer system that can be seen and touched. These components perform different functions, such as entering information, processing data, displaying results, and storing files. Understanding the purpose of each hardware component is important when assembling, maintaining, and troubleshooting a computer system.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:27:01');
INSERT INTO `tbl_interactive_contents` VALUES ('145','50','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices',NULL,'Mouse',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:27:01');
INSERT INTO `tbl_interactive_contents` VALUES ('146','50','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices',NULL,'Speakers',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:27:01');
INSERT INTO `tbl_interactive_contents` VALUES ('147','50','drag_drop','Computer Hardware Components Matching',NULL,'Drag each computer hardware component to the category that best describes its primary function. Read the name and description of each item carefully before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Processing Components',NULL,'RAM',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:27:01');
INSERT INTO `tbl_interactive_contents` VALUES ('148','50','drag_drop','Computer Hardware Classification Challenge',NULL,'Drag each hardware component to the category that best describes its primary function. Carefully identify the purpose of each component before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Storage Devices',NULL,'HDD',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:27:01');
INSERT INTO `tbl_interactive_contents` VALUES ('149','50','drag_drop','Computer Hardware Classification Challenge',NULL,'Drag each hardware component to the category that best describes its primary function. Carefully identify the purpose of each component before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Networking & Communication Devices',NULL,'Router',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:27:01');
INSERT INTO `tbl_interactive_contents` VALUES ('150','50','drag_drop','Computer Hardware Classification Challenge',NULL,'Drag each hardware component to the category that best describes its primary function. Carefully identify the purpose of each component before placing it in the correct category.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Power & Cooling Components',NULL,'Case Fan',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:27:01');
INSERT INTO `tbl_interactive_contents` VALUES ('151','51','text',NULL,NULL,NULL,NULL,'lklklkllklklklkllklkl',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('152','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices',NULL,'Keyboard',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('153','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices',NULL,'Mouse',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('154','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices',NULL,'Scanner',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('155','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices',NULL,'Monitor',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('156','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices',NULL,'Printer',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('157','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices',NULL,'Speakers',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('158','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Processing Components',NULL,'CPU',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('159','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Processing Components',NULL,'GPU',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('160','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Storage Devices',NULL,'USB',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('161','51','drag_drop','ijijijij',NULL,'bnbnbnbnbn',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Networking & Communication Devices',NULL,'Modem',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_contents` VALUES ('162','52','text',NULL,NULL,NULL,NULL,'jkjkjk',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('163','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices',NULL,'Keyboard',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('164','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices',NULL,'Monitor',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('165','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Processing Components',NULL,'GPU',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('166','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Storage Devices',NULL,'HDD',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('167','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Networking & Communication Devices',NULL,'Flash',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('168','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Power & Cooling Components',NULL,'Modem',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('169','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Components',NULL,'Cooling',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('170','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cooling',NULL,'NIC',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('171','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Processing',NULL,'CPU',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('172','52','drag_drop','jkjk',NULL,'jkjkj',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Devices',NULL,'USB Flash Drive',NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_contents` VALUES ('173','53','drag_drop','Hardware Matching Components',NULL,'Drag each components',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Processing','Components that process instructions and perform calculations.','Processor','','',NULL,NULL,NULL,NULL,'2026-09-03 05:23:44');
INSERT INTO `tbl_interactive_contents` VALUES ('174','53','drag_drop','Hardware Matching Components',NULL,'Drag each components',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Output Devices','Devices that display or produce information from a computer.','Monitor',NULL,NULL,NULL,NULL,NULL,NULL,'2026-09-03 04:50:23');
INSERT INTO `tbl_interactive_contents` VALUES ('175','53','drag_drop','Hardware Matching Components',NULL,'Drag each components',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Input Devices','Devices used to enter data or commands into a computer.','Keyboard','',NULL,NULL,NULL,NULL,NULL,'2026-09-03 05:23:47');
INSERT INTO `tbl_interactive_contents` VALUES ('196','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Prepare the necessary tools and computer components.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('197','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Install the power supply unit (PSU) into the computer case.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('198','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Install the processor (CPU) onto the motherboard.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('199','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Apply thermal paste and install the CPU cooler.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'3',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('200','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Install the RAM modules into the motherboard.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'4',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('201','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Install the motherboard into the computer case.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'5',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('202','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Install the storage drive, such as an SSD or HDD.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('203','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Connect the power and data cables to the components.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'7',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('204','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Install the graphics card and other expansion cards if needed.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'8',NULL,NULL,NULL,'2026-09-03 01:59:31');
INSERT INTO `tbl_interactive_contents` VALUES ('205','57','arrange_steps','Proper Procedure for Assembling a Desktop Computer','Hardware Assembly','Arrange the following steps in the correct order to successfully assemble a desktop computer.',NULL,NULL,'Check all connections, close the case, and test the computer.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'9',NULL,NULL,NULL,'2026-09-03 01:59:31');

DROP TABLE IF EXISTS `tbl_interactive_modules`;
CREATE TABLE `tbl_interactive_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_interactive_modules` VALUES ('1','1',NULL,'Module 1: Computer System Servicing','qweqweqweqweqweqweqweqwe','2026-06-26 23:00:01');
INSERT INTO `tbl_interactive_modules` VALUES ('2','1',NULL,'Module 2: Computer System','qweqweqweqweqweqweqweqwe','2026-06-27 06:52:51');
INSERT INTO `tbl_interactive_modules` VALUES ('3','1',NULL,'Module 3: Hardware Assembly','asdqweqweasd','2026-06-27 08:50:54');
INSERT INTO `tbl_interactive_modules` VALUES ('4','1',NULL,'Module 4: Software Assembly','asdqweqweqweqwe','2026-06-28 04:27:17');
INSERT INTO `tbl_interactive_modules` VALUES ('5','1',NULL,'Module 5: Understanding Culture Society and Politics','qweqweqweqweasdasdasd','2026-08-19 04:12:17');
INSERT INTO `tbl_interactive_modules` VALUES ('6','1',NULL,'Module 6: qweqweqwe','qweqweqwe','2026-08-19 04:13:05');
INSERT INTO `tbl_interactive_modules` VALUES ('7','1',NULL,'Module 7: asdasdasd','asdasdsdsds','2026-08-19 04:14:46');
INSERT INTO `tbl_interactive_modules` VALUES ('8','1',NULL,'Module 8: qweqwe','qweqweqweqw','2026-08-19 06:08:52');
INSERT INTO `tbl_interactive_modules` VALUES ('9','1',NULL,'Module 9: qweqwe','qweqweasdas','2026-08-19 09:41:54');
INSERT INTO `tbl_interactive_modules` VALUES ('10','1',NULL,'Module 10: Introduction to Cooperatives','This module introduces learners to the meaning, purpose, importance, and different types of cooperatives. It helps learners understand how cooperatives are organized to meet the common needs of their members and how members contribute to the growth and success of the cooperative.','2026-08-19 12:32:03');
INSERT INTO `tbl_interactive_modules` VALUES ('11','1',NULL,'Module 11: Introduction to Cooperatives','This module introduces learners to the meaning, purpose, importance, and different types of cooperatives. It helps learners understand how cooperatives are organized to meet the common needs of their members and how members contribute to the growth and success of the cooperative.','2026-08-20 00:02:26');
INSERT INTO `tbl_interactive_modules` VALUES ('12','1',NULL,'Module 12: Introduction to Cooperatives','This module introduces learners to the meaning, purpose, importance, and different types of cooperatives. It helps learners understand how cooperatives are organized to meet the common needs of their members and how members contribute to the growth and success of the cooperative.','2026-08-20 03:26:06');
INSERT INTO `tbl_interactive_modules` VALUES ('13','1',NULL,'Module 13: asdasdasd','asdasdasd','2026-08-20 20:18:01');
INSERT INTO `tbl_interactive_modules` VALUES ('14','1',NULL,'Module 14: adssad','adsasd','2026-08-20 22:19:26');
INSERT INTO `tbl_interactive_modules` VALUES ('15','1',NULL,'Module 15: asdasd','asdasdasds','2026-08-20 23:37:23');
INSERT INTO `tbl_interactive_modules` VALUES ('16','1',NULL,'Module 16: xcxcxcx','xcxcxcxc','2026-08-21 01:42:28');
INSERT INTO `tbl_interactive_modules` VALUES ('17','1','1','Module 17: Understanding Culture Society and Politics','asdasdasdqweqwe','2026-08-23 14:38:54');
INSERT INTO `tbl_interactive_modules` VALUES ('18','1','1','Module 18: Understanding Culture Society and Politics','asdasdasdqweqwe','2026-08-23 14:44:45');
INSERT INTO `tbl_interactive_modules` VALUES ('19','1','1','Module 19: Introduction to Cooperatives','This module introduces learners to the meaning, purpose, importance, and different types of cooperatives. It helps learners understand how cooperatives are organized to meet the common needs of their members and how members contribute to the growth and success of the cooperative.','2026-08-25 05:11:51');
INSERT INTO `tbl_interactive_modules` VALUES ('20','1','1','Module 20: Computer System Servicing','Computer System Servicing (CSS) provides interactive lessons, activities, simulations, and assessments that help students develop essential computer hardware, software, and networking skills.','2026-08-26 10:26:20');
INSERT INTO `tbl_interactive_modules` VALUES ('21','1','1','Module 21: qwe','lkjlkjlkjlkjlk','2026-08-29 07:44:21');
INSERT INTO `tbl_interactive_modules` VALUES ('22','1','1','Module 22: ASD','ASD','2026-08-29 08:17:29');
INSERT INTO `tbl_interactive_modules` VALUES ('23','1','1','Module 23: asdasdasd','asdasdasdasd','2026-08-29 20:10:34');
INSERT INTO `tbl_interactive_modules` VALUES ('24','1','1','Module 24: asd','asd','2026-08-29 20:16:34');
INSERT INTO `tbl_interactive_modules` VALUES ('25','1','1','Module 25: Introduction to Computer Hardware and Components','Learn the basic components of a computer system, their functions, and how different hardware devices work together. This module introduces students to common input, output, storage, and processing devices used in computer systems.','2026-08-30 10:48:32');
INSERT INTO `tbl_interactive_modules` VALUES ('26','1','1','Module 26: Introduction to Computer Hardware and Components','Learn the basic components of a computer system, their functions, and how different hardware devices work together. This module introduces students to common input, output, storage, and processing devices used in computer systems.','2026-08-30 10:48:35');
INSERT INTO `tbl_interactive_modules` VALUES ('27','1','1','Module 27: Introduction to Computer Hardware and Components','Learn the basic components of a computer system, their functions, and how different hardware devices work together. This module introduces students to common input, output, storage, and processing devices used in computer systems.','2026-08-30 10:52:28');
INSERT INTO `tbl_interactive_modules` VALUES ('28','1','1','Module 28: Introduction to Computer Hardware and Components','Learn the basic components of a computer system, their functions, and how different hardware devices work together. This module introduces students to common input, output, storage, and processing devices used in computer systems.','2026-08-30 11:14:16');
INSERT INTO `tbl_interactive_modules` VALUES ('29','1','1','Module 29: Introduction to Computer Hardware and Components','Learn the basic components of a computer system, their functions, and how different hardware devices work together. This module introduces students to common input, output, storage, and processing devices used in computer systems.','2026-08-30 13:27:01');
INSERT INTO `tbl_interactive_modules` VALUES ('30','1','1','Module 30: qweqweqweqweqwe','asdasdasd','2026-08-30 13:34:18');
INSERT INTO `tbl_interactive_modules` VALUES ('31','1','1','Module 31: jkjk','jkjk','2026-08-30 13:42:03');
INSERT INTO `tbl_interactive_modules` VALUES ('32','1','1','Module 32: xcxcxc','xcxcxcx','2026-09-01 06:57:08');
INSERT INTO `tbl_interactive_modules` VALUES ('33','1','1','Module 33: Arrange','ArrangeArrangeArrangeArrangeArrange','2026-09-01 10:06:49');
INSERT INTO `tbl_interactive_modules` VALUES ('34','1','1','Module 34: asas','asasa','2026-09-01 10:15:54');
INSERT INTO `tbl_interactive_modules` VALUES ('35','1','1','Module 35: asas','asasa','2026-09-01 10:19:01');
INSERT INTO `tbl_interactive_modules` VALUES ('36','1','1','Module 36: adsasd','asdasdasd','2026-09-01 10:23:22');

DROP TABLE IF EXISTS `tbl_landing_videos`;
CREATE TABLE `tbl_landing_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `category_label` varchar(60) DEFAULT NULL,
  `duration_label` varchar(20) DEFAULT NULL,
  `youtube_video_id` varchar(30) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_landing_videos` VALUES ('1','How to Choose the Right Strand','Guidance','14 min','tK2N9dO5mZY','1','1');
INSERT INTO `tbl_landing_videos` VALUES ('2','A Day in STEM Class','Academic Track','11 min','swapSSDN8g','2','1');
INSERT INTO `tbl_landing_videos` VALUES ('3','Inside a TVL Workshop','TVL Track','16 min','Sfxqq5A8cJE','3','1');
INSERT INTO `tbl_landing_videos` VALUES ('4','Sports & Arts Track Highlights','Sports & Arts','13 min','bS0JgBKFodc','4','1');

DROP TABLE IF EXISTS `tbl_lessons`;
CREATE TABLE `tbl_lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interactive_module_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `file_path` text DEFAULT NULL,
  `video_path` text DEFAULT NULL,
  `file_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interactive_module_id` (`interactive_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_lessons` VALUES ('1','1','Lesson 1: qweqweqewadsad','asdqwwe','asdqweqweqweasdasdddddddddddddddddsdas',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('2','1','Lesson 3: qweqweasd','asdqweqwe','asdqweqweqweqwe',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('3','1','Lesson 5: asdasdqweqwe','qweqweqweasd','asdqweqwe',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('4','2','Lesson 1: asdasdasdqweqwe','qweqweqweqwe','asdasdasddsadqweweweertrtrtr',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('5','2','Lesson 3: xcvvxbcvb','cvbcvbcvb','cvbcvbcvb',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('6','3','Lesson 1: qweqweqwe','asdasdqwe','asdqweqweqw',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('7','3','Lesson 3: qweqweqweqweasd','asdasdqwe','asdasdqweqwe',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('8','4','Lesson 1: qweqweasda','asdqweqw','asdqweqw',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('9','4','Lesson 3: qweqweasd','asdqweqw','asdqweqwe',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('10','4','Lesson 5: qweqweasdasd','qweqwedasd','qweqwdasd',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('11','4','Lesson 7: qweqweasdasd','qweqwe','asdasdqweqwe',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('12','4','Lesson 9: qweqweasd','qweqweqwe','qweqweqweqwe',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('13','5','Lesson 1: Understanding Culture Society and Politics','Understanding Culture Society and Politics',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('14','6','Lesson 1: qweqweqwe','qweqweqwe',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('15','7','Lesson 1: sdsdsds','sdsdsdsd',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('16','8','Lesson 1: qweqweqweqw','qweqweqweqw',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('17','9','Lesson 1: asdasdasd','asdasdas',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('18','10','Lesson 1: What is a Cooperative?','Understanding the Meaning and Purpose of Cooperatives',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('19','10','Lesson 3: Purpose and Importance of Cooperatives','Understanding How Cooperatives Help Their Members and Communities',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('20','10','Lesson 5: Types of Cooperatives','Understanding Different Types of Cooperatives',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('21','11','Lesson 1: What is a Cooperative?','Understanding the Meaning and Purpose of Cooperatives',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('22','11','Lesson 3: Purpose and Importance of Cooperatives','Understanding How Cooperatives Help Their Members and Communities',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('23','11','Lesson 5: Types of Cooperatives','Understanding Different Types of Cooperatives',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('24','11','Lesson 7: Types of Cooperatives','Understanding Different Types of Cooperatives',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('25','11','Lesson 9: Purpose and Importance of Cooperatives','Understanding How Cooperatives Help Their Members and Communities',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('26','12','Lesson 1: What is a Cooperative?','Understanding the Meaning and Purpose of Cooperatives',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('27','12','Lesson 3: Purpose and Importance of Cooperatives','Understanding How Cooperatives Help Their Members and Communities',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('28','12','Lesson 5: Types of Cooperatives','Understanding Different Types of Cooperatives',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('29','12','Lesson 7: Types of Cooperatives','Understanding Different Types of Cooperatives',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('30','13','Lesson 1: asdasdasd','asdasdasd',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('31','14','Lesson 1: asdasd','adsasd',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('32','15','Lesson 1: asdasdasd','asdasdasd',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('33','16','Lesson 1: xcxcxcx','cxcxcxcx',NULL,NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('34','17','Lesson 1: Understanding the Computer system servicing','Computer','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('35','18','Lesson 1: Understanding the Computer system servicing','Computer','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('36','19','Lesson 1: What is a Cooperative?','Understanding the Meaning and Purpose of Cooperatives','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('37','19','Lesson 3: Purpose and Importance of Cooperatives','Understanding How Cooperatives Help Their Members and Communities','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('38','19','Lesson 5: Types of Cooperatives','Understanding Different Types of Cooperatives','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('39','19','Lesson 7: Types of Cooperatives','Understanding Different Types of Cooperatives','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('40','20','Lesson 1: Purpose and Importance of Cooperatives','Understanding How Cooperatives Help Their Members and Communities','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('41','20','Lesson 3: Purpose and Importance of Cooperatives','Understanding How Cooperatives Help Their Members and Communities','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('42','21','Lesson 1: asdasd','asdasd','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('43','22','Lesson 1: asdasd','asdasd','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('44','23','Lesson 1: qweqweqwe','asdasdasd','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('45','24','Lesson 1: asd','asd','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('46','25','Lesson 1: Identifying Computer Hardware Components','Computer Hardware and Its Functions','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('47','26','Lesson 1: Identifying Computer Hardware Components','Computer Hardware and Its Functions','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('48','27','Lesson 1: Identifying Computer Hardware Components','Computer Hardware and Its Functions','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('49','28','Lesson 1: Identifying Computer Hardware Components','Computer Hardware and Its Functions','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('50','29','Lesson 1: Identifying Computer Hardware Components','Computer Hardware and Its Functions','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('51','30','Lesson 1: cvcvcvcv','cvcvfghghgh','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('52','31','Lesson 1: jkjk','jkjk','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('53','32','Lesson 1: xcxcxc','xcxcxc','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('54','33','Lesson 1: Arrange','Arrange','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('55','34','Lesson 1: asasa','asasas','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('56','35','Lesson 1: asasa','asasas','',NULL,NULL,NULL);
INSERT INTO `tbl_lessons` VALUES ('57','36','Lesson 1: asdasd','asdasdasd','',NULL,NULL,NULL);

DROP TABLE IF EXISTS `tbl_master_lrn`;
CREATE TABLE `tbl_master_lrn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_LRN` varchar(50) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `strand` varchar(50) DEFAULT NULL,
  `grade_level_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `school_year` varchar(9) DEFAULT NULL,
  `enrollment_status` varchar(20) DEFAULT 'Active',
  `is_matched` tinyint(1) DEFAULT 0,
  `matched_student_id` int(11) DEFAULT NULL,
  `imported_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_lrn_year` (`student_LRN`,`school_year`),
  KEY `fk_master_lrn_grade` (`grade_level_id`),
  KEY `fk_master_lrn_section` (`section_id`),
  CONSTRAINT `fk_master_lrn_grade` FOREIGN KEY (`grade_level_id`) REFERENCES `tbl_grade_level` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_master_lrn_section` FOREIGN KEY (`section_id`) REFERENCES `tbl_sections` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_master_lrn` VALUES ('73','107908100429','Javier','Renz','Dela Cruz','CSS','2','3','2026-2027','Enrolled','1','14','2026-09-05 06:04:13','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('74','107908100225','Katigbak','Danica','Salazar','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:04:13','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('75','107908100459','Lacson','Paolo','Fabros','CSS','1','1','2026-2027','Pending','0',NULL,'2026-09-05 06:04:13','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('76','107908100603','Manalo','Erika','Manalo','CSS','1','2','2026-2027','Pending','0',NULL,'2026-09-05 06:04:13','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('77','107908100284','Nolasco','Nathaniel','Tolentino','CSS','2','3','2026-2027','Enrolled','1','18','2026-09-05 06:04:13','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('78','107908100828','Ocampo','Cassandra','Cruz','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:04:13','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('79','107908100890','Pascual','Julian','Villanueva','CSS','1','1','2026-2027','Pending','0',NULL,'2026-09-05 06:04:13','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('80','107908100006','Quiambao','Alyssa','Aguilar','CSS','1','2','2026-2027','Pending','0',NULL,'2026-09-05 06:04:13','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('81','107908100777','Rosales','Marco','Hernandez','CSS','2','3','2026-2027','Enrolled','1','21','2026-09-05 06:04:13','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('82','107908100825','Sandoval','Jasmine','Ocampo','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:04:13','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('83','107908100331','Amoyan','Rogelio','Santos','CSS','2','3','2026-2027','Enrolled','1','3','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('84','107908100970','Dela Cruz','Maria','Flores','CSS','2','3','2026-2027','Enrolled','1','8','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('85','107908100154','Santos','Juan','Ramirez','CSS','2','3','2026-2027','Enrolled','1','23','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('86','107908100404','Bautista','Anna','Castro','CSS','2','3','2026-2027','Enrolled','1','5','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('87','107908100666','Villanueva','Mark','Javier','CSS','2','3','2026-2027','Enrolled','1','25','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('88','107908100049','Fernandez','Kristine','Quiambao','CSS','2','3','2026-2027','Enrolled','1','10','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('89','107908100074','Ramirez','Joshua','Lopez','CSS','2','3','2026-2027','Enrolled','1','19','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('90','107908100840','Domingo','Angel','Navarro','CSS','2','3','2026-2027','Enrolled','1','9','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('91','107908100548','Mendoza','Patricia','Mendoza','CSS','2','3','2026-2027','Enrolled','1','16','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('92','107908100096','Salazar','Kevin','Enriquez','CSS','2','3','2026-2027','Enrolled','1','22','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('93','107908100374','Reyes','Carlos','Lacson','CSS','2','3','2026-2027','Enrolled','1','20','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('94','107908100596','Garcia','Bianca','Sandoval','CSS','2','3','2026-2027','Enrolled','1','12','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('95','107908100059','Torres','Miguel','Torres','CSS','2','3','2026-2027','Enrolled','1','24','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('96','107908100931','Aquino','Sofia','Bautista','CSS','2','3','2026-2027','Enrolled','1','4','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('97','107908100519','Cruz','Diego','Castillo','CSS','2','3','2026-2027','Enrolled','1','7','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('98','107908100219','Flores','Camille','Gutierrez','CSS','2','3','2026-2027','Enrolled','1','11','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('99','107908100038','Gonzales','Rafael','Nolasco','CSS','2','3','2026-2027','Enrolled','1','13','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('100','107908100088','Navarro','Andrea','Uy','CSS','2','3','2026-2027','Enrolled','1','17','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('101','107908100444','Lopez','Gabriel','Aquino','CSS','2','3','2026-2027','Enrolled','1','15','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('102','107908100428','Castillo','Nicole','Fernandez','CSS','2','3','2026-2027','Enrolled','1','6','2026-09-05 06:10:26','2026-09-05 22:12:04');
INSERT INTO `tbl_master_lrn` VALUES ('103','107908100071','Aguilar','Emmanuel','Bernardo','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('104','107908100246','Bernardo','Trisha','Ilagan','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('105','107908100092','Castro','Adrian','Pascual','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('106','107908100564','De Guzman','Kyla','Reyes','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('107','107908100434','Diaz','Vincent','Gonzales','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('108','107908100060','Enriquez','Michelle','Domingo','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('109','107908100846','Fabros','Francis','Diaz','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('110','107908100579','Gutierrez','Angelica','Katigbak','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('111','107908100126','Hernandez','Christian','Rosales','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('112','107908100228','Ilagan','Kimberly','Garcia','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('113','107908100645','Javier','Renz','Dela Cruz','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('114','107908100642','Katigbak','Danica','Salazar','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('115','107908100063','Lacson','Paolo','Fabros','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('116','107908100590','Manalo','Erika','Manalo','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('117','107908100599','Nolasco','Nathaniel','Tolentino','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('118','107908100406','Ocampo','Cassandra','Cruz','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('119','107908100050','Pascual','Julian','Villanueva','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('120','107908100999','Quiambao','Alyssa','Aguilar','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('121','107908100226','Rosales','Marco','Hernandez','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');
INSERT INTO `tbl_master_lrn` VALUES ('122','107908100047','Sandoval','Jasmine','Ocampo','CSS','2','4','2026-2027','Pending','0',NULL,'2026-09-05 06:10:26','2026-09-05 22:15:22');

DROP TABLE IF EXISTS `tbl_module_progress`;
CREATE TABLE `tbl_module_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `interactive_module_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `status` enum('not_started','in_progress','completed') NOT NULL DEFAULT 'not_started',
  `completion_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_lessons` int(11) NOT NULL DEFAULT 0,
  `completed_lessons` int(11) NOT NULL DEFAULT 0,
  `is_finished` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `last_accessed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_module` (`student_id`,`interactive_module_id`),
  KEY `idx_student_id` (`student_id`),
  KEY `idx_interactive_module_id` (`interactive_module_id`),
  KEY `idx_subject_id` (`subject_id`),
  CONSTRAINT `fk_module_progress_interactive_module` FOREIGN KEY (`interactive_module_id`) REFERENCES `tbl_interactive_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_module_progress_student` FOREIGN KEY (`student_id`) REFERENCES `tbl_students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_module_progress_subject` FOREIGN KEY (`subject_id`) REFERENCES `tbl_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3007 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_module_progress` VALUES ('2994','3','32','1','in_progress','0.00','1','0','0','2026-09-15 15:11:59',NULL,'2026-09-15 15:12:17','2026-09-15 01:11:59');
INSERT INTO `tbl_module_progress` VALUES ('2997','3','33','1','in_progress','0.00','1','0','0','2026-09-15 15:12:43',NULL,'2026-09-15 15:12:43','2026-09-15 01:12:43');
INSERT INTO `tbl_module_progress` VALUES ('2999','3','34','1','in_progress','0.00','1','0','0','2026-09-15 15:12:57',NULL,'2026-09-15 15:12:57','2026-09-15 01:12:57');
INSERT INTO `tbl_module_progress` VALUES ('3001','3','25','1','in_progress','0.00','1','0','0','2026-09-15 15:13:10',NULL,'2026-09-15 15:13:10','2026-09-15 01:13:10');
INSERT INTO `tbl_module_progress` VALUES ('3003','3','36','1','in_progress','0.00','1','0','0','2026-09-15 15:13:25',NULL,'2026-09-15 17:49:33','2026-09-15 01:13:25');

DROP TABLE IF EXISTS `tbl_modules`;
CREATE TABLE `tbl_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL DEFAULT 0,
  `teacher_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` enum('pdf','docs','ppt') DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_modules` VALUES ('1','1','3','1','Module 1: Week 1 - 2','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London','APPLIED-PRACTICAL-RESEARCH-1_Q1_Mod1-V2.pdf','uploads/modules/6a3f8059c7c3a_APPLIED-PRACTICAL-RESEARCH-1_Q1_Mod1-V2.pdf','pdf','1030215','2026-06-27 01:48:41');

DROP TABLE IF EXISTS `tbl_notifications`;
CREATE TABLE `tbl_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `grade_level_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `section_id` int(11) DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_notifications` VALUES ('1','3',NULL,NULL,NULL,'1','No Class on Friday and Saturday','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London','announcement','2026-06-27 01:49:00','3','0');
INSERT INTO `tbl_notifications` VALUES ('2','0','3','2',NULL,NULL,'New Masterlist Available','3 student(s) were added to the official roster for Grade 12 - CSS 12-1. Open Bulk Enrollment to add them to your class.','masterlist_import','2026-09-03 23:18:29','3','0');
INSERT INTO `tbl_notifications` VALUES ('3','0','3','2',NULL,NULL,'New Masterlist Available','2 student(s) were added to the official roster for Grade 12 - CSS 12-2. Open Bulk Enrollment to add them to your class.','masterlist_import','2026-09-03 23:18:29','4','0');
INSERT INTO `tbl_notifications` VALUES ('4','0','3','2',NULL,NULL,'New Masterlist Available','10 student(s) were added to the official roster for Grade 12 - CSS 12-1. Open Bulk Enrollment to add them to your class.','masterlist_import','2026-09-05 06:04:13','3','0');
INSERT INTO `tbl_notifications` VALUES ('5','0','3','2',NULL,NULL,'New Masterlist Available','10 student(s) were added to the official roster for Grade 12 - CSS 12-2. Open Bulk Enrollment to add them to your class.','masterlist_import','2026-09-05 06:04:13','4','0');
INSERT INTO `tbl_notifications` VALUES ('6','0','3','2',NULL,NULL,'New Masterlist Available','20 student(s) were added to the official roster for Grade 12 - CSS 12-1. Open Bulk Enrollment to add them to your class.','masterlist_import','2026-09-05 06:10:26','3','0');
INSERT INTO `tbl_notifications` VALUES ('7','0','3','2',NULL,NULL,'New Masterlist Available','20 student(s) were added to the official roster for Grade 12 - CSS 12-2. Open Bulk Enrollment to add them to your class.','masterlist_import','2026-09-05 06:10:26','4','0');
INSERT INTO `tbl_notifications` VALUES ('8','3',NULL,NULL,NULL,'1','Reminder: Submit Module 1 Assignment','Hi everyone,\r\n\r\nThis is a reminder to submit your Module 1: Week 1-2 assignment on or before September 5, 2026 . Please make sure your output is complete and uploaded through the Classwork tab.\r\n\r\nLate submissions may not be accepted, so kindly submit ahead of time if possible. Let me know if you have any questions or concerns.\r\n\r\nThank you!','announcement','2026-09-05 19:21:02','3','0');

DROP TABLE IF EXISTS `tbl_quiz_results`;
CREATE TABLE `tbl_quiz_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `total` int(11) NOT NULL DEFAULT 0,
  `passed` tinyint(1) DEFAULT 0,
  `taken_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `answers` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_quiz_results` VALUES ('1','43','2','2','5','0','2026-08-23 11:45:27','{\"43\":\"a\",\"44\":\"b\",\"45\":\"d\",\"46\":\"c\",\"47\":\"b\"}');
INSERT INTO `tbl_quiz_results` VALUES ('2','43','2','2','5','0','2026-08-23 11:45:27','{\"43\":\"a\",\"44\":\"b\",\"45\":\"d\",\"46\":\"c\",\"47\":\"b\"}');
INSERT INTO `tbl_quiz_results` VALUES ('3','97','2','0','5','0','2026-08-25 10:49:53','{\"97\":\"b\",\"98\":\"b\",\"99\":\"c\",\"100\":\"d\",\"101\":\"b\"}');
INSERT INTO `tbl_quiz_results` VALUES ('9','116','2','1','5','0','2026-09-03 15:59:13','{\"116\":\"b\",\"117\":\"c\",\"118\":\"c\",\"119\":\"c\",\"120\":\"c\"}');
INSERT INTO `tbl_quiz_results` VALUES ('11','110','2','2','5','0','2026-09-03 16:05:07','{\"110\":\"a\",\"111\":\"c\",\"112\":\"b\",\"113\":\"c\",\"114\":\"b\"}');
INSERT INTO `tbl_quiz_results` VALUES ('12','87','2','0','1','0','2026-09-05 01:45:58','{\"87\":\"d\"}');

DROP TABLE IF EXISTS `tbl_recent_activity`;
CREATE TABLE `tbl_recent_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `activity_type` enum('module_opened','lesson_opened','activity_completed','quiz_completed','flashcards_viewed') NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_student_created` (`student_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=762 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_recent_activity` VALUES ('1','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-24 01:45:01');
INSERT INTO `tbl_recent_activity` VALUES ('2','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-24 01:45:11');
INSERT INTO `tbl_recent_activity` VALUES ('3','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-24 01:45:14');
INSERT INTO `tbl_recent_activity` VALUES ('4','2','quiz_completed','Quiz','Computer System Servicing','2026-08-24 01:45:27');
INSERT INTO `tbl_recent_activity` VALUES ('5','2','lesson_opened','Lesson 9: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 01:45:28');
INSERT INTO `tbl_recent_activity` VALUES ('6','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 01:48:10');
INSERT INTO `tbl_recent_activity` VALUES ('7','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-24 01:53:34');
INSERT INTO `tbl_recent_activity` VALUES ('8','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 01:54:49');
INSERT INTO `tbl_recent_activity` VALUES ('9','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-24 01:54:51');
INSERT INTO `tbl_recent_activity` VALUES ('10','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-24 01:54:54');
INSERT INTO `tbl_recent_activity` VALUES ('11','2','lesson_opened','Lesson 9: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 01:55:12');
INSERT INTO `tbl_recent_activity` VALUES ('12','2','activity_completed','Understanding the Importance of Cooperatives','Computer System Servicing','2026-08-24 01:55:16');
INSERT INTO `tbl_recent_activity` VALUES ('13','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-24 02:02:48');
INSERT INTO `tbl_recent_activity` VALUES ('14','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-24 02:02:53');
INSERT INTO `tbl_recent_activity` VALUES ('15','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-24 02:02:56');
INSERT INTO `tbl_recent_activity` VALUES ('16','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-08-24 02:06:54');
INSERT INTO `tbl_recent_activity` VALUES ('17','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-24 02:10:24');
INSERT INTO `tbl_recent_activity` VALUES ('18','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-24 03:02:46');
INSERT INTO `tbl_recent_activity` VALUES ('19','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-24 03:10:33');
INSERT INTO `tbl_recent_activity` VALUES ('20','2','module_opened','Module 18: Understanding Culture Society and Politics','Computer System Servicing','2026-08-24 04:45:28');
INSERT INTO `tbl_recent_activity` VALUES ('21','2','lesson_opened','Lesson 1: Understanding the Computer system servicing','Computer System Servicing','2026-08-24 04:45:28');
INSERT INTO `tbl_recent_activity` VALUES ('22','2','lesson_opened','Lesson 1: qweqweqweqw','Computer System Servicing','2026-08-24 09:56:59');
INSERT INTO `tbl_recent_activity` VALUES ('23','2','module_opened','Module 17: Understanding Culture Society and Politics','Computer System Servicing','2026-08-24 11:57:08');
INSERT INTO `tbl_recent_activity` VALUES ('24','2','lesson_opened','Lesson 1: Understanding the Computer system servicing','Computer System Servicing','2026-08-24 11:57:08');
INSERT INTO `tbl_recent_activity` VALUES ('25','2','lesson_opened','Lesson 1: asdasdasdqweqwe','Computer System Servicing','2026-08-24 12:01:50');
INSERT INTO `tbl_recent_activity` VALUES ('26','2','lesson_opened','Lesson 1: qweqweqewadsad','Computer System Servicing','2026-08-24 12:02:01');
INSERT INTO `tbl_recent_activity` VALUES ('27','2','lesson_opened','Lesson 3: qweqweasd','Computer System Servicing','2026-08-24 12:02:11');
INSERT INTO `tbl_recent_activity` VALUES ('28','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-24 12:02:18');
INSERT INTO `tbl_recent_activity` VALUES ('29','2','lesson_opened','Lesson 1: asdasdasd','Computer System Servicing','2026-08-24 12:03:54');
INSERT INTO `tbl_recent_activity` VALUES ('30','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-24 12:04:13');
INSERT INTO `tbl_recent_activity` VALUES ('31','2','lesson_opened','Lesson 9: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 12:04:21');
INSERT INTO `tbl_recent_activity` VALUES ('32','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-24 12:07:40');
INSERT INTO `tbl_recent_activity` VALUES ('33','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 12:08:09');
INSERT INTO `tbl_recent_activity` VALUES ('34','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-24 12:08:18');
INSERT INTO `tbl_recent_activity` VALUES ('35','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-24 13:48:33');
INSERT INTO `tbl_recent_activity` VALUES ('36','2','lesson_opened','Lesson 1: Understanding the Computer system servicing','Computer System Servicing','2026-08-24 13:48:48');
INSERT INTO `tbl_recent_activity` VALUES ('37','2','lesson_opened','Lesson 1: Understanding the Computer system servicing','Computer System Servicing','2026-08-24 13:54:43');
INSERT INTO `tbl_recent_activity` VALUES ('38','2','lesson_opened','Lesson 1: Understanding Culture Society and Politics','Computer System Servicing','2026-08-24 13:54:55');
INSERT INTO `tbl_recent_activity` VALUES ('39','2','lesson_opened','Lesson 1: Understanding Culture Society and Politics','Computer System Servicing','2026-08-24 14:41:07');
INSERT INTO `tbl_recent_activity` VALUES ('40','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-24 14:42:11');
INSERT INTO `tbl_recent_activity` VALUES ('41','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 14:43:52');
INSERT INTO `tbl_recent_activity` VALUES ('42','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-24 14:46:29');
INSERT INTO `tbl_recent_activity` VALUES ('43','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-24 14:46:36');
INSERT INTO `tbl_recent_activity` VALUES ('44','2','lesson_opened','Lesson 9: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 14:47:28');
INSERT INTO `tbl_recent_activity` VALUES ('45','2','lesson_opened','Lesson 1: qweqweqewadsad','Computer System Servicing','2026-08-24 14:48:18');
INSERT INTO `tbl_recent_activity` VALUES ('46','2','lesson_opened','Lesson 3: qweqweasd','Computer System Servicing','2026-08-24 14:48:24');
INSERT INTO `tbl_recent_activity` VALUES ('47','2','lesson_opened','Lesson 1: asdasdasd','Computer System Servicing','2026-08-24 14:48:33');
INSERT INTO `tbl_recent_activity` VALUES ('48','2','lesson_opened','Lesson 1: Understanding Culture Society and Politics','Computer System Servicing','2026-08-24 14:48:54');
INSERT INTO `tbl_recent_activity` VALUES ('49','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-24 15:11:47');
INSERT INTO `tbl_recent_activity` VALUES ('50','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-24 15:11:54');
INSERT INTO `tbl_recent_activity` VALUES ('51','2','lesson_opened','Lesson 1: asdasdasd','Computer System Servicing','2026-08-24 15:15:49');
INSERT INTO `tbl_recent_activity` VALUES ('52','2','module_opened','Module 8: qweqwe','Computer System Servicing','2026-08-24 20:02:56');
INSERT INTO `tbl_recent_activity` VALUES ('53','2','lesson_opened','Lesson 1: qweqweqweqw','Computer System Servicing','2026-08-24 20:02:56');
INSERT INTO `tbl_recent_activity` VALUES ('54','2','module_opened','Module 1: Computer System Servicing','Computer System Servicing','2026-08-24 21:30:41');
INSERT INTO `tbl_recent_activity` VALUES ('55','2','lesson_opened','Lesson 1: qweqweqewadsad','Computer System Servicing','2026-08-24 21:30:41');
INSERT INTO `tbl_recent_activity` VALUES ('56','2','lesson_opened','Lesson 1: qweqweqewadsad','Computer System Servicing','2026-08-24 21:59:58');
INSERT INTO `tbl_recent_activity` VALUES ('57','2','module_opened','Module 12: Introduction to Cooperatives','Computer System Servicing','2026-08-25 05:41:24');
INSERT INTO `tbl_recent_activity` VALUES ('58','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-25 05:41:24');
INSERT INTO `tbl_recent_activity` VALUES ('59','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-25 05:41:35');
INSERT INTO `tbl_recent_activity` VALUES ('60','2','module_opened','Module 10: Introduction to Cooperatives','Computer System Servicing','2026-08-25 06:07:29');
INSERT INTO `tbl_recent_activity` VALUES ('61','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-25 06:07:29');
INSERT INTO `tbl_recent_activity` VALUES ('62','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-25 06:07:36');
INSERT INTO `tbl_recent_activity` VALUES ('63','2','module_opened','Module 11: Introduction to Cooperatives','Computer System Servicing','2026-08-25 06:11:03');
INSERT INTO `tbl_recent_activity` VALUES ('64','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-25 06:11:11');
INSERT INTO `tbl_recent_activity` VALUES ('65','2','module_opened','Module 10: Introduction to Cooperatives','Computer System Servicing','2026-08-25 15:26:11');
INSERT INTO `tbl_recent_activity` VALUES ('66','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-25 15:26:11');
INSERT INTO `tbl_recent_activity` VALUES ('67','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-25 15:59:03');
INSERT INTO `tbl_recent_activity` VALUES ('68','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-25 19:12:17');
INSERT INTO `tbl_recent_activity` VALUES ('69','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-25 19:12:18');
INSERT INTO `tbl_recent_activity` VALUES ('70','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-25 19:12:33');
INSERT INTO `tbl_recent_activity` VALUES ('71','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-25 19:12:48');
INSERT INTO `tbl_recent_activity` VALUES ('72','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-25 19:13:08');
INSERT INTO `tbl_recent_activity` VALUES ('73','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-25 21:58:31');
INSERT INTO `tbl_recent_activity` VALUES ('74','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-25 21:58:31');
INSERT INTO `tbl_recent_activity` VALUES ('75','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-25 22:06:33');
INSERT INTO `tbl_recent_activity` VALUES ('76','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-25 22:07:49');
INSERT INTO `tbl_recent_activity` VALUES ('77','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-25 22:07:59');
INSERT INTO `tbl_recent_activity` VALUES ('78','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-25 22:10:52');
INSERT INTO `tbl_recent_activity` VALUES ('79','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-25 22:14:34');
INSERT INTO `tbl_recent_activity` VALUES ('80','2','quiz_completed','Quiz','Computer System Servicing','2026-08-25 10:49:53');
INSERT INTO `tbl_recent_activity` VALUES ('81','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-26 08:23:18');
INSERT INTO `tbl_recent_activity` VALUES ('82','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-26 08:23:18');
INSERT INTO `tbl_recent_activity` VALUES ('83','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 08:23:23');
INSERT INTO `tbl_recent_activity` VALUES ('84','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 09:25:57');
INSERT INTO `tbl_recent_activity` VALUES ('85','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 09:33:10');
INSERT INTO `tbl_recent_activity` VALUES ('86','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 09:39:30');
INSERT INTO `tbl_recent_activity` VALUES ('87','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 09:51:07');
INSERT INTO `tbl_recent_activity` VALUES ('88','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 10:00:57');
INSERT INTO `tbl_recent_activity` VALUES ('89','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 10:06:21');
INSERT INTO `tbl_recent_activity` VALUES ('90','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 10:26:23');
INSERT INTO `tbl_recent_activity` VALUES ('91','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-26 10:26:45');
INSERT INTO `tbl_recent_activity` VALUES ('92','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 10:26:45');
INSERT INTO `tbl_recent_activity` VALUES ('93','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:06:32');
INSERT INTO `tbl_recent_activity` VALUES ('94','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:11:40');
INSERT INTO `tbl_recent_activity` VALUES ('95','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:16:52');
INSERT INTO `tbl_recent_activity` VALUES ('96','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:23:10');
INSERT INTO `tbl_recent_activity` VALUES ('97','2','quiz_completed','Quiz','Computer System Servicing','2026-08-26 11:27:32');
INSERT INTO `tbl_recent_activity` VALUES ('98','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:27:32');
INSERT INTO `tbl_recent_activity` VALUES ('99','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:43:30');
INSERT INTO `tbl_recent_activity` VALUES ('100','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:43:32');
INSERT INTO `tbl_recent_activity` VALUES ('101','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:49:48');
INSERT INTO `tbl_recent_activity` VALUES ('102','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:51:11');
INSERT INTO `tbl_recent_activity` VALUES ('103','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 11:54:54');
INSERT INTO `tbl_recent_activity` VALUES ('104','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-26 19:39:32');
INSERT INTO `tbl_recent_activity` VALUES ('105','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 19:39:32');
INSERT INTO `tbl_recent_activity` VALUES ('106','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 19:39:59');
INSERT INTO `tbl_recent_activity` VALUES ('107','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 19:46:18');
INSERT INTO `tbl_recent_activity` VALUES ('108','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 19:59:23');
INSERT INTO `tbl_recent_activity` VALUES ('109','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 20:07:29');
INSERT INTO `tbl_recent_activity` VALUES ('110','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-26 20:32:53');
INSERT INTO `tbl_recent_activity` VALUES ('111','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-27 02:12:34');
INSERT INTO `tbl_recent_activity` VALUES ('112','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:12:34');
INSERT INTO `tbl_recent_activity` VALUES ('113','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:16:30');
INSERT INTO `tbl_recent_activity` VALUES ('114','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:17:39');
INSERT INTO `tbl_recent_activity` VALUES ('115','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:23:31');
INSERT INTO `tbl_recent_activity` VALUES ('116','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:28:40');
INSERT INTO `tbl_recent_activity` VALUES ('117','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:28:51');
INSERT INTO `tbl_recent_activity` VALUES ('118','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:34:06');
INSERT INTO `tbl_recent_activity` VALUES ('119','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:34:21');
INSERT INTO `tbl_recent_activity` VALUES ('120','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:39:28');
INSERT INTO `tbl_recent_activity` VALUES ('121','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:42:44');
INSERT INTO `tbl_recent_activity` VALUES ('122','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:47:22');
INSERT INTO `tbl_recent_activity` VALUES ('123','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 02:47:59');
INSERT INTO `tbl_recent_activity` VALUES ('124','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:18:20');
INSERT INTO `tbl_recent_activity` VALUES ('125','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:18:33');
INSERT INTO `tbl_recent_activity` VALUES ('126','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:32:48');
INSERT INTO `tbl_recent_activity` VALUES ('127','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:33:29');
INSERT INTO `tbl_recent_activity` VALUES ('128','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-27 03:35:56');
INSERT INTO `tbl_recent_activity` VALUES ('129','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:40:16');
INSERT INTO `tbl_recent_activity` VALUES ('130','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:43:59');
INSERT INTO `tbl_recent_activity` VALUES ('131','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:47:26');
INSERT INTO `tbl_recent_activity` VALUES ('132','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:52:33');
INSERT INTO `tbl_recent_activity` VALUES ('133','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 03:56:23');
INSERT INTO `tbl_recent_activity` VALUES ('134','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 04:00:46');
INSERT INTO `tbl_recent_activity` VALUES ('135','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 04:27:39');
INSERT INTO `tbl_recent_activity` VALUES ('136','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 04:40:49');
INSERT INTO `tbl_recent_activity` VALUES ('137','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 04:40:51');
INSERT INTO `tbl_recent_activity` VALUES ('138','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 04:53:26');
INSERT INTO `tbl_recent_activity` VALUES ('139','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 05:01:10');
INSERT INTO `tbl_recent_activity` VALUES ('140','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 05:01:11');
INSERT INTO `tbl_recent_activity` VALUES ('141','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 05:10:38');
INSERT INTO `tbl_recent_activity` VALUES ('142','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 05:13:21');
INSERT INTO `tbl_recent_activity` VALUES ('143','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 05:20:45');
INSERT INTO `tbl_recent_activity` VALUES ('144','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 05:30:13');
INSERT INTO `tbl_recent_activity` VALUES ('145','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 05:35:21');
INSERT INTO `tbl_recent_activity` VALUES ('146','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:10:10');
INSERT INTO `tbl_recent_activity` VALUES ('147','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:16:07');
INSERT INTO `tbl_recent_activity` VALUES ('148','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:22:35');
INSERT INTO `tbl_recent_activity` VALUES ('149','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:27:57');
INSERT INTO `tbl_recent_activity` VALUES ('150','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:31:04');
INSERT INTO `tbl_recent_activity` VALUES ('151','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:33:13');
INSERT INTO `tbl_recent_activity` VALUES ('152','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:38:28');
INSERT INTO `tbl_recent_activity` VALUES ('153','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-27 06:42:53');
INSERT INTO `tbl_recent_activity` VALUES ('154','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-27 06:42:53');
INSERT INTO `tbl_recent_activity` VALUES ('155','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-27 06:42:58');
INSERT INTO `tbl_recent_activity` VALUES ('156','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 06:43:05');
INSERT INTO `tbl_recent_activity` VALUES ('157','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:44:26');
INSERT INTO `tbl_recent_activity` VALUES ('158','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:51:29');
INSERT INTO `tbl_recent_activity` VALUES ('159','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 06:59:36');
INSERT INTO `tbl_recent_activity` VALUES ('160','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-27 06:59:43');
INSERT INTO `tbl_recent_activity` VALUES ('161','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 06:59:49');
INSERT INTO `tbl_recent_activity` VALUES ('162','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 07:02:18');
INSERT INTO `tbl_recent_activity` VALUES ('163','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 07:09:58');
INSERT INTO `tbl_recent_activity` VALUES ('164','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 07:14:28');
INSERT INTO `tbl_recent_activity` VALUES ('165','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-27 09:03:32');
INSERT INTO `tbl_recent_activity` VALUES ('166','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-27 09:03:32');
INSERT INTO `tbl_recent_activity` VALUES ('167','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 09:03:37');
INSERT INTO `tbl_recent_activity` VALUES ('168','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-27 09:03:41');
INSERT INTO `tbl_recent_activity` VALUES ('169','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 09:03:44');
INSERT INTO `tbl_recent_activity` VALUES ('170','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 10:22:24');
INSERT INTO `tbl_recent_activity` VALUES ('171','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-27 12:00:20');
INSERT INTO `tbl_recent_activity` VALUES ('172','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-27 12:00:20');
INSERT INTO `tbl_recent_activity` VALUES ('173','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 12:00:26');
INSERT INTO `tbl_recent_activity` VALUES ('174','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 12:19:31');
INSERT INTO `tbl_recent_activity` VALUES ('175','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 12:19:39');
INSERT INTO `tbl_recent_activity` VALUES ('176','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-27 20:06:05');
INSERT INTO `tbl_recent_activity` VALUES ('177','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-27 20:06:05');
INSERT INTO `tbl_recent_activity` VALUES ('178','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 20:06:11');
INSERT INTO `tbl_recent_activity` VALUES ('179','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 20:07:55');
INSERT INTO `tbl_recent_activity` VALUES ('180','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 20:11:23');
INSERT INTO `tbl_recent_activity` VALUES ('181','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 20:17:59');
INSERT INTO `tbl_recent_activity` VALUES ('182','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 20:18:14');
INSERT INTO `tbl_recent_activity` VALUES ('183','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 20:24:00');
INSERT INTO `tbl_recent_activity` VALUES ('184','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 20:35:37');
INSERT INTO `tbl_recent_activity` VALUES ('185','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-27 20:36:41');
INSERT INTO `tbl_recent_activity` VALUES ('186','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 20:38:44');
INSERT INTO `tbl_recent_activity` VALUES ('187','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 20:44:40');
INSERT INTO `tbl_recent_activity` VALUES ('188','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-27 20:44:49');
INSERT INTO `tbl_recent_activity` VALUES ('189','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-27 20:44:56');
INSERT INTO `tbl_recent_activity` VALUES ('190','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 20:44:56');
INSERT INTO `tbl_recent_activity` VALUES ('191','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 20:50:05');
INSERT INTO `tbl_recent_activity` VALUES ('192','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 20:51:46');
INSERT INTO `tbl_recent_activity` VALUES ('193','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 20:55:24');
INSERT INTO `tbl_recent_activity` VALUES ('194','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 21:03:22');
INSERT INTO `tbl_recent_activity` VALUES ('195','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-27 21:04:51');
INSERT INTO `tbl_recent_activity` VALUES ('196','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-27 21:04:51');
INSERT INTO `tbl_recent_activity` VALUES ('197','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 21:04:58');
INSERT INTO `tbl_recent_activity` VALUES ('198','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 22:44:12');
INSERT INTO `tbl_recent_activity` VALUES ('199','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-27 22:55:46');
INSERT INTO `tbl_recent_activity` VALUES ('200','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-27 22:55:47');
INSERT INTO `tbl_recent_activity` VALUES ('201','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-27 22:58:58');
INSERT INTO `tbl_recent_activity` VALUES ('202','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-28 00:20:09');
INSERT INTO `tbl_recent_activity` VALUES ('203','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-28 00:20:09');
INSERT INTO `tbl_recent_activity` VALUES ('204','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-28 00:20:15');
INSERT INTO `tbl_recent_activity` VALUES ('205','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-28 00:21:57');
INSERT INTO `tbl_recent_activity` VALUES ('206','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 00:21:59');
INSERT INTO `tbl_recent_activity` VALUES ('207','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-28 00:22:10');
INSERT INTO `tbl_recent_activity` VALUES ('208','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 00:22:10');
INSERT INTO `tbl_recent_activity` VALUES ('209','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-28 08:02:19');
INSERT INTO `tbl_recent_activity` VALUES ('210','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-28 08:02:19');
INSERT INTO `tbl_recent_activity` VALUES ('211','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-28 08:02:31');
INSERT INTO `tbl_recent_activity` VALUES ('212','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-28 08:05:05');
INSERT INTO `tbl_recent_activity` VALUES ('213','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 08:05:07');
INSERT INTO `tbl_recent_activity` VALUES ('214','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-28 08:05:38');
INSERT INTO `tbl_recent_activity` VALUES ('215','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 08:05:38');
INSERT INTO `tbl_recent_activity` VALUES ('216','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 08:14:24');
INSERT INTO `tbl_recent_activity` VALUES ('217','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 08:20:27');
INSERT INTO `tbl_recent_activity` VALUES ('218','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 08:32:19');
INSERT INTO `tbl_recent_activity` VALUES ('219','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 08:42:41');
INSERT INTO `tbl_recent_activity` VALUES ('220','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 08:51:50');
INSERT INTO `tbl_recent_activity` VALUES ('221','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-28 17:32:11');
INSERT INTO `tbl_recent_activity` VALUES ('222','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 17:32:12');
INSERT INTO `tbl_recent_activity` VALUES ('223','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 17:32:24');
INSERT INTO `tbl_recent_activity` VALUES ('224','2','module_opened','Module 9: qweqwe','Computer System Servicing','2026-08-28 17:59:30');
INSERT INTO `tbl_recent_activity` VALUES ('225','2','lesson_opened','Lesson 1: asdasdasd','Computer System Servicing','2026-08-28 17:59:30');
INSERT INTO `tbl_recent_activity` VALUES ('226','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 17:59:45');
INSERT INTO `tbl_recent_activity` VALUES ('227','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 17:59:49');
INSERT INTO `tbl_recent_activity` VALUES ('228','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-28 19:36:36');
INSERT INTO `tbl_recent_activity` VALUES ('229','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 19:36:36');
INSERT INTO `tbl_recent_activity` VALUES ('230','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 19:36:41');
INSERT INTO `tbl_recent_activity` VALUES ('231','2','module_opened','Module 10: Introduction to Cooperatives','Computer System Servicing','2026-08-28 19:53:39');
INSERT INTO `tbl_recent_activity` VALUES ('232','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-28 19:53:39');
INSERT INTO `tbl_recent_activity` VALUES ('233','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-28 23:25:01');
INSERT INTO `tbl_recent_activity` VALUES ('234','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 23:25:01');
INSERT INTO `tbl_recent_activity` VALUES ('235','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 23:25:07');
INSERT INTO `tbl_recent_activity` VALUES ('236','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 23:43:57');
INSERT INTO `tbl_recent_activity` VALUES ('237','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-28 23:58:37');
INSERT INTO `tbl_recent_activity` VALUES ('238','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 23:58:37');
INSERT INTO `tbl_recent_activity` VALUES ('239','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-28 23:58:42');
INSERT INTO `tbl_recent_activity` VALUES ('240','2','module_opened','Module 24: asd','Computer System Servicing','2026-08-29 20:29:36');
INSERT INTO `tbl_recent_activity` VALUES ('241','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-29 20:29:36');
INSERT INTO `tbl_recent_activity` VALUES ('242','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-29 20:36:24');
INSERT INTO `tbl_recent_activity` VALUES ('243','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-29 20:36:31');
INSERT INTO `tbl_recent_activity` VALUES ('244','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-29 20:36:31');
INSERT INTO `tbl_recent_activity` VALUES ('245','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-29 20:36:35');
INSERT INTO `tbl_recent_activity` VALUES ('246','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-29 21:03:51');
INSERT INTO `tbl_recent_activity` VALUES ('247','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-29 21:13:18');
INSERT INTO `tbl_recent_activity` VALUES ('248','2','module_opened','Module 23: asdasdasd','Computer System Servicing','2026-08-29 21:13:37');
INSERT INTO `tbl_recent_activity` VALUES ('249','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-29 21:13:38');
INSERT INTO `tbl_recent_activity` VALUES ('250','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-29 21:19:07');
INSERT INTO `tbl_recent_activity` VALUES ('251','2','module_opened','Module 24: asd','Computer System Servicing','2026-08-29 21:31:48');
INSERT INTO `tbl_recent_activity` VALUES ('252','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-29 21:31:48');
INSERT INTO `tbl_recent_activity` VALUES ('253','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-29 21:38:06');
INSERT INTO `tbl_recent_activity` VALUES ('254','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-29 21:43:17');
INSERT INTO `tbl_recent_activity` VALUES ('255','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-29 21:48:42');
INSERT INTO `tbl_recent_activity` VALUES ('256','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-29 21:48:42');
INSERT INTO `tbl_recent_activity` VALUES ('257','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-29 21:48:46');
INSERT INTO `tbl_recent_activity` VALUES ('258','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-29 21:54:47');
INSERT INTO `tbl_recent_activity` VALUES ('259','2','module_opened','Module 23: asdasdasd','Computer System Servicing','2026-08-29 21:58:48');
INSERT INTO `tbl_recent_activity` VALUES ('260','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-29 21:58:48');
INSERT INTO `tbl_recent_activity` VALUES ('261','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-29 22:11:05');
INSERT INTO `tbl_recent_activity` VALUES ('262','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-29 22:24:53');
INSERT INTO `tbl_recent_activity` VALUES ('263','2','module_opened','Module 24: asd','Computer System Servicing','2026-08-30 06:35:06');
INSERT INTO `tbl_recent_activity` VALUES ('264','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-30 06:35:06');
INSERT INTO `tbl_recent_activity` VALUES ('265','2','module_opened','Module 24: asd','Computer System Servicing','2026-08-30 07:10:58');
INSERT INTO `tbl_recent_activity` VALUES ('266','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-30 07:10:58');
INSERT INTO `tbl_recent_activity` VALUES ('267','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-30 07:48:27');
INSERT INTO `tbl_recent_activity` VALUES ('268','2','module_opened','Module 24: asd','Computer System Servicing','2026-08-30 08:10:10');
INSERT INTO `tbl_recent_activity` VALUES ('269','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-30 08:10:10');
INSERT INTO `tbl_recent_activity` VALUES ('270','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-30 08:17:10');
INSERT INTO `tbl_recent_activity` VALUES ('271','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-30 08:17:34');
INSERT INTO `tbl_recent_activity` VALUES ('272','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-30 08:17:34');
INSERT INTO `tbl_recent_activity` VALUES ('273','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-30 08:17:39');
INSERT INTO `tbl_recent_activity` VALUES ('274','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-08-30 08:17:46');
INSERT INTO `tbl_recent_activity` VALUES ('275','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 08:17:48');
INSERT INTO `tbl_recent_activity` VALUES ('276','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-30 08:18:02');
INSERT INTO `tbl_recent_activity` VALUES ('277','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 08:18:02');
INSERT INTO `tbl_recent_activity` VALUES ('278','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 08:59:04');
INSERT INTO `tbl_recent_activity` VALUES ('279','2','module_opened','Module 24: asd','Computer System Servicing','2026-08-30 08:59:14');
INSERT INTO `tbl_recent_activity` VALUES ('280','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-30 08:59:14');
INSERT INTO `tbl_recent_activity` VALUES ('281','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 09:10:29');
INSERT INTO `tbl_recent_activity` VALUES ('282','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-30 09:10:30');
INSERT INTO `tbl_recent_activity` VALUES ('283','2','module_opened','Module 23: asdasdasd','Computer System Servicing','2026-08-30 09:16:22');
INSERT INTO `tbl_recent_activity` VALUES ('284','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-30 09:16:23');
INSERT INTO `tbl_recent_activity` VALUES ('285','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-30 09:27:37');
INSERT INTO `tbl_recent_activity` VALUES ('286','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-30 09:33:01');
INSERT INTO `tbl_recent_activity` VALUES ('287','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-30 10:24:05');
INSERT INTO `tbl_recent_activity` VALUES ('288','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 10:24:59');
INSERT INTO `tbl_recent_activity` VALUES ('289','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-30 10:30:27');
INSERT INTO `tbl_recent_activity` VALUES ('290','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 10:31:41');
INSERT INTO `tbl_recent_activity` VALUES ('291','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-30 10:37:55');
INSERT INTO `tbl_recent_activity` VALUES ('292','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 10:41:33');
INSERT INTO `tbl_recent_activity` VALUES ('293','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-30 10:43:10');
INSERT INTO `tbl_recent_activity` VALUES ('294','2','lesson_opened','Lesson 1: qweqweqwe','Computer System Servicing','2026-08-30 10:52:56');
INSERT INTO `tbl_recent_activity` VALUES ('295','2','module_opened','Module 27: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-30 10:53:05');
INSERT INTO `tbl_recent_activity` VALUES ('296','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 10:53:05');
INSERT INTO `tbl_recent_activity` VALUES ('297','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 10:58:51');
INSERT INTO `tbl_recent_activity` VALUES ('298','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 11:07:12');
INSERT INTO `tbl_recent_activity` VALUES ('299','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 11:07:21');
INSERT INTO `tbl_recent_activity` VALUES ('300','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-30 11:08:15');
INSERT INTO `tbl_recent_activity` VALUES ('301','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-30 11:08:15');
INSERT INTO `tbl_recent_activity` VALUES ('302','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-30 11:08:20');
INSERT INTO `tbl_recent_activity` VALUES ('303','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-30 11:14:27');
INSERT INTO `tbl_recent_activity` VALUES ('304','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 11:14:29');
INSERT INTO `tbl_recent_activity` VALUES ('305','2','module_opened','Module 28: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-30 11:14:38');
INSERT INTO `tbl_recent_activity` VALUES ('306','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 13:22:38');
INSERT INTO `tbl_recent_activity` VALUES ('307','2','module_opened','Module 29: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-30 13:27:16');
INSERT INTO `tbl_recent_activity` VALUES ('308','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 13:28:25');
INSERT INTO `tbl_recent_activity` VALUES ('309','2','module_opened','Module 28: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-30 13:29:17');
INSERT INTO `tbl_recent_activity` VALUES ('310','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 13:34:21');
INSERT INTO `tbl_recent_activity` VALUES ('311','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-08-30 13:34:28');
INSERT INTO `tbl_recent_activity` VALUES ('312','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-30 13:34:28');
INSERT INTO `tbl_recent_activity` VALUES ('313','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-30 13:42:06');
INSERT INTO `tbl_recent_activity` VALUES ('314','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-08-30 13:42:11');
INSERT INTO `tbl_recent_activity` VALUES ('315','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 13:42:11');
INSERT INTO `tbl_recent_activity` VALUES ('316','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 13:52:09');
INSERT INTO `tbl_recent_activity` VALUES ('317','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-08-30 19:44:25');
INSERT INTO `tbl_recent_activity` VALUES ('318','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-30 19:44:25');
INSERT INTO `tbl_recent_activity` VALUES ('319','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-08-30 19:49:24');
INSERT INTO `tbl_recent_activity` VALUES ('320','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 19:49:24');
INSERT INTO `tbl_recent_activity` VALUES ('321','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 19:59:36');
INSERT INTO `tbl_recent_activity` VALUES ('322','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-30 19:59:44');
INSERT INTO `tbl_recent_activity` VALUES ('323','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-30 20:00:38');
INSERT INTO `tbl_recent_activity` VALUES ('324','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 20:00:38');
INSERT INTO `tbl_recent_activity` VALUES ('325','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 20:00:43');
INSERT INTO `tbl_recent_activity` VALUES ('326','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-30 21:12:16');
INSERT INTO `tbl_recent_activity` VALUES ('327','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 21:13:23');
INSERT INTO `tbl_recent_activity` VALUES ('328','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 21:18:40');
INSERT INTO `tbl_recent_activity` VALUES ('329','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 21:25:08');
INSERT INTO `tbl_recent_activity` VALUES ('330','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 21:32:33');
INSERT INTO `tbl_recent_activity` VALUES ('331','2','module_opened','Module 28: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-30 21:32:46');
INSERT INTO `tbl_recent_activity` VALUES ('332','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 21:32:46');
INSERT INTO `tbl_recent_activity` VALUES ('333','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 22:16:51');
INSERT INTO `tbl_recent_activity` VALUES ('334','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 22:30:37');
INSERT INTO `tbl_recent_activity` VALUES ('335','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-30 22:31:11');
INSERT INTO `tbl_recent_activity` VALUES ('336','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 22:31:13');
INSERT INTO `tbl_recent_activity` VALUES ('337','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-30 22:31:37');
INSERT INTO `tbl_recent_activity` VALUES ('338','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 22:31:37');
INSERT INTO `tbl_recent_activity` VALUES ('339','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 22:31:42');
INSERT INTO `tbl_recent_activity` VALUES ('340','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 22:41:41');
INSERT INTO `tbl_recent_activity` VALUES ('341','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-30 22:43:49');
INSERT INTO `tbl_recent_activity` VALUES ('342','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-30 22:43:50');
INSERT INTO `tbl_recent_activity` VALUES ('343','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-30 22:47:26');
INSERT INTO `tbl_recent_activity` VALUES ('344','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-08-31 14:40:25');
INSERT INTO `tbl_recent_activity` VALUES ('345','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 14:40:25');
INSERT INTO `tbl_recent_activity` VALUES ('346','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-31 14:41:33');
INSERT INTO `tbl_recent_activity` VALUES ('347','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 14:41:33');
INSERT INTO `tbl_recent_activity` VALUES ('348','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 14:41:51');
INSERT INTO `tbl_recent_activity` VALUES ('349','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 14:46:57');
INSERT INTO `tbl_recent_activity` VALUES ('350','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 14:52:00');
INSERT INTO `tbl_recent_activity` VALUES ('351','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:00:47');
INSERT INTO `tbl_recent_activity` VALUES ('352','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:01:13');
INSERT INTO `tbl_recent_activity` VALUES ('353','2','module_opened','Module 27: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-31 15:01:38');
INSERT INTO `tbl_recent_activity` VALUES ('354','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 15:01:38');
INSERT INTO `tbl_recent_activity` VALUES ('355','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 15:07:41');
INSERT INTO `tbl_recent_activity` VALUES ('356','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:12:15');
INSERT INTO `tbl_recent_activity` VALUES ('357','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 15:13:09');
INSERT INTO `tbl_recent_activity` VALUES ('358','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:17:21');
INSERT INTO `tbl_recent_activity` VALUES ('359','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 15:18:31');
INSERT INTO `tbl_recent_activity` VALUES ('360','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 15:27:44');
INSERT INTO `tbl_recent_activity` VALUES ('361','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:30:04');
INSERT INTO `tbl_recent_activity` VALUES ('362','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-08-31 15:31:55');
INSERT INTO `tbl_recent_activity` VALUES ('363','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 15:31:55');
INSERT INTO `tbl_recent_activity` VALUES ('364','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:36:24');
INSERT INTO `tbl_recent_activity` VALUES ('365','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 15:39:42');
INSERT INTO `tbl_recent_activity` VALUES ('366','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:42:07');
INSERT INTO `tbl_recent_activity` VALUES ('367','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:49:17');
INSERT INTO `tbl_recent_activity` VALUES ('368','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 15:53:25');
INSERT INTO `tbl_recent_activity` VALUES ('369','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 15:54:19');
INSERT INTO `tbl_recent_activity` VALUES ('370','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 16:05:45');
INSERT INTO `tbl_recent_activity` VALUES ('371','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 16:13:36');
INSERT INTO `tbl_recent_activity` VALUES ('372','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 16:30:17');
INSERT INTO `tbl_recent_activity` VALUES ('373','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 16:37:35');
INSERT INTO `tbl_recent_activity` VALUES ('374','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 16:53:20');
INSERT INTO `tbl_recent_activity` VALUES ('375','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 16:55:06');
INSERT INTO `tbl_recent_activity` VALUES ('376','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 16:58:26');
INSERT INTO `tbl_recent_activity` VALUES ('377','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 17:03:44');
INSERT INTO `tbl_recent_activity` VALUES ('378','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 17:07:56');
INSERT INTO `tbl_recent_activity` VALUES ('379','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 17:10:11');
INSERT INTO `tbl_recent_activity` VALUES ('380','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 17:17:29');
INSERT INTO `tbl_recent_activity` VALUES ('381','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 17:18:02');
INSERT INTO `tbl_recent_activity` VALUES ('382','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 17:28:03');
INSERT INTO `tbl_recent_activity` VALUES ('383','2','','jkjk',NULL,'2026-08-31 17:28:28');
INSERT INTO `tbl_recent_activity` VALUES ('384','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 17:29:53');
INSERT INTO `tbl_recent_activity` VALUES ('385','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 17:31:33');
INSERT INTO `tbl_recent_activity` VALUES ('386','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-08-31 17:37:18');
INSERT INTO `tbl_recent_activity` VALUES ('387','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-31 17:37:19');
INSERT INTO `tbl_recent_activity` VALUES ('388','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 17:41:14');
INSERT INTO `tbl_recent_activity` VALUES ('389','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-08-31 17:41:33');
INSERT INTO `tbl_recent_activity` VALUES ('390','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 17:41:33');
INSERT INTO `tbl_recent_activity` VALUES ('391','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-08-31 17:42:20');
INSERT INTO `tbl_recent_activity` VALUES ('392','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 17:47:32');
INSERT INTO `tbl_recent_activity` VALUES ('393','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 17:47:34');
INSERT INTO `tbl_recent_activity` VALUES ('394','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 17:55:07');
INSERT INTO `tbl_recent_activity` VALUES ('395','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 17:56:26');
INSERT INTO `tbl_recent_activity` VALUES ('396','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 18:09:56');
INSERT INTO `tbl_recent_activity` VALUES ('397','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 18:15:39');
INSERT INTO `tbl_recent_activity` VALUES ('398','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 18:31:47');
INSERT INTO `tbl_recent_activity` VALUES ('399','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 18:38:41');
INSERT INTO `tbl_recent_activity` VALUES ('400','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 18:39:10');
INSERT INTO `tbl_recent_activity` VALUES ('401','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 18:39:19');
INSERT INTO `tbl_recent_activity` VALUES ('402','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 18:46:34');
INSERT INTO `tbl_recent_activity` VALUES ('403','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 18:46:58');
INSERT INTO `tbl_recent_activity` VALUES ('404','2','module_opened','Module 29: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-31 18:47:12');
INSERT INTO `tbl_recent_activity` VALUES ('405','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 18:47:12');
INSERT INTO `tbl_recent_activity` VALUES ('406','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 18:54:29');
INSERT INTO `tbl_recent_activity` VALUES ('407','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 18:55:41');
INSERT INTO `tbl_recent_activity` VALUES ('408','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 18:56:18');
INSERT INTO `tbl_recent_activity` VALUES ('409','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 19:01:11');
INSERT INTO `tbl_recent_activity` VALUES ('410','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:01:12');
INSERT INTO `tbl_recent_activity` VALUES ('411','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 19:01:40');
INSERT INTO `tbl_recent_activity` VALUES ('412','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 19:06:21');
INSERT INTO `tbl_recent_activity` VALUES ('413','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:06:35');
INSERT INTO `tbl_recent_activity` VALUES ('414','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 19:06:44');
INSERT INTO `tbl_recent_activity` VALUES ('415','2','module_opened','Module 28: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-31 19:09:01');
INSERT INTO `tbl_recent_activity` VALUES ('416','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:12:13');
INSERT INTO `tbl_recent_activity` VALUES ('417','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 19:19:24');
INSERT INTO `tbl_recent_activity` VALUES ('418','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 19:19:25');
INSERT INTO `tbl_recent_activity` VALUES ('419','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:19:26');
INSERT INTO `tbl_recent_activity` VALUES ('420','2','','Computer Hardware Components Matching',NULL,'2026-08-31 19:24:11');
INSERT INTO `tbl_recent_activity` VALUES ('421','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:25:11');
INSERT INTO `tbl_recent_activity` VALUES ('422','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 19:35:49');
INSERT INTO `tbl_recent_activity` VALUES ('423','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 19:35:49');
INSERT INTO `tbl_recent_activity` VALUES ('424','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:35:50');
INSERT INTO `tbl_recent_activity` VALUES ('425','2','','jkjk',NULL,'2026-08-31 19:37:50');
INSERT INTO `tbl_recent_activity` VALUES ('426','2','','Computer Hardware Components Matching',NULL,'2026-08-31 19:39:18');
INSERT INTO `tbl_recent_activity` VALUES ('427','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 19:44:00');
INSERT INTO `tbl_recent_activity` VALUES ('428','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 19:44:00');
INSERT INTO `tbl_recent_activity` VALUES ('429','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:44:14');
INSERT INTO `tbl_recent_activity` VALUES ('430','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:49:33');
INSERT INTO `tbl_recent_activity` VALUES ('431','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 19:51:05');
INSERT INTO `tbl_recent_activity` VALUES ('432','2','module_opened','Module 27: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-31 19:52:23');
INSERT INTO `tbl_recent_activity` VALUES ('433','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-08-31 19:53:13');
INSERT INTO `tbl_recent_activity` VALUES ('434','2','module_opened','Module 29: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-31 19:53:36');
INSERT INTO `tbl_recent_activity` VALUES ('435','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 19:54:44');
INSERT INTO `tbl_recent_activity` VALUES ('436','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-08-31 19:54:50');
INSERT INTO `tbl_recent_activity` VALUES ('437','2','module_opened','Module 16: xcxcxcx','Computer System Servicing','2026-08-31 19:56:36');
INSERT INTO `tbl_recent_activity` VALUES ('438','2','lesson_opened','Lesson 1: xcxcxcx','Computer System Servicing','2026-08-31 19:56:36');
INSERT INTO `tbl_recent_activity` VALUES ('439','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-08-31 19:56:52');
INSERT INTO `tbl_recent_activity` VALUES ('440','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-08-31 19:56:52');
INSERT INTO `tbl_recent_activity` VALUES ('441','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-31 19:56:58');
INSERT INTO `tbl_recent_activity` VALUES ('442','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 20:01:11');
INSERT INTO `tbl_recent_activity` VALUES ('443','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 20:06:14');
INSERT INTO `tbl_recent_activity` VALUES ('444','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-08-31 20:06:18');
INSERT INTO `tbl_recent_activity` VALUES ('445','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-08-31 21:10:27');
INSERT INTO `tbl_recent_activity` VALUES ('446','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-08-31 21:10:27');
INSERT INTO `tbl_recent_activity` VALUES ('447','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-08-31 21:12:02');
INSERT INTO `tbl_recent_activity` VALUES ('448','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-31 21:12:02');
INSERT INTO `tbl_recent_activity` VALUES ('449','2','module_opened','Module 24: asd','Computer System Servicing','2026-08-31 21:14:10');
INSERT INTO `tbl_recent_activity` VALUES ('450','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-31 21:14:10');
INSERT INTO `tbl_recent_activity` VALUES ('451','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-31 21:17:36');
INSERT INTO `tbl_recent_activity` VALUES ('452','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-08-31 21:20:11');
INSERT INTO `tbl_recent_activity` VALUES ('453','2','module_opened','Module 27: Introduction to Computer Hardware and Components','Computer System Servicing','2026-08-31 21:23:21');
INSERT INTO `tbl_recent_activity` VALUES ('454','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 21:23:21');
INSERT INTO `tbl_recent_activity` VALUES ('455','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-31 21:37:30');
INSERT INTO `tbl_recent_activity` VALUES ('456','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-08-31 21:37:31');
INSERT INTO `tbl_recent_activity` VALUES ('457','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-08-31 22:23:53');
INSERT INTO `tbl_recent_activity` VALUES ('458','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-01 01:03:55');
INSERT INTO `tbl_recent_activity` VALUES ('459','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-01 01:29:41');
INSERT INTO `tbl_recent_activity` VALUES ('460','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-09-01 01:32:40');
INSERT INTO `tbl_recent_activity` VALUES ('461','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-01 01:43:25');
INSERT INTO `tbl_recent_activity` VALUES ('462','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-09-01 01:43:42');
INSERT INTO `tbl_recent_activity` VALUES ('463','2','module_opened','Module 29: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-01 01:44:15');
INSERT INTO `tbl_recent_activity` VALUES ('464','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-09-01 01:44:15');
INSERT INTO `tbl_recent_activity` VALUES ('465','2','module_opened','Module 24: asd','Computer System Servicing','2026-09-01 01:53:25');
INSERT INTO `tbl_recent_activity` VALUES ('466','2','lesson_opened','Lesson 1: asd','Computer System Servicing','2026-09-01 01:53:25');
INSERT INTO `tbl_recent_activity` VALUES ('467','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-01 01:53:52');
INSERT INTO `tbl_recent_activity` VALUES ('468','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 01:53:52');
INSERT INTO `tbl_recent_activity` VALUES ('469','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-09-01 11:56:49');
INSERT INTO `tbl_recent_activity` VALUES ('470','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-01 11:56:49');
INSERT INTO `tbl_recent_activity` VALUES ('471','2','module_opened','Module 28: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-01 11:58:55');
INSERT INTO `tbl_recent_activity` VALUES ('472','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-09-01 11:58:55');
INSERT INTO `tbl_recent_activity` VALUES ('473','2','module_opened','Module 27: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-01 11:59:22');
INSERT INTO `tbl_recent_activity` VALUES ('474','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-01 11:59:53');
INSERT INTO `tbl_recent_activity` VALUES ('475','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 11:59:53');
INSERT INTO `tbl_recent_activity` VALUES ('476','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 11:59:59');
INSERT INTO `tbl_recent_activity` VALUES ('477','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-01 12:02:27');
INSERT INTO `tbl_recent_activity` VALUES ('478','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 12:02:27');
INSERT INTO `tbl_recent_activity` VALUES ('479','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 12:10:55');
INSERT INTO `tbl_recent_activity` VALUES ('480','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 12:20:23');
INSERT INTO `tbl_recent_activity` VALUES ('481','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 13:06:41');
INSERT INTO `tbl_recent_activity` VALUES ('482','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-01 13:07:17');
INSERT INTO `tbl_recent_activity` VALUES ('483','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 13:07:17');
INSERT INTO `tbl_recent_activity` VALUES ('484','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-01 14:54:24');
INSERT INTO `tbl_recent_activity` VALUES ('485','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 14:54:24');
INSERT INTO `tbl_recent_activity` VALUES ('486','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 15:07:28');
INSERT INTO `tbl_recent_activity` VALUES ('487','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 15:17:51');
INSERT INTO `tbl_recent_activity` VALUES ('488','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 15:23:02');
INSERT INTO `tbl_recent_activity` VALUES ('489','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 15:30:09');
INSERT INTO `tbl_recent_activity` VALUES ('490','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 15:36:12');
INSERT INTO `tbl_recent_activity` VALUES ('491','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-01 15:38:56');
INSERT INTO `tbl_recent_activity` VALUES ('492','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 15:38:56');
INSERT INTO `tbl_recent_activity` VALUES ('493','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 15:39:04');
INSERT INTO `tbl_recent_activity` VALUES ('494','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 15:51:12');
INSERT INTO `tbl_recent_activity` VALUES ('495','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-09-01 20:33:09');
INSERT INTO `tbl_recent_activity` VALUES ('496','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-01 20:33:09');
INSERT INTO `tbl_recent_activity` VALUES ('497','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-01 20:34:17');
INSERT INTO `tbl_recent_activity` VALUES ('498','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 20:34:17');
INSERT INTO `tbl_recent_activity` VALUES ('499','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-01 20:34:37');
INSERT INTO `tbl_recent_activity` VALUES ('500','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 20:34:37');
INSERT INTO `tbl_recent_activity` VALUES ('501','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 20:34:42');
INSERT INTO `tbl_recent_activity` VALUES ('502','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-09-01 20:36:34');
INSERT INTO `tbl_recent_activity` VALUES ('503','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-09-01 20:36:34');
INSERT INTO `tbl_recent_activity` VALUES ('504','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-09-01 20:36:39');
INSERT INTO `tbl_recent_activity` VALUES ('505','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-01 20:57:16');
INSERT INTO `tbl_recent_activity` VALUES ('506','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-01 20:57:21');
INSERT INTO `tbl_recent_activity` VALUES ('507','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 20:57:21');
INSERT INTO `tbl_recent_activity` VALUES ('508','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-09-01 21:04:08');
INSERT INTO `tbl_recent_activity` VALUES ('509','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 21:47:45');
INSERT INTO `tbl_recent_activity` VALUES ('510','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 21:54:58');
INSERT INTO `tbl_recent_activity` VALUES ('511','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 22:03:28');
INSERT INTO `tbl_recent_activity` VALUES ('512','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 22:03:28');
INSERT INTO `tbl_recent_activity` VALUES ('513','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 22:10:12');
INSERT INTO `tbl_recent_activity` VALUES ('514','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 22:26:00');
INSERT INTO `tbl_recent_activity` VALUES ('515','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 22:31:59');
INSERT INTO `tbl_recent_activity` VALUES ('516','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 22:39:02');
INSERT INTO `tbl_recent_activity` VALUES ('517','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-09-01 22:49:05');
INSERT INTO `tbl_recent_activity` VALUES ('518','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-01 22:49:05');
INSERT INTO `tbl_recent_activity` VALUES ('519','2','module_opened','Module 10: Introduction to Cooperatives','Computer System Servicing','2026-09-01 22:51:42');
INSERT INTO `tbl_recent_activity` VALUES ('520','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-09-01 22:51:42');
INSERT INTO `tbl_recent_activity` VALUES ('521','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-01 22:53:53');
INSERT INTO `tbl_recent_activity` VALUES ('522','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 22:53:54');
INSERT INTO `tbl_recent_activity` VALUES ('523','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-01 23:03:13');
INSERT INTO `tbl_recent_activity` VALUES ('524','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-01 23:08:35');
INSERT INTO `tbl_recent_activity` VALUES ('525','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-02 00:25:06');
INSERT INTO `tbl_recent_activity` VALUES ('526','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-02 00:25:07');
INSERT INTO `tbl_recent_activity` VALUES ('527','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-02 00:28:29');
INSERT INTO `tbl_recent_activity` VALUES ('528','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-02 03:55:50');
INSERT INTO `tbl_recent_activity` VALUES ('529','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-02 03:55:51');
INSERT INTO `tbl_recent_activity` VALUES ('530','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-02 08:17:21');
INSERT INTO `tbl_recent_activity` VALUES ('531','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-02 08:17:21');
INSERT INTO `tbl_recent_activity` VALUES ('532','2','module_opened','Module 36: adsasd','Computer System Servicing','2026-09-02 08:20:38');
INSERT INTO `tbl_recent_activity` VALUES ('533','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-02 08:20:38');
INSERT INTO `tbl_recent_activity` VALUES ('534','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-02 08:44:40');
INSERT INTO `tbl_recent_activity` VALUES ('535','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-02 08:44:40');
INSERT INTO `tbl_recent_activity` VALUES ('536','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-02 08:44:45');
INSERT INTO `tbl_recent_activity` VALUES ('537','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-02 10:01:16');
INSERT INTO `tbl_recent_activity` VALUES ('538','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-02 10:24:05');
INSERT INTO `tbl_recent_activity` VALUES ('539','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-02 10:24:06');
INSERT INTO `tbl_recent_activity` VALUES ('540','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-02 15:20:07');
INSERT INTO `tbl_recent_activity` VALUES ('541','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-02 15:20:25');
INSERT INTO `tbl_recent_activity` VALUES ('542','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-09-02 15:20:57');
INSERT INTO `tbl_recent_activity` VALUES ('543','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-09-02 15:20:57');
INSERT INTO `tbl_recent_activity` VALUES ('544','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-09-02 15:21:01');
INSERT INTO `tbl_recent_activity` VALUES ('545','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-02 15:23:08');
INSERT INTO `tbl_recent_activity` VALUES ('546','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-02 15:23:11');
INSERT INTO `tbl_recent_activity` VALUES ('547','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-02 15:25:17');
INSERT INTO `tbl_recent_activity` VALUES ('548','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-02 15:25:30');
INSERT INTO `tbl_recent_activity` VALUES ('549','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-02 15:25:30');
INSERT INTO `tbl_recent_activity` VALUES ('550','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-02 15:33:19');
INSERT INTO `tbl_recent_activity` VALUES ('551','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-02 15:38:49');
INSERT INTO `tbl_recent_activity` VALUES ('552','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-02 15:38:57');
INSERT INTO `tbl_recent_activity` VALUES ('553','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-02 15:54:56');
INSERT INTO `tbl_recent_activity` VALUES ('554','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-02 15:54:56');
INSERT INTO `tbl_recent_activity` VALUES ('555','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-02 15:55:00');
INSERT INTO `tbl_recent_activity` VALUES ('556','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-02 18:15:15');
INSERT INTO `tbl_recent_activity` VALUES ('557','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-02 23:01:06');
INSERT INTO `tbl_recent_activity` VALUES ('558','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-02 23:50:42');
INSERT INTO `tbl_recent_activity` VALUES ('559','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-02 23:50:52');
INSERT INTO `tbl_recent_activity` VALUES ('560','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 00:12:21');
INSERT INTO `tbl_recent_activity` VALUES ('561','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 00:21:17');
INSERT INTO `tbl_recent_activity` VALUES ('562','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 00:22:38');
INSERT INTO `tbl_recent_activity` VALUES ('563','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 00:48:53');
INSERT INTO `tbl_recent_activity` VALUES ('564','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 01:11:33');
INSERT INTO `tbl_recent_activity` VALUES ('565','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 01:18:31');
INSERT INTO `tbl_recent_activity` VALUES ('566','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 01:20:50');
INSERT INTO `tbl_recent_activity` VALUES ('567','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 01:26:25');
INSERT INTO `tbl_recent_activity` VALUES ('568','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 01:41:03');
INSERT INTO `tbl_recent_activity` VALUES ('569','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 01:52:53');
INSERT INTO `tbl_recent_activity` VALUES ('570','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 01:58:06');
INSERT INTO `tbl_recent_activity` VALUES ('571','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 02:03:45');
INSERT INTO `tbl_recent_activity` VALUES ('572','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 02:08:47');
INSERT INTO `tbl_recent_activity` VALUES ('573','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 02:14:14');
INSERT INTO `tbl_recent_activity` VALUES ('574','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 02:23:02');
INSERT INTO `tbl_recent_activity` VALUES ('575','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 03:16:25');
INSERT INTO `tbl_recent_activity` VALUES ('576','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 04:08:57');
INSERT INTO `tbl_recent_activity` VALUES ('577','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 04:31:30');
INSERT INTO `tbl_recent_activity` VALUES ('578','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 04:40:30');
INSERT INTO `tbl_recent_activity` VALUES ('579','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 04:44:39');
INSERT INTO `tbl_recent_activity` VALUES ('580','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 04:45:14');
INSERT INTO `tbl_recent_activity` VALUES ('581','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 04:45:50');
INSERT INTO `tbl_recent_activity` VALUES ('582','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 04:50:29');
INSERT INTO `tbl_recent_activity` VALUES ('583','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 04:51:22');
INSERT INTO `tbl_recent_activity` VALUES ('584','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 05:03:32');
INSERT INTO `tbl_recent_activity` VALUES ('585','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 05:08:38');
INSERT INTO `tbl_recent_activity` VALUES ('586','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 05:11:38');
INSERT INTO `tbl_recent_activity` VALUES ('587','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 05:11:40');
INSERT INTO `tbl_recent_activity` VALUES ('588','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 05:14:15');
INSERT INTO `tbl_recent_activity` VALUES ('589','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 05:17:49');
INSERT INTO `tbl_recent_activity` VALUES ('590','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 05:21:25');
INSERT INTO `tbl_recent_activity` VALUES ('591','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 05:21:29');
INSERT INTO `tbl_recent_activity` VALUES ('592','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 05:23:21');
INSERT INTO `tbl_recent_activity` VALUES ('593','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 05:28:28');
INSERT INTO `tbl_recent_activity` VALUES ('594','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 06:12:41');
INSERT INTO `tbl_recent_activity` VALUES ('595','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 06:18:04');
INSERT INTO `tbl_recent_activity` VALUES ('596','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 06:23:40');
INSERT INTO `tbl_recent_activity` VALUES ('597','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 06:25:07');
INSERT INTO `tbl_recent_activity` VALUES ('598','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 06:27:17');
INSERT INTO `tbl_recent_activity` VALUES ('599','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 06:36:30');
INSERT INTO `tbl_recent_activity` VALUES ('600','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 06:41:53');
INSERT INTO `tbl_recent_activity` VALUES ('601','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 06:51:08');
INSERT INTO `tbl_recent_activity` VALUES ('602','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 06:56:21');
INSERT INTO `tbl_recent_activity` VALUES ('603','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 07:01:27');
INSERT INTO `tbl_recent_activity` VALUES ('604','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 07:06:44');
INSERT INTO `tbl_recent_activity` VALUES ('605','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 07:41:43');
INSERT INTO `tbl_recent_activity` VALUES ('606','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 07:47:38');
INSERT INTO `tbl_recent_activity` VALUES ('607','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 07:48:46');
INSERT INTO `tbl_recent_activity` VALUES ('608','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 08:26:16');
INSERT INTO `tbl_recent_activity` VALUES ('609','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 08:43:04');
INSERT INTO `tbl_recent_activity` VALUES ('610','2','','Proper Procedure for Assembling a Desktop Computer',NULL,'2026-09-03 08:43:07');
INSERT INTO `tbl_recent_activity` VALUES ('611','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 11:54:23');
INSERT INTO `tbl_recent_activity` VALUES ('612','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 12:04:52');
INSERT INTO `tbl_recent_activity` VALUES ('613','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 12:49:41');
INSERT INTO `tbl_recent_activity` VALUES ('614','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 12:54:53');
INSERT INTO `tbl_recent_activity` VALUES ('615','2','','Proper Procedure for Assembling a Desktop Computer',NULL,'2026-09-03 12:55:12');
INSERT INTO `tbl_recent_activity` VALUES ('616','2','','Proper Procedure for Assembling a Desktop Computer',NULL,'2026-09-03 12:55:25');
INSERT INTO `tbl_recent_activity` VALUES ('617','2','','Proper Procedure for Assembling a Desktop Computer',NULL,'2026-09-03 12:56:18');
INSERT INTO `tbl_recent_activity` VALUES ('618','2','','Proper Procedure for Assembling a Desktop Computer',NULL,'2026-09-03 12:59:06');
INSERT INTO `tbl_recent_activity` VALUES ('619','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 13:00:59');
INSERT INTO `tbl_recent_activity` VALUES ('620','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 13:17:37');
INSERT INTO `tbl_recent_activity` VALUES ('621','2','','Proper Procedure for Assembling a Desktop Computer',NULL,'2026-09-03 13:17:46');
INSERT INTO `tbl_recent_activity` VALUES ('622','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 13:25:21');
INSERT INTO `tbl_recent_activity` VALUES ('623','2','','Proper Procedure for Assembling a Desktop Computer',NULL,'2026-09-03 13:28:30');
INSERT INTO `tbl_recent_activity` VALUES ('624','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 14:02:52');
INSERT INTO `tbl_recent_activity` VALUES ('625','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 14:02:56');
INSERT INTO `tbl_recent_activity` VALUES ('626','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 14:02:58');
INSERT INTO `tbl_recent_activity` VALUES ('627','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 14:13:12');
INSERT INTO `tbl_recent_activity` VALUES ('628','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 14:13:12');
INSERT INTO `tbl_recent_activity` VALUES ('629','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 14:18:18');
INSERT INTO `tbl_recent_activity` VALUES ('630','2','','Hardware Matching Components',NULL,'2026-09-03 14:23:15');
INSERT INTO `tbl_recent_activity` VALUES ('631','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 14:23:32');
INSERT INTO `tbl_recent_activity` VALUES ('632','2','','Hardware Matching Components',NULL,'2026-09-03 14:24:27');
INSERT INTO `tbl_recent_activity` VALUES ('633','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 14:26:44');
INSERT INTO `tbl_recent_activity` VALUES ('634','2','','Hardware Matching Components',NULL,'2026-09-03 14:27:07');
INSERT INTO `tbl_recent_activity` VALUES ('635','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 14:28:36');
INSERT INTO `tbl_recent_activity` VALUES ('636','2','','Hardware Matching Components',NULL,'2026-09-03 14:29:18');
INSERT INTO `tbl_recent_activity` VALUES ('637','2','','Hardware Matching Components',NULL,'2026-09-03 14:32:04');
INSERT INTO `tbl_recent_activity` VALUES ('638','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 14:34:05');
INSERT INTO `tbl_recent_activity` VALUES ('639','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 14:34:06');
INSERT INTO `tbl_recent_activity` VALUES ('640','2','','Hardware Matching Components',NULL,'2026-09-03 14:34:14');
INSERT INTO `tbl_recent_activity` VALUES ('641','2','','Hardware Matching Components',NULL,'2026-09-03 14:36:20');
INSERT INTO `tbl_recent_activity` VALUES ('642','2','','Hardware Matching Components',NULL,'2026-09-03 14:37:04');
INSERT INTO `tbl_recent_activity` VALUES ('643','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-03 14:41:22');
INSERT INTO `tbl_recent_activity` VALUES ('644','2','','Hardware Matching Components',NULL,'2026-09-03 14:41:27');
INSERT INTO `tbl_recent_activity` VALUES ('645','2','','Hardware Matching Components',NULL,'2026-09-03 14:44:44');
INSERT INTO `tbl_recent_activity` VALUES ('646','2','','Hardware Matching Components',NULL,'2026-09-03 14:46:08');
INSERT INTO `tbl_recent_activity` VALUES ('647','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 14:46:29');
INSERT INTO `tbl_recent_activity` VALUES ('648','2','quiz_completed','Quiz','Computer System Servicing','2026-09-03 14:47:10');
INSERT INTO `tbl_recent_activity` VALUES ('649','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-03 14:47:16');
INSERT INTO `tbl_recent_activity` VALUES ('650','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 14:47:16');
INSERT INTO `tbl_recent_activity` VALUES ('651','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 14:47:23');
INSERT INTO `tbl_recent_activity` VALUES ('652','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 15:31:43');
INSERT INTO `tbl_recent_activity` VALUES ('653','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 15:33:21');
INSERT INTO `tbl_recent_activity` VALUES ('654','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-09-03 15:35:55');
INSERT INTO `tbl_recent_activity` VALUES ('655','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-09-03 15:35:55');
INSERT INTO `tbl_recent_activity` VALUES ('656','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-09-03 15:36:01');
INSERT INTO `tbl_recent_activity` VALUES ('657','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 15:37:16');
INSERT INTO `tbl_recent_activity` VALUES ('658','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 15:43:54');
INSERT INTO `tbl_recent_activity` VALUES ('659','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-09-03 15:43:55');
INSERT INTO `tbl_recent_activity` VALUES ('660','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 15:44:00');
INSERT INTO `tbl_recent_activity` VALUES ('661','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 15:54:01');
INSERT INTO `tbl_recent_activity` VALUES ('662','2','quiz_completed','Module 2 Quiz: Hardware and Software','Computer System Servicing','2026-09-03 15:55:49');
INSERT INTO `tbl_recent_activity` VALUES ('663','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-09-03 15:58:51');
INSERT INTO `tbl_recent_activity` VALUES ('664','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 15:59:07');
INSERT INTO `tbl_recent_activity` VALUES ('665','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-03 16:00:51');
INSERT INTO `tbl_recent_activity` VALUES ('666','2','quiz_completed','Module 1 Quiz: Introduction to Cooperatives','Computer System Servicing','2026-09-03 16:01:17');
INSERT INTO `tbl_recent_activity` VALUES ('667','2','module_opened','Module 36: adsasd','Computer System Servicing','2026-09-03 16:06:52');
INSERT INTO `tbl_recent_activity` VALUES ('668','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-03 16:06:52');
INSERT INTO `tbl_recent_activity` VALUES ('669','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-04 01:00:53');
INSERT INTO `tbl_recent_activity` VALUES ('670','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 01:00:53');
INSERT INTO `tbl_recent_activity` VALUES ('671','2','module_opened','Module 36: adsasd','Computer System Servicing','2026-09-04 01:00:59');
INSERT INTO `tbl_recent_activity` VALUES ('672','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-04 01:00:59');
INSERT INTO `tbl_recent_activity` VALUES ('673','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-04 01:01:19');
INSERT INTO `tbl_recent_activity` VALUES ('674','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-04 01:01:19');
INSERT INTO `tbl_recent_activity` VALUES ('675','2','module_opened','Module 29: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-04 01:04:26');
INSERT INTO `tbl_recent_activity` VALUES ('676','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-09-04 01:04:26');
INSERT INTO `tbl_recent_activity` VALUES ('677','2','module_opened','Module 28: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-04 01:05:41');
INSERT INTO `tbl_recent_activity` VALUES ('678','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-09-04 01:05:55');
INSERT INTO `tbl_recent_activity` VALUES ('679','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-04 01:05:55');
INSERT INTO `tbl_recent_activity` VALUES ('680','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-04 01:06:38');
INSERT INTO `tbl_recent_activity` VALUES ('681','2','module_opened','Module 27: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-04 01:07:19');
INSERT INTO `tbl_recent_activity` VALUES ('682','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-04 01:11:12');
INSERT INTO `tbl_recent_activity` VALUES ('683','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-04 01:15:47');
INSERT INTO `tbl_recent_activity` VALUES ('684','2','','Proper Procedure for Assembling a Desktop Computer',NULL,'2026-09-04 01:22:21');
INSERT INTO `tbl_recent_activity` VALUES ('685','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-04 01:22:28');
INSERT INTO `tbl_recent_activity` VALUES ('686','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-04 01:33:54');
INSERT INTO `tbl_recent_activity` VALUES ('687','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-09-04 02:06:18');
INSERT INTO `tbl_recent_activity` VALUES ('688','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-04 02:06:18');
INSERT INTO `tbl_recent_activity` VALUES ('689','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-04 08:26:49');
INSERT INTO `tbl_recent_activity` VALUES ('690','2','module_opened','Module 36: adsasd','Computer System Servicing','2026-09-04 08:27:27');
INSERT INTO `tbl_recent_activity` VALUES ('691','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-04 08:27:27');
INSERT INTO `tbl_recent_activity` VALUES ('692','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-04 08:57:03');
INSERT INTO `tbl_recent_activity` VALUES ('693','2','module_opened','Module 30: qweqweqweqweqwe','Computer System Servicing','2026-09-04 17:20:08');
INSERT INTO `tbl_recent_activity` VALUES ('694','2','lesson_opened','Lesson 1: cvcvcvcv','Computer System Servicing','2026-09-04 17:20:08');
INSERT INTO `tbl_recent_activity` VALUES ('695','2','module_opened','Module 28: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-04 17:21:13');
INSERT INTO `tbl_recent_activity` VALUES ('696','2','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-09-04 17:21:13');
INSERT INTO `tbl_recent_activity` VALUES ('697','2','module_opened','Module 27: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-04 17:21:39');
INSERT INTO `tbl_recent_activity` VALUES ('698','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-04 17:22:58');
INSERT INTO `tbl_recent_activity` VALUES ('699','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-04 17:22:59');
INSERT INTO `tbl_recent_activity` VALUES ('700','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-04 17:23:11');
INSERT INTO `tbl_recent_activity` VALUES ('701','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 17:23:11');
INSERT INTO `tbl_recent_activity` VALUES ('702','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 17:35:22');
INSERT INTO `tbl_recent_activity` VALUES ('703','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 20:09:03');
INSERT INTO `tbl_recent_activity` VALUES ('704','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 20:52:21');
INSERT INTO `tbl_recent_activity` VALUES ('705','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 21:10:12');
INSERT INTO `tbl_recent_activity` VALUES ('706','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 21:15:24');
INSERT INTO `tbl_recent_activity` VALUES ('707','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 21:33:15');
INSERT INTO `tbl_recent_activity` VALUES ('708','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 21:46:35');
INSERT INTO `tbl_recent_activity` VALUES ('709','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 21:53:17');
INSERT INTO `tbl_recent_activity` VALUES ('710','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 21:58:55');
INSERT INTO `tbl_recent_activity` VALUES ('711','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 22:12:27');
INSERT INTO `tbl_recent_activity` VALUES ('712','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 22:18:29');
INSERT INTO `tbl_recent_activity` VALUES ('713','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 22:35:30');
INSERT INTO `tbl_recent_activity` VALUES ('714','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-04 22:36:58');
INSERT INTO `tbl_recent_activity` VALUES ('715','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-04 22:40:55');
INSERT INTO `tbl_recent_activity` VALUES ('716','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-04 22:44:46');
INSERT INTO `tbl_recent_activity` VALUES ('717','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-04 22:44:46');
INSERT INTO `tbl_recent_activity` VALUES ('718','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-04 23:00:33');
INSERT INTO `tbl_recent_activity` VALUES ('719','2','module_opened','Module 31: jkjk','Computer System Servicing','2026-09-05 00:09:33');
INSERT INTO `tbl_recent_activity` VALUES ('720','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-05 00:09:33');
INSERT INTO `tbl_recent_activity` VALUES ('721','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-05 00:15:56');
INSERT INTO `tbl_recent_activity` VALUES ('722','2','lesson_opened','Lesson 1: jkjk','Computer System Servicing','2026-09-05 00:26:31');
INSERT INTO `tbl_recent_activity` VALUES ('723','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-05 00:26:37');
INSERT INTO `tbl_recent_activity` VALUES ('724','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 00:26:37');
INSERT INTO `tbl_recent_activity` VALUES ('725','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 00:33:08');
INSERT INTO `tbl_recent_activity` VALUES ('726','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 01:01:18');
INSERT INTO `tbl_recent_activity` VALUES ('727','2','','Hardware Matching Components',NULL,'2026-09-05 01:03:49');
INSERT INTO `tbl_recent_activity` VALUES ('728','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 01:34:35');
INSERT INTO `tbl_recent_activity` VALUES ('729','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 01:40:07');
INSERT INTO `tbl_recent_activity` VALUES ('730','2','module_opened','Module 19: Introduction to Cooperatives','Computer System Servicing','2026-09-05 01:43:31');
INSERT INTO `tbl_recent_activity` VALUES ('731','2','lesson_opened','Lesson 1: What is a Cooperative?','Computer System Servicing','2026-09-05 01:43:31');
INSERT INTO `tbl_recent_activity` VALUES ('732','2','lesson_opened','Lesson 7: Types of Cooperatives','Computer System Servicing','2026-09-05 01:43:35');
INSERT INTO `tbl_recent_activity` VALUES ('733','2','lesson_opened','Lesson 5: Types of Cooperatives','Computer System Servicing','2026-09-05 01:43:41');
INSERT INTO `tbl_recent_activity` VALUES ('734','2','lesson_opened','Lesson 3: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-05 01:43:51');
INSERT INTO `tbl_recent_activity` VALUES ('735','2','module_opened','Module 20: Computer System Servicing','Computer System Servicing','2026-09-05 01:45:25');
INSERT INTO `tbl_recent_activity` VALUES ('736','2','lesson_opened','Lesson 1: Purpose and Importance of Cooperatives','Computer System Servicing','2026-09-05 01:45:25');
INSERT INTO `tbl_recent_activity` VALUES ('737','2','module_opened','Module 18: Understanding Culture Society and Politics','Computer System Servicing','2026-09-05 01:45:48');
INSERT INTO `tbl_recent_activity` VALUES ('738','2','lesson_opened','Lesson 1: Understanding the Computer system servicing','Computer System Servicing','2026-09-05 01:45:49');
INSERT INTO `tbl_recent_activity` VALUES ('739','2','quiz_completed','asd','Computer System Servicing','2026-09-05 01:45:58');
INSERT INTO `tbl_recent_activity` VALUES ('740','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 01:55:58');
INSERT INTO `tbl_recent_activity` VALUES ('741','2','module_opened','Module 36: adsasd','Computer System Servicing','2026-09-05 01:56:05');
INSERT INTO `tbl_recent_activity` VALUES ('742','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-05 01:56:05');
INSERT INTO `tbl_recent_activity` VALUES ('743','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-05 02:02:22');
INSERT INTO `tbl_recent_activity` VALUES ('744','2','lesson_opened','Lesson 1: Understanding the Computer system servicing','Computer System Servicing','2026-09-05 02:02:54');
INSERT INTO `tbl_recent_activity` VALUES ('745','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 02:04:51');
INSERT INTO `tbl_recent_activity` VALUES ('746','2','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-05 02:08:08');
INSERT INTO `tbl_recent_activity` VALUES ('747','2','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-05 02:22:28');
INSERT INTO `tbl_recent_activity` VALUES ('748','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 02:22:29');
INSERT INTO `tbl_recent_activity` VALUES ('749','2','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-05 02:37:24');
INSERT INTO `tbl_recent_activity` VALUES ('750','3','module_opened','Module 32: xcxcxc','Computer System Servicing','2026-09-15 15:11:59');
INSERT INTO `tbl_recent_activity` VALUES ('751','3','lesson_opened','Lesson 1: xcxcxc','Computer System Servicing','2026-09-15 15:11:59');
INSERT INTO `tbl_recent_activity` VALUES ('752','3','module_opened','Module 33: Arrange','Computer System Servicing','2026-09-15 15:12:43');
INSERT INTO `tbl_recent_activity` VALUES ('753','3','lesson_opened','Lesson 1: Arrange','Computer System Servicing','2026-09-15 15:12:43');
INSERT INTO `tbl_recent_activity` VALUES ('754','3','module_opened','Module 34: asas','Computer System Servicing','2026-09-15 15:12:57');
INSERT INTO `tbl_recent_activity` VALUES ('755','3','lesson_opened','Lesson 1: asasa','Computer System Servicing','2026-09-15 15:12:57');
INSERT INTO `tbl_recent_activity` VALUES ('756','3','module_opened','Module 25: Introduction to Computer Hardware and Components','Computer System Servicing','2026-09-15 15:13:10');
INSERT INTO `tbl_recent_activity` VALUES ('757','3','lesson_opened','Lesson 1: Identifying Computer Hardware Components','Computer System Servicing','2026-09-15 15:13:10');
INSERT INTO `tbl_recent_activity` VALUES ('758','3','module_opened','Module 36: adsasd','Computer System Servicing','2026-09-15 15:13:25');
INSERT INTO `tbl_recent_activity` VALUES ('759','3','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-15 15:13:25');
INSERT INTO `tbl_recent_activity` VALUES ('760','3','module_opened','Module 36: adsasd','Computer System Servicing','2026-09-15 17:49:33');
INSERT INTO `tbl_recent_activity` VALUES ('761','3','lesson_opened','Lesson 1: asdasd','Computer System Servicing','2026-09-15 17:49:33');

DROP TABLE IF EXISTS `tbl_role_permissions`;
CREATE TABLE `tbl_role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  `permission_key` varchar(64) NOT NULL,
  `allowed` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_role_perm` (`role`,`permission_key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_role_permissions` VALUES ('1','admin','manage_teacher_accounts','1','2026-09-15 08:36:17');

DROP TABLE IF EXISTS `tbl_school_profile`;
CREATE TABLE `tbl_school_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(150) NOT NULL DEFAULT '',
  `deped_school_id` varchar(50) DEFAULT NULL,
  `region_division` varchar(150) DEFAULT NULL,
  `principal_name` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `current_school_year` varchar(20) DEFAULT NULL,
  `grade_levels_offered` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_school_profile` VALUES ('1','SHS Strand','','','','','','2026-2027','Grade 11, Grade 12','2026-09-15 18:13:08');

DROP TABLE IF EXISTS `tbl_sections`;
CREATE TABLE `tbl_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_level_id` int(11) NOT NULL,
  `strand` varchar(20) DEFAULT NULL,
  `section_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grade_level_id` (`grade_level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_sections` VALUES ('1','1','CSS','CSS 11-1');
INSERT INTO `tbl_sections` VALUES ('2','1','CSS','CSS 11-2');
INSERT INTO `tbl_sections` VALUES ('3','2','CSS','CSS 12-1');
INSERT INTO `tbl_sections` VALUES ('4','2','CSS','CSS 12-2');
INSERT INTO `tbl_sections` VALUES ('6','2','CSS','CSS 12-3');
INSERT INTO `tbl_sections` VALUES ('7','2','CSS','CSS 12-4');
INSERT INTO `tbl_sections` VALUES ('8','2','CSS','CSS 12-5');
INSERT INTO `tbl_sections` VALUES ('9','1','CSS','CSS 11-3');
INSERT INTO `tbl_sections` VALUES ('10','1','CSS','CSS 11-4');
INSERT INTO `tbl_sections` VALUES ('11','1','CSS','CSS 11-5');
INSERT INTO `tbl_sections` VALUES ('12','2','CSS','CSS 12-6');
INSERT INTO `tbl_sections` VALUES ('13','2','CSS','CSS 12-7');
INSERT INTO `tbl_sections` VALUES ('14','2','CSS','CSS 12-8');
INSERT INTO `tbl_sections` VALUES ('15','1','CSS','CSS 11-8');
INSERT INTO `tbl_sections` VALUES ('16','1','CSS','CSS 11-6');

DROP TABLE IF EXISTS `tbl_strand_settings`;
CREATE TABLE `tbl_strand_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `strand_code` varchar(20) NOT NULL,
  `strand_name` varchar(150) NOT NULL,
  `track` varchar(50) DEFAULT NULL,
  `category_label` varchar(50) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `related_blurb` text DEFAULT NULL,
  `is_related_recommendation` tinyint(1) NOT NULL DEFAULT 0,
  `is_offered` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_strand_settings` VALUES ('1','STEM','Science, Technology, Engineering & Math','Academic Track',NULL,'Heavy on math and lab sciences. Built for students aiming at engineering, medicine, IT, or pure science degrees.','../images/stem.jpg',NULL,'0','1','1');
INSERT INTO `tbl_strand_settings` VALUES ('2','ABM','Accountancy, Business & Management','Academic Track',NULL,'Covers finance, marketing, and entrepreneurship — a direct path into business, accountancy, or management degrees.','../images/abm.jpg',NULL,'0','1','2');
INSERT INTO `tbl_strand_settings` VALUES ('3','HUMSS','Humanities & Social Sciences','Academic Track',NULL,'Focuses on communication, law, and social issues — ideal for future lawyers, teachers, writers, and public servants.','../images/humms.jpg',NULL,'0','1','3');
INSERT INTO `tbl_strand_settings` VALUES ('4','CSS','Computer System Servicing','TVL Track',NULL,'Hands-on training in PC assembly, OS installation, networking, and hardware repair, leading to a TESDA NC II certificate.','../images/css.jpg',NULL,'0','1','4');
INSERT INTO `tbl_strand_settings` VALUES ('5','EPAS','Electronic Products Assembly & Servicing','TVL Track',NULL,'Gain hands-on experience in assembling, testing, and repairing electronic devices for careers in electronics and technical servicing.','../images/epas.avif',NULL,'0','1','5');
INSERT INTO `tbl_strand_settings` VALUES ('6','CBF','Cookery, Bread & Pastries, Food & Beverage Services','TVL Track',NULL,'Develop skills in cooking, baking, and food service, preparing students for careers in restaurants, hotels, and the hospitality industry.','../images/cookery.jpg',NULL,'0','1','6');
INSERT INTO `tbl_strand_settings` VALUES ('7','BHW','Beauty Care, Hair Dressing, Wellness & Massage','TVL Track',NULL,'Learn beauty care, hairstyling, and wellness massage, leading to careers in salons, spas, and the beauty industry.','../images/beauty.avif',NULL,'0','1','7');
INSERT INTO `tbl_strand_settings` VALUES ('8','EET-ICT','Electrical & Electronics Technology','TVL Track','TVL · ICT',NULL,'https://images.unsplash.com/photo-1551434678-e076c223a692?w=600&q=80','Pairs naturally with CSS — where CSS builds and repairs the machine, ICT focuses on the programs and systems that run on it.','1','0','8');
INSERT INTO `tbl_strand_settings` VALUES ('9','EET-ELEX','Electrical & Electronics Technology','TVL Track','TVL · Electronics',NULL,'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&q=80','Shares core competencies with CSS in circuitry, soldering, and component-level troubleshooting.','1','0','9');
INSERT INTO `tbl_strand_settings` VALUES ('10','IATD','Industrial Arts & Technical Drafting','TVL Track','TVL · Industrial Arts',NULL,'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=600&q=80','Teaches the technical drawing and fabrication skills CSS students use when documenting layouts and server room setups.','1','0','10');

DROP TABLE IF EXISTS `tbl_student_enrollments`;
CREATE TABLE `tbl_student_enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `student_lrn` varchar(50) DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `grade_level_id` int(11) NOT NULL,
  `school_year` varchar(9) NOT NULL,
  `enrolled_by_teacher_id` int(11) DEFAULT NULL,
  `enrolled_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_student_subject_year` (`student_id`,`subject_id`,`school_year`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`),
  KEY `section_id` (`section_id`),
  KEY `fk_enroll_grade_level` (`grade_level_id`),
  KEY `fk_enroll_enrolled_by` (`enrolled_by_teacher_id`),
  CONSTRAINT `fk_enroll_enrolled_by` FOREIGN KEY (`enrolled_by_teacher_id`) REFERENCES `tbl_teachers` (`id`),
  CONSTRAINT `fk_enroll_grade_level` FOREIGN KEY (`grade_level_id`) REFERENCES `tbl_grade_level` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_student_enrollments` VALUES ('2','2','107908100055','1','3','2','2026-2027','1','2026-09-03 21:52:39');
INSERT INTO `tbl_student_enrollments` VALUES ('3',NULL,'107908100012','1','3','2','2026-2027','1','2026-09-03 21:52:40');
INSERT INTO `tbl_student_enrollments` VALUES ('4',NULL,'107908100214','1','3','2','2026-2027','1','2026-09-03 21:52:40');
INSERT INTO `tbl_student_enrollments` VALUES ('5','2','107908100055','1','4','2','','1','2026-09-03 23:39:48');
INSERT INTO `tbl_student_enrollments` VALUES ('6',NULL,'136245789012','1','4','2','','1','2026-09-03 23:39:48');
INSERT INTO `tbl_student_enrollments` VALUES ('7',NULL,'124678903214','1','4','2','','1','2026-09-03 23:39:48');
INSERT INTO `tbl_student_enrollments` VALUES ('8','3','107908100331','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('9','4','107908100931','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('10','5','107908100404','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('11','6','107908100428','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('12','7','107908100519','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('13','8','107908100970','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('14','9','107908100840','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('15','10','107908100049','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('16','11','107908100219','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('17','12','107908100596','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('18','13','107908100038','1','3','2','','1','2026-09-05 06:45:53');
INSERT INTO `tbl_student_enrollments` VALUES ('19','14','107908100429','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('20','15','107908100444','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('21','16','107908100548','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('22','17','107908100088','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('23','18','107908100284','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('24','19','107908100074','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('25','20','107908100374','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('26','21','107908100777','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('27','22','107908100096','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('28','23','107908100154','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('29','24','107908100059','1','3','2','','1','2026-09-05 06:45:54');
INSERT INTO `tbl_student_enrollments` VALUES ('30','25','107908100666','1','3','2','','1','2026-09-05 06:45:54');

DROP TABLE IF EXISTS `tbl_student_progress`;
CREATE TABLE `tbl_student_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `content_type` enum('lesson','quiz','activity','assignment','flashcard','module') NOT NULL,
  `content_id` int(11) NOT NULL,
  `status` enum('not_started','in_progress','completed') NOT NULL DEFAULT 'not_started',
  `score` int(11) DEFAULT NULL,
  `total_points` int(11) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `passed` tinyint(1) DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `last_accessed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_progress` (`student_id`,`content_type`,`content_id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`),
  KEY `section_id` (`section_id`),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_student_progress` VALUES ('1','2','0',NULL,'lesson','1','completed',NULL,NULL,NULL,NULL,'0','2026-06-27 09:14:18',NULL,NULL,'2026-06-27 09:14:18');
INSERT INTO `tbl_student_progress` VALUES ('3','2','0',NULL,'lesson','2','completed',NULL,NULL,NULL,NULL,'0','2026-06-27 09:30:05',NULL,NULL,'2026-06-27 09:30:05');
INSERT INTO `tbl_student_progress` VALUES ('12','2','0',NULL,'lesson','4','completed',NULL,NULL,NULL,NULL,'0','2026-06-27 11:06:33',NULL,NULL,'2026-06-27 11:06:33');
INSERT INTO `tbl_student_progress` VALUES ('13','2','0',NULL,'lesson','5','completed',NULL,NULL,NULL,NULL,'0','2026-06-27 11:10:15',NULL,NULL,'2026-06-27 11:10:15');
INSERT INTO `tbl_student_progress` VALUES ('14','2','0',NULL,'lesson','6','completed',NULL,NULL,NULL,NULL,'0','2026-06-27 11:16:06',NULL,NULL,'2026-06-27 11:16:06');
INSERT INTO `tbl_student_progress` VALUES ('17','2','0',NULL,'lesson','7','completed',NULL,NULL,NULL,NULL,'0','2026-06-27 12:21:50',NULL,NULL,'2026-06-27 12:21:50');
INSERT INTO `tbl_student_progress` VALUES ('20','2','0',NULL,'lesson','8','completed',NULL,NULL,NULL,NULL,'0','2026-06-28 04:32:22',NULL,NULL,'2026-06-28 04:32:22');
INSERT INTO `tbl_student_progress` VALUES ('25','2','0',NULL,'lesson','9','completed',NULL,NULL,NULL,NULL,'0','2026-07-04 15:16:40',NULL,NULL,'2026-07-04 01:16:40');
INSERT INTO `tbl_student_progress` VALUES ('26','2','0',NULL,'lesson','10','completed',NULL,NULL,NULL,NULL,'0','2026-07-04 15:16:42',NULL,NULL,'2026-07-04 01:16:42');
INSERT INTO `tbl_student_progress` VALUES ('27','2','0',NULL,'lesson','11','completed',NULL,NULL,NULL,NULL,'0','2026-07-04 15:16:44',NULL,NULL,'2026-07-04 01:16:44');
INSERT INTO `tbl_student_progress` VALUES ('28','2','0',NULL,'lesson','12','completed',NULL,NULL,NULL,NULL,'0','2026-07-04 15:16:45',NULL,NULL,'2026-07-04 01:16:45');
INSERT INTO `tbl_student_progress` VALUES ('30','2','0',NULL,'lesson','17','completed',NULL,NULL,NULL,NULL,'0','2026-08-19 23:43:28',NULL,NULL,'2026-08-19 09:43:28');
INSERT INTO `tbl_student_progress` VALUES ('31','2','0',NULL,'lesson','33','completed',NULL,NULL,NULL,NULL,'0','2026-08-21 16:37:49',NULL,NULL,'2026-08-21 02:37:49');
INSERT INTO `tbl_student_progress` VALUES ('32','2','0',NULL,'lesson','21','completed',NULL,NULL,NULL,NULL,'0','2026-08-23 08:28:47',NULL,NULL,'2026-08-22 18:28:47');
INSERT INTO `tbl_student_progress` VALUES ('33','2','0',NULL,'lesson','22','completed',NULL,NULL,NULL,NULL,'0','2026-08-23 08:29:04',NULL,NULL,'2026-08-22 18:29:04');
INSERT INTO `tbl_student_progress` VALUES ('35','2','0',NULL,'lesson','3','completed',NULL,NULL,NULL,NULL,'0','2026-08-23 15:30:58',NULL,NULL,'2026-08-23 01:30:58');
INSERT INTO `tbl_student_progress` VALUES ('36','2','0',NULL,'lesson','24','completed',NULL,NULL,NULL,NULL,'0','2026-08-24 01:45:27',NULL,NULL,'2026-08-23 11:45:27');
INSERT INTO `tbl_student_progress` VALUES ('38','2','0',NULL,'lesson','23','completed',NULL,NULL,NULL,NULL,'0','2026-08-24 02:02:53',NULL,NULL,'2026-08-23 12:02:53');
INSERT INTO `tbl_student_progress` VALUES ('40','2','0',NULL,'lesson','26','completed',NULL,NULL,NULL,NULL,'0','2026-08-24 02:05:59',NULL,NULL,'2026-08-23 12:05:59');
INSERT INTO `tbl_student_progress` VALUES ('41','2','0',NULL,'lesson','31','completed',NULL,NULL,NULL,NULL,'0','2026-08-24 02:06:54',NULL,NULL,'2026-08-23 12:06:54');
INSERT INTO `tbl_student_progress` VALUES ('42','2','0',NULL,'lesson','14','completed',NULL,NULL,NULL,NULL,'0','2026-08-24 02:10:31',NULL,NULL,'2026-08-23 12:10:31');
INSERT INTO `tbl_student_progress` VALUES ('48','2','0',NULL,'lesson','18','completed',NULL,NULL,NULL,NULL,'0','2026-08-24 15:11:54',NULL,NULL,'2026-08-24 01:11:54');
INSERT INTO `tbl_student_progress` VALUES ('50','2','0',NULL,'lesson','37','completed',NULL,NULL,NULL,NULL,'0','2026-08-25 10:49:53',NULL,NULL,'2026-08-25 10:49:53');
INSERT INTO `tbl_student_progress` VALUES ('52','2','0',NULL,'lesson','38','completed',NULL,NULL,NULL,NULL,'0','2026-08-25 10:50:11',NULL,NULL,'2026-08-25 10:50:11');
INSERT INTO `tbl_student_progress` VALUES ('53','2','0',NULL,'lesson','36','completed',NULL,NULL,NULL,NULL,'0','2026-08-25 10:50:17',NULL,NULL,'2026-08-25 10:50:17');
INSERT INTO `tbl_student_progress` VALUES ('54','2','0',NULL,'lesson','40','completed',NULL,NULL,NULL,NULL,'0','2026-08-26 11:27:32',NULL,NULL,'2026-08-26 11:27:32');
INSERT INTO `tbl_student_progress` VALUES ('72','2','0',NULL,'lesson','52','completed',NULL,NULL,NULL,NULL,'0','2026-08-31 17:28:28',NULL,NULL,'2026-08-31 03:28:28');
INSERT INTO `tbl_student_progress` VALUES ('74','2','0',NULL,'lesson','49','completed',NULL,NULL,NULL,NULL,'0','2026-08-31 19:24:11',NULL,NULL,'2026-08-31 05:24:11');
INSERT INTO `tbl_student_progress` VALUES ('77','2','0',NULL,'lesson','57','completed',NULL,NULL,NULL,NULL,'0','2026-09-03 08:43:07',NULL,NULL,'2026-09-03 08:43:07');
INSERT INTO `tbl_student_progress` VALUES ('84','2','0',NULL,'lesson','53','completed',NULL,NULL,NULL,NULL,'0','2026-09-03 14:23:15',NULL,NULL,'2026-09-03 14:23:15');
INSERT INTO `tbl_student_progress` VALUES ('95','2','0',NULL,'lesson','41','completed',NULL,NULL,NULL,NULL,'0','2026-09-03 14:47:10',NULL,NULL,'2026-09-03 14:47:10');
INSERT INTO `tbl_student_progress` VALUES ('108','2','0',NULL,'lesson','35','completed',NULL,NULL,NULL,NULL,'0','2026-09-05 01:45:58',NULL,NULL,'2026-09-05 01:45:58');

DROP TABLE IF EXISTS `tbl_students`;
CREATE TABLE `tbl_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_LRN` varchar(255) NOT NULL,
  `master_lrn_id` int(11) DEFAULT NULL,
  `grade_level_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_students_master_lrn` (`master_lrn_id`),
  KEY `fk_students_grade` (`grade_level_id`),
  KEY `fk_students_section` (`section_id`),
  CONSTRAINT `fk_students_grade` FOREIGN KEY (`grade_level_id`) REFERENCES `tbl_grade_level` (`id`),
  CONSTRAINT `fk_students_master_lrn` FOREIGN KEY (`master_lrn_id`) REFERENCES `tbl_master_lrn` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_students_section` FOREIGN KEY (`section_id`) REFERENCES `tbl_sections` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_students` VALUES ('3','107908100331','83','2','3','7','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('4','107908100931','96','2','3','8','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('5','107908100404','86','2','3','9','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('6','107908100428','102','2','3','10','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('7','107908100519','97','2','3','11','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('8','107908100970','84','2','3','12','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('9','107908100840','90','2','3','13','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('10','107908100049','88','2','3','14','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('11','107908100219','98','2','3','15','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('12','107908100596','94','2','3','16','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('13','107908100038','99','2','3','17','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_students` VALUES ('14','107908100429','73','2','3','18','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('15','107908100444','101','2','3','19','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('16','107908100548','91','2','3','20','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('17','107908100088','100','2','3','21','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('18','107908100284','77','2','3','22','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('19','107908100074','89','2','3','23','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('20','107908100374','93','2','3','24','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('21','107908100777','81','2','3','25','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('22','107908100096','92','2','3','26','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('23','107908100154','85','2','3','27','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('24','107908100059','95','2','3','28','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_students` VALUES ('25','107908100666','87','2','3','29','2026-09-05 06:45:54','2026-09-05 06:45:54');

DROP TABLE IF EXISTS `tbl_subject_section_access`;
CREATE TABLE `tbl_subject_section_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_section_subject` (`section_id`,`subject_id`),
  KEY `idx_section` (`section_id`),
  KEY `idx_subject` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_subject_section_access` VALUES ('1','3','5','0','2026-09-13 12:13:24');
INSERT INTO `tbl_subject_section_access` VALUES ('3','1','11','0','2026-09-13 08:56:55');
INSERT INTO `tbl_subject_section_access` VALUES ('7','3','9','0','2026-09-13 12:03:47');
INSERT INTO `tbl_subject_section_access` VALUES ('11','3','3','0','2026-09-13 12:13:24');
INSERT INTO `tbl_subject_section_access` VALUES ('44','3','4','0','2026-09-13 12:13:24');
INSERT INTO `tbl_subject_section_access` VALUES ('105','3','1','1','2026-09-13 12:27:33');
INSERT INTO `tbl_subject_section_access` VALUES ('108','4','1','1','2026-09-14 05:59:04');
INSERT INTO `tbl_subject_section_access` VALUES ('109','2','2','0','2026-09-13 13:20:25');

DROP TABLE IF EXISTS `tbl_subjects`;
CREATE TABLE `tbl_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_level_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_image` varchar(255) NOT NULL,
  `subject_description` varchar(255) NOT NULL,
  `curriculum_type` enum('legacy','new') NOT NULL DEFAULT 'legacy',
  `strand_group` varchar(60) DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 0,
  `subject_code` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `grade_level_id` (`grade_level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_subjects` VALUES ('1','2','Computer System Servicing','uploads/subjects/subj_6a3fba1b0758d.jpg','Computer System Servicing (CSS) provides interactive lessons, activities, simulations, and assessments that help students develop essential computer hardware, software, and networking skills.','new','Elective Cluster — CSS','0','css','2026-06-26 03:31:32','2026-09-13 05:29:15');
INSERT INTO `tbl_subjects` VALUES ('2','1','Computer System Servicing','uploads/subjects/subj_6a9e81523cfe0.jpg','Computer System Servicing (CSS) provides interactive lessons, activities, simulations, and assessments that help students develop essential computer hardware, software, and networking skills.','new','Elective Cluster — CSS','0','css_11','2026-09-07 03:18:10','2026-09-13 05:29:15');
INSERT INTO `tbl_subjects` VALUES ('3','2','Filipino','','','legacy','Core (all strands)','0','','2026-09-08 08:00:06','2026-09-13 05:53:51');
INSERT INTO `tbl_subjects` VALUES ('4','2','PE','','','legacy','Core (all strands)','0','','2026-09-08 08:03:57','2026-09-13 05:53:51');
INSERT INTO `tbl_subjects` VALUES ('5','2','Physical Science','','','legacy','Core (all strands)','0','','2026-09-08 08:06:09','2026-09-13 05:53:51');
INSERT INTO `tbl_subjects` VALUES ('6','2','Computer Programming','','','new','Elective Cluster — CSS','0','','2026-09-08 08:08:12','2026-09-13 05:29:15');
INSERT INTO `tbl_subjects` VALUES ('7','2','Computer System','','','new','Elective Cluster — CSS','0','','2026-09-08 08:08:30','2026-09-13 05:29:15');
INSERT INTO `tbl_subjects` VALUES ('8','1','Intro to Computing','','','new','Elective Cluster — CSS','0','','2026-09-08 08:12:43','2026-09-13 05:29:15');
INSERT INTO `tbl_subjects` VALUES ('9','2','Physical Store','','','legacy','ABM Strand','0','','2026-09-08 08:18:02','2026-09-13 05:53:51');
INSERT INTO `tbl_subjects` VALUES ('10','1','Computer','','','new','Elective Cluster — CSS','0','','2026-09-08 08:18:18','2026-09-13 05:29:15');
INSERT INTO `tbl_subjects` VALUES ('11','1','Capstone','','','legacy','Core (all strands)','1','','2026-09-08 08:18:37','2026-09-13 09:05:19');

DROP TABLE IF EXISTS `tbl_system_backups`;
CREATE TABLE `tbl_system_backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `file_path` varchar(500) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'success',
  `type` varchar(20) DEFAULT NULL,
  `size_mb` decimal(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_system_backups` VALUES ('1','backup_20260915_165114.sql','C:\\xampp\\htdocs\\learning_management/uploads/backups/backup_20260915_165114.sql','2026-09-15 08:51:14','success','manual','0.27','1');

DROP TABLE IF EXISTS `tbl_teacher_assignments`;
CREATE TABLE `tbl_teacher_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `grade_level_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `join_code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `subject_id` (`subject_id`),
  KEY `grade_level_id` (`grade_level_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_teacher_assignments` VALUES ('1','1','1','2','3','','DZII8EF');
INSERT INTO `tbl_teacher_assignments` VALUES ('2','1','1','2','4','','ZH3DJSP');
INSERT INTO `tbl_teacher_assignments` VALUES ('5','2','1','2','4','','V4VJ236');
INSERT INTO `tbl_teacher_assignments` VALUES ('6','2','2','1','2','','LOPUQ1L');

DROP TABLE IF EXISTS `tbl_teachers`;
CREATE TABLE `tbl_teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_teachers` VALUES ('1','3');
INSERT INTO `tbl_teachers` VALUES ('2','30');

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_users` VALUES ('1','superadmin123','superadmin123','$2y$10$Zd8Ak/vLI3C2P788t8YW6O50g2ilDoYPd0BqM0QW/TB9GaXvkIBXm','superadmin','2026-06-26 01:53:39','2026-06-26 01:53:39');
INSERT INTO `tbl_users` VALUES ('2','admin','admin123','$2y$10$EOtfxgNfR3UNxww50AOH7.u4k.UKK4ChRNr9yjvVJG5doOTlITK4m','admin','2026-06-26 03:42:18','2026-06-26 03:42:18');
INSERT INTO `tbl_users` VALUES ('3','Manny Zuniga','mannyzuniga123','$2y$10$8jOUJ77R80NCVvTMvapgl.mlIMdBqSouvIZ4wOsE463xLk23Ivlc6','teacher','2026-06-26 04:27:38','2026-06-26 04:27:38');
INSERT INTO `tbl_users` VALUES ('5','rogelioamoyan','rogelioamoyan','$2y$10$w.a/BzTS4RBgsy/56/3UHeaUXvd0ynMInK8GqwB97RgH33Ey.kJfm','student','2026-06-26 06:43:52','2026-06-26 06:43:52');
INSERT INTO `tbl_users` VALUES ('6','Rogelio Amoyan','107908100331','$2y$10$fZKSmsq82jTG/AheOVGzd.OyaGOSuxKuqDy4Oe.16gF2ZLs.WxAxq','student','2026-09-05 06:11:12','2026-09-05 06:11:12');
INSERT INTO `tbl_users` VALUES ('7','Rogelio Amoyan','107908100331','$2y$10$LVBS2MVlc5xO/23KKVneEu.4yfF2K1w353uoDBA/XX23sPw4p2S5G','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('8','Sofia Aquino','107908100931','$2y$10$aNTDVjyE5fFh.6XtgTmEQe1euJjfdhbb5mZ8g4N6RkuHrtR2BK8hq','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('9','Anna Bautista','107908100404','$2y$10$RpcItWuEHlAS3qHgbx4hYeSU3K8sgphUKdA51xdY7isFw9tlDwLQ2','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('10','Nicole Castillo','107908100428','$2y$10$3jbQuPz6PQSAqQHyZclj8uy9Lpk1TUM/TlzoWGOAERxpUGIB/VcA2','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('11','Diego Cruz','107908100519','$2y$10$iFWl0DsYgH9Wz2eEE8NQkuAlIqHpLhRBiPRQRmdMrsVfjtzY2vKSu','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('12','Maria Dela Cruz','107908100970','$2y$10$ENnpDpRQf8aVzRwP0x/lM.P16k0CMy1nnVkeoVTCfrwi.cEOyvKn6','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('13','Angel Domingo','107908100840','$2y$10$uor7RVo.oNDOVGWpAzaUUunJrk1ZIRLVKWqIHmVvLTnTjbNvbr8Be','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('14','Kristine Fernandez','107908100049','$2y$10$28pKHOD7HDIzc4KefSHaH.d3WscXc6kxeFaHEgbV4HdkRjC3kocji','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('15','Camille Flores','107908100219','$2y$10$zVWt9vFHFRkBBO77hmAqCe.AWaCcpkXpBSz9/FwK3hmGynOA3xy2u','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('16','Bianca Garcia','107908100596','$2y$10$4wnxG4lsJx8Zc5HJlvb/qu8kpOJRR6RWDCIGT6fU.7ZgyRfbViFl.','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('17','Rafael Gonzales','107908100038','$2y$10$QrCjwWBSH/85x1q.j9.cNe5Rv2O8IZDOgz2jZ8Xa8d7Pwupz1NwYa','student','2026-09-05 06:45:53','2026-09-05 06:45:53');
INSERT INTO `tbl_users` VALUES ('18','Renz Javier','107908100429','$2y$10$QZ..a2Yn28Lg/u/1jLH6IezVk2KEyYAXUEmiiehcn5qmAJUYGuphy','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('19','Gabriel Lopez','107908100444','$2y$10$MiMl0yjh5C0ikjMNOtypuOw4aMMGrE/zqqfzlGh206wm0oUIjm1P6','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('20','Patricia Mendoza','107908100548','$2y$10$sABp8RH5si/rXYkGlg0UNeYMCVMHw5n/GY2fiBR3T1d.b33X8n.3G','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('21','Andrea Navarro','107908100088','$2y$10$wJiG9UhN9BefIYAlKAln4eVXL9159Pv9Qg1vYauR1TNku9pBMKjPG','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('22','Nathaniel Nolasco','107908100284','$2y$10$nkGSuwK8GeHFdKcbBL9lJ.xWHoGR45kGQ7.WEtCYm9FH.s0rQ3jyu','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('23','Joshua Ramirez','107908100074','$2y$10$uHdyj6mAsSSpBXjxh1wXYOcnOBqLp7SBFlGv3NvhVNRSDcke/kXjq','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('24','Carlos Reyes','107908100374','$2y$10$v1kv/sBqZ15/CfOFQjvk5.VUb7KjT4hpg9mWKECo.pAKe94n/NJ9e','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('25','Marco Rosales','107908100777','$2y$10$rWZYL5sN4QPZWXDiPZSi5uyHs0ZkKgD0j2/FTapSIGGN1/yrryv4K','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('26','Kevin Salazar','107908100096','$2y$10$MorER05vlmHZsL1B8ITL2OQ2oEzafk6DqRtNDE3eVdMqWTQvtvz6y','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('27','Juan Santos','107908100154','$2y$10$ok.Wq9YYaq/LV0hR5HgBU.Bs5J/eEn0371Qe2oIZbzm.Q/.KCKiGG','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('28','Miguel Torres','107908100059','$2y$10$h23YomD176qYMm0pU2bOluywt7zwziJ7JygdPhvW0hFqNPK6XRB/C','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('29','Mark Villanueva','107908100666','$2y$10$GZ05cA7caW6sY3MmMrx1kOXWXJe3DhhmNz9MGNRWQ2t/DJyiQTJGy','student','2026-09-05 06:45:54','2026-09-05 06:45:54');
INSERT INTO `tbl_users` VALUES ('30','Randolph Balleras','randolph123','$2y$10$3RjQl5DyGoIljvN6YJ9a5ORMwjaf0oqq/FJp0uSkdwHbcdO8vetNq','teacher','2026-09-07 03:36:33','2026-09-07 03:36:33');
INSERT INTO `tbl_users` VALUES ('31','next_admin','next_admin','$2y$10$KZMBeYDnDuLgdlIiVnaENuoTVQtQwdVSmkBbwqZ09oQvyU4sxIOPS','admin','2026-09-15 04:10:16','2026-09-15 04:10:16');

SET FOREIGN_KEY_CHECKS=1;
