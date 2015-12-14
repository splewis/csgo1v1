<?php
/**
 * =============================================================================
 * @author Clayton
 * @version 1.3.6
 * @link https://github.com/supimfuzzy/csgo-multi-1v1
 * =============================================================================
 */

$mysql_host = '127.0.0.1';
$mysql_user = 'ryan';
$mysql_pass = 'cr0wdcontr0l!';
$mysql_db = 'csgo_server';
$mysql_table = 'multi1v1_stats'; //Default table set by game plugin. Do not change this if you don't know what you are doing.

$page_title = "Multi 1v1 Arena";
$SteamAPI_Key = "6B8C3DF74E2AC40E779F5FB56B4F2112"; //https://steamcommunity.com/dev/apikey
$antisquatter_rate_loss = 40; //Rate loss constant. A higher value equals a higher rate loss per day of inactivity.
$log_antisquatter = true; //Keep IP logs and the number of player stats changes. For debugging and testing purposes only.
$anti_squatter_pass = ""; //Used to access anti-squatter.php
$days_until_inactivity = 7; //Number of days before a player is given an inactive status.

$connect = mysqli_connect($mysql_host,$mysql_user,$mysql_pass) or die('Cannot connect to server.');
$connect->set_charset('utf8');
$select_db = mysqli_select_db($connect, $mysql_db) or die('Cannot find database.')
?>
