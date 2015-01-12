<?php
/*
Plugin Name: Machform Shortcode
Plugin URI: http://www.laymance.com/products/wordpress-plugins/machform-shortcode/
Description: Creates a shortcode for inserting Machform forms into your posts or pages (only tested with MachForm 3.5+)
Version: 1.1
Author: Laymance Technologies
Author URI: http://www.laymance.com
License: GPL2

Copyright 2013  Laymance Technologies  (email : support@laymance.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$mfsc_option_name = 'machform_shortcode_domain';

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
        echo 'WOW! This isn\'t supposed to happen! I can\'t be called directly dude!';
        exit;
}


$machform_domain = get_option($mfsc_option_name, '--');

if ( $machform_domain == '' or $machform_domain == '--' ){
	// Show an alert for them to configure the plugin
	add_action( 'admin_notices', 'machform_sc_admin_notices' );
} else {
	// We have the config data we need, make our shortcode
	if ( strpos($machform_domain, 'http') === false ){
		$machform_domain = 'http://' . $machform_domain;
	}
	
	if ( substr($machform_domain, -1) != '/' ) $machform_domain .= '/';
	
	// Create our short code
	add_shortcode( 'machform', 'machform_shortcode' );
}

// Create the menu entry
add_action('admin_menu', 'machform_sc_plugin_menu');

function machform_shortcode( $atts ){
	global $machform_domain;
	
	// If no ID is given, return a blank string
	$atts['id'] = intval($atts['id']);
	if ( $atts['id'] < 1 ) return '';
	
	// If no "type" is given, default to javascript embed
	if ( $atts['type'] == '' ) $atts['type'] = 'js';
	
	// Support URL Parameters
	$additional_parms = '';
	$skip_keys = array('id','type','height');
	
	foreach( $atts as $attkey=>$attval ){
    	$attkey = trim($attkey);
    	
    	// Skip known keys that are used for other functions
    	if ( in_array($attkey, $skip_keys) ) continue;
    	
    	// Real URL parameter keys from Machforms will not have a space in them,
    	// so skip over them... the keys should be in the form of element_1_1, 
    	// element_1_2, etc.
    	if ( strpos($attkey, ' ') !== false ) continue;
    	
    	$additional_parms .= '&' . $attkey . '=' . urlencode($attval);
	}
	
	
	$atts['height'] = intval($atts['height']);
	if ( intval($atts['height']) < 1 ) $atts['height'] = 800;

	if ( $atts['type'] == 'js' ){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'machform-postmessage', $machform_domain . 'js/jquery.ba-postmessage.min.js' );
		wp_enqueue_script( 'machform-loader', $machform_domain . 'js/machform_loader.js', false, false, true );

        $content = '<script type="text/javascript">var __machform_url = \'' . $machform_domain . 'embed.php?id=' . $atts['id'] . $additional_parms . '\'; var __machform_height = ' . $atts['height'] . ';</script>';
        $content .= '<div id="mf_placeholder"></div>';
				
		return $content;
	} elseif ( $atts['type'] == 'iframe' ){
    	$content = '<iframe onload="javascript:parent.scrollTo(0,0);" height="' . $atts['height'] . '" allowTransparency="true" frameborder="0" scrolling="no" style="width:100%;border:none" ';
    	$content .= 'src="' . $machform_domain . 'embed.php?id=' . $atts['id'] . $additional_parms . '"><a href="' . $machform_domain . 'view.php?id=' . $atts['id'] . $additional_parms . '">Click here to complete the form.</a></iframe>';
		
		return $content;
	} else {
		// Don't know what they are requesting, return a blank string
		return '';
	}
}

function machform_sc_plugin_menu() {
	$hook_suffix = add_options_page('Machform Shortcode Options', 'Machform Shortcode', 'manage_options', 'machform_shortcodes', 'machform_sc_options');
}

function machform_sc_admin_notices() {
	echo "<div id='notice' class='updated fade'><p>The Machform Shortcodes plugin has not been configured yet. It must be configured before it can be used.</p></div>\n";
}

function machform_sc_options(){
	global $mfsc_option_name, $machform_domain;
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	// See if a form has been submitted, if so, process it
	if ( $_POST['mfsc_submit'] == 1 and $_POST['machform_url'] != '' ){
		if ( strpos($_POST['machform_url'], 'http') === false ){
			$_POST['machform_url'] = 'http://' . $_POST['machform_url'];
		}
		
		if ( substr($_POST['machform_url'], -1) != '/' ) $_POST['machform_url'] .= '/';
		
		if ( get_option($mfsc_option_name) !== false ){
			update_option($mfsc_option_name, $_POST['machform_url']);
		} else {
			add_option($mfsc_option_name, $_POST['machform_url'], null, 'yes');
		}
		
		$machform_domain = $_POST['machform_url'];
		
		$alert = "<div id='notice' class='updated fade'><p>Your configuration options have been saved!</p></div><br />";
	} else {
		$alert = '';
	}

	ob_start();
	?>
<div class="wrap" id="contain">
<h2>Machform Shortcodes</h2>
The Machform Shortcodes plugin creates the shortcodes necessary for you to insert javascript or iframe forms created by Machform into your posts or pages! Configuration is simple,<br />
we simply need the URL for your Machforms installation. If you are not using the excellent forms application from App Nitro, you should check it out <a href="http://www.appnitro.com" target="_blank">here</a>!<br /><br />
<?php echo $alert; ?>
<form method="post">
	<input type="hidden" name="mfsc_submit" value="1">
	<table border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td valign="middle"><strong>Machform URL/Location</strong></td>
		<td valign="middle"><input type="text" name="machform_url" value="<?php echo $machform_domain; ?>" size="45"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">Example: http://forms.mydomain.com/   OR   https://www.mydomain.com/machforms/</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value=" Save Configuration "></td>
	</tr>
	</table>
</form>
<br />
<br />
<h2>How do you use the Machform Shortcode?</h2>
Using the short codes is easy!  The shortcode supports both the javascript method as well as the iframe method of Machform.  Follow these simple steps in order to use Machforms on your site:<br />
<br />
<strong>Step 1:</strong> In Machforms, click on the "Code" option for the form you wish to embed in order to see the Machforms embed code.<br />
<br />
<strong>Step 2:</strong> In the embed code, make note of the "height" and the "id" of your form, you will use that in your shortcode!<br />
<br />
<strong>Step 3:</strong> In your content where you want the form to appear, insert a shortcode using the following format:&nbsp;&nbsp; [machform type=(<em>"js" or "iframe"</em>) id=(<em>ID #</em>) height=(<em>height #</em>)]<br />
<br />
* The type option must be "js" for javascript, or "iframe" for the iFrame method. If the type is not specified, it defaults to the javascript method.<br />
* The id option is the ID number of the form from your embed code. <span style="color:maroon; font-weight:bold;">The ID is required.</span><br />
* The height option is the height size of the form from your embed code. If a height is not specified, it defaults to 800.<br />
<br />
An example of a finished shortcode:  [machform type=js id=1593 height=703]<br />
<br />
<br />
<strong>URL Parameters</strong><br />
The plugin now supports URL Parameters.  You can read more about Machform's implementation of URL Parameters by visiting <a href="http://www.appnitro.com/doc-url-parameters" rel="nofollow">their website here</a>.<br />
<br />
To use URL parameters with your shortcodes, just add the additional parameters inside of the shortcode like the following example:<br />
<br />
[machform type=js id=1593 height=703 element_1_1="Field Text Here" element_1_2="Field Text Here"]<br />
<br />
<br />

<strong>Step 4:</strong> You are done, save your content and your form should appear!<br />
</div>
	<?php
	$content = ob_get_clean();
	
	echo $content;	
}

?>