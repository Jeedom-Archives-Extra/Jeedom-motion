$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
var areas;
$("#bt_selectActionMessage").on('click', function () {
    jeedom.cmd.getSelectModal({cmd: {type: 'action',subType : 'message'}}, function (result) {
        $(".eqLogicAttr[data-l2key=alertMessageCommand]").atCaret('insert',result.human);
    });
});
$('body').on('change',"input[type='range']",function() {
	var el = $(this);
	var width = el.width();
	var offset = 1.3;
	var newPoint = (el.val() - el.attr("min")) / (el.attr("max") - el.attr("min"));
	if (newPoint < 0) 
		newPoint = 0; 
	else if (newPoint > 1) 
		newPoint = width; 
	else {
		newPoint = width * newPoint + offset ; 
		//offset -= newPoint;
	}
	el.next("output")
		.css({
			left: newPoint,
      			 marginLeft: offset + "%"
		})
		.text(el.val());
})
.trigger('change');
$.ajax({
	type: "POST",
	timeout:8000, 
	url: "plugins/motion/core/ajax/motion.ajax.php",
	data: {
		action: "SearchCamera",
	},
	dataType: 'json',
	error: function(request, status, error) {
		handleAjaxError(request, status, error);
	},
	success: function(data) { 
		if (data.state != 'ok') {
			$('#div_alert').showAlert({message: data.result, level: 'danger'});
			return;
		}
		if (data.result!=false)
		{
		var Select=$('<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="camera">');
			for (var i in data.result) {
				Select.append(
					$('<option>').attr('value',data.result[i].Id)
					.text(data.result[i].Nom));
			}
		$('.CameraSelect').html(Select);
		}
	}
});	
$('.logDemon').on('click', function() {
    $('#md_modal').dialog({
		title: "{{log}}",
		height: 600,
		width: 850});
    $('#md_modal').load('index.php?v=d&modal=motion.logDemon&plugin=motion&type=motion').dialog('open');
});
$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraType]').on('change', function() {
	switch($(this).val())
	{
		case 'ip':
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraUSB]').closest('.form-group').hide();
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraUrl]').closest('.form-group').show();
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraLogin]').closest('.form-group').show();
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraPass]').closest('.form-group').show();
		break;
		case 'usb':
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraUSB]').closest('.form-group').show();
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraUrl]').closest('.form-group').hide();
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraLogin]').closest('.form-group').hide();
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraPass]').closest('.form-group').hide();
		break;
	}
});	
function UpdateCameraUSB(){
		$.ajax({
		type: "POST",
		timeout:8000, 
		url: "plugins/motion/core/ajax/motion.ajax.php",
		data: {
			action: "SearchUSBCamera",
			cameraAnalyse: $('.eqLogicAttr[data-l1key=configuration][data-l2key=analyse]').val(),
		},
		dataType: 'json',
		error: function(request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function(data) { 
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			var OldValue=$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraUSB]').val();
			var selectCameraUSB=$('<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cameraUSB">');
			selectCameraUSB.append($('<option value="">Aucun</option>'));
			for (var i in data.result) {
				selectCameraUSB.append($('<option value="' + data.result[i].value + '">').text(data.result[i].name + ' (' + data.result[i].value + ')'));
			}
			var div=$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraUSB]').parent();
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraUSB]').remove();
			div.append(selectCameraUSB);
			$('.eqLogicAttr[data-l1key=configuration][data-l2key=cameraUSB] option[value="'+OldValue+'"]').prop('selected', true);
			
		}
	});	
}
$('body').on('click','.editMaphilightArea', function() {
	var _this=this;
	if($(this).closest('.cmd').find('.cmdAttr[data-l1key=configuration][data-l2key=maphilightArea]').val()!= undefined)
		areas=$(this).closest('.cmd').find('.cmdAttr[data-l1key=configuration][data-l2key=maphilightArea]').val().split(',');
	
	$('#md_modal').dialog({
		title: "{{Editer la zone de detection}}",
		height: 600,
		width: 550});
	$('#md_modal').load('index.php?v=d&plugin=motion&modal=maphilight.motion')
	$('#md_modal').dialog('open');
	$("#md_modal").dialog('option', 'buttons', {
		"Annuler": function() {
			$(this).dialog("close");
		},
		"Valider": function() {
			$(_this).closest('.cmd').find('.cmdAttr[data-l1key=configuration][data-l2key=maphilightArea]').val(areas);
			$(this).dialog('close');
		}
	});
});  
$('body').on('click','.editArea', function() {
	var _this=this;
	if($(this).closest('.cmd').find('.cmdAttr[data-l1key=configuration][data-l2key=area]').val()!= undefined)
		areas=$(this).closest('.cmd').find('.cmdAttr[data-l1key=configuration][data-l2key=area]').val();
	
	$('#md_modal').dialog({
		title: "{{Editer la zone de detection}}",
		height: 600,
		width: 550});
	$('#md_modal').load('index.php?v=d&plugin=motion&modal=area.motion&id='. $('.eqLogicAttr[data-l1key=id]').val());
	$('#md_modal').dialog('open');
	$("#md_modal").dialog('option', 'buttons', {
		"Annuler": function() {
			$(this).dialog("close");
		},
		"Valider": function() {
			$(_this).closest('.cmd').find('.cmdAttr[data-l1key=configuration][data-l2key=area]').val(areas);
			$(this).dialog('close');
		}
	});
});   
function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
        var _cmd = {};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
	var tr =$('<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">');
  	tr.append($('<td>')
		.append($('<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove">'))
		.append($('<i class="fa fa-arrows-v pull-left cursor bt_sortable" style="margin-top: 9px;">')));
	tr.append($('<td>')
		.append($('<div>')
			.append($('<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">'))
			.append($('<input class="cmdAttr form-control input-sm" data-l1key="name" value="' + init(_cmd.name) + '" placeholder="{{Name}}" title="Name">')))
		.append($('<div>')
			.append($('<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon">')
				.append($('<i class="fa fa-flag">')).text('Icone'))
			.append($('<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;">'))));

	var action=$('<td>');
		
		action.append($('<div style="width : 40%;display : inline-block;">')
			.append($('<input type="hidden" class="cmdAttr" data-l1key="type" value="info" />'))
			.append($('<input type="hidden" class="cmdAttr" data-l1key="subType" value="binary" />'))
			.append($('<span>')
				.append($('<label>')
					.text('{{Historiser}}'))
				.append($('<input type="checkbox" class="cmdAttr" data-size="mini" data-label-text="{{Historiser}}" data-l1key="isHistorized" checked/>')))
			.append($('</br>'))
			.append($('<span>')
				.append($('<label>')
					.text('{{Afficher}}'))
				.append($('<input type="checkbox" class="cmdAttr" data-size="mini" data-label-text="{{Afficher}}" data-l1key="isVisible" checked/>'))));
	if (_cmd.logicalId =='detect')
	{
		action.append($('<div style="width : 40%;display : inline-block;">')
			.append($('<a class="cmdAction btn btn-primary editArea">')
				.append($('<i class="fa fa-check">'))
				.text('{{Editer}}')
				.trigger("click"))
			.append($('<input type="hidden" class="cmdAttr" data-l1key="configuration" data-l2key="area">')));
	}
	if (_cmd.logicalId =='maphilight')
	{
		action.append($('<div style="width : 40%;display : inline-block;">')
			.append($('<a class="cmdAction btn btn-primary editMaphilightArea">')
				.append($('<i class="fa fa-check">'))
				.text('{{Editer}}')
				.trigger("click"))
			.append($('<input type="hidden" class="cmdAttr" data-l1key="configuration" data-l2key="maphilightArea">')));
	}
	tr.append(action);
	var parmetre=$('<td>');
	if (is_numeric(_cmd.id)) {
		parmetre.append($('<a class="btn btn-default btn-xs cmdAction" data-action="test">')
			.append($('<i class="fa fa-rss">')
				.text('{{Tester}}')));
	}
	parmetre.append($('<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure">')
		.append($('<i class="fa fa-cogs">')));
	tr.append(parmetre);
	$('#table_cmd tbody').append(tr);
	$('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
	UpdateCameraUSB();
	}
