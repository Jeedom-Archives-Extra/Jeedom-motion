<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('desktop', 'jquery.maphilight.min', 'js', 'motion');
 ?>
<style>
.polygon {
  background-repeat: no-repeat;
  position:relative;
  width: 100%;
  height: 100%;
}
.cornerResizers {
  display: block;
  position: absolute;
  width: 6px;
  height: 6px;
  background-color: #333;
  border: 1px solid #fff;
  overflow: hidden;
  cursor: move;
}

.medianResizers {
  display: block;
  position: absolute;
  width: 4px;
  height: 4px;
  background-color: #fff;
  border: 1px solid #333;
  overflow: hidden;
  cursor: move;
}
</style>
<div class="polygon">
	<div id="div_displayArea"></div>
	<map name="map" id="map"></map>
</div>
<script>

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
			$('.polygon').append(data.result);
			$('.polygon').find('.directDisplay'+eqLogiqId).clone().appendTo(".AreaContent");
			$('.polygon').find('.eqLogic').remove('.eqLogic');
		}
	}
});
var coords=[];
for(var loop=0; loop<areas.length; loop=loop+2)
{
	var coord=[areas[loop],areas[loop+1]]
	coords.push(coord);
};
$('.directDisplay').on('click', function (e) {
	setCoordinates(e);
}); 
function hightlight() {
	$('.directDisplay').find('img').maphilight({
		stroke: true,
		fade: true, 
		strokeColor: '4F95EA',
		alwaysOn: true,
		fillColor: '365E71',
		fillOpacity: 0.2,
		shadow: true,
		shadowColor: '000000',
		shadowRadius: 5,
		shadowOpacity: 0.6,
		shadowPosition: 'outside'
	});
}
function setCoordinates(e) {
	var x = e.pageX;
	var y = e.pageY;
	var offset = $('.directDisplay').find('img').offset();
	x -= parseInt(offset.left);
	y -= parseInt(offset.top);
	if(x < 0) { x = 0; }
	if(y < 0) { y = 0; }
	var coord=[x,y];
	//alert(coord.join());
	coords.push(coord);
	updateCoords();
	areas=coords.join();
}
function updateCoords() {
	var shape = (coords.length <= 2) ? 'rect' : 'poly';
	$('#map').html($('<area>')
		.addClass("area") 
		.attr('shape',shape)
		.attr('coords',coords.join()));
	hightlight();
	editPolygon();
}
function editPolygon() {
	$('#div_displayArea').html('');
	for(var loop=0; loop< coords.length;loop++)
	{
		var coord=coords[loop];
		var coordX=parseInt(coord[0]);
		var coordY=parseInt(coord[1]);
		var cornerDiv=$('<div id="corner_' + loop + '" class="cornerResizers"></div>');
		var interDiv=$('<div id="inter_' + loop + '" class="medianResizers"></div>');
		
		// Add resizers
		$('#div_displayArea').append(cornerDiv);
		$('#div_displayArea').append(interDiv);
		
	  // Set and fix resizer dimensions if neeeded (only even values allowed)
		var cornerWidth = parseInt(cornerDiv.css('width').replace(/px$/,""));
		if (cornerWidth % 2 != 0) {
		  cornerWidth++;
		  cornerDiv.css('width', cornerWidth.toString() + 'px');
		}
		var cornerHeight = parseInt(cornerDiv.css('height').replace(/px$/,""));
		if (cornerHeight % 2 != 0) {
		  cornerHeight++;
		  cornerDiv.css('height', cornerHeight.toString() + 'px');
		}
		var interWidth = parseInt(interDiv.css('width').replace(/px$/,""));
		if (interWidth % 2 != 0) {
			interWidth++;
			interDiv.css('width', interWidth.toString() + 'px');
		}
		var interHeight = parseInt(interDiv.css('height').replace(/px$/,""));
		if (interHeight % 2 != 0) {
			interHeight++;
			interDiv.css('height', interHeight.toString() + 'px');
		}
		// Set corner resizer position
		cornerDiv.css('left', Math.round(coordX - (cornerWidth / 2) - 1) + 'px');
		cornerDiv.css('top', Math.round(coordY - (cornerHeight / 2) - 1) + 'px');

		// Set median resizer position
		if (loop == (coords.length - 1)) {
		interDiv.css('left', Math.round(((coordX + parseInt(coords[0][0])) / 2) - (interWidth / 2) - 1) + 'px');
		interDiv.css('top', Math.round(((coordY + parseInt(coords[0][1])) / 2) - (interHeight / 2) - 1) + 'px');
		} else {
		interDiv.css('left', Math.round(((coordX + parseInt(coords[loop+1][0])) / 2) - (interWidth / 2) - 1) + 'px');
		interDiv.css('top', Math.round(((coordY  + parseInt(coords[loop+1][1])) / 2) - (interHeight / 2) - 1) + 'px');
		}
		if (coords.length > 1) {
			// Setup dragging for corner resizer
			cornerDiv.draggable({
				scroll: false,
				opacity: 0.50,
				zIndex: 500,
				delay: 50,
				drag: function(e, ui) {
					// Get middle position of resizer
					var x = Math.round(ui.position.left) + (cornerWidth / 2) + 1;
					var y = Math.round(ui.position.top) + (cornerHeight / 2) + 1;
					coords[$(this).attr('id').explode('_')[1]][0]=x;
					coords[$(this).attr('id').explode('_')[1]][1]=y;
					updateCoords();
				},
			});

			// Catch right click on corner resizer and remove point
			cornerDiv.contextmenu(function(e) {
				coords.splice(parseInt($(this).attr('id').explode('_')[1]),1);					
				updateCoords();
			});
			
			// Setup dragging for corner resizer
			interDiv.draggable({
				scroll: false,
				opacity: 0.50,
				zIndex: 500,
				delay: 50,
				stop: function(e, ui) {
					// Get middle position of resizer
					var x = Math.round(ui.position.left) + (cornerWidth / 2) + 1;
					var y = Math.round(ui.position.top) + (cornerHeight / 2) + 1;
					var coord=[x,y];
					coords.splice(parseInt($(this).attr('id').explode('_')[1])+1,0,coord);					
					updateCoords();
				},
			});
		}
	}
}
</script>
