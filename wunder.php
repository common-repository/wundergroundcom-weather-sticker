<?php
/*
Plugin Name: wunderground.com Weather Sticker
Description: Adds a sidebar widget to display the standard wunderground.com Weather Sticker
Author: DropDeadDick
Version: 2.0
Author URI: http://dropdeaddick.com
Plugin URI: http://dropdeaddick.com
**** Added option for locations outside of US to enter station number
**** Addded option to select sticker with white text
**** Not all cities are available but don't blame me this just means its not listed at http://www.wunderground.com
This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
function widget_wunder_init() {
 
if ( !function_exists('register_sidebar_widget') )
		return;
		
function widget_wunder($args) {
		extract($args);
		$defaults = array('title' => '', 'txtcolor' => '', 'state' => '', 'city' => '', 'not_us' => '', 'station'=>'');
		$options = (array) get_option('widget_wunder');
		
		foreach ( $defaults as $key => $value )
			if ( !isset($options[$key]) )
				$options[$key] = $defaults[$key];
				
		echo $before_widget . $before_title;
		echo $options['title'];
		echo $after_title;
		echo "</br>"; 
		$city2 = preg_replace ( '# {1,}#', '_', trim ( $options['city'] ) );
		
		if ($options['txtcolor']){
		$infobox = "infoboxtr_white";
		}
		else{
		$infobox = "infoboxtr_both";
		}
		
		if ($options['not_us']){
		echo "<a href=\"http://www.wunderground.com/global/stations/".$options['station'].".html?bannertypeclick=infoboxtr\">
		<img src=\"http://banners.wunderground.com/weathersticker/".$infobox."/language/www/global/stations/".$options['station'].".gif\" border=0 alt=\"Click for Forecast\" height=108 width=144></a>";
		}
		else{
		echo "<a href=\"http://www.wunderground.com/US/".$options['state']."/".$city2.".html?bannertypeclick=infoboxtr\">
		<img src=\"http://banners.wunderground.com/weathersticker/".$infobox."/language/www/US/".$options['state']."/".$city2.".gif\" border=0 alt=\"Click for Forecast\" height=108 width=144></a>";
		}
		
		echo $after_widget;
}
	
function widget_wunder_control() {

		$options = get_option('widget_wunder');
		if ( !is_array($options) )
			$options = array('title' => '', 'txtcolor' => '', 'state' => '', 'city' => '', 'not_us' => '', 'station'=>'');
		if ( $_POST['wunder-submit'] ) {

			$options['title'] = strip_tags(stripslashes($_POST['wunder-title']));
			$options['txtcolor'] = isset($_POST['wunder-txtcolor']);
			$options['city'] = strip_tags(stripslashes($_POST['wunder-city']));
			$options['state'] = strip_tags(stripslashes($_POST['wunder-state']));
			$options['not_us'] = isset($_POST['wunder-not_us']);
			$options['station'] = strip_tags(stripslashes($_POST['wunder-station']));
			update_option('widget_wunder', $options);
		}
		
		$txtcolor = $options['txtcolor'] ? 'checked="checked"' : '';
        $title = htmlspecialchars($options['title'], ENT_QUOTES);
		$city = htmlspecialchars($options['city'], ENT_QUOTES);
		$state = htmlspecialchars($options['state'], ENT_QUOTES);
		$not_us = $options['not_us'] ? 'checked="checked"' : '';
		$station = htmlspecialchars($options['station'], ENT_QUOTES);
		
		echo '<p style="text-align:right;"><label for="wunder-title">Title: <input style="width: 200px;" id="wunder-title" name="wunder-title" type="text" value="'.$title.'" /></label></p>
		<p style="text-align:left;"><label for="wunder-txtcolor">Use White Text <input class="checkbox" type="checkbox" '.$txtcolor.' id="wunder-txtcolor" name="wunder-txtcolor" /></label></p>
		<p style="text-align:right;"><label for="wunder-city">City: <input style="width: 200px;" id="wunder-city" name="wunder-city" type="text" value="'.$city.'" /></label></p>
		<p style="text-align:right;"><label for="wunder-state">State Abbreviation: <input style="width: 125px;" id="wunder-state" name="wunder-state" type="text" value="'.$state.'" /></label></p>
		<p style="text-align:left;"><label for="wunder-not_us">Not located in US <input class="checkbox" type="checkbox" '.$not_us.' id="wunder-not_us" name="wunder-not_us" /></label></p>
		<p style="text-align:right;">*Use station number if outside US</p>
		<p style="text-align:right;"><label for="wunder-state">Station Number: <input style="width: 125px;" id="wunder-station" name="wunder-station" type="text" value="'.$station.'" /></label></p>
		<input type="hidden" id="wunder-submit" name="wunder-submit" value="1" />';
	
	}
	
	register_sidebar_widget('Weather Sticker', 'widget_wunder');
	register_widget_control('Weather Sticker', 'widget_wunder_control', 300, 265);
}
	add_action('plugins_loaded', 'widget_wunder_init');
?>