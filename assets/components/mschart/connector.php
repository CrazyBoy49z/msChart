<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var msChart $msChart */
$msChart = $modx->getService('mschart', 'msChart', $modx->getOption('mschart_core_path', null,
        $modx->getOption('core_path') . 'components/mschart/') . 'model/mschart/'
);
$modx->lexicon->load('mschart:default');

// handle request
$corePath = $modx->getOption('mschart_core_path', null, $modx->getOption('core_path') . 'components/mschart/');
$path = $modx->getOption('processorsPath', $msChart->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));