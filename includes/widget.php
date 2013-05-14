<?php

// A quick check to prevent running the include by itself
if ('widget.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('<h1>Direct File Access Prohibited</h1>');

/*
Plugin Name: Random Post Widget
Plugin URI: http://jamesbruce.me/
Description: Random Post Widget grabs a random post and the associated thumbnail to display on your sidebar
Author: James Bruce
Version: 1
Author URI: http://jamesbruce.me/
*/


class MCServer_Check_Widget extends WP_Widget // The example widget class
{
  function MCServer_Check_Widget() // Widget Settings
  {
    $widget_ops = array('classname' => 'MCServer_Check_Widget', 'description' => 'Displays the status of Minecraft Server' );
    $this->WP_Widget('MCServer_Check_Widget', 'Minecraft Server Stats', $widget_ops);
  }

  function form($instance) // and of course the form for the widget options
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' , 'domain' => 'minecraft.example.com', 'port' => '25565', 'show_info' => true ) );
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($instance['title']); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('domain'); ?>">Domain: <input class="widefat" id="<?php echo $this->get_field_id('domain'); ?>" name="<?php echo $this->get_field_name('domain'); ?>" type="text" value="<?php echo attribute_escape($instance['domain']); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('port'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('port'); ?>" name="<?php echo $this->get_field_name('port'); ?>" type="text" value="<?php echo attribute_escape($instance['port']); ?>" /></label></p>
<?php
  }

  function update($new_instance, $old_instance) // update the widget
  {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['domain'] = strip_tags( $new_instance['domain'] );
    $instance['port'] = strip_tags( $new_instance['port'] );
    return $instance;
  }

  function widget($args, $instance) // display the widget
  {
    extract($args, EXTR_SKIP);

    $domain = $instance['domain'];

    $server = new MCServerStatus( $instance['domain'] , $instance['port'] );

    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

    if (!empty($title))
      echo $before_title . $title . $after_title;

    // WIDGET CODE GOES HERE
    ob_start();
    ?>
<div class="mcserver_online">
    <div class="mcserver_stats">
        <p class="domain" style="margin:0"><?php echo $domain; ?></p>
        <p class="players" style="margin:0"><?php echo "Current Players: ".$server->online_players."/".$server->max_players; ?></p>
        <p class="motd" style="margin:0"><?php echo $server->motd; ?></p>
    </div>
</div>
    <?php
    $layout = ob_get_contents();
    ob_end_clean();
    echo $layout;
    echo $after_widget;
  }

}

add_action( 'widgets_init', create_function('', 'return register_widget("MCServer_Check_Widget");') );

?>
