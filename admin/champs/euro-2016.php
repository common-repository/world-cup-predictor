<?php
	$sql = "INSERT INTO `{$wpdb->prefix}{$this->prefix}match` (`match_no`, `kickoff`, `home_team_id`, `away_team_id`, `home_goals`, `away_goals`, `home_penalties`, `away_penalties`, `venue_id`, `is_result`, `extra_time`, `stage_id`, `scored`, `wwhen`) VALUES
	(1, '2016-06-10 19:00:00', 7, 16, 0, 0, 0, 0, 8, 0, 0, 1, 1, '2016-05-19 00:45:20'),
	(2, '2016-06-11 13:00:00', 1, 21, 0, 0, 0, 0, 2, 0, 0, 1, 1, '2016-05-19 00:45:54'),
	(3, '2016-06-11 16:00:00', 24, 18, 0, 0, 0, 0, 1, 0, 0, 2, 1, '2016-05-19 00:46:11'),
	(4, '2016-06-11 19:00:00', 6, 17, 0, 0, 0, 0, 5, 0, 0, 2, 1, '2016-05-19 00:46:24'),
	(5, '2016-06-12 13:00:00', 22, 4, 0, 0, 0, 0, 7, 0, 0, 4, 1, '2016-05-19 00:46:43'),
	(6, '2016-06-12 16:00:00', 13, 12, 0, 0, 0, 0, 6, 0, 0, 3, 1, '2016-05-19 00:46:58'),
	(7, '2016-06-12 19:00:00', 8, 23, 0, 0, 0, 0, 3, 0, 0, 3, 1, '2016-05-19 00:47:12'),
	(8, '2016-06-13 13:00:00', 19, 5, 0, 0, 0, 0, 10, 0, 0, 4, 0, '2016-05-19 00:48:54'),
	(9, '2016-06-13 16:00:00', 15, 20, 0, 0, 0, 0, 8, 0, 0, 5, 0, '2016-05-19 00:49:44'),
	(10, '2016-06-13 19:00:00', 3, 11, 0, 0, 0, 0, 5, 0, 0, 5, 0, '2016-05-19 00:50:20'),
	(11, '2016-06-14 16:00:00', 2, 9, 0, 0, 0, 0, 1, 0, 0, 6, 0, '2016-05-19 00:51:09'),
	(12, '2016-06-14 19:00:00', 14, 10, 0, 0, 0, 0, 9, 0, 0, 6, 0, '2016-05-19 00:51:44'),
	(13, '2016-06-15 13:00:00', 17, 18, 0, 0, 0, 0, 3, 0, 0, 2, 0, '2016-05-19 00:52:35'),
	(14, '2016-06-15 16:00:00', 16, 21, 0, 0, 0, 0, 7, 0, 0, 1, 0, '2016-05-19 00:53:31'),
	(15, '2016-06-15 19:00:00', 7, 1, 0, 0, 0, 0, 5, 0, 0, 1, 0, '2016-05-19 00:54:02'),
	(16, '2016-06-16 13:00:00', 6, 24, 0, 0, 0, 0, 2, 0, 0, 2, 0, '2016-05-19 00:55:09'),
	(17, '2016-06-16 16:00:00', 23, 12, 0, 0, 0, 0, 4, 0, 0, 3, 0, '2016-05-19 00:56:00'),
	(18, '2016-06-16 19:00:00', 8, 13, 0, 0, 0, 0, 8, 0, 0, 3, 0, '2016-05-19 00:56:45'),
	(19, '2016-06-17 13:00:00', 11, 20, 0, 0, 0, 0, 10, 0, 0, 5, 0, '2016-05-19 00:57:28'),
	(20, '2016-06-17 16:00:00', 5, 4, 0, 0, 0, 0, 9, 0, 0, 4, 0, '2016-05-19 00:58:30'),
	(21, '2016-06-17 19:00:00', 19, 22, 0, 0, 0, 0, 6, 0, 0, 4, 0, '2016-05-19 00:59:12'),
	(22, '2016-06-18 13:00:00', 3, 15, 0, 0, 0, 0, 1, 0, 0, 5, 0, '2016-05-19 01:00:00'),
	(23, '2016-06-18 16:00:00', 10, 9, 0, 0, 0, 0, 5, 0, 0, 6, 0, '2016-05-19 01:01:40'),
	(24, '2016-06-18 19:00:00', 14, 2, 0, 0, 0, 0, 7, 0, 0, 6, 0, '2016-05-19 01:02:11'),
	(25, '2016-06-19 19:00:00', 16, 1, 0, 0, 0, 0, 4, 0, 0, 1, 0, '2016-05-19 01:02:58'),
	(26, '2016-06-19 19:00:00', 21, 7, 0, 0, 0, 0, 3, 0, 0, 1, 0, '2016-05-19 01:03:33'),
	(27, '2016-06-20 19:00:00', 17, 24, 0, 0, 0, 0, 10, 0, 0, 2, 0, '2016-05-19 01:04:26'),
	(28, '2016-06-20 19:00:00', 18, 6, 0, 0, 0, 0, 9, 0, 0, 2, 0, '2016-05-19 01:05:01'),
	(29, '2016-06-21 16:00:00', 23, 13, 0, 0, 0, 0, 5, 0, 0, 3, 0, '2016-05-19 01:05:57'),
	(30, '2016-06-21 16:00:00', 12, 8, 0, 0, 0, 0, 7, 0, 0, 3, 0, '2016-05-19 01:06:38'),
	(31, '2016-06-21 19:00:00', 4, 19, 0, 0, 0, 0, 1, 0, 0, 4, 0, '2016-05-19 01:07:23'),
	(32, '2016-06-21 19:00:00', 5, 22, 0, 0, 0, 0, 2, 0, 0, 4, 0, '2016-05-19 01:07:59'),
	(33, '2016-06-22 16:00:00', 9, 14, 0, 0, 0, 0, 4, 0, 0, 6, 0, '2016-05-19 01:15:14'),
	(34, '2016-06-22 16:00:00', 10, 2, 0, 0, 0, 0, 8, 0, 0, 6, 0, '2016-05-19 01:16:08'),
	(35, '2016-06-22 19:00:00', 11, 15, 0, 0, 0, 0, 3, 0, 0, 5, 0, '2016-05-19 01:16:38'),
	(36, '2016-06-22 19:00:00', 20, 3, 0, 0, 0, 0, 6, 0, 0, 5, 0, '2016-05-19 01:17:09'),
	(37, '2016-06-25 13:00:00', 25, 26, 0, 0, 0, 0, 9, 0, 0, 7, 0, '2016-05-19 01:18:36'),
	(38, '2016-06-25 16:00:00', 27, 28, 0, 0, 0, 0, 7, 0, 0, 7, 0, '2016-05-19 01:20:03'),
	(39, '2016-06-25 19:00:00', 29, 30, 0, 0, 0, 0, 2, 0, 0, 7, 0, '2016-05-19 01:21:10'),
	(40, '2016-06-26 13:00:00', 31, 32, 0, 0, 0, 0, 4, 0, 0, 7, 0, '2016-05-19 01:27:15'),
	(41, '2016-06-26 16:00:00', 33, 34, 0, 0, 0, 0, 3, 0, 0, 7, 0, '2016-05-19 01:28:05'),
	(42, '2016-06-26 19:00:00', 35, 36, 0, 0, 0, 0, 10, 0, 0, 7, 0, '2016-05-19 01:28:46'),
	(43, '2016-06-27 16:00:00', 37, 38, 0, 0, 0, 0, 8, 0, 0, 7, 0, '2016-05-19 01:29:35'),
	(44, '2016-06-27 19:00:00', 39, 40, 0, 0, 0, 0, 6, 0, 0, 7, 0, '2016-05-19 01:30:18'),
	(45, '2016-06-30 19:00:00', 41, 42, 0, 0, 0, 0, 5, 0, 0, 8, 0, '2016-05-19 01:31:09'),
	(46, '2016-07-01 19:00:00', 43, 44, 0, 0, 0, 0, 3, 0, 0, 8, 0, '2016-05-19 01:31:59'),
	(47, '2016-07-02 19:00:00', 45, 46, 0, 0, 0, 0, 1, 0, 0, 8, 0, '2016-05-19 01:32:42'),
	(48, '2016-07-03 19:00:00', 47, 48, 0, 0, 0, 0, 8, 0, 0, 8, 0, '2016-05-19 01:33:32'),
	(49, '2016-07-06 19:00:00', 49, 50, 0, 0, 0, 0, 4, 0, 0, 9, 0, '2016-05-19 01:34:23'),
	(50, '2016-07-07 19:00:00', 51, 52, 0, 0, 0, 0, 5, 0, 0, 9, 0, '2016-05-19 01:35:08'),
	(51, '2016-07-10 19:00:00', 53, 54, 0, 0, 0, 0, 8, 0, 0, 10, 0, '2016-05-19 01:35:56')";
	$wpdb->query($sql);
	
	$sql = "INSERT INTO `{$wpdb->prefix}{$this->prefix}stage` (`stage_name`, `is_group`, `sort_order`, `wwhen`) VALUES
	('Group A', 1, 1, '2016-05-18 02:33:40'),
	('Group B', 1, 2, '2016-05-18 02:34:11'),
	('Group C', 1, 3, '2016-05-18 02:34:32'),
	('Group D', 1, 4, '2016-05-18 02:34:45'),
	('Group E', 1, 5, '2016-05-18 02:35:07'),
	('Group F', 1, 6, '2016-05-18 02:35:21'),
	('Round of 16', 0, 7, '2016-05-18 02:35:50'),
	('Quarter-finals', 0, 8, '2016-05-18 02:36:07'),
	('Semi-finals', 0, 9, '2016-05-18 02:36:26'),
	('Final', 0, 10, '2016-05-18 02:36:42')";
	$wpdb->query($sql);
	
	$sql = "INSERT INTO `{$wpdb->prefix}{$this->prefix}team` (`name`, `country`, `team_url`, `group_order`, `wwhen`) VALUES
	('Albania', 'alb', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=2/index.html', 1, '0000-00-00 00:00:00'),
	('Austria', 'aut', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=8/index.html', 1, '0000-00-00 00:00:00'),
	('Belgium', 'bel', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=13/index.html', 1, '0000-00-00 00:00:00'),
	('Croatia', 'cro', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=56370/index.html', 1, '0000-00-00 00:00:00'),
	('Czech Republic', 'cze', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=58837/index.html', 2, '0000-00-00 00:00:00'),
	('England', 'eng', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=39/index.html', 1, '0000-00-00 00:00:00'),
	('France', 'fra', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=43/index.html', 2, '0000-00-00 00:00:00'),
	('Germany', 'ger', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=47/index.html', 1, '0000-00-00 00:00:00'),
	('Hungary', 'hun', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=57/index.html', 2, '0000-00-00 00:00:00'),
	('Iceland', 'isl', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=58/index.html', 3, '0000-00-00 00:00:00'),
	('Italy', 'ita', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=66/index.html', 2, '0000-00-00 00:00:00'),
	('Northern Ireland', 'nir', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=63/index.html', 2, '0000-00-00 00:00:00'),
	('Poland', 'pol', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=109/index.html', 3, '0000-00-00 00:00:00'),
	('Portugal', 'por', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=110/index.html', 4, '0000-00-00 00:00:00'),
	('Republic of Ireland', 'irl', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=64/index.html', 3, '0000-00-00 00:00:00'),
	('Romania', 'rou', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=113/index.html', 3, '0000-00-00 00:00:00'),
	('Russia', 'rus', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=57451/index.html', 2, '0000-00-00 00:00:00'),
	('Slovakia', 'svk', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=58836/index.html', 3, '0000-00-00 00:00:00'),
	('Spain', 'esp', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=122/index.html', 3, '0000-00-00 00:00:00'),
	('Sweden', 'swe', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=127/index.html', 4, '0000-00-00 00:00:00'),
	('Switzerland', 'sui', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=128/index.html', 4, '0000-00-00 00:00:00'),
	('Turkey', 'tur', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=135/index.html', 4, '0000-00-00 00:00:00'),
	('Ukraine', 'ukr', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=57166/index.html', 4, '0000-00-00 00:00:00'),
	('Wales', 'wal', 'http://www.uefa.com/uefaeuro/season=2016/teams/team=144/index.html', 4, '0000-00-00 00:00:00'),
	('Runner-up A', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Runner-up C', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner B', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Third-place A/C/D', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner D', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Third-place B/E/F', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner A', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Third-place C/D/E', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner C', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Third-place A/B/F', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner F', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Runner-up E', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner E', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Runner-up D', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Runner-up B', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Runner-up F', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner R16 (St-Etienne) ', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner R16 (Lens)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner R16 (Paris)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner R16 (Toulouse)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner R16 (Lille)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner R16 (St-Denis)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner R16 (Lyon)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner R16 (Nice)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner QF (Marseille)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner QF (Lille)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner QF (Bordeaux)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner QF (St-Denis)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner SF (Lyon)', 'xxx', '', 0, '0000-00-00 00:00:00'),
	('Winner SF (Marseille)', 'xxx', '', 0, '0000-00-00 00:00:00')";
	$wpdb->query($sql);
	
	$sql = "INSERT INTO `{$wpdb->prefix}{$this->prefix}venue` (`venue_name`, `venue_url`, `stadium`, `tz_offset`, `wwhen`) VALUES
	('Bordeaux', 'http://www.uefa.com/uefaeuro/hosts/france/city=1272/index.html', 'Stade de Bordeaux', 0, '2016-05-18 02:19:48'),
	('Lens Agglo', 'http://www.uefa.com/uefaeuro/hosts/france/city=2065/index.html', 'Stade Bollaert-Delelis', 0, '2016-05-18 02:26:43'),
	('Lille Métropole', 'http://www.uefa.com/uefaeuro/hosts/france/city=2084/index.html', 'Stade Pierre Mauroy', 0, '2016-05-18 02:27:31'),
	('Lyon', 'http://www.uefa.com/uefaeuro/hosts/france/city=2156/index.html', 'Stade de Lyon', 0, '2016-05-18 02:28:12'),
	('Marseille', 'http://www.uefa.com/uefaeuro/hosts/france/city=2201/index.html', 'Stade Vélodrome', 0, '2016-05-18 02:28:57'),
	('Nice', 'http://www.uefa.com/uefaeuro/hosts/france/city=2344/index.html', 'Stade de Nice', 0, '2016-05-18 02:29:29'),
	('Paris', 'http://www.uefa.com/uefaeuro/hosts/france/city=2470/index.html', 'Parc des Princes', 0, '2016-05-18 02:30:02'),
	('Saint-Denis', 'http://www.uefa.com/uefaeuro/hosts/france/city=50000003/index.html', 'Stade de France', 0, '2016-05-18 02:30:40'),
	('Saint-Etienne', 'http://www.uefa.com/uefaeuro/hosts/france/city=2673/index.html', 'Stade Geoffroy Guichard', 0, '2016-05-18 02:31:21'),
	('Toulouse', 'http://www.uefa.com/uefaeuro/hosts/france/city=2972/index.html', 'Stadium de Toulouse', 0, '2016-05-18 02:33:09')";
	$wpdb->query($sql);
	
	$this->setMessage(__('Match data imported', WCP_TD));
