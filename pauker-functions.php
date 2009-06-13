<?php

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

function pauker_menu()
{
    global $wpdb;
    include 'pauker-admin.php';
}
 
function pauker_admin_actions()
{
    add_options_page("Pauker", "Pauker", 1,
"Pauker", "pauker_menu");
}
?>