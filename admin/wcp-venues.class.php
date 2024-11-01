<?php
/**
 * @package WorldCup
 * @version $Id: wcp-venues.class.php 2153552 2019-09-09 12:55:03Z landoweb $
 * @author landoweb
 * Copyright Landoweb Programador, 2014
 * 
 * Venue Administration functions for the WorldCup plugin.
 * 
 */
 
class WorldCupVenues extends WorldCupAdmin {
	
	var $tab;
	
	/**
	 * Constructor
	 */
	function __construct($tab) {
		$this->tab = $tab;
		parent::__construct();
	}
	
	/**
	 * Display and manage venues
	 */
	function venues() {
		
		global $wpdb;
		
		$venue_name = '';
		$venue_url = '';
		$stadium = '';
		$tz_offset = 0;  // hours
		$venue_id = -1;
		
		if (isset($_POST[$this->prefix.'modifyVenueCancel'])) {
			check_admin_referer($this->prefix . 'venue-form');
			$this->selectTab($this->tab);
		}
		
		if (isset($_POST[$this->prefix.'addVenue'])) {
			check_admin_referer($this->prefix . 'venue-form');
			
			extract($_POST, EXTR_IF_EXISTS);
			
			// Save to database
			if ($this->insert($venue_name, $venue_url, $stadium, $tz_offset) !== false) {
				$venue_name = '';
				$venue_url = '';
				$stadium = '';
				$tz_offset = 0;
				$this->setMessage(__('Changes saved', WCP_TD));
			}
			$this->selectTab($this->tab);
		}
		
		/**
		 * Actually modify the result.
		 */
		if (isset($_POST[$this->prefix.'modifyVenue'])) {
			check_admin_referer($this->prefix . 'venue-form');
			
			extract($_POST, EXTR_IF_EXISTS);
			
			if ($this->update($venue_id, $venue_name, $venue_url, $stadium, $tz_offset) !== false) {
				$venue_name = '';
				$venue_url = '';
				$stadium = '';
				$tz_offset = 0;
				$venue_id = -1;
				$this->setMessage(__('Changes saved', WCP_TD));
			}
			$this->selectTab($this->tab);
		}
		
		/**
		 * Process GET request to retreive the venue details and pre-fill
		 * the form.
		 */
		if (isset($_GET['modifyvenue_id'])) {
			$venue_id = $_GET['modifyvenue_id'];
			$row = $this->get($venue_id);
			if (empty($row)) $venue_id = -1;	// Didn't find row. Prevent modification
			extract($row, EXTR_IF_EXISTS);
			$this->selectTab($this->tab);
		}
		
		if (isset($_POST[$this->prefix.'deleteVenue'])) {
			check_admin_referer($this->prefix . 'list-venues');
			if (isset($_POST['venue_id'])) {
				foreach ($_POST['venue_id'] as $id) {
					$this->delete($id);
				}
				$this->setMessage(__('Changes saved', WCP_TD));
			}
			$this->selectTab($this->tab);
		}
?>
		<div class="wrap">
			
			<h2><?php _e('Manage venues', WCP_TD) ?></h2>
			
			<?php $this->printMessage(); ?>
			
			<form class="form-table <?php echo $this->prefix; ?>form" name="venue" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
				
				<?php wp_nonce_field( $this->prefix . 'venue-form' ) ?>
				
				<div class="form-group">
					<label for="venue_name"><?php _e( 'Venue Name', WCP_TD ) ?></label>
					<input type="text" name="venue_name" value="<?php echo $venue_name;?>" size="45" maxlength="64" />
				</div>
				<div class="form-group">
					<label for="venue_url"><?php _e( 'Venue URL', WCP_TD ) ?></label>
					<input type="text" name="venue_url" value="<?php echo $venue_url;?>" size="65" maxlength="255" />
				</div>
				<div class="form-group">
					<label for="stadium"><?php _e( 'Stadium', WCP_TD ) ?></label>
					<input type="text" name="stadium" value="<?php echo $stadium;?>" size="45" maxlength="64" />
				</div>
				<div class="form-group">
					<label for="tz_offset"><?php _e( 'UTC Offset in hours', WCP_TD ) ?></label>
					<input type="text" name="tz_offset" value="<?php echo $tz_offset;?>" size="3" maxlength="3" />
				</div>
<?php 
			if  ($venue_id != -1) {
?>
				<input type="hidden" value="<?php echo $venue_id; ?>" name="venue_id"></input>
				<p class="submit">
					<input type="submit" name="<?php echo $this->prefix;?>modifyVenue" value="<?php _e( 'Modify Venue', WCP_TD ) ?>" class="button-primary" />
					<input type="submit" name="<?php echo $this->prefix;?>modifyVenueCancel" value="<?php _e( 'Cancel', WCP_TD ) ?>" class="button" />
				</p>
<?php 
			} else {
?>
				<p class="submit"><input type="submit" name="<?php echo $this->prefix;?>addVenue" value="<?php _e( 'Add Venue', WCP_TD ) ?>" class="button-primary" /></p>
<?php 
			}
?>
			</form>
<?php 
		/**
		 * Show the current venue list in a table
		 */
		$sql = "SELECT venue_id, venue_name, venue_url, stadium, tz_offset, wwhen
				FROM 
					{$wpdb->prefix}{$this->prefix}venue
				ORDER BY
					venue_name";
					
		$result = $wpdb->get_results( $sql , OBJECT );

?>		
			<form name="listvenues" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
				
				<?php wp_nonce_field( $this->prefix . 'list-venues' ) ?>
				
				<table class="<?php echo $this->prefix; ?>table" width="90%">
					<thead>
						<tr>
							<th><?php _e('Del', WCP_TD) ?></th>
							<th><?php _e('ID', WCP_TD) ?></th>
							<th><?php _e('Venue Name', WCP_TD) ?></th>
							<th><?php _e('Venue URL', WCP_TD) ?></th>
							<th><?php _e('Stadium', WCP_TD) ?></th>
							<th><?php _e('UTC+', WCP_TD) ?></th>
							<th><?php _e('Last Modified', WCP_TD) ?></th>
						</tr>
					</thead>
					<tbody>
<?php
					foreach ($result as $row) {
?>
						<tr>
							<td><input type="checkbox" value="<?php echo $row->venue_id; ?>" name ="venue_id[<?php echo $row->venue_id;?>]"/></td>
							<td><a title="<?php _e('Modify this venue', WCP_TD); ?>" href="<?php echo $_SERVER['REQUEST_URI'] ?>&amp;modifyvenue_id=<?php echo $row->venue_id; ?>"><?php echo $row->venue_id; ?></a></td>
							<td><?php echo $this->unclean($row->venue_name); ?></td>
							<td><?php echo $row->venue_url; ?></td>
							<td><?php echo $this->unclean($row->stadium); ?></td>
							<td><?php echo $row->tz_offset; ?></td>
							<td><?php echo $row->wwhen; ?></td>
						</tr>
<?php
					}
?>
					</tbody>
				</table>
				
				<p><input type="submit" name="<?php echo $this->prefix; ?>deleteVenue" value="<?php _e( 'Delete Selected', WCP_TD ); ?>" class="button" /></p>
				
			</form>
			
		</div>
<?php
	}
	
