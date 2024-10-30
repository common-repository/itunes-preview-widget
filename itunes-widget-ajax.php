<?php 

include '../../../wp-config.php';
include '../../../wp-admin/admin.php';


if(isset($_POST)){
	switch($_POST['action']){
	
		case 'widget':
			$url = $_GET['url'];
			if($url != ''):

				$url = base64_decode($url);

				$load = 'http://itunes.apple.com/WebObjects/MZStore.woa/wa/remotePreview?url='.$url;
				$get = json_decode(preg_replace('/(null\(|\))/', '', file_get_contents($load)));
				$html = file_get_contents($get->src);
				#print_r($get); exit();

				$html = explode('</head>', $html);
				$html = str_replace('/stylesheets/ellipsis.css', 'http://itunes.apple.com/stylesheets/ellipsis.css', $html);

				$html = $html[0].'<link href="'.get_option('siteurl').'/wp-content/plugins/itunes-preview-widget/css/widget.css" rel="stylesheet" />
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js"></script></head>'.$html[1];

				$html = preg_replace('/\<span class="text"\>(.*)\<\/span\>/', '${1}', $html);
				$html = preg_replace('/\<span class="itunes-plus"\>(.*)\<\/span\>/', '${1}', $html);
				$html = preg_replace('/\<span\>iTunes\<\/span\>/', 'iTunes', $html);

				echo $html;
			endif;
		break;
		
		case 'add_artist':
			$ipw_options = get_option('ipw_options');			
			$artist_id = $_POST['artist_id'];		
			
			$ipw_options['ipw_artist'] = esc_attr($artist_id);
			update_option('ipw_options', $ipw_options);		
		break;

		case 'update':
			$post_options = $_POST['ipw_options'];		
			$ipw_options = get_option('ipw_options');			
			
			// clear out artist data if new entity type
			if($ipw_options['ipw_entity'] != $post_options['ipw_entity']){
				$ipw_options['ipw_artist'] = '';
			}
			
			$ipw_options['ipw_country'] = esc_attr($post_options['ipw_country']);
			$ipw_options['ipw_entity'] = esc_attr($post_options['ipw_entity']);
			$ipw_options['ipw_album_count'] = esc_attr($post_options['ipw_album_count']);
			$ipw_options['ipw_show_albums'] = esc_attr($post_options['ipw_show_albums']);
			$ipw_options['ipw_affiliate_id'] = esc_attr($post_options['ipw_affiliate_id']);
			$ipw_options['ipw_show_skorinc'] = esc_attr($post_options['ipw_show_skorinc']);
			
			update_option('ipw_options', $ipw_options);
		
			add_action( 'admin_notices', 'ipw_notice_saved');
	
			if ( !count( get_settings_errors() ) )
				add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
			set_transient('settings_errors', get_settings_errors(), 30);
		
			$goback = add_query_arg( 'updated', 'true',  wp_get_referer() );
			wp_redirect( $goback );
			exit;
	
	
		break;
	
		case 'close_message':
			$ipw_options = get_option('ipw_options');
			$ipw_options['ipw_message_show'] = '0';
			update_option('ipw_options', esc_attr($ipw_options));
		break;
	
	
		default:
			return false;
		break;
	}
}



?>