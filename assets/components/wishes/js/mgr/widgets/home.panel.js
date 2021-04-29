Wishes.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'wishes-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('wishes') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('wishes_items'),
                layout: 'anchor',
                items: [{
                    html: _('wishes_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'wishes-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    Wishes.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(Wishes.panel.Home, MODx.Panel);
Ext.reg('wishes-panel-home', Wishes.panel.Home);
