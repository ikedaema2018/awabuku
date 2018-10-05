-- MySQL dump 10.13  Distrib 5.5.57, for debian-linux-gnu (x86_64)
--
-- Host: 0.0.0.0    Database: c9
-- ------------------------------------------------------
-- Server version	5.5.57-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `book_tag`
--

DROP TABLE IF EXISTS `book_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_tag`
--

LOCK TABLES `book_tag` WRITE;
/*!40000 ALTER TABLE `book_tag` DISABLE KEYS */;
INSERT INTO `book_tag` VALUES (1,1,4,'2018-10-03 10:15:25','2018-10-03 10:15:25'),(2,1,3,'2018-10-03 10:15:25','2018-10-03 10:15:25'),(3,2,3,'2018-10-03 14:48:31','2018-10-03 14:48:31'),(4,3,5,'2018-10-03 15:04:08','2018-10-03 15:04:08'),(5,1,6,'2018-10-03 16:10:14','2018-10-03 16:10:14'),(6,4,7,'2018-10-03 22:36:17','2018-10-03 22:36:17'),(7,4,6,'2018-10-03 22:36:17','2018-10-03 22:36:17'),(8,2,8,'2018-10-04 09:37:26','2018-10-04 09:37:26'),(9,4,9,'2018-10-04 09:55:27','2018-10-04 09:55:27'),(10,4,10,'2018-10-04 09:55:52','2018-10-04 09:55:52'),(11,3,11,'2018-10-04 09:59:08','2018-10-04 09:59:08'),(12,3,3,'2018-10-04 09:59:08','2018-10-04 09:59:08'),(13,4,4,'2018-10-04 12:31:05','2018-10-04 12:31:05'),(14,5,11,'2018-10-04 12:31:53','2018-10-04 12:31:53'),(15,5,4,'2018-10-04 12:31:53','2018-10-04 12:31:53'),(16,6,12,'2018-10-04 14:15:07','2018-10-04 14:15:07'),(17,7,3,'2018-10-04 14:16:32','2018-10-04 14:16:32'),(18,8,13,'2018-10-04 14:17:12','2018-10-04 14:17:12'),(19,9,10,'2018-10-04 14:18:40','2018-10-04 14:18:40'),(20,10,13,'2018-10-04 14:23:27','2018-10-04 14:23:27'),(21,11,14,'2018-10-04 14:24:23','2018-10-04 14:24:23'),(22,13,15,'2018-10-04 14:26:15','2018-10-04 14:26:15');
/*!40000 ALTER TABLE `book_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `BookTitle` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `BookAuthor` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `isbn10` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `isbn13` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `PublishedDate` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `Publisher` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `BookImage` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `BookDiscription` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (1,'PHPフレームワークLaravel入門','掌田津耶乃／著','9784798052588','9784798052588','20170918','秀和システム','https://cover.openbd.jp/9784798052588.jpg','わかりやすいのに高機能とGitHubで大人気の新スタンダードPHPフレームワーク「Laravel」5.4対応の解説書です。','2018-10-03 10:15:25','2018-10-03 10:15:25'),(2,'Azure定番システム設計・実装・運用ガイド','日本マイクロソフト株式会社／著','9784822253790','9784822253790','20180907','日経ＢＰ','https://cover.openbd.jp/9784822253790.jpg','Azureサポートチーム直伝！オンプレミス資産のクラウド化のベストプラクティスを移行シナリオに沿って現場目線で実践的に解説','2018-10-03 14:48:31','2018-10-03 14:48:31'),(3,'阿・吽 ８','おかざき真里／著 阿吽社／監修','9784098600960','9784098600960','20180809','小学館','https://cover.openbd.jp/9784098600960.jpg','空海と最澄！激動の時代に立ち向かう二人  既刊重版続々！唐で留学生として学ぶ空海は期間20年の決まりを破って、日本帰国を目指す！一方、ひと足先に帰国した最澄は桓武天皇の崩御による政変に巻き込まれていく…8世紀の平安期、二人の天才・空海と最澄をおかざき真里が華麗かつ剛胆な筆致で描き出す圧倒的歴史大河ロマン第8集！  【編集担当からのおすすめ情報】  東京国立博物館特別展「仁和寺と御室派のみほとけ-天平と真言密教の名宝-」でのグッズコラボ、BS朝日「京都ぶらり歴史探訪 歩いてわかる弘法大師・空海」におかざき氏出演と話題が続いている注目作です。歴史が大きく動き始め、新たな展開を見せていく第8集、是非ご一読ください！','2018-10-03 15:04:08','2018-10-03 15:04:08'),(4,'ルーヴルの猫 上','松本大洋／著','9784091897480','9784091897480','20171030','小学館','https://cover.openbd.jp/9784091897480.jpg','猫 × 松本大洋 × ルーヴル美術館 ！  ルーヴル美術館の屋根裏に棲みついた猫達。人間から隠れて暮らしていたが、一匹の白猫がその掟を破り、冒険に出かける。絵画から聞こえる声に導かれて入った世界には…！？  【編集担当からのおすすめ情報】  猫好き、アート好き、フランス好き、に松本大洋が贈る絵本のような物語。','2018-10-03 22:36:17','2018-10-03 22:36:17'),(5,'amazon　世界最先端の戦略がわかる','成毛眞／著','9784478105054','9784478105054','20180810','ダイヤモンド社','https://cover.openbd.jp/9784478105054.jpg','書誌情報なし','2018-10-04 12:31:53','2018-10-04 12:31:53'),(6,'おもしろまじめな チャットボットをつくろう','松浦健一郎／著 司ゆき／著','9784798051642','9784798051642','20170615','秀和システム','https://cover.openbd.jp/9784798051642.jpg','無駄に技術を使ったおもしろチャットボット! !  ・おなかがすいた！ 近くにお店は？ ・カップめんを買ったら家に同じのがあった？そんな ちいさな困ったを 解決！スマホで位置情報を調べて、ネットからお店を検索してくれる。以前のチャットからどのカップめんを買ったか調べてくれる。 そんな、思いつきのアイデアからチャットで動くボットをつくろう。 そのための「ボット」と「ネット」と「プログラム」の知識を学べます。 -- スマホ、LINE、HTTP、JSON、PHP、Messaging API、Webhook、Web API、GPSなどのIT技術を使い倒して、チャットでお天気予報を教えてくれる世話焼きボット、商品を調べて安いお値段を教えてくれる節約家ボット、近くのおいしいお店を教えるグルメ君ボット、口真似でチャットを沸かせる盛り上げボット、など、いろんな技術を無駄に駆使した ボットとネットとプログラム の知識を学べる本です。本書では人工知能を扱いませんが、日々進化し、ネットで提供されている最新の Web APIや画像検索システムなどと合わせれば、無限のアイデアが実現できるでしょう。','2018-10-04 14:15:07','2018-10-04 14:15:07'),(7,'ノンデザイナーズ・デザインブック　［第4版］','RobinWilliams／著 吉川典秀／翻訳 小原司［日本語版解説］／監修・翻訳 ほか','9784839955557','9784839955557','20160630','マイナビ出版','https://cover.openbd.jp/9784839955557.jpg','デザインでない人のための、デザインの定番基本書。待望の第4版！','2018-10-04 14:16:32','2018-10-04 14:16:32'),(8,'PHP7＋MariaDB／MySQLマスターブック','永田順伸／著','9784839962340','9784839962340','20180130','マイナビ出版','https://cover.openbd.jp/9784839962340.jpg','圧倒的人気を誇るPHP解説書籍の最新版！','2018-10-04 14:17:12','2018-10-04 14:17:12'),(9,'Laravelリファレンス','川瀬裕久／著 新原雅司／著 竹澤有貴／著 松尾大／著 大村創太郎／著','9784844339458','9784844339458','2016-01','インプレス','https://cover.openbd.jp/9784844339458.jpg','Laravelは、開発の速度や利便性を重視した、Webアプリケーション開発のためのオープンソースのPHPフレームワーク。多くの機能を提供しつつ、定型コードの量がより少ない、コードの記述・可読性が高い、開発チームのスタイルに合わせられる、といった点で評価されています。本書では、Laravel Ver.5.1 LTSを中心に、各種機能や開発の基礎・実践を包括的に解説しています。','2018-10-04 14:18:40','2018-10-04 14:18:40'),(10,'JavaScript&jQueryレッスンブック = JavaScript&jQuery LESSON BOOK : ステップバイステップ形式でマスターできる : 最新jQuery対応','大津真／著','9784883377947','9784883377947','2011-12','ソシム','https://cover.openbd.jp/9784883377947.jpg','JavaScriptの知識がゼロでも基礎から覚えられる。Webのデザインに必須のJQueryもわかりやすく解説。非同期通信やWebアプリなど最新の技術もこれでわかる。','2018-10-04 14:23:27','2018-10-04 14:23:27'),(11,'クラウドでできるHTML5ハイブリッドアプリ開発 Monaca公式ガイドブック Cordova/Onsen UIで作るiOS/Android両対応アプリ','永井勝則／著 アシアル株式会社／監修','9784798140285','9784798140285','20150217','翔泳社','https://cover.openbd.jp/9784798140285.jpg','無料で使えるMonacaクラウドでiOS/Android両プラットフォームで動作するアプリを作る方法を解説。','2018-10-04 14:24:23','2018-10-04 14:24:23'),(12,'独習PHP 第3版','山田祥寛／著','9784798135472','9784798135472','20160408','翔泳社','https://cover.openbd.jp/9784798135472.jpg','PHPプログラミングの標準教科書『独習PHP』が、最新のPHP7に対応して登場！','2018-10-04 14:25:03','2018-10-04 14:25:03'),(13,'jQuery最高の教科書','株式会社シフトブレイン／著','9784797372212','9784797372212','20131127','SBクリエイティブ','https://cover.openbd.jp/9784797372212.jpg','こんな教科書、今までなかった！','2018-10-04 14:26:15','2018-10-04 14:26:15');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `category_genre` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'資金調達',1,'2018-10-03 10:10:55','2018-10-03 10:10:55'),(2,'企画',1,'2018-10-03 10:11:11','2018-10-03 10:11:11'),(3,'マネジメント',1,'2018-10-03 10:11:17','2018-10-03 10:11:17'),(4,'財務',1,'2018-10-03 10:11:25','2018-10-03 10:11:25'),(5,'プログラミング言語',2,'2018-10-03 10:11:37','2018-10-03 10:11:37'),(6,'IOT',2,'2018-10-03 10:11:47','2018-10-03 10:11:47'),(7,'統計・分析',2,'2018-10-03 10:12:02','2018-10-03 10:12:02'),(8,'アルゴリズム',2,'2018-10-03 10:12:11','2018-10-03 10:12:11'),(9,'設計',2,'2018-10-03 10:12:25','2018-10-03 10:12:25'),(10,'ＩＴ企業',3,'2018-10-03 10:12:46','2018-10-03 10:12:46'),(11,'リフレッシュ',4,'2018-10-03 10:13:04','2018-10-03 10:13:04'),(12,'モチベーションアップ',4,'2018-10-03 10:13:12','2018-10-03 10:13:12');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_genres`
--

DROP TABLE IF EXISTS `category_genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_genres` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_genrename` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_genres`
--

LOCK TABLES `category_genres` WRITE;
/*!40000 ALTER TABLE `category_genres` DISABLE KEYS */;
INSERT INTO `category_genres` VALUES (1,'起業を目指す','2018-10-03 10:09:05','2018-10-03 10:09:05'),(2,'プログラミングを学ぶ','2018-10-03 10:09:14','2018-10-03 10:09:14'),(3,'G’s生の教養を磨く','2018-10-03 10:09:38','2018-10-03 10:09:38'),(4,'気持ちのマネジメント','2018-10-03 10:10:26','2018-10-03 10:10:26');
/*!40000 ALTER TABLE `category_genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_lists`
--

DROP TABLE IF EXISTS `category_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_lists`
--

LOCK TABLES `category_lists` WRITE;
/*!40000 ALTER TABLE `category_lists` DISABLE KEYS */;
INSERT INTO `category_lists` VALUES (1,1,5,'2018-10-03 10:15:25','2018-10-03 10:15:25'),(2,2,5,'2018-10-03 14:48:31','2018-10-03 14:48:31'),(3,3,1,'2018-10-03 15:04:08','2018-10-03 15:04:08'),(4,1,1,'2018-10-03 16:10:14','2018-10-03 16:10:14'),(5,4,1,'2018-10-03 22:36:17','2018-10-03 22:36:17'),(6,2,4,'2018-10-04 09:37:26','2018-10-04 09:37:26'),(7,3,5,'2018-10-04 09:59:08','2018-10-04 09:59:08'),(8,4,5,'2018-10-04 12:31:05','2018-10-04 12:31:05'),(9,5,5,'2018-10-04 12:31:53','2018-10-04 12:31:53'),(10,5,1,'2018-10-04 12:32:42','2018-10-04 12:32:42'),(11,6,1,'2018-10-04 14:15:07','2018-10-04 14:15:07'),(12,7,5,'2018-10-04 14:16:32','2018-10-04 14:16:32'),(13,8,10,'2018-10-04 14:17:12','2018-10-04 14:17:12'),(14,9,11,'2018-10-04 14:18:40','2018-10-04 14:18:40'),(15,10,10,'2018-10-04 14:23:27','2018-10-04 14:23:27'),(16,11,12,'2018-10-04 14:24:23','2018-10-04 14:24:23'),(17,12,10,'2018-10-04 14:25:03','2018-10-04 14:25:03'),(18,13,1,'2018-10-04 14:26:15','2018-10-04 14:26:15');
/*!40000 ALTER TABLE `category_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `comment_text` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `evaluation` int(11) NOT NULL,
  `key` int(11) NOT NULL,
  `rental_id` int(11) DEFAULT NULL,
  `today_book` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,1,1,'kakak',2,3,NULL,0,'2018-10-03 10:15:25','2018-10-03 10:15:25'),(2,1,2,2,'lplplpplplp',3,3,NULL,0,'2018-10-03 14:48:31','2018-10-03 14:48:31'),(3,1,3,3,'こここっこ',3,3,NULL,0,'2018-10-03 15:04:08','2018-10-03 15:04:08'),(4,1,4,1,'kakaka',2,4,NULL,0,'2018-10-03 16:10:14','2018-10-03 16:10:14'),(5,1,5,4,'ここここ',3,3,NULL,0,'2018-10-03 22:36:17','2018-10-03 22:36:17'),(6,1,6,2,'kokoko',2,3,NULL,0,'2018-10-04 09:37:26','2018-10-04 09:37:26'),(7,1,7,4,'kokok',4,3,NULL,0,'2018-10-04 09:55:27','2018-10-04 09:55:27'),(8,1,8,4,'kokoko',4,4,NULL,0,'2018-10-04 09:55:52','2018-10-04 09:55:52'),(9,1,9,3,'kokokoko',3,3,NULL,0,'2018-10-04 09:59:08','2018-10-04 09:59:08'),(10,1,1,1,'yokatta~~~',3,3,1,0,'2018-10-04 10:48:24','2018-10-04 10:48:24'),(11,1,1,1,'yokatta~~~',3,3,1,0,'2018-10-04 10:48:24','2018-10-04 10:48:24'),(12,1,1,1,'yokatta~~~',3,3,1,0,'2018-10-04 10:48:24','2018-10-04 10:48:24'),(13,1,10,4,'kokokokokokoko',3,4,NULL,0,'2018-10-04 12:31:05','2018-10-04 12:31:05'),(14,1,12,5,'111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',5,5,NULL,1,'2018-10-04 12:32:42','2018-10-04 12:32:42'),(15,1,13,6,'kokokokokoo',2,4,NULL,0,'2018-10-04 14:15:07','2018-10-04 14:15:07'),(16,1,14,7,'kokokoo',4,4,NULL,0,'2018-10-04 14:16:32','2018-10-04 14:16:32'),(17,1,15,8,'kokokoko',3,4,NULL,0,'2018-10-04 14:17:12','2018-10-04 14:17:12'),(18,1,16,9,'kokokoko',2,3,NULL,0,'2018-10-04 14:18:40','2018-10-04 14:18:40'),(19,1,17,10,'aaaaaaaaaaaaaaaAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',5,2,NULL,0,'2018-10-04 14:23:27','2018-10-04 14:23:27'),(20,1,18,11,'lolololololo',5,4,NULL,0,'2018-10-04 14:24:23','2018-10-04 14:24:23'),(21,1,19,12,'kokokkoo',5,3,NULL,0,'2018-10-04 14:25:03','2018-10-04 14:25:03'),(22,1,20,13,'aaaaaaazazaz',5,3,NULL,0,'2018-10-04 14:26:15','2018-10-04 14:26:15');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'LAB5',NULL,NULL),(2,'LAB5',NULL,NULL),(3,'LAB4',NULL,NULL),(4,'LAB4',NULL,NULL),(5,'TUTER',NULL,NULL),(6,'TUTER',NULL,NULL),(7,'TEACHER',NULL,NULL),(8,'TEACHER',NULL,NULL);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keys`
--

DROP TABLE IF EXISTS `keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keys`
--

LOCK TABLES `keys` WRITE;
/*!40000 ALTER TABLE `keys` DISABLE KEYS */;
INSERT INTO `keys` VALUES (1,'初心者','2018-10-03 10:14:25','2018-10-03 10:14:25'),(2,'中級者','2018-10-03 10:14:28','2018-10-03 10:14:28'),(3,'辞書引き可','2018-10-03 10:14:31','2018-10-03 10:14:31'),(4,'概要がわかる','2018-10-03 10:14:34','2018-10-03 10:14:34'),(5,'上級者','2018-10-03 10:14:43','2018-10-03 10:14:43');
/*!40000 ALTER TABLE `keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2018_07_09_030931_create_categories_table',1),(4,'2018_07_10_005638_create_books_table',1),(5,'2018_07_15_024151_create_comments_table',1),(6,'2018_07_15_024259_create_rentals_table',1),(7,'2018_07_18_143333_create_owners_table',1),(8,'2018_08_06_160458_create_category_lists_table',1),(9,'2018_08_08_020216_create_threads_table',1),(10,'2018_08_08_020325_create_thread_comments_table',1),(11,'2018_09_18_144540_create_category_genres_table',1),(12,'2018_09_20_154929_create_tags_table',1),(13,'2018_09_21_004114_create_keys_table',1),(14,'2018_09_21_162237_create_book_tag_table',1),(15,'2018_10_03_095842_create_groups_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owners`
--

DROP TABLE IF EXISTS `owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `owners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `rental_flag` int(11) NOT NULL DEFAULT '0',
  `life_flag` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `return_flag` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owners`
--

LOCK TABLES `owners` WRITE;
/*!40000 ALTER TABLE `owners` DISABLE KEYS */;
INSERT INTO `owners` VALUES (1,1,0,0,1,0,'2018-10-03 10:15:25','2018-10-04 10:48:24'),(2,2,2,0,1,0,'2018-10-03 14:48:31','2018-10-04 01:17:16'),(3,3,0,1,1,0,'2018-10-03 15:04:08','2018-10-04 01:14:10'),(4,1,2,0,1,0,'2018-10-03 16:10:14','2018-10-04 09:38:16'),(5,4,2,0,1,0,'2018-10-03 22:36:17','2018-10-04 09:35:53'),(6,2,2,0,1,0,'2018-10-04 09:37:26','2018-10-04 09:37:46'),(7,4,1,0,1,0,'2018-10-04 09:55:27','2018-10-04 09:55:27'),(8,4,0,0,1,0,'2018-10-04 09:55:52','2018-10-04 09:55:52'),(9,3,1,0,1,0,'2018-10-04 09:59:08','2018-10-04 09:59:08'),(10,4,0,0,1,0,'2018-10-04 12:31:05','2018-10-04 12:31:05'),(11,5,1,0,1,0,'2018-10-04 12:31:53','2018-10-04 12:31:53'),(12,5,2,0,1,0,'2018-10-04 12:32:42','2018-10-04 12:32:42'),(13,6,2,0,2,0,'2018-10-04 14:15:07','2018-10-04 14:15:07'),(14,7,0,0,2,0,'2018-10-04 14:16:32','2018-10-04 14:16:32'),(15,8,2,0,2,0,'2018-10-04 14:17:12','2018-10-04 14:17:12'),(16,9,0,0,2,0,'2018-10-04 14:18:40','2018-10-04 14:18:40'),(17,10,0,0,2,0,'2018-10-04 14:23:27','2018-10-04 14:23:27'),(18,11,0,0,2,0,'2018-10-04 14:24:23','2018-10-04 14:24:23'),(19,12,0,0,2,0,'2018-10-04 14:25:03','2018-10-04 14:25:03'),(20,13,0,0,2,0,'2018-10-04 14:26:15','2018-10-04 14:26:15');
/*!40000 ALTER TABLE `owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rentals`
--

DROP TABLE IF EXISTS `rentals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rentals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `return_day` datetime NOT NULL,
  `returned_day` datetime DEFAULT NULL,
  `return_flag` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rentals`
--

LOCK TABLES `rentals` WRITE;
/*!40000 ALTER TABLE `rentals` DISABLE KEYS */;
INSERT INTO `rentals` VALUES (1,1,1,'2018-10-10 00:00:00','2018-10-04 00:00:00',0,'2018-10-03 16:09:23','2018-10-04 10:48:24');
/*!40000 ALTER TABLE `rentals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tags` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'java script',5,'2018-10-03 10:13:44','2018-10-03 10:13:44'),(2,'統計・分析',7,'2018-10-03 10:13:48','2018-10-03 10:13:48'),(3,'PHP',5,'2018-10-03 10:13:56','2018-10-03 10:13:56'),(4,'laravel',5,'2018-10-03 10:15:25','2018-10-03 10:15:25'),(5,'統計・分析',1,'2018-10-03 15:04:08','2018-10-03 15:04:08'),(6,'kokok',1,'2018-10-03 16:10:14','2018-10-03 16:10:14'),(7,'java',1,'2018-10-03 22:36:17','2018-10-03 22:36:17'),(8,'kokoko',4,'2018-10-04 09:37:26','2018-10-04 09:37:26'),(9,'jquery',10,'2018-10-04 09:55:27','2018-10-04 09:55:27'),(10,'lpp',11,'2018-10-04 09:55:52','2018-10-04 09:55:52'),(11,'python',5,'2018-10-04 09:59:08','2018-10-04 09:59:08'),(12,'asasas',1,'2018-10-04 14:15:07','2018-10-04 14:15:07'),(13,'java script',10,'2018-10-04 14:17:12','2018-10-04 14:17:12'),(14,'lll',12,'2018-10-04 14:24:23','2018-10-04 14:24:23'),(15,'aaaaa',1,'2018-10-04 14:26:15','2018-10-04 14:26:15');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `thread_comments`
--

DROP TABLE IF EXISTS `thread_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thread_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `thread_comment` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `comment_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `thread_comments`
--

LOCK TABLES `thread_comments` WRITE;
/*!40000 ALTER TABLE `thread_comments` DISABLE KEYS */;
INSERT INTO `thread_comments` VALUES (1,1,'ここここ',5,'2018-10-03 22:36:17','2018-10-03 22:36:17');
/*!40000 ALTER TABLE `thread_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `threads`
--

DROP TABLE IF EXISTS `threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thread_sub` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `thread_body` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `threads`
--

LOCK TABLES `threads` WRITE;
/*!40000 ALTER TABLE `threads` DISABLE KEYS */;
INSERT INTO `threads` VALUES (1,'〜で悩んでいる件','lplplplplp',1,4,'2018-10-03 22:35:01','2018-10-03 22:35:01');
/*!40000 ALTER TABLE `threads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `avater` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `kanri_flag` int(11) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_facebook_id_unique` (`facebook_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'974875409359893','Atsuko Koda','https://graph.facebook.com/v3.0/974875409359893/picture?width=1920',2,1,'CR0Ul9RKPzIkQ157bU4apPbTCLYCfUec4G1rfIvHnE5D8Swj49vEPCgsYKEk','2018-10-03 10:07:33','2018-10-03 10:37:18'),(2,NULL,'g\'s book',NULL,NULL,0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-04  8:03:36
