var albumCount, base_url;

function itunes_widget( params ){
	var self = jQuery('#'+params.base_id);

	jQuery.getJSON('http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/ws/wsLookup?callback=?&jsoncallback=?&id='+params.artist_id+'&entity=album&country='+params.country, function( resp ){
		self.find('.ipw_loading_dock').html('<ul class="ipw_albums widget_albums"></ul>');
		for(var i=0; i <= params.count; i++){
	
			var album = resp.results[i];
			if(album != undefined && album != null && album.wrapperType == 'collection'){		
				var htmlstring = '<li class="ipw_clearfix"><a href="'+album.collectionViewUrl+'"><img src="'+album.artworkUrl100.replace('100x100', '225x225')+'" width="25px" align="left" />'+album.collectionName+'</a></li>';
				self.find('.ipw_loading_dock .ipw_albums').append(htmlstring);
				//jQuery('.ipw_loading_dock .ipw_albums li').hide();
			}			
		}

		self.find('.ipw_loading_dock .ipw_albums li a').click(function(e){		
			e.preventDefault();	
			params.preview_url = jQuery(this).attr('href');
			itunes_show_album( params );
		});
	});
}

function itunes_show_album( params ){
	var self = jQuery('#'+params.base_id);

	self.find('.ipw_loading_dock').html('');
	
	var iframe_url = params.base_url+'/wp-content/plugins/itunes-preview-widget/itunes-widget-iframe.php?url='+Base64.encode(params.preview_url);
	if(params.affiliate_id != ''){
		iframe_url = iframe_url+'&affiliate_id='+params.affiliate_id;
	}			
	
	self.find('.ipw_loading_dock')
		.append('<span class="iframe_copy"><iframe src="'+iframe_url+'" frameborder="0" id="frame1" scrolling="no" width="100%" height="400px" style="min-width:255px"></iframe></span><br />');
		
	if(params.count > 1){
		self.find('.ipw_loading_dock').append('<a href="javascript:;" class="widget_back">Back to Albums</a>');
		self.find('.widget_back').click(function(e){
			itunes_widget( params );
			e.preventDefault();
		});
	}
	
	self.find('.ipw_loading_dock').removeClass('ipw_loading');
		
	setTimeout(function(){
		self.find('.ipw_loading_dock').fadeIn();
	}, 500);		
}
		
function id_search( term ){
	var parent = window.opener.document;

	var i = 0;
	var count = 15;
	var params = { 
		media: 'music',
		entity: 'musicArtist',
		version: 2,
		term: term
	};
	
	jQuery.getJSON('http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?callback=?&jsoncallback=?', params, function( data ){
		jQuery('.ajax_content').html(' ');
 		
		
		for(i in data.results){
			var artist = data.results[i];
		
			if(artist != undefined && artist != null){
			
				content = '';
				content += '<tr>';
				content += '<td><a href="javascript:;" class="artist_result" rel="'+artist.artistId+'">'+artist.artistName+'</a></td>';
				content += '<td>ID: <input type="text" size="20" readonly="true" value="'+artist.artistId+'" onclick="this.select()" /></td>';
				content += '</tr>';
			
				jQuery('.ajax_content').append(content);
			
			}
		}
		
		$('.artist_result').click(function(){
			var _id = $(this).attr('rel');
			
			$(parent).find('.get_id_input').val(_id);
			window.close();
		})

	})
}

