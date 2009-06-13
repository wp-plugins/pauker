<?php 
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
				<p>
				<input type="hidden" id="pauker-submit" name="pauker-submit" value="1" />
	<?php
	}
	$ops = array('classname' => 'widget_pauker', 'description' => "Shows a random flash card of a given Pauker data file" );
	wp_register_sidebar_widget('pauker', 'Pauker', 'widget_pauker', $widget_ops);
	wp_register_widget_control('pauker', 'Pauker', 'widget_pauker_control' );
}
?>