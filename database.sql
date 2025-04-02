-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for my_store
CREATE DATABASE IF NOT EXISTS `my_store` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `my_store`;

-- Dumping structure for table my_store.account
CREATE TABLE IF NOT EXISTS `account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `fullname` (`fullname`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store.account: ~5 rows (approximately)
INSERT INTO `account` (`id`, `username`, `fullname`, `password`, `role`) VALUES
	(1, 'user1', 'user', '$2y$12$6ToOlhnnGMOs8a4xwha21.Xyahy90r./fGEPd20P96.gSp0pt6Be2', 'admin'),
	(2, 'admin1', 'admin', '2', 'admin'),
	(3, 'user2', 'User2', '$2y$12$JsdP4iL2O.AD0anz1wnqdOJgC7N25msNzGtr9YZvEtjfneel4sROu', 'user'),
	(5, 'test', 'Test', '$2y$12$rl6lXPg1SLqtZRBLogiwr.cRT5tKmrimGMmojcwkQqWmGUTfhZ42G', 'user'),
	(6, 'admin2', 'Julez Duc', '$2y$12$vGuO8XDyC5Zi/BXAwP8e7ueITp92bvdHyf3KJwBG19ET.k4eViGXC', 'admin'),
	(7, 'uoi', 'skibidi', '$2y$12$.Od4p8i4VLcbZcHn0dKB.e0LZenGXKCOCU56v0whn5AYWzLCQ4LnK', 'user');

-- Dumping structure for table my_store.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store.category: ~5 rows (approximately)
INSERT INTO `category` (`id`, `name`, `description`) VALUES
	(1, 'Văn học', 'Danh mục sách văn học, tiểu thuyết.'),
	(2, 'Khoa học', 'Danh mục sách khoa học và nghiên cứu.'),
	(3, 'Kinh tế', 'Danh mục sách kinh tế, tài chính.'),
	(4, 'Công nghệ', 'Danh mục sách về công nghệ, lập trình.'),
	(5, 'Thiếu nhi', 'Danh mục sách dành cho trẻ em.');

-- Dumping structure for table my_store.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store.orders: ~5 rows (approximately)
INSERT INTO `orders` (`id`, `name`, `phone`, `address`, `created_at`) VALUES
	(1, 'Nguyen Van A', '0909099099', 'thủ đức', '2025-03-18 20:12:30'),
	(2, 'Nguyen Van A', '9823456365', 'RTR', '2025-03-18 20:31:36'),
	(3, 'Nguyen Thi T', '9823456365', 'ưerfw', '2025-03-18 20:37:18'),
	(4, 'Nguyen Van A', '9823456365', 'l', '2025-03-18 20:58:50'),
	(5, 'Nguyen Van A', '12312321', 'ưerw', '2025-03-18 21:06:42');

-- Dumping structure for table my_store.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store.order_details: ~7 rows (approximately)
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
	(1, 1, 1, 3, 150000.00),
	(2, 1, 2, 1, 200000.00),
	(3, 1, 5, 1, 250000.00),
	(4, 2, 4, 1, 180000.00),
	(5, 3, 2, 1, 200000.00),
	(6, 4, 2, 1, 200000.00),
	(7, 5, 1, 1, 150000.00);

-- Dumping structure for table my_store.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT 'uploads/default-image.jpg',
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_chk_1` CHECK ((`price` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store.product: ~9 rows (approximately)
INSERT INTO `product` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
	(1, 'Đắc Nhân Tâm', 'Cuốn sách giúp cải thiện giao tiếp và ứng xử.', 150000.00, 'uploads/cuon-sach-dak-nhan-tam-dark-nhan-tam.png', 1),
	(2, 'Lập Trình Python Cơ Bản', 'Hướng dẫn lập trình Python từ cơ bản đến nâng cao.', 200000.00, 'uploads/sach_python.jpg', 4),
	(3, 'Nhà Giả Kim', 'Cuốn sách truyền cảm hứng về hành trình tìm kiếm ước mơ.', 120000.00, 'uploads/Nha_gia_kim.jpg', 1),
	(4, 'Tư Duy Nhanh và Chậm', 'Khám phá cách thức hoạt động của bộ não con người.', 180000.00, 'uploads/tuduynhanhvacham.jpg', 2),
	(5, 'Dạy Con Làm Giàu', 'Bí quyết tài chính giúp bạn làm giàu.', 250000.00, 'uploads/doi-cho-sach-jack-cho-thom.jpg', 3),
	(7, 'Thiên Tài Bên Trái, Kẻ Điên Bên Phải', 'Khám phá tâm lý học hiện đại.', 190000.00, 'uploads/thien-tai-ben-trai.jpg', 2),
	(8, 'Lập Trình Web Với PHP', 'Hướng dẫn lập trình web với PHP và MySQL.', 220000.00, 'uploads/php-mysql.jpg', 4),
	(13, 'Mắt biếc', 'adgedg', 23523.00, 'uploads/67ec908ca98b0-f4c8de8ef128b87da882a9f4747aa621.jpg', 3);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
