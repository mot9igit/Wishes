Wishes.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'wishes-panel-home',
            renderTo: 'wishes-panel-home-div'
        }]
    });
    Wishes.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(Wishes.page.Home, MODx.Component);
Ext.reg('wishes-page-home', Wishes.page.Home);