/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.4.14-MariaDB : Database - exemples
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`exemples` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `exemples`;

/*Table structure for table `atores` */

DROP TABLE IF EXISTS `atores`;

CREATE TABLE `atores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `atores` */

/*Table structure for table `empregados` */

DROP TABLE IF EXISTS `empregados`;

CREATE TABLE `empregados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_escritorio` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `salario` decimal(7,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_EMPREGADOS_ESCRITORIOS` (`id_escritorio`),
  CONSTRAINT `FK_EMPREGADOS_ESCRITORIOS` FOREIGN KEY (`id_escritorio`) REFERENCES `escritorios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `empregados` */

/*Table structure for table `escritorios` */

DROP TABLE IF EXISTS `escritorios`;

CREATE TABLE `escritorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cidade` varchar(150) NOT NULL,
  `telefone` varchar(50) NOT NULL,
  `endereco` varchar(200) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

/*Data for the table `escritorios` */

insert  into `escritorios`(`id`,`cidade`,`telefone`,`endereco`,`created_at`,`updated_at`,`deleted_at`) values (2,'ribeirao','16991838523','xxxxx','2021-01-13 08:47:33','2021-01-13 08:50:21','2021-01-13 08:50:21'),(3,'ribeirao','16991838523','xxxxx','2021-01-13 11:46:09','2021-01-13 12:12:34','2021-01-13 12:12:34'),(4,'Ribeirão Preto','+5516991838523','Rua Álvares Cabral','2021-01-13 11:59:46','2021-01-13 12:14:26','2021-01-13 12:14:26'),(5,'ribeirao','16991838523','xxxxx','2021-01-13 12:15:25','2021-01-13 12:15:44','2021-01-13 12:15:44'),(6,'Ribeirão Preto','+5516991838523','Rua Álvares Cabral','2021-01-13 13:06:40','2021-01-13 13:31:20','2021-01-13 13:31:20'),(7,'Ribeirão Preto','+5516991838523','Rua Álvares Cabral','2021-01-13 13:51:26','2021-01-13 13:52:29','2021-01-13 13:52:29'),(8,'16','+5516991838523','xxxxx','2021-01-13 13:55:01','2021-01-13 13:56:05','2021-01-13 13:56:05'),(9,'ribeirao','16991838523','xxxxx','2021-01-14 05:29:13','2021-01-14 05:29:33','2021-01-14 05:29:33'),(10,'ribeirao preto','16991838523','xxxxx','2021-01-14 05:33:30','2021-01-14 05:50:39','2021-01-14 05:50:39'),(11,'Ribeirão Preto','+5555991838523','Rua Álvares Cabral','2021-01-14 05:43:00','2021-01-14 05:50:43','2021-01-14 05:50:43'),(12,'Ribeirão Preto','+5555991838523','Rua Álvares Cabral','2021-01-14 05:43:48','2021-01-14 05:50:45','2021-01-14 05:50:45'),(13,'Ribeirão Preto','+5516991838523','Rua Álvares Cabral','2021-01-14 05:43:55','2021-01-14 05:50:48','2021-01-14 05:50:48'),(14,'Ribeirão Preto','+5516991838523','Rua Álvares Cabral','2021-01-14 05:48:08','2021-01-14 05:50:59','2021-01-14 05:50:59'),(15,'ribeirao','16991838523','xxxxx','2021-01-14 05:48:22','2021-01-14 05:51:02','2021-01-14 05:51:02'),(16,'Ribeirão Preto','+5516991838523','Rua Álvares Cabral','2021-01-14 05:49:14','2021-01-14 05:51:05','2021-01-14 05:51:05'),(17,'ribeirao','+5516999999999','xxxxx','2021-01-14 05:50:15','2021-01-14 05:51:08','2021-01-14 05:51:08'),(18,'PONTAL','+5516991838523','Rua Álvares Cabral','2021-01-14 08:48:31','2021-01-14 08:50:09',NULL);

/*Table structure for table `filme` */

DROP TABLE IF EXISTS `filme`;

CREATE TABLE `filme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `ano` varchar(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `filme` */

/*Table structure for table `filme_atores` */

DROP TABLE IF EXISTS `filme_atores`;

CREATE TABLE `filme_atores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_filme` int(11) NOT NULL,
  `id_atores` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_FILME` (`id_filme`),
  KEY `FK_ATORES` (`id_atores`),
  CONSTRAINT `FK_ATORES` FOREIGN KEY (`id_atores`) REFERENCES `atores` (`id`),
  CONSTRAINT `FK_FILME` FOREIGN KEY (`id_filme`) REFERENCES `filme` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `filme_atores` */

/*Table structure for table `log` */

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabela` varchar(255) NOT NULL,
  `evento` enum('insert','update','delete') NOT NULL,
  `values` varchar(4000) DEFAULT NULL,
  `chave` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

/*Data for the table `log` */

insert  into `log`(`id`,`tabela`,`evento`,`values`,`chave`) values (19,'escritorios','insert','Array\n(\n    [action] => Add\n    [id] => \n    [cidade] => RIBEIRãO PRETO\n    [telefone] => +5516991838523\n    [endereco] => Rua Álvares Cabral\n)\n',18),(20,'escritorios','update','Array\n(\n    [action] => Edit\n    [id] => 18\n    [cidade] => pontal\n    [telefone] => +5516991838523\n    [endereco] => Rua Álvares Cabral\n)\n',18),(21,'escritorios','update','Array\n(\n    [action] => Edit\n    [id] => 18\n    [cidade] => PONTAL\n    [telefone] => +5516991838523\n    [endereco] => Rua Álvares Cabral\n)\n',18);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
