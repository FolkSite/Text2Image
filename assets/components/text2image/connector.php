<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var Text2Image $Text2Image */
$Text2Image = $modx->getService('text2image', 'Text2Image', $modx->getOption('text2image_core_path', null, $modx->getOption('core_path') . 'components/text2image/') . 'model/text2image/');
$modx->lexicon->load('text2image:default');

// handle request
$corePath = $modx->getOption('text2image_core_path', null, $modx->getOption('core_path') . 'components/text2image/');
$path = $modx->getOption('processorsPath', $Text2Image->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));