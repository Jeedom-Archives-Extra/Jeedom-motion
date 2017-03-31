<?php
set_time_limit(120);
$url=urldecode($_REQUEST['url']);
$timeout = time();
if(!$src=@fopen($url,"rb")){
	log::add('motion','debug','Impossible d\'ouvrir le flux video '.$url);
	$path = 'plugins/motion/core/template/icones/no-image-blanc.png';
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	echo 'data:image/' . $type . ';base64,' . base64_encode($data);
}else {
  header('Max-Age: 0');
  header('Expires: 0');
  header('Cache-Control: no-cache, private');
  header('Pragma: no-cache');
  #header('Content-Type: video/mpeg'); 
  header('Content-Type: multipart/x-mixed-replace; boundary=BoundaryString');
  while ( !feof($src) ) {
    echo  fread($src, 2048);
    if ( (time() - $timeout) >= 120 ) {
    	exit();
    }
  }
}
?>
