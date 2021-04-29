var Wishes = function (config) {
    config = config || {};
    Wishes.superclass.constructor.call(this, config);
};
Ext.extend(Wishes, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('wishes', Wishes);

Wishes = new Wishes();