CREATE DATABASE task_manager;
USE task_manager;

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(55) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `created_at`) VALUES
(5, 1, 'sdafsdfa', 'sadff', 'completed', '2025-03-27 00:01:21'),
(6, 1, 'gym', 'daily go gym', 'completed', '2025-03-27 00:01:32'),
(7, 1, 'study', 'study', 'pending', '2025-03-27 00:32:24'),
(9, 3, 'sfasfd', 'sadfsdf', 'pending', '2025-03-27 01:48:14');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_img` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `profile_img`, `bio`, `created_at`) VALUES
(1, 'nikhil', 'nikhil@gmail.com', NULL, '$2y$10$Gj0FY4.IMt2O/Ees6Xqhe.h1lOeN3X.0a7t4WBSce2NIO0.MQlCEm', '', NULL, '2025-03-26 02:27:45'),
(2, 'rahul', 'rahulkumar@gmail.com', '9876543120', '$2y$10$D7.p8fFUK4IdGaOWnB6oxuycevXuv.IAZpl111zbmRZzn9G0iBKJ6', '../public/uploads/1743038608_pngwing.com (28).png', 'i am a web developer', '2025-03-27 00:41:05'),
(3, 'afzal', 'afzal@gmail.com', NULL, '$2y$10$lxbrLsVW5Z5U8Y/BJFIQlOX14kSafAXXXGHkpTEcyVh9FSuJYYV9W', '', NULL, '2025-03-27 01:47:33');

