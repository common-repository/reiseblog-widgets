<?php
class wp_widget_reisetiger extends WP_Widget {

// constructor
    function wp_widget_reisetiger() {
        parent::WP_Widget(false, $name = __('Reiseblog: Schn&auml;ppchen', 'wp_widget_plugin'), 
        array(
        'classname' => 'reiseblog-widgets-reiseschnaeppchen',
  			'description' => esc_html( htmlentities('Aktuelle Reise-Schnäppchen und Gutscheine fürs günstiges Verreisen. Das Widget aktualisiert sich automatisch.'))),
      	array(
        			'width' => 400
        		)
        	);
    }

// widget form creation
    function form($instance) { 
    // Check values 
    if( $instance) { 
         $title = esc_attr($instance['title']); 
         $anzahl = esc_attr($instance['anzahl']);   
         $checkboxgutscheine = esc_attr($instance['checkboxgutscheine']); // Added 
         $checkboxangebote = esc_attr($instance['checkboxangebote']); // Added
         $checkboxtipps = esc_attr($instance['checkboxtipps']); // Added
         $checkboxzufall = esc_attr($instance['checkboxzufall']); // Added
         $darstellung = esc_attr($instance['darstellung']);
    } else { // Standardwerte
         $title = 'Reiseschn&auml;ppchen'; 
         $anzahl = '10';  
         $checkboxgutscheine = '1'; // Added 
         $checkboxangebote = '1'; // Added 
         $checkboxtipps = '1'; // Added 
         $checkboxzufall = ''; // Added 
         $darstellung = 'listemitpreis';
    }
    $darstellungList = array( 'listemitpreis' => 'Liste', 'listeohnepreis' => 'Liste ohne Preis / Rabatt', 'tabellemitpreis' => 'Tabelle' , 'tabelleohnepreis' => 'Tabelle ohne Preis / Rabatt'); 
    ?>
    <p>
    <label for="<?php echo $this->get_field_id('title'); ?>">Titel:</label>
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" size="40" />
    </p>
    <p>Welche Reiseschn&auml;ppchen / Deals sollen angezeigt werden?  </p>
    <p>
    <input id="<?php echo $this->get_field_id('checkboxgutscheine'); ?>" name="<?php echo $this->get_field_name('checkboxgutscheine'); ?>" type="checkbox" value="1" <?php checked( '1', $checkboxgutscheine ); ?> />
    <label for="<?php echo $this->get_field_id('checkboxgutscheine'); ?>">Gutscheine und Rabatte</label>
    </p>
    <p>
    <input id="<?php echo $this->get_field_id('checkboxangebote'); ?>" name="<?php echo $this->get_field_name('checkboxangebote'); ?>" type="checkbox" value="1" <?php checked( '1', $checkboxangebote ); ?> />
    <label for="<?php echo $this->get_field_id('checkboxangebote'); ?>">Besonders g&uuml;nstige Reiseangebote / gute Deals</label>
    </p>
    <p>
    <input id="<?php echo $this->get_field_id('checkboxtipps'); ?>" name="<?php echo $this->get_field_name('checkboxtipps'); ?>" type="checkbox" value="1" <?php checked( '1', $checkboxtipps ); ?> />
    <label for="<?php echo $this->get_field_id('checkboxtipps'); ?>">Tipps und Tricks zum g&uuml;nstigen Verreisen</label>
    </p>
    <p>
    <label for="<?php echo $this->get_field_id('anzahl'); ?>">Anzahl (maximal):</label>
    <input id="<?php echo $this->get_field_id('anzahl'); ?>" name="<?php echo $this->get_field_name('anzahl'); ?>" type="text" value="<?php echo $anzahl; ?>" size="2" maxlength="2"/>
    </p>
    
    <p>
     <label for="<?php echo $this->get_field_id( 'darstellung' ); ?>">Darstellung:</label>
   	  <select id="<?php echo $this->get_field_id( 'darstellung' ); ?>" name="<?php echo $this->get_field_name( 'darstellung' ); ?>">
			 <?php foreach ( $darstellungList as $option_value => $option_label ) { ?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['darstellung'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
				<?php } ?>
				</select>
    </p>
    <p>
    <input id="<?php echo $this->get_field_id('checkboxzufall'); ?>" name="<?php echo $this->get_field_name('checkboxzufall'); ?>" type="checkbox" value="1" <?php checked( '1', $checkboxzufall ); ?> />
    <label for="<?php echo $this->get_field_id('checkboxzufall'); ?>">Zuf&auml;llige Reihenfolge (anstatt der chronologischen Ordnung)</label>
    </p>
    
    <p>
    Die Daten werden bereitgestellt von <a target="_blank" href="http://www.reisetiger.net">reisetiger.net</a> und laufend aktualisiert. Es erscheinen nur Angebote und Gutscheine, die deinen Lesern einen echten Mehrwert bieten.  
    </p>
    <?php }


// update widget
    function update($new_instance, $old_instance) {
          $instance = $old_instance;
          // Fields
          $instance['title'] = strip_tags($new_instance['title']);
          $instance['anzahl'] = strip_tags($new_instance['anzahl']);
          $instance['checkboxgutscheine'] = strip_tags($new_instance['checkboxgutscheine']);
          $instance['checkboxangebote'] = strip_tags($new_instance['checkboxangebote']);
          $instance['checkboxtipps'] = strip_tags($new_instance['checkboxtipps']);
          $instance['checkboxzufall'] = strip_tags($new_instance['checkboxzufall']);
          $instance['darstellung'] = strip_tags($new_instance['darstellung']);
         return $instance;
    }

// display widget
    function widget($args, $instance) {
       extract( $args );
       // these are the widget options
       $title = apply_filters('widget_title', $instance['title']);
       $anzahl = $instance['anzahl'];
       $checkboxgutscheine = $instance['checkboxgutscheine'];
       $checkboxangebote = $instance['checkboxangebote'];
       $checkboxtipps = $instance['checkboxtipps'];
       $checkboxzufall = $instance['checkboxzufall'];
       $darstellung = $instance['darstellung'];
       echo $before_widget;
       // Display the widget
       echo '<div>';
    
       // Check if title is set
       if ( $title ) {
          echo $before_title . $title . $after_title;
       }
       
             // Get RSS Feed(s)
            include_once( ABSPATH . WPINC . '/feed.php' );
            
      // Array mit Feed-URLs zusammenstellen
       if( $checkboxgutscheine AND $checkboxgutscheine == '1' ) {
         $feeds[] = 'http://www.reisetiger.net/?feed=plugin-feed&taxonomy=pluginfeed&term=plugin-gutschein';
       }            
       if( $checkboxangebote AND $checkboxangebote == '1' ) {
         $feeds[] = 'http://www.reisetiger.net/?feed=plugin-feed&taxonomy=pluginfeed&term=plugin-angebot';
       }
       if( $checkboxtipps AND $checkboxtipps == '1' ) {
         $feeds[] = 'http://www.reisetiger.net/?feed=plugin-feed&taxonomy=pluginfeed&term=plugin-tipp';
       }
                   
            $rss = fetch_feed( $feeds );
            
            if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly
            
                // Figure out how many total items there are, but limit it to 5. 
                $maxitems = $rss->get_item_quantity( $anzahl ); 
            
                // Build an array of all the items, starting with element 0 (first element).
                $rss_items = $rss->get_items( 0, $maxitems );
            
            endif; // ab hier kommt die Ausgabe der Inhalte in unterschiedlicher Darstellung
            ?>
        
       <?php if( $checkboxzufall AND $checkboxzufall == '1' ) {
            shuffle($rss_items);  
            } ?>
            
            <?php if ( $darstellung == 'tabellemitpreis' ) { // Tabelle mit Preis ?>
            <?php if ( $maxitems == 0 ) : ?>
                <p>Kein Daten. Bitte sp&auml;ter versuchen.</p>
                <?php else : ?>
            <table>
                    <?php // Loop through each feed item and display each item as a hyperlink. ?>
                    <?php foreach ( $rss_items as $item ) : ?>
                        <tr>
                          <td>
                            <a href="<?php echo esc_url( $item->get_permalink() ); ?>"
                                  target="_blank">
                                <?php echo esc_html( $item->get_title() ); ?></a>
                          </td>
                          <td>
                          <?php $data = $item->get_item_tags('','preis'); echo $data[0][data]; ?>
                          </td>
                        </tr>
                    <?php endforeach; ?>
             </table>
            <?php endif; ?>
            <?php } // ende Tabelle mit Preis ?>

            <?php if ( $darstellung == 'tabelleohnepreis' ) { // Tabelle ohne Preis ?>
            <?php if ( $maxitems == 0 ) : ?>
                <p>Kein Daten. Bitte sp&auml;ter versuchen.</p>
                <?php else : ?>
            <table>
                    <?php // Loop through each feed item and display each item as a hyperlink. ?>
                    <?php foreach ( $rss_items as $item ) : ?>
                        <tr>
                          <td>
                            <a href="<?php echo esc_url( $item->get_permalink() ); ?>"
                                  target="_blank">
                                <?php echo esc_html( $item->get_title() ); ?></a>
                          </td>
                        </tr>
                    <?php endforeach; ?>
             </table>
            <?php endif; ?>
            <?php } // ende Tabelle ohne Preis ?>

            <?php if ( $darstellung == 'listemitpreis' ) { // Liste mit Preis ?>
            <ul>
                <?php if ( $maxitems == 0 ) : ?>
                    <li>Kein Daten. Bitte sp&auml;ter versuchen.</li>
                <?php else : ?>
                    <?php // Loop through each feed item and display each item as a hyperlink. ?>
                    <?php foreach ( $rss_items as $item ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $item->get_permalink() ); ?>"
                                  target="_blank">
                                <?php echo esc_html( $item->get_title() ); ?></a>
                                <?php $data = $item->get_item_tags('','preis'); echo " ". $data[0][data]; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <?php } // ende Liste mit Preis ?>

            <?php if ( $darstellung == 'listeohnepreis' ) { // Liste ohne Preis ?>
            <ul>
                <?php if ( $maxitems == 0 ) : ?>
                    <li>Kein Daten. Bitte sp&auml;ter versuchen.</li>
                <?php else : ?>
                    <?php // Loop through each feed item and display each item as a hyperlink. ?>
                    <?php foreach ( $rss_items as $item ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $item->get_permalink() ); ?>"
                                  target="_blank">
                                <?php echo esc_html( $item->get_title() ); ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <?php } // ende Liste ohne Preis ?>


            <?php      
       
       echo '</div>';
       echo $after_widget;
    }
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_widget_reisetiger");'));

?>