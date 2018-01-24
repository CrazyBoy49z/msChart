<?php
/** @var modX $modx */
/** @var msChart $msChart */

switch ($modx->event->name) {
    case 'OnBeforeManagerPageInit':
        /** @var array $action */
        if ($action['namespace'] != 'minishop2' && $action['controller'] != 'mgr/orders') {
            return '';
        }
        if ($msChart = $modx->getService('mschart', 'msChart', MODX_CORE_PATH . 'components/mschart/model/mschart/')) {
            $msChart->loadJS($modx->controller);
        }
        break;
}