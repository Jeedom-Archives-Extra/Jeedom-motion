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
	<div class="Areas"></div>
</div>
<script>
$('.AreaContent .CameraSnap .CameraSnap').load(function() {		 
 	/*for(var loop=0; loop<9; loop++){		
 		$('.AreaContent .Areas')		
 			.append($('<div>')		
 				.addClass('Area')		
 				.attr('id',loop));		
 		/*if(areas.lenght>0){		
 			if(areas.indexOf(loop+1)>=0)		
 				$('.AreaContent').find('#area_'+loop).addClass('Select');		
 		}*/		
 	//};		
 	/*var offsetImg = $('.AreaContent').find('img').offset();		
 	var offsetArea =$('.AreaContent').find('.Areas').offset();		
 	$('.AreaContent').find('.Areas').css('width', $(this).width());		
 	$('.AreaContent').find('.Areas').css('height',$(this).height());		
 	$('.AreaContent').find('.Area').css('width', $(this).width()/3);		
 	$('.AreaContent').find('.Area').css('height',$(this).height()/3);		
 	$('.AreaContent').find('.Areas').css('left',offsetImg.left - offsetArea.left);		
 	$('.AreaContent').find('.Areas').css('top', offsetImg.top - offsetArea.top);*/		
 });		
// $('body').on('click','.Area',function (e) {		
 	//var AreaSelect=parseInt($(this).attr('id').split('_')[1])+1;		
 	/*if (areas.indexOf(AreaSelect)>=0)		
 	{		
 		$(this).removeClass('Select');		
 		areas=areas.toString().replace(AreaSelect.toString(),'');		
 	}		
 	else		
 	{		
 		$(this).addClass('Select');		
 		areas=areas+AreaSelect;		
 	}*/		
 //}); 
</script>
