<?php
/**
 * Prediction Administration functions for the WorldCup plugin.
 * 
 * @package WorldCup
 * @version $Id: wcp-predictions.class.php 2153552 2019-09-09 12:55:03Z landoweb $
 * @author landoweb
 * @copyright Copyright Landoweb Programador, 2014
 * 
 */
 
class WorldCupPredictions extends WorldCupAdmin {
	
	var $tab;
	
	/*
	 * Constructor
	 */
	function __construct($tab) {
		$this->tab = $tab;
		parent::__construct();
	}
	
	/*
	 * Display and manage predictions
	 */
	function predictions() {
		
		global $wpdb;
		
		$user_id = $match_id = -1;
		$home_goals = $away_goals = $points = $home_penalties = $away_penalties = 0;
		$wwhen = '2017-01-01 12:00:00';
		$prediction_id = -1;
		$filter_stage_id = -1;
		$filter_user_id = -1;
		$filter_team_id = -1;
		$filter_match_type = -1;
		
		if (isset($_POST[$this->prefix.'modifyPredictionCancel'])) {
			check_admin_referer($this->prefix . 'prediction-form');
			$this->selectTab($this->tab);
		}
		
		if (isset($_POST[$this->prefix.'filterPrediction'])) {
			check_admin_referer($this->prefix . 'prediction-form');
			extract($_POST, EXTR_IF_EXISTS);
			$this->selectTab($this->tab);
		}
		
		if (isset($_POST[$this->prefix.'addPrediction'])) {
			check_admin_referer($this->prefix . 'prediction-form');
			
			extract($_POST, EXTR_IF_EXISTS);
			
			// Save to database
			if ($this->insert($user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen) !== false) {
				$user_id = $match_id = -1;
				$home_goals = $away_goals = $home_penalties = $away_penalties = $points = 0;
				$wwhen = '2017-01-01 12:00:00';
				$this->setMessage(__('Changes saved', WCP_TD));
			}
			$this->selectTab($this->tab);
		}
		
		/*
		 * Actually modify the result.
		 */
		if (isset($_POST[$this->prefix.'modifyPrediction'])) {
			check_admin_referer($this->prefix . 'prediction-form');
			
			extract($_POST, EXTR_IF_EXISTS);
			
			if ($this->update($prediction_id, $user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen) !== false) {
				$user_id = $match_id = -1;
				$home_goals = $away_goals = $home_penalties = $away_penalties = $points = 0;
				$wwhen = '2017-01-01 12:00:00';
				$prediction_id = -1;
				$this->setMessage(__('Changes saved', WCP_TD));
			}
			$this->selectTab($this->tab);
		}
		
		/*
		 * Process GET request to retreive the prediction details and pre-fill
		 * the form.
		 */
		if (isset($_GET['modifyprediction_id'])) {
			$prediction_id = $_GET['modifyprediction_id'];
			$row = $this->get($prediction_id);
			if (empty($row)) $prediction_id = -1;	// Didn't find row. Prevent modification
			extract($row, EXTR_IF_EXISTS);
			$this->selectTab($this->tab);
		}
		
		if (isset($_POST[$this->prefix.'deletePrediction'])) {
			check_admin_referer($this->prefix . 'list-predictions');
			if (isset($_POST['prediction_id'])) {
				foreach ($_POST['prediction_id'] as $id) {
					$this->delete($id);
				}
				$this->setMessage(__('Changes saved', WCP_TD));
			}
			$this->selectTab($this->tab);
		}
?>
		<div class="wrap">
			
			<h2><?php _e('Manage predictions', WCP_TD) ?></h2>
			
			<p><?php _e('For a manually entered prediction the prediction time must be before the kickoff time.', WCP_TD); ?></p>
			
			<?php $this->printMessage(); ?>
			
			<form class="form-table <?php echo $this->prefix; ?>form" name="prediction" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
				
				<?php wp_nonce_field( $this->prefix . 'prediction-form' ) ?>
				
				<div class="form-group">
					<label for="user_id"><?php _e( 'User', WCP_TD ) ?></label>
					<?php echo $this->getUsers($user_id, true, 'user_id', __('Select user', WCP_TD)); ?>
				</div>
				<div class="form-group">
					<label for="match_id"><?php _e( 'Match', WCP_TD ) ?></label>
					<?php $matches = new WorldCupMatches(3); ?>
					<?php echo $matches->getMatches($match_id, true, 'match_id', __('Select match', WCP_TD)); ?>					
				</div>
				<div class="form-group">
					<label for="home_goals"><?php _e( 'Goals A', WCP_TD ) ?></label>
					<input type="text" name="home_goals" value="<?php echo $home_goals;?>" size="4" />
					<label for="home_penalties"><?php _e( 'Penalties A', WCP_TD ) ?></label>
					<input type="text" name="home_penalties" value="<?php echo $home_penalties;?>" size="4" />
				</div>
				<div class="form-group">
					<label for="away_goals"><?php _e( 'Goals B', WCP_TD ) ?></label>
					<input type="text" name="away_goals" value="<?php echo $away_goals;?>" size="4" />
					<label for="away_penalties"><?php _e( 'Penalties B', WCP_TD ) ?></label>
					<input type="text" name="away_penalties" value="<?php echo $away_penalties;?>" size="4" />
				</div>
				<div class="form-group">
					<label for="points"><?php _e( 'Points', WCP_TD ) ?></label>
					<input type="text" name="points" value="<?php echo $points;?>" size="4" />
				</div>
				<div class="form-group">
					<label for="wwhen"><?php _e( 'Prediction time', WCP_TD ) ?></label>
					<input type="text" name="wwhen" value="<?php echo $wwhen;?>" size="20" />
				</div>
				
				<p class="submit">
					<?php echo $this->getUsers($filter_user_id, true, 'filter_user_id', __('All Users', WCP_TD)); ?>
					<?php $stages = new WorldCupStages(2); ?>
					<?php echo $stages->getStages($filter_stage_id, true, 'filter_stage_id', __('All Stages', WCP_TD)); ?>
					<?php $teams = new WorldCupTeams(0); ?>
					<?php echo $teams->getTeams($filter_team_id, true, 'filter_team_id', __('All Teams', WCP_TD)); ?>
					<?php echo $this->match_types($filter_match_type); ?>
					<input type="submit" name="<?php echo $this->prefix;?>filterPrediction" value="<?php _e( 'Filter', WCP_TD ) ?>" class="button" />
				</p>
<?php 
			if  ($prediction_id != -1) {
?>
				<input type="hidden" value="<?php echo $prediction_id; ?>" name="prediction_id"></input>
				<p class="submit">
					<input type="submit" name="<?php echo $this->prefix;?>modifyPrediction" value="<?php _e( 'Modify Prediction', WCP_TD ) ?>" class="button-primary" />
					<input type="submit" name="<?php echo $this->prefix;?>modifyPredictionCancel" value="<?php _e( 'Cancel', WCP_TD ) ?>" class="button" />
				</p>
<?php 
			} else {
?>
				<p class="submit"><input type="submit" name="<?php echo $this->prefix;?>addPrediction" value="<?php _e( 'Add Prediction', WCP_TD ) ?>" class="button-primary" /></p>
<?php 
			}
?>
			</form>
<?php 
		$user_filter = $stage_filter = $team_filter = $match_filter = '';
		if ($filter_user_id != -1) $user_filter = ' AND u.ID = ' . $filter_user_id;
		if ($filter_stage_id != -1) $stage_filter = ' AND s.stage_id = ' . $filter_stage_id;
		if ($filter_team_id != -1) $team_filter = ' AND (h.team_id = ' . $filter_team_id . ' OR a.team_id = ' . $filter_team_id . ')';
		if ($filter_match_type == 1) $match_filter = ' AND m.is_result = 1';
		if ($filter_match_type == 0) $match_filter = ' AND m.is_result = 0';
		
		/**
		 * Show the current prediction list in a table
		 */
		$sql = "SELECT prediction_id, u.display_name, match_no, 
					h.name AS home_team_name, a.name AS away_team_name, s.stage_name,
					p.home_goals, p.away_goals, p.home_penalties, p.away_penalties, p.wwhen, p.points, is_group
				FROM 
					{$wpdb->prefix}{$this->prefix}prediction p,
					{$wpdb->prefix}{$this->prefix}match m,
					{$wpdb->prefix}{$this->prefix}team h,
					{$wpdb->prefix}{$this->prefix}team a,
					{$wpdb->prefix}{$this->prefix}stage s,
					{$wpdb->users} u
				WHERE
					p.match_id = m.match_id AND u.ID = p.user_id AND
					m.home_team_id = h.team_id AND m.away_team_id = a.team_id AND
					s.stage_id = m.stage_id
					$user_filter $stage_filter $team_filter $match_filter
				ORDER BY
					u.display_name, s.sort_order, m.kickoff
				LIMIT 100";

					
		$result = $wpdb->get_results( $sql , OBJECT );

?>		
			<p><strong><?php _e('All times are UTC', WCP_TD); ?></strong></p>
			<form id="listpredictions" name="listpredictions" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
			
				<?php wp_nonce_field( $this->prefix . 'list-predictions' ) ?>
				
				<table class="<?php echo $this->prefix; ?>table" width="90%">
					<thead>
						<tr>
							<th><input type="checkbox" value="" id="selectallprediction"/> <?php _e('Del', WCP_TD) ?></th>
							<th><?php _e('ID', WCP_TD) ?></th>
							<th><?php _e('User', WCP_TD) ?></th>
							<th><?php _e('Points', WCP_TD) ?></th>
							<th><?php _e('#', WCP_TD) ?></th>
							<th><?php _e('Stage', WCP_TD) ?></th>
							<th><?php _e('Team', WCP_TD) ?></th>
							<th><?php _e('A', WCP_TD) ?></th>
							<th>&nbsp;</th>
							<th><?php _e('B', WCP_TD) ?></th>
							<th><?php _e('Team', WCP_TD) ?></th>
							<th><?php _e('Last Modified', WCP_TD) ?></th>
						</tr>
					</thead>
					<tbody>
<?php
					foreach ($result as $row) {
?>
						<tr>
							<td>
								<input type="checkbox" value="<?php echo $row->prediction_id; ?>" name ="prediction_id[<?php echo $row->prediction_id;?>]"/>
							</td>
							<td>
								<a title="<?php _e('Modify this prediction', WCP_TD); ?>" href="<?php echo $_SERVER['REQUEST_URI'] ?>&amp;modifyprediction_id=<?php echo $row->prediction_id; ?>"><?php echo $row->prediction_id; ?></a>
							</td>
							<td><?php echo $row->display_name; ?></td>
							<td><?php echo $row->points; ?></td>
							<td><?php echo $row->match_no; ?></td>
							<td><?php echo $this->unclean($row->stage_name); ?></td>
							<td><?php echo $this->unclean($row->home_team_name); ?></td>
							<td>
							<?php echo $this->unclean($row->home_goals);
								if (!$row->is_group) { 
									echo ' ('.$this->unclean($row->home_penalties) . ')';
								}
							?>
							</td>
							<td>-</td>
							<td>
							<?php echo $this->unclean($row->away_goals);
								if (!$row->is_group) { 
									echo ' ('.$this->unclean($row->away_penalties) . ')';
								}
							?>
							</td>
							<td><?php echo $this->unclean($row->away_team_name); ?></td>
							<td><?php echo $row->wwhen; ?></td>
						</tr>
<?php
					}
?>
					</tbody>
				</table>
				
				<p>
					<input type="submit" name="<?php echo $this->prefix; ?>deletePrediction" value="<?php _e( 'Delete Selected', WCP_TD ); ?>" class="button" />
				</p>
			</form>
			
		</div>
<?php
	}
	
