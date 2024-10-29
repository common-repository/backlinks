<?php
/*
Plugin Name: Backlinks
Plugin URI: http://dbzer0.com/the-penguin-migration/backlinks
Description: A plugin to show the google blog search backlinks for the current page.
Author: Divided By Zer0
Version: 1.1.2
Author URI: http://dbzer0.com/
*/

add_action('template_redirect', 'addeffects'); // Add the scriptaculous-effects to the header. 
add_action('plugins_loaded', 'add_backlinks_widget'); // Activate our widget.
$backlinks_options = get_option('backlinks');
add_action('admin_menu', 'backlinks_options');

//set initial defaults
if ($backlinks_options['bl_title'] == null) $backlinks_options['bl_title'] = 'Blogs linking to this article';
if ($backlinks_options['bl_date'] == null) $backlinks_options['bl_date'] = 'j F Y';
if ($backlinks_options['bl_number_of_backlinks'] == null) $backlinks_options['bl_number_of_backlinks'] = 0;
if ($backlinks_options['bl_more_text'] == null) $backlinks_options['bl_more_text'] = '(See more&hellip;)';
if ($backlinks_options['bl_hide_when_empty'] == null) $backlinks_options['bl_hide_when_empty'] = 0;

//create options page
function backlinks_options() {
   if (function_exists('add_options_page')) {
   	add_options_page('Backlinks', 'Backlinks', 'manage_options', 'backlinks/options.php');
    }
}

// The function that loads the scriptaculous effect in the page.
function addeffects() {
            if (function_exists('wp_enqueue_script')) wp_enqueue_script('scriptaculous-effects');
}

// This function grabs the current post's url and makes it into a google blog search result with the number of results as a variable. 
// The first argument is the number of items to return for the feed and the second lets the function return feed results only the string 'feed' is passed.
function prepare_url($result_nr=null, $type='') {
	if ($result_nr != null) {
		$google_url = 'http://blogsearch.google.com/blogsearch_feeds?q=link:'. get_permalink() .'&oe=utf-8&um=1&ie=utf-8&num='. $result_nr .'&output=atom';
	}
	else {
		$google_url = 'http://blogsearch.google.com/blogsearch?ie=UTF-8&q='. get_permalink();
	}	
return($google_url);
}

// A fuction that prepares the simplepie to parse the google blog search feed and returns it as a variable $feed
function prepare_backlinks() {
$google_feed = prepare_url(100, 'feed');
$feed = new SimplePie(); // Create a new simplepie object
$feed->set_feed_url($google_feed); // Tell it to use our newly formatted url
$feed->enable_order_by_date(true); // Self Explanatory
$feed->enable_cache(true); // Self Explanatory
$feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/backlinks/cache'); //Save our rss cache in the backlinks plugin directory under /cache
$feed->init(); // Initialize the feed with simplepie actions
return $feed; // Return the result to the function that called it.
}

//This function returns the scriptaculous effect the user selected when called.
function backlinks_effect() {
	global $backlinks_options;
switch ($backlinks_options['backlinks_effect']) {
	case 1:
	  $effect='slide';
	  break;
	case 2:
	  $effect='blind';
	  break;
	case 3:
	  $effect='appear';
          break;
	default:
	  $effect='slide';
}
return ($effect);
}

//The Main function for use in templates
function backlinks() {
	global $backlinks_options;

if (!class_exists('SimplePie')) //Checks if the Simplepie Core plugin is installed and if not stops further calls which would b0rk the Universe.
{ 
echo '<p><b>Error:</b> Backlinks requires  <a href="http://wordpress.org/extend/plugins/simplepie-core">SimplePie Core</a> to be installed before use. Exiting gracefully...</p>';
return;
}

$feed = prepare_backlinks(); // Get the simplepie object for use

if ($backlinks_options['bl_hide_when_empty'] == 1 && $feed->get_item_quantity() == 0) return; //If the user chose to hide the backlinks when there are none, we escape the call

// We need this kind of echo as the onclick effects requires both kind of quotes. Within we include an anchor so that people can navigate directly to the results. 
// The onclick effect uses the blind effect and the toggle function allows it to be opened and closed.
echo '<a class="backlinks_title" name="backlinks" href="#backlinks" onclick="Effect.toggle';
echo "('backlinks_slide', '". backlinks_effect() ."');";
echo ' return false;">';
echo $feed->get_item_quantity() .' ' . $backlinks_options['bl_title'] .'</a> &raquo; '. ( ($backlinks_options['bl_more'] == 1)?'<a class="morelink" href="'. prepare_url() .'" target="_blank">'. $backlinks_options['bl_more_text'] .'</a>':''); //The actual text link. It also returns the total number of results found.

echo '<div id="backlinks_slide" ' . ( ($backlinks_options['bl_visible'] != 1)?'style="display:none;"':'') . '><ul class="dates">'; // This div starts hidden and is what the scriptaculous effect uses. The id tells it that anything in this div should be revealed/concealed. 
// We also start an unorganised list with the dates class which is common in themes.

foreach ($feed->get_items(0,$backlinks_options['bl_number_of_backlinks']) as $item) { // We start our iterations in the items in the feed. We go through them all.
// For each iteration we list one item along with it's date. The short content is used as a title
echo '<li class="backlinks"><span class="date">' . $item->get_date($backlinks_options['bl_date']) . '</span><a href="' . $item->get_permalink() . '" title="' . $item->get_description() . '" ' . ( ($backlinks_options['bl_nofollow'] == 1)?'rel="nofollow"':'') . '>'. $item->get_title() .'</a></li>';
} 
echo '</ul><br/>
<small>Links found through <a href="http://dbzer0.com/the-penguin-migration/backlinks">Backlinks</a></small>
</div>'; // Closing our list and div. Leave the link you want to make me happy :)
}

