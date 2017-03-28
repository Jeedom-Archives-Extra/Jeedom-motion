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
	margin:-2px;
	padding:-1px;
	display:inline-block;
	border-style: solid;
    border-width: 2px;
}
.Select
{
	background-color: blue;
	opacity: 0.5
}
</style>
<div class="AreaContent">
	<img class="CameraSnap" src="<?php echo 'plugins/motion/core/php/stream.php?url='. urlencode($eqLogic->getUrl());?>"/>
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
var onImgLoad = function(selector, callback){
    $(selector).each(function(){
        if (this.complete || /*for IE 10-*/ $(this).height() > 0) {
            callback.apply(this);
        }
        else {
            $(this).on('load', function(){
                callback.apply(this);
            });
        }
    });
};
onImgLoad('.CameraSnap', function(){	
 	$('.AreaContent .Areas').css('width', $(this).width());		
 	$('.AreaContent .Areas').css('height',$(this).height());		
 	$('.AreaContent .Area').css('width', $(this).width()/3);		
 	$('.AreaContent .Area').css('height',$(this).height()/3);		
	$.each(areas.split(''),function(index,area){
		$('.AreaContent .Area[id='+area+']').addClass('Select');
	});
});

$('body').on('click','.AreaContent .Area',function(event) {
  	$( event.target ).toggleClass( "Select" );		
 	var AreaSelect=$(this).attr('id');		
 	if (areas.indexOf(AreaSelect)>=0)		
 	{		
 		//$(this).removeClass('Select');		
 		areas=areas.toString().replace(AreaSelect.toString(),'');
 	}		
 	else		
 	{		
 		//$(this).addClass('Select');		
 		areas=areas+AreaSelect;		
 	}	
 }); 
</script>
