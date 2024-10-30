<?php
/*
Plugin Name: iTunes Preview Widget
Plugin URI: http://noblegiant.com/itunes
Description: Embeds an interactive iTunes Preview for an artist as a sidebar widget. See <code><a href="http://noblegiant.com/itunes" target="_blank">http://noblegiant.com/itunes</a></code> for a preview.
Author: Jordan Andree 
Version: 1.3.1
Author URI: http://noblegiant.com
*/

// actions 
add_action('admin_init', 'ipw_scripts');
add_action( 'widgets_init', 'Itunes_widget_func' );
add_action('wp_enqueue_scripts', 'ipw_scripts');

// registrations
register_activation_hook( __FILE__, 'ipw_activate' );

function ipw_activate(){
	$ver = get_option('ipw_version');
	update_option('ipw_version', '1.3.1');
}

function ipw_scripts(){
	wp_enqueue_style('itunes', WP_PLUGIN_URL.'/itunes-preview-widget/css/ipw_styles.css');
	wp_enqueue_script('itunes', WP_PLUGIN_URL.'/itunes-preview-widget/js/ipw_itunes.js');		
	wp_enqueue_script('base64', WP_PLUGIN_URL.'/itunes-preview-widget/js/base64.js');		
}

class Itunes_Widget extends WP_Widget {

