Wishes.grid.Items = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'wishes-grid-items';
    }
    Ext.applyIf(config, {
        url: Wishes.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/wishes/getlist'
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateItem(grid, e, row);
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'wishes-grid-row-disabled'
                    : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    });
    Wishes.grid.Items.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(Wishes.grid.Items, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = Wishes.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    createItem: function (btn, e) {
        var w = MODx.load({
            xtype: 'wishes-item-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({active: true});
        w.show(e.target);
    },

    updateItem: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/wishes/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'wishes-item-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    removeItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('wishes_items_remove')
                : _('wishes_item_remove'),
            text: ids.length > 1
                ? _('wishes_items_remove_confirm')
                : _('wishes_item_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/wishes/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },

    disableItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/wishes/disable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },

    exportItems: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        window.open(this.config.url+'?action=export&ids='+ids+'&type=full');
    },

    exportItemsSmall: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        window.open(this.config.url+'?action=export&ids='+ids+'&type=small');
    },

    enableItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: _('wishes_item_enable'),
            text: _('wishes_item_enable_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/wishes/enable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
    },

    getFields: function () {
        return ['id', 'name', 'description', 'createdon', 'endon','active', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('wishes_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70
        }, {
            header: _('wishes_item_name'),
            dataIndex: 'name',
            sortable: true,
            width: 200,
        }, {
            header: _('wishes_item_description'),
            dataIndex: 'description',
            sortable: false,
            width: 250,
        }, {
            header: _('wishes_item_createdon'),
            dataIndex: 'createdon',
            sortable: false,
            width: 250,
        }, {
            header: _('wishes_item_endon'),
            dataIndex: 'endon',
            sortable: false,
            width: 250,
        }, {
            header: _('wishes_item_active'),
            dataIndex: 'active',
            renderer: Wishes.utils.renderBoolean,
            sortable: true,
            width: 100,
        }, {
            header: _('wishes_grid_actions'),
            dataIndex: 'actions',
            renderer: Wishes.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('wishes_item_create'),
            handler: this.createItem,
            scope: this
        }, '->', {
            xtype: 'wishes-field-search',
            width: 250,
            listeners: {
                search: {
                    fn: function (field) {
                        this._doSearch(field);
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        field.setValue('');
                        this._clearSearch();
                    }, scope: this
                },
            }
        }];
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },
});
Ext.reg('wishes-grid-items', Wishes.grid.Items);
