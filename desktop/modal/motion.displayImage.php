<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
echo '<center>';
echo '<img class="img-responsive" src="' . init('src') . '" />';
echo '<script type="text/javascript" src="http://www.supportduweb.com/page/js/flashobject.js"></script>
<div id="player_9132" style="display:inline-block;">
	<a href="http://get.adobe.com/flashplayer/">{{Le plugin flashplayer n\'est pas installé, cliquer ici}}</a></a>
</div>
<script type="text/javascript">
	var flashvars_9132 = {};
	var params_9132 = {
		quality: "high",
		wmode: "transparent",
		bgcolor: "#ffffff",
		allowScriptAccess: "always",
		allowFullScreen: "true",
		flashvars: "fichier=' . init('src') . '&auto_play=true"
	};
	var attributes_9132 = {};
	flashObject("http://flash.supportduweb.com/flv_player/v1_18.swf", "player_9132", "720", "405", "8", false, flashvars_9132, params_9132, attributes_9132);
</script>';
echo '<center>';
?>
