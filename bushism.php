<?php
/*
Plugin Name: Bushism
Plugin URI: http://www.hindoogle.com/bushism
Description: Displays a wacky quote from George W. Bush every day.
Version: 1.2
Author: Rajat Banerjee
Author URI: http://www.hindoogle.com
 */

/*
 * Authors Note: You are free to use this plugin as much or anywhere you would like.
 * It is totally free.
 * My only request is that you don't change the link-back to my site.
*/
 

/*  Copyright 2008  Rajat Banerjee  (email : rajat _at_ Hindoogle _._ com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



function retr_bushism(&$quote, &$source)
{
	$url = "http://www.hindoogle.com/bushism/today.php";
	if(!($contents = file_get_contents($url)))
	{
		echo 'Could not open URL: $url';
		exit;
	}

	$qstart = strpos($contents,'<quote>') + 7;
	$qlen = strpos($contents,'</quote>') - $qstart;

	$sstart = strpos($contents,'<source>')  + 8;
	$slen = strpos($contents,'</source>') - $sstart;

	$quote = substr($contents, $qstart, $qlen);
	$source = substr($contents,$sstart, $slen);
	if(is_naughty($quote))
	{
		$quote = "";
	}
	if(is_naughty($source))
	{
		$source = "";
	}
}

function get_bushism()
{
	$myquote;
	$mysource;# = "";
	$ret;# = "";
	retr_bushism($myquote,$mysource);
	if(strlen($myquote))
	{
		$ret = $myquote;
	}
	else
	{
		echo "No quote found!";
	}
	if(strlen($mysource))
	{
		//Please don't modify this script.
		$ret = $ret . " -- " . "<a href=\"http://www.hindoogle.com/bushism\">" . $mysource . "</a>";
	}
	else
	{
		echo "No source found!";
	}
	return $ret;
}

function is_naughty($input)
{
	$stripped = strip_tags($input);
	if($stripped != $input)
		return true;

	#if(strpbrk($input, '"\'\\\/\*\&\^') != FALSE)
	#	return true;

	return false;
}

function bushism_inpost($text)
{
	
	$start = strpos($text,"[bushism]");
	if ($start !== FALSE) {
		$str = get_bushism();
		$text = preg_replace("/\[bushism\]/i", $str, $text );
	}
	return $text;
}

if(function_exists(add_filter))
{
	add_filter('the_content','bushism_inpost',7);
	add_filter('the_excerpt','bushism_inpost',7);
}
else {
	echo "Didn't load as WP Plugin..<P>";
}
?>
