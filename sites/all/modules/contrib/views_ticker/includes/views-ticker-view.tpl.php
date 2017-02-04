<?php
// $Id$

/**
 * @file
 * Displays a scrolling list.
 *
 * @ingroup views_templates
 */
?>
<!-- start scroll -->
<?php
print "<div class='view view-$viewname'><div class='view-content view-content-$viewname'>";

if ($scroller_type == 'horizontal')
{
	print "<div>";
	print "<ul id='views-ticker-liScroll-$viewname'>";
}
elseif ($scroller_type == 'vertical')
{
	print "<div id='views-ticker-vTicker-$viewname'>";
	print "<ul id='views-ticker-vTicker-list-$viewname'>";
}
elseif ($scroller_type == 'bbc')
{
	print "<div id='views-ticker-$align-$viewname'>";
	print "<ul id='views-ticker-bbc-$viewname'>";
}
else
{
	print "<div id='views-ticker-$align-$viewname'>";
	print "<ul id='views-ticker-fade-$viewname'>";
}

foreach ($rows as $row)
{
	if ($scroller_type == 'horizontal')
	{
		print "<li class='views-liScroll-item views-liScroll-item-$viewname'>";
		print "<span class='views-liScroll-tick-field'>$row</span></li>";
	}
	elseif ($scroller_type == 'vertical')
	{
		print "<li class='views-vTicker-item views-vTicker-item-$viewname'>";
		print "<span class='views-vTicker-tick-field'>$row</span></li>";
	}
	elseif($scroller_type=='fade')
	{
		print "<li class='views-fade-item views-fade-item-$viewname'>";
		print "<span class='views-fade-tick-field'>$row</span></li>";
	}
	else #bbc
	{
		print "<li class='views-bbc-item views-bbc-item-$viewname'>";
		print "<span class='views-bbc-tick-field'>$row</span></li>";
	}
}


print "</ul></div></div></div>";

?>

<!-- end scroll -->
