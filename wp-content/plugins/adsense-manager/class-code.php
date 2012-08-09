<?php

if(!ADSENSEM_VERSION){die();}

$_adsensem_networks['ad_code']  = array(
		'name'	=>	'HTML Code',
		'shortname' => 'co',
		'www'		=>	'',
		'www-signup'		=>	'',
		 );

/*

  INITIALISATION
  All functions in here called at startup (after other plugins have loaded, in case
  we need to wait for the widget-plugin).
*/

class Ad_Code extends Ad_Generic {

	function Ad_Code(){
		$this->Ad_Generic();
	}
				
  function render_ad() {

		global $_adsensem;

		$code=$this->p['code']; 

			return $code;
    }

	function save_settings_network() {
		
		/* Maybe reprocesses the dimensions *import* code here?
		 Possible to extract dimensions from most blocks of code, e.g. width="xxx" ? */
		
		
	}
			
	
	
	function import_settings($code){
	  //Attempt to find html width/height strings
	  if(preg_match('/width="(\w*)"/', $code, $matches)!=0){ $width=$matches[1]; }
	  if(preg_match('/height="(\w*)"/', $code, $matches)!=0){ $height=$matches[1]; }
	  $_POST['adsensem-adformat'] = $width . "x" . $height;
	  $_POST['adsensem-code']=$code;
      
	  $this->save_settings();
	}


	function _form_settings_help(){
	?>
			<p>AdSense Manager supports most Ad networks including <?php adsensem_admin::network_list(array('Ad_AdSenseAd','Ad_AdSenseReferral','Ad_Code')); ?>.</p>
			<p>Any networks not supported directly will be can be managed as HTML Code units. You can re-attempt import of code units at any time using the Import Options.</p>
	<?php
	}


}

?>
