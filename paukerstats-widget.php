<?php
function widget_paukerstats_register() {
	function widget_paukerstats($args) {
		extract($args);
		$options = get_option('widget_paukerstats');
		$title = empty($options['title']) ? 'PaukerStats' : $options['title'];
		$cards = simplexml_load_file(PAUKEROPENPATH.PAUKERFILE);
		$numCards = 0;
		$numLearned = 0;
		$numUnlearned = 0;
		$numExceeded = 0;
		$i = 0;
		foreach ($cards->Batch as $batch) {
			$numCards += sizeof($batch->Card);
			foreach ($batch->Card as $card){
				if ($i < 3) {
					$numUnlearned++;
				} else if (intval($card->FrontSide[0]['LearnedTimestamp'])/1000 < (time()-24*3600*exp($i-3))) {
					$numExceeded++;
				} else {
					$numLearned++;
				}
			}
			$i++;
		}
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
				<li><b><?php echo $numCards; ?></b> <?php _e('words', 'pauker'); ?></li>
				<li><b><?php echo $numLearned; ?></b> <?php _e('learned', 'pauker'); ?></li>
				<li><b><?php echo $numExceeded; ?></b> <?php _e('expired', 'pauker'); ?></li>
				<li><b><?php echo $numUnlearned; ?></b> <?php _e('not learned', 'pauker'); ?></li>
			</ul>
		<?php echo $after_widget; ?>

<?php
	}

	function widget_paukerstats_control() {
		$options = get_option('widget_paukerstats');
		if ( isset($_POST["paukerstats-submit"]) ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["paukerstats-title"]));
			if ($_FILES['paukerstats-file']['tmp_name'] != "") {
			    paukerstatsSaveFile('paukerstats-file');
			}
			$options = $newoptions;
			update_option('widget_paukerstats', $options);
		}
		if ( $options != $newoptions ) {
		}
		$title = attribute_escape($options['title']);
	?>
				<p><label for="paukerstats-title"><?php _e('Title:', 'pauker'); ?> <input class="widefat" id="paukerstats-title" name="paukerstats-title" type="text" value="<?php echo $title; ?>" /></label></p>
				<input type="hidden" id="paukerstats-submit" name="paukerstats-submit" value="1" />
	<?php
	}
	$ops = array('classname' => 'widget_paukerstats', 'description' => "Shows the stats of a given Pauker data file" );
	wp_register_sidebar_widget('paukerstats', 'PaukerStats', 'widget_paukerstats', $widget_ops);
	wp_register_widget_control('paukerstats', 'PaukerStats', 'widget_paukerstats_control' );
}
?>