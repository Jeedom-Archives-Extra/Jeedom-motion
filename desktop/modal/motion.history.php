<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('id') == '') {
	throw new Exception(__('L\'id ne peut etre vide', __FILE__));
}
$camera = motion::byId(init('id'));
if (!is_object($camera)) {
	throw new Exception(__('L\'équipement est introuvable : ', __FILE__) . init('id'));
}
if ($camera->getEqType_name() != 'motion') {
	throw new Exception(__('Cet équipement n\'est pas de type motion : ', __FILE__) . $camera->getEqType_name());
}
$directory=$camera->getSnapshotDiretory(true);
$url=dirname(__FILE__);
if(substr($url,-1)!='/')
	$url.='/';
foreach(explode('/',$url) as $section)
	$url.='../';	
if(substr($directory,0,1)=='/')
	$url=substr($url,0,-1);
$directory=$url.$directory;
$files = array();
$offset=strpos($camera->getConfiguration('snapshot_filename'),'-')+1;
$StartAnnee=strpos($camera->getConfiguration('snapshot_filename'),'%Y')-$offset;
$StartMoi=strpos($camera->getConfiguration('snapshot_filename'),'%m')+2-$offset;
$StartJour=strpos($camera->getConfiguration('snapshot_filename'),'%d')+2-$offset;
$StartHeure=strpos($camera->getConfiguration('snapshot_filename'),'%H')+2-$offset;
$StartMinute=strpos($camera->getConfiguration('snapshot_filename'),'%M')+2-$offset;
$StartSeconde=strpos($camera->getConfiguration('snapshot_filename'),'%S')+2-$offset;
foreach (ls($directory, '*') as $file) {
	if($file != 'lastsnap.jpg'){
		$offset=strpos($file,'-')+1;
		$time = substr($file,$StartHeure+$offset,2).':'.substr($file,$StartMinute+$offset,2).':'.substr($file,$StartSeconde+$offset,2);
		$date = substr($file,$StartJour+$offset,2).'/'.substr($file,$StartMoi+$offset,2).'/'.substr($file,$StartAnnee+$offset,4);
		if ($date == '') {
			continue;
		}
		if (!isset($files[$date])) {
			$files[$date] = array();
		}
		if(strpos($file,'.jpg')>0)
			$type='photo';
		else
			$type='video';
		$files[$date][$time][$type] = $file;
	}
}
krsort($files);
?>
<div id='div_cameraRecordAlert' style="display: none;"></div>
<?php
echo '<a class="btn btn-danger bt_removeCameraFile pull-right" data-all="1"><i class="fa fa-trash-o"></i> {{Tout supprimer}}</a>';
echo '<a class="btn btn-success  pull-right" target="_blank" href="core/php/downloadFile.php?pathfile=' . urlencode($directory . '*') . '" ><i class="fa fa-download"></i> {{Tout télécharger}}</a>';
?>
<?php
$i = 0;
foreach ($files as $date => &$file) {
	$cameraName = str_replace(' ', '-', $camera->getName());
	echo '<div class="div_dayContainer">';
	echo '<legend>';
	echo '<a class="btn btn-xs btn-danger bt_removeCameraFile" data-day="1" data-filename="' . $camera->getId() . '/' . $cameraName . '_' . $date . '*"><i class="fa fa-trash-o"></i> {{Supprimer}}</a> ';
	echo '<a class="btn btn-xs btn-success" target="_blank"  href="core/php/downloadFile.php?pathfile=' . urlencode($directory . $cameraName . '_' . $date . '*') . '" ><i class="fa fa-download"></i> {{Télécharger}}</a> ';
	echo $date;
	echo ' <a class="btn btn-xs btn-default toggleList"><i class="fa fa-chevron-down"></i></a> ';
	echo '</legend>';
	echo '<div class="cameraThumbnailContainer">';
	krsort($file);
	foreach ($file as $time => $filename) {
		$fontType = 'fa-camera';
		if (isset($filename['video'])){
			$fontType = 'fa-video-camera';
			$i++;
		}
		if (isset($filename['video'])){
			echo '<div class="cameraDisplayCard" style="background-color: #e7e7e7;padding:5px;height:167px;">';
			echo '<center><i class="fa ' . $fontType . ' pull-right"></i>  ' . str_replace('-', ':', $time) . '</center>';
			echo '<video class="displayVideo" width="150" height="100" controls loop data-src="core/php/downloadFile.php?pathfile=' . urlencode($directory . $filename['video']) . '" style="cursor:pointer"><source src="core/php/downloadFile.php?pathfile=' . urlencode($directory . $filename['video']) . '">Your browser does not support the video tag.</video>';
			echo '<center style="margin-top:5px;"><a target="_blank" href="core/php/downloadFile.php?pathfile=' . urlencode($directory . $filename['video']) . '" class="btn btn-success btn-xs" style="color : white"><i class="fa fa-download"></i></a>';
			echo ' <a class="btn btn-danger bt_removeCameraFile btn-xs" style="color : white" data-filename="' . $directory . $filename['video'] . '"><i class="fa fa-trash-o"></i></a></center>';
			echo '</div>';
		}
		if (isset($filename['photo'])){	
			echo '<div class="cameraDisplayCard" style="background-color: #e7e7e7;padding:5px;height:167px;">';
			echo '<center><i class="fa ' . $fontType . ' pull-right"></i>  ' . str_replace('-', ':', $time) . '</center>';
			echo '<center><img class="img-responsive cursor displayImage lazy" src="plugins/motion/core/img/no-image.png" data-original="core/php/downloadFile.php?pathfile=' . urlencode($directory . $filename['photo']) . '" width="150"/></center>';
			echo '<center style="margin-top:5px;"><a target="_blank" href="core/php/downloadFile.php?pathfile=' . urlencode($directory . $filename['photo']) . '" class="btn btn-success btn-xs" style="color : white"><i class="fa fa-download"></i></a>';
			echo ' <a class="btn btn-danger bt_removeCameraFile btn-xs" style="color : white" data-filename="' . $directory . $filename['photo'] . '"><i class="fa fa-trash-o"></i></a></center>';
			echo '</div>';
		}
	}
	echo '</div>';
	echo '</div>';
}
?>
<script>
    $('.cameraThumbnailContainer').packery({gutter : 5});
    $('.displayImage').on('click', function() {
        $('#md_modal2').dialog({title: "Image"});
        $('#md_modal2').load('index.php?v=d&plugin=motion&modal=motion.displayImage&src='+ $(this).attr('src')).dialog('open');
    });
	$('.displayVideo').on('click', function() {
        $('#md_modal2').dialog({title: "Vidéo"});
        $('#md_modal2').load('index.php?v=d&plugin=motion&modal=motion.displayVideo&src='+ $(this).attr('data-src')).dialog('open');
    });
	$('.bt_removeCameraFile').on('click', function() {
		if(typeof $(this).attr('data-filename') != 'undefined'){
			RemoveFile($(this).attr('data-filename'));
		}else {
			$(this).parent().parent().find('.bt_removeCameraFile').each(function() {
				if(typeof $(this).attr('data-filename') != 'undefined')
					RemoveFile($(this).attr('data-filename'));
			});
		}
		$(this).parent().parent().remove();
	});
	function RemoveFile(filename){	
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/motion/core/ajax/motion.ajax.php", // url du fichier php
			data: {
				action: "removeRecord",
				file: filename,
				cameraId:<?php echo $camera->getId();?>,
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error,$('#div_cameraRecordAlert'));
			},
			success: function(data) { // si l'appel a bien fonctionné
				if (data.state != 'ok') {
					$('#div_cameraRecordAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$(".cameraThumbnailContainer").slideToggle(1);
				$('.cameraThumbnailContainer').packery({gutter : 5});
				$(".cameraThumbnailContainer").slideToggle(1);
			}
		});
	}
    $(".cameraThumbnailContainer").slideToggle(1);
    $(".cameraThumbnailContainer").eq(0).slideToggle(1);
    $('.toggleList').on('click', function() {
        $(this).closest('.div_dayContainer').find(".cameraThumbnailContainer").slideToggle("slow");
    });

    $("img.lazy").lazyload({
      container: $("#md_modal")
  });
</script>
