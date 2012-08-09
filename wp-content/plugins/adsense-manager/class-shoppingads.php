<?php

if(!ADSENSEM_VERSION){die();}

$_adsensem_networks['ad_shoppingads'] = array(
		'name'	=>	'ShoppingAds',
		'shortname' => 'shops',
		'www'		=>	'http://shoppingads.com/',
		'www-create'	=>	'http://shoppingads.com/getcode/',
		'www-signup'	=>	'http://www.shoppingads.com/refer_1ebff04bf5805f6da1b4',
		 );

/*

  INITIALISATION
  All functions in here called at startup (after other plugins have loaded, in case
  we need to wait for the widget-plugin).
*/

class Ad_ShoppingAds extends Ad_Generic {
	
	function Ad_ShoppingAds(){
		$this->Ad_Generic();
	}
		
	function render_ad(){
		
		$code = '<script type="text/javascript"><!--' . "\n";
		$code.= 'shoppingads_ad_client = "' . $this->account_id() . '";' . "\n";
		$code.= 'shoppingads_ad_campaign = "' . $this->pd('campaign') . '";' . "\n";

		list($width,$height,$null)=split('[x]',$this->pd('adformat'));
		$code.= 'shoppingads_ad_width = "' . $width . '";' . "\n";
		$code.= 'shoppingads_ad_height = "' . $height . '";' . "\n";

		$code.= 'shoppingads_ad_kw = "' . $this->pd('keywords') . '";' . "\n";

		$code.= 'shoppingads_color_border = "' . $this->pd('color-border') . '";' . "\n";
		$code.= 'shoppingads_color_bg = "' . $this->pd('color-bg') . '";' . "\n";
		$code.= 'shoppingads_color_heading = "' . $this->pd('color-title') . '";' . "\n";
		$code.= 'shoppingads_color_text = "' . $this->pd('color-text') . '";' . "\n";
		$code.= 'shoppingads_color_link = "' . $this->pd('color-title') . '";' . "\n";

		$code.= 'shoppingads_attitude = "' . $this->pd('attitude') . '";' . "\n";
		if($this->pd('new-window')=='yes'){$code.= 'shoppingads_options = "n";' . "\n";}

		$code.= '--></script>
		<script type="text/javascript" src="http://ads.shoppingads.com/pagead/show_sa_ads.js">
		</script>' . "\n";
		
		return $code;
	}
	

		function save_settings_network() {
			
			$this->p['campaign']=strip_tags(stripslashes($_POST['adsensem-campaign']));
			$this->p['keywords']=strip_tags(stripslashes($_POST['adsensem-keywords']));
			$this->p['attitude']=strip_tags(stripslashes($_POST['adsensem-attitude']));
			$this->p['new-window']=strip_tags(stripslashes($_POST['adsensem-new-window']));
			
			$this->p['color-border']=strip_tags(stripslashes($_POST['adsensem-color-border']));
			$this->p['color-title']=strip_tags(stripslashes($_POST['adsensem-color-title']));
			$this->p['color-bg']=strip_tags(stripslashes($_POST['adsensem-color-bg']));
			$this->p['color-text']=strip_tags(stripslashes($_POST['adsensem-color-text']));
			$this->p['color-link']=strip_tags(stripslashes($_POST['adsensem-color-link']));
		}
		
		
	function reset_defaults_network() {
		global $_adsensem;
		$_adsensem['defaults'][$this->network()]+= array (
				'color-border'=> 'FFFFFF',
				'color-bg'	=> 'FFFFFF',
				'color-title'	=> '00A0E2',
				'color-text' 	=> '000000',
				'color-link'	=> '008000',
				'campaign' => '',
				'keywords' => '',
				'attitude' => 'cool',
				'new-window' => 'no',
				'adformat' => '250x250',
								);
	}

	
		function import_detect_network($code){
			return ( strpos($code,'shoppingads_ad_client')!==false );
		}
		
		function import_settings($code){

			if(preg_match('/shoppingads_ad_campaign(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-campaign'] = $matches[3]; }
			
			//Process dimensions and fake adformat (to auto-select from list when editing) (NO CUSTOM OPTIONS)
			if(preg_match('/shoppingads_ad_height(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-height'] = $matches[3]; }
			if(preg_match('/shoppingads_ad_width(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-width'] = $matches[3]; }
				$_POST['adsensem-adformat']=$_POST['adsensem-width'] . 'x' . $_POST['adsensem-height'];
			
			if(preg_match('/shoppingads_ad_kw(\s*)=(\s*)"([^"]*)"/', $code, $matches)!=0){ $_POST['adsensem-keywords'] = $matches[3]; }					 
			
				if(preg_match('/shoppingads_color_border(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-color-border'] = $matches[3]; }
				if(preg_match('/shoppingads_color_bg(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-color-bg'] = $matches[3]; }
				if(preg_match('/shoppingads_color_heading(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-color-title'] = $matches[3]; }
				if(preg_match('/shoppingads_color_text(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-color-text'] = $matches[3]; }
				if(preg_match('/shoppingads_color_link(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-color-link'] = $matches[3]; }
			
			if(preg_match('/shoppingads_attitude(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-attitude'] = $matches[3]; }
			if(preg_match('/shoppingads_options(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ ($matches[3]=='n')?$_POST['adsensem-new-window']='yes':$_POST['adsensem-new-window']='no'; }
			
			if(preg_match('/shoppingads_ad_client(\s*)=(\s*)"(\w*)"/', $code, $matches)!=0){ $_POST['adsensem-account-id'] = $matches[3]; }
			
			$this->save_settings();
		}

	
	function _form_settings_campaign(){
			adsensem_admin::_field_input('Campaign','campaign',25,'Campaign identifier for this unit.',true);
			adsensem_admin::_field_input('Keywords','keywords',25,'Keywords for this unit',true);
	}
	
	function _form_settings_style(){
		$default=array('' => 'Use Default');
		$attitude	=	array('true' => 'Classic','false' => 'Basic','cool' => 'Cool Blue','fader'=>'Ad Fader','etched'=>'Etched');
		$yesno	=	array('yes' => 'Yes','no' => 'No');
		adsensem_admin::_field_select('Attitude','attitude',$yesno);
	 	adsensem_admin::_field_select('Open New Window','new-window',$yesno);
	}
		
	
	//Middle

	
	
}

?>
