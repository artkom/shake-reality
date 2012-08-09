<?php
if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );

function sixscan_htaccess_install( $htaccess_sixscan_version = "") {
	global $wp_filesystem;

	try { 
		$htaccess_sixscan = trim ( $wp_filesystem->get_contents( SIXSCAN_HTACCESS_6SCAN . $htaccess_sixscan_version ) ) . "\n\n";		
		
		if ( ! $wp_filesystem->copy( SIXSCAN_HTACCESS_6SCAN_GATE_SOURCE, SIXSCAN_HTACCESS_6SCAN_GATE_DEST , TRUE , 0755 ) ) {
			throw new Exception( 'Failed to find ' . SIXSCAN_HTACCESS_6SCAN_GATE_FILE_NAME . ' during installation' );
		}		
	
		if ( ! $wp_filesystem->copy( SIXSCAN_SIGNATURE_SRC, SIXSCAN_SIGNATURE_DEST , TRUE , 0755 ) ) {
			throw new Exception( 'Failed to find ' . SIXSCAN_SIGNATURE_SRC . ' during installation' );
		}
		
		if ( $wp_filesystem->exists( SIXSCAN_HTACCESS_FILE ) ) {
			$htaccess_content = $wp_filesystem->get_contents( SIXSCAN_HTACCESS_FILE );
			$htaccess_sixscan .= preg_replace( '@# Created by 6Scan plugin(.*?)# End of 6Scan plugin@s' , '' , $htaccess_content ) ;
		}
		
		if ( $wp_filesystem->put_contents( SIXSCAN_HTACCESS_FILE , $htaccess_sixscan ) === FALSE )
			throw new Exception('Failed to open htaccess during installation');
		
	} catch( Exception $e ) {
		return( $e );
	}			
	
	return TRUE;
}
	
function sixscan_htaccess_uninstall() {
	global $wp_filesystem;

	if ( $wp_filesystem == NULL )
		WP_Filesystem();

	try {
		if ( $wp_filesystem->exists( SIXSCAN_HTACCESS_FILE ) ) {
			$htaccess_content = $wp_filesystem->get_contents( SIXSCAN_HTACCESS_FILE );
			$a = preg_replace( '@# Created by 6Scan plugin(.*?)# End of 6Scan plugin@s', '', $htaccess_content) ;
		}
	
		if ( $wp_filesystem->put_contents( SIXSCAN_HTACCESS_FILE , $a ) === FALSE )
			throw new Exception('Failed to open htaccess during installation');		
		
		if ( filesize( SIXSCAN_HTACCESS_FILE ) == 1 ) 
			$wp_filesystem->delete( SIXSCAN_HTACCESS_FILE );
			
		if ( $wp_filesystem->exists( SIXSCAN_HTACCESS_6SCAN_GATE_DEST ) )
			$wp_filesystem->delete( SIXSCAN_HTACCESS_6SCAN_GATE_DEST );	
			
		if ( $wp_filesystem->exists( SIXSCAN_SIGNATURE_DEST ) )
			$wp_filesystem->delete ( SIXSCAN_SIGNATURE_DEST ) ;
		
	} catch( Exception $e ) {
		return( $e );
	}
	
	return TRUE;
}
?>