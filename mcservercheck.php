<?php
/**
 * @package MC_Server_Check
 * @version alpha
 */
/*
Plugin Name: MC Server Check
Plugin URI: http://wordpress.org/extend/plugins/mc-server-check/
Description: A plugin to display Minecraft server stats with shortcodes and widgets.
Author: Robert Lane
Version: alpha
Author URI: http://profiles.wordpress.org/robertlane
*/

include_once('includes/server_check_engine.php');
/*
add_shortcode( 'yourmom', 'yourmomfoo' );
function yourmomfoo() {
return "<h3>Your Mom!</h3>";
}
*/

add_shortcode( 'mcservercheck', 'mcserver_shortcode' );

function mcserver_shortcode( $atts, $content = 'Minecraft Server' ) {

$bg_img =  plugin_dir_url( __FILE__ )."img/background_rose.png";

extract( shortcode_atts( array(
   'domain' => 'minecraft.example.com',
   'port' => 25565,
   ), $atts ) );

$server = new MCServerEngine( $domain, $port );

if ($server->online == false) {
  return '<div class="mcserver_offline">Down</down>';
}

ob_start();
?>
<div class="mcserver_online" id="<?php echo $domain; ?>" style="background-image:url('<?php echo $bg_img; ?>');width:500px;height:150px;color:white;font-size:14px;margin-left:auto;margin-right:auto;position:relative;font-family:arial;border:5px;border-radius:5px">
    <div class="mcserver_stats" style="position:absolute;top:5px;left:10px">
        <p class="name" style="font-size:1.5em;font-family:'arial black';"><?php echo $content; ?></p>
        <p class="domain" style="margin:0"><?php echo $domain; ?></p>
        <p class="players" style="margin:0"><?php echo "Current Players: ".$server->online_players."/".$server->max_players; ?></p>
        <p class="motd" style="margin:0"><?php echo $server->motd; ?></p>
    </div>
</div>

<?php
$layout = ob_get_contents();
ob_end_clean();
return $layout;
}


/*
class MCServer_Main {

public function MCServer_Main() {
//add_action('admin_notices',  array( $this, 'my_admin_notice') );


}



static function my_admin_notice(){
global $server;
ob_start();
?>

<div class="updated">
<?php echo $server->motd; ?> Current Players: <? echo $server->online_players."/".$server->max_players; ?>
</div>

<?php
ob_flush();

}



} // Closes the Class
$MCServerMain = new MCServer_Main;
*/

?>
