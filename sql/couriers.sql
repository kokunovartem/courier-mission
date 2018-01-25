-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 24 2018 г., 23:42
-- Версия сервера: 5.7.16-log
-- Версия PHP: 7.1.0

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `couriers`
--

-- --------------------------------------------------------

--
-- Структура таблицы `couriers`
--

DROP TABLE IF EXISTS `couriers`;
CREATE TABLE `couriers` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `patr_name` varchar(45) DEFAULT NULL,
  `birthday_dt` date DEFAULT NULL,
  `create_dt` timestamp NULL DEFAULT NULL,
  `busy_until` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `couriers`
--

INSERT INTO `couriers` (`id`, `last_name`, `first_name`, `patr_name`, `birthday_dt`, `create_dt`, `busy_until`) VALUES
(1, 'Андреев', 'Денис', 'Дмитриевич', '1987-10-05', '2014-05-04 21:00:00', NULL),
(2, 'Борисов', 'Эдуард', 'Федорович', '1991-03-07', '2014-05-04 21:00:00', NULL),
(3, 'Витальев', 'Константин', 'Сергеевич', '1995-08-01', '2014-05-04 21:00:00', NULL),
(4, 'Григорьев', 'Ильдар', 'Максимович', '1986-04-25', '2014-05-04 21:00:00', NULL),
(5, 'Драгунов', 'Семен', 'Павлович', '1994-09-12', '2014-05-04 21:00:00', NULL),
(6, 'Елисеев', 'Дмитрий', 'Николаевич', '1997-01-08', '2014-05-04 21:00:00', NULL),
(7, 'Жуков', 'Артем', 'Дмитриевич', '1985-02-21', '2014-12-09 21:00:00', NULL),
(8, 'Звездин', 'Максим', 'Иванович', '1992-01-16', '2014-12-09 21:00:00', NULL),
(9, 'Исаев', 'Денис', 'Петрович', '1992-08-17', '2014-12-09 21:00:00', NULL),
(10, 'Кондратьев', 'Иван', 'Федорович', '1987-05-02', '2014-12-09 21:00:00', NULL),
(11, 'Леонтьев', 'Алексей', 'Игоревич', '1990-07-12', '2014-12-09 21:00:00', NULL),
(12, 'Михайлов', 'Андрей', 'Дмитриевич', '1992-12-05', '2014-12-09 21:00:00', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `courier_missions`
--

DROP TABLE IF EXISTS `courier_missions`;
CREATE TABLE `courier_missions` (
  `id` int(11) NOT NULL,
  `couriers_id` smallint(5) UNSIGNED NOT NULL,
  `regions_id` tinyint(3) UNSIGNED NOT NULL,
  `begin_dt` timestamp NULL DEFAULT NULL,
  `arrival_dt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `show_order` tinyint(4) DEFAULT NULL,
  `travel_time` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`id`, `title`, `show_order`, `travel_time`) VALUES
(1, 'Санкт-Петербург', 1, 57600),
(2, 'Уфа', 2, 144000),
(3, 'Нижний Новгород', 3, 57600),
(4, 'Владимир', 4, 28800),
(5, 'Кострома', 5, 82800),
(6, 'Екатеринбург', 6, 158400),
(7, 'Ковров', 7, 32400),
(8, 'Воронеж', 8, 136800),
(9, 'Самара', 9, 151200),
(10, 'Астрахань', 10, 165600);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `couriers`
--
ALTER TABLE `couriers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `courier_missions`
--
ALTER TABLE `courier_missions`
  ADD PRIMARY KEY (`id`,`couriers_id`,`regions_id`),
  ADD KEY `fk_couriers_has_regions_regions1_idx` (`regions_id`),
  ADD KEY `fk_couriers_has_regions_couriers1_idx` (`couriers_id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `couriers`
--
ALTER TABLE `couriers`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT для таблицы `courier_missions`
--
ALTER TABLE `courier_missions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `courier_missions`
--
ALTER TABLE `courier_missions`
  ADD CONSTRAINT `courier_has_mission` FOREIGN KEY (`couriers_id`) REFERENCES `couriers` (`id`),
  ADD CONSTRAINT `region_has_mission` FOREIGN KEY (`regions_id`) REFERENCES `regions` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
