<?php
/**
 * Display a admin panel to manage the plugin.
 * 
 * @package WorldCup
 * @version $Id: wcp-admin.class.php 2153552 2019-09-09 12:55:03Z landoweb $
 * @author landoweb
 * @copyright Copyright Landoweb Programador, 2014
 * 
 */
 
class WorldCupAdmin extends WorldCup {
	
	/**
	 * Constructor
	 */
	function __construct() {
		global $wpdb;
		$wpdb->show_errors(true);  //Fixed in 1.2.4 http://trac.buddypress.org/ticket/2361
		parent::__construct();
	}
	
	/**
	 * Activation hook.
	 * 
	 * Create database structure
	 */
	function activate() {
		
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		
		global $wpdb;
		
		$installed_ver = get_option($this->prefix.'db_version');
		if ($installed_ver == '1.1' || $installed_ver == '1.0') {
			// Remove old database structure from beta version
			$wpdb->query("DROP TABLE {$wpdb->prefix}{$this->prefix}stage");
			$wpdb->query("DROP TABLE {$wpdb->prefix}{$this->prefix}team");
			$wpdb->query("DROP TABLE {$wpdb->prefix}{$this->prefix}venue");
			$wpdb->query("DROP TABLE {$wpdb->prefix}{$this->prefix}match");
			$wpdb->query("DROP TABLE {$wpdb->prefix}{$this->prefix}prediction");
		}
		
		$charset_collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}
		