// The widget. It basically does the same function as above but is not calling that directly.
function add_backlinks_widget()
{

	// First check if all is OK for widgets
	if (!function_exists('register_sidebar_widget'))
	  return;

	function backlinks_widget($args) {
		global $backlinks_options;

	if (!class_exists('SimplePie'))
	{ 
		echo '<p><b>Error:</b> Backlinks requires  <a href="http://wordpress.org/extend/plugins/simplepie-core">SimplePie Core</a> to be installed before use. Exiting gracefully...</p>';
		return;
	}
		extract($args);
        	$feed = prepare_backlinks(); 
		if ($backlinks_options['bl_hide_when_empty'] == 1 && $feed->get_item_quantity() == 0) return;
		$options = get_option('backlinks_widget');

		echo $before_widget;

		echo $before_title . ($options['nr_checkbox']!=null?$feed->get_item_quantity():'') . ' ' . $options['title'] . $after_title;

		echo '<ul class="dates">';
		foreach ($feed->get_items(0,$options['number']) as $item) {
        	  echo '<li class="backlinks"><span class="date">' . $item->get_date('j/m/y') . '</span><a href="' . $item->get_permalink() . '" title="' . $item->get_description() . '" ' . ( ($backlinks_options['bl_nofollow'] == 1)?'rel="nofollow"':'') . '>'. $item->get_title() .'</a></li>'; 
        	}
        	echo '</ul>';

		echo $after_widget;
	}


	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_backlinks_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('backlinks_widget');
		if ( !is_array($options) )
			$options = array('title'=>'', 'number'=>'5', 'nr_checkbox'=>'1');
		if ( $_POST['backlinks-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['backlinks-title']));
			$options['number'] = strip_tags(stripslashes($_POST['backlinks-number']));
			$options['nr_checkbox'] = $_POST['backlinks-title-nr-chk'];
			update_option('backlinks_widget', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$number = htmlspecialchars($options['number'], ENT_QUOTES);
		$nr_checkbox = $options['nr_number'];
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="backlinks-title">' . __('Title:') . ' <input style="width: 200px;" id="backlinks-title" name="backlinks-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="backlinks-number">' . __('Number of results:') . ' <input style="width: 40px;" id="backlinks-number" name="backlinks-number" type="text" value="'.$number.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="backlinks-title-nr-chk">' . __('Do you want the number of backlinks prepended to the title? ') . ' <input id="backlinks-title-nr-chk" name="backlinks-title-nr-chk" type="checkbox" value="1" ' . ($nr_checkbox!= null?'checked="checked"':'') . '/></label></p>';
		echo '<input type="hidden" id="backlinks-submit" name="backlinks-submit" value="1" />';
	}


	register_sidebar_widget('Backlinks (Experimental)', 'backlinks_widget');
	register_widget_control('Backlinks (Experimental)', 'widget_backlinks_control', 250, 100);

}


//This function defines the predefined classes for the items
function add_bl_stylesheet() {
   global $backlinks_options;

	if (!$backlinks_options['bl_ownstylesheet']) {
		echo <<<END
			<style type="text/css">
			a.backlinks_title{
				font-style:bold;
				font-size:150%
			}
			a.backlinks_title:hover{
				text-decoration:underline;
			}
			a.morelink{
				font-style:italic;
				font-size:75%
				color:#888;
				text-decoration:none;
			}
			ul.dates{
				list-style-type:none;
				margin:1.5em 0 2em 0;
				border-top:1px solid #3D3D3D;
			}
			ul.dates .date{
				color:#858585;
				padding:0 1.5em 0 0;
			}
			</style>
END;
	}
}
add_action('wp_head', 'add_bl_stylesheet');
?>