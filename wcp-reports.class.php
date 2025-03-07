<?php
/**
 * Class that generates reports for the WorldCup plugin.
 * 
 * @version $Id: wcp-reports.class.php 2153552 2019-09-09 12:55:03Z landoweb $
 * @author landoweb
 * @package WorldCup
 * @copyright Copyright Landoweb Programador, 2014
 * 
 */
 
class WorldCupReport extends WorldCup {
	
	private $knockout;  // Knockout stage match results
	private $locale; 	// cache locale request
	
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Generates header for sorting the teams table.
	 * 
	 * @param unknown $stage_name - Defines a name for the group or stage
	 * @param string $compact - Show a compact table (for widget)
	 * @return string
	 */
	function table_header($stage_name, $compact = false) {
		
		$team = __('Team',WCP_TD);
		$mp = __('MP', WCP_TD);
		$mp_l = __('Matches Played', WCP_TD);
		$w = __('W', WCP_TD);
		$w_l = __('Won', WCP_TD);
		$d = __('D', WCP_TD);
		$d_l = __('Draw', WCP_TD);
		$l = __('L', WCP_TD);
		$l_l = __('Lost', WCP_TD);
		$gf = __('GF', WCP_TD);
		$gf_l = __('Goals For', WCP_TD);
		$ga = __('GA', WCP_TD);
		$ga_l = __('Goals Against', WCP_TD);
		$gd = __('GD', WCP_TD);
		$gd_l = __('Goal difference', WCP_TD);
		$p = __('Pts', WCP_TD);
		$p_l = __('Points', WCP_TD);
		
		if($compact == true) {
			
			$output = "<table class='wcp-group zebra'>
			<tr><th colspan='8'><h3>$stage_name</h3></th></tr>
			<tr class='wcup-header'>
				<th class='wcup-team'>$team</th>
				<th class='wcup-points'><span title='$p_l'>$p</span></th>
				<th class='wcup-points'><span title='$gd_l'>$gd</span></th>
			</tr>";
			
		} else {
			
			$output = "<table class='wcp-group zebra'>
			<tr><th colspan='8'><h3>$stage_name</h3></th></tr>
			<tr class='wcup-header'>
				<th class='wcup-team'>$team</th>
				<th class='wcup-points'><span title='$mp_l'>$mp</span></th>
				<th class='wcup-points'><span title='$w_l'>$w</span></th>
				<th class='wcup-points'><span title='$d_l'>$d</span></th>
				<th class='wcup-points'><span title='$l_l'>$l</span></th>
				<th class='wcup-points'><span title='$gf_l'>$gf</span></th>
				<th class='wcup-points'><span title='$ga_l'>$ga</span></th>
				<th class='wcup-points'><span title='$p_l'>$p</span></th>
			</tr>";			
		}
		
		return $output;
	}
	
	/**
	 * Generates rows for the teams table.
	 * 
	 * @param unknown $row - Array data from each team
	 * @param string $compact - Show a compact table (for widget)
	 * @return string
	 */
	function table_row($row, $compact = false) {
		
		$flag = $this->mklink($this->flag($row->team_country), $this->unclean($row->team_url), $this->unclean($row->team_name));
		$name = $this->mklink($this->unclean($row->team_name), $this->unclean($row->team_url), $this->unclean($row->team_name));
		$gd = $row->gf - $row->ga;
		
		if($compact == true) {
			
			$output = "<tr class='wcup-row'>
				<td class='wcup-team'>$flag $name</td>
				<td class='wcup-points'>$row->points</td>
				<td class='wcup-points'>$gd</td>
			</tr>";
			
		} else {
			
			$output = "<tr class='wcup-row'>
				<td class='wcup-team'>$flag $name</td>
				<td class='wcup-points'>$row->played</td>
				<td class='wcup-points'>$row->won</td>
				<td class='wcup-points'>$row->drawn</td>
				<td class='wcup-points'>$row->lost</td>
				<td class='wcup-points'>$row->gf</td>
				<td class='wcup-points'>$row->ga</td>
				<td class='wcup-points'>$row->points</td>
			</tr>";		
		}
		
		return $output;
	}
	
