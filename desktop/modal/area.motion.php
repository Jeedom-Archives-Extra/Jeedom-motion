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
onImgLoad('img', function(){
 	var offsetImg = $(this).offset();		
 	var offsetArea =$('.AreaContent .Areas').offset();		
 	$('.AreaContent .Areas').css('width', $(this).width());		
 	$('.AreaContent .Areas').css('height',$(this).height());		
 	$('.AreaContent .Area').css('width', $(this).width()/3);		
 	$('.AreaContent .Area').css('height',$(this).height()/3);		
 	$('.AreaContent .Areas').css('left',offsetImg.left - offsetArea.left);		
 	$('.AreaContent .Areas').css('top', offsetImg.top - offsetArea.top);	
	$.each(areas,function(area){
		$('.AreaContent .Area[id='+area+']').addClass('Select');
	});
});
$('body').on('click','.Area',function() {		
 	var AreaSelect=$(this).attr('id');		
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
	alert(areas);
 }); 
</script>
