<?php 
/**
 Plugin Name: World Cup Predictor
 Plugin URI: http://www.wcp.net.br/
 Description: Plugin to manage soccer predictions and present a fantasy football competition for the UEFA Champions League 2019/2020™.
 Version: 1.9.6
 SVN Version: $Id: world-cup-predictor.php 2153552 2019-09-09 12:55:03Z landoweb $
 Author: Landoweb Programador
 Author URI: http://www.landoweb.com.br
 Copyright Landoweb Programador, 2014 (email : orlandostefanin@gmail.com)
 */ 
 
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WCP_TD', 'world-cup-predictor');  // Text domain & plugin dir
load_plugin_textdomain(WCP_TD, false, 'world-cup-predictor/lang');

class WorldCup {
	
	const VERSION = '1.9.6';
	
	var $prefix = 'wcup_';
	
	var $support = 'orlandostefanin@gmail.com';
	
	/**
	 * error handling
	 *
	 * @var boolean
	 */
	private $error = false;
	
	/**
	 * message
	 *
	 * @var string
	 */
	private $message = null;
	
	function __construct() {
		
		global $wpdb;
		
		$valid_locales = array('ar_AE', 'ar_BH', 'ar_DZ', 'ar_EG', 'ar_IQ', 'ar_JO', 'ar_KW', 'ar_LB', 'ar_LY', 'ar_MA', 'ar_OM', 'ar_QA',  'ar_SA', 'ar_SY', 'ar_TN', 'ar_YE', 'be_BY', 'bg_BG', 'ca_ES', 'cs_CZ', 'da_DK', 'de_AT', 'de_CH', 'de_DE', 'de_LU','el_GR', 'en_AU', 'en_CA', 'en_GB', 'en_NZ', 'en_PH', 'en_US', 'en_ZA', 'es_AR', 'es_BO', 'es_CL', 'es_CR', 'es_DO', 'es_EC', 'es_ES', 'es_GT', 'es_HN', 'es_MX', 'es_NI', 'es_PA', 'es_PE', 'es_PR', 'es_PY', 'es_SV', 'es_UY', 'es_VE', 'et_EE', 'eu_ES', 'fi_FI', 'fo_FO', 'fr_BE', 'fr_CA', 'fr_CH', 'fr_FR', 'fr_LU', 'gl_ES', 'gu_IN', 'he_IL', 'hi_IN', 'hr_HR', 'hu_HU', 'id_ID', 'is_IS', 'it_CH', 'it_IT', 'ja_JP', 'ko_KR', 'lt_LT', 'lv_LV', 'mk_MK', 'mn_MN', 'ms_MY', 'nb_NO', 'nl_BE', 'nl_NL', 'pl_PL', 'pt_BR', 'pt_PT', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sq_AL', 'sr_RS', 'sv_FI', 'sv_SE', 'ta_IN', 'te_IN', 'tr_TR', 'uk_UA', 'ur_PK', 'vi_VN', 'zh_CN', 'zh_HK', 'zh_TW');
		
		if (defined('WPLANG') && WPLANG && in_array(WPLANG, $valid_locales)) {
			$wpdb->query($wpdb->prepare('SET lc_time_names = %s', WPLANG));
		}	
		
		remove_filter('the_content', 'my_formatter', 99);
	}
	
	function print_styles() {
		wp_enqueue_style($this->prefix.'style', WP_PLUGIN_URL . '/' . WCP_TD . '/css/style.css?v=1.1.01');
	}
	
	function print_scripts() {
		wp_enqueue_script($this->prefix . 'js', WP_PLUGIN_URL . '/' . WCP_TD.'/js/wcp.js', array( 'jquery' ));
	}
	
	/**
	 * Initialize the plugin widgets
	 */
	function widgets_init() {
		/**
		 * For the results table
		 */
		require_once(dirname(__FILE__).'/wcp-widgets.class.php');
		register_widget('WorldCupRankingWidget');
		register_widget('WorldCupPredictionsWidget');
		register_widget('WorldCupStandingsWidget');
		register_widget('WorldCupMyPointsWidget');
	}
	
	/**
	 * Process shortcode [world-cup-predictor]
	 * 
	 */
	function shortcode($atts) {
		
		extract(shortcode_atts(array(
			'predict' => 1,
			'ranking' => 0,
			'tables' => 0,					// Show group tables
			'scores' => 0,					// All users predictions by match id
			'results' => 0,					// Match results
			'user' => 0,					// Display current users predictions
			'my_points' => 0,				// Display the total points of current user
			'stage' => 0,					// Group id - zero = all
			'show_results' => 1,			// Show match results below group tables
			'limit' => 999999,				// Limit ranking and prediction scores
			'highlight' => '',				// CSS style to apply to current user in rankings
			'show_total' => 1,				// Display total in user predictions
			'group' => false,				// Show only group stage matches
			'kickoff' => false,				// Order matches by kickoff time
			'predict_penalties' => false,	// Users can predict penalty goals.
			'avatar' => 1,					// Display users' avatar
			'team' => 0,					// Show match results for a specific team
			'playoff' => 0					// Display ranking just of knockout stage
		), $atts));
		
		$output = '';
		
		if (!is_numeric($stage)) {
			$stage = 0;
		}
		
		if (!is_numeric($limit)) {
			$limit = 999999;
		}
		
		if ($ranking) {
			require_once(dirname(__FILE__).'/wcp-reports.class.php');
			$r = new WorldCupReport();
			$output =  $r->user_ranking($limit, $avatar, $highlight, $stage, $playoff);
			return $output;
		}
		
		if ($tables) {
			require_once(dirname(__FILE__).'/wcp-reports.class.php');
			$r = new WorldCupReport();
			$output =  $r->group_tables($stage, $show_results);
			return $output;
		}
		
		if ($scores) {
			require_once(dirname(__FILE__).'/wcp-reports.class.php');
			$r = new WorldCupReport();
			$output =  $r->user_scores($scores, $limit, -1, $highlight);
			return $output;
		}
		
		if ($results) {
			require_once(dirname(__FILE__).'/wcp-reports.class.php');
			$r = new WorldCupReport();
			$output =  $r->results($stage, '100%', $team, $group, $kickoff);
			return $output;
		}
		
		if ($user) {
			require_once(dirname(__FILE__).'/wcp-reports.class.php');
			$r = new WorldCupReport();
			$output =  $r->user_predictions($show_total, $show_results);
			return $output;
		}
		
		if ($my_points) {
			require_once(dirname(__FILE__).'/wcp-reports.class.php');
			$r = new WorldCupReport();
			$output =  $r->my_points();
			return $output;
		}		
		
		if ($predict) {
			require_once(dirname(__FILE__).'/wcp-predict.class.php');
			$p = new WorldCupPredict();
			$output =  $p->prediction_form($stage, $limit, $group, $kickoff, $predict_penalties);
			return $output;
		}
	}
	
	function debug($var, $echo = true) {
		$output = "<pre>";
		$output .= print_r($var, true);
		$output .= "</pre>";
		if ($echo) echo $output;
		return $output;
	}
	
	/**
	 * Clean the input string of dangerous input.
	 * @param $str input string
	 * @return cleaned string.
	 */
	function clean($str) {
		$str = strip_tags($str);
		return @trim(htmlspecialchars($str, ENT_QUOTES));
	}
	
	/**
	 * Reverse clean() after getting from DB
	 * @param $str input string
	 * @return cleaned string.
	 */
	function unclean($str) {
		return stripslashes($str);
	}
	
	function flag($country) {
		$class = ($country != 'xxx' ? $this->prefix.'flag' : '');
		return '<img alt="" width="32" class="'.$class.'" src="'.WP_PLUGIN_URL.'/'.WCP_TD.'/images/uefa-2019/'.strtolower($country).'.png" />';
	}
	
	/**
	 * set message
	 *
	 * @param string $message
	 * @param boolean $error triggers error message if true
	 * @return none
	 */
	function setMessage( $message, $error = false ) {
		$type = 'success';
		if ( $error ) {
			$this->error = true;
			$type = 'error';
		}
		$this->message[$type] = $message;
	}
	
	/**
	 * return message
	 *
	 * @param none
	 * @return string
	 */
	function getMessage() {
		if (is_null($this->message) || (empty($this->message))) return false;
		
		if ( $this->error )
			return $this->message['error'];
		else
			return $this->message['success'];
	}
	
	/**
	 * print formatted message
	 *
	 * @param none
	 * @return string
	 */
	function printMessage($echo = true) {
		if ($this->getMessage() === false)  return '';
		
		$str = '';
		
		if ( $this->error )
			$str = "<div class='message error'><p>".$this->getMessage()."</p></div>";
		else
			$str = "<div class='message saved fade'><p><strong>".$this->getMessage()."</strong></p></div>";
		$this->message = null;
		
		if (!$echo) return $str;
		echo $str;
	}
	
	/*
	 * Check for positive integer 
	 * TODO allow leading zeros !
	 */
	function isint($i) {
		return ((string)$i === (string)(int)$i && (int)$i >= 0);
	}
	
	/*
	 * Return an href
	 */
	function mklink($str, $url, $title) {
		$link = $str;
		if (!empty($url)) {
			$link = '<a href="'.$url.'" title="'.$title.'" alt="'.$title.'" target="_blank" >'.$str.'</a>';
		} else {
			$link = '<span title="'.$title.'">'.$str.'</span>';
		}
		return $link;
	}
	
	function format_date($mysql_date) {
		return mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $mysql_date);
	}
}

