<?php
	$sql = "INSERT INTO `{$wpdb->prefix}{$this->prefix}match` (`match_no`, `kickoff`, `home_team_id`, `away_team_id`, `home_goals`, `away_goals`, `home_penalties`, `away_penalties`, `venue_id`, `is_result`, `extra_time`, `stage_id`, `scored`, `wwhen`) VALUES
	(1, '2017-06-17 15:00:00', 8, 6, 0, 0, 0, 0, 3, 0, 0, 1, 1, '2017-04-28 16:46:56'),
	(2, '2017-06-18 15:00:00', 7, 5, 0, 0, 0, 0, 1, 0, 0, 1, 0, '2017-04-28 16:47:28'),
	(3, '2017-06-18 18:00:00', 2, 3, 0, 0, 0, 0, 2, 0, 0, 2, 0, '2017-04-28 16:48:09'),
	(4, '2017-06-19 15:00:00', 1, 4, 0, 0, 0, 0, 4, 0, 0, 2, 0, '2017-04-28 16:48:45'),
	(5, '2017-06-21 15:00:00', 8, 7, 0, 0, 0, 0, 2, 0, 0, 1, 0, '2017-04-28 16:49:38'),
	(6, '2017-06-21 18:00:00', 5, 6, 0, 0, 0, 0, 4, 0, 0, 1, 0, '2017-04-28 16:50:11'),
	(7, '2017-06-22 15:00:00', 2, 1, 0, 0, 0, 0, 3, 0, 0, 2, 0, '2017-04-28 16:51:00'),
	(8, '2017-06-22 18:00:00', 4, 3, 0, 0, 0, 0, 1, 0, 0, 2, 0, '2017-04-28 16:51:43'),
	(9, '2017-06-24 15:00:00', 5, 8, 0, 0, 0, 0, 1, 0, 0, 1, 0, '2017-04-28 16:52:33'),
	(10, '2017-06-24 15:00:00', 6, 7, 0, 0, 0, 0, 3, 0, 0, 1, 0, '2017-04-28 16:53:17'),
	(11, '2017-06-25 15:00:00', 4, 2, 0, 0, 0, 0, 4, 0, 0, 2, 0, '2017-04-28 16:55:45'),
	(12, '2017-06-25 15:00:00', 3, 1, 0, 0, 0, 0, 2, 0, 0, 2, 0, '2017-04-28 16:56:18'),
	(13, '2017-06-28 18:00:00', 9, 10, 0, 0, 0, 0, 1, 0, 0, 3, 0, '2017-04-28 17:11:10'),
	(14, '2017-06-29 18:00:00', 11, 12, 0, 0, 0, 0, 4, 0, 0, 3, 0, '2017-04-28 17:12:12'),
	(15, '2017-07-02 12:00:00', 13, 14, 0, 0, 0, 0, 2, 0, 0, 4, 0, '2017-04-28 17:13:19'),
	(16, '2017-07-02 18:00:00', 15, 16, 0, 0, 0, 0, 3, 0, 0, 5, 0, '2017-04-28 17:14:20')";
	$wpdb->query($sql);
	
	$sql = "INSERT INTO `{$wpdb->prefix}{$this->prefix}stage` (`stage_name`, `is_group`, `sort_order`, `wwhen`) VALUES
	('Group A', 1, 1, '2017-04-28 16:31:41'),
	('Group B', 1, 2, '2017-04-28 16:31:57'),
	('Semi-finals', 0, 3, '2017-04-28 16:32:16'),
	('Play-off for Third Place', 0, 4, '2017-04-28 16:32:35'),
	('Final', 0, 5, '2017-04-28 16:37:12')";
	$wpdb->query($sql);
	
	$sql = "INSERT INTO `{$wpdb->prefix}{$this->prefix}team` (`name`, `country`, `team_url`, `group_order`, `wwhen`) VALUES
	('Australia', 'aus', 'http://www.fifa.com/confederationscup/teams/team=43976/index.html', 3, '2017-04-28 14:49:01'),
	('Cameroon', 'cmr', 'http://www.fifa.com/confederationscup/teams/team=43849/index.html', 1, '2017-04-28 14:48:41'),
	('Chile', 'chi', 'http://www.fifa.com/confederationscup/teams/team=43925/index.html', 2, '2017-04-28 14:48:47'),
	('Germany', 'ger', 'http://www.fifa.com/confederationscup/teams/team=43948/index.html', 4, '2017-04-28 14:49:12'),
	('Mexico', 'mex', 'http://www.fifa.com/confederationscup/teams/team=43911/index.html', 4, '2017-04-28 14:48:29'),
	('New Zealand', 'nzl', 'http://www.fifa.com/confederationscup/teams/team=43978/index.html', 2, '2017-04-28 14:48:07'),
	('Portugal', 'por', 'http://www.fifa.com/confederationscup/teams/team=43963/index.html', 3, '2017-04-28 14:48:19'),
	('Russia', 'rus', 'http://www.fifa.com/confederationscup/teams/team=43965/index.html', 1, '2017-04-28 14:47:53'),
	('[WA]', 'xxx', '', 0, '2017-04-28 17:07:43'),
	('[RB]', 'xxx', '', 0, '2017-04-28 17:07:59'),
	('[WB]', 'xxx', '', 0, '2017-04-28 17:08:59'),
	('[RA]', 'xxx', '', 0, '2017-04-28 17:09:15'),
	('[L13]', 'xxx', '', 0, '2017-04-28 17:09:35'),
	('[L14]', 'xxx', '', 0, '2017-04-28 17:09:52'),
	('[W13]', 'xxx', '', 0, '2017-04-28 17:10:10'),
	('[W14]', 'xxx', '', 0, '2017-04-28 17:10:25')";
	$wpdb->query($sql);
	
	$sql = "INSERT INTO `{$wpdb->prefix}{$this->prefix}venue` (`venue_name`, `venue_url`, `stadium`, `tz_offset`, `wwhen`) VALUES
	('Kazan', 'http://www.fifa.com/confederationscup/destination/cities/city=73217/index.html', 'Kazan Arena', 3, '2017-04-28 16:08:16'),
	('Moscow', 'http://www.fifa.com/confederationscup/destination/cities/city=1559/index.html', 'Spartak Stadium', 3, '2017-04-28 16:09:00'),
	('Saint Petersburg', 'http://www.fifa.com/confederationscup/destination/cities/city=1771/index.html', 'Saint Petersburg Stadium', 3, '2017-04-28 16:13:09'),
	('Sochi', 'http://www.fifa.com/confederationscup/destination/cities/city=35268/index.html', 'Fisht Stadium', 3, '2017-04-28 16:10:12')";
	$wpdb->query($sql);
	
	$this->setMessage(__('Match data imported', WCP_TD));
