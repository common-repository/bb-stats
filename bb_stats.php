<?php

/*

Plugin Name: Bad Behavior Stats
Version: 1.6
Plugin URI: http://www.ajaydsouza.com/wordpress/plugins/bad-behavior-stats-plugin/
Description: Display number of blocked access attempts by the <a href="http://www.ioerror.us/software/bad-behavior/" target="_blank">Bad Behavior</a> Plugin. (Original mysql function by <a href="http://txfx.net/2005/04/29/bad-bad-spambots/#comment-6346" target="_blank">Mark Jaquith</a>). Go to <a href="options-general.php?page=bb_stats.php">Options &gt;&gt; BBStats</a> to configure.
Author: Ajay D'Souza
Author URI: http://www.ajaydsouza.com/

*/

define('BB_COUNT', '|%count%|');
define('BB_NO_OF_DAYS', '|%days%|');
define('BB_URL', '|%bburl%|');
define('BBStats_URL', '|%bbstatsurl%|');

/**********************************************************************
*					Main Function						*
*********************************************************************/
function bb_block_count() {
	$str = bb_block_count_noecho();
	echo $str;
}

function bb_block_count_noecho()
{

	$bb_no_of_days		=	get_option('bbs_bb_no_of_days');	// Set to that of $wp_bb_logging_duration in bad-behavior-wordpress.php
	$only403			=	get_option('bbs_only403');			// Count only 403 & 412 blocked requests
	$add_footer			=	get_option('bbs_add_footer');		// Automatically add stats to wp_footer?
	$wp_dash			=	get_option('bbs_wp_dash');			// Enable widget for WP-Dash
	$str				=	get_option('bbs_str');				// Text to be displayed

	global $wpdb;

if (function_exists('wp_bb_timer_display'))		// Check if Bad Behavior is installed
{
	if ($only403)
		$bb_count = $wpdb->get_var("SELECT COUNT(`id`) FROM `" . WP_BB_LOG . "` WHERE `http_response` = '403' OR `http_response` = '412'");
	else
		$bb_count = $wpdb->get_var("SELECT COUNT(`id`) FROM `" . WP_BB_LOG . "`");

	$str = stripslashes($str);
	$str = preg_replace(BB_COUNT,$bb_count,$str);
	$str = preg_replace(BB_NO_OF_DAYS,$bb_no_of_days,$str);
	$str = preg_replace(BB_URL,'<a href="http://www.ioerror.us/software/bad-behavior/">Bad Behavior</a>',$str);
	$str = preg_replace(BBStats_URL,'<a href="http://www.ajaydsouza.com/wordpress/plugins/bad-behavior-stats-plugin/">BBStats</a>',$str);

	$bb_str = $str;

	return $str;

}
else
{
	echo "<br /><strong>Please install / activate <a href=\"http://www.ioerror.us/software/bad-behavior/\">Bad Behavior</a></strong>.";
}

}