function wcp_uninstall() {
	global $wpdb;
	
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wcup_stage");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wcup_team");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wcup_venue");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wcup_match");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wcup_prediction");
	
	delete_option( 'wcup_db_version' );
	delete_option( 'wcup_donated' );
	delete_option( 'wcup_nag' );
	delete_option( 'wcup_show_predictions' );
	delete_option( 'wcup_group_stats');
	delete_option( 'wcup_scoring' );
	delete_option( 'wcup_countdown_format' );
	delete_option( 'wcup_browser_locale' );
	delete_option( 'wcup_match_separator' );
	delete_option( 'wcup_match_predictions' );
	delete_option( 'wcup_player_predictions' );	
	
	$roles = array("subscriber", "contributor", "author", "editor", "administrator");
	foreach ($roles as $role) {
		$arole = get_role($role);
		$arole->remove_cap('wcup_manager');
	}
}

$wcp = new WorldCup();

add_shortcode(WCP_TD, array(&$wcp, 'shortcode'));

add_action('wp_print_styles', array(&$wcp, 'print_styles'));
add_action('wp_print_scripts', array(&$wcp, 'print_scripts'));
add_action('widgets_init', array(&$wcp, 'widgets_init'));

require_once(dirname(__FILE__).'/wcp-predict.class.php');
$wcpp = new WorldCupPredict();
add_action('wp_ajax_worldcuppredictor_ajax', array(&$wcpp,'ajax'));

