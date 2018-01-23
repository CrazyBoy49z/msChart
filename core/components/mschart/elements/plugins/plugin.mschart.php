<?php
/** @var modX $modx */
/** @var msChart $msChart */

switch ($modx->event->name) {
    case 'OnManagerPageBeforeRender':
        if ($_GET['a'] != 'mgr/orders' && $_GET['namespace'] != 'minishop2') {
            return;
        }
        if ($msChart = $modx->getService('mschart', 'msChart', MODX_CORE_PATH . 'components/mschart/model/mschart/')) {
            $msChart->loadJS($modx->controller);

            //$modx->log(modX::LOG_LEVEL_ERROR, $modx->event->name .' '.   print_r($tmp, 1));
        }
        break;
}