/* This function adds an Options page in WP Admin */
function bb_stats_options() {

	if(!get_option(bbs_str))
	{
		add_option('bbs_bb_no_of_days', 7, 'No. of Days');
		add_option('bbs_only403', true, 'Count only 403 & 412 access attempts');
		add_option('bbs_add_footer', false, 'Add stats to footer');
		add_option('bbs_wp_dash', false, 'Enable Widget for WP-Dash');
		$str = "<br />%bbstatsurl% report: %bburl% has blocked <strong>%count%</strong> access attempts in the last %days% days.";
		add_option('bbs_str', $str, 'Add stats to footer');

	}

	if($_POST['bb_stats_save']){

		update_option('bbs_bb_no_of_days', $_POST['bb_no_of_days']);
		update_option('bbs_only403', $_POST['only403']);
		update_option('bbs_add_footer', $_POST['add_footer']);
		update_option('bbs_wp_dash', $_POST['wp_dash']);
		$str = stripslashes($_POST['str']);
		update_option('bbs_str', $str);

		echo '<div class="updated"><p>Bad Behavior Stats options saved successfully.</p></div>';
	}

	if($_POST['bb_stats_default']){

		update_option('bbs_bb_no_of_days', 7, 'No. of Days');
		update_option('bbs_only403', true, 'Count only 403 & 412 access attempts');
		update_option('bbs_add_footer', false, 'Add stats to footer');
		update_option('bbs_wp_dash', false, 'Enable Widget for WP-Dash');
		$str = "<br />%bbstatsurl% report: %bburl% has blocked <strong>%count%</strong> access attempts in the last %days% days.";
		update_option('bbs_str', $str, 'Add stats to footer');

		// Delete options for older versions of program
		if (get_option('bbs_returns')) delete_option('bbs_returns');
		if (get_option('bbs_count_only')) delete_option('bbs_count_only');

		echo '<div class="updated"><p>Bad Behavior Stats options set to Default.</p></div>';
	}

	?>

<div class="wrap">
  <h2>Bad Behavior Stats Options</h2>
  <form method="post" id="bb_stats_options">
    <fieldset class="options">
    <legend>Display Options</legend>
    <table width="100%" cellspacing="2" cellpadding="5">
      <tr style="background: #ccc;">
        <th>Variable Name</th>
        <th>Value</th>
        <th>Description</th>
      </tr>
      <tr>
        <td><var>$bb_no_of_days</var></td>
        <td><input name="bb_no_of_days" type="text" id="bb_no_of_days" value="<?php echo get_option('bbs_bb_no_of_days') ?>" size="2" /></td>
        <td>Set to that of <var>$wp_bb_logging_duration</var> in <code>bad-behavior-wordpress.php</code></td>
      </tr>
      <tr>
        <td><var>$only403</var></td>
        <td><input name="only403" type="checkbox" id="only403" value="only403" <?php if(get_option('bbs_only403')) { echo('checked="checked"'); } ?> /></td>
        <td>Only 403 & 412 blocked attempts are counted </td>
      </tr>
      <tr>
        <td><var>$add_footer</var></td>
        <td><input name="add_footer" type="checkbox" id="add_footer" value="add_footer" <?php if(get_option('bbs_add_footer')) { echo('checked="checked"'); } ?> /></td>
        <td>Automatically add stats to <code>wp_footer()</code> if the template contains the code of it.</td>
      </tr>
      <tr>
        <td><var>$wp_dash</var></td>
        <td><input name="wp_dash" type="checkbox" id="wp_dash" value="wp_dash" <?php if(get_option('bbs_wp_dash')) { echo('checked="checked"'); } ?> /></td>
        <td>Enable widget for <a href="http://somethingunpredictable.com/wp-dash/" target="_blank">WP-Dash</a>.</td>
      </tr>
      <tr style="background: #ccc;">
        <td colspan="3"><strong>Text to be displayed:</strong></td>
      </tr>
      <tr>
        <td colspan="3"><textarea name="str" cols="100" rows="3" id="str" style="width:90%;"><?php echo stripslashes(get_option('bbs_str')) ?></textarea></td>
      </tr>
      <tr>
        <td colspan="3">Use <em>%count%</em> to display the number of block attempts.<br />
          Use <em>%days%</em> to display the number of days (<var>$bb_no_of_days</var>).<br />
          Use <em>%bburl%</em> to display a link to Bad Behavior page.<br />
          Use <em>%bbstatsurl%</em> to display a link to Bad Behavior Stats page.</td>
      </tr>
      <tr>
        <td><input type="submit" name="bb_stats_save" value="Update Options" />
        </td>
        <td><input name="bb_stats_reset" type="reset" id="bb_stats_reset" value="Reset Form" /></td>
        <td align="right"><input name="bb_stats_default" type="submit" id="bb_stats_default" value="Default Options" onclick="if (!confirm('Do you want to set Bad Behavior Stats options to Default?')) return false;" /></td>
      </tr>
    </table>
    </fieldset>
  </form>
</div>

	<?php
}

function bb_stats_adminmenu(){
	if (function_exists('add_options_page')) {
		add_options_page('Bad Behavior Stats Options', 'BB Stats', 9, 'bb_stats.php', 'bb_stats_options');
		}
}

/**********************************************************************
*				Begin WP-Dash Widget						*
*********************************************************************/

function bb_stats_css($ID) {
	return default_widget_css($ID) . "
	#widget$ID { width: 200px; height: 100px; }";
}

function bb_stats_content($ID) {
	$str = bb_block_count_noecho();
	return $str;
}

function make_bb_stats_available() {
	if((function_exists('make_widget_available'))&&(get_option('bbs_wp_dash')))
		make_widget_available('Bad Behavior Stats', 'Shows the blocked access attempts in the last seven days.', 'bb_stats_');
}
add_action('init', 'make_bb_stats_available');
/**********************************************************************
*				End WP-Dash Widget						*
*********************************************************************/


add_action('admin_menu','bb_stats_adminmenu',1);

if (get_option('bbs_add_footer'))		// Add stats to wp_footer
	add_action('wp_footer', 'bb_block_count', 3);

?>