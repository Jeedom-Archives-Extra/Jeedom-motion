<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$eqLogic=eqLogic::byId(init('id'));
?>
<style>
.AreaContent
{
	position:relative;
	margin:0;
	padding:0;
}
.Areas
{
	position: absolute;
	left:0;
	top:0;
	margin:0;
	padding:0;
	z-index:1;
}
.Area
{
	display:inline-block;
	border-style: solid;
    border-width: 2px;
	margin-right: -2px;
	margin-top: -2px;
	margin-bottom: -2px;
	padding:0;
	font-size: 0;
	word-spacing: -1;
}
.Select
{
	background-color: blue;
	opacity: 0.5
}
</style>
<div class="AreaContent">
	<div class="Snapshot">
		<img class="CameraSnap" src="<?php echo $eqLogic->getSnapshot();?>"/>
	</div>
	<div class="Areas">
		<div class="Area" id="1"></div>
		<div class="Area" id="2"></div>
		<div class="Area" id="3"></div>
		<div class="Area" id="4"></div>
		<div class="Area" id="5"></div>
		<div class="Area" id="6"></div>
		<div class="Area" id="7"></div>
		<div class="Area" id="8"></div>
		<div class="Area" id="9"></div>
	</div>
</div>
<script>
//$('.AreaContent .CameraSnap .CameraSnap').load(function() {		 
	
 	var offsetImg = $('.AreaContent .CameraSnap .CameraSnap').offset();		
 	var offsetArea =$('.AreaContent .Areas').offset();		
 	$('.AreaContent .Areas').css('width', $('.AreaContent .CameraSnap .CameraSnap').width());		
 	$('.AreaContent .Areas').css('height',$('.AreaContent .CameraSnap .CameraSnap').height());		
 	$('.AreaContent .Area').css('width', $('.AreaContent .CameraSnap .CameraSnap').width()/3);		
 	$('.AreaContent .Area').css('height',$('.AreaContent .CameraSnap .CameraSnap').height()/3);		
 	$('.AreaContent .Areas').css('left',offsetImg.left - offsetArea.left);		
 	$('.AreaContent .Areas').css('top', offsetImg.top - offsetArea.top);*	
 //});		
 $('body').on('click','.Area',function (e) {		
 	var AreaSelect=parseInt($(this).attr('id'))+1;		
 	if (areas.indexOf(AreaSelect)>=0)		
 	{		
 		$(this).removeClass('Select');		
 		areas=areas.toString().replace(AreaSelect.toString(),'');		
 	}		
 	else		
 	{		
 		$(this).addClass('Select');		
 		areas=areas+AreaSelect;		
 	}	
 }); 
</script>
