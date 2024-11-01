<?php
/**
 * Handle the main admin menu
 * 
 * @package WorldCup
 * @version $Id: wcp-menu.class.php 1647773 2017-04-29 00:49:48Z landoweb $
 * @author landoweb
 * @copyright Copyright Landoweb Programador, 2014
 * 
 */
 
class WorldCupMenu extends WorldCupAdmin {
	
	function menu() {
		
		$teams = new WorldCupTeams(0);
		$venues = new WorldCupVenues(1);
		$stages = new WorldCupStages(2);
		$matches = new WorldCupMatches(3);
?>
		<div class="wrap">
			
			<h2><?php _e('WCP Manager', WCP_TD) ?></h2>
			
			<div id="<?php echo $this->prefix; ?>tabs" class="ui-tabs">
				
				<ul class="ui-tabs-nav">
					<li><a href="#<?php echo $this->prefix; ?>tabs-1"><?php _e("Teams", WCP_TD); ?></a></li>
					<li><a href="#<?php echo $this->prefix; ?>tabs-2"><?php _e("Venues", WCP_TD); ?></a></li>
					<li><a href="#<?php echo $this->prefix; ?>tabs-3"><?php _e("Stages", WCP_TD); ?></a></li>
					<li><a href="#<?php echo $this->prefix; ?>tabs-4"><?php _e("Matches", WCP_TD); ?></a></li>
					<li><a href="#<?php echo $this->prefix; ?>tabs-5"><?php _e("Group Results", WCP_TD); ?></a></li>
				</ul>
				
				<div id="<?php echo $this->prefix; ?>tabs-1">
					<?php echo $teams->teams(); ?>
				</div>
				
				<div id="<?php echo $this->prefix; ?>tabs-2">
					<?php echo $venues->venues(); ?>
				</div>
				
				<div id="<?php echo $this->prefix; ?>tabs-3">
					<?php echo $stages->stages(); ?>
				</div>
				
				<div id="<?php echo $this->prefix; ?>tabs-4">
					<?php echo $matches->matches(); ?>
				</div>
				
				<div id="<?php echo $this->prefix; ?>tabs-5">
					<?php echo $this->group_results(); ?>
				</div>
				
			</div>
		
		</div>
<?php
	}
	
	function group_results() {
		
		$report = new WorldCupReport();
?>
		<div class="wrap" style="padding-bottom:1em;">
			
			<h2><?php _e('Group Results', WCP_TD) ?></h2>
			
			<?php echo $report->group_tables(0, 1, '50%'); ?>
			
		</div>
<?php
	}
}