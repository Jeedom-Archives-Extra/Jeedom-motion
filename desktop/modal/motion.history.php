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
$directory=config::byKey('SnapshotFolder','motion');
if(!file_exists($directory)){
	exec('sudo mkdir -p '.$directory);
	exec('sudo chmod 777 -R '.$directory);
}
if(substr($directory,-1)!='/')
	$directory.='/';
$directory = calculPath($directory);
$url=dirname(__FILE__);
if(substr($url,-1)!='/')
	$url.='/';
foreach(split('/',$url) as $section)
	$url.='../';	
if(substr($directory,0,1)=='/')
	$url=substr($url,0,-1);
motion::UploadImg($camera->getId());
$files = array();
$offset=strpos($camera->getConfiguration('snapshot_filename'),'-')+1;
$StartAnnée=strpos($camera->getConfiguration('snapshot_filename'),'%Y')-$offset;
$StartMoi=strpos($camera->getConfiguration('snapshot_filename'),'%m')+2-$offset;
$StartJour=strpos($camera->getConfiguration('snapshot_filename'),'%d')+2-$offset;
$StartHeure=strpos($camera->getConfiguration('snapshot_filename'),'%H')+2-$offset;
$StartMinute=strpos($camera->getConfiguration('snapshot_filename'),'%M')+2-$offset;
$StartSeconde=strpos($camera->getConfiguration('snapshot_filename'),'%S')+2-$offset;
foreach (ls($directory, '*') as $file) {
	if($file != 'lastsnap.jpg'){
		$offset=strpos($file,'-')+1;
		$time = substr($file,$StartHeure+$offset,2).':'.substr($file,$StartMinute+$offset,2).':'.substr($file,$StartSeconde+$offset,2);
		$date = substr($file,$StartJour+$offset,2).'/'.substr($file,$StartMoi+$offset,2).'/'.substr($file,$StartAnnée+$offset,4);
		if ($date == '') {
			continue;
		}
		if (!isset($files[$date])) {
			$files[$date] = array();
		}
		if(strpos($file,'.avi')>0)
			$type='video';
		else
			$type='photo';
		$files[$date][$time][$type] = $file;
	}
}
krsort($files);
?>
<div id='div_cameraRecordAlert' style="display: none;"></div>
<?php
echo '<a class="btn btn-danger bt_removeCameraFile pull-right" data-all="1"><i class="fa fa-trash-o"></i> {{Tout supprimer}}</a>';
?>
<?php
foreach ($files as $date => &$file) {
	echo '<div class="div_dayContainer">';
	echo '<legend>';
	echo '<a class="btn btn-xs btn-danger bt_removeCameraFile" data-day="1"><i class="fa fa-trash-o"></i> {{Supprimer}}</a> ';
	echo $date;
	echo '</legend>';
	echo '<div class="cameraThumbnailContainer">';
	krsort($file);
	foreach ($file as $time => $filename) {
		if (isset($filename['photo'])){
			echo '<div class="cameraDisplayCard" style="background-color: #e7e7e7;padding:5px;height:167px;">';
			echo '<center>' . $time . '</center>';
			echo '<center><img class="img-responsive cursor displayImage" src="core/php/downloadFile.php?pathfile=' . urlencode($url.$directory.$filename['photo']) . '" width="150"/></center>';
			echo '<center style="margin-top:5px;"><a href="core/php/downloadFile.php?pathfile=' . urlencode($url.$directory.$filename['photo']) . '" class="btn btn-success btn-xs" style="color : white"><i class="fa fa-download"></i></a>';
			echo ' <a class="btn btn-danger bt_removeCameraFile btn-xs" style="color : white" data-filename="' . $directory . $filename['photo'] . '"><i class="fa fa-trash-o"></i></a></center>';
			echo '</div>';
		}
		if (isset($filename['video'])){
			echo '<div class="cameraDisplayCard" style="background-color: #e7e7e7;padding:5px;height:167px;">';
			echo '<center>' . $time . '</center>';
			echo '<center><i class="img-responsive cursor displayImage fa fa-file-video-o fa-5x" src="' . urlencode($url$directory . $filename['video']) . '" width="150"></i></center>';
			echo '<center style="margin-top:5px;"><a href="core/php/downloadFile.php?pathfile=' . urlencode($url.$directory . $filename['video']) . '" class="btn btn-success btn-xs" style="color : white"><i class="fa fa-download"></i></a>';
			echo ' <a class="btn btn-danger bt_removeCameraFile btn-xs" style="color : white" data-filename="' . $directory . $filename['video'] . '"><i class="fa fa-trash-o"></i></a></center>';
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
        $('#md_modal2').dialog({title: "Visualisation des prises de vue"});
        $('#md_modal2').load('index.php?v=d&plugin=motion&modal=motion.displayImage&src='+ $(this).attr('src')).dialog('open');
    });
	    $('.bt_removeCameraFile').on('click', function() {
        var card;
        if($(this).attr('data-day') == 1){
            card = $(this).closest('.div_dayContainer');
			var date=$(this).attr('data-date')
			$('.bt_removeCameraFile[data-day='+date+']').each(function() {
				if($(this).attr('data-filename'))
					RemoveFile($(this).attr('data-filename'));
			});
        }
        else if($(this).attr('data-all') == 1){
            card = $('.div_dayContainer');
			$('.bt_removeCameraFile').each(function() {
				if($(this).attr('data-filename'))
					RemoveFile($(this).attr('data-filename'));
			});
        }
		else 
		{
			card = $(this).closest('.cameraDisplayCard')
			RemoveFile($(this).attr('data-filename'));
		}
		card.remove();
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
				$('.cameraThumbnailContainer').packery({gutter : 5});
			}
		});
	}
 </script>
