<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
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
<div class="AreaContent"></div>
<div id="debug"></div>
<script>
var eqLogiqId=$('.eqLogicAttr[data-l1key=id]').val();
 $('body').on('click','.Area',function (e) {
	var AreaSelect=parseInt($(this).attr('id').split('_')[1])+1;
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
$.ajax({
	type: 'POST',
	url: 'plugins/motion/core/ajax/motion.ajax.php',
	data: {
		action: 'WidgetHtml',
		cameraId:eqLogiqId
	},
	dataType: 'json',
	global: false,
	error: function (request, status, error) {
		handleAjaxError(request, status, error, $('#div_updatepreRequisAlert'));
	},
	success: function (data) {
		if (data.result)
		{
			$('.AreaContent').append(data.result);
			$('.AreaContent').find('.ImgVideoFlux'+eqLogiqId).clone().appendTo(".AreaContent");
			$('.AreaContent').find('.eqLogic').remove('.eqLogic');
			
			$('.AreaContent').find('.ImgVideoFlux'+eqLogiqId).load(function() {
				//alert('width: '+$(this).width()+' height:'+$(this).height());
				$('.AreaContent').find('.Areas').css('width', $(this).width());
				$('.AreaContent').find('.Areas').css('height',$(this).height());
				if ($('.AreaContent').find('.Areas').length==0){
					$('.AreaContent').append($('<center>').append($('<span>').addClass('Areas')));
					var offsetImg = $('.AreaContent').find('.ImgVideoFlux'+eqLogiqId).offset();
					var offsetArea =$('.AreaContent').find('.Areas').offset();
					$('.AreaContent').find('.Areas').css('left',offsetImg.left - offsetArea.left);
					$('.AreaContent').find('.Areas').css('top', offsetImg.top - offsetArea.top);
					for(var loop=0; loop<9; loop++)
					{
						$('.AreaContent').find('.Areas')
							.append($('<div>')
								.addClass('Area')
								.attr('id','area_'+loop)
								.css('width', '33%')
								.css('height', '33%'));
						if(areas.indexOf(loop+1)>=0)
							$('.AreaContent').find('#area_'+loop).addClass('Select');
					};
				}
			});
		}
	}
});
</script>
