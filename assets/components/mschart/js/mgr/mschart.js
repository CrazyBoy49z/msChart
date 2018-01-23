var msChart = function (config) {
    config = config || {};
    msChart.superclass.constructor.call(this, config);
};
Ext.extend(msChart, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('mschart', msChart);

msChart = new msChart();


Ext.ComponentMgr.onAvailable('minishop2-form-orders', function() {
    this.on('beforerender', function() {
        this.add({
            layout: 'anchor',
            items: [{
                xtype: 'mschart-panel'
            }]
        });
    });
});