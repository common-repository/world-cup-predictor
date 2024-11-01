<?php
/**
 * @package WorldCup
 * @version $Id: wcp-scoring.class.php 2153552 2019-09-09 12:55:03Z landoweb $
 * @author landoweb
 * Copyright Landoweb Programador, 2014
 * 
 */
 
class WorldCupScoring extends WorldCupAdmin {
	
	var $tab = 3;
	var $default = array('exact'=>10, 'win'=>6, 'draw'=>4, 'bonus_goals'=>1, 'bonus_goal_difference'=>0);
	
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	function menu() {
		$predictions = new WorldCupPredictions(0);
		$results = new WorldCupResults(1);
		
		/**
		 * Calculate scores now to reflect results in prediction screen
		 */
		if (isset($_POST[$this->prefix.'scoreMatch'])) {
			check_admin_referer($this->prefix . 'list-matches');
			if (isset($_POST['match_id'])) {
				foreach ($_POST['match_id'] as $id) {
					$this->calculate_scores($id);
				}
			}
			$this->selectTab(1);
		}
?>
		<div class="wrap">
			
			<h2><?php _e('WCP Manager Predictions', WCP_TD) ?></h2>
			
			<div id="<?php echo $this->prefix; ?>tabs" class="ui-tabs">
				
				<ul class="ui-tabs-nav">
					<li><a href="#<?php echo $this->prefix; ?>tabs-1"><?php _e("Predictions", WCP_TD); ?></a></li>
					<li><a href="#<?php echo $this->prefix; ?>tabs-2"><?php _e("Score Matches", WCP_TD); ?></a></li>
					<li><a href="#<?php echo $this->prefix; ?>tabs-3"><?php _e("Users Ranking", WCP_TD); ?></a></li>
					<li><a href="#<?php echo $this->prefix; ?>tabs-4"><?php _e("Configure Scoring", WCP_TD); ?></a></li>
				</ul>

				<div id="<?php echo $this->prefix; ?>tabs-1">
					<?php echo $predictions->predictions(); ?>
				</div>
		
				<div id="<?php echo $this->prefix; ?>tabs-2">
					<?php echo $results->results(); ?>
				</div>
		
				<div id="<?php echo $this->prefix; ?>tabs-3">
					<?php echo $this->ranking(); ?>
				</div>
		
				<div id="<?php echo $this->prefix; ?>tabs-4">
					<?php echo $this->scores(); ?>
				</div>
				
			</div>
			
		</div>
<?php
	}
	
