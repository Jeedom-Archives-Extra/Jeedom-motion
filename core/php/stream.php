<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
set_time_limit(120);
$url=urldecode($_REQUEST['url']);
$timeout = time();
$src=@fopen($url,"rb");
if(!$src){
	log::add('motion','debug','Impossible d\'ouvrir le flux video '.$url);
	$src=@fopen('plugins/motion/core/template/icones/no-image-blanc.png',"rb");
}
if($src){
	header('Max-Age: 0');
	header('Expires: 0');
	header('Cache-Control: no-cache, private');
	header('Pragma: no-cache');
	#header('Content-Type: video/mpeg'); 
	header('Content-Type: multipart/x-mixed-replace; boundary=BoundaryString');

	while ( !feof($src) ) {
		echo  fread($src, 2048);
		/*if ( (time() - $timeout) >= 120 ) {
			exit();
		}*/
	}
}
?>
