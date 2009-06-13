<?php
/*
Plugin Name: Pauker
Plugin URI: http://www.mysse.net/
Description: This plugin adds a widget that will show a random flash card from a Pauker card set. There's also a widget that shows the statistics of the current Pauker card set. See http://pauker.sourceforge.net/ for more details on Pauker by Ronny Standke
Version: 1.1
Author: moe
Author URI: http://www.mysse.net/
*/

/*  Copyright 2009  Moe  (email : paukerpluginReMoVeThIsBeFoReSeNdInG@mysse.net)

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

$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'pauker', 'wp-content/plugins/' . $plugin_dir, $plugin_dir );

require_once('pauker-functions.php');
require_once('pauker-widget.php');
require_once('paukerstats-widget.php');
require_once('pauker-includes.php');

add_action('widgets_init', 'widget_pauker_register');
add_action('widgets_init', 'widget_paukerstats_register');
add_action('admin_menu', 'pauker_admin_actions');

?>
