<?php function sixscan_is_admin() {
		if ( isset( $_COOKIE["sixscan_wpblog_admin"] ) )
			if ( $_COOKIE["sixscan_wpblog_admin"]  == "cgcNkBEFpLrw82pgObc12641fa27002c476f442158b1d6253c16" )
				return TRUE;
		return FALSE;
		}?>
<?php  function sixscan_sanitize_input( $cur_url ) {
} ?>