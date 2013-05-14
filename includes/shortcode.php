<?php

// A quick check to prevent running the include by itself
if ('shortcode.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('<h1>Direct File Access Prohibited</h1>');

function mcserver_shortcode( $atts, $content = 'Minecraft Server' ) {

global $MCServerCheck_pluginURL;

extract( shortcode_atts( array(
   'domain' => 'minecraft.example.com',
   'port' => 25565,
   'bg_image' => '',
   ), $atts ) );

$mcserver_cache = get_option('mcserver_cache');
$current_time = time();
$cache_expirery = 60;
$expire_time = $current_time + $cache_expirery;



if ( array_key_exists($domain, $mcserver_cache) && $current_time <= $mcserver_cache[$domain]['time'] ) {
    $values = array(
        "online" => $mcserver_cache[$domain]['online'],
        "online_players" => $mcserver_cache[$domain]['online_players'],
        "max_players" => $mcserver_cache[$domain]['max_players'],
        "motd" => $mcserver_cache[$domain]['motd'],
    );
    $server['online'] = true;
    $server = (object) $values;
} else {
    $server = new MCServerStatus( $domain, $port );
    $site_cache = array(
        "time" => $expire_time,
        "online" => $server->online,
        "online_players" => $server->online_players,
        "max_players" => $server->max_players,
        "motd" => $server->motd,
    );
    $mcserver_cache[$domain] = $site_cache;
    update_option( 'mcserver_cache', $mcserver_cache );
}



if ( $server->online == true ) {

    ob_start();
?>

<div class='mcserver_online' id='<?php echo $domain; ?>' style='color:white;background-image:url("<?php echo $MCServerCheck_pluginURL."img/background_rose.png" ?>");'>
    <div class="mcserver_stats">
        <p class="name" style="font-size:1.5em;font-family:'arial black';"><?php echo $content; ?></p>
        <p class="domain" style="margin:0"><?php echo $domain; ?></p>
        <p class="players" style="margin:0"><?php echo "Current Players: ".$server->online_players."/".$server->max_players; ?></p>
        <p class="motd" style="margin:0"><?php echo $server->motd; ?></p>
       <!-- <h2><?php echo print_r($mcserver_cache[$domain]); ?> </h2>
        <h1><?php echo $expire_time; ?>
    -->
    </div>
</div>
<hr>

<?php
    $layout = ob_get_contents();
    ob_end_clean();
    return $layout;
} else {
    ob_start();
    ?>
    <div class='mcserver_offline' style='background-image:url("<?php echo $MCServerCheck_pluginURL."img/static01.gif" ?>");'><?php echo $domain; ?> <p>is</p> <strong>Offline</strong></div>
        <h2><?php echo print_r($mcserver_cache[$domain]); ?> </h2>
        <h1><?php echo ($current_time + $cache_expirery); ?>
        <hr>
       <!-- <h1><?php echo print_r($server); ?>
        <h1><?php echo $mcserver_cache[$domain]['time']; ?>
    -->
    <?php
    $layout = ob_get_contents();
    ob_end_clean();
    return $layout;
}

}

?>
