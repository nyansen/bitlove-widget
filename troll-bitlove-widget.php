<?php
  /**
   * Plugin Name: Bitlove widget
   * Plugin URI:
   * Description: Create a Link-Button to your Bitlove Page
   * Author: Trollfunk
   * Version: 1.0
   * Author URI: http://www.trollfunk.de
   * License: GPL 2.0, @see http://www.gnu.org/licenses/gpl-2.0.html
   **/

  class troll_bitlove_widget extends WP_Widget {
    var $prefix;
    var $textdomain;
    
    /**
     * Set up the widget's unique name, ID, class, description, and other options.
     * @since 1.0
     */
    function troll_bitlove_widget() {
      $this->prefix = 'troll';
      $this->textdomain = 'troll-bitlove-widget';
      
      $this->plugin_file = 'troll-bitlove-widget/troll-bitlove-widget.php';
      $this->settings_url = admin_url( 'widgets.php' );
      $this->donate_url = !empty($this->donate_url) ? $this->donate_url : 'http://flattr.com/thing/466024/Trollfunk';
      $this->support_url = !empty($this->support_url) ? $this->support_url : 'http://trollfunk.de/';

      $widget_ops = array('classname' => 'widget-bitlove', 'description' => __( 'Display a bitlove button to your profil page ore feed page.', $this->textdomain) );
      parent::WP_Widget("{$this->prefix}-bitlove-widget", __('Bitlove', $this->textdomain), $widget_ops);

      // Filtering pluginn action links and plugin row meta
      add_filter( 'plugin_action_links', array(&$this, 'plugin_action_links'),  10, 2 );
      add_filter( 'plugin_row_meta', array(&$this, 'plugin_row_meta'),  10, 2 );
    }

    /**
     * Outputs the widget based on the arguments input through the widget controls.
     * @since 1.0
     */
    function widget($args, $instance) {
      extract( $args );
      
      echo $before_widget;
      echo '<p><a href="http://www.bitlove.org/';
      if ($instance['screen_name'] != '')
        echo $instance['screen_name'];
      if ($instance['screen_name'] != '' AND $instance['feed_name'] != '' )
        echo '/' . $instance['feed_name'];
      if ($instance['screen_name'])
        echo '/';
      echo '" target="_blank"><img src="/wp-content/plugins/troll-bitlove-widget/images/bitlove-button.png" alt="bitlove" /></a></p>';
      echo $after_widget;
    }
    
    /**
     * Displays the widget control options in the Widgets admin screen.
     * @since 1.0
     */
    function form( $instance ) {
      $defaults = array(
        'title'         => 'Bitlove',
        'screen_name'   => '',
        'feed_name'     => '',
      );

      $instance = wp_parse_args( (array) $instance, $defaults );
      ?>

      <div class="troll-widget-controls">
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', $this->textdomain ); ?></label>
          <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('screen_name'); ?>"><?php _e('Username:',$this->textdomain); ?></label>
          <input type="text" name="<?php echo $this->get_field_name('screen_name'); ?>" value="<?php echo esc_attr( $instance['screen_name'] ); ?>" class="widefat" id="<?php echo $this->get_field_id('screen_name'); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('feed_name'); ?>"><?php _e('Feedame:',$this->textdomain); ?></label>
          <input type="text" name="<?php echo $this->get_field_name('feed_name'); ?>" value="<?php echo esc_attr( $instance['feed_name'] ); ?>" class="widefat" id="<?php echo $this->get_field_id('feed_name'); ?>" />
        </p>
      </div>
    <?php 
    }

    function plugin_action_links( $actions, $plugin_file ) {
      if ( $plugin_file == $this->plugin_file && $this->settings_url)
        $actions[] = '<a href="'.$this->settings_url.'">' . __('Settings', 'troll-core') .'</a>';
      
      return $actions;
    }
  
    function plugin_row_meta( $plugin_meta, $plugin_file ){
      if ( $plugin_file == $this->plugin_file ) {
        $plugin_meta[] = '<a href="'.$this->donate_url.'">' . __('Donate', 'troll-core') .'</a>';
        $plugin_meta[] = '<a href="'.$this->support_url.'">' . __('Support', 'troll-core') .'</a>';
      }

      return $plugin_meta;
    }
  }

add_action('widgets_init', 'register_troll_bitlove_widget');

function register_troll_bitlove_widget() {
  register_widget('troll_bitlove_widget');
}  