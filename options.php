<?php

// Functions lovingly ripped from the stumbleupon info link (http://leau.net/su/)

function backlinks_request($name, $default=null)
{
	if (!isset($_REQUEST[$name])) return $default;
	if (get_magic_quotes_gpc()) return backlinks_stripslashes($_REQUEST[$name]);
	else return $_REQUEST[$name];
}

function backlinks_stripslashes($value)
{
	$value = is_array($value) ? array_map('backlinks_stripslashes', $value) : stripslashes($value);
	return $value;
}

function backlinks_field_text($name, $label='', $tips='', $attrs='')
{
  global $options;
  if (strpos($attrs, 'size') === false) $attrs .= 'size="30"';
  echo '<tr><td class="label">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></td>';
  echo '<td><input type="text" ' . $attrs . ' name="options[' . $name . ']" value="' .
    htmlspecialchars($options[$name]) . '"/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}


function backlinks_field_checkbox($name, $label='', $tips='', $attrs='')
{
  global $options;
  echo '<tr><td class="label">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></td>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' .
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function backlinks_field_radio($name, $label='', $tips='', $attrs='', $value='')
{
  global $options;
  echo '<tr><td class="label">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></td>';
  echo '<td><input type="radio" ' . $attrs . ' name="options[' . $name . ']" value="' . $value . '" ' .
    ($options[$name]== $value?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

if (isset($_POST['Submit']))
{
  $options = backlinks_request('options');
  update_option('backlinks', $options);
}
else
{
    $options = get_option('backlinks');
}

function check_simplepie() {
if (class_exists('SimplePie'))
{ echo '<p>SimplePie Core is installed. All systems optimal. Rock on!</p>'; }
else
{ echo '<p><strong>Oh-oh! This plugin relies on the <a href="http://wordpress.org/extend/plugins/simplepie-core">SimplePie Core</a> plugin to enable necessary functionality. Please download, install, and activate it to avoid meltdown and gnashing of teeth.</strong></p>'; }
}

?>

<div class="wrap">
<form method="post">

<? check_simplepie() ?>

<h2>Backlinks Options</h2>

<p> Allows you to see which other blogs have linked to your pages by utilizing google blog searches</p>
<hr />
<h3> Display options </h3>
<p>
	Use this option to modify how your backlinks will be displayed<br />
</p>
<table>
	<? backlinks_field_text('bl_title', 'What is the title you want for your backlinks?', 'e.g. "Blogs linking to this article."'); ?>
	<? backlinks_field_text('bl_date', 'What date format do you want?', 'You can find a list of options <a href="http://uk3.php.net/date">here</a>', 'size="10"'); ?>
	<? backlinks_field_text('bl_number_of_backlinks', 'How many backlinks do you want to show at maximum?', 'Use 0 for unlimited (max 100)','size="3"'); ?>
	<? backlinks_field_checkbox('bl_hide_when_empty', 'Hide the whole thing when no backlinks found?', '');  ?>
</table>
<hr />
<h3> Functionality Opions </h3>
<p>
	Use this options to change the way the backlinks will behave?<br />
</p>
<table>
	<? backlinks_field_checkbox('bl_visible', 'Start with the links visible?', '');  ?>
	<? backlinks_field_checkbox('bl_nofollow', 'Do you want rel="nofollow" appended to links?', '');  ?>
	<? backlinks_field_checkbox('bl_more', 'Show link to full google blogsearch results?', 'Useful if you limit the number of displayed links.');  ?>
	<? backlinks_field_text('bl_more_text', 'Title for "more" link?', 'e.g. "(See more backlinks)"'); ?>
</table>
<hr />
<h3> Effect </h3>
<p>
	What kind of effect do you want you want?<br />
</p>
<table>
	<? backlinks_field_radio('backlinks_effect', '<a href="http://github.com/madrobby/scriptaculous/wikis/effect-slidedown">Slide</a>', '(Default)', '', '1');  ?>
	<? backlinks_field_radio('backlinks_effect', '<a href="http://github.com/madrobby/scriptaculous/wikis/effect-blinddown">Blind</a>', '', '', '2');  ?>
	<? backlinks_field_radio('backlinks_effect', '<a href="http://github.com/madrobby/scriptaculous/wikis/effect-appear">Appear</a>', '', '', '3');  ?>
</table>
<hr />
<h3> Your own stylesheet </h3>
<p>
If you want your own stylesheet to format buttons/text and/or use your own buttons, then
just check this button. If you do, use the following classes in your stylesheet to format:
</p>
<ul>
<li>Use the <b><tt>a.backlinks_title<tt></b>  class to format the title link.</li>
<li>Use the <b><tt>a.morelink</tt></b> class to format the "more results" link.</li>
<li>Use the <b><tt>ul.dates</tt></b> class to format the list</li>
<li>Use the <b></tt>ul.dates .date</tt></b> class to format the date of each link</li>
</ul>
<p>Alternatively you can check this button and not have a stylesheet for a simple look</p>
<table>
	<? backlinks_field_checkbox('bl_ownstylesheet', 'Use your own stylesheet?', '');  ?>
</table>
<p>
	<a href="http://dbzer0.com/backlinks" target="_blank">Main homepage of the plugin</a>.<br />
	<a href="http://dbzer0.com" target="_blank">Author's homepage</a>.<br />
	If you liked the plugin, please say a kind word or donate <a href="http://svcs.affero.net/rm.php?&r=dbzer0&p=Void_Infinite">here</a><br />
	Many thanks to <a href="http://edward.de.leau.net">Edward de Leau</a> for his excellent options template.<br />
</p>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>

