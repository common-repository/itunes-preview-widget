<?php $ipw_options = get_option('ipw_options'); ?>

<div class="wrap ipw_wrap">
<h2>iTunes Preview Widget</h2>

<?php if($ipw_options['ipw_message_show'] == '1'): ?>
	
	<div id="setting-error-settings_updated" class="updated settings-error ipw_top"> 
		<a href="javascript:;" class="ipw_close" title="Close">x</a>
		
		<p>
			Feel free to shoot us a comment or question at our blog <a href="http://skorinc.com/2010/12/itunes-preview-widget-for-wordpress/" target="_blank">here</a><br />
			if you like this plugin and are feeling generous, then drop us a rating on the <a href="wordpress.org/extend/plugins/itunes-preview-widget/" target="_blank">Wordpress Plugin page</a>.
			<br /><em>Thanks! - jordan w/ Skörinc</em>
		</p>
	
	</div>

    <script type="text/javascript">
    jQuery(document).ready(function($){
    	$('.ipw_top .ipw_close').click(function(){
    		$.ajax({
    			type:'post',
    			url:'<?php echo WP_PLUGIN_URL ?>/itunes-preview-widget/itunes-widget-ajax.php',
    			data: { action: 'close_message' },
    			success: function(data){
    				// console.log(data);
    				$('.ipw_top').remove();
    			}
    		})
    	});
    })
    </script>
<?php endif; ?>

<form action="<?php echo WP_PLUGIN_URL ?>/itunes-preview-widget/itunes-widget-ajax.php" method="post">

<div class="ipw_box ipw_settings">
	<div class="ipw_inside">
		<h2>Settings</h2>
		<table width="100%">
			<tr>
				<td><label for="ipw_country">Country?</label></td>
				<td>
				<?Php ipw_country_list('ipw_options[ipw_country]', 'ipw_country'); ?>
				<script type="text/javascript">
				jQuery('select#ipw_country').val('<?php echo $ipw_options['ipw_country']; ?>');
				</script>
				</td>
			</tr>
		
			<tr>
				<td><label for="ipw_album_count">How many albums to show?</label></td>
				<td><select name="ipw_options[ipw_album_count]" id="ipw_album_count">
					<?php for($i=1; $i<=15; $i++): ?>
						<option value="<?php echo $i ?>" <?php echo ($i == $ipw_options['ipw_album_count'] ? 'selected="selected"' : '') ?>><?php echo $i ?></option>
					<?php endfor; ?>
				</select>
				</td>
			</tr>
			<tr>
				<td><label for="ipw_show_albums">Show albums first or go straight to first album tracks?<br /><small>Music and Podcasts only</small></label></td>
				<td>
				<select name="ipw_options[ipw_show_albums]" id="ipw_show_albums">
					<option value="1" <?php echo (1 == $ipw_options['ipw_show_albums'] ? 'selected="selected"' : '')?>>Yes</option>
					<option value="0" <?php echo (0 == $ipw_options['ipw_show_albums'] ? 'selected="selected"' : '')?>>No</option>
				</select>
				</td>
			</tr>
			<tr>
				<td><label for="ipw_show_skorinc">Hide credit on widget?</label></td>
				<td><select name="ipw_options[ipw_show_skorinc]" id="ipw_show_skorinc">
						<option value="1" <?php echo (1 == $ipw_options['ipw_show_skorinc'] ? 'selected="selected"' : '')?>>Yes</option>
						<option value="0" <?php echo (0 == $ipw_options['ipw_show_skorinc'] ? 'selected="selected"' : '')?>>No</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="ipw_affiliate_id">iTunes Affiliate ID? <small>US Only</small></label></td>
				<td><input type="text" size="20" name="ipw_options[ipw_affiliate_id]" value="<?php echo $ipw_options['ipw_affiliate_id']; ?>" id="ipw_affiliate_id" /></td>
			</tr>
			<tr>
				<td colspan="2">
				<p class="submit">
					<input type="hidden" name="action" value="update" />
					<input type="submit" class="button-primary" value="Save Changes" />					
				</p>
				</td>
			</tr>
		</table>
	</div>
</div>


<div class="ipw_box ipw_search">
	<div class="ipw_inside">
		
		<h2>Search iTunes</h2>
		<input type="text" id="ipw_search" size="35" />
		<input type="button" value="Go" class="button" id="ipw_go" />
		
		<div class="ipw_cont">
			<ul class="ipw_albums"></ul>
		</div>

		<script type="text/javascript"> loadAdminScripts('<?php echo get_option('siteurl') ?>'); </script>

	</div>
</div>

<?php if($ipw_options['ipw_artist'] != ''): ?>
	<div class="ipw_box ipw_preview">
		<div class="ipw_inside">
			<h2>Widget Preview</h2>
			
			<div class="ipw_widget_container"><div class="ipw_loading_dock"></div></div>
			<script type="text/javascript">
			loadWidget({ item: '<?php echo $ipw_options['ipw_artist'] ?>', media: '<?php echo $ipw_options['ipw_entity'] ?>', album_count: <?php echo $ipw_options['ipw_album_count'] ?>, base_url: '<?php echo get_option('siteurl') ?>' });
			</script>
		</div>
	</div>
<?php endif; ?>

</form>

</div>