		// Plugin database table version
		$db_version = "1.9.6";
		
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}{$this->prefix}match` (
		  `match_id` int(11) NOT NULL AUTO_INCREMENT,
		  `match_no` int(11) NOT NULL,
		  `kickoff` datetime NOT NULL,
		  `home_team_id` int(11) NOT NULL,
		  `away_team_id` int(11) NOT NULL,
		  `home_goals` int(11) NOT NULL,
		  `away_goals` int(11) NOT NULL,
		  `home_penalties` int(11) NOT NULL,
		  `away_penalties` int(11) NOT NULL,
		  `venue_id` int(11) NOT NULL,
		  `is_result` BOOL NOT NULL DEFAULT '0', 
		  `extra_time` BOOL NOT NULL DEFAULT '0', 
		  `stage_id` int(11) NOT NULL,
		  `scored` BOOL NOT NULL DEFAULT '0', 
		  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`match_id`)
		) $charset_collate";
		$wpdb->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}{$this->prefix}prediction` (
		  `prediction_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` bigint(20) NOT NULL,
		  `match_id` int(11) NOT NULL,
		  `home_goals` int(11) NOT NULL,
		  `away_goals` int(11) NOT NULL,
		  `home_penalties` int(11) NOT NULL,
		  `away_penalties` int(11) NOT NULL,
		  `points` int(11) NOT NULL,
		  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`prediction_id`),
		  UNIQUE KEY `idx_pred_um` (`user_id`,`match_id`),
		  INDEX  `idx_pred_wwhen` (  `wwhen` )
		) $charset_collate";
		$wpdb->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}{$this->prefix}stage` (
		  `stage_id` int(11) NOT NULL AUTO_INCREMENT,
		  `stage_name` varchar(32) NOT NULL,
		  `is_group` tinyint(1) NOT NULL,
		  `sort_order` int(11) NOT NULL,
		  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`stage_id`)
		) $charset_collate";
		$wpdb->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}{$this->prefix}team` (
		  `team_id` int(20) NOT NULL AUTO_INCREMENT,
		  `name` varchar(64) NOT NULL,
		  `country` char(3) NOT NULL,
		  `team_url` varchar(255) NOT NULL,
		  `group_order` int(11) NOT NULL,
		  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`team_id`)
		) $charset_collate";
		$wpdb->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}{$this->prefix}venue` (
		  `venue_id` int(11) NOT NULL AUTO_INCREMENT,
		  `venue_name` varchar(64) NOT NULL,
		  `venue_url` varchar(255) NOT NULL,
		  `stadium` varchar(64) NOT NULL,
		  `tz_offset` int(11) NOT NULL,
		  `wwhen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`venue_id`)
		) $charset_collate";
		$wpdb->query($sql);
		
		// Installed plugin database table version
		$installed_ver = get_option($this->prefix.'db_version');
		
		// If the database has changed, update the structure while preserving data
		if (empty($installed_ver) || $db_version != $installed_ver) {
			
			if (!empty($installed_ver) && $installed_ver == "1.2") {
				$sql = "ALTER TABLE  `{$wpdb->prefix}{$this->prefix}prediction` ADD UNIQUE  `idx_pred` (  `user_id` ,  `match_id` )";
				$wpdb->query($sql);
				$sql = "ALTER TABLE  `{$wpdb->prefix}{$this->prefix}match` ADD  `scored` BOOL NOT NULL DEFAULT  '0' AFTER  `stage_id`";
				$wpdb->query($sql);
				update_option($this->prefix.'db_version', '1.3');
			}
			
			// Add group_order to teams table to manually sort group tables in the event of a tie.
			if (!empty($installed_ver) && $installed_ver == "1.3") {
				$sql = "ALTER TABLE  `{$wpdb->prefix}{$this->prefix}team` ADD `group_order` INT(11) NOT NULL DEFAULT 0 AFTER  `team_url`";
				$wpdb->query($sql);
				update_option($this->prefix.'db_version', '1.4');
			}
			
			// Add tz_offset to venues table to show match times in local time.
			if (!empty($installed_ver) && $installed_ver == "1.4") {
				$sql = "ALTER TABLE  `{$wpdb->prefix}{$this->prefix}venue` ADD `tz_offset` INT(11) NOT NULL DEFAULT 0 AFTER  `stadium`";
				$wpdb->query($sql);
				update_option($this->prefix.'db_version', '1.5');
			}
			
			// Remove auto update from prediction table.
			if (!empty($installed_ver) && ($installed_ver == "1.5")) {
				$sql = "ALTER TABLE  `{$wpdb->prefix}{$this->prefix}prediction` CHANGE  `wwhen`  `wwhen` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
				$wpdb->query($sql);
				update_option($this->prefix.'db_version', '1.9.1');
			}
			
			// Remove option promo_link of author
			if (!empty($installed_ver)) {
				@delete_option( 'wcup_promo_link' );
			}
		}
 		update_option($this->prefix.'db_version', $db_version);
		
 		add_option($this->prefix.'donated', 0);
 		add_option($this->prefix.'nag', 10);
 		
 		add_option($this->prefix.'countdown_format', __('Next prediction deadline in', WCP_TD) . " %%D%%d, %%H%%h, %%M%%m, %%S%%s");
 		add_option($this->prefix.'browser_locale', 1);
 		add_option($this->prefix.'show_predictions', 0);
		add_option($this->prefix.'match_separator', '-');
		add_option($this->prefix.'match_predictions', '');
		add_option($this->prefix.'player_predictions', '');
		delete_option($this->prefix.'group_stats');  // Clear cache
 		
		/**
		 * Set Capabilities
		 */
		$role = get_role('administrator');
		$role->add_cap($this->prefix.'manager');	// Can manage players, teams etc.
		
		$role = get_role('editor');
		$role->add_cap($this->prefix.'manager');
		
		$support_message = "Siteurl: " . get_site_option( 'siteurl' ) . "\nAdmin: " . get_site_option( 'admin_email' );
		@wp_mail($this->support, __('Plugin successfully activated'), $support_message);
		
		return true;
	}
	
	/**
	 * Deactivation hook.
	 */
	function deactivate() {
 		delete_option($this->prefix.'group_stats');  // Clear cache
		return true;
	}
	
	/**
	 * Create admin menu
	 */
	function admin_menu() {
		
		require_once(dirname(__FILE__).'/wcp-menu.class.php');
		$menu = new WorldCupMenu();
		$scoring = new WorldCupScoring();
		$overview = new WorldCupOverview();
		
		add_menu_page(__('WCP Menu', WCP_TD), __('WCP Cup', WCP_TD), $this->prefix.'manager', $this->prefix.'menu', array($overview, 'overview'), WP_PLUGIN_URL.'/'.WCP_TD .'/images/football.png');
		add_submenu_page($this->prefix.'menu' ,__('Overview', WCP_TD), __('Overview', WCP_TD), $this->prefix.'manager', $this->prefix.'menu' , array($overview, 'overview'));
		add_submenu_page($this->prefix.'menu' ,__('Teams and Matches', WCP_TD), __('Teams and Matches', WCP_TD), $this->prefix.'manager', $this->prefix.'config' , array($menu, 'menu'));
		add_submenu_page($this->prefix.'menu' ,__('Predictions', WCP_TD), __('Predictions', WCP_TD), $this->prefix.'manager', $this->prefix.'predictions' , array($scoring, 'menu'));
		add_submenu_page($this->prefix.'menu' ,__('Help', WCP_TD), __('Help', WCP_TD), $this->prefix.'manager', $this->prefix.'help' , array($this, 'help'));
	}
	
	/**
	 * Style sheet for admin functions
	 */
	function admin_print_styles() {
?>		
<link type="text/css" rel="stylesheet" href="<?php echo WP_PLUGIN_URL . '/' . WCP_TD; ?>/css/style.css?v=1.0" />
<link type="text/css" rel="stylesheet" href="<?php echo WP_PLUGIN_URL . '/' . WCP_TD; ?>/css/admin-style.css?v=1.0" />
<link type="text/css" rel="stylesheet" href="<?php echo WP_PLUGIN_URL . '/' . WCP_TD; ?>/css/jquery-ui.css" />
<?php		
		wp_admin_css( 'css/dashboard' );
	}
	
	/**
	 * Javascript for admin functions
	 */
	function admin_print_scripts() {
		wp_enqueue_script($this->prefix.'admin_js', WP_PLUGIN_URL . '/' . WCP_TD . '/js/wcp-admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-dialog' ));
		wp_enqueue_script( 'postbox' );
	}
	
	/**
	 * Init hook
	 */
	function admin_init() {
		
		register_setting( $this->prefix.'option-group', $this->prefix.'show_predictions');
		register_setting( $this->prefix.'option-group', $this->prefix.'countdown_format');
		register_setting( $this->prefix.'option-group', $this->prefix.'browser_locale');
		register_setting( $this->prefix.'option-group', $this->prefix.'match_separator');
		register_setting( $this->prefix.'option-group', $this->prefix.'match_predictions');
		register_setting( $this->prefix.'option-group', $this->prefix.'player_predictions');
		
		if (isset($_POST[$this->prefix.'already_donated'])) {
			update_option($this->prefix.'donated', 1);
		}
		
		if (isset($_REQUEST['page']) && stripos($_REQUEST['page'], 'wcup') !== false) {
			$donated = get_option($this->prefix.'donated', 0);
			if (!$donated) {
				$count = (int)get_option($this->prefix.'nag') - 1;
				if ($count <= 0) {
					add_action('admin_notices', array(&$this, 'nag'));
					$count = 20;
				}
				update_option($this->prefix.'nag', $count);
			}
		}
	}
	
	function nag() {
		echo '<div class="updated">
				<p>'.sprintf(__('Are you enjoying this plugin?  Please consider donating.', WCP_TD), '').'</p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="float:left;">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
				">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
				</form>
				<form method="post">
		        <p><input class="button-secondary" type="submit" name="'.$this->prefix.'already_donated" value="'.__('Already donated', WCP_TD).'" /></p>
				</form>
		     </div>';
	}
	
	function settings() {
?>
	<form method="post" action="options.php">
		
		<?php settings_fields( $this->prefix.'option-group' );?>
		
		<div class="form-group">
			<label for="<?php echo $this->prefix; ?>countdown_format"><?php _e('Countdown clock format', WCP_TD); ?></label>
			<input style="font-family:'Courier New', Courier, monospace;" type="text" size="60" name="<?php echo $this->prefix; ?>countdown_format" value="<?php echo get_option($this->prefix.'countdown_format', __('Next prediction deadline in', WCP_TD) . ' %%D%%d, %%H%%h, %%M%%m, %%S%%s'); ?>" />
		</div>
		<div class="form-group">
			<label for="<?php echo $this->prefix; ?>browser_locale"><?php _e('Convert kickoff times to local timezone. If unchecked kickoff times are displayed as match local time', WCP_TD); ?></label>
			<input type="checkbox" id="<?php echo $this->prefix; ?>browser_locale" name="<?php echo $this->prefix; ?>browser_locale" value="1" <?php echo get_option($this->prefix.'browser_locale', 1) ? ' checked ' : ''; ?> />
		</div>
		<div class="form-group">
			<label for="<?php echo $this->prefix; ?>show_predictions"><?php _e('Show users predictions before kickoff', WCP_TD); ?></label>
			<input type="checkbox" id="<?php echo $this->prefix; ?>show_predictions" name="<?php echo $this->prefix; ?>show_predictions" value="1" <?php echo get_option($this->prefix.'show_predictions', 1) ? ' checked ' : ''; ?> />
		</div>
		<div class="form-group">
			<label for="<?php echo $this->prefix; ?>match_separator"><?php _e('Separator used in scores of matches', WCP_TD); ?></label>
			<input type="text" size="5" maxlength="7" id="<?php echo $this->prefix; ?>match_separator" name="<?php echo $this->prefix; ?>match_separator" value="<?php echo get_option($this->prefix.'match_separator', '-'); ?>" />
		</div>
		<div class="form-group">
			<label for="<?php echo $this->prefix; ?>match_predictions"><?php _e('Page Predictions by Match', WCP_TD); ?></label>
			<select class="select2" name="<?php echo $this->prefix; ?>match_predictions"> 
				<option value=""><?php _e('Choose Page', WCP_TD);?>...</option> 
				<?php 
					$pages = get_pages(); 
					foreach ( $pages as $page ) {
						if(get_option($this->prefix.'match_predictions') == $page->ID) {
							$selected = ' selected="selected"'; 
						} else { 
							$selected = ''; 
						}
						$option = '<option'.$selected.' value="' . $page->ID . '">';
						$option .= $page->post_title;
						$option .= '</option>';
						echo $option;
					}
				?>
			</select>
		</div>
		<div class="form-group">
			<label for="<?php echo $this->prefix; ?>player_predictions"><?php _e('Page Predictions of User', WCP_TD); ?></label>
			<select class="select2" name="<?php echo $this->prefix; ?>player_predictions"> 
				<option value=""><?php _e('Choose Page', WCP_TD);?>...</option> 
				<?php 
					$pages = get_pages(); 
					foreach ( $pages as $page ) {
						if(get_option($this->prefix.'player_predictions') == $page->ID) { 
							$selected = ' selected="selected"'; 
						} else { 
							$selected = ''; 
						}
						$option = '<option'.$selected.' value="' . $page->ID . '">';
						$option .= $page->post_title;
						$option .= '</option>';
						echo $option;
					}
				?>
			</select>
		</div>
		
		<p class="submit">
			<input type="submit" class="button" value="<?php _e('Save Changes', WCP_TD) ?>" />
		</p>
		
	</form>
<?php		
	}
	
	function help() {
		
		require_once(dirname(__FILE__).'/markdown.php');
		
		$help = file_get_contents(dirname(__FILE__).'/../readme.txt');
		if (empty($help)) {
			return '<h1>No Help</h1>';
		}
		
		// Fudge the header to markdown syntax
		$str = str_replace(array('===', '===','Contributors:',  'Donate link: http://www.wcp.net.br/donate/',  'Tags:',   'Requires at least:',  'Tested up to:',  'Stable tag:'), array('=',   '=',  '* Contributors:','* Donate link: <a href="http://www.wcp.net.br/donate/">http://www.wcp.net.br/donate/</a>','* Tags:', '* Requires at least:','* Tested up to:','* Stable tag:'), $help);
		echo Markdown($str);
		return;
	}
	
	/**
	 * Set to current JQuery tab
	 * 
	 * @param int $i tab number indexed from 0
	 * @return none
	 */
	function selectTab($i) {
?>
		<script type="text/javascript">
		jQuery(function($) {
		  	$("#<?php echo $this->prefix; ?>tabs").tabs({ active: <?php echo $i; ?> });
		});
		</script>
<?php 
	}
	
	/**
	 * Get a list of registered users in a dropdown select box
	 * 
	 * @param $player_id - Preselect this user
	 */
	function getUsers($user_id, $empty = true, $id = 'user_id', $empty_str = 'All Users') {
		
		global $wpdb;
		
		$sql = 'SELECT ID,user_login, display_name FROM ' . $wpdb->users . ' ORDER BY display_name';
		
		$users = $wpdb->get_results( $sql , OBJECT );
		
		$output = '<select name="'.$id.'" id="'.$id.'">';
		if ($empty) $output .= '<option value = "-1">'.$empty_str.'</option>';
		
		foreach ($users as $row) {
			$output .= "<option ";
			if (!is_null($user_id) && $user_id == $row->ID) {
				$output .= " selected ";
			}
			$output .= "value=\"$row->ID\">$row->user_login ($row->display_name)</option>";
		}
		$output .= "</select>";
		
		return $output;
	}
	
	/*
	 * Check is [+/-]HH:MM format
	 */
	function is_hhmm($s) {
		return (preg_match("/^[+-]{0,1}([0-9]{2}):([0-9]{2})/", $s));
	}
	
	/*
	 * Check YYYY-MM-DD HH:MM:SS format
	 */
	function is_datetime($d) {
		if (empty($d) || $d == '0000-00-00 00:00:00') return false;
		return (preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/", $d));
	}
}