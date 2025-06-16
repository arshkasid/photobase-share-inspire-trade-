-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 16, 2025 at 08:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `photobase`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `post_id` int(11) NOT NULL,
  `username` longtext NOT NULL,
  `comment` longtext NOT NULL,
  `commented_on` date NOT NULL DEFAULT current_timestamp(),
  `c_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`post_id`, `username`, `comment`, `commented_on`, `c_id`) VALUES
(1, 'arsh', 'nice', '2025-06-16', 1),
(1, 'arsh', 'really cheap', '2025-06-16', 2),
(3, 'arsh', 'intrested', '2025-06-16', 3),
(2, 'ashwani', 'ok', '2025-06-16', 4),
(3, 'ashwani', 'intrested', '2025-06-16', 5);

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `name` longtext NOT NULL,
  `members` bigint(20) NOT NULL,
  `id` int(11) NOT NULL,
  `picture` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communities`
--

INSERT INTO `communities` (`name`, `members`, `id`, `picture`) VALUES
('Street Photography Collective', 2, 1, 'NEON.PNG'),
('Nature Lens Club', 1, 2, 'Nature Lens Club.png'),
('Portrait Pros', 0, 3, 'Portrail Pros.png'),
('Urban Explorers', 1, 4, 'Urban Explorers.png'),
('Wildlife Watchers', 0, 5, 'Wildlife Watchers.png'),
('Black & White Masters', 0, 6, 'Black & White Masters.png'),
('Travel Shutterbugs', 0, 7, 'Travel Shutterbugs.png'),
('Minimalist Moments', 0, 8, 'Minimalist Moments.png'),
('Nightscape Society', 0, 9, 'Nightscape Society.png'),
('Macro Magic', 0, 10, 'Macro Magic.png'),
('Film & Vintage Shooters', 0, 11, 'Film & Vintage Shooters.png'),
('Landscape Legends', 0, 12, 'Landscape Legends.png'),
('Creative Compositions', 0, 13, 'Creative Compositions.png'),
('Photo Gearheads', 0, 14, 'Photo Gearheads.png'),
('Newbie Photographers Hub', 0, 15, 'Newbie Photographers Hub.png'),
('Event & Wedding Shooters', 0, 16, 'Event & Wedding Shooters.png'),
('Drone Photography Group', 0, 17, 'Drone Photography Group.png'),
('Food Photography Network', 0, 18, 'Food Photography Network.png'),
('Fashion & Editorial Collective', 0, 19, 'Fashion & Editorial Collective.png'),
('Abstract Artistry', 0, 20, 'Abstract Artistry.png'),
('Upcycle Gear', 1, 21, 'Upcycle Gear.png');

-- --------------------------------------------------------

--
-- Table structure for table `following`
--

CREATE TABLE `following` (
  `follower` longtext NOT NULL,
  `following` longtext NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `following`
--

INSERT INTO `following` (`follower`, `following`, `id`) VALUES
('arsh', 'ashwani', 2),
('ashwani', 'arsh', 3),
('nimish', 'arsh', 4);

-- --------------------------------------------------------

--
-- Table structure for table `join_comm`
--

CREATE TABLE `join_comm` (
  `username` longtext NOT NULL,
  `community` longtext NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `join_comm`
--

INSERT INTO `join_comm` (`username`, `community`, `id`) VALUES
('ashwani', 'Street Photography Collective', 1),
('ashwani', 'Upcycle Gear', 2),
('ashwani', 'Urban Explorers', 3),
('ashwani', 'Nature Lens Club', 4),
('nimish', 'Street Photography Collective', 7);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` longtext NOT NULL,
  `receiver` longtext NOT NULL,
  `message` longtext NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `receiver`, `message`, `created_at`) VALUES
(1, 'arsh', 'ashwani', 'hi', '2025-06-16'),
(2, 'ashwani', 'arsh', 'how are you\\r\\n', '2025-06-16'),
(3, 'arsh', 'ashwani', 'do you want gears?\\r\\n', '2025-06-16'),
(4, 'arsh', 'ashwani', 'test \\r\\n', '2025-06-16'),
(5, 'arsh', 'ashwani', 'test', '2025-06-16'),
(6, 'arsh', 'ashwani', 'test', '2025-06-16'),
(7, 'arsh', 'ashwani', 'test', '2025-06-16'),
(8, 'arsh', 'ashwani', 'test', '2025-06-16'),
(9, 'arsh', 'ashwani', 'test', '2025-06-16'),
(10, 'arsh', 'ashwani', 'test', '2025-06-16'),
(11, 'arsh', 'ashwani', 'does it work?', '2025-06-16'),
(12, 'ashwani', 'nimish', 'hi', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `type` varchar(100) NOT NULL,
  `caption` longtext NOT NULL,
  `picture` longtext DEFAULT NULL,
  `price` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `title` varchar(200) NOT NULL,
  `community` longtext NOT NULL DEFAULT 'none',
  `tags` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `username`, `type`, `caption`, `picture`, `price`, `created_at`, `title`, `community`, `tags`) VALUES
(1, 'arsh', 'picture', 'hi this is a picture post', 'mirrorless-camera-2048px-9621.png', 0, '2025-06-16', '', 'none', ''),
(2, 'arsh', 'text', 'this is just a text one', '0', 0, '2025-06-16', '', 'none', ''),
(3, 'arsh', 'ad', 'selling my mind condition z7', 'mirrorless-camera-2048px-9621.png', 960000, '2025-06-16', 'selling camera', 'none', ''),
(4, 'nimish', 'text', 'im planning a trip for ranthambore, anyonne intrested?', '', 0, '2025-06-16', '', 'Travel Shutterbugs', 'trip, tiger, ranthambore'),
(5, 'nimish', 'picture', 'look at this tiger i captured', 'post_685042fc302601.51940734.png', 0, '2025-06-16', '', '', 'tiger, photo, wild'),
(6, 'nimish', 'ad', 'nikon z7 lens', 'post_6850492d8ce246.67481190.jpeg', 25000, '2025-06-16', 'selling a lens', 'Upcycle Gear', 'lens, nikon, sell'),
(7, 'nimish', 'text', 'searching for a nice street with dark aesthetics. any leads?', '', 0, '2025-06-16', '', 'Street Photography Collective', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `bio` longtext NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `password` longtext NOT NULL,
  `picture` varchar(200) NOT NULL DEFAULT 'default.png',
  `email` varchar(200) NOT NULL,
  `ig` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `name`, `bio`, `mobile`, `password`, `picture`, `email`, `ig`) VALUES
('arsh', 'arsh k', 'hahahahahhahhaa', 8527621952, '1234', 'images.jpg', 'arshkasid046@gmail.com', 'arsh.png'),
('ashwani', 'ashwani kasid', 'this is new bio', 9999724567, '1234', 'mirrorless-camera-2048px-9621.png', 'arshkasid@gmail.com', ''),
('nimish', 'nimish medatwal', '', 1234567890, '1234', 'NEON.PNG', 'nim@gmail.com', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `following`
--
ALTER TABLE `following`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `join_comm`
--
ALTER TABLE `join_comm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `following`
--
ALTER TABLE `following`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `join_comm`
--
ALTER TABLE `join_comm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