	/**
	 * Configure scoring
	 */
	function scores() {
		
		$scoring = get_option($this->prefix.'scoring', $this->default);
		$scoring = array_merge($this->default, $scoring);
		
		if (isset($_POST[$this->prefix.'savescore'])) {
			check_admin_referer($this->prefix . 'scoring-form');
			$scoring = $_POST['scoring'];
			$valid = true;
			foreach ($scoring as $key=>$value) {
				if (!is_numeric($value)) {
					$this->setMessage(__('Points must be numeric', WCP_TD), true);
					$value = false;
					break;
				}
			}
			if ($valid) {
				update_option($this->prefix.'scoring', $scoring);
				$this->setMessage(__('Changes saved', WCP_TD));
			}
			$this->selectTab($this->tab);
		}
		
		$knockout_msg = __('This includes the penalties in the knockout stage if applicable', WCP_TD);
?>
		<div class="wrap">
			
			<h2><?php _e('Setup points allocation', WCP_TD); ?></h2>
			
			<?php $this->printMessage(); ?>
			
			<form class="form-table <?php echo $this->prefix; ?>form" name="scoring" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
				
				<?php wp_nonce_field( $this->prefix . 'scoring-form' ) ?>
				
				<div class="form-group">
					<label for="exact"><?php _e( 'Points for correct score', WCP_TD ) ?></label>
					<input type="text" name="scoring[exact]" value="<?php echo $scoring['exact'];?>" size="5" />
					<span><?php printf(__('For example predict 3-0 and the result is 3-0 award %d points', WCP_TD), $scoring['exact']); echo '<br />' . $knockout_msg; ?></span>
				</div>
				<div class="form-group">
					<label for="draw"><?php _e( 'Points for guessing a draw', WCP_TD ) ?></label>
					<input type="text" name="scoring[draw]" value="<?php echo $scoring['draw'];?>" size="5" />
						<span><?php printf(__('For example predict 2-2 and the result is 1-1 award %d points', WCP_TD), $scoring['draw']); ?></span>
				</div>
				<div class="form-group">
					<label for="win"><?php _e( 'Wrong score but predicted the correct winner', WCP_TD ) ?></label>
					<input type="text" name="scoring[win]" value="<?php echo $scoring['win'];?>" size="5" />
					<span><?php printf(__('For example predict 1-0 and the result is 2-0 award %d points', WCP_TD), $scoring['win']); echo '<br />' . $knockout_msg; ?></span>
				</div>
				
				<p><?php printf(__('Points above are not cummulative, for example predict 2-2 and the result is 2-2, you gain %d
			points, NOT %d points', WCP_TD), $scoring['exact'], $scoring['exact'] + $scoring['draw']); ?></p>
				
				<h3><?php _e('Bonus points', WCP_TD); ?></h3>
				
				<div class="form-group">
					<label for="bonus_goals"><?php _e( 'Points for correct number of goals', WCP_TD ) ?></label>
					<input type="text" name="scoring[bonus_goals]" value="<?php echo $scoring['bonus_goals'];?>" size="5" />
					<span><?php printf(__('For example predict 3-0 and the result is 3-1 award extra %d points', WCP_TD), $scoring['bonus_goals']); ?></span>
				</div>
				<div class="form-group">
					<label for="bonus_goal_difference"><?php _e( 'Points for correct goal difference', WCP_TD ) ?></label>
					<input type="text" name="scoring[bonus_goal_difference]" value="<?php echo $scoring['bonus_goal_difference'];?>" size="5" />
					<span><?php printf(__('For example predict 5-3 and the result is 3-1 award extra %d points', WCP_TD), $scoring['bonus_goal_difference']); ?></span>
				</div>
				
				<p><strong><?php _e('Bonus points are cummulative', WCP_TD)?></strong></p>
				
				<p class="submit"><input type="submit" name="<?php echo $this->prefix;?>savescore" value="<?php _e( 'Save', WCP_TD ) ?>" class="button-primary" /></p>
				
			</form>
			
		</div>
<?php 
	}
	
	function ranking() {
		
		$r = new WorldCupReport();
		
		$match_id = -1;
		$user_id = -1;
		
		if (isset($_POST['filter_match'])) {
			$match_id = $_POST['filter_match'];
			$this->selectTab(2);
		}
		
		if (isset($_POST['filter_user'])) {
			$user_id = $_POST['filter_user'];
			$this->selectTab(2);
		}
?>
		<div class="wrap" style="padding-bottom:2em;">
			
			<h2><?php _e('Ranking and Scores', WCP_TD); ?></h2>
			
			<div style="width:60%; float:left">
				<form method="POST">
				<p class="submit">
					<?php $matches = new WorldCupMatches(3); ?>
					<?php echo $matches->getMatches($match_id, true, 'filter_match', __('Select Match Result', WCP_TD), 1); ?>
					<input type="submit" name="<?php echo $this->prefix;?>filterScores" value="<?php _e( 'Select', WCP_TD ) ?>" class="button" />
				</p>
				<p class="submit">
					<?php echo $matches->getUsers($user_id, true, 'filter_user', __('All Users', WCP_TD))?>
					<input type="submit" name="<?php echo $this->prefix;?>filterScores" value="<?php _e( 'Filter', WCP_TD ) ?>" class="button" />
				</p>
				</form>
				
				<h3><?php _e('User Points', WCP_TD); ?></h3>
				<?php echo $r->user_scores($match_id, 999999, $user_id); ?>
			</div>
			
			<div style="width:30%; float:right">
				<h3><?php _e('Ranking', WCP_TD); ?></h3>
				<?php echo $r->user_ranking(); ?>
			</div>
		
			<p>&nbsp;</p>
			<div style="clear:both"></div>
		</div>
<?php 
	}
	
	/*
	 * Calculate users scores for thier predictions.
	 * 
	 * TODO - Check wwhen < kickoff time !
	 */
	public function calculate_scores($match_id) {
		
		global $wpdb;
		
		$scoring = get_option($this->prefix.'scoring', $this->default);
		
		// Bit brutal - note wwhen == wwhen prevent updating predicition last modified date.
		$sql = "UPDATE
					{$wpdb->prefix}{$this->prefix}prediction p
				SET
					p.points = 0
				WHERE
					p.match_id = %d";
		$wpdb->query($wpdb->prepare($sql, $match_id));
		
		/*
		 * Exact match
		 */
		if ($scoring['exact'] != 0) {
			$sql = "UPDATE
						{$wpdb->prefix}{$this->prefix}prediction p,
						{$wpdb->prefix}{$this->prefix}match m
					SET
						p.points = %d
					WHERE
						m.is_result = 1 AND
						p.wwhen < m.kickoff AND					
						p.points = 0 AND
						p.match_id = %d AND
						p.match_id = m.match_id AND
						p.home_goals + p.home_penalties = m.home_goals + m.home_penalties AND
						p.away_goals + p.away_penalties = m.away_goals + m.away_penalties";
			$wpdb->query($wpdb->prepare($sql, $scoring['exact'], $match_id));
		}
		
		/*
		 * Win
		 */
		if ($scoring['win'] != 0) {
			$sql = "UPDATE
						{$wpdb->prefix}{$this->prefix}prediction p,
						{$wpdb->prefix}{$this->prefix}match m
					SET
						p.points = %d
					WHERE
						m.is_result = 1 AND
						p.wwhen < m.kickoff AND					
						p.points = 0 AND
						p.match_id = %d AND
						p.match_id = m.match_id AND
						p.home_goals + p.home_penalties > p.away_goals + p.away_penalties AND
						m.home_goals + m.home_penalties > m.away_goals + m.away_penalties";
			$wpdb->query($wpdb->prepare($sql,  $scoring['win'], $match_id));
		
			$sql = "UPDATE
						{$wpdb->prefix}{$this->prefix}prediction p,
						{$wpdb->prefix}{$this->prefix}match m
					SET
						p.points = %d
					WHERE
						m.is_result = 1 AND
						p.wwhen < m.kickoff AND					
						p.points = 0 AND
						p.match_id = %d AND
						p.match_id = m.match_id AND
						p.home_goals + p.home_penalties < p.away_goals + p.away_penalties AND
						m.home_goals + m.home_penalties < m.away_goals + m.away_penalties";
			$wpdb->query($wpdb->prepare($sql, $scoring['win'], $match_id));
		}
		
		/*
		 * Draw
		 */
		if ($scoring['draw'] != 0) {
			$sql = "UPDATE
						{$wpdb->prefix}{$this->prefix}prediction p,
						{$wpdb->prefix}{$this->prefix}match m
					SET
						p.points = %d
					WHERE
						m.is_result = 1 AND
						p.wwhen < m.kickoff AND					
						p.points = 0 AND
						p.match_id = %d AND
						p.match_id = m.match_id AND
						p.home_goals = p.away_goals AND
						m.home_goals = m.away_goals";
			$wpdb->query($wpdb->prepare($sql, $scoring['draw'], $match_id));
		}
		
		if ($scoring['bonus_goals'] != 0) {
			/*
			 * Bonus - home
			 */
			$sql = "UPDATE
						{$wpdb->prefix}{$this->prefix}prediction p,
						{$wpdb->prefix}{$this->prefix}match m
					SET
						p.points = p.points + %d
					WHERE
						m.is_result = 1 AND
						p.wwhen < m.kickoff AND					
						p.match_id = %d AND
						p.match_id = m.match_id AND
						p.home_goals = m.home_goals";
			$wpdb->query($wpdb->prepare($sql, $scoring['bonus_goals'], $match_id));
			/*
			 * Bonus - away
			 */
			$sql = "UPDATE
						{$wpdb->prefix}{$this->prefix}prediction p,
						{$wpdb->prefix}{$this->prefix}match m
					SET
						p.points = p.points + %d
					WHERE
						m.is_result = 1 AND
						p.wwhen < m.kickoff AND					
						p.match_id = %d AND
						p.match_id = m.match_id AND
						p.away_goals = m.away_goals";
			$wpdb->query($wpdb->prepare($sql, $scoring['bonus_goals'], $match_id));
		}
		
		if ($scoring['bonus_goal_difference'] != 0) {
			/*
			 * Bonus - goal difference
			 */
			$sql = "UPDATE
						{$wpdb->prefix}{$this->prefix}prediction p,
						{$wpdb->prefix}{$this->prefix}match m
					SET
						p.points = p.points + %d
					WHERE
						m.is_result = 1 AND
						p.wwhen < m.kickoff AND					
						p.match_id = %d AND
						p.match_id = m.match_id AND
						p.home_goals - p.away_goals = m.home_goals - m.away_goals";
			$wpdb->query($wpdb->prepare($sql, $scoring['bonus_goal_difference'], $match_id));
		}
		
		/*
		 * Mark match as scored
		 */
		$sql = "UPDATE
					{$wpdb->prefix}{$this->prefix}match
				SET
					scored = 1
				WHERE
					match_id = %d";
		$wpdb->query($wpdb->prepare($sql, $match_id));
	}
}

?>