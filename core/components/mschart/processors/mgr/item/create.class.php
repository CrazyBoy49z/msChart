<?php

class msChartItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'msChartItem';
    public $classKey = 'msChartItem';
    public $languageTopics = array('mschart');
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mschart_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
            $this->modx->error->addField('name', $this->modx->lexicon('mschart_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'msChartItemCreateProcessor';