if (is_admin()) {
	
	require_once(dirname(__FILE__).'/admin/wcp-admin.class.php');
	require_once(dirname(__FILE__).'/admin/wcp-teams.class.php');
	require_once(dirname(__FILE__).'/admin/wcp-venues.class.php');
	require_once(dirname(__FILE__).'/admin/wcp-stages.class.php');
	require_once(dirname(__FILE__).'/admin/wcp-matches.class.php');
	require_once(dirname(__FILE__).'/admin/wcp-predictions.class.php');
	require_once(dirname(__FILE__).'/admin/wcp-scoring.class.php');
	require_once(dirname(__FILE__).'/admin/wcp-overview.class.php');
	require_once(dirname(__FILE__).'/admin/wcp-results.class.php');
	require_once(dirname(__FILE__).'/wcp-reports.class.php');
	
	$wcpadmin = new WorldCupAdmin();
	
	// Activation function
	register_activation_hook(__FILE__, array(&$wcpadmin, 'activate'));
	register_deactivation_hook(__FILE__, array(&$wcpadmin, 'deactivate'));
	
	// Register a uninstall hook to automatically remove all tables & option
	if ( function_exists('register_uninstall_hook') )
		register_uninstall_hook( __FILE__ , 'wcp_uninstall' );
	
	add_action('admin_menu', array(&$wcpadmin, 'admin_menu'));
	add_action('admin_print_scripts', array(&$wcpadmin, 'admin_print_scripts'));
	add_action('admin_print_styles', array(&$wcpadmin, 'admin_print_styles'));
	add_action('admin_init', array(&$wcpadmin, 'admin_init'));
}

if(isset($_GET['wcp']) && $_GET['wcp'] == 'scores') {
	add_filter('the_content','wcp_template_scores_content');
}

if(isset($_GET['wcp']) && $_GET['wcp'] == 'predictions') {
	add_filter('the_content','wcp_template_predictions_content');
}

function wcp_template_scores_content($match_id = NULL, $limit = NULL, $user_id = -1) {
	
	global $wpdb;
	
	require_once(dirname(__FILE__).'/wcp-reports.class.php');
	$r = new WorldCupReport();
	
	$match_id = isset($_GET['match_id']) ? $_GET['match_id'] : $match_id;
	
	// Performs method show_scores() that will call the method score_match() to show the guesses
	return $r->show_scores(array('match_id'=>$match_id));
}

function wcp_template_predictions_content($user = NULL, $schedule_id = NULL, $month = NULL) {
	
	global $wpdb;
	
	require_once(dirname(__FILE__).'/wcp-reports.class.php');
	$r = new WorldCupReport();
	
	$user = isset($_GET['user']) ? $_GET['user'] : $user;
	$playoff = isset($_GET['playoff']) ? $_GET['playoff'] : 0;
	$stage = isset($_GET['stage']) ? $_GET['stage'] : 0;
	
	// Performs method show_user_predictions() that will call the method score_match() to show the guesses
	return $r->show_user_predictions(array('user'=>$user,'playoff'=>$playoff,'stage'=>$stage));
}

?>