	/**
	 * For each team - calculate the matches played, points, etc.
	 */
	function calculate_group_tables() {
		
		global $wpdb;
		
		/**
		 * All matches
		 */
		$sql = "SELECT DISTINCT t.team_id, t.name AS team_name, t.team_url, t.country AS team_country, stage_name, s.stage_id, s.sort_order, t.group_order
				FROM
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team t,
					{$wpdb->prefix}{$this->prefix}match m
				WHERE
					(m.home_team_id = t.team_id OR m.away_team_id = t.team_id) AND
					m.stage_id = s.stage_id AND s.is_group = 1 
				ORDER BY
					s.sort_order, match_no";
		$teams = @$wpdb->get_results($sql, OBJECT_K);
		
		/**
		 * Matches played
		 */
		$sql = "SELECT t.team_id, COUNT(*) AS played, t.name, s.stage_id FROM
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team t,
					{$wpdb->prefix}{$this->prefix}match m
				WHERE
					(m.home_team_id = t.team_id OR m.away_team_id = t.team_id) AND
					m.stage_id = s.stage_id AND s.is_group = 1 AND m.is_result = 1 
				GROUP BY
					t.team_id";
		$mp =  @$wpdb->get_results($sql, OBJECT_K);
		
		/**
		 * Matches won
		 */
		$sql = "SELECT t.team_id, COUNT(*) AS won, t.name FROM
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team t,
					{$wpdb->prefix}{$this->prefix}match m
				WHERE
					((m.home_team_id = t.team_id AND m.home_goals > m.away_goals) OR
					 (m.away_team_id = t.team_id AND m.away_goals > m.home_goals))
					AND m.stage_id = s.stage_id AND s.is_group = 1 AND m.is_result = 1 
				GROUP BY
					t.team_id";
		$won =  @$wpdb->get_results($sql, OBJECT_K);
		
		/**
		 * Matches lost
		 */
		$sql = "SELECT t.team_id, COUNT(*) AS lost, t.name FROM
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team t,
					{$wpdb->prefix}{$this->prefix}match m
				WHERE
					((m.home_team_id = t.team_id AND m.home_goals < m.away_goals) OR
					 (m.away_team_id = t.team_id AND m.away_goals < m.home_goals))
					AND m.stage_id = s.stage_id AND s.is_group = 1 AND m.is_result = 1 
				GROUP BY
					t.team_id";
		$lost =  @$wpdb->get_results($sql, OBJECT_K);
		
		/**
		 * Matches drawn
		 */
		$sql = "SELECT t.team_id, COUNT(*) AS drawn, t.name FROM
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team t,
					{$wpdb->prefix}{$this->prefix}match m
				WHERE
					((m.home_team_id = t.team_id AND m.home_goals = m.away_goals) OR
					 (m.away_team_id = t.team_id AND m.home_goals = m.away_goals))
					AND m.stage_id = s.stage_id AND s.is_group = 1 AND m.is_result = 1 
				GROUP BY
					t.team_id";
		$drawn =  @$wpdb->get_results($sql, OBJECT_K);
		
		/**
		 * Goals for and against
		 */
		$sql = "SELECT home_team_id AS team_id, SUM(home_goals) AS gf, SUM(away_goals) AS ga FROM
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}match m
				WHERE
					m.stage_id = s.stage_id AND s.is_group = 1 AND m.is_result = 1 
				GROUP BY
					m.home_team_id";
		$goals1 =  @$wpdb->get_results($sql, OBJECT_K);
		$sql = "SELECT away_team_id AS team_id, SUM(away_goals) AS gf, SUM(home_goals) AS ga FROM
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}match m
				WHERE
					m.stage_id = s.stage_id AND s.is_group = 1 AND m.is_result = 1 
				GROUP BY
					m.away_team_id";
		$goals2 =  @$wpdb->get_results($sql, OBJECT_K);
		
		$stats = array();
		
		/**
		 * Merge the results from above.
		 */
		foreach ($teams as $team_id=>$val) {
			
			$temp = new WorldCupStats();
			
			$temp->team_id = $team_id;
			$temp->stage_id = $val->stage_id;
			$temp->team_url = $val->team_url;
			$temp->stage_name = $val->stage_name;
			$temp->team_country = $val->team_country;
			$temp->team_name = $val->team_name;
			$temp->sort_order = $val->sort_order;
			$temp->group_order = $val->group_order;
			
			if (is_array($mp) && array_key_exists($team_id,$mp)) {
				$temp->played = $mp[$team_id]->played;
			}
			if (is_array($won) && array_key_exists($team_id,$won)) {
				$temp->won = $won[$team_id]->won;
				$temp->points += (3 * $temp->won);
			}
			if (is_array($lost) && array_key_exists($team_id,$lost)) {
				$temp->lost = $lost[$team_id]->lost;
			}
			if (is_array($drawn) && array_key_exists($team_id,$drawn)) {
				$temp->drawn = $drawn[$team_id]->drawn;
				$temp->points += (1 * $temp->drawn);
			}
			if (is_array($goals1) && array_key_exists($team_id,$goals1)) {
				$temp->gf = $goals1[$team_id]->gf;
				$temp->ga = $goals1[$team_id]->ga;
			}
			if (is_array($goals2) && array_key_exists($team_id,$goals2)) {
				$temp->gf += $goals2[$team_id]->gf;
				$temp->ga += $goals2[$team_id]->ga;
			}
			
			$stats[] = $temp;
		}
		
		usort($stats, array($this, 'stats_sort'));
		
		update_option($this->prefix.'group_stats', $stats);  // Cache stats
		
		return $stats;
	}
	
	/**
	 * User sort
	 * 
	 * Sort by group sort order (keep groups together), then points, then matches played, then,
	 * 
	 * From http://en.wikipedia.org/wiki/2014_FIFA_World_Cup#Group_stage
	 * 
	 * Tie-breaking criteria
	 * 
	 * For the World Cup tournament, FIFA uses the following criteria to rank teams in the Group Stage.[88]
	 * 
	 * 1. greatest number of points in all group matches;
	 * 2. goal difference in all group matches;
	 * 3. greatest number of goals scored in all group matches.
	 * 4. greatest number of points in matches between tied teams;
	 * 5. goal difference in matches between tied teams;
	 * 6. greatest number of goals scored in matches between tied teams;
	 * 7. drawing of lots by the FIFA Organising Committee.
	 * 
	 */
	function stats_sort($a, $b) {
		
		if ($a->sort_order == $b->sort_order) {
			if ($a->points == $b->points) {					// Tie break 1.
				if ($a->played == $b->played) {				// Before all matches played promote better
															// points for lesser matches
					$agd = $a->gf - $a->ga;  // Goal difference
					$bgd = $b->gf - $b->ga;
					if ($agd == $bgd) {						// Tie break 2
						
						if ($a->gf == $b->gf) {				// Tie break 3
							
							/*
							 * Tie break criteria between tied teams
							 */
							if ($a->points == $b->points) {	// Tie break 4
								$agd = $a->gf - $a->ga;  // Goal difference
								$bgd = $b->gf - $b->ga;
								if ($agd == $bgd) {						// Tie break 5
									
									if ($a->gf == $b->gf) {				// Tie break 6
										
										if ($a->group_order == $b->group_order) {
											return 0;
										}
					    				return ($a->group_order < $b->group_order) ? -1 : 1; // Tie Break 7 - draw lots		
									}
					    			return ($a->gf < $b->gf) ? 1 : -1;		
								}
					    		return ($agd < $bgd) ? 1 : -1;		
							}
						    return ($a->points < $b->points) ? 1 : -1;
						}
		    			return ($a->gf < $b->gf) ? 1 : -1;		
					}
		    		return ($agd < $bgd) ? 1 : -1;		
			    }
				return ($a->played < $b->played) ? -1 : 1;
			}
		    return ($a->points < $b->points) ? 1 : -1;
		}
	    return ($a->sort_order < $b->sort_order) ? -1 : 1;		
	}
	
	/**
	 * Return the cached group tables
	 */
	function group_match_stats() {
		$stats = get_option($this->prefix.'group_stats');  // Get cached stats
		if (!$stats) {
			$stats = $this->calculate_group_tables();
		}
		return $stats;
	}
	
	/**
	 * Display group tables.
	 * 
	 * @param integer $stage - Limit to stage_id (0 == all).
	 * @param boolean $show_results - If true, display match results under group table.
	 * @param string $width - Sets a width to display.
	 * @param string $compact - Show a compact table (for widget)
	 * @return string
	 */
	function group_tables($stage, $show_results, $width='100%', $compact = false) {
		
		$output = '';
		
		$stats = $this->group_match_stats();
		
		$stage_id = -1;
		foreach ($stats as $row) {
			if ($stage && $stage != $row->stage_id) continue;
			if ($stage_id != $row->stage_id) {
				if ($stage_id != -1) {
					$output .= '</table>' . PHP_EOL;
					if ($show_results) $output .= $this->results($stage_id, $width);
				}
				$output .= $this->table_header($this->unclean($row->stage_name), $compact);
				$stage_id = $row->stage_id;
			}
			$output .= $this->table_row($row, $compact);
		}
		if (count($stats) && !empty($output)) {
			$output .= '</table>' . PHP_EOL;
			if ($show_results) $output .= $this->results($stage_id, $width);
		}
		
		return $output;
	}
	
	/**
	 * Display team name - bold if winner, append (a.e.t) or (pen.) if required.
	 */
	function team_name($row, $home, $justify = true) {
		$output = '';
		$suffix = '';
		$winner1 = '';
		$winner2 = '';
		
		if ($home) {
			$flag = $this->mklink($this->flag($row->home_country), $row->home_url, $this->unclean($row->home_team_name));
			$team = $this->mklink($this->unclean($row->home_team_name), $row->home_url, $this->unclean($row->home_team_name));
			// Bold winner
			if ($row->home_goals + $row->home_penalties > $row->away_goals + $row->away_penalties) {
				$winner1 = '<strong>';
				$winner2 = '</strong>';
				if (!$row->is_group && $row->is_result) {
				
					if ($row->home_penalties != 0 || $row->away_penalties != 0) {
						$suffix = ' (<span title="'.__('Penalties', WCP_TD).'">' . __('pen.', WCP_TD) . '</span>)';
					} elseif ($row->extra_time) {
						$suffix = ' (<span title="'.__('Extra Time', WCP_TD).'">' . __('a.e.t.', WCP_TD) . '</span>)';
					}
				}
			}
			$output = "$flag $winner1{$team}{$winner2} $suffix";
			
		} else {
			$flag = $this->mklink($this->flag($row->away_country), $row->away_url, $this->unclean($row->away_team_name));
			$team = $this->mklink($this->unclean($row->away_team_name), $row->away_url, $this->unclean($row->away_team_name));
			// Bold winner
			if ($row->away_goals + $row->away_penalties > $row->home_goals + $row->home_penalties) {
				$winner1 = '<strong>';
				$winner2 = '</strong>';
				if (!$row->is_group && $row->is_result) {
					
					if ($row->home_penalties != 0 || $row->away_penalties != 0) {
						$suffix = ' (<span title="'.__('Penalties', WCP_TD).'">' . __('pen.', WCP_TD) . '</span>)';
					} elseif ($row->extra_time) {
						$suffix = ' (<span title="'.__('Extra Time', WCP_TD).'">' . __('a.e.t.', WCP_TD) . '</span>)';
					}
				}
			}
			if ($justify) {
				$output = "$suffix $winner1{$team}{$winner2} $flag";
			} else {
				$output = "$flag $winner1{$team}{$winner2} $suffix";
			}
		}
		
		return $output;
	}
	
	/**
	 * Display teams score - bold if winner - optionally add penalties (n)
	 */
	function team_score($row, $home) {
		$output = '';
		$pen = '';
		$winner1 = '';
		$winner2 = '';
		
		if (!$row->is_result) return '&nbsp;';
		
		if ($home) {
			// Bold winner
			if ($row->home_goals + $row->home_penalties > $row->away_goals + $row->away_penalties) {
				$winner1 = '<strong>';
				$winner2 = '</strong>';
			}
			if (!$row->is_group && $row->is_result) {
				
				if ($row->home_penalties != 0 || $row->away_penalties != 0) {
					$pen = ' ('.$row->home_penalties.')';
				}
			}
			$output = "{$winner1}{$row->home_goals}{$pen}{$winner2}";
			
		} else {
			// Bold winner
			if ($row->away_goals + $row->away_penalties > $row->home_goals + $row->home_penalties) {
				$winner1 = '<strong>';
				$winner2 = '</strong>';
			}
			if (!$row->is_group && $row->is_result) {
				
				if ($row->home_penalties != 0 || $row->away_penalties != 0) {
					$pen = ' ('.$row->away_penalties.')';
				}
			}
			$output = "{$winner1}{$row->away_goals}{$pen}{$winner2}";
		}
		
		return $output;
	}
	
	/**
	 * Match results in a simple table for each stage or team.
	 * 
	 * @param integer $stage - ID of stage or group to show results.
	 * @param string $width - Sets width to display table.
	 * @param number $team - ID of team to show the results.
	 * @param boolean $group - Show only results of group stage.
	 * @param boolean $kickoff - Display results order by kickoff.
	 * @return string
	 */
	function results($stage, $width="100%", $team = 0, $group = false, $kickoff = false) {
		
		global $wpdb;
		
		$locale = get_option($this->prefix.'browser_locale', 1);
		$separator = get_option($this->prefix.'match_separator', '-');
		
		$output = '';
		$stage_filter = '';
		$team_filter = '';
		$group_filter = '';
		if ($stage) $stage_filter = "AND s.stage_id = $stage";
		if ($team) $team_filter = "AND (m.home_team_id = $team OR m.away_team_id = $team)";
		if ($group) $group_filter = ' AND is_group = 1';
		$sort_order = 'sort_order, kickoff';
		if ($kickoff) $sort_order = 'kickoff';
		
		$sql = "SELECT s.stage_id, match_id, match_no, DATE_ADD(kickoff, INTERVAL v.tz_offset HOUR) AS local_kickoff,
					h.name AS home_team_name, a.name AS away_team_name,
					home_goals, away_goals, home_penalties, away_penalties, venue_name, stage_name, 
					h.country AS home_country, a.country AS away_country,
					h.team_url AS home_url, a.team_url AS away_url, tz_offset,
					venue_url, stadium, is_group, is_result, extra_time,
					DATE_FORMAT(kickoff, '%Y%m%d%H%i') AS utc_kickoff
				FROM 
					{$wpdb->prefix}{$this->prefix}match m,
					{$wpdb->prefix}{$this->prefix}venue v,
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team h,
					{$wpdb->prefix}{$this->prefix}team a
				WHERE
					m.venue_id = v.venue_id AND m.stage_id = s.stage_id AND
					m.home_team_id = h.team_id AND m.away_team_id = a.team_id
					$stage_filter $team_filter $group_filter
				ORDER BY
					$sort_order, kickoff, match_no";
		
		$result = $wpdb->get_results( $sql , OBJECT );
		
		if ($result) $output .= '<table class="predictor">' .PHP_EOL;
		
		foreach ($result as $row) {
			$output .= '<tr><td class="l">'.$this->team_name($row, true);
			$output .= '</td><td class="cl">'.$this->team_score($row, true).'</td>';
			$output .= "<td class='c'>$separator</td>";
			$output .= '<td class="cr">'.$this->team_score($row, false).'</td><td class="r">';
			$output .= $this->team_name($row, false) . '</td><td class="res">';
			if (get_option($this->prefix.'match_predictions', 1)) {
			
				if (get_option($this->prefix.'show_predictions', 0) || ($row->utc_kickoff < date("YmdHi"))) {
					$output .= '<a class="predictions-link link1" title="'.__('Predictions', WCP_TD).'" href="'. esc_url(add_query_arg( array('wcp' => 'scores', 'match_id' => $row->match_id), get_permalink(get_option($this->prefix.'match_predictions')) ) ).'"><img src="'. WP_PLUGIN_URL.'/'.WCP_TD .'/images/predictions.png" /></a>';
				}
			}
			$output .= '</td></tr>' . PHP_EOL;
			$output .= '<tr class="venue"><td  class="sep" colspan="6">'.$row->stadium.', '.$row->venue_name.' &ndash; ';
			$output .= '<span ';
			if ($locale) $output .= 'tzoffset="'.$row->tz_offset.'" utc="'.$row->utc_kickoff.'"';
			$output .= ' class="'.$this->prefix.'kickoff_time">'.$this->format_date($row->local_kickoff).'</span></td></tr>' . PHP_EOL;
		}				
		
		if ($result) $output .= "</table>" .PHP_EOL;
		
		return $output;
	}
	
	/**
	 * Get match details
	 */
	function get_stage2_matches() {
		global $wpdb;
		
		$sql = "SELECT match_no, s.stage_id, match_id, tz_offset,
					DATE_ADD(kickoff, INTERVAL v.tz_offset HOUR) AS local_kickoff,
					h.name AS home_team_name, a.name AS away_team_name,
					home_goals, away_goals, 
					home_penalties, away_penalties, venue_name, stage_name, 
					h.country AS home_country, a.country AS away_country,
					h.team_url AS home_url, a.team_url AS away_url,
					venue_url, stadium, is_result, is_group, extra_time,
					DATE_FORMAT(kickoff, '%Y%m%d%H%i') AS utc_kickoff
				FROM 
					{$wpdb->prefix}{$this->prefix}match m,
					{$wpdb->prefix}{$this->prefix}venue v,
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team h,
					{$wpdb->prefix}{$this->prefix}team a
				WHERE
					m.venue_id = v.venue_id AND m.stage_id = s.stage_id AND
					m.home_team_id = h.team_id AND m.away_team_id = a.team_id AND
					match_no > 48";   // TODO - Yuck 48
		
		return $wpdb->get_results($sql, OBJECT_K);
	}
	
	function stage($i) {
		return "<!--S{$i}-->" . $this->clean($this->knockout[$i]->stage_name);
	}
	
	function venue($i) {
		
		$url = $this->mklink($this->clean($this->knockout[$i]->venue_name),
								$this->clean($this->knockout[$i]->venue_url),
								$this->clean($this->knockout[$i]->stadium));
		
		$str = "<!--V{$i}--><span ";
		if ($this->locale) $str .= 'tzoffset="'.$this->knockout[$i]->tz_offset."\" utc=\"{$this->knockout[$i]->utc_kickoff}\"";
		$str .= " class=\"date_only {$this->prefix}kickoff_time\">".$this->format_date($this->knockout[$i]->local_kickoff)."</span> &ndash; $url";
		return $str;
	}
	
	function home_score($i) {
		return "<!--GH{$i}-->" . $this->team_score($this->knockout[$i], true);
	}
	
	function home_team($i) {
		return "<!--TH{$i}-->" . $this->team_name($this->knockout[$i], true);
	}
	
	function away_score($i) {
		return "<!--GA{$i}-->" . $this->team_score($this->knockout[$i], false);
	}
	
	function away_team($i) {
		return "<!--TA{$i}-->" . $this->team_name($this->knockout[$i], false, false);
	}
	
	/**
	 * Display a ranking table of all predictions for all matches.
	 * 
	 * @param integer $limit - Limit the number of users to show.
	 * @param string $avatar - Show the avatar of player.
	 * @param string $highlight - Apply CSS formatting to the logged in user.
	 * @return string
	 */
	function user_ranking($limit = 999999, $avatar = false, $highlight = '', $stage = 0, $playoff = 0) {
		
		global $wpdb;
		global $current_user;
		
		$output = '';
		$curr_user_id = -1;
		
		if (!empty($highlight) && is_user_logged_in()) {
			wp_get_current_user();
			$curr_user_id = $current_user->ID;
		}
		
		$output = '';
		
		if (!is_numeric($limit)) {
			$limit = 999999;
		}
		
		$stage_filter = '';
		if ($playoff) $stage_filter = "AND is_group = 0";
		if ($stage) $stage_filter = "AND m.stage_id = $stage";
		
		$sql = "SELECT SUM(points) AS total, u.display_name, u.ID
				FROM
					{$wpdb->prefix}{$this->prefix}prediction p,
					{$wpdb->users} u,
					{$wpdb->prefix}{$this->prefix}match m,
					{$wpdb->prefix}{$this->prefix}stage s
				WHERE
					p.user_id = u.ID AND p.match_id = m.match_id AND m.stage_id = s.stage_id
					$stage_filter
				GROUP BY
					user_id
				ORDER BY
					total DESC, user_registered ASC
				LIMIT %d";
				
		$result = $wpdb->get_results($wpdb->prepare($sql, $limit));
		$output .= "<table class='zebra'><tr class='wcup-header'><th colspan='2' class='wcup-user'>".__('User', WCP_TD)."</th><th class='wcup-points' title='".__('Points', WCP_TD)."'>".__('Pts', WCP_TD)."</th><th class='wcup-review'>&nbsp;</th></tr>" . PHP_EOL;
	$pos = 1;
		$count = 1;
		$oldTotal = -1;
		foreach ($result as $row) {
			
			if($oldTotal > $row->total){
				$pos = $count;
			}
			
			if ($row->ID == $curr_user_id) {
				$style = "style=\"$highlight\"";
			} else {
				$style = 'class="wcup-row"';
			}
			$output .= "<tr $style><td class='wcup-user'>".($avatar ? get_avatar( $row->ID, '52', '', $row->display_name ) : '').'</td><td>'. $pos . '. ' . $row->display_name."</td><td class='wcup-points'>$row->total</td><td class='wcup-review'>";
			
			$oldTotal = $row->total;
			
			if (get_option($this->prefix.'player_predictions')) {
				$stage_link = '';
				if ($playoff) $stage_link = '&playoff=1';
				if ($stage) $stage_link = '&stage=1';
				$output .= "<a class='review-link' title='".__('Predictions', WCP_TD)."' href=\"".esc_url(add_query_arg( array('wcp' => 'predictions', 'user' => $row->ID), get_permalink(get_option($this->prefix.'player_predictions')) ) )."$stage_link\"><img src='". WP_PLUGIN_URL."/".WCP_TD ."/images/predictions.png' /></a>";
			}			
			
			$output .= "</td></tr>" . PHP_EOL;
			$count++;
		}
		$output .= "</table>" . PHP_EOL;
		
		return $output;
	}
	
	/**
	 * Show user scores for one match
	 */
	function score_match($match_id, $limit = 999999, $highlight = '', $curr_user_id, $filter_user, $avatar, $is_group, $is_result) {
		
		global $wpdb;
		
		$output = '';
		$separator = get_option($this->prefix.'match_separator', '-');
		
		$sql = "SELECT points, u.display_name, u.ID, p.home_goals, p.away_goals, p.home_penalties, p.away_penalties
				FROM
					{$wpdb->prefix}{$this->prefix}match m,
					{$wpdb->prefix}{$this->prefix}prediction p,
					{$wpdb->users} u
				WHERE
					p.user_id = u.ID AND m.match_id = p.match_id AND m.match_id = %d $filter_user
				ORDER BY
					points DESC
				LIMIT %d";
					
		$result = $wpdb->get_results($wpdb->prepare($sql, $match_id, $limit));
		foreach ($result as $row) {
			$hpen = '';
			$apen = '';
			if (!$is_group && ($row->home_penalties > 0 || $row->away_penalties > 0)) { 
				$hpen = '('.$row->home_penalties . ')';
				$apen = '('.$row->away_penalties . ')';
			}
			if ($row->ID == $curr_user_id) {
				$style = "style=\"$highlight\"";
			} else {
				$style = 'class="wcup-row"';
			}
			$output .= "<tr $style>";
			$output .= "<td class='wcup-user'>".($avatar ? get_avatar( $row->ID, '16', '', $row->display_name ) : '').' '.$row->display_name."</td>";
			$output .= "<td class='wcup-score'>$row->home_goals{$hpen} $separator $row->away_goals{$apen}</td>";
			if ($is_result) {
				$output .= "<td class='wcup-points'>$row->points</td>";
			} else {
				$output .= "<td class='wcup-points'>&ndash;</td>";
			}
			$output .= "</tr>";
		}
		
		return $output;
	}
	
	/**
	 * Display the guesses for each match and the points awarded
	 * 
	 * @param $match_id  (if -1 all matches)
	 * @param $limit
	 */
	
	/**
	 * Display the guesses for each match and the points awarded
	 * 
	 * @param integer $match_id - ID of match to show the predictions. -1 == all matches
	 * @param number $limit - Limit of predictions to show
	 * @param integer $user_id - ID of player to show the predictions. -1 == all players
	 * @param string $highlight - Customize CSS of logged in user
	 * @return string
	 */
	function user_scores($match_id, $limit = 999999, $user_id = -1, $highlight = '') {
		
		global $wpdb;
		global $current_user;
		
		$output = '';
		$avatar = false;
		$separator = get_option($this->prefix.'match_separator', '-');
		
		if (!is_numeric($limit)) {
			$limit = 999999;
		}
		
		$curr_user_id = -1;
		
		if (!empty($highlight) && is_user_logged_in()) {
			wp_get_current_user();
			$curr_user_id = $current_user->ID;
		}
		
		$filter_user = '';
		if ($user_id != -1) $filter_user = " AND u.ID = $user_id ";
		
		// Show all matches after kickoff expired unless the option 'show predictions' is set
		$throttle = 'AND kickoff < UTC_TIMESTAMP()';
		$show_predictions = get_option($this->prefix.'show_predictions', 0);
		if ($show_predictions) {
			$throttle = '';
		}		
		$sql = "SELECT m.match_id,
					h.country AS home_country, a.country AS away_country,
					h.team_url AS home_url, a.team_url AS away_url,
					h.name AS home_team_name, home_goals, home_penalties,
					a.name AS away_team_name, away_goals, away_penalties,
					is_group, extra_time, is_result
				FROM
					{$wpdb->prefix}{$this->prefix}match m,
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team h,
					{$wpdb->prefix}{$this->prefix}team a
				WHERE
					s.stage_id = m.stage_id AND (m.match_id = %d OR %d = -1) $throttle AND
					h.team_id = m.home_team_id AND a.team_id = m.away_team_id AND (m.is_result OR EXISTS
						(SELECT * FROM {$wpdb->prefix}{$this->prefix}prediction p WHERE p.match_id = m.match_id) )
				ORDER BY
					m.kickoff DESC";
					
		$toprow = @$wpdb->get_results($wpdb->prepare($sql, $match_id, $match_id), OBJECT_K);
//		$this->debug($toprow);
		
		if ($toprow) {
			$output .= "<table class='scores zebra'>";
			$num = count($toprow);
			$i = 1;
			foreach ($toprow as $key=>$row) {
				
				$output .= "<tr class='wcup-header'><th nowrap class='l'>".$this->team_name($row, true).' '. $this->team_score($row, true). $separator .$this->team_score($row, false).' '. $this->team_name($row, false)."</th><th class='wcup-score' title='".__('Prediction', WCP_TD)."'>".__('Pred', WCP_TD)."</th><th class='wcup-points' title='".__('Points', WCP_TD)."'>".__('Pts', WCP_TD)."</th></tr>";
	
				$output .= $this->score_match($key, $limit, $highlight, $curr_user_id, $filter_user, $avatar, $row->is_group, $row->is_result);
				
				if ($i < $num) {
					$output .= "<tr><td colspan='5'>&nbsp;</td></tr>" .PHP_EOL;
				}
				$i++;
			}
			$output .= "</table>";
		}
		
		return $output;
	}
	
	function show_scores($atts, $content = null) {
		
		extract(shortcode_atts(array(
			'match_id' => '', 
			'limit' => 999,
			'user_id' => -1,
			'highlight' => 'background:red;font-weight:bold'
		), $atts));
		
		global $wpdb;
		global $current_user;
	
		ob_start();
		$output = '';
		$output .= '<a class="predictions-link link2" href="javascript:history.go(-1)"><h6>'.__('Back', WCP_TD).'</h6></a>' . PHP_EOL;
		$avatar = false;
		
		if (!is_numeric($limit)) {
			$limit = 999;
		}
		
		$curr_user_id = -1;
		
		if (!empty($highlight) && is_user_logged_in()) {
			wp_get_current_user();
			$curr_user_id = $current_user->ID;
		}
		
		$filter_user = '';
		if ($user_id != -1) $filter_user = " AND r.user_id = $user_id ";
		
		// Show all matches after kickoff expired unless the option 'show predictions' is set
		$throttle = 'AND kickoff < UTC_TIMESTAMP()';
		$show_predictions = get_option($this->prefix.'show_predictions', 0);
		if ($show_predictions) {
			$throttle = '';
		}
		
		$sql = "SELECT m.match_id,
					h.country AS home_country, a.country AS away_country,
					h.team_url AS home_url, a.team_url AS away_url,
					h.name AS home_team_name, home_goals, home_penalties,
					a.name AS away_team_name, away_goals, away_penalties,
					is_group, extra_time, is_result
				FROM
					{$wpdb->prefix}{$this->prefix}match m,
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->prefix}{$this->prefix}team h,
					{$wpdb->prefix}{$this->prefix}team a
				WHERE
					s.stage_id = m.stage_id AND (m.match_id = %d OR %d = -1) $throttle AND
					h.team_id = m.home_team_id AND a.team_id = m.away_team_id AND (m.is_result OR EXISTS
						(SELECT * FROM {$wpdb->prefix}{$this->prefix}prediction p WHERE p.match_id = m.match_id) )
				ORDER BY
					m.kickoff";
					
		$toprow = $wpdb->get_results($wpdb->prepare($sql, $match_id, $match_id), OBJECT_K);
		
		if ($toprow) {
			$output .= "<table class='wcp-group zebra'>";
			$num = count($toprow);
			$i = 1;
			foreach ($toprow as $key=>$row) {
				
				$output .= "<tr class='wcup-header'><th nowrap class='l'>".$this->team_name($row, true).' '. $this->team_score($row, true)."&ndash;".$this->team_score($row, false).' '. $this->team_name($row, false)."</th><th class='wcup-score'>".__('Prediction', WCP_TD)."</th><th class='wcup-points'>".__('Pts', WCP_TD)."</th></tr>";
	
				$output .= $this->score_match($key, $limit, $highlight, $curr_user_id, $filter_user, $avatar, $row->is_group, $row->is_result);
				
				if ($i < $num) {
					$output .= "<tr><td colspan='5'>&nbsp;</td></tr>" .PHP_EOL;
				}
				$i++;
			}
			$output .= "</table>";
		}
		
		$output .= '<a class="predictions-link link3" href="javascript:history.go(-1)"><h6>'.__('Back', WCP_TD).'</h6></a>' . PHP_EOL;
		$output .= ob_get_contents();
		ob_end_clean();
		return $output;
	}	
	
	/**
	 * Display predictions of logged in user.
	 * 
	 * @param number $show_total - Show the total below the table.
	 * @param number $show_result - Show the result aside the prediction.
	 * @return string
	 */
	function user_predictions($show_total = 1, $show_result = 1, $max = 0) {
		
		global $wpdb;
		global $current_user;
		
		$output = '';
		$separator = get_option($this->prefix.'match_separator', '-');
		
		/*
		 * Depending on request, return nice default for widget and shortcode if not logged in.
		 */
		if (!is_user_logged_in()) {
			return "";
		}
		
		wp_get_current_user();
		
		$total = 0;
		if ($show_total) {
			$sql = "SELECT COALESCE(SUM(points),0) AS total
					FROM
						{$wpdb->prefix}{$this->prefix}prediction p
					WHERE
						p.user_id = %d";
			$row = $wpdb->get_row( $wpdb->prepare($sql, $current_user->ID) , OBJECT );
			$total = $row->total;
		}
		
		$limit = '';
		if($max) $limit = "LIMIT $max";
		
		$sql = "SELECT is_group,
			h.name AS home_team_name, a.name AS away_team_name, is_result,
			p.home_goals, p.away_goals, p.home_penalties, p.away_penalties,
			m.home_goals AS mhg, m.away_goals AS mag, m.home_penalties AS mhp, m.away_penalties AS map,
			h.country AS home_country, a.country AS away_country, points
		FROM 
			{$wpdb->prefix}{$this->prefix}match m,
			{$wpdb->prefix}{$this->prefix}prediction p,
			{$wpdb->prefix}{$this->prefix}stage s,
			{$wpdb->prefix}{$this->prefix}team h,
			{$wpdb->prefix}{$this->prefix}team a
		WHERE
			m.stage_id = s.stage_id AND p.match_id = m.match_id AND
			m.home_team_id = h.team_id AND m.away_team_id = a.team_id AND
			p.user_id = %d
		ORDER BY
			kickoff
		$limit";
		
		$result = $wpdb->get_results( $wpdb->prepare($sql, $current_user->ID) , OBJECT );
		$output .= '<table class="group zebra '.$this->prefix.'user_pred_widget">';
		$output .= "<tr class='wcup-header'>";
		$output .= "<th>".__('Match', WCP_TD)."</th>";
		if ($show_result) {
			$output .= "<th class='wcup-score' title='".__('Results', WCP_TD)."'>".__('Res', WCP_TD)."</th>";
		} else {
			$output .= "<th class='wcup-score'>&nbsp;</th>";
		}
		$output .= "<th class='wcup-score' title='".__('Predictions', WCP_TD)."'>".__('Pred', WCP_TD)."</th>";
		$output .= "<th class='wcup-points' title='".__('Points', WCP_TD)."'>".__('Pts', WCP_TD)."</th>";
		$output .= "</tr>";
		foreach ($result as $row) {
			$hpen = '';
			$apen = '';
			$match_result = '';
			if ($row->is_result && $show_result) {
				$mhpen = '';
				$mapen = '';
				if (!$row->is_group && ($row->mhp > 0 || $row->map > 0)) { 
					$mhpen = '('.$row->mhp . ')';
					$mapen = '('.$row->map . ')';
				}
				$match_result = "$row->mhg{$mhpen}&nbsp;$separator&nbsp;$row->mag{$mapen}";
			}
			if (!$row->is_group && ($row->home_penalties > 0 || $row->away_penalties > 0)) { 
				$hpen = '('.$row->home_penalties . ')';
				$apen = '('.$row->away_penalties . ')';
			}
			$output .= "<tr class='wcup-row'>";
			$output .= "<td>".$this->unclean($row->home_team_name)." $separator ".$this->unclean($row->away_team_name)."</td>";
			$output .= "<td class='wcup-score'>$match_result</td>";
			$output .= "<td class='wcup-score'>$row->home_goals{$hpen}&nbsp;$separator&nbsp;$row->away_goals{$apen}</td>";
			if ($row->is_result) {
				$output .= "<td class='wcup-points'>$row->points</td>";
			} else {
				$output .= "<td class='wcup-points'>&ndash;</td>";
			}
			$output .= "</tr>";
		}
		
		if ($show_total) {
			$output .= "<tr>";
			$output .= "<th colspan='3'>".__('Total', WCP_TD)."</th>";
			$output .= "<th class='wcup-points'>$total</th>";
			$output .= "</tr>";
		}
		
		$output .= "</table>";
		
		return $output;
	}
	
	/**
	 * Display logged user predictions in a widget.
	 *
	 * @param number $show_result - Show the result aside the prediction.
	 * @return string
	 */
	function widget_predictions($show_result = 1) {
		
		global $wpdb;
		global $current_user;
		
		$output = '';
		$separator = get_option($this->prefix.'match_separator', '-');
		
		/*
		 * Depending on request, return nice default for widget and shortcode if not logged in.
		 */
		if (!is_user_logged_in()) {
			return "";
		}
		
		wp_get_current_user();
		
		$sql = "SELECT is_group,
			h.name AS home_team_name, a.name AS away_team_name, is_result,
			p.home_goals, p.away_goals, p.home_penalties, p.away_penalties,
			m.home_goals AS mhg, m.away_goals AS mag, m.home_penalties AS mhp, m.away_penalties AS map,
			h.country AS home_country, a.country AS away_country, points
		FROM 
			{$wpdb->prefix}{$this->prefix}match m,
			{$wpdb->prefix}{$this->prefix}prediction p,
			{$wpdb->prefix}{$this->prefix}stage s,
			{$wpdb->prefix}{$this->prefix}team h,
			{$wpdb->prefix}{$this->prefix}team a
		WHERE
			m.stage_id = s.stage_id AND p.match_id = m.match_id AND
			m.home_team_id = h.team_id AND m.away_team_id = a.team_id AND
			p.user_id = %d AND kickoff < UTC_TIMESTAMP()
		ORDER BY
			kickoff DESC
		LIMIT 5";
		
		$last = $wpdb->get_results( $wpdb->prepare($sql, $current_user->ID, $current_user->ID) , OBJECT );
		$last = array_reverse($last);
		
		$sql = "SELECT is_group,
			h.name AS home_team_name, a.name AS away_team_name, is_result,
			p.home_goals, p.away_goals, p.home_penalties, p.away_penalties,
			m.home_goals AS mhg, m.away_goals AS mag, m.home_penalties AS mhp, m.away_penalties AS map,
			h.country AS home_country, a.country AS away_country, points
		FROM 
			{$wpdb->prefix}{$this->prefix}match m,
			{$wpdb->prefix}{$this->prefix}prediction p,
			{$wpdb->prefix}{$this->prefix}stage s,
			{$wpdb->prefix}{$this->prefix}team h,
			{$wpdb->prefix}{$this->prefix}team a
		WHERE
			m.stage_id = s.stage_id AND p.match_id = m.match_id AND
			m.home_team_id = h.team_id AND m.away_team_id = a.team_id AND
			p.user_id = %d AND kickoff > UTC_TIMESTAMP()
		ORDER BY
			kickoff 
		LIMIT 5";
		
		$next = $wpdb->get_results( $wpdb->prepare($sql, $current_user->ID, $current_user->ID) , OBJECT );
		$result = array_merge($last, $next);
		
		$output .= '<table class="group zebra '.$this->prefix.'user_pred_widget">';
		$output .= "<tr class='wcup-header'>";
		$output .= "<th>".__('Match', WCP_TD)."</th>";
		if ($show_result) {
			$output .= "<th class='wcup-score' title='".__('Results', WCP_TD)."'>".__('Res', WCP_TD)."</th>";
		} else {
			$output .= "<th class='wcup-score'>&nbsp;</th>";
		}
		$output .= "<th class='wcup-score' title='".__('Predictions', WCP_TD)."'>".__('Pred', WCP_TD)."</th>";
		$output .= "</tr>";
		foreach ($result as $row) {
			$hpen = '';
			$apen = '';
			$match_result = '-';
			if ($row->is_result && $show_result) {
				$mhpen = '';
				$mapen = '';
				if (!$row->is_group && ($row->mhp > 0 || $row->map > 0)) { 
					$mhpen = '('.$row->mhp . ')';
					$mapen = '('.$row->map . ')';
				}
				$match_result = "$row->mhg{$mhpen}&nbsp;$separator&nbsp;$row->mag{$mapen}";
			}
			if (!$row->is_group && ($row->home_penalties > 0 || $row->away_penalties > 0)) { 
				$hpen = '('.$row->home_penalties . ')';
				$apen = '('.$row->away_penalties . ')';
			}
			$output .= "<tr class='wcup-row'>";
			$output .= "<td nowrap>".$this->unclean($row->home_team_name)." $separator ".$this->unclean($row->away_team_name)."</td>";
			$output .= "<td class='wcup-score'>$match_result</td>";
			$output .= "<td class='wcup-score'>$row->home_goals{$hpen}&nbsp;$separator&nbsp;$row->away_goals{$apen}</td>";
			$output .= "</tr>";
		}
		
		$output .= "</table>";
		
		return $output;
	}
	
	function my_points() {
	
		global $wpdb;
		global $current_user;
	
		/*
		 * Depending on request, return nice default for widget
		* and shortcode if not logged in.
		*/
		if (!is_user_logged_in()) {
			return "";
		}
	
		wp_get_current_user();
	
		$total = 0;
		$sql = "SELECT COALESCE(SUM(points),0) AS total
		FROM
		{$wpdb->prefix}{$this->prefix}prediction p
		WHERE
		p.user_id = %d";
			
		$row = $wpdb->get_row( $wpdb->prepare($sql, $current_user->ID) , OBJECT );
		$total = $row->total;
	
		/*
		* Only want total score, not table.
		*/
		return "$total";
	}
	
	function show_user_predictions($atts, $content = null, $stage = 0) {
		
		extract(shortcode_atts(array(
			'user' => '', 
			'show_total' => 1,
			'playoff' => 0
		), $atts));
		
		global $wpdb;
		global $current_user;
		
		ob_start();
		$output = '';
		$separator = get_option($this->prefix.'match_separator', '-');
		$stage_filter = '';
		if ($playoff) $stage_filter = "AND is_group = 0";
		if ($stage) $stage_filter = "AND m.stage_id = $stage";
		
		$output .= '<a class="predictions-link link4" href="javascript:history.go(-1)"><h6>'.__('Back', WCP_TD).'</h6></a>' . PHP_EOL;
		
		wp_get_current_user();
		
		$total = 0;
		if ($show_total) {
			$sql = "SELECT display_name, COALESCE(SUM(points),0) AS total
					FROM
						{$wpdb->prefix}{$this->prefix}prediction p,
						{$wpdb->users} u,
						{$wpdb->prefix}{$this->prefix}match m,
						{$wpdb->prefix}{$this->prefix}stage s
					WHERE
						p.user_id = %d AND p.user_id = u.ID AND p.match_id = m.match_id AND m.stage_id = s.stage_id $stage_filter";
			
			$row = $wpdb->get_row( $wpdb->prepare($sql, $user) , OBJECT );
			$display_name = $row->display_name;
			$total = $row->total;
		}
		
		$throttle = 'AND kickoff < UTC_TIMESTAMP()';
		$show_predictions = get_option($this->prefix.'show_predictions', 0);
		if ($show_predictions) {
			$throttle = '';
		}
		
		$sql = "SELECT is_group,
			h.name AS home_team_name, a.name AS away_team_name, is_result,
			p.home_goals, p.away_goals, p.home_penalties, p.away_penalties,
			m.home_goals AS mhg, m.away_goals AS mag, m.home_penalties AS mhp, m.away_penalties AS map,
			h.country AS home_country, a.country AS away_country, points
		FROM 
			{$wpdb->prefix}{$this->prefix}match m,
			{$wpdb->prefix}{$this->prefix}prediction p,
			{$wpdb->prefix}{$this->prefix}stage s,
			{$wpdb->prefix}{$this->prefix}team h,
			{$wpdb->prefix}{$this->prefix}team a
		WHERE
			m.stage_id = s.stage_id AND p.match_id = m.match_id AND
			m.home_team_id = h.team_id AND m.away_team_id = a.team_id AND
			p.user_id = %d $throttle $stage_filter
		ORDER BY
			kickoff DESC";
		
		$result = $wpdb->get_results( $wpdb->prepare($sql, $user) , OBJECT );
		$output .= '<h6>' . $display_name . '</h6>';
		$output .= '<table class="group zebra '.$this->prefix.'user_pred_widget">';
		$output .= "<tr class='wcup-header'>";
		$output .= "<th>".__('Match', WCP_TD)."</th>";
		$output .= "<th class='wcup-score' title='".__('Result', WCP_TD)."'>".__('Res', WCP_TD)."</th>";
		$output .= "<th class='wcup-score' title='".__('Prediction', WCP_TD)."'>".__('Pred', WCP_TD)."</th>";
		$output .= "<th class='wcup-points' title='".__('Points', WCP_TD)."'>".__('Pts', WCP_TD)."</th>";
		$output .= "</tr>";
		foreach ($result as $row) {
			$hpen = '';
			$apen = '';
			$match_result = '';
			if ($row->is_result) {
				$mhpen = '';
				$mapen = '';
				if (!$row->is_group && ($row->mhp > 0 || $row->map > 0)) { 
					$mhpen = '('.$row->mhp . ')';
					$mapen = '('.$row->map . ')';
				}
				$match_result = "$row->mhg{$mhpen}&nbsp;$separator&nbsp;$row->mag{$mapen}";
			}
			if (!$row->is_group && ($row->home_penalties > 0 || $row->away_penalties > 0)) { 
				$hpen = '('.$row->home_penalties . ')';
				$apen = '('.$row->away_penalties . ')';
			}
			$output .= "<tr class='wcup-row'>";
			$output .= "<td>".$this->unclean($row->home_team_name)." $separator ".$this->unclean($row->away_team_name)."</td>";
			$output .= "<td class='wcup-score'>$match_result</td>";
			$output .= "<td class='wcup-score'>$row->home_goals{$hpen}&nbsp;$separator&nbsp;$row->away_goals{$apen}</td>";
			if ($row->is_result) {
				$output .= "<td class='wcup-points'>$row->points</td>";
			} else {
				$output .= "<td class='wcup-points'>&ndash;</td>";
			}
			$output .= "</tr>";
		}
		
		if ($show_total) {
			$output .= "<tr>";
			$output .= "<th colspan='3'>".__('Total', WCP_TD)."</th>";
			$output .= "<th class='wcup-points'>$total</th>";
			$output .= "</tr>";
		}
		
		$output .= "</table>";
		
		$output .= '<a class="predictions-link link5" href="javascript:history.go(-1)"><h6>'.__('Back', WCP_TD).'</h6></a>' . PHP_EOL;
		
		$output .= ob_get_contents();
		ob_end_clean();
		return $output;
	}	
}

/**
 * Match stats
 */
class WorldCupStats {
	var $team_id = 0;
	var $played = 0;
	var $won = 0;
	var $lost = 0;
	var $drawn = 0;
	var $gf = 0;
	var $ga = 0;
	var $points = 0;
	var $stage_id = 0;
	var $team_country = '';
	var $team_name = '';
	var $team_url = '';
	var $sort_order = 0;
	var $group_order = 0;
}
?>