	private function match_types($filter_match_type) {
		
		$output = '<select name="filter_match_type" id="filter_match_type">';
		$output .= "<option " . ($filter_match_type == -1 ? 'selected' : '') . ' value="-1">'.__('All Matches', WCP_TD).'</option>';
		$output .= "<option " . ($filter_match_type == 1 ? 'selected' : '') . ' value="1">'.__('Results only', WCP_TD).'</option>';
		$output .= "<option " . ($filter_match_type == 0 ? 'selected' : '') . ' value="0">'.__('Pending matches only', WCP_TD).'</option>';
		$output .= '</select>';
		
		return $output;
	}
	
	/**
	 * Check valid input
	 */
	private function valid($user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen) {
		
		if (!is_numeric($home_goals) || !is_numeric($away_goals) || !is_numeric($points)) {
			$this->setMessage(__("Goals and points must be numeric", WCP_TD), true);
			return false;
		}
		
		if (!is_numeric($home_penalties) || !is_numeric($away_penalties)) {
			$this->setMessage(__("Penalties must be numeric", WCP_TD), true);
			return false;
		}
		
		if ($user_id == -1 || $match_id == -1) {
			$this->setMessage(__("Select a user and match", WCP_TD), true);
			return false;
		}
		
		if (!$this->is_datetime($wwhen)) {
			$this->setMessage(__("Prediction time must be valid YYYY-MM-DD HH:MM:SS date time format.", WCP_TD), true);
			return false;
		}
		
		return true;
	}
	
