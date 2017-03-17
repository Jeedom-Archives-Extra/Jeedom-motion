<?php
set_time_limit(120);
 
$port = $_GET['port']; 
 
$src = fopen(urldecode($_REQUEST['url'], 'rb'));
$timeout = time();
 
header('Max-Age: 0');
header('Expires: 0');
header('Cache-Control: no-cache, private');
header('Pragma: no-cache');
#header('Content-Type: video/mpeg'); 
header('Content-Type: multipart/x-mixed-replace; boundary=BoundaryString');
 
while ( !feof($src) ) 
{
	echo  fread($src, 2048);
	if ( (time() - $timeout) >= 120 ) {
		exit();
	}
}
?>
