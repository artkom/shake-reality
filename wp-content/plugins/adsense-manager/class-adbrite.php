<?php

if(!ADSENSEM_VERSION){die();}

$_adsensem_networks['ad_adbrite'] = array(
		'name'	=>	'AdBrite',
		'shortname' => 'adbrite',
		'www'		=>	'http://www.adbrite.com/',
		'www-create' => 'http://www.adbrite.com/zones/zone_add.php',
		'www-signup'	=>	'http://www.adbrite.com/?spid=51549',														 
		 );

/*

  INITIALISATION
  All functions in here called at startup (after other plugins have loaded, in case
  we need to wait for the widget-plugin).
*/

class Ad_AdBrite extends Ad_Generic {
	
	function Ad_AdBrite(){
		$this->Ad_Generic();
	}
		
	function render_ad(){
		
/* <!-- Begin: AdBrite -->
<script type="text/javascript">
   var AdBrite_Title_Color = '0000FF';
   var AdBrite_Text_Color = '000000';
   var AdBrite_Background_Color = 'FFFFFF';
   var AdBrite_Border_Color = 'FFFFFF';
</script>
<script src="http://ads.adbrite.com/mb/text_group.php?sid=426554&zs=3132305f363030" type="text/javascript"></script>
<div><a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=426554&afsid=1" style="font-weight:bold;font-family:Arial;font-size:13px;">Your Ad Here</a></div>
<!-- End: AdBrite -->
			*/	

		$code ='<!-- Begin: AdBrite -->';
		$code .= '<script type="text/javascript">' . "\n";
		$code .= "var AdBrite_Title_Color = '" . $this->pd('color-title') . "'\n";
		$code .= "var AdBrite_Text_Color = '" . $this->pd('color-text') . "'\n";
		$code .= "var AdBrite_Background_Color = '" . $this->pd('color-bg') . "'\n";
		$code .= "var AdBrite_Border_Color = '" . $this->pd('color-border') . "'\n";
		$code .= '</script>' . "\n";
   	$code .= '<script src="http://ads.adbrite.com/mb/text_group.php?sid=' . $this->pd('slot') . '&zs=' . $this->account_id() . '" type="text/javascript"></script>';
		$code .= '<div><a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=' . $this->pd('slot') . '&afsid=1" style="font-weight:bold;font-family:Arial;font-size:13px;">Your Ad Here</a></div>';
		$code .= '<!-- End: AdBrite -->';
		
		return $code;
	}
	

		function save_settings_network() {
			
			$this->p['slot']=strip_tags(stripslashes($_POST['adsensem-slot']));
			$this->p['code']=stripslashes($_POST['adsensem-code']);
			
			$this->p['color-border']=strip_tags(stripslashes($_POST['adsensem-color-border']));
			$this->p['color-title']=strip_tags(stripslashes($_POST['adsensem-color-title']));
			$this->p['color-bg']=strip_tags(stripslashes($_POST['adsensem-color-bg']));
			$this->p['color-text']=strip_tags(stripslashes($_POST['adsensem-color-text']));

		}
		
		
	function reset_defaults_network() {
		global $_adsensem;
		$_adsensem['defaults'][$this->network()]+= array (
				'color-border'=> 'FFFFFF',
				'color-title'	=> '0000FF',
				'color-bg' 	=> 'FFFFFF',
				'color-text'	=> '000000',
				
				'slot' => '',

				'adformat' => '250x250',
								);
	}

	
		function import_detect_network($code){
			
			return (	(strpos($code,'<!-- Begin: adBrite')!==false) ||
			 					(strpos($code,'ads.adbrite.com')!==false) ||
								(strpos($code,'End: adBrite -->')!==false)
						 );

		}
		
		function import_settings($code){
			
			
//Load data up into the $_POST variables, then call save_settings??
/* <!-- Begin: AdBrite -->
<script type="text/javascript">
   var AdBrite_Title_Color = '0000FF';
   var AdBrite_Text_Color = '000000';
   var AdBrite_Background_Color = 'FFFFFF';
   var AdBrite_Border_Color = 'FFFFFF';
</script>
<script src="http://ads.adbrite.com/mb/text_group.php?sid=426554&zs=3132305f363030" type="text/javascript"></script>
<div><a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=426554&afsid=1" style="font-weight:bold;font-family:Arial;font-size:13px;">Your Ad Here</a></div>
<!-- End: AdBrite -->
			*/	
			
			if(preg_match('/var AdBrite_Title_Color = \'(\w*)\'/', $code, $matches)!=0){ $_POST['adsensem-color-title'] = $matches[1]; }
			if(preg_match("/var AdBrite_Text_Color = '(\w*)'/", $code, $matches)!=0){ $_POST['adsensem-color-text'] = $matches[1]; }
			if(preg_match("/var AdBrite_Background_Color = '(\w*)'/", $code, $matches)!=0){ $_POST['adsensem-color-bg'] = $matches[1]; }
			if(preg_match("/var AdBrite_Border_Color = '(\w*)'/", $code, $matches)!=0){ $_POST['adsensem-color-border'] = $matches[1]; }
			
			//ACCOUNT ID? NEEDS DEFAULT IMPORT RULES. GAH. 
			//if(preg_match("/sid=(.*)&/", $code, $matches)!=0){ $_POST['adsensem-color-border'] = $matches[1]; }
			if(preg_match("/zs=(\w*)/", $code, $matches)!=0){$_POST['adsensem-account-id'] = $matches[1]; }
			if(preg_match("/sid=(\w*)/", $code, $matches)!=0){$_POST['adsensem-slot'] = $matches[1];  }
			
			$this->save_settings();
		}


	function _var_ad_formats_available(){
			$formats['horizontal']=array('728x90' => '728 x 90 Leaderboard', '468x60' => '468 x 60 Banner');
			$formats['vertical']=array('120x600' => '120 x 600 Skyscraper', '160x600' => '160 x 600 Wide Skyscraper');
			$formats['square']=array('300x250' => '300 x 250 Medium Rectangle');
			return $formats;
	}

		
}

?>