    function Itunes_Widget() {
        $widget_ops = array( 'classname' => 'itunes_widget' );
        $control_ops = array( 'width' => 350, 'height' => 250 );
        $this->WP_Widget( 'itunes_widget', 'iTunes Widget', $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract($args);

        echo $before_widget;
        
    	$title = apply_filters('widget_title', $instance['title'] );
    	if(!empty($title)) echo $before_title.$title.$after_title;
    	
    	?>
    	<div class="ipw_widget_cont" id="itunes_widget_<?php echo $instance['id']; ?>">
            <div class="ipw_loading_dock"></div>
        </div>

        <script type="text/javascript">
        jQuery(document).ready(function($){
            itunes_widget({ 
                            artist_id: <?php echo $instance['artist_id']; ?>, 
                            count: <?php echo $instance['count']; ?>, 
                            country: "<?php echo $instance['country']; ?>", 
                            affiliate_id: "<?php echo $instance['affiliate_id']; ?>", 
                            base_url:  "<?php echo get_option('siteurl'); ?>",
                            base_id: "<?php echo $args['widget_id']; ?>"
                         });
        })
        </script>
        <?php
    	
    	echo $after_widget;
    	
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        foreach ( array('title', 'artist_id', 'count', 'country', 'affiliate_id') as $val ) {
            $instance[$val] = strip_tags( $new_instance[$val] );
        }
        return $instance;
    }

    function form( $instance ) {
        $defaults = array( 
            'title'         => 'iTunes Previews', 
            'artist_id'     => '', 
            'count'         => 5,
            'country'       => 'US',
            'affiliate_id'  => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <table width="100%">
            <tr>
                <td><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Title"); ?>:</label></td>
                <td width="250px"><input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />   </td>
            </tr>
            <tr>
                <td><label for="<?php echo $this->get_field_id( 'artist_id' ); ?>"><?php _e("Artist ID"); ?>:</label></td>
                <td>
                    <input class="widefat get_id_input" id="<?php echo $this->get_field_id( 'artist_id' ); ?>" name="<?php echo $this->get_field_name( 'artist_id' ); ?>" value="<?php echo $instance['artist_id']; ?>" style="width:75%" /> 
                    <a href="javascript:;" class="ipw_find_id">Find ID</a>
                    <script type="text/javascript">
                        jQuery(document).ready(function($){
                            $('.ipw_find_id').click(function(e){
                               var newwin = window.open( "<?php echo WP_PLUGIN_URL.'/itunes-preview-widget/getid.php'; ?>", "getid", "status = 0, height = 300, width=200" );
                               newwin.focus();
                            });
                        })
                    </script>
                    
                </td> 
            </tr>
            <tr>
                <td><label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e("Album Limit"); ?>:</label></td>
                <td>
                    <select id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>">
                    <?php for($i=1; $i<=25; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php if($instance['count'] == $i) echo 'selected="true"'; ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="<?php echo $this->get_field_id( 'country' ); ?>"><?php _e("Country"); ?>:</label></td>
                <td>
                    <select id="<?php echo $this->get_field_id( 'country' ); ?>" name="<?php echo $this->get_field_name( 'country' ); ?>">
                    <?php foreach(ipw_country_list() as $abbr => $name): ?>
                        <option value="<?php echo $abbr; ?>" <?php if($instance['country'] == $abbr) echo 'selected="true"'; ?>><?php echo $name; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="<?php echo $this->get_field_id( 'affiliate_id' ); ?>"><?php _e("Affiliate ID"); ?>:</label></td>
                <td><input class="widefat" id="<?php echo $this->get_field_id( 'affiliate_id' ); ?>" name="<?php echo $this->get_field_name( 'affiliate_id' ); ?>" value="<?php echo $instance['affiliate_id']; ?>"/></td> 
            </tr>
            
        </table>
        
    <?php 
    }
}

function Itunes_widget_func() {
    register_widget( 'Itunes_Widget' );
}

function ipw_country_list(){
    $countries = array(
      "GB" => "United Kingdom",
      "US" => "United States",
      "AF" => "Afghanistan",
      "AL" => "Albania",
      "DZ" => "Algeria",
      "AS" => "American Samoa",
      "AD" => "Andorra",
      "AO" => "Angola",
      "AI" => "Anguilla",
      "AQ" => "Antarctica",
      "AG" => "Antigua And Barbuda",
      "AR" => "Argentina",
      "AM" => "Armenia",
      "AW" => "Aruba",
      "AU" => "Australia",
      "AT" => "Austria",
      "AZ" => "Azerbaijan",
      "BS" => "Bahamas",
      "BH" => "Bahrain",
      "BD" => "Bangladesh",
      "BB" => "Barbados",
      "BY" => "Belarus",
      "BE" => "Belgium",
      "BZ" => "Belize",
      "BJ" => "Benin",
      "BM" => "Bermuda",
      "BT" => "Bhutan",
      "BO" => "Bolivia",
      "BA" => "Bosnia And Herzegowina",
      "BW" => "Botswana",
      "BV" => "Bouvet Island",
      "BR" => "Brazil",
      "IO" => "British Indian Ocean Territory",
      "BN" => "Brunei Darussalam",
      "BG" => "Bulgaria",
      "BF" => "Burkina Faso",
      "BI" => "Burundi",
      "KH" => "Cambodia",
      "CM" => "Cameroon",
      "CA" => "Canada",
      "CV" => "Cape Verde",
      "KY" => "Cayman Islands",
      "CF" => "Central African Republic",
      "TD" => "Chad",
      "CL" => "Chile",
      "CN" => "China",
      "CX" => "Christmas Island",
      "CC" => "Cocos (Keeling) Islands",
      "CO" => "Colombia",
      "KM" => "Comoros",
      "CG" => "Congo",
      "CD" => "Congo, The Democratic Republic Of The",
      "CK" => "Cook Islands",
      "CR" => "Costa Rica",
      "CI" => "Cote D'Ivoire",
      "HR" => "Croatia (Local Name: Hrvatska)",
      "CU" => "Cuba",
      "CY" => "Cyprus",
      "CZ" => "Czech Republic",
      "DK" => "Denmark",
      "DJ" => "Djibouti",
      "DM" => "Dominica",
      "DO" => "Dominican Republic",
      "TP" => "East Timor",
      "EC" => "Ecuador",
      "EG" => "Egypt",
      "SV" => "El Salvador",
      "GQ" => "Equatorial Guinea",
      "ER" => "Eritrea",
      "EE" => "Estonia",
      "ET" => "Ethiopia",
      "FK" => "Falkland Islands (Malvinas)",
      "FO" => "Faroe Islands",
      "FJ" => "Fiji",
      "FI" => "Finland",
      "FR" => "France",
      "FX" => "France, Metropolitan",
      "GF" => "French Guiana",
      "PF" => "French Polynesia",
      "TF" => "French Southern Territories",
      "GA" => "Gabon",
      "GM" => "Gambia",
      "GE" => "Georgia",
      "DE" => "Germany",
      "GH" => "Ghana",
      "GI" => "Gibraltar",
      "GR" => "Greece",
      "GL" => "Greenland",
      "GD" => "Grenada",
      "GP" => "Guadeloupe",
      "GU" => "Guam",
      "GT" => "Guatemala",
      "GN" => "Guinea",
      "GW" => "Guinea-Bissau",
      "GY" => "Guyana",
      "HT" => "Haiti",
      "HM" => "Heard And Mc Donald Islands",
      "VA" => "Holy See (Vatican City State)",
      "HN" => "Honduras",
      "HK" => "Hong Kong",
      "HU" => "Hungary",
      "IS" => "Iceland",
      "IN" => "India",
      "ID" => "Indonesia",
      "IR" => "Iran (Islamic Republic Of)",
      "IQ" => "Iraq",
      "IE" => "Ireland",
      "IL" => "Israel",
      "IT" => "Italy",
      "JM" => "Jamaica",
      "JP" => "Japan",
      "JO" => "Jordan",
      "KZ" => "Kazakhstan",
      "KE" => "Kenya",
      "KI" => "Kiribati",
      "KP" => "Korea, Democratic People's Republic Of",
      "KR" => "Korea, Republic Of",
      "KW" => "Kuwait",
      "KG" => "Kyrgyzstan",
      "LA" => "Lao People's Democratic Republic",
      "LV" => "Latvia",
      "LB" => "Lebanon",
      "LS" => "Lesotho",
      "LR" => "Liberia",
      "LY" => "Libyan Arab Jamahiriya",
      "LI" => "Liechtenstein",
      "LT" => "Lithuania",
      "LU" => "Luxembourg",
      "MO" => "Macau",
      "MK" => "Macedonia, Former Yugoslav Republic Of",
      "MG" => "Madagascar",
      "MW" => "Malawi",
      "MY" => "Malaysia",
      "MV" => "Maldives",
      "ML" => "Mali",
      "MT" => "Malta",
      "MH" => "Marshall Islands",
      "MQ" => "Martinique",
      "MR" => "Mauritania",
      "MU" => "Mauritius",
      "YT" => "Mayotte",
      "MX" => "Mexico",
      "FM" => "Micronesia, Federated States Of",
      "MD" => "Moldova, Republic Of",
      "MC" => "Monaco",
      "MN" => "Mongolia",
      "MS" => "Montserrat",
      "MA" => "Morocco",
      "MZ" => "Mozambique",
      "MM" => "Myanmar",
      "NA" => "Namibia",
      "NR" => "Nauru",
      "NP" => "Nepal",
      "NL" => "Netherlands",
      "AN" => "Netherlands Antilles",
      "NC" => "New Caledonia",
      "NZ" => "New Zealand",
      "NI" => "Nicaragua",
      "NE" => "Niger",
      "NG" => "Nigeria",
      "NU" => "Niue",
      "NF" => "Norfolk Island",
      "MP" => "Northern Mariana Islands",
      "NO" => "Norway",
      "OM" => "Oman",
      "PK" => "Pakistan",
      "PW" => "Palau",
      "PA" => "Panama",
      "PG" => "Papua New Guinea",
      "PY" => "Paraguay",
      "PE" => "Peru",
      "PH" => "Philippines",
      "PN" => "Pitcairn",
      "PL" => "Poland",
      "PT" => "Portugal",
      "PR" => "Puerto Rico",
      "QA" => "Qatar",
      "RE" => "Reunion",
      "RO" => "Romania",
      "RU" => "Russian Federation",
      "RW" => "Rwanda",
      "KN" => "Saint Kitts And Nevis",
      "LC" => "Saint Lucia",
      "VC" => "Saint Vincent And The Grenadines",
      "WS" => "Samoa",
      "SM" => "San Marino",
      "ST" => "Sao Tome And Principe",
      "SA" => "Saudi Arabia",
      "SN" => "Senegal",
      "SC" => "Seychelles",
      "SL" => "Sierra Leone",
      "SG" => "Singapore",
      "SK" => "Slovakia (Slovak Republic)",
      "SI" => "Slovenia",
      "SB" => "Solomon Islands",
      "SO" => "Somalia",
      "ZA" => "South Africa",
      "GS" => "South Georgia, South Sandwich Islands",
      "ES" => "Spain",
      "LK" => "Sri Lanka",
      "SH" => "St. Helena",
      "PM" => "St. Pierre And Miquelon",
      "SD" => "Sudan",
      "SR" => "Suriname",
      "SJ" => "Svalbard And Jan Mayen Islands",
      "SZ" => "Swaziland",
      "SE" => "Sweden",
      "CH" => "Switzerland",
      "SY" => "Syrian Arab Republic",
      "TW" => "Taiwan",
      "TJ" => "Tajikistan",
      "TZ" => "Tanzania, United Republic Of",
      "TH" => "Thailand",
      "TG" => "Togo",
      "TK" => "Tokelau",
      "TO" => "Tonga",
      "TT" => "Trinidad And Tobago",
      "TN" => "Tunisia",
      "TR" => "Turkey",
      "TM" => "Turkmenistan",
      "TC" => "Turks And Caicos Islands",
      "TV" => "Tuvalu",
      "UG" => "Uganda",
      "UA" => "Ukraine",
      "AE" => "United Arab Emirates",
      "UM" => "United States Minor Outlying Islands",
      "UY" => "Uruguay",
      "UZ" => "Uzbekistan",
      "VU" => "Vanuatu",
      "VE" => "Venezuela",
      "VN" => "Viet Nam",
      "VG" => "Virgin Islands (British)",
      "VI" => "Virgin Islands (U.S.)",
      "WF" => "Wallis And Futuna Islands",
      "EH" => "Western Sahara",
      "YE" => "Yemen",
      "YU" => "Yugoslavia",
      "ZM" => "Zambia",
      "ZW" => "Zimbabwe"
    );
    
    return $countries;
}




?>