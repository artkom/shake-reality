<?php

function sixscan_backup_func_controller( $amazon_backup_address , $backup_type , &$backup_err_descr ){
	/* Empty error message */	

	/* Check whether backup can be run on this system */
	$run_backup_prerequisites = sixscan_backup_func_can_run();

	/* If there was a false in prerequisites */
	if ( array_search( FALSE , array_values( $run_backup_prerequisites ) , TRUE ) !== FALSE ){
		$backup_err_descr[ 'prerequisites' ] = $run_backup_prerequisites;	
		$backup_err_descr[ 'success' ] = FALSE;
		return;
	}

	/* Set enough time for backup to work. */
	set_time_limit( SIXSCAN_BACKUP_MAX_RUN_SECONDS );	

	/* Run backup according to what was requested */
	if ( $backup_type == SIXSCAN_BACKUP_DATABASE_REQUEST ){		

		 $backup_result = sixscan_backup_func_db( $backup_name_file_result );					
	}
	else if ( $backup_type == SIXSCAN_BACKUP_FILES_REQUEST ){

		$backup_result = sixscan_backup_func_files( $backup_name_file_result );
	}
	else {
		 $backup_err_descr[ 'success' ] = FALSE;
		 $backup_err_descr[ 'user_result_message' ] = "Bad action type requested";
		 $backup_err_descr[ 'internal_message' ] = "Bad action type requested";
		 return FALSE;
	}
	
	/*	An error occured */
	if ( $backup_result === FALSE){
	 	$backup_err_descr = array_merge(  $backup_err_descr , $backup_name_file_result );		
		return FALSE;				
	}

	/* Save to amazon */
	$amazon_save_val = sixscan_backup_comm_save_file( $amazon_backup_address , $backup_name_file_result );
	
	/* Cleanup */
	if ( file_exists( $backup_name_file_result ) ){
		$backup_err_descr[ 'md5' ] = md5_file( $backup_name_file_result );
		$backup_err_descr[ 'size' ] = filesize( $backup_name_file_result );
		unlink( $backup_name_file_result );
	}

	/* If we have failed uploading the file to amazon - return the error description */
	if ( $amazon_save_val !== TRUE ){
		$backup_err_descr = array_merge(  $backup_err_descr ,$amazon_save_val );
		return FALSE;
	}	
	
	$backup_err_descr[ 'success' ] = TRUE;
	return TRUE;
}

function sixscan_backup_func_can_run(){
	$requirements_table = array();

	/* We don't run on Windows now */
	if ( sixscan_common_is_windows_os() == TRUE ){		
		$requirements_table[ 'Operating system' ] =  FALSE;
	}
	else{
		$requirements_table[ 'Operating system' ] =  TRUE;	
	}

	/*	Can't run in safe mode */
	if ( ini_get( 'safe_mode' ) ){
		$requirements_table[ 'Safe mode disabled' ] =  FALSE;
	}
	else{
		$requirements_table[ 'Safe mode disabled' ] =  TRUE;	
	}
	
	/* We need to be able to change execution time */	
	@set_time_limit( SIXSCAN_BACKUP_MAX_RUN_SECONDS );
	if ( ini_get( 'max_execution_time' ) != SIXSCAN_BACKUP_MAX_RUN_SECONDS ){
		$requirements_table[ 'Max execution time' ] =  FALSE;
	}
	else{
		$requirements_table[ 'Max execution time' ] =  TRUE;	
	}

	/* Requires libcurl for file upload */
	if ( function_exists( 'curl_init' ) == FALSE ){
		$requirements_table[ 'libcurl installed' ] = FALSE;
	}
	else{
		$requirements_table[ 'libcurl installed' ] = TRUE;
	}

	/* Check libcurl for SSL support */
	$libcurl_version = curl_version();
	$libcurl_is_ssl_supported = ( $libcurl_version[ 'features' ] & CURL_VERSION_SSL );
	if ( (bool)$libcurl_is_ssl_supported != TRUE ){
		$requirements_table[ 'libcurl SSL support' ] = FALSE;
	}
	else{
		$requirements_table[ 'libcurl SSL support' ] = TRUE;
	}

	/* Testing whether we can execute functions */
	$disabled_functions = ini_get( "disable_functions" );
	if ( strstr( $disabled_functions , "passthru" ) !== FALSE ){
		$requirements_table[ 'passthru() enabled' ] = FALSE;
	}
	else{
		$requirements_table[ 'passthru() enabled' ] = TRUE;
	}

	ob_start();	passthru( "mysqldump --version" ); $dumpavailable = ob_get_contents(); ob_end_clean();
	if ( strlen( $dumpavailable ) == 0 ){
		$requirements_table[ 'mysqldump exists' ] = FALSE;
	}
	else{
		$requirements_table[ 'mysqldump exists' ] = TRUE;
	}

	return $requirements_table;
}

