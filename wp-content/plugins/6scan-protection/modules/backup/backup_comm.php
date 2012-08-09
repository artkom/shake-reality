<?php

if ( ! defined( 'ABSPATH' ) ) 
        die( 'No direct access allowed' );

/* Called by the backup controller to send files to Amazon storage */
function sixscan_backup_comm_save_file( $amazon_backup_address , $backed_filename ){
        $sixscan_set_fields = array( 'key' , 'AWSAccessKeyId' , 'acl' , 'policy' , 'signature' );
        $sixscan_amazon_options = array();
        $backup_save_result = array();

        if ( file_exists( $backed_filename ) == FALSE ){
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "File $backed_filename not found";
                $backup_save_result[ 'user_result_message' ] = "6Scan failed to create the backup file.  This could indicate a permissions problem in your hosting environment.";
                return $backup_save_result;
        }

        /* Set parameters for amazon request */
        foreach ( $_REQUEST as $amazon_key => $amazon_val ) {
                if ( in_array(  $amazon_key , $sixscan_set_fields ) )
                        $sixscan_amazon_options[ $amazon_key ] = $amazon_val;
        }               
        
        /* Special value that has to be added */
        $sixscan_amazon_options[ 'Content-Type' ] = 'application/gzip';

        /*      Actual Amazon upload code */
        return sixscan_backup_comm_post_request( base64_decode( urldecode( $amazon_backup_address ) ) , $sixscan_amazon_options , $backed_filename );     
}


/*      Request to Amazon servers       */
function sixscan_backup_comm_post_request( $remote_url , $headers_array , $file_name ){

        global $sixscan_comm_data_prefix;
        global $sixscan_comm_data_appendix;
        $backup_save_result = array();

        $data_file_size = filesize( $file_name );
        $max_accepted_file_size = ( double )$_REQUEST[ 'backup_size_limit' ];
        
        if ( $data_file_size > $max_accepted_file_size){
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "File too large. Size is $data_file_size , max allowed: $max_accepted_file_size";
                $backup_save_result[ 'user_result_message' ] = "Backup file is too large (" .
                        round( $data_file_size / 1048576 , 2 ) . " MB); your account limit is " . round( $max_accepted_file_size / 1048576 , 2 ) . "MB.";
                return $backup_save_result;
        }

        /*      Random string to define data boundary in post request.
        Based on php.net information about fsockopen
        */
        srand( (double) microtime() * 1000000 );
        $boundary = "---------------------" . substr( md5 ( rand( 0, 32000 )) , 0 , 10 );        

        /* Build variables */
        foreach( $headers_array as $key => $value ){
            $sixscan_comm_data_prefix .= "--$boundary\r\n";
            $sixscan_comm_data_prefix .= "Content-Disposition: form-data; name=\"$key\"\r\n";
            $sixscan_comm_data_prefix .= "\r\n$value\r\n";
        }

        $sixscan_comm_data_prefix .= "--$boundary\r\n";
        $sixscan_comm_data_prefix .= "Content-Disposition: form-data; name=\"file\"; filename=\"$file_name\"\r\n";
        $sixscan_comm_data_prefix .= "Content-Type: application/octet-stream\r\n\r\n";

        $sixscan_comm_data_appendix = "\r\n--$boundary--\r\n";
        
        $data_size = $data_file_size + strlen( $sixscan_comm_data_prefix ) + strlen( $sixscan_comm_data_appendix );

        /* Open the file and pass it to libcurl */
        $fp = fopen( $file_name , 'r' );
        $curl_handle = curl_init();         
        curl_setopt( $curl_handle , CURLOPT_URL , $remote_url );
        curl_setopt( $curl_handle , CURLOPT_RETURNTRANSFER , 1 ); 
        curl_setopt( $curl_handle , CURLOPT_HTTPHEADER ,array(  'Content-Type: multipart/form-data; boundary=' . $boundary ,
                                                                'Content-Length: ' . $data_size ) );
        curl_setopt( $curl_handle , CURLOPT_POST , TRUE );
        curl_setopt( $curl_handle , CURLOPT_HEADER , FALSE );
        curl_setopt( $curl_handle , CURLOPT_READFUNCTION , 'sixscan_backup_comm_reader_callback' );
        curl_setopt( $curl_handle , CURLOPT_INFILESIZE , $data_size );
        curl_setopt( $curl_handle , CURLOPT_INFILE , $fp );
        
        $curl_err_description = "";
        $response = curl_exec( $curl_handle ); 

        if ( curl_errno( $curl_handle ) != 0 ){
                $curl_err_description = curl_error( $curl_handle );               
        }

        $http_ret_code = curl_getinfo( $curl_handle , CURLINFO_HTTP_CODE );
        curl_close( $curl_handle ); 
        fclose( $fp );

        /* Empty response (204) is the code for successful upload */
        if ( $http_ret_code == 204 ){
                return TRUE;
        }
        else{
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "Curl response: $curl_err_description , Amazon response: $response";
                $backup_save_result[ 'user_result_message' ] = "The connection to our backup storage server was interrupted during transfer.";
                return $backup_save_result;
        }
                
}

/*      This will be called to read every chunk from file and pass it to server */
function sixscan_backup_comm_reader_callback( $curl_handle , $fp , $requested_len) {       
        static $first_read_happened = 0;
        static $appenix_data_written = 0;
        global $sixscan_comm_data_prefix;
        global $sixscan_comm_data_appendix;

        /* First data chunk - send the prefix and part of the data */
        if ( $first_read_happened == 0 ){
                $first_read_happened++;               
                $first_data_chunk_sz = $requested_len -  strlen( $sixscan_comm_data_prefix ) ;                
                return $sixscan_comm_data_prefix . fread( $fp , $first_data_chunk_sz );
        }

        /*      Appendix left from previous write */
        if ( $appenix_data_written > 0){
                return substr( $sixscan_comm_data_appendix , $appenix_data_written + 1 );
        }
        
        /*      Read data from file and send it */
        $data_chunk = fread( $fp , $requested_len );

        /* If we had data to send - use it. Otherwise send appendix data */
        if ( strlen ( $data_chunk ) == $requested_len )
                return $data_chunk;                

        /*      If both data chunk and appendix are less (or equal) to requested - just write them */
        if ( strlen ( $data_chunk ) + strlen( $sixscan_comm_data_appendix ) <= $requested_len )
                return $data_chunk . $sixscan_comm_data_appendix;
        
        /*      If data and appendix are longer than requested - write whatever we can and remember the remainder of appenix
                It will be written in the next write, at line of ( if ( $appenix_data_written > 0){ )
         */
        if ( strlen ( $data_chunk ) + strlen( $sixscan_comm_data_appendix ) > $requested_len ){
                $appenix_data_written = $requested_len - strlen ( $data_chunk );
                return $data_chunk . substr( $sixscan_comm_data_appendix , 0 , $appenix_data_written );
        }
}
?>