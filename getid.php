<?php
include '../../../wp-config.php';

?>
<!DOCTYPE html>
<html>
      <head>
          <meta http-equiv="Content-type" content="text/html; charset=utf-8">
          <title>Get ID</title>
          <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
          <script src="<?php echo WP_PLUGIN_URL; ?>/itunes-preview-widget/js/ipw_itunes.js" type="text/javascript"></script>
          <script src="<?php echo WP_PLUGIN_URL; ?>/itunes-preview-widget/js/base64.js" type="text/javascript"></script>
          <link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/itunes-preview-widget/css/ipw_styles.css" type="text/css" />
          <style type="text/css" media="screen">
            body {
                font:13px / 1 'Helvetica Neue', Arial, Sans-serif;
            }
          </style>
      </head>
      <body id="getid">
          
          <table width="100%">
              <tr class="search_row">
                  <td>Artist Name</td>
                  <td><input type="text" name="artist_name" id="artist_name" /> <input type="submit" value="search" id="ipw_search" /></td>
              </tr>
          </table>
          <table width="100%" class="ajax_content">
              
          </table>
          <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#ipw_search').click(function(e){
                    e.preventDefault();
                    id_search($('#artist_name').val());
                })
            })
          </script>
      </body>  
</html>


