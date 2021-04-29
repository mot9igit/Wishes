<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var Wishes $Wishes */
$Wishes = $modx->getService('Wishes', 'Wishes', MODX_CORE_PATH . 'components/wishes/model/');
$modx->lexicon->load('wishes:default');

// handle request
$corePath = $modx->getOption('wishes_core_path', null, $modx->getOption('core_path') . 'components/wishes/');
$path = $modx->getOption('processorsPath', $Wishes->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);