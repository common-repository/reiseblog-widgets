<?php
class wp_widget_reisecountdown extends WP_Widget {

	// Prefix for the widget.
	var $prefix;

	// Textdomain for the widget.
	var $textdomain;

	function __construct() {
	
		$this->prefix = 'reisecountdown';
		$this->textdomain = 'reisecountdown';
	
		// Give your own prefix name eq. your-theme-name-
		$prefix = '';

		// Create the widget
		$this->WP_Widget( $this->prefix, esc_attr__( 'Reiseblog: Reise-Countdown', $this->textdomain ), 
        array(
        'classname' => 'reiseblog-widgets-reisecountdown',
  			'description' => esc_html( htmlentities('Zeige deinen Lesern, wann du wieder auf Reise gehst. Urlaubs-Vorfreude ist die schönste Freude!'))),
      	array(
        			'width' => 400
        		) );
		
		// Load the widget stylesheet for the widgets admin screen
		add_action( 'load-widgets.php', array(&$this, 'load_scripts_styles') );
	//	add_action( 'admin_print_styles', array(&$this, 'admin_print_styles') );
		
		// Print the user costum style sheet
		if ( is_active_widget(false, false, $this->id_base) ) {
			wp_enqueue_style( $this->prefix, REISEBLOGWIDGETS_URL . 'css/rbcd.css' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( $this->prefix, REISEBLOGWIDGETS_URL . 'js/jquery.countdown.min.js' );
			add_action( 'wp_head', array( &$this, 'print_script') );
		}
	}


	function load_scripts_styles() {
		// wp_enqueue_style( 'total-dialog', REISEBLOGWIDGETS_URL . 'css/dialog.css', array( 'farbtastic', 'thickbox' ), REISEBLOGWIDGETS_VERSION );
		wp_register_script( 'total-dialog', REISEBLOGWIDGETS_URL . 'js/jquery.dialog.js', array( 'jquery', 'farbtastic', 'thickbox' ), REISEBLOGWIDGETS_VERSION );
		wp_enqueue_script( 'countdown-dialog', REISEBLOGWIDGETS_URL . 'js/jquery.countdown-dialog.js', array( 'total-dialog' ), REISEBLOGWIDGETS_VERSION );
	}
		
	
	function print_script() {
		$settings = $this->get_settings();
		foreach ($settings as $key => $setting){
			$widget_id = $this->id_base . '-' . $key;
			if( is_active_widget( false, $widget_id, $this->id_base ) ) {
				
				// Print the countdown script new Date(year, mth - 1, day, hr, min, sec)
				if ( !empty( $setting['until'] ) ) {
					echo '<style type="text/css">';
						if ( $setting['bg_color'] ) 		echo '#' . $this->id . '-wrapper .countdown_section {background-color: ' . $setting['bg_color'] . '}';
						if ( $setting['counter_image'] ) 	echo '#' . $this->id . '-wrapper .countdown_section {background-image: url(' . $setting['counter_image'] . '); }';
						if ( $setting['counter_color'] )  	echo '#' . $this->id . '-wrapper .countdown_amount {color: ' . $setting['counter_color'] . '}';
						if ( $setting['label_color'] ) 		echo '#' . $this->id . '-wrapper .countdown_section {color: ' . $setting['label_color'] . '}';
					echo '</style>';
					
					echo '<script type="text/javascript">';
						echo 'jQuery(document).ready(function($){';
							$countdown	 = '';
							$countdown	.= $setting['counter'] . ': theDate ,'; // until or since
							$countdown 	.= !empty($setting['expiryUrl']) ? 'expiryUrl: "' . $setting['expiryUrl'] . '",' : 'expiryUrl: "",';
							$countdown 	.= !empty($setting['expiryText']) ? 'expiryText: "' . $setting['expiryText'] . '",' : 'expiryText: "",';
							$countdown 	.= 'alwaysExpire: ' . $setting['alwaysExpire'] . ',';
							$countdown 	.= "format: '" . $setting['format'] . "',";
							$countdown 	.= 'compact: ' . $setting['compact'] . ',';
							$countdown 	.= 'tickInterval: ' . $setting['tickInterval'] . ',';
							$countdown 	.= "compactLabels: ['" . $setting['compactLabels'][0] . "', '" . $setting['compactLabels'][1] . "', '" . $setting['compactLabels'][2] . "', '" . $setting['compactLabels'][3] . "'],";
							$countdown	.= "labels: ['" . $setting['cLabels'][0] . "', '" . $setting['cLabels'][1] . "', '" . $setting['cLabels'][2] . "', '" . $setting['cLabels'][3] . "', '" . $setting['cLabels'][4] . "', '" . $setting['cLabels'][5] . "', '" . $setting['cLabels'][6] . "'],";
							$countdown 	.= "labels1: ['" . $setting['cLabels1'][0] . "', '" . $setting['cLabels1'][1] . "', '" . $setting['cLabels1'][2] . "', '" . $setting['cLabels1'][3] . "', '" . $setting['cLabels1'][4] . "', '" . $setting['cLabels1'][5] . "', '" . $setting['cLabels1'][6] . "']";
							
							echo 'var theDate = new Date("' . $setting['until'][0] . '/' . $setting['until'][1] . '/' . $setting['until'][2] . ' ' . $setting['until'][3] . ':' . $setting['until'][4] . '");';
							echo "$('#$widget_id-wrapper').countdown({ $countdown });";
						echo '});';
					echo '</script>';
				}

				// Print the custom style and script
				if ( !empty( $setting['customstylescript'] ) ) echo $setting['customstylescript'];
			}
		}
	}
	
	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Set up the arguments for wp_list_categories(). */
		$args = array(
			'title_icon'			=> $instance['title_icon'],
			'counter' 				=> $instance['counter'],
			'until' 				=> $instance['until'],
			'cLabels' 				=> $instance['cLabels'],
			'cLabels1' 				=> $instance['cLabels1'],
			'compactLabels' 		=> $instance['compactLabels'],
			'format' 				=> $instance['format'],
			'expiryUrl' 			=> $instance['expiryUrl'],
			'expiryText' 			=> $instance['expiryText'],
			'ueberText' 			=> $instance['ueberText'],
			'unterText' 			=> $instance['unterText'],
			'alwaysExpire' 			=> !empty( $instance['alwaysExpire'] ) ? true : false,
			'compact' 				=> !empty( $instance['compact'] ) ? true : false,
			'onExpiry' 				=> $instance['onExpiry'],
			'onTick' 				=> $instance['onTick'],
			'tickInterval' 			=> $instance['tickInterval'],
			'counter_image' 		=> $instance['counter_image'],
			'bg_color' 				=> $instance['bg_color'],
			'counter_color' 		=> $instance['counter_color'],
			'label_color' 			=> $instance['label_color'],
			'toggle_active'			=> $instance['toggle_active']
		);

		// Output the theme's widget wrapper
		echo $before_widget;		

		// If a title was input by the user, display it
		if ( !empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

      if ( !empty( $instance['ueberText'] ) )
			echo '<p>'. $instance['ueberText']. '</p>';
			
		echo '<div id="'. $this->id . '-wrapper"></div>';

      if ( !empty( $instance['unterText'] ) )
			echo '<p>'. $instance['unterText']. '</p>';

		// Close the theme's widget wrapper
    echo $after_widget;
		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6.0
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Set the instance to the new instance. */
		$instance = $new_instance;

		$instance['title'] 				= strip_tags( $new_instance['title'] );
		$instance['title_icon']			= strip_tags( $new_instance['title_icon'] );
		$instance['counter'] 			= $new_instance['counter'];
		$instance['until'] 				= $new_instance['until'];
		$instance['cLabels'] 			= $new_instance['cLabels'];
		$instance['cLabels1'] 			= $new_instance['cLabels1'];
		$instance['compactLabels'] 		= $new_instance['compactLabels'];
		$instance['format'] 			= $new_instance['format'];
		$instance['expiryUrl'] 			= strip_tags( $new_instance['expiryUrl'] );
		$instance['expiryText'] 		= wp_kses_post( $new_instance['expiryText'] );
		$instance['ueberText'] 		= wp_kses_post( $new_instance['ueberText'] );
		$instance['unterText'] 		= wp_kses_post( $new_instance['unterText'] );
		$instance['alwaysExpire'] 		= ( isset( $new_instance['alwaysExpire'] ) ? 1 : 0 );
		$instance['compact'] 			= ( isset( $new_instance['compact'] ) ? 1 : 0 );
		$instance['onExpiry'] 			= $new_instance['onExpiry'];
		$instance['onTick'] 			= $new_instance['onTick'];
		$instance['tickInterval'] 		= strip_tags( $new_instance['tickInterval'] );
		$instance['counter_image'] 		= $new_instance['counter_image'];
		$instance['bg_color'] 			= strip_tags($new_instance['bg_color']);
		$instance['counter_color'] 		= strip_tags($new_instance['counter_color']);
		$instance['label_color'] 		= strip_tags($new_instance['label_color']);
		$instance['toggle_active'] 		= $new_instance['toggle_active'];
		
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6.0
	 */
	function form( $instance ) {

		// Set up the default form values
		// date-time: mm jj aa hh mn
		$defaults = array(
			'title' 			=> 'Reise-Countdown',
			'title_icon'		=> '',
			'counter' 			=> 'until',
			'until' 			=> array( 0 => date('m'), 1 => date('j'), 2 => date('Y'), 3 => 16, 4 => 53 ),
			'cLabels' 			=> array( 0 => 'Jahre', 1 => 'Monate', 2 => 'Wochen', 3 => 'Tage', 4 => 'Stunden', 5 => 'Minuten', 6 => 'Sekunden' ),
			'cLabels1' 			=> array( 0 => 'Jahr', 1 => 'Monat', 2 => 'Woche', 3 => 'Tag', 4 => 'Stunde', 5 => 'Minute', 6 => 'Sekunde' ),
			'compactLabels' 	=> array( 0 => 'J', 1 => 'M', 2 => 'W', 3 => 'T' ),
			'format' 			=> 'yodHMS',
			'expiryUrl' 		=> '',
			'expiryText' 		=> 'Ich bin aktuell auf Weltreise!',
			'ueberText' 		=> '',
			'unterText' 		=> 'Bald gehts endlich wieder los!',
			'alwaysExpire' 		=> false,
			'compact' 			=> false,
			'onExpiry' 			=> '',
			'onTick' 			=> '',
			'tickInterval' 		=> 1,
			'bg_color' 			=> '#f6f7f6',
			'counter_image' 	=> '',
			'counter_color' 	=> '#444444',
			'label_color' 		=> '#444444',
			'toggle_active'		=> array( 0 => true, 1 => false, 2 => false, 3 => false, 4 => false, 5 => false )
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );

		$tabs = array( 
			__( 'General', $this->textdomain ),  
			__( 'Format', $this->textdomain ),
			__( 'Customs', $this->textdomain ),
			__( 'Upgrade', $this->textdomain )
		);
		
		// Set the default value of each widget input
		global $wp_locale;
		$time_adj = current_time('timestamp');
		$counterList = array( 'until' => 'Zukunft' , 'since' => 'Vergangenheit');
		?>


						<p>
							<label for="<?php echo $this->get_field_id( 'title' ); ?>">Titel: </label>
							<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" size="40" value="<?php echo esc_attr( $instance['title'] ); ?>" />
						</p>						

							<div id ="until-<?php echo $this->id; ?>" class="curtime tc-curtime">

								<div class="js timestampdiv">
                	<label>Reise-Zeitpunkt:</label>

										<?php
											$month = "<select class='mm' name='" . $this->get_field_name( 'until' ) . "[]'>";
											for ( $i = 1; $i < 13; $i = $i +1 ) {
												$monthnum = zeroise($i, 2);
												$month .= "\t\t\t" . '<option value="' . $monthnum . '"';
												if ( $i == $instance['until'][0] )
													$month .= ' selected="selected"';
												/* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
												$month .= '>' . sprintf( __( '%1$s-%2$s' ), $monthnum, $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) . "</option>\n";
											}
											$month .= '</select>';
											echo $month;
                      
                      
										?>
										<input type="text" autocomplete="off" tabindex="4" maxlength="2" size="2" value="<?php echo $instance['until'][1]; ?>" name="<?php echo $this->get_field_name( 'until' ); ?>[]" class="jj" />, 
										<input type="text" autocomplete="off" tabindex="4" maxlength="4" size="4" value="<?php echo $instance['until'][2]; ?>" name="<?php echo $this->get_field_name( 'until' ); ?>[]" class="aa" /> @ 
										<input type="text" autocomplete="off" tabindex="4" maxlength="2" size="2" value="<?php echo $instance['until'][3]; ?>" name="<?php echo $this->get_field_name( 'until' ); ?>[]" class="hh"> : 
										<input type="text" autocomplete="off" tabindex="4" maxlength="2" size="2" value="<?php echo $instance['until'][4]; ?>" name="<?php echo $this->get_field_name( 'until' ); ?>[]" class="mn">


									
									<input type="hidden" value="11" name="ss" class="ss" />
									<input type="hidden" value="<?php echo esc_attr( $instance['until']['0'] ); ?>" name="hidden_mm" class="hidden_mm">
									<input type="hidden" value="<?php echo gmdate( 'd', $time_adj ); ?>" name="cur_mm" class="cur_mm">
									<input type="hidden" value="<?php echo esc_attr( $instance['until']['1'] ); ?>" name="hidden_jj" class="hidden_jj">
									<input type="hidden" value="<?php echo gmdate( 'm', $time_adj ); ?>" name="cur_jj" class="cur_jj">
									<input type="hidden" value="<?php echo esc_attr( $instance['until']['2'] ); ?>" name="hidden_aa" class="hidden_aa">
									<input type="hidden" value="<?php echo gmdate( 'Y', $time_adj ); ?>" name="cur_aa" class="cur_aa">
									<input type="hidden" value="<?php echo esc_attr( $instance['until']['3'] ); ?>" name="hidden_hh" class="hidden_hh">
									<input type="hidden" value="<?php echo gmdate( 'h', $time_adj ); ?>" name="cur_hh" class="cur_hh">
									<input type="hidden" value="<?php echo esc_attr( $instance['until']['4'] ); ?>" name="hidden_mn" class="hidden_mn">
									<input type="hidden" value="<?php echo gmdate( 'i', $time_adj ); ?>" name="cur_mn" class="cur_mn">
								</div>
							
							</div>	


						<p>
							<label for="<?php echo $this->get_field_id( 'expiryText' ); ?>">Text / HTML wenn Countdown abgelaufen (anstelle des Countdowns)</label>
							<textarea class="widefat" id="<?php echo $this->get_field_id( 'expiryText' ); ?>" name="<?php echo $this->get_field_name( 'expiryText' ); ?>"><?php echo esc_attr( $instance['expiryText'] ); ?></textarea>
						</p>


						<p>
							<label for="<?php echo $this->get_field_id( 'ueberText' ); ?>">Text / HTML &uuml;ber dem Countdown</label>
							<textarea class="widefat" id="<?php echo $this->get_field_id( 'ueberText' ); ?>" name="<?php echo $this->get_field_name( 'ueberText' ); ?>"><?php echo esc_attr( $instance['ueberText'] ); ?></textarea>
						</p>
            
						<p>
							<label for="<?php echo $this->get_field_id( 'unterText' ); ?>">Text / HTML unter dem Countdown</label>
							<textarea class="widefat" id="<?php echo $this->get_field_id( 'unterText' ); ?>" name="<?php echo $this->get_field_name( 'unterText' ); ?>"><?php echo esc_attr( $instance['unterText'] ); ?></textarea>
						</p>	

						<p>
							<label for="<?php echo $this->get_field_id( 'format' ); ?>">Datums-Format f&uuml;r die Anzeige</label>
							<input type="text" id="<?php echo $this->get_field_id( 'format' ); ?>" size="10" name="<?php echo $this->get_field_name( 'format' ); ?>" value="<?php echo esc_attr( $instance['format'] ); ?>" /><br>
							<span class="controlDesc"><?php echo esc_html( htmlentities('Format-Platzhalter: Y = Jahre, O = Monate, W = Wochen, D = Tage, H = Stunden, M = Minuten, S = Sekunden. Bei Kleinbuchstaben werden Nullwerte ausgeblendet.')); ?></span>	
						</p>
            
            <p>
            <label for="<?php echo $this->get_field_id( 'counter' ); ?>">Art des Countdowns:</label>
            		<select class="smallfat" id="<?php echo $this->get_field_id( 'counter' ); ?>" name="<?php echo $this->get_field_name( 'counter' ); ?>">
									<?php foreach ( $counterList as $option_value => $option_label ) { ?>
										<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['counter'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
									<?php } ?>
								</select>
             </p>

						<p>
							<label for="<?php echo $this->get_field_id( 'tickInterval' ); ?>">Refresh Intervall (in Sekunden):</label>
							<input type="text" id="<?php echo $this->get_field_id( 'tickInterval' ); ?>" name="<?php echo $this->get_field_name( 'tickInterval' ); ?>" size="5" value="<?php echo esc_attr( $instance['tickInterval'] ); ?>" />
						</p>

            <p>
							<label for="<?php echo $this->get_field_id( 'bg_color' ); ?>">Farbe Countdown-Hintergrund:</label>
							<input type="text" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" size="10" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" size="5" value="<?php echo esc_attr( $instance['bg_color'] ); ?>" />
						</p>

            <p>
							<label for="<?php echo $this->get_field_id( 'counter_color' ); ?>">Farbe Countdown-Zahlen:</label>
							<input type="text" id="<?php echo $this->get_field_id( 'counter_color' ); ?>" size="10" name="<?php echo $this->get_field_name( 'counter_color' ); ?>" size="5" value="<?php echo esc_attr( $instance['counter_color'] ); ?>" />
						</p>
            
            <p>
							<label for="<?php echo $this->get_field_id( 'label_color' ); ?>">Farbe Countdown-Beschriftung:</label>
							<input type="text" id="<?php echo $this->get_field_id( 'label_color' ); ?>" size="10" name="<?php echo $this->get_field_name( 'label_color' ); ?>" size="5" value="<?php echo esc_attr( $instance['label_color'] ); ?>" />
						</p>

            <p>
							<label for="<?php echo $this->get_field_id( 'compact' ); ?>">
							<input type="checkbox" <?php checked( $instance['compact'], true ); ?> id="<?php echo $this->get_field_id( 'compact' ); ?>" name="<?php echo $this->get_field_name( 'compact' ); ?>" /> Kleinere Version des Countdowns (anstatt der normalen Gr&ouml;&szlig;e)</label>
						</p>
            
							<table style="display: none">
								<tr>
									<td><span class="controlDesc"><?php _e( 'Years', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels' ); ?>[]" value="<?php echo $instance['cLabels'][1]; ?>" /></td>
									<td class="separator"></td>
									<td><span class="controlDesc"><?php _e( 'Year', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels1' ); ?>[]" value="<?php echo $instance['cLabels'][1]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Months', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels' ); ?>[]" value="<?php echo $instance['cLabels'][1]; ?>" /></td>
									<td class="separator"></td>
									<td><span class="controlDesc"><?php _e( 'Month', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels1' ); ?>[]" value="<?php echo $instance['cLabels1'][1]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Weeks', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels' ); ?>[]" value="<?php echo $instance['cLabels'][2]; ?>" /></td>
									<td class="separator"></td>
									<td><span class="controlDesc"><?php _e( 'Week', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels1' ); ?>[]" value="<?php echo $instance['cLabels1'][2]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Days', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels' ); ?>[]" value="<?php echo $instance['cLabels'][3]; ?>" /></td>
									<td class="separator"></td>
									<td><span class="controlDesc"><?php _e( 'Day', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels1' ); ?>[]" value="<?php echo $instance['cLabels1'][3]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Hours', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels' ); ?>[]" value="<?php echo $instance['cLabels'][4]; ?>" /></td>
									<td class="separator"></td>
									<td><span class="controlDesc"><?php _e( 'Hour', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels1' ); ?>[]" value="<?php echo $instance['cLabels1'][4]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Minutes', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels' ); ?>[]" value="<?php echo $instance['cLabels'][5]; ?>" /></td>
									<td class="separator"></td>
									<td><span class="controlDesc"><?php _e( 'Minute', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels1' ); ?>[]" value="<?php echo $instance['cLabels1'][5]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Seconds', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels' ); ?>[]" value="<?php echo $instance['cLabels'][6]; ?>" /></td>
									<td class="separator"></td>
									<td><span class="controlDesc"><?php _e( 'Second', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'cLabels1' ); ?>[]" value="<?php echo $instance['cLabels1'][6]; ?>" /></td>
								</tr>
							</table>
              
              <table style="display: none">
								<tr>
									<td><span class="controlDesc"><?php _e( 'Year', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'compactLabels' ); ?>[]" value="<?php echo $instance['compactLabels'][0]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Month', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'compactLabels' ); ?>[]" value="<?php echo $instance['compactLabels'][1]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Week', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'compactLabels' ); ?>[]" value="<?php echo $instance['compactLabels'][2]; ?>" /></td>
								</tr>
								<tr>
									<td><span class="controlDesc"><?php _e( 'Day', $this->textdomain ); ?></span></td><td><input type="hidden" class="smallfat" name="<?php echo $this->get_field_name( 'compactLabels' ); ?>[]" value="<?php echo $instance['compactLabels'][3]; ?>" /></td>
								</tr>
							</table>		

	<?php
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_widget_reisecountdown");'));

?>