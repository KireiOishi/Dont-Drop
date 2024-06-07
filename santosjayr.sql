SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `course_id` INT,
    `section_id` INT,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
);


CREATE TABLE `sections` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `section_name` VARCHAR(255) NOT NULL
);


CREATE TABLE `student_activities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT,
    `activity_type` ENUM('quiz', 'activity', 'exam', 'attendance') NOT NULL,
    `score` INT DEFAULT NULL,
    `datetime` DATETIME NOT NULL,
   

    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);


CREATE TABLE `courses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_name` VARCHAR(100) NOT NULL,
    `description` TEXT
);

CREATE TABLE `subjects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `subject_name` VARCHAR(100) NOT NULL,
    `course_id` INT,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);



CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirmation_status` enum('not confirmed','confirmed') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `confirmation_code` varchar(50) NOT NULL,
  `forgot_password_code` varchar(40) NOT NULL,
  `block_status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `user` (`user_id`, `first_name`, `last_name`, 'subject' `email`, `password`, `confirmation_status`, `profile_picture`, `address`, `contact_info`, `confirmation_code`, `forgot_password_code`, `block_status`) VALUES

(81, 'jayr', 'santos', 'caps' 'sample@gmail.com', 'aa', 'confirmed', NULL, 'bustos', '999', 'd3s1a', 'fd53s', 0);

