<?php
include '../../../wp-config.php';

$url = $_GET['url'];
if($url != ''):
    $affiliate_id = $_GET['affiliate_id'];
	$url = base64_decode($url);
	
	$load = 'http://itunes.apple.com/WebObjects/MZStore.woa/wa/remotePreview?url='.$url;
	$get = json_decode(preg_replace('/(null\(|\))/', '', file_get_contents($load)));
	$html = file_get_contents($get->src);

	$html = explode('</head>', $html);
	$html = str_replace('/stylesheets/ellipsis.css', 'http://itunes.apple.com/stylesheets/ellipsis.css', $html);

	$new_html = $html[0].'<link href="'.WP_PLUGIN_URL.'/itunes-preview-widget/css/ipw_widget.css" rel="stylesheet" />';
	$new_html .= '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js"></script></head>'.$html[1];
	
	function replace_links($matches){
	    $affiliate_id = $_GET['affiliate_id'];
		return 'http://click.linksynergy.com/fs-bin/click?id='.$affiliate_id.'&subid=&offerid=146261.1&type=10&tmpid=3909&RD_PARM1='.urlencode(urlencode($matches[0]));
	}
	
	if($affiliate_id != ''){
	  $new_html = preg_replace_callback('@(http://itunes.apple.com/([\w/_\.](\?\S+)?)?)@', 'replace_links', $new_html);
	}

	$new_html = preg_replace('/\<span class="text"\>(.*)\<\/span\>/', '${1}', $new_html);
	$new_html = preg_replace('/\<span class="itunes-plus"\>(.*)\<\/span\>/', '${1}', $new_html);
	$new_html = preg_replace('/\<span\>iTunes\<\/span\>/', 'iTunes', $new_html);

	echo $new_html;
endif;



?>