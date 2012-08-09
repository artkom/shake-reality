<?php
/*
Plugin Name: DBC Backup
Plugin URI: http://www.t3-design.com/
Description: Database Cron Backup
Version: 1.1
Author: Chris T.
Author URI: http://www.t3-design.com/
*/

add_action('activate_dbcbackup/dbcbackup.php', 'dbcbackup_install');
function dbcbackup_install() 
{
	$options = array('export_dir' => '', 'compression' => 'none', 'gzip_lvl' => 0, 'period' => 86400,  'schedule' => time(), 'active' => 0, 'rotate' => -1);
	add_option('dbcbackup_options', $options, '', 'no');
}
	
add_action('deactivate_dbcbackup/dbcbackup.php', 'dbcbackup_uninstall');	
function dbcbackup_uninstall()
{
	wp_clear_scheduled_hook('dbc_backup');	
	delete_option('dbcbackup_options');
}

add_action('dbc_backup', 'dbcbackup_run');
function dbcbackup_run($mode = 'auto')
{
	if(defined('DBC_BACKUP_RETURN')) return;
	$cfg = get_option('dbcbackup_options'); 
	if(!$cfg['active'] AND $mode == 'auto') return;
	if(empty($cfg['export_dir'])) return;
	if($mode == 'auto')	dbcbackup_locale();
	
	require_once ('inc/functions.php');
	define('DBC_COMPRESSION', $cfg['compression']);
	define('DBC_GZIP_LVL', $cfg['gzip_lvl']);
	define('DBC_BACKUP_RETURN', true);
	
	$timenow 			= 	time();
	$mtime 				= 	explode(' ', microtime());
	$time_start 		= 	$mtime[1] + $mtime[0];
	$key 				= 	substr(md5(md5(DB_NAME.'|'.microtime())), 0, 6);
	$date 				= 	date('m.d.y-H.i.s', $timenow);
	list($file, $fp) 	=	dbcbackup_open($cfg['export_dir'].'/Backup_'.$date.'_'.$key);
	
	if($file)
	{
		$removed = dbcbackup_rotate($cfg, $timenow);
		@set_time_limit(0);
		$sql = mysql_query("SHOW TABLE STATUS FROM ".DB_NAME);
		dbcbackup_write($file, dbcbackup_header());
		while ($row = mysql_fetch_array($sql))
		{	
			dbcbackup_structure($row['Name'], $file);
			dbcbackup_data($row['Name'], $file);
		}
		dbcbackup_close($file);
		$result = __('Successful', 'dbcbackup');
	}
	else
	{
		$result = sprintf(__("Failed To Open: %s.", 'dbcbackup'), $fp);
	}
	$mtime 			= 	explode(' ', microtime());
	$time_end 		= 	$mtime[1] + $mtime[0];
	$time_total 	= 	$time_end - $time_start;
	$cfg['logs'][] 	= 	array ('file' => $fp, 'size' => @filesize($fp), 'started' => $timenow, 'took' => $time_total, 'status'	=> $result, 'removed' => $removed);					
	update_option('dbcbackup_options', $cfg);
	return ($mode == 'auto' ? true : $cfg['logs']);
}

function dbcbackup_locale()
{
	load_plugin_textdomain('dbcbackup', 'wp-content/plugins/dbcbackup');
}

add_action('admin_menu', 'dbcbackup_menu');
function dbcbackup_menu() 
{
	if(function_exists('add_menu_page')) 
	{
		add_menu_page('DBC Backup', 'DB Cron Backup', 'manage_options', dirname(__FILE__).'/dbcbackup-options.php');
	}
}

add_filter('cron_schedules', 'dbcbackup_interval');
function dbcbackup_interval() {
	$cfg = get_option('dbcbackup_options');
	$cfg['period'] = ($cfg['period'] == 0) ? 86400 : $cfg['period'];
	return array('dbc_backup' => array('interval' => $cfg['period'], 'display' => __('DBC Backup Interval', 'dbc_backup')));
}

?>