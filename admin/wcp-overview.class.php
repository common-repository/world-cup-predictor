<?php
/**
 * Handle the main admin welcome screen
 * 
 * @package WorldCup
 * @version $Id: wcp-overview.class.php 2153552 2019-09-09 12:55:03Z landoweb $
 * @author landoweb
 * @copyright Copyright Landoweb Programador, 2014
 * 
 */
 
class WorldCupOverview extends WorldCupAdmin {
	
	function dashboard() {
		
		global $wpdb;
		$disabled = $created = '';
		
		$teams    = intval( $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}{$this->prefix}team WHERE country <> 'xxx'") );
		$venues = intval( $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}{$this->prefix}venue") );
		$matches    = intval( $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}{$this->prefix}match") );
		$predictions = intval( $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}{$this->prefix}prediction") );
		
		if ($teams || $venues || $matches) {
			$disabled = ' disabled ';
		}

		if(get_option($this->prefix.'created_pages') || get_option($this->prefix.'match_predictions') || get_option($this->prefix.'player_predictions')) {
			$created = ' disabled ';
		}
		
		?>
		
		<p class="sub"><?php _e('At a Glance', WCP_TD); ?></p>
		<div class="table">
			<table>
				<tbody>
					<tr class="first">
						<td class="first b"><?php echo $teams; ?></td>
						<td class="t"><?php echo _n( 'Team', 'Teams', $teams, WCP_TD ); ?></td>
					</tr>
					<tr class="first">
						<td class="first b"><?php echo $venues; ?></td>
						<td class="t"><?php echo _n( 'Venue', 'Venues', $venues, WCP_TD ); ?></td>
					</tr>
					<tr class="first">
						<td class="first b"><?php echo $matches; ?></td>
						<td class="t"><?php echo _n( 'Match', 'Matches', $matches, WCP_TD ); ?></td>
					</tr>
					<tr class="first">
						<td class="first b"><?php echo $predictions; ?></td>
						<td class="t"><?php echo _n( 'Prediction', 'Predictions', $predictions, WCP_TD ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="pages">
			<?php if(current_user_can($this->prefix . 'manager')): ?>
				<form method="POST">
					<div class="form-group">
						<label for="<?php echo $this->prefix; ?>create_pages"><?php _e('Here you can create predictions pages, group tables and rankings automatically.', WCP_TD) ?></label>
					</div>					
					<p class="submit">
						<input <?php echo $created; ?> type="submit" id="<?php echo $this->prefix; ?>create_pages" name="<?php echo $this->prefix; ?>create_pages" class="button rbutton" value="<?php _e('Create Pages', WCP_TD) ?>" />
					</p>
				</form>
			<?php endif; ?>

		</div>

		<div class="versions">
			<?php _e('Here you can import your teams, venues and match schedules.', WCP_TD) ?>
			<?php if(current_user_can($this->prefix . 'manager')): ?>
				<form method="POST">
					<div class="form-group">
						<label for="champs"><?php _e('Championship', WCP_TD); ?></label>
						<select class="select2" name="champs">
							<option value="uefa-2019"><?php _e('Champions League 2019/2020', WCP_TD); ?></option>
							<option value="conf-2017"><?php _e('Confederations Cup 2017', WCP_TD); ?></option>
							<option value="euro-2016"><?php _e('Euro 2016', WCP_TD); ?></option>
							<option value="wcup-2014"><?php _e('World Cup 2014', WCP_TD); ?></option>
						</select>
					</div>
					<p class="submit">
						<input <?php echo $disabled; ?> type="submit" name="<?php echo $this->prefix; ?>import" class="button rbutton" value="<?php _e('Import', WCP_TD) ?>" />
					</p>
				</form>
			<?php endif; ?>
			<p>
			<?php
				$userlevel = '<span class="b">' . (current_user_can($this->prefix . 'manager') ? __('WCP Manager', WCP_TD) : __('no', WCP_TD)) . '</span>';
		        printf(__('You currently have %s rights.', WCP_TD), $userlevel);
		    ?>
		    </p>
		</div>

		<?php
	}
	
	function news() {
		// get feed_messages
		require_once(ABSPATH . WPINC . '/feed.php');
		?>
		<div class="rss-widget">
			<?php
			$feed_url = 'http://wcp.net.br/feed/rss/';
			$feed = fetch_feed($feed_url);
			if ($feed && !isset($feed->errors)) {
				?><ul><?php 
				foreach ($feed->get_items(0, $feed->get_item_quantity(5)) as $item) {
			        ?>
			          <li><a class="rsswidget" title="" href='<?php echo wp_filter_kses($item->get_permalink()); ?>'><?php echo esc_html($item->get_title()); ?></a>
					  <span class="rss-date"><?php echo $item->get_date(); ?></span> 
			          <div class="rssSummary"><?php echo $item->get_description(); ?></div></li>
			        <?php
				}
				?></ul><?php 
			} else {
				echo '<p>' . sprintf(__('Newsfeed could not be loaded.  Check the <a href="%s">front page</a> to check for updates.', WCP_TD), 'http://www.wcp.net.br/') . '</p>';
			}
		    ?>
		</div>
		<?php
	}
	
	function donators() {
		$i = 0;
		$list = '';
		
		$supporter = $this->get_remote_array('http://wcp.net.br/wp-content/plugins/world-cup-predictor/donations');
		
		// Ensure that this is a array
		if ( is_array($supporter) ) {
			$supporter = array_reverse($supporter);
			
			foreach ($supporter as $name => $url) {
				$i++;
				if ($url)
					$list .= "<li><a href=\"$url\">$name</a></li>\n";
				else
					$list .= "<li>$name</li>";
				if ($i > 6)
					break;
			}			
		}
		
		?>
		<div id="dashboard_server_settings" class="dashboard-widget-holder">
			<div class="wcp-dashboard-widget">
			  	<div class="dashboard-widget-content">
					<?php _e('Thank you to all donators...', WCP_TD); ?>
			  		<ul class="wcup-settings">
					<?php echo $list; ?>
					</ul>
					<p>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
						">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
						</form>
					</p>
				</div>
		    </div>
		</div>
		<?php	
	}
	
	function get_server_info() {
		global $wpdb;
		
		// Get MYSQL Version
		$sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
		// GET SQL Mode
		$mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
		if (is_array($mysqlinfo)) $sql_mode = $mysqlinfo[0]->Value;
		if (empty($sql_mode)) $sql_mode = __('Not set', WCP_TD);
		// Get PHP Safe Mode
		if(ini_get('safe_mode')) $safe_mode = __('On', WCP_TD);
		else $safe_mode = __('Off', WCP_TD);
		
	?>
		<li><?php _e('Operating System', WCP_TD); ?> : <span><?php echo PHP_OS; ?></span></li>
		<li><?php _e('Server', WCP_TD); ?> : <span><?php echo $_SERVER["SERVER_SOFTWARE"]; ?></span></li>
		<li><?php _e('MYSQL Version', WCP_TD); ?> : <span><?php echo $sqlversion; ?></span></li>
		<li><?php _e('SQL Mode', WCP_TD); ?> : <span><?php echo $sql_mode; ?></span></li>
		<li><?php _e('PHP Version', WCP_TD); ?> : <span><?php echo PHP_VERSION; ?></span></li>
		<li><?php _e('PHP Safe Mode', WCP_TD); ?> : <span><?php echo $safe_mode; ?></span></li>
	<?php
	}
	
	function server() {
		?>
		<div id="dashboard_server_settings" class="dashboard-widget-holder wp_dashboard_empty">
			<div class="wcp-dashboard-widget">
			  	<div class="dashboard-widget-content">
		      		<ul class="wcup-settings">
		      		<?php $this->get_server_info(); ?>
			   		</ul>
				</div>
		    </div>
		</div>
		<?php	
	}
	
	function support() {
		?>
		<div id="dashboard_server_settings" class="dashboard-widget-holder wp_dashboard_empty">
			<div class="wcp-dashboard-widget">
			  	<div class="dashboard-widget-content">
			  		<a href="http://wordpress.org/support/plugin/world-cup-predictor" target="_blank"><?php _e('Support Forum', WCP_TD); ?></a>
				</div>
		    </div>			
		</div>
		<?php	
	}
	
	function overview() {
		
		add_meta_box('dashboard_right_now', __('Welcome to World Cup Predictor V ', WCP_TD) . self::VERSION, array(&$this, 'dashboard'), 'wcp_overview', 'left', 'core');
		add_meta_box('dashboard_primary', __('Latest News', WCP_TD), array(&$this, 'news'), 'wcp_overview', 'right', 'core');
		add_meta_box('wcp_donators', __('Recent donators', WCP_TD), array(&$this, 'donators'), 'wcp_overview', 'right', 'core');
		add_meta_box('wcp_server', __('Server Settings', WCP_TD), array(&$this, 'server'), 'wcp_overview', 'right', 'core');
		add_meta_box('wcp_settings', __('Settings', WCP_TD), array(&$this, 'settings'), 'wcp_overview', 'left', 'core');
		add_meta_box('wcp_support', __('Support', WCP_TD), array(&$this, 'support'), 'wcp_overview', 'left', 'core');
		
		if (isset($_POST[$this->prefix.'import'])) {
			$this->import();
		}

		if (isset($_POST[$this->prefix.'create_pages'])) {

			$pages = apply_filters(
				$this->prefix . 'create_pages',
				array(
					'predictions-by-match'      => array(
						'name'    => _x( 'predictions-by-match', 'Page slug', WCP_TD ),
						'title'   => _x( 'Predictions by Match', 'Page title', WCP_TD ),
						'content' => '',
						'option' => $this->prefix.'match_predictions'
					),
					'predictions-of-user'      => array(
						'name'    => _x( 'predictions-of-user', 'Page slug', WCP_TD ),
						'title'   => _x( 'Predictions of User', 'Page title', WCP_TD ),
						'content' => '',
						'option' => $this->prefix.'player_predictions'
					),
					'predictions'      => array(
						'name'    => _x( 'predictions', 'Page slug', WCP_TD ),
						'title'   => _x( 'Predictions', 'Page title', WCP_TD ),
						'content' => '<!-- wp:shortcode -->[' . apply_filters( 'world-cup-predictor_shortcode_tag', 'world-cup-predictor' ) . ' kickoff=true limit=16]<!-- /wp:shortcode -->',
						'option' => ''
					),
					'group-tables'      => array(
						'name'    => _x( 'group-tables', 'Page slug', WCP_TD ),
						'title'   => _x( 'Group Tables', 'Page title', WCP_TD ),
						'content' => '<!-- wp:shortcode -->[' . apply_filters( 'world-cup-predictor_shortcode_tag', 'world-cup-predictor' ) . ' tables=1 show_results=1]<!-- /wp:shortcode -->',
						'option' => ''
					),
					'ranking'      => array(
						'name'    => _x( 'ranking', 'Page slug', WCP_TD ),
						'title'   => _x( 'Ranking', 'Page title', WCP_TD ),
						'content' => '<!-- wp:shortcode -->[' . apply_filters( 'world-cup-predictor_shortcode_tag', 'world-cup-predictor' ) . ' ranking=1]<!-- /wp:shortcode -->',
						'option' => ''
					)
				)
			);

			foreach ( $pages as $key => $page ) {
				$this->create_pages( esc_sql( $page['name'] ), $page['option'], $page['title'], $page['content'], ! empty( $page['parent'] ) ? wc_get_page_id( $page['parent'] ) : '' );
			}

			update_option( $this->prefix.'created_pages', 1 );
		}
	?>
	<div class="wrap wcp-wrap">
		<h2><?php _e('World Cup Predictor Overview', WCP_TD) ?></h2>
		<div id="dashboard-widgets-wrap" class="wcp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" style="width:49%;">
							<?php do_meta_boxes('wcp_overview', 'left', ''); ?>
						</div>
			    		<div class="postbox-container" style="width:49%;">
							<?php do_meta_boxes('wcp_overview', 'right', ''); ?>
						</div>						
					</div>
				</div>
		    </div>
		</div>
	</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// postboxes setup
			postboxes.add_postbox_toggles('wcc-overview');
		});
		//]]>
	</script>
	<?php
		
	}
	
	/**
	 * Read an array from a remote url
	 * 
	 * @param string $url
	 * @return array of the content
	 */
	function get_remote_array($url) {
		if ( function_exists('wp_remote_request') ) {
			
			$options = array();
			$options['headers'] = array(
				'User-Agent' => 'World Cup Predictor V' . self::VERSION . '; (' . get_bloginfo('url') .')'
			);
			
			$response = wp_remote_request($url, $options);
			
			if ( is_wp_error( $response ) )
				return false;
			
			if ( 200 != $response['response']['code'] )
				return false;
		   	
			$content = unserialize($response['body']);
			
			if (is_array($content)) 
				return $content;
		}
		
		return false;	
	}
	
	function import() {
		
		global $wpdb;
		
		$champs = '';
		
		extract($_POST, EXTR_IF_EXISTS);

		if(!empty($champs)) {
			require_once(dirname(__FILE__)."/champs/$champs.php");
		}
		
		$this->printMessage();
	}

	/**
	 * Create a page and store the ID in an option.
	 *
	 * @param mixed  $slug Slug for the new page.
	 * @param string $option Option name to store the page's ID.
	 * @param string $page_title (default: '') Title for the new page.
	 * @param string $page_content (default: '') Content for the new page.
	 * @param int    $post_parent (default: 0) Parent for the new page.
	 * @return int page ID.
	 */
	function create_pages( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
		global $wpdb;

		$option_value = get_option( $option );

		if ( $option_value > 0 ) {
			$page_object = get_post( $option_value );

			if ( $page_object && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ), true ) ) {
				// Valid page is already in place.
				return $page_object->ID;
			}
		}

		if ( strlen( $page_content ) > 0 ) {
			// Search for an existing page with the specified page content (typically a shortcode).
			$shortcode = str_replace( array( '<!-- wp:shortcode -->', '<!-- /wp:shortcode -->' ), '', $page_content );
			$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$shortcode}%" ) );
		} else {
			// Search for an existing page with the specified page slug.
			$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
		}

		//$valid_page_found = apply_filters( $this->prefix . 'create_page_id', $valid_page_found, $slug, $page_content );

		if ( $valid_page_found ) {
			if ( $option ) {
				update_option( $option, $valid_page_found );
			}
			return $valid_page_found;
		}

		// Search for a matching valid trashed page.
		if ( strlen( $page_content ) > 0 ) {
			// Search for an existing page with the specified page content (typically a shortcode).
			$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		} else {
			// Search for an existing page with the specified page slug.
			$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
		}

		if ( $trashed_page_found ) {
			$page_id   = $trashed_page_found;
			$page_data = array(
				'ID'          => $page_id,
				'post_status' => 'publish',
			);
			wp_update_post( $page_data );
		} else {
			$page_data = array(
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'post_author'    => 1,
				'post_name'      => $slug,
				'post_title'     => $page_title,
				'post_content'   => $page_content,
				'post_parent'    => $post_parent,
				'comment_status' => 'closed',
			);
			$page_id   = wp_insert_post( $page_data );
		}

		if ( $option ) {
			update_option( $option, $page_id );
		}

		return $page_id;
	}		
}
