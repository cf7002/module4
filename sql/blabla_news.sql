SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `blabla_news`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_advertisements`
--

CREATE TABLE `tbl_advertisements` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `price` int(11) UNSIGNED NOT NULL,
  `vendor` varchar(255) NOT NULL,
  `href` varchar(255) DEFAULT NULL,
  `rating` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `count` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `version` smallint(6) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `priority` tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  `is_moderated` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `sub_category` tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `version` smallint(6) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_comments`
--

CREATE TABLE `tbl_comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `news_id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_images`
--

CREATE TABLE `tbl_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `news_id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_news`
--

CREATE TABLE `tbl_news` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `is_analytic` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `view_counter` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `version` smallint(6) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_subscribers`
--

CREATE TABLE `tbl_subscribers` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_tags`
--

CREATE TABLE `tbl_tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `version` smallint(6) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_tag_relations`
--

CREATE TABLE `tbl_tag_relations` (
  `id` int(11) UNSIGNED NOT NULL,
  `news_id` int(11) UNSIGNED NOT NULL,
  `tag_id` int(11) UNSIGNED NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `nick_name` varchar(50) DEFAULT NULL,
  `role` tinyint(2) UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `version` smallint(6) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_votes`
--

CREATE TABLE `tbl_votes` (
  `id` int(11) UNSIGNED NOT NULL,
  `comment_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `vote` tinyint(2) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `tbl_advertisements`
--
ALTER TABLE `tbl_advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_comments`
--
ALTER TABLE `tbl_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news` (`news_id`),
  ADD KEY `user` (`user_id`),
  ADD KEY `parent` (`parent_id`);

--
-- Индексы таблицы `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news` (`news_id`);

--
-- Индексы таблицы `tbl_news`
--
ALTER TABLE `tbl_news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category_id`);

--
-- Индексы таблицы `tbl_subscribers`
--
ALTER TABLE `tbl_subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_tags`
--
ALTER TABLE `tbl_tags`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_tag_relations`
--
ALTER TABLE `tbl_tag_relations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news` (`news_id`),
  ADD KEY `tag` (`tag_id`);

--
-- Индексы таблицы `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_votes`
--
ALTER TABLE `tbl_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment` (`comment_id`),
  ADD KEY `user` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `tbl_advertisements`
--
ALTER TABLE `tbl_advertisements`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT для таблицы `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `tbl_comments`
--
ALTER TABLE `tbl_comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3198;
--
-- AUTO_INCREMENT для таблицы `tbl_images`
--
ALTER TABLE `tbl_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;
--
-- AUTO_INCREMENT для таблицы `tbl_news`
--
ALTER TABLE `tbl_news`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;
--
-- AUTO_INCREMENT для таблицы `tbl_subscribers`
--
ALTER TABLE `tbl_subscribers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tbl_tags`
--
ALTER TABLE `tbl_tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `tbl_tag_relations`
--
ALTER TABLE `tbl_tag_relations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1078;
--
-- AUTO_INCREMENT для таблицы `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `tbl_votes`
--
ALTER TABLE `tbl_votes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12779;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `tbl_comments`
--
ALTER TABLE `tbl_comments`
  ADD CONSTRAINT `ref_comments4news` FOREIGN KEY (`news_id`) REFERENCES `tbl_news` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ref_parents` FOREIGN KEY (`parent_id`) REFERENCES `tbl_comments` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ref_users4comments` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD CONSTRAINT `ref_images4news` FOREIGN KEY (`news_id`) REFERENCES `tbl_news` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_news`
--
ALTER TABLE `tbl_news`
  ADD CONSTRAINT `ref_news4categories` FOREIGN KEY (`category_id`) REFERENCES `tbl_categories` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_tag_relations`
--
ALTER TABLE `tbl_tag_relations`
  ADD CONSTRAINT `ref_relations4news` FOREIGN KEY (`news_id`) REFERENCES `tbl_news` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ref_relations4tags` FOREIGN KEY (`tag_id`) REFERENCES `tbl_tags` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_votes`
--
ALTER TABLE `tbl_votes`
  ADD CONSTRAINT `ref_votes4comments` FOREIGN KEY (`comment_id`) REFERENCES `tbl_comments` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ref_votes4users` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
