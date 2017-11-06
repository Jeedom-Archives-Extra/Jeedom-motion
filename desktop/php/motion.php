<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'motion');
$eqLogics = eqLogic::byType('motion');
?>
<style>
	output { 
		position: absolute;
		background-image: linear-gradient(top, #444444, #999999);
		width: 40px; 
		height: 30px; 
		text-align: center; 
		border-radius: 10px; 
		display: inline-block; 
		font: bold 15px/30px Georgia;
		bottom: 120%;
		left: 0;
		margin-left: -1%;
	}
	output:after { 
		content: "";
		position: absolute;
		width: 0;
		height: 0;
		border-top: 10px solid #999999;
		border-left: 5px solid transparent;
		border-right: 5px solid transparent;
		top: 100%;
		left: 50%;
		margin-left: -5px;
		margin-top: -1px;
	}
</style>
<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une camera}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-10 col-md-7 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">	
		<legend>{{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; " >
				<center>
					<i class="fa fa-plus-circle" style="font-size : 7em;color:#406E88;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#406E88">
					<center>Ajouter</center>
				</span>
			</div>
			<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="height: 120px; margin-bottom: 10px; padding: 5px; border-radius: 2px; width: 160px; margin-left: 10px; position: absolute; left: 170px; top: 0px; background-color: rgb(255, 255, 255);">
				<center>
			      		<i class="fa fa-wrench" style="font-size : 5em;color:#767676;"></i>
			    	</center>
			    	<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>Configuration</center></span>
			</div>
			<div class="cursor logDemon" style="background-color : #ffffff;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
			  <center>
				<i class="fa fa-heartbeat" style="font-size : 5em;color:#767676;"></i>
			  </center>
				<center>{{Information sur l'activité de motion}}</center>
			</div>
		</div>
        <legend>{{Les caméras surveillées}}</legend>
		<div class="eqLogicThumbnailContainer">
			<?php
			if (count($eqLogics) == 0) {
				echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore de camera, cliquez sur Ajouter une camera pour commencer}}</span></center>";
			} else {
			?>
				<?php
				foreach ($eqLogics as $eqLogic) {
					echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
					echo "<center>";
					echo '<img src="plugins/motion/plugin_info/motion_icon.png" height="105" width="95" />';
					echo "</center>";
					echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
					echo '</div>';
				}
				?>
			<?php } ?>
		</div>
    </div>
	<div class="eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation">
				<a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay">
					<i class="fa fa-arrow-circle-left"></i>
				</a>
			</li>
			<li role="presentation" class="active">
				<a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">
					<i class="fa fa-tachometer"></i> Equipement</a>
			</li>
			<li role="presentation" class=""><a href="#CaptureDeviceOptions" role="tab" data-toggle="tab" aria-expanded="false"> {{Option de capture}}</a></li>
			<li role="presentation" class=""><a href="#StreamOptions" role="tab" data-toggle="tab" aria-expanded="false"> {{Option de stream}}</a></li>
			<li role="presentation" class=""><a href="#TrackingOptions" role="tab" data-toggle="tab" aria-expanded="false"> {{Option de Tracking}}</a></li>
			<li role="presentation" class=""><a href="#RoundRobin" role="tab" data-toggle="tab" aria-expanded="false"> {{Round Robin}}</a></li>
			<li role="presentation" class=""><a href="#MotionDetectionSettings" role="tab" data-toggle="tab" aria-expanded="false"> {{Parametre de Detection}}</a></li>
			<li role="presentation" class=""><a href="#ImageFileOutput" role="tab" data-toggle="tab" aria-expanded="false"> {{Photo options}}</a></li>
			<li role="presentation" class=""><a href="#FFMPEGoptions" role="tab" data-toggle="tab" aria-expanded="false"> {{Video options}}</a></li>
			<li role="presentation" class=""><a href="#TextDisplay" role="tab" data-toggle="tab" aria-expanded="false"> {{Affichage de text}}</a></li>
			<li role="presentation" class=""><a href="#Filenames" role="tab" data-toggle="tab" aria-expanded="false"> {{Nommage des fichiers}}</a></li>
			<li role="presentation" class=""><a href="#GlobalNetworkOptions" role="tab" data-toggle="tab" aria-expanded="false"> {{Reseau}}</a></li>			
			<li role="presentation" class=""><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-list-alt"></i>Commandes</a></li>
		</ul>
		<div>
			<a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> Sauvegarder</a>
			<a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> Supprimer</a>
			<a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> Configuration avancée</a>
		</div>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab"> 
						<br/>
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-md-5 control-label">{{Nom de l'équipement template}}</label>
								<div class="col-md-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement template}}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-5 control-label" >{{Objet parent}}</label>
								<div class="col-md-7">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
										foreach (object::all() as $object) {
											echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" ></label>
								<div class="col-sm-9">
									<label>{{Activer}}</label>
									<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable"/>
									<label>{{Visible}}</label>
									<input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-5 control-label">{{Catégorie}}</label>
								<div class="col-md-8">
									<?php
									foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
										echo '</label>';
									}
									?>

								</div>
							</div>
							<div class="form-group">
								<label class="col-md-5 control-label">{{Commande d'alerte (mail, slack...)}}</label>
								<div class="col-md-8">
									<div class="input-group">
										<input class="form-control input-sm eqLogicAttr" data-l1key="configuration" data-l2key="alertMessageCommand" placeholder="{{Commande mail pour l'envoi d'une capture}}">
										<span class="input-group-btn">
											<a class="btn btn-success btn-sm listCmdActionMessage" id="bt_selectActionMessage">
												<i class="fa fa-list-alt"></i>
											</a>
										</span>
									</div>
								</div>      
							    </div> 
						</div>
					</div>
			<div role="tabpanel" class="tab-pane" id="StreamOptions">
				<br/>
				<legend>Option de streaming</legend>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-5 control-label">{{Streaming}}</label>
						<div class="col-md-7">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="stream_motion" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Streaming uniquement en local}}</label>
						<div class="col-md-7">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="stream_localhost" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Port de streaming de la camera}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="stream_port" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{substream_port (%)}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="substream_port" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Qualité de l'image (%)}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="stream_quality" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{FrameRate}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="stream_maxrate" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Methode d'autentification}}</label>
						<div class="col-md-7">
							<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="stream_auth_method">
								<option value="0">Désactivé</option>
								<option value="1">Basique</option>
								<option value="2">MD5 digest</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{username:password}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="stream_authentication" />
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="CaptureDeviceOptions">
			<br/>
			<legend>Option des equipement de capture</legend>
			<div class="form-horizontal">
				<div class="MotionPlugin" style="{display:none;}">
					<div class="form-group">
						<label class="col-md-5 control-label">{{Type de camera}}</label>
						<div class="col-md-7">
							<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cameraType">
								<option value="ip">IP</option>
								<option value="usb">USB</option>
								<option value="raspi">RaspiCam</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Camera}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cameraUSB">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Url de la Camera}}</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cameraUrl">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Login de connexion a la Camera}}</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cameraLogin">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Mots de pass de la Camera}}</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cameraPass">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{choisir la palette préférable d'être utilisé par le mouvement}}</label>
					<div class="col-md-7">
						<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="v4l2_palette">
							<option value="0">V4L2_PIX_FMT_SN9C10X : 'S910'</option>
							<option value="1">V4L2_PIX_FMT_SBGGR16 : 'BYR2'</option>
							<option value="2">V4L2_PIX_FMT_SBGGR8  : 'BA81'</option>
							<option value="3">V4L2_PIX_FMT_SPCA561 : 'S561'</option>
							<option value="4">V4L2_PIX_FMT_SGBRG8  : 'GBRG'</option>
							<option value="5">V4L2_PIX_FMT_SGRBG8  : 'GRBG'</option>
							<option value="6">V4L2_PIX_FMT_PAC207  : 'P207'</option>
							<option value="7"> V4L2_PIX_FMT_PJPG   : 'PJPG'</option>
							<option value="8">V4L2_PIX_FMT_MJPEG   : 'MJPEG'</option>
							<option value="9"> V4L2_PIX_FMT_JPEG   : 'JPEG'</option>
							<option value="10">V4L2_PIX_FMT_RGB24  : 'RGB3'</option>
							<option value="11"> V4L2_PIX_FMT_SPCA501 : 'S501'</option>
							<option value="12"> V4L2_PIX_FMT_SPCA505 : 'S505'</option>
							<option value="13"> V4L2_PIX_FMT_SPCA508 : 'S508'</option>
							<option value="14">V4L2_PIX_FMT_UYVY    : 'UYVY'</option>
							<option value="15">V4L2_PIX_FMT_YUYV    : 'YUYV'</option>
							<option value="16">V4L2_PIX_FMT_YUV422P : '422P'</option>
							<option value="17">V4L2_PIX_FMT_YUV420  : 'YU12'</option>
							<option value="18">V4L2_PIX_FMT_Y10	: 'YU10'</option>
							<option value="19">V4L2_PIX_FMT_Y12	: 'YU12'</option>
							<option value="20">V4L2_PIX_FMT_GREY	: 'GREY'</option>
							<option value="21">V4L2_PIX_FMT_H264	: 'H264'</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Chanel d'entrer}}</label>
					<div class="col-md-7">
						<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="input">
							<option value="-1">-1</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{La norme vidéo à utiliser (seulement pour la capture vidéo et TV tuner cartes)}}</label>
					<div class="col-md-7">
						<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="norm">
							<option value="0">PAL</option>
							<option value="1">NTSC</option>
							<option value="2">SECAM</option>
							<option value="3">PAL NC no colour</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Remplacez la fréquence pour la webcam)}}</label>
					<div class="col-md-7">
						<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="power_line_frequency">
							<option value="-1">Par default</option>
							<option value="0">Désactivé</option>
							<option value="1">50hz</option>
							<option value="2">60hz</option>
							<option value="3">Auto</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Fréquence à régler le tuner (kHz) (uniquement pour les cartes tuner TV)}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="frequency" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Rotation de l'image (degrés)}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="rotate"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Largeur de l'image capturée (pixel)}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="width" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Hauteur de l'image capturée (pixel)}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="height" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Nombre maximum d'image par seconde}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="framerate"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Délai minimum entre les captures (s)}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="minimum_frame_time" />
					</div>
				</div>
				<div class="form-group netCam">
					<label class="col-md-5 control-label">{{Réglez les contrôles jpeg moins strictes}}</label>
					<div class="col-md-7">
						<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="netcam_tolerant_check" />
					</div>
				</div>
				<div class="form-group netCam">
					<label class="col-md-5 control-label">{{RTSP connection uses TCP to communicate to the camera. Can prevent image corruption.}}</label>
					<div class="col-md-7">
						<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="rtsp_uses_tcp" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Name of camera to use if you are using a camera accessed through OpenMax/MMAL}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="mmalcam_name" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Camera control parameters (see raspivid/raspistill tool documentation)}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="mmalcam_control_params" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Laissez mouvement réguler la luminosité d'un dispositif vidéo}}</label>
					<div class="col-md-7">
						<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="auto_brightness" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Luminosité}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="brightness" max="255" min="0" step="1" name="brightness">
						<output for="brightness" onforminput="value = brightness.valueAsNumber;"></output>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Contraste}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="contrast" max="255" min="0" step="1" name="contrast">
						<output for="contrast" onforminput="value = contrast.valueAsNumber;"></output>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Saturation}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="saturation" max="255" min="0" step="1" name="saturation">
						<output for="saturation" onforminput="value = saturation.valueAsNumber;"></output>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">{{Réglez la teinte}}</label>
					<div class="col-md-7">
						<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="hue" max="255" min="0" step="1" name="hue">
						<output for="hue" onforminput="value = hue.valueAsNumber;"></output>
					</div>
				</div>
			</div>
		</div>
			<div role="tabpanel" class="tab-pane" id="RoundRobin">
				<br/>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-5 control-label">{{Nombre de cadres de capturer à chaque étape}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="roundrobin_frames" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Nombre d'images à ignorer avant chaque étape}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="roundrobin_skip" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Essayez de filtrer le bruit généré}}</label>
						<div class="col-md-7">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="switchfilter" />
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="MotionDetectionSettings">
				<br/>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-5  control-label">Règle la sensibilité de la détection de mouvement</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="threshold" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Règle automatiquement le seuil de bruit}}</label>
						<div class="col-md-7">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="threshold_tune" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Seuil de bruit pour la détection de mouvement}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="noise_level"  />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Règle automatiquement le seuil de bruit}}</label>
						<div class="col-md-7">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="noise_tune" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Fichier PGM à utiliser comme un masque de sensibilité.}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="mask_file" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{PGM file to completely mask out a area of image.# Full path name to. (Default: not defined).}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="mask_privacy"  />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Créer dynamiquement un fichier de masque pendant le fonctionnement}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="smart_mask_speed" max="10" min="0" step="1" name="smart_mask_speed">
							<output for="smart_mask_speed" onforminput="value = smart_mask_speed.valueAsNumber;"></output>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Ignorer les changements d'intensité lumineuse soudaine massives (% de l'image)}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="lightswitch" max="100" min="0" step="1" name="lightswitch">
							<output for="lightswitch" onforminput="value = lightswitch.valueAsNumber;"></output>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Cadres doivent contenir le mouvement au moins le nombre d'images spécifié}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="minimum_motion_frames" max="5" min="0" step="1"  name="minimum_motion_frames">
							<output for="minimum_motion_frames" onforminput="value = minimum_motion_frames.valueAsNumber;"></output>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Indique le nombre de photos pré-capturées avant le mouvement}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="pre_capture" max="5" min="0" step="1" name="pre_capture">
							<output for="pre_capture" onforminput="value = pre_capture.valueAsNumber;"></output>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Nombre d'images capturer après le mouvement}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="post_capture" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Temps sans détection de mouvement qui déclenche la fin d'un événement.}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="event_gap"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Temps maximal d'une video}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="max_movie_time"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Toujours enregistrer des images même si il n'y avait pas de mouvement}}</label>
						<div class="col-md-7">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="emulate_motion" />
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="ImageFileOutput">
			<br/>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-5 control-label">{{Prendre une image a la détection}}</label>
						<div class="col-md-7">
								<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="output_pictures" >
									<option value="on">On</option>
									<option value="off">Off</option>
									<option value="first">La première</option>
									<option value="best">La meilleur</option>
									<option value="center">Au millieu</option>
								</select>
						</div>
					</div>					
					<div class="form-group">
						<label class="col-md-5 control-label">{{Type d'image}}</label>
						<div class="col-md-7">
								<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="picture_type" >
									<option value="jpeg">jpeg</option>
									<option value="webp">webp</option>
									<option value="ppm">ppm</option>
								</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Images de sortie avec seulement les pixels objet en mouvement}}</label>
						<div class="col-md-7">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="output_debug_pictures" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{La qualité à utiliser pour la compression JPEG}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="quality"/>
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="FFMPEGoptions">  
				<br/>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-5  control-label">Utiliser ffmpeg pour encoder les vidéos mpeg en temps réel :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="ffmpeg_output_movies" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Creer un video avec juste les pixel en mouvement :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="ffmpeg_output_debug_movies" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Timelapse de la video}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ffmpeg_timelapse"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Mode de timelapse}}</label>
						<div class="col-md-7">
							<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ffmpeg_timelapse_mode" >
								<option value="hourly">Toutes les heures</option>
								<option value="daily">Tous les jours</option>
								<option value="weekly-sunday">Tous les dimanche</option>
								<option value="weekly-monday">Tous les lundi</option>
								<option value="monthly">Tous les mois</option>
								<option value="manual">Manuel</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Bitrate utilisé par le codeur ffmpeg}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ffmpeg_bps"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">{{Enables and defines variable bitrate for the ffmpeg encoder.}}</label>
						<div class="col-md-7">
							<input class="eqLogicAttr form-control" type="range" data-l1key="configuration" data-l2key="ffmpeg_variable_bitrate" max="100" min="0" step="1" name="ffmpeg_variable_bitrate">
							<output for="ffmpeg_variable_bitrate" onforminput="value = ffmpeg_variable_bitrate.valueAsNumber;"></output>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Format des vidéos enregistrées. Le mpeg4 produira des fichiers .avi mais d\'autres formats sont disponibles. :</label>
						<div class="col-md-7 ">
							<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ffmpeg_video_codec">
								<option value = "mpeg4"> mpeg4 - vous donne les fichiers avec l'extension .avi </option>
								<option value = "msmpeg4"> msmpeg4 - vous donne également les fichiers MPEG4. </option>
								<option value = "swf"> swf - vous donne un film flash avec l'extension .swf </option>
								<option value = "flv"> flv - vous donne une vidéo flash avec l'extension .flv </option>
								<option value = "ffv1"> ffv1 - FF codec vidéo 1 pour Lossless Encoding (expérimental) </option>
								<option value = "mov"> mov - QuickTime (depuis 3.2.10). </option>
								<option value = "mpg"> mpg - Creates mpg file with mpeg-2 encoding.</option>
								<option value = "mp4"> mp4 - MPEG-4 Part 14 H264 encoding</option>
								<option value = "mkv"> mkv - Matroska H264 encoding</option>
								<option value = "hevc"> hevc - H.265 / HEVC (High Efficiency Video Coding)</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Utilisez ffmpeg pour désentrelacer vidéo :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="ffmpeg_duplicate_frames" />
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="TextDisplay">
			  <br/>
					# %Y = year, %m = month, %d = date,<br/>
					# %H = hour, %M = minute, %S = second,<br/>
					# %v = event, %q = frame number, %t = thread (camera) number,<br/>
					# %D = changed pixels, %N = noise level,<br/>
					# %i and %J = width and height of motion area,<br/>
					# %K and %L = X and Y coordinates of motion center<br/>
					# %C = value defined by text_event
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-5  control-label">Localiser et tracer un cadre autour de l'objet en mouvement.</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="locate_motion_mode" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Choisir le style de cadre :</label>
						<div class="col-md-7 ">
							<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="locate_motion_style" >
								<option value="box">Cadre</option>
								<option value="redbox">Cadre rouge</option>
								<option value="cross">Croix</option>
								<option value="redcross">Croix Rouge</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Choisir le texte a afficher a droite :</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="text_right"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Dessinez le nombre de changements pixel sur les images :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="text_changes" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Texte a ajouter lors d'un evenement :</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="text_event"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Utiliser ffmpeg pour encoder les vidéos mpeg en temps réel :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="text_double" />
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="Filenames">
					# %Y = year, %m = month, %d = date,<br/>
					# %H = hour, %M = minute, %S = second,<br/>
					# %v = event, %q = frame number, %t = thread (camera) number,<br/>
					# %D = changed pixels, %N = noise level,<br/>
					# %i and %J = width and height of motion area,<br/>
					# %K and %L = X and Y coordinates of motion center<br/>
					# %C = value defined by text_event<br/>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-md-5  control-label">Nom du fichier snapshot :</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="snapshot_filename"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Nom du fichier de snapshot lors d'une detection :</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="picture_filename" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Nom du fichier de video lors d'une detection :</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="movie_filename"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Nom du fichier de video lors d'un timelapse :</label>
						<div class="col-md-7 ">
							<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="timelapse_filename"/>
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="GlobalNetworkOptions">
				<div class="form-horizontal">
					<div class="form-group" id="Capture_device_options">
						<label class="col-md-5  control-label">Utiliser IPV6 :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="ipv6_enabled" />
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="TrackingOptions">
				<div class="form-horizontal">
					<div class="form-group" >
						<label class="col-md-5  control-label">The generic type enables the definition of motion center and motion size to be used with the conversion specifiers for options like on_motion_detected :</label>
						<div class="col-md-7 ">
							<select class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_type" >
							<option value=0>none (default)</option>
							<option value=1>stepper</option>
							<option value=2>iomojo</option>
							<option value=3>pwc</option>
							<option value=4>generic</option>
							<option value=5>uvcvideo</option>
							<option value=6>servo</option>
							</select>
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Enable auto tracking (default: off) :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="track_auto" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Serial port of motor (default: none) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_port" />
						</div>
					</div>

					<div class="form-group" >
						<label class="col-md-5  control-label">Motor number for x-axis (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_motorx" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Set motorx reverse (default: 0) :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="track_motorx_reverse" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label"># Motor number for y-axis (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr"data-l1key="configuration" data-l2key="track_motory" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Set motory reverse (default: 0) :</label>
						<div class="col-md-7 ">
							<input type="checkbox" class="eqLogicAttr" data-size="mini" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="track_motory_reverse" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Maximum value on x-axis (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_maxx" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Minimum value on x-axis (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_minx" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Maximum value on y-axis (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_maxy" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Minimum value on y-axis (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_miny" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Center value on x-axis (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_homex" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Center value on y-axis (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_homey" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">ID of an iomojo camera if used (default: 0) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_iomojo_id" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Angle in degrees the camera moves per step on the X-axis with auto-track (default: 10):</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_step_angle_x" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Angle in degrees the camera moves per step on the Y-axis with auto-track (default: 10) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_step_angle_y" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label"> Delay to wait for after tracking movement as numberof picture frames (default: 10) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_move_wait" />
						</div>
					</div>
					<div class="form-group" >
						<label class="col-md-5  control-label">Speed to set the motor to (stepper motor option) (default: 255) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_speed" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5  control-label">Number of steps to make (stepper motor option) (default: 40) :</label>
						<div class="col-md-7 ">
							<input class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="track_stepsize" />
						</div>
					</div>					
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab"> 
				<a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une zone de detection}}</a><br/><br/>
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{}}</th>
							<th>{{Nom}}</th>
							<th>{{Action}}</th>
							<th>{{}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		</div>
</div>
<?php include_file('desktop', 'motion', 'js', 'motion'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
