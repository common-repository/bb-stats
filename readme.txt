=== Bad Behavior Stats ===
Tags: Bad Behavior, stats, anti-spam
Contributors: Ajay D'Souza

Display number of blocked access attempts by the <a href="http://www.ioerror.us/software/bad-behavior/" target="_blank">Bad Behavior</a> Plugin. (Original mysql function by <a href="http://txfx.net/2005/04/29/bad-bad-spambots/#comment-6346" target="_blank">Mark Jaquith</a>).


== Installation ==

1. Upload to your plugins folder, usually `wp-content/plugins/`
2. Activate the plugin on the plugin screen
3. Add <?php if (function_exists('bb_block_count')) bb_block_count(); ?> where you want to display the stats.


== Frequently Asked Questions ==

= What are the requirements for this plugin? =

1. WordPress 1.5 or above 
2. Bad Behavior Plugin 

= Example of the output? =

	Bad Behavior has blocked 13 access attempts in the last 7 days.

= Can I customize what is displayed? =

Yes, you can. Just goto "Options > BB Stats" and set the following:
1. $bb_no_of_days - the number of days you set the $wp_bb_logging_duration variable in bad-behavior-wordpress.php. Default of both variables is 7. 
2. $only403 - Count only 403 errors. Default is checked. 
3. $add_footer - Automatically add to wp_footer()
4. $wp_dash - Enable widget for WP-Dash
5. $str - The text to be displayed on your site

For more information, please visit http://www.ajaydsouza.com/wordpress/plugins/bad-behavior-stats-plugin/#customizing

= Do I really need this plugin? =
Not really, but Bad Behavior is an excellent plugin that blocks all kinds of incorrect access attempts to your website stopping a good amount of spam. This information is stored in a database. So, why not display how many access attempts have been stopped.
For one you have a ready reference to see how badly your blog is being hit and two wouldn't you love to say that my blog is so popular that even spammers don't want to leave it alone ;)

