<?php
	try {
		require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
		include_file('core', 'authentification', 'php');
		
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (init('action') == 'SearchCamera') {
			$EqLogic = eqLogic::byType('camera');
			/*    if (!is_object($EqLogic)) {
			// ajax::success(false);
			}*/
			$return=array();
			foreach($EqLogic as $Camera)
			$return[]=array('Nom'=>$Camera->getName(),'Id'=>$Camera->getID());
			ajax::success($return);
		}
		if (init('action') == 'SearchUSBCamera') {
			$USBCamera=array();
			foreach (motion::getUsbMapping() as $name => $value) {
			$USBCamera[]=array('name'=>$name,'value'=>$value);
		}
		ajax::success($USBCamera);
		}
		if (init('action') == 'removeRecord') {
			$result;
			$file = init('file');
			$Camera=eqLogic::byId(init('cameraId'));
			if (is_object($Camera)){
				$result=motion::removeRecord($file);
				ajax::success($result);
			}
			ajax::success(false);
		}
		if (init('action') == 'WidgetHtml') {
			$MotionCamera=eqLogic::byId(init('cameraId'));
			if (is_object($MotionCamera))
				ajax::success($MotionCamera->toHtml('dashboard'));
			ajax::success(false);
		}
		if (init('action') == 'RefreshFlux') {
			$MotionCamera=eqLogic::byId(init('cameraId'));
			if (is_object($MotionCamera))
				ajax::success($MotionCamera->getSnapshot());
		ajax::success(false);
		}
		if (init('action') == 'getCamera') {
			if (init('object_id') == '') {
				$object = object::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
			} else {
				$object = object::byId(init('object_id'));
			}
			if (!is_object($object)) {
				$object = object::rootObject();
			}
			$return = array();
			$return['eqLogics'] = array();
			if (init('object_id') == '') {
				foreach (object::all() as $object) {
					foreach ($object->getEqLogic(true, false, 'motion') as $camera) {
						$return['eqLogics'][] = $camera->toHtml(init('version'));
					}
				}
			} else {
				foreach ($object->getEqLogic(true, false, 'motion') as $camera) {
					$return['eqLogics'][] = $camera->toHtml(init('version'));
				}
				foreach (object::buildTree($object) as $child) {
					$cameras = $child->getEqLogic(true, false, 'motion');
					if (count($cameras) > 0) {
						foreach ($cameras as $camera) {
							$return['eqLogics'][] = $camera->toHtml(init('version'));
						}
					}
				}
			}
			ajax::success($return);
		}
		if (init('action') == 'getLog') {
			ajax::success("<pre>".file_get_contents('/etc/motion/motion.log')."</pre>");
		}
		if (init('action') == 'removeLog') {
			exec('sudo rm /etc/motion/motion.log > /dev/null 2>/dev/null &');
			ajax::success("Suppression faite");
		}
		if (init('action') == 'getCoord') {
			$return='';
			$cmd=cmd::byId(init('id'));
			if(is_object($cmd)){
				switch($cmd->getLogicalId()){
					case 'detect':	
						$return= $cmd->getConfiguration('DetectArea');
					break;
					case 'maphilight':
						$return= $cmd->getConfiguration('maphilightArea');
					break;
				}
			}
			ajax::success($return);
		}
		throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
		/*     * *********Catch exeption*************** */
	} catch (Exception $e) {
		ajax::error(displayExeption($e), $e->getCode());
	}
?>
