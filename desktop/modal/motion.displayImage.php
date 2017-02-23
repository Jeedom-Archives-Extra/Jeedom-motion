<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
//echo init('src');
if(strpos(init('src'),'.jpg')>0){
	echo '<center><img class="img-responsive" src="' . init('src') . '" /></center>';
else
	?>
<center>
	<video width="320" height="240" autoplay>
	  	<source src="' . init('src') . '" type="video/mp4">
	  	<source src="' . init('src') . '" type="video/ogg">
	  	<source src="' . init('src') . '" type="video/flv">
		Your browser does not support the video tag.
	</video>
</center>
<?php
}
?>
