<?php

class msChart
{
    /** @var modX $modx */
    public $modx;

    public $fields = array();


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('mschart_core_path', $config,
            $this->modx->getOption('core_path') . 'components/mschart/'
        );
        $assetsUrl = $this->modx->getOption('mschart_assets_url', $config,
            $this->modx->getOption('assets_url') . 'components/mschart/'
        );
        $connectorUrl = $assetsUrl . 'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            //'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'templatesPath' => $corePath . 'elements/templates/',
            'processorsPath' => $corePath . 'processors/',
        ), $config);

        $this->modx->addPackage('mschart', $this->config['modelPath']);
        $this->modx->lexicon->load('mschart:default');
    }

    /**
     * @param modManagerController $controller
     */
    public function loadJS(modManagerController $controller)
    {
        //$this->modx->regClientStartupScript($this->config['jsUrl'] . 'mgr/mschart.js');

        $controller->addLexiconTopic('mschart:default');

        $controller->addJavascript($this->config['jsUrl'] . 'mgr/mschart.js');
        $controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/chart.panel.js');
        $controller->addJavascript($this->config['jsUrl'] . 'mgr/vendor/highstock/highcharts.js');
        $controller->addJavascript($this->config['jsUrl'] . 'mgr/vendor/highstock/modules/data.js');
        $controller->addJavascript($this->config['jsUrl'] . 'mgr/vendor/highstock/modules/drilldown.js');

        $controller->addHtml('<script type="text/javascript">
             msChart.config = {
                 "status":' . $this->getItems('msOrderStatus') . ',
                 "statusPercent":' . $this->getItemsPercent('status') . ',
                 "payment":' . $this->getItems('msPayment') . ',
                 "paymentPercent":' . $this->getItemsPercent('payment') . ',
                 "delivery":' . $this->getItems('msDelivery') . ',
                 "deliveryPercent":' . $this->getItemsPercent('delivery') . '
             }
            </script>
        ');
    }

    /**
     * @param $class
     * @return string
     */
    public function getItems($class)
    {
        $response = [];

        $q = $this->modx->newQuery($class);
        $q->select('id, name');

        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $name = !empty(strip_tags($row['name']))
                    ? $row['name']
                    : $this->modx->lexicon('mschart_other');
                $this->fields[$row['id']] = $name;
                $response[] = $name;
            }
        }
        return json_encode($response);
    }

    /**
     * Вычисляет процент
     * @param $mode
     * @return string
     */
    public function getItemsPercent($mode)
    {
        $response = [];
        $total = [];

        if ($items = $this->fields) {
            $c = $this->modx->newQuery('msOrder');
            $c->select('id, status, payment, delivery');

            if ($c->prepare() && $c->stmt->execute()) {
                while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tmp[$row[$mode]][] = $row;
                    $total[] = $row;
                }
                $countTotal = count($total);

                foreach ($items as $key => $item) {
                    $count = count($tmp[$key]);
                    $response[$item] = $count > 0 ? $count / $countTotal * 100 : 0;
                }
            }
            $this->fields = array();
        }
        return json_encode($response);
    }

}