/*  Run files backup */
function sixscan_backup_func_files( &$backed_up_filename ){
	
	/*	Generate random seed and random file name */
	srand( (double) microtime() * 1000000 );
	$tmp_random_seed = date("Y-m-d-H-i-s") . "_" . substr( md5 ( rand( 0, 32000 )) , 0 , 10 );	
	$temp_file_archived = get_temp_dir() . "files_backup_$tmp_random_seed.tar.gz";
	$tmp_backup_dir = "/tmp/6scan_backup_$tmp_random_seed/";

	/*	If a previous file was not deleted from some reason, delete it now */
	sixscan_backup_func_delete_previous( SIXSCAN_BACKUP_LAST_FS_NAME , $temp_file_archived );

	/* Prepare backup directory */	
	$backup_cmd = "mkdir $tmp_backup_dir; cp -r " . ABSPATH . " $tmp_backup_dir";
	ob_start(); passthru( $backup_cmd ); ob_end_clean();

	/* Linux backup is using tar.gz */		
	$backup_cmd = "tar -czf $temp_file_archived $tmp_backup_dir 2>&1";
	$ret_val = 0;

	/* Run the tar command, while ignoring its output */
	ob_start(); passthru( $backup_cmd , $ret_val ); $tar_output = ob_get_contents(); ob_clean();

	$cleanup_cmd = "rm -rf $tmp_backup_dir 2>&1";
	passthru( $cleanup_cmd );

	/* Check for error that might've occured while running tar. Retval 0 is ok */
	if ( $ret_val == 0 ){
		$backed_up_filename = $temp_file_archived;		 		
		return TRUE;
	}
	else{
		$backed_up_filename[ 'internal_message' ] = "Failed running tar. Retval = $ret_val , Tar error: $tar_output";
		$backed_up_filename[ 'user_result_message' ] = "Your hosting environment does not support the tar command, required for backup archiving.";
		$backed_up_filename[ 'success' ] = FALSE;
		return FALSE;
	}
}

/* Run DB backup */
function sixscan_backup_func_db( &$backed_up_filename ){

	/*	Generate random seed and random file name */
	srand( (double) microtime() * 1000000 );
	$tmp_sql_dmp = "sql_dump" . date("Y-m-d-H-i-s") . "_" . substr( md5 ( rand( 0, 32000 )) , 0 , 10 );

	$temp_sql_file_name = get_temp_dir() . $tmp_sql_dmp . ".sql";	
	$temp_sql_file_tgzipped = get_temp_dir() . $tmp_sql_dmp . ".tar.gz";
	$temp_backup_status = get_temp_dir() . $tmp_sql_dmp . ".err.txt";

	/*	If a previous file was not deleted from some reason, delete it now and save the current filename */
	sixscan_backup_func_delete_previous( SIXSCAN_BACKUP_LAST_DB_NAME , $temp_sql_file_tgzipped );

	/* Prepare the mysqldump command, using defines from wp-config.php */
	if ( strncmp( DB_HOST , 'localhost:' , 10 ) == 0 ){
		
		/*	Connecting to DB using unix socket. 'Remove the localhost:'' prefix */
		$db_backup_cmd = "mysqldump -S " . substr( DB_HOST , 10 ) . " -u " . DB_USER . " -p'" . DB_PASSWORD . "' " . DB_NAME . " 2>$temp_backup_status > $temp_sql_file_name";	
	}
	else{
		/*	Connecting to DB using tcp connect */
		$db_backup_cmd = "mysqldump -h " . DB_HOST . " -u " . DB_USER . " -p'" . DB_PASSWORD . "' " . DB_NAME . " 2>$temp_backup_status > $temp_sql_file_name";	
	}

	/* Run the mysqldump */	
	$ret_val = 0;
	passthru( $db_backup_cmd , $ret_val );

	if ( $ret_val != 0 ){
		$mysqldump_err = file_get_contents( $temp_backup_status );
		$backed_up_filename[ 'internal_message' ] = "Mysqldump failed. Retval = $ret_val. Mysqldump error = $$mysqldump_err";
		$backed_up_filename[ 'user_result_message' ] = "Mysqldump command failed : $mysqldump_err";
		$backed_up_filename[ 'success' ] = FALSE;

		@unlink( $temp_sql_file_name );
		@unlink( $temp_backup_status );
		return FALSE;
	}	
	
	/*	Remove the stderr file, if no error has occured */
	@unlink( $temp_backup_status );

	/* Create tar.gz and remove the original .sql, while ignoring the output */
	$archive_command = "tar -czf $temp_sql_file_tgzipped $temp_sql_file_name 2>&1";
	$ret_val = "";
	ob_start(); passthru( $archive_command , $ret_val ); $tar_output = ob_get_contents(); ob_clean();

	$cleanup_cmd = "rm $temp_sql_file_name 2>&1";
	passthru( $cleanup_cmd );

	if ( $ret_val == 0 ){
		$backed_up_filename = $temp_sql_file_tgzipped;
		return TRUE;
	}
	else{
		$backed_up_filename[ 'internal_message' ] = "Failed running tar of sql dump file. Retval = $ret_val , tar result = $tar_output";
		$backed_up_filename[ 'user_result_message' ] = "Your hosting environment does not support the tar command, required for backup archiving.";
		$backed_up_filename[ 'success' ] = FALSE;		
		return FALSE;		
	}
}

/*	If, from any reason (like OOM, server reset or anything else), the script was interrupted
	while creating/uploading a backup, we should cleanup the archive files. */
function sixscan_backup_func_delete_previous( $backup_type , $new_backup_filename ){

	$last_db_backup_name = get_option( $backup_type );
	if ( file_exists( $last_db_backup_name ) )
		@unlink( $last_db_backup_name );
	update_option( $backup_type , $new_backup_filename );
}

?>