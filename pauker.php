<?php
/*
Plugin Name: Pauker
Plugin URI: http://www.mysse.net/
Description: This plugin adds a widget that will show a random flash card from a Pauker card set. There's also a plugin that shows the statistics of the current Pauker card set. See http://pauker.sourceforge.net/ for more details on Pauker by ???
Version: 1.0
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
define(PAUKERFILE, 'pauker.pau');
define(PAUKERUPLOADPATH, '../wp-content/uploads/pauker/');
define(PAUKEROPENPATH, 'wp-content/uploads/pauker/');

function getRandomCardFront($cards) {
	if (!$cards->Batch) {
		return;
	}
	$numCards = 0;
	foreach ($cards->Batch as $batch) {
		$numCards += sizeof($batch->Card);
	}
	$random = rand(0, $numCards-1);
	$batch = 0;
	while ($random >= sizeof($cards->Batch[$batch])) {
		$random -= sizeof($cards->Batch[$batch]);
		$batch++;
	}
	echo nl2br($cards->Batch[$batch]->Card[$random]->FrontSide[0]->Text[0]);
	return array($batch, $random);
}

function getCardBack($cards, $pos) {
	echo nl2br($cards->Batch[$pos[0]]->Card[$pos[1]]->ReverseSide[0]->Text[0]);
}

function paukerSaveFile($name) {
	if(!file_exists(PAUKERUPLOADPATH)) {
		mkdir(PAUKERUPLOADPATH, 0700);
	}
	if(!move_uploaded_file($_FILES[$name]['tmp_name'], PAUKERUPLOADPATH.PAUKERFILE.".tmp")) {
		die("Upload failed!");
	}
	$gzFile = gzopen(PAUKERUPLOADPATH.PAUKERFILE.".tmp", "r");
	$data = gzread($gzFile, 20*filesize(PAUKERUPLOADPATH.PAUKERFILE.".tmp"));
	gzclose($gzFile);
	unlink(PAUKERUPLOADPATH.PAUKERFILE.".tmp");
	$destFile = fopen(PAUKERUPLOADPATH.PAUKERFILE, 'w');
	fwrite($destFile, $data);
	fclose($destFile);
}

function widget_pauker_register() {
	function widget_pauker($args) {
		extract($args);
		$options = get_option('widget_pauker');
		$title = empty($options['title']) ? 'Pauker' : $options['title'];
		$cards = simplexml_load_file(PAUKEROPENPATH.PAUKERFILE);
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
			<?php if (true) {?><li style="border-bottom: 1px solid #225378;"><?php $pos = getRandomCardFront($cards); ?></li><?php } ?>
			<?php if (isset($pos)) {?><li><?php getCardBack($cards, $pos); ?></li><?php } ?>
			</ul>
		<?php echo $after_widget; ?>

<?php
	}

	function widget_pauker_control() {
		$options = get_option('widget_pauker');
		if ( isset($_POST["pauker-submit"]) ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["pauker-title"]));
			if ($_FILES['pauker-file']['tmp_name'] != "") {
			    paukerSaveFile('pauker-file');
			}
			$options = $newoptions;
			update_option('widget_pauker', $options);
		}
		if ( $options != $newoptions ) {
		}
		$title = attribute_escape($options['title']);
	?>
				<p><label for="pauker-title"><?php _e('Title:', 'pauker'); ?> <input class="widefat" id="pauker-title" name="pauker-title" type="text" value="<?php echo $title; ?>" /></label></p>
				<p><label for="pauker-file"><?php _e('Upload Pauker File', 'pauker'); ?></label><input id="pauker-file" name="pauker-file" type="file" /></p>
				<input type="hidden" id="pauker-submit" name="pauker-submit" value="1" />
	<?php
	}
	$ops = array('classname' => 'widget_pauker', 'description' => "Shows a random flash card of a given Pauker data file" );
	wp_register_sidebar_widget('pauker', 'Pauker', 'widget_pauker', $widget_ops);
	wp_register_widget_control('pauker', 'Pauker', 'widget_pauker_control' );
}
add_action('widgets_init', 'widget_pauker_register');

?>
