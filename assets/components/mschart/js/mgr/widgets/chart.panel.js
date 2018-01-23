msChart.panel.Chart = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'mschart-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            title: _('mschart_charts'),
            xtype: 'fieldset',
            cls: 'x-fieldset-checkbox-toggle',
            style: 'padding-top: 5px',
            checkboxToggle: true,
            collapsed: Ext.state.Manager.get('mschart-collapsed-chart') != true ? false : true,
            forceLayout: true,
            listeners: {
                'expand': {
                    fn: function (p) {
                        Ext.state.Manager.set('mschart-collapsed-chart', false);
                    }, scope: this
                },
                'collapse': {
                    fn: function (p) {
                        Ext.state.Manager.set('mschart-collapsed-chart', true);
                    }, scope: this
                }
            },
            items: [{
                layout: 'column',
                items: [{
                    columnWidth: .33,
                    layout: 'form',
                    items: [{
                        id: 'mschart-chart-panel-status',
                        listeners: {
                            render: {
                                fn: function (a,b) {
                                    this.Chart('status', 'statusPercent', _('mschart_status'));
                                }, scope: this
                            }, single: true
                        }
                    }]
                },{
                    columnWidth: .33,
                    layout: 'form',
                    items: [{
                        id: 'mschart-chart-panel-payment',
                        listeners: {
                            render: {
                                fn: function (a,b) {
                                    this.Chart('payment', 'paymentPercent', _('mschart_payment'));
                                }, scope: this
                            }, single: true
                        }
                    }]
                },{
                    columnWidth: .33,
                    layout: 'form',
                    items: [{
                        id: 'mschart-chart-panel-delivery',
                        listeners: {
                            render: {
                                fn: function (a,b) {
                                    this.Chart('delivery', 'deliveryPercent', _('mschart_delivery'));
                                }, scope: this
                            }, single: true
                        }
                    }]
                }]

            }]
            // items: [{
            //     xtype: 'modx-tabs',
            //     defaults: {border: false, autoHeight: true},
            //     border: true,
            //     deferredRender: false,
            //     hideMode: 'offsets',
            //     items: [{
            //         title: _('mschart_status'),
            //         layout: 'anchor',
            //         items: [{
            //             //xtype: 'mschart-grid-items',
            //             //cls: 'main-wrapper',
            //         }]
            //     }]
            // }]
        }]
    });
    msChart.panel.Chart.superclass.constructor.call(this, config);
};
Ext.extend(msChart.panel.Chart, MODx.Panel, {

    Chart: function (mode, v, text) {
        if (!Ext.get('mschart-highlight-' + mode)) {
            container = Ext.get('mschart-chart-panel-' + mode);
            Ext.DomHelper.append(container, {
                tag: 'div',
                id: 'mschart-highlight-' + mode
            });
        }
        var id = 'mschart-highlight-' + mode;

        Highcharts.chart(id, {
            chart: {
                type: 'column',
                height: 200
            },
            title: {
                text: text
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Total percent'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    cursor: 'pointer',
                    allowPointSelect: true,
                    //selected: true,
                    marker: {
                        states: {
                            select: {
                                lineColor: 'red'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}%'
                    },
                    point: {
                        events: {
                            click: function () {
                                // if (this.selected) {
                                //     Ext.getCmp('tcbillboard-grid-orders').baseParams.chart = null;
                                // } else {
                                //     Ext.getCmp('tcbillboard-grid-orders').baseParams.chart = this.val;
                                // }
                                // Ext.getCmp('tcbillboard-grid-orders').getBottomToolbar().changePage(1);
                                // Ext.getCmp('tcbillboard-grid-orders').refresh();
                            }
                        }
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
            },
            series: [{
                name: 'xnj-nj nfv',
                colorByPoint: true,
                data: this.getItemsChart(mode, v)
            }]
        });
    },

    getItemsChart: function(mode, v) {
        var charts = [];

        for (i = 0; i < msChart.config[mode].length; i++) {
            var field = msChart.config[mode][i];

            if (field) {
                var item = {
                    name: field,
                    y: msChart.config[v][field],
                    val: i + 1
                };
                charts.push(item);
            }
        }
        return charts;
    }
});
Ext.reg('mschart-panel', msChart.panel.Chart);
