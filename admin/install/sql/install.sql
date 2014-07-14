SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
DELETE FROM `#__jshopping_payment_method` WHERE `payment_code` = 'zarinpalzg';

INSERT INTO `#__jshopping_payment_method` (`payment_id`, `name_en-GB`, `name_de-DE`, `description_en-GB`, `description_de-DE`, `payment_code`, `payment_class`, `payment_publish`, `payment_ordering`, `payment_params`, `payment_type`, `tax_id`, `price`, `show_descr_in_email`,`name_fa-IR`,`description_fa-IR`) VALUES
(NULL, 'zarinpalzg','zarinpalzg', '', '', 'zarinpalzg','pm_zarinpalzg', 0, 5, 'acc=joomina\n transaction_end_status=6\n transaction_pending_status=1\n transaction_failed_status=3\n checkdatareturn=0', 2, 1, 0, 0,'zarinpalzg', '');

