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
$server = new MCServerEngine("mcserver.geekgamer.tv", 25565);

class MCServer_Main {

public function MCServer_Main() {
add_action('admin_notices',  array( $this, 'my_admin_notice') );
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
?>
