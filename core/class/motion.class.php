<?php
/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
include_file('core', 'MotionService', 'class', 'motion');
include_file('core', 'pointLocation', 'class', 'motion');
class motion extends eqLogic {
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//                                                                                                   fa                                            //
	//                                                                 Fonction jeedom                                                               // 
	//                                                                                                                                               //
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	public static function getUsbMapping($_name = '') {
		$cache = cache::byKey('motion::usbMapping');
		if (!is_json($cache->getValue()) || $_name == '') {
			$usbMapping = array();
			foreach (ls('/dev/', 'video*') as $usb) {
				$vendor = '';
				$model = '';
				foreach (explode("\n", shell_exec('/sbin/udevadm info --name=/dev/' . $usb . ' --query=all')) as $line) {
					if (strpos($line, 'E: ID_MODEL=') !== false) {
						$model = trim(str_replace(array('E: ID_MODEL=', '"'), '', $line));
					}
					if (strpos($line, 'E: ID_VENDOR=') !== false) {
						$vendor = trim(str_replace(array('E: ID_VENDOR=', '"'), '', $line));
					}
				}
				if ($vendor == '' && $model == '') {
					$usbMapping['/dev/' . $usb] = '/dev/' . $usb;
				} else {
					$name = trim($vendor . ' ' . $model);
					$number = 2;
					while (isset($usbMapping[$name])) {
						$name = trim($vendor . ' ' . $model . ' ' . $number);
						$number++;
					}
					$usbMapping[$name] = '/dev/' . $usb;
				}
			}
			cache::set('motion::usbMapping', json_encode($usbMapping), 0);
		} else {
			$usbMapping = json_decode($cache->getValue(), true);
		}
		if ($_name != '') {
			if (isset($usbMapping[$_name])) {
				return $usbMapping[$_name];
			}
			$usbMapping = self::getUsbMapping('');
			if (isset($usbMapping[$_name])) {
				return $usbMapping[$_name];
			}
			if (file_exists($_name)) {
				return $_name;
			}
			return '';
		}
		return $usbMapping;
	}
    	public function preInsert() {
		$this->setConfiguration('analyse','local');
		$this->setConfiguration('stream_quality','50');
		$this->setConfiguration('stream_maxrate','5');
		$this->setConfiguration('v4l2_palette','17');
		$this->setConfiguration('norm','0');
		$this->setConfiguration('frequency','0');
		$this->setConfiguration('rotate','0');
		$this->setConfiguration('width','380');
		$this->setConfiguration('height','240');
		$this->setConfiguration('framerate','5');
		$this->setConfiguration('minimum_frame_time','0');
		$this->setConfiguration('netcam_tolerant_check',0);
		$this->setConfiguration('auto_brightness',1);
		$this->setConfiguration('brightness','0');
		$this->setConfiguration('contrast','0');
		$this->setConfiguration('saturation','0');
		$this->setConfiguration('hue','0');
		$this->setConfiguration('roundrobin_frames','0');
		$this->setConfiguration('roundrobin_skip','0');
		$this->setConfiguration('switchfilter',0);
		$this->setConfiguration('threshold','1500');
		$this->setConfiguration('noise_level','32');
		$this->setConfiguration('noise_tune',1);
		$this->setConfiguration('mask_file','');
		$this->setConfiguration('smart_mask_speed','0');
		$this->setConfiguration('lightswitch','0');
		$this->setConfiguration('minimum_motion_frames','0');
		$this->setConfiguration('pre_capture','0');
		$this->setConfiguration('post_capture','0');
		$this->setConfiguration('event_gap','60');
		$this->setConfiguration('max_movie_time','0');
		$this->setConfiguration('emulate_motion',0);
		$this->setConfiguration('snapshot_interval','0');
		$this->setConfiguration('output_pictures','best');
		$this->setConfiguration('output_debug_pictures',0);
		$this->setConfiguration('quality','75');
		$this->setConfiguration('ffmpeg_output_movies',1);
		$this->setConfiguration('ffmpeg_output_debug_movies',0);
		$this->setConfiguration('ffmpeg_timelapse','0');
		$this->setConfiguration('ffmpeg_timelapse_mode','manual');
		$this->setConfiguration('ffmpeg_bps','500000');
		$this->setConfiguration('ffmpeg_video_codec','mpeg4');
		$this->setConfiguration('locate_motion_mode',0);
		$this->setConfiguration('locate_motion_style','box');
		$this->setConfiguration('text_right','%Y-%m-%d\n%T-%q');
		$this->setConfiguration('text_changes',0);
		$this->setConfiguration('text_event','%Y%m%d%H%M%S');
		$this->setConfiguration('text_double',0);
		$this->setConfiguration('snapshot_filename','%v-%Y%m%d%H%M%S-snapshot');
		$this->setConfiguration('picture_filename','%v-%Y%m%d%H%M%S-%q');
		$this->setConfiguration('movie_filename','%v-%Y%m%d%H%M%S');
		$this->setConfiguration('timelapse_filename','%Y%m%d-timelapse');
		$this->setConfiguration('ipv6_enabled',0);       
    	}
	public function preSave(){
		/*$this->setConfiguration('stream_motion',1);
		$this->setConfiguration('stream_port',$this->getStreamPort());*/
		$directory = dirname(__FILE__) . '/../../mask/';
	}
	public function postSave() {
		$file='/etc/motion/camera'.$this->getId().'.conf';
		$this->NewThread();
		self::AddCommande($this,__('Parcourir les video', __FILE__),'browseRecord',"info", 'binary');
		$detect=self::AddCommande($this,'Détection','detect',"info", 'binary','','MotionDetectZone');
		$detect->setConfiguration('repeatEventManagement','always');
		$detect->save();
		self::AddCommande($this,'Prendre une photo','snapshot',"action", 'other','<i class="fa fa-camera"></i>');
		self::AddCommande($this,'Enregistrer une video','makemovie',"action", 'other','<i class="fa fa-circle"></i>');
		$StatusDetection= self::AddCommande($this,'Status la détection','detectionstatus',"info", 'binary');
		$CommandeDetection=self::AddCommande($this,'Activer la détection','detectionactif',"action", 'other');
		$CommandeDetection->setValue($StatusDetection->getId());
		$CommandeDetection->save();
		$CommandeDetection=self::AddCommande($this,'Desactiver la détection','detectionpause',"action", 'other');
		$CommandeDetection->setValue($StatusDetection->getId());
		$CommandeDetection->save();
		$CommandeDetection=self::AddCommande($this,'Activer/Desactiver la détection','detectionaction',"action", 'other','','MotionDetect');
		$CommandeDetection->setValue($StatusDetection->getId());
		$CommandeDetection->save();
    	}
	public function preRemove() {
		$this->RemoveThread();
    	}
	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) 
			return $replace;
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1)
			return '';
		$Cmds = '';
		$replace['#url#']= urlencode($this->getUrl());
		$cmdColor = ($this->getPrimaryCategory() == '') ? '' : jeedom::getConfiguration('eqLogic:category:' . $this->getPrimaryCategory() . ':' . $vcolor);
		$replace['#cmdColor#'] = $cmdColor;
		$action = '';
		$maphilightArea = '';
		$detect="";
		foreach ($this->getCmd() as $cmd) {
			if ($cmd->getIsVisible() == 1) {
				switch($cmd->getLogicalId()){
					case 'detect':	
						$replaceCmd['#MotionArea#'] = ($cmd->getConfiguration('DetectArea') == '') ? '[]' : $cmd->getConfiguration('DetectArea');
						$detect = template_replace($replaceCmd, $cmd->toHtml($_version, $cmdColor));
					break;
					case 'lastImg':
					case 'detectionactif':
					case 'detectionpause':
					case 'detectionstatus':
					case 'browseRecord':
					break;
					case 'maphilight':
						$replaceCmd['#areas#'] = $cmd->getConfiguration('maphilightArea');
						$maphilightArea .= template_replace($replaceCmd, $cmd->toHtml($_version, $cmdColor));
					break;
					default: 
						if ($cmd->getDisplay('hideOn' . $version) == 1) 
							continue;
						if ($cmd->getDisplay('forceReturnLineBefore', 0) == 1) 
							$action .= '<br/>';
						$action .= $cmd->toHtml($_version, $cmdColor);
						if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) 
							$action .= '<br/>';
					break;
				}
			}
		}
		$replace['#detect#']= $detect;
		$replace['#maphilightArea#'] = $maphilightArea;
		$replace['#action#'] = $action;
		if ($_version == 'dview' || $_version == 'mview') {
			$object = $this->getObject();
			$replace['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace['#name#'] : $replace['#name#'];
		}
		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}
		if ($_version == 'dview' || $_version == 'mview') {
			$object = $this->getObject();
			$replace['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace['#name#'] : $replace['#name#'];
		}
      		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'eqLogic', 'motion')));
  	}
	public static $_widgetPossibility = array('custom' => array(
	        'visibility' => true,
	        'displayName' => true,
	        'displayObjectName' => true,
	        'optionalParameters' => true,
	        'background-color' => true,
	        'text-color' => true,
	        'border' => true,
	        'border-radius' => true
	));
	public static function AddCommande($eqLogic,$Name,$_logicalId,$Type="info", $SubType='binary',$icone='',$Template='') {
		$Commande = $eqLogic->getCmd(null,$_logicalId);
		if (!is_object($Commande))
		{
			$Commande = new motionCmd();
			$Commande->setId(null);
			$Commande->setName($Name);
			$Commande->setLogicalId($_logicalId);
			$Commande->setEqLogic_id($eqLogic->getId());
			$Commande->setType($Type);
			$Commande->setSubType($SubType);
		}
     		$Commande->setTemplate('dashboard',$Template );
		$Commande->setTemplate('mobile', $Template);
		if ($icone!='')
			$Commande->setDisplay('icon',$icone);
		$Commande->save();
		return $Commande;
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//                                                                                                                                               //
	//                                                        Configuration de motion                                                                // 
	//                                                                                                                                               //
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public static function UpdateMotionConf() {
		$file='/usr/local/etc/motion/motion.conf';
		if($fp = fopen($file,"w")){
			fputs($fp,'daemon on');
			fputs($fp, "\n");
			fputs($fp,'setup_mode off');
			fputs($fp, "\n");
			fputs($fp,'logfile /etc/motion/motion.log');
			fputs($fp, "\n");
			fputs($fp,'log_level 6');
			fputs($fp, "\n");
			fputs($fp,'log_type all');
			fputs($fp, "\n");
			fputs($fp,'webcontrol_port '.config::byKey('Port', 'motion'));
			fputs($fp, "\n");
			fputs($fp,'webcontrol_localhost off');
			fputs($fp, "\n");
			fputs($fp,'webcontrol_html_output off');
			fputs($fp, "\n");
			foreach(eqLogic::byType('motion') as $Camera){		
				if($Camera->getIsEnable()){
					if(file_exists('/etc/motion/camera'.$Camera->getId().'.conf')){
						fputs($fp,'camera /etc/motion/camera'.$Camera->getId().'.conf');
						fputs($fp, "\n");
					}
				}
			}
		}
		fclose($fp);
		$file='/etc/motion/motion.log';
		if(!file_exists($file)){
			$fp = fopen($file,"w+");
			fclose($fp);
		}
	}
	private function simpleName ($chaine){
		// Le premier paramètre de la fonction iconv () est à adapter au codage de caractères utilisé par tes chaînes
		// (ex. : 'ISO-8859-1' si les chaînes de caractères utilisent ce codage)
		$string = iconv ('UTF-8', 'US-ASCII//TRANSLIT//IGNORE', $chaine);
		$string = preg_replace ('#[^.0-9a-z]+#i', '', $string);
		$string = strtolower ($string);
		//return $string;
		return $chaine;
	}
	private function WriteThread($file){
		log::add('motion','debug','Mise a jours du fichier: '.$file);	
		exec('sudo chmod 777 -R /etc/motion/');
		if($fp = fopen($file,"w+")){
			fputs($fp, 'text_left '.$this->simpleName($this->getName()));
			fputs($fp, "\n");
			fputs($fp, 'target_dir '.$this->getSnapshotDiretory(true));
			fputs($fp, "\n");
			$adress=network::getNetworkAccess('internal').'/plugins/motion/core/php/detect.php';
			fputs($fp, 'on_event_start curl -v --header "Connection: keep-alive" "' . $adress.'?id='.$this->getId().'&state=1"');
			fputs($fp, "\n");
			fputs($fp, 'on_picture_save curl -v --header "Connection: keep-alive" "' . $adress.'?id='.$this->getId().'&file='.$this->getConfiguration('picture_filename').'"');
			fputs($fp, "\n");
			fputs($fp, 'on_motion_detected curl -v --header "Connection: keep-alive" "' . $adress.'?id='.$this->getId().'&width=%i&height=%J&X=%K&Y=%L"');
			fputs($fp, "\n");
			fputs($fp, 'on_event_end curl -v --header "Connection: keep-alive" "' . $adress.'?id='.$this->getId().'&state=0"');
			fputs($fp, "\n");
			//Definition du parametre area_detect
			$AreaDetect='';
			foreach ($this->getCmd() as $Commande){
				if ($Commande->getLogicalId() =='detect')
					$AreaDetect.=$Commande->getConfiguration('area') ;
			}
			if ($AreaDetect!=''){
				fputs($fp, 'area_detect '.$AreaDetect);					
				fputs($fp, "\n");
				fputs($fp, 'on_area_detected curl -v --header "Connection: keep-alive" "' . $adress.'?id='.$this->getId().'&width=%i&height=%J&X=%K&Y=%L"');
				fputs($fp, "\n");
			}
			fputs($fp, 'netcam_keepalive force');
			fputs($fp, "\n");
			switch ($this->getConfiguration('cameraType')){
				case 'ip':
					fputs($fp, 'netcam_url '.trim($this->getConfiguration('cameraUrl')));
					fputs($fp, "\n");
					if($this->getConfiguration('cameraLogin')!='' || $this->getConfiguration('cameraPass')!=''){
						fputs($fp, 'netcam_userpass '.trim($this->getConfiguration('cameraLogin').':'.$this->getConfiguration('cameraPass')));
						fputs($fp, "\n");
					}
				break;
				case 'usb':
					fputs($fp, 'videodevice '.trim($this->getConfiguration('cameraUSB')));
					fputs($fp, "\n");
				break;
			}
			foreach($this->getConfiguration() as $key => $value)	{
				switch($key){
					case 'alertMessageCommand':
					case 'createtime':
					case 'updatetime':
					case 'plugin':
					case 'camera':
					case 'cameraUrl':
					case 'cameraLogin':
					case 'cameraPass':
					case 'analyse':
					case 'cameraMotionPort':
					case 'cameraUSB':
					case 'cameraType':
					case 'previousIsEnable':
					case 'previousIsVisible':
					break;
					case 'stream_motion':
					case 'stream_localhost':
					case 'netcam_tolerant_check':
					case 'auto_brightness':
					case 'switchfilter':
					case 'noise_tune':
					case 'emulate_motion':
					case 'output_debug_pictures':
					case 'ffmpeg_output_movies':
					case 'ffmpeg_output_debug_movies':
					case 'locate_motion_mode':
					case 'text_changes':
					case 'text_double':
					case 'ipv6_enabled':
						if($value==0)
							$value='off';
						else
							$value='on';
						fputs($fp,$key.' '.trim($value));
						fputs($fp, "\n");
					break;
					default:
						fputs($fp,$key.' '.trim($value));
						fputs($fp, "\n");
					break;
				}
			}
		}
	}
	public function NewThread() {
		self::deamon_start();
	}
	public  function RemoveThread() {
		self::deamon_start();
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//                                                                                                                                               //
	//                                                         Gestion du player motion                                                              // 
	//                                                                                                                                               //
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function getUrl(){
		if($this->getConfiguration('stream_motion')){
			if($this->getConfiguration('stream_port')!=''){
				$urlStream='http://';
				if($this->getConfiguration('stream_auth_method')!=0 && $this->getConfiguration('stream_authentication')!='')
					$urlStream.=$this->getConfiguration('stream_authentication').'@';
				$urlStream.=config::byKey('Host', 'motion').':'.$this->getConfiguration('stream_port');
				$urlStream.='/stream.mjpg';
			}
		}	
		else {
			$url=explode('://',$this->getConfiguration('cameraUrl'));
			switch ($this->getConfiguration('cameraType'))
			{
				case 'ip':
					if($this->getConfiguration('cameraLogin')!='' && $this->getConfiguration('cameraPass')!='')
						$urlStream=$url[0].'://'.$this->getConfiguration('cameraLogin').':'.$this->getConfiguration('cameraPass').'@'.$url[1];
					else
						$urlStream=$this->getConfiguration('cameraUrl');
				break;
			}
		}
		return $urlStream;
	}
	public function url_exists($url) {
		if (!$fp = curl_init($url)) return false;
		return true;
	}
	public function getSnapshotDiretory($Snapshot=false) {
		$directory=config::byKey('SnapshotFolder','motion');
		if(!file_exists($directory)){
			exec('sudo mkdir -p '.$directory);
			exec('sudo chmod 777 -R '.$directory);
		}
		if(substr($directory,-1)!='/')
			$directory.='/';
		if($Snapshot){
			$directory.=$this->getId().'/';
			if(!file_exists($directory)){
				exec('sudo mkdir -p '.$directory);
				exec('sudo chmod 777 -R '.$directory);
			}
		}
		$directory = calculPath($directory);
		if (!is_writable($directory)) 
			exec('sudo chmod 777 -R '.$directory);
		return $directory;
	}
	public static function removeRecord($file) {
		exec('sudo rm '. $file);
		log::add('motion','debug','Fichiers '.$file.' à été supprimée');
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//                                                                                                                                               //
	//                                                                 Gestion des détection                                                             // 
	//                                                                                                                                               //
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function CleanFolder() {
		$directory=$this->getSnapshotDiretory(true);
		$size = 0;
		foreach(scandir($directory, 1) as $file) {
			if(is_file($directory.$file) && $file != '.' && $file != '..'  && $file != 'lastsnap.jpg') {	
				if ($size>= config::byKey('SnapshotFolderSeize', 'motion')*1000000) //Valeur SnapshotFolderSeize en megaoctet
					self::removeRecord($directory.$file);
				else
					$size += filesize($directory.$file);
			}
		}
		log::add('motion','debug','Le dossier '.$directory.' est a '.$size);
	}
	public function getLastSnap($_file){
		$directory=$this->getSnapshotDiretory(true);
		$files=array();
		switch($this->getConfiguration("output_pictures")){
			case "off":
				log::add('motion','debug','Motion ne prend pas de photo');
			break;
			case "first":
				log::add('motion','debug','Motion séléctionne la premiere photo de la détéction');
				if(file_exists($directory.$file))
					$files[]=$directory.$_file;
			break;
			case "on":
			case "best":
			case "center":
				log::add('motion','debug','Envoie de la derniere photo prise par Motion');
				/*foreach (array_diff(scandir($directory,SCANDIR_SORT_DESCENDING), array('..', '.')) as $file) {
					$path_parts = pathinfo($file);
					if($path_parts['extension'] == 'jpg'){
						$files[]=$directory.$file;
						break;
					}
				}*/
				$pattern = '\.(jpg)$'; // check only file with these ext.          
				$newstamp = 0;            
				if ($handle = opendir($directory)) {               
					while (false !== ($file = readdir($handle)))  {            
						// Eliminate current directory, parent directory            
						if (ereg('^\.{1,2}$',$file)) continue;            
						// Eliminate other file not in pattern            
						if (! ereg($pattern,$file)) continue;            
						$timedat = filemtime($directory.$file);            
						if ($timedat > $newstamp) {
							$newstamp = $timedat;
							$files[0]=$directory.$file;
						}
					}
				}
					
			break;
		}
		return $files;
	}
	public function SendLastSnap($file){
		if($this->getConfiguration('alertMessageCommand')!=''){	
			$_options['title'] = '[Jeedom][Motion] Détéction sur la camera '.$this->getHumanName();
			$_options['message'] = 'La camera '.$this->getHumanName(). ' a détécté un mouvement. Voici le snapshot qui a ete pris';
			$_options['files']=$this->getLastSnap($file);
			log::add('motion','debug','Envoie d\'un message avec les derniere photo:'.json_encode($_options['files']));
			$cmds = explode('&&', $this->getConfiguration('alertMessageCommand'));
			foreach ($cmds as $id) {
				$cmd = cmd::byId(str_replace('#', '', $id));
				if (is_object($cmd)) {
					log::add('motion','debug','Envoie du message avec '.$cmd->getHumanName());
					$cmd->execute($_options);
				}
			}
		}
	}
	public function UpdateDetection($Parametres){
		if(isset($Parametres['file'])){
			$this->SendLastSnap($Parametres['file'].'.jpg');
			$this->CleanFolder();
		}
		$Commande=$this->getCmd('info','detect');
		if(is_object($Commande))
		{	
			foreach($this->getCmd('info','maphilight',null,true) as $maphilightCmd){
				if(is_object($maphilightCmd)){
					log::add('motion','debug','Mise a jours de l\'état de MapHiLight : '.$maphilightCmd->getHumanName());
					if(isset($Parametres['X']) && isset($Parametres['Y'])){
						$pointLocation = new pointLocation($maphilightCmd->getConfiguration('maphilightArea'));
						$IsInArea=$pointLocation->pointInPolygon(array("x" => $Parametres['X'],"y" => $Parametres['Y']));
						log::add('motion','debug','Les coordonées de la détection x='.$Parametres['X'].' y='.$Parametres['Y'].' sont =>'.$IsInArea);
						$maphilightCmd->setCollectDate('');
						if ($IsInArea=='outside')
							$maphilightCmd->event(false);
						else
							$maphilightCmd->event(true);
					}else
						$maphilightCmd->event(false);
					$maphilightCmd->save();
				}
			}
			log::add('motion','debug','Les coordonées de la détection x='.$Parametres['X'].' y='.$Parametres['Y'].' dans =>'.$Parametres['width'].'x'.$Parametres['height']);
			if(isset($Parametres['X']) && isset($Parametres['Y']) && isset($Parametres['width']) && isset($Parametres['height']))
				$coord=array($Parametres['X']+($Parametres['width']/2),
					     $Parametres['Y']+($Parametres['height']/2),
					     $Parametres['X']-($Parametres['width']/2),
					     $Parametres['Y']-($Parametres['height']/2));
			else
				$coord=array();
			$Commande->setConfiguration('DetectArea',json_encode($coord));
			$Commande->save();
			$this->refreshWidget();
			if(isset($Parametres['state'])){
				log::add('motion','debug','Détection sur la camera => '.$this->getName().' => '.$Parametres['state']);
				$Commande->event($Parametres['state']);
			}	
		}
		else
			log::add('motion','debug','Impossible de trouver la commande');
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//                                                                                                                                               //
	//                                                                 Gestion des démon                                                             // 
	//                                                                                                                                               //
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public static function deamonRunning() {
		$result = exec("ps aux | grep motion | grep -v grep | awk '{print $2}'");
		if($result != ""){
			return $result;
		}
        	return false;
	}
	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'motion_update';
		$return['progress_file'] = '/tmp/compilation_motion_in_progress';
		if (file_exists('/usr/local/src/motion/'))
			$return['state'] = 'ok';
		else
			$return['state'] = 'nok';
		return $return;
	}
	public static function dependancy_install() {
		if (file_exists('/tmp/compilation_motion_in_progress')) {
			return;
		}
		log::remove('motion_update');
		$cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh';
		$cmd .= ' >> ' . log::getPathToLog('motion_update') . ' 2>&1 &';
		exec($cmd);
	}
	public static function deamon_info() {
		$return = array();
		$return['log'] = 'motion';
		if(!self::deamonRunning())
			$return['state'] =  'nok';
		else
			$return['state'] =  'ok';
		$return['launchable'] = 'ok';
		return $return;
	}
	public static function deamon_start($_debug = false) {
		self::deamon_stop();	
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') 
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		if ($deamon_info['state'] != 'ok') {
			
			exec('sudo chmod -R 777 /usr/local/etc/motion/');
			exec('sudo rm /etc/motion/*');
			foreach(eqLogic::byType('motion') as $Camera){		
				$file='/etc/motion/camera'.$Camera->getId().'.conf';
				$Camera->WriteThread($file);
				$Camera->checkAndUpdateCmd('detectionstatus',true);
				self::UpdateMotionConf();
			}
			//exec('sudo chmod 777 /dev/video*');
			log::remove('motion');
			$file='/etc/motion/motion.log';
			if(!file_exists($file)){
				$fp = fopen($file,"w+");
				fclose($fp);
			}
			$cmd = 'sudo motion';
			$cmd .= ' >> ' . log::getPathToLog('motion') . ' 2>&1 &';
			exec($cmd);
		}
	}
	public static function deamon_stop() {
		exec('sudo pkill motion');
		if(file_exists('/etc/motion/motion.log'))
			exec('sudo rm /etc/motion/motion.log');
	}
	public function getStreamPort(){
		$Port=8081;
		while(true){
			$connection = @fsockopen('127.0.0.1', $Port,$errno, $errstr, 5);
			if (is_resource($connection)){
				fclose($connection);
				return $Port;
			}
			else
				$Port++;
		}
	}
}

class motionCmd extends cmd {
	public function preSave() {	
		if($this->getLogicalId() == '')
		{
			$this->setLogicalId('maphilight');
     			$this->setTemplate('dashboard', 'MotionDetectMapHiLight');
			$this->setTemplate('mobile', 'MotionDetectMapHiLight');
		}
	}
	public function execute($_options = array()) {
		$directory=$this->getEqLogic()->getSnapshotDiretory(true);
		$Host=config::byKey('Host', 'motion');
		$Port=config::byKey('Port', 'motion');	
		log::add('motion','debug','Connexion au motion '.$Host.':'.$Port);
		$MotionService = new MotionService($Host,$Port);
		$IdMotionCamera=$MotionService->getCameraId($directory);
		switch($this->getLogicalId())
		{
			case 'snapshot':
				log::add('motion','debug','Lancement d\'un snapshot');
				$return=$MotionService->Snapshot($IdMotionCamera);
			break;
			case 'makemovie':
				log::add('motion','debug','Lancement de l\enregistrement d\une Video');
				$return=$MotionService->MakeMovie($IdMotionCamera);
			break;
			case 'detectionaction':
				$return=$MotionService->getCameraStatut($IdMotionCamera);
				if ($return==1)
				{	
					$valeur='pause';
					$return=0;
				}
				else
				{	
					$valeur='start';
					$return=1;
				}
				$MotionService->detection($valeur,$IdMotionCamera);
				log::add('motion','debug','La detection sur la camera '.$this->getEqLogic()->getName().' est a :'.$return);
				$listener=cmd::byId($this->getValue());
				$listener->setCollectDate('');
				$listener->event($return);
				$listener->save();
			break;
			case 'detectionactif':
				$valeur='start';
				$return=1;
				$MotionService->detection($valeur,$IdMotionCamera);
				log::add('motion','debug','La detection sur la camera '.$this->getEqLogic()->getName().' est a :'.$return);
				$listener=cmd::byId($this->getValue());
				$listener->setCollectDate('');
				$listener->event($return);
				$listener->save();
			break;
			case 'detectionpause':
				$valeur='pause';
				$return=0;
				$MotionService->detection($valeur,$IdMotionCamera);
				log::add('motion','debug','La detection sur la camera '.$this->getEqLogic()->getName().' est a :'.$return);
				$listener=cmd::byId($this->getValue());
				$listener->setCollectDate('');
				$listener->event($return);
				$listener->save();
			break;
			case 'detectionstatus':
				$return=$MotionService->getCameraStatut($IdMotionCamera);
				log::add('motion','debug','La detection sur la camera '.$this->getEqLogic()->getName().' est a :'.$return);
				$this->setCollectDate('');
				$this->event($return);
				$this->save();
			break;
		}
	return $return;
	}
}
?>
