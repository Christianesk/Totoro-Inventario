
function alertaError($mensaje) {
	noty({
		text : $mensaje,
		type : 'error',
		dismissQueue : true,
		layout : 'topRight',
		theme : 'relax',
		timeout : 5000,
	});
}

function alertaInfo($mensaje) {
	noty({
		text : $mensaje,
		type : 'information',
		dismissQueue : true,
		layout : 'topRight',
		theme : 'relax',
		timeout : 5000,
	});
}


function alertaExito($mensaje) {
	noty({
		text : $mensaje,
		type : 'success',
		dismissQueue : true,
		layout : 'topRight',
		theme : 'relax',
		timeout : 5000,
	});
}
function alertaWarn($mensaje) {
	noty({
		text : $mensaje,
		type : 'warning',
		dismissQueue : true,
		layout : 'topRight',
		theme : 'relax',
		timeout : 5000,
	});
}