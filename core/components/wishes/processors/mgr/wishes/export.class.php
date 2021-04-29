<?php

$corePath = $modx->getOption('wishes_core_path', null, $modx->getOption('core_path') . 'components/wishes/');
$Wishes = $modx->getService('Wishes', 'Wishes', $corePath . 'model/');
if ($Wishes) {
	if ($_REQUEST["type"] == 'full') {
		$Wishes->getFullXLSX($_REQUEST['ids']);
	} else {
		$Wishes->getSimpleXLSX($_REQUEST['ids']);
	}
}