<?php
/** @var modX $modx */
switch ($modx->event->name) {
	case 'msOnCreateOrder':
		// get Wishes
		$corePath = $modx->getOption('wishes_core_path', null, $modx->getOption('core_path') . 'components/wishes/');
		$Wishes = $modx->getService('Wishes', 'Wishes', $corePath . 'model/');
		if($Wishes){
			$order = $modx->getOption('msOrder', $scriptProperties);
			if (!is_object($order)) {
				return;
			}
			$criteria = array(
				"active" => 1
			);
			$active_wish = $modx->getObject("WishesItems", $criteria);
			if($active_wish){
				foreach ($order->getMany('Products') as $orderProduct) {
					$data = array();
					$data["wishes_id"] = $active_wish->get("id");
					$data["order_id"] = $order->get("id");
					$data["product_id"] = $orderProduct->get('product_id');
					$data["name"] = $orderProduct->get('name');
					$data["count"] = $orderProduct->get('count');
					$data["price"] = $orderProduct->get('price');
					$data["weight"] = $orderProduct->get('weight');
					$data["cost"] = $orderProduct->get('cost');
					$options = $orderProduct->get('options');
					if($options["modification"]){
						$product = $modx->getObject("msopModification", $options["modification"]);
						if($product){
							$data["article"] = $product->get("article");
						}
					}else{
						$product = $modx->getObject("msProduct", $data["product_id"]);
						if($product){
							$data["article"] = $product->get("article");
						}
					}
					$data["options"] = json_encode($options);

					// set new object
					$w_product = $modx->newObject('WishesProducts');
					$w_product->fromArray($data);
					$w_product->save();
				}
			}else{
				$modx->log(MODX_LOG_LEVEL_ERROR, "Нет активных Wishes");
			}
		}else{
			$modx->log(MODX_LOG_LEVEL_ERROR, "Не могу инициализировать объект Wishes");
		}
		break;
}