	/**
	 * Check valid input
	 */
	private function valid($venue_name, $venue_url, $stadium, $tz_offset) {
		if (empty($venue_name) || empty($stadium)) {
			$this->setMessage(__("Venue Name or Stadium can not be empty", WCP_TD), true);
			return false;
		}
		
		if (!is_int(abs($tz_offset))) {
			$this->setMessage(__("UTC Offset must be numeric", WCP_TD), true);
			return false;
		}
		return true;
	}
	
	/**
	 * Insert row
	 */
	private function insert($venue_name, $venue_url, $stadium, $tz_offset) {
		
		global $wpdb;
		
		$venue_name = $this->clean($venue_name);
		$stadium = $this->clean($stadium);
		
		if (!$this->valid($venue_name, $venue_url, $stadium, $tz_offset)) {
			return false;
		}
		
		$sql = "INSERT INTO {$wpdb->prefix}{$this->prefix}venue (venue_name, venue_url, stadium, tz_offset)
				VALUES (%s, %s, %s, %d)";
		
		$ret = $wpdb->query( $wpdb->prepare( $sql, $venue_name, $venue_url, $stadium, $tz_offset) );
		
		if ($ret == 1) {
			return $wpdb->insert_id;
		} else {
			return false;
		}
	}
	
	/**
	 * Update row
	 */
	private function update($venue_id, $venue_name, $venue_url, $stadium, $tz_offset) {
		
		global $wpdb;
		
		$venue_name = $this->clean($venue_name);
		$stadium = $this->clean($stadium);
		
		if (!$this->valid($venue_name, $venue_url, $stadium, $tz_offset)) {
			return false;
		}
		
		$sql = "UPDATE {$wpdb->prefix}{$this->prefix}venue
				SET
					venue_name = %s,
					venue_url = %s,
					stadium = %s,
					tz_offset = %d
				WHERE venue_id = %d";
		
		return $wpdb->query( $wpdb->prepare( $sql, $venue_name, $venue_url, $stadium, $tz_offset, $venue_id) );
	}
	
	/**
	 * Get row by id.
	 */
	private function get($venue_id) {
		
		global $wpdb;
		
		$sql = "SELECT venue_name, venue_url, stadium, tz_offset
				FROM {$wpdb->prefix}{$this->prefix}venue WHERE venue_id = %d";
		
		$row = $wpdb->get_row( $wpdb->prepare($sql, $venue_id) , ARRAY_A );
		
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
	private function delete($venue_id) {
		
		global $wpdb;
		
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}{$this->prefix}match WHERE venue_id = %d";
		$count = $wpdb->get_var($wpdb->prepare($sql, $venue_id));
		if ($count) {
			$this->setMessage(__('Can not delete a venue with matches', WCP_TD), true);
			return false;
		}
		
		$sql = "DELETE FROM {$wpdb->prefix}{$this->prefix}venue WHERE venue_id = %d";
		
		$wpdb->query( $wpdb->prepare( $sql, $venue_id ) );
	}
	
	/**
	 * Get a list of venues in a dropdown select box
	 * 
	 * @param $venue_id - Preselect this venue
	 */
	function getVenues($venue_id, $empty = true, $id = 'venue_id', $empty_str = '', $class = '') {
		
		global $wpdb;
		
		$sql = "SELECT venue_id, venue_name, stadium FROM {$wpdb->prefix}{$this->prefix}venue ORDER BY venue_name";
		
		$result = $wpdb->get_results( $sql );
		
		$output = '<select name="'.$id.'" id="'.$id.'" class="'.$class.'">';
		if ($empty) $output .= '<option value = "-1">'.$empty_str.'</option>';
		
		foreach ($result as $row) {
			$output .= '<option ';
			if (!is_null($id) && $venue_id == $row->venue_id) {
				$output .= ' selected ';
			}
			$output .= 'value="'.$row->venue_id.'">'.$this->unclean($row->venue_name).' - '.$this->unclean($row->stadium).'</option>';
		}
		$output .= '</select>';
		
		return $output;
	}
}

?>