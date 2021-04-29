<?php

/**
 * The home manager controller for Wishes.
 *
 */
class WishesHomeManagerController extends modExtraManagerController
{
    /** @var Wishes $Wishes */
    public $Wishes;


    /**
     *
     */
    public function initialize()
    {
		$corePath = $this->modx->getOption('wishes_core_path', null, $this->modx->getOption('core_path') . 'components/wishes/');
        $this->Wishes = $this->modx->getService('Wishes', 'Wishes', $corePath . 'model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['wishes:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('wishes');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->Wishes->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->Wishes->config['jsUrl'] . 'mgr/wishes.js');
        $this->addJavascript($this->Wishes->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->Wishes->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->Wishes->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->Wishes->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->Wishes->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->Wishes->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        Wishes.config = ' . json_encode($this->Wishes->config) . ';
        Wishes.config.connector_url = "' . $this->Wishes->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "wishes-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="wishes-panel-home-div"></div>';

        return '';
    }
}