	/**
	 * Insert row
	 */
	private function insert($user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen) {
		global $wpdb;

		if (!$this->valid($user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen)) {
			return false;
		}
		
		$sql = "INSERT INTO {$wpdb->prefix}{$this->prefix}prediction (user_id, match_id, home_goals, away_goals, home_penalties, away_penalties, points, wwhen)
				VALUES (%d, %d, %d, %d, %d, %d, %d, %s)";
		
		$ret = $wpdb->query( $wpdb->prepare( $sql, $user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen) );
		
		if ($ret == 1) {
			return $wpdb->insert_id;
		} else {
			return false;
		}
	}
	
	/**
	 * Update row
	 */
	private function update($prediction_id, $user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen) {
		global $wpdb;
		
		if (!$this->valid($user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen)) {
			return false;
		}
		
		$sql = "UPDATE {$wpdb->prefix}{$this->prefix}prediction
				SET
					user_id = %d,
					match_id = %d,
					home_goals = %d,
					away_goals = %d,
					home_penalties = %d,
					away_penalties = %d,
					points = %d,
					wwhen = %s
				WHERE prediction_id = %d";
		
		return $wpdb->query( $wpdb->prepare( $sql, $user_id, $match_id, $home_goals, $away_goals, $home_penalties, $away_penalties, $points, $wwhen, $prediction_id ) );
	}
	
	/**
	 * Get row by id.
	 */
	private function get($prediction_id) {
		global $wpdb;
		
		$sql = "SELECT user_id, match_id, home_goals, away_goals, home_penalties, away_penalties, points, wwhen
				FROM {$wpdb->prefix}{$this->prefix}prediction WHERE prediction_id = %d";
		
		$row = $wpdb->get_row( $wpdb->prepare($sql, $prediction_id) , ARRAY_A );
		
		if (!is_null($row)) {
			foreach ($row as $key=>$r) {
				$row[$key] = $this->unclean($r);
			}
		}
		
		return ($row ? $row : array());
	}
	
	/**
	 * Delete row
	 */
	private function delete($prediction_id) {
		global $wpdb;
		
		$sql = "DELETE FROM {$wpdb->prefix}{$this->prefix}prediction WHERE prediction_id = %d";
		
		$wpdb->query( $wpdb->prepare( $sql, $prediction_id ) );
	}
}

?>