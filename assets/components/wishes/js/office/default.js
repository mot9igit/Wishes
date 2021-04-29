Ext.onReady(function () {
    Wishes.config.connector_url = OfficeConfig.actionUrl;

    var grid = new Wishes.panel.Home();
    grid.render('office-wishes-wrapper');

    var preloader = document.getElementById('office-preloader');
    if (preloader) {
        preloader.parentNode.removeChild(preloader);
    }
});