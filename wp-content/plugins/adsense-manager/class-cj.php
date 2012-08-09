<?php

if(!ADSENSEM_VERSION){die();}

$_adsensem_networks['ad_cj'] = array(
		'name'	=>	'Commission Junction',
		'shortname' => 'cj',
		'www'		=>	'http://www.cj.com/',
		'www-create' => 'https://members.cj.com/member/publisher/accounts/listmyadvertisers.do?sortKey=active_start_date&sortOrder=DESC',
		'www-signup'		=>	'http://www.cj.com/',
	);

/*

  INITIALISATION
  All functions in here called at startup (after other plugins have loaded, in case
  we need to wait for the widget-plugin).
*/

class Ad_CJ extends Ad_Generic {

	function Ad_CJ(){
		$this->Ad_Generic();
	}

	
	function render_ad(){
		
/* <a href="http://www.tkqlhce.com/click-2619547-10495765" target="_blank" onmouseover="window.status='http://www.PayPerPost.com';return true;" onmouseout="window.status=' ';return true;">
 <img src="http://www.lduhtrp.net/image-2619547-10495765" width="120" height="600" alt="Get Paid to Blog About the Things You Love" border="0"/></a> */	

			$cjservers=array(
				'www.kqzyfj.com',
				'www.tkqlhce.com',
				'www.jdoqocy.com',
				'www.dpbolvw.net',
				'www.lduhtrp.net');
		
		$code = '';
		$code .= '<!-- Start: CJ Ads -->';
	 	$code .= '<a href="http://' . $cjservers[array_rand($cjservers)] . '/click-' . $this->account_id() . '-' . $this->pd('slot') . '"';
		if($this->pd('new-window')=='yes'){$code.=' target="_blank" ';}
		
		if($this->pd('hide-link')=='yes'){
			$code.='onmouseover="window.status=\'';
			$code.=$this->pd('hide-link-url');
			$code.='\';return true;" onmouseout="window.status=\' \';return true;"';
		}
		
		$code .= '>';
		
		$code .= '<img src="http://' . $cjservers[array_rand($cjservers)] . '/image-' . $this->account_id() . '-' . $this->pd('slot') . '"';
		$code .= ' width="' . $this->pd('width') . '" ';
		$code .= ' height="' . $this->pd('height') . '" ';
		$code .= ' alt="' . $this->pd('alt-text') . '" ';
		$code .= '>';
		$code .= '</a>';
	
		return $code;
		
	}
	
	function can_benice(){return false;}
		
	function reset_defaults_network() {
		global $_adsensem;
		$_adsensem['defaults'][$this->network()]+= array (
				'hide-link' => 'no',
				'hide-link-url' => '',
				'new-window' => 'no',
				'alt-text' => '',

				'adformat' => '250x250',
								);
	}
				
		function save_settings_network() {
			
			$this->p['slot']=strip_tags(stripslashes($_POST['adsensem-slot']));
			$this->p['adformat']=stripslashes($_POST['adsensem-adformat']);
			//$this->p['code']=stripslashes($_POST['adsensem-code']);

			$this->p['hide-link']=strip_tags(stripslashes($_POST['adsensem-hide-link']));
			$this->p['hide-link-url']=strip_tags(stripslashes($_POST['adsensem-hide-link-url']));
			$this->p['new-window']=strip_tags(stripslashes($_POST['adsensem-new-window']));
			$this->p['alt-text']=strip_tags(stripslashes($_POST['adsensem-alt-text']));

		}

		function import_detect_network($code){
			
			# Domains: (add more)
			$domains = array(
			'www.commission-junction.com',
			'www.cj.com',
			'www.qksrv.net',
			'www.kqzyfj.com',
			'www.tkqlhce.com',
			'www.jdoqocy.com',
			'www.dpbolvw.net',
			'www.lduhtrp.net',
			'www.anrdoezrs.net');
			
			$match=false;
			foreach($domains as $d){$match=$match || (strpos($code,'href="http://' . $d)!==false);}
			return $match;
			
		}
		
		function import_settings($code){
			
/* <a href="http://www.tkqlhce.com/click-2619547-10495765" target="_blank" onmouseover="window.status='http://www.PayPerPost.com';return true;" onmouseout="window.status=' ';return true;">
<img src="http://www.lduhtrp.net/image-2619547-10495765" width="728" height="90" alt="Get Paid to Blog About the Things You Love" border="0"/></a> */	
		
			if(preg_match('/http:\/\/([.\w]*)\/click-(\d*)-(\d*)/', $code, $matches)!=0){ 
				//ACCOUNT ID? NEEDS DEFAULT IMPORT RULES. GAH. 
				$_POST['adsensem-account-id'] = $matches[2]; 
				$_POST['adsensem-slot'] = $matches[3]; 
			}
			
			if(preg_match('/width="(\w*)"/', $code, $matches)!=0){
				$width=$matches[1]; 
				if(preg_match('/height="(\w*)"/', $code, $matches)!=0){
					$height=$matches[1]; 
					$_POST['adsensem-adformat'] = $width . "x" . $height; //Only set if both width and height present
				}
			}

			
			$this->save_settings();
		}
		
	function _form_settings_help(){
	?><tr><td><p>Further campaigns can be found through <a href="http://www.cj.com/" target="_blank">CJ's</a> site:</p>
	<ul>
	<li><a href="https://members.cj.com/member/publisher/accounts/listmyadvertisers.do?sortKey=active_start_date&sortOrder=DESC" target="_blank">Find Advertisers (By Relationship)</a><br />
			Find more ads from existing relationships.</li>
	<li><a href="https://members.cj.com/member/publisher/accounts/listmyadvertisers.do?sortKey=active_start_date&sortOrder=DESC" target="_blank">Find Advertisers (No Relationship)</a><br />
			Find ads from new advertisers.</li>
	<li><a href="https://members.cj.com/member/publisher/other/getlinkdetail.do?adId=<?php echo $this->p('slot');?>" target="_blank">View Ad Setup</a><br />
			View the online ad setup page for this ad.</li>
	</ul>	</td></tr>
	<tr><td><p>You can also view your <a href="https://www.google.com/adsense/report/overview" target="_blank">statistics and earnings</a> online.</p></td></tr>
	<?php	
	}
	

	function _form_settings_link_options(){
			$default=array('' => 'Use Default');
			$yesno	=	array('yes' => 'Yes','no' => 'No');
			adsensem_admin::_field_input('Alternate Text','alt-text',25,'Alt text to display where images not shown.');
			adsensem_admin::_field_select('In New Window','new-window',$yesno);
			adsensem_admin::_field_select('Hide Link','hide-link',$yesno);
			adsensem_admin::_field_input('Display URL','hide-link-url',25,'Destination to display when mouse hovers link.');
	}

		
}

?>
