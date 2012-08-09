<?php

if(!ADSENSEM_VERSION){die();}

$_adsensem_networks['ad_widgetbucks']	= array(
		'name' => 'WidgetBucks',
		'shortname' => 'widget',
		'www' => 'http://www.widgetbucks.com/',
		'www-create' => 'http://www.widgetbucks.com/widget.page?action=call&widgetID=',
		'www-signup' => 'http://www.widgetbucks.com/home.page?referrer=468034'
		);

/*

  INITIALISATION
  All functions in here called at startup (after other plugins have loaded, in case
  we need to wait for the widget-plugin).
*/

class Ad_WidgetBucks extends Ad_Generic {
	
	function Ad_WidgetBucks(){
		$this->Ad_Generic();
	}

				
	function render_ad(){
		
		$code ='';
		$code .= '<!-- START CUSTOM WIDGETBUCKS CODE --><div>';
		$code .= '<script src="http://api.widgetbucks.com/script/ads.js?uid=' . $this->pd('slot') . '"></script>'; 
		$code .= '</div><!-- END CUSTOM WIDGETBUCKS CODE -->';
		return $code;
		
	}
	
		function save_settings_network() {
			
			$this->p['slot']=strip_tags(stripslashes($_POST['adsensem-slot']));
		
		}

	
		function import_detect_network($code){
			
			return (	preg_match('/http:\/\/....widgetbucks.com\/script\/ads.js\?uid=/', $code, $matches) !==0);
			
		}
		
		function import_settings($code){

			if(preg_match("/http:\/\/....widgetbucks.com\/script\/ads.js\?uid=(\w*)\"/", $code, $matches)!=0){ 
				//ACCOUNT ID? NEEDS DEFAULT IMPORT RULES. GAH. 
				//$_POST['adsensem-account-id'] = $matches[3]; 
				$_POST['adsensem-slot'] = $matches[1]; 
			}
			
			$this->save_settings();
		}
	
	function _form_settings_network(){
	?><td><td><p>No network settings.</p></td></tr>
	<?php
	}
		
	function _form_settings_help(){
	?><tr><td><p>Configuration is available through the <a href="http://www.widgetbucks.com/" target="_blank">WidgetBucks site</a>. 
	Account maintenance links:</p>
	<ul>
	<li><a href="http://www.widgetbucks.com/myWidgets.page?action=call" target="_blank">My Widgets</a><br />
			View, manage and create widgets.</li>
	<li><a href="http://www.widgetbucks.com/myBucks.page?action=call" target="_blank">My Bucks</a><br />
			View your account balance and payment schedule.</li>
	<li><a href="https://www.widgetbucks.com/mySettings.page?action=call" target="_blank">My Settings</a><br />
			Change account details and other global settings.</li>
	</ul>
	</td></tr><?php
	}

//Middle
	
		
		
}

?>
