<?php

class Wishes
{
    /** @var modX $modx */
    public $modx;
	public $simplexlsx;

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

		$corePath = $this->modx->getOption('wishes_core_path', $config, $this->modx->getOption('core_path') . 'components/wishes/');
		$assetsUrl = $this->modx->getOption('wishes_assets_url', $config, $this->modx->getOption('assets_url') . 'components/wishes/');
		$assetsPath = $this->modx->getOption('wishes_assets_path', $config, $this->modx->getOption('base_path') . 'assets/components/wishes/');

		require_once($assetsPath . 'libs/simplexslxgen/SimpleXLSXGen.php');
		$this->simplexlsx = new SimpleXLSXGen();

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

		//$this->modx->log(1, print_r($this->config, 1));

        $this->modx->addPackage('wishes', $this->config['modelPath']);
        $this->modx->lexicon->load('wishes:default');
    }

    public function getFullXLSX($id){
		$simple_xlsx = $this->simplexlsx;
    	$products = $this->modx->getCollection("WishesProducts", array("wishes_id" => $id));
    	$data = array();
		$this->modx->log(1, count($products));
    	foreach($products as $key => $product){
    		$p = $product->toArray();
			$head = array();
			$values = array();
			foreach($p as $k => $v){
				$head[] = $k; //$this->modx->lexicon("wishes_item_product_".$k)
				$values[] = $v;
			}
			$this->modx->log(1, $key);
			if($key == 1) {
				$data[] = $head;
			}
			$data[] = $values;
		}
		$this->modx->log(1, print_r($data, 1));
		$simple_xlsx->addSheet($data, 'Потребности' );
		//$simple_xlsx->fromArray($data);
		$simple_xlsx->downloadAs('wish_'.$id.'_'.date("d-m-Y_H:i:s").'.xlsx');
	}

	public function getSimpleXLSX($id){
		$simple_xlsx = $this->simplexlsx;
		$products = $this->modx->getCollection("WishesProducts", array("wishes_id" => $id));
		$data = array();
		$head = array('article', 'name', 'count', 'cost');
		$data[] = $head;
		$this->modx->log(1, count($products));
		foreach($products as $key => $product){
			$p = $product->toArray();
			if($data[$p["article"]]){
				$data[$p["article"]]["count"] += $p["count"];
				$data[$p["article"]]["cost"] += $p["cost"];
			}else {
				$data[$p["article"]] = array(
					"article" => $p["article"],
					"name" => $p["name"],
					"count" => $p["count"],
					"cost" => $p["cost"]
				);
			}
		}
		$simple_xlsx->addSheet($data, 'Потребности' );
		//$simple_xlsx->fromArray($data);
		$simple_xlsx->downloadAs('wish_'.$id.'_'.date("d-m-Y_H:i:s").'.xlsx');
	}
}