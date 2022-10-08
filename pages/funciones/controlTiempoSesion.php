<?php
function tiempoCumplido() {
	$fechaAct = date('Y-m-d h:i:s');
	$fechaIngreso = strtotime($_SESSION['UltIngreso']);
	$diferencia = abs(strtotime($fechaIngreso)-strtotime($fechaAct));//$fechaAct->diff($fechaIngreso);
	$horas = floor($diferencia/3600);
	$_SESSION['horas'] = $horas;
	if (/*$diferencia->y > 0 || $diferencia->m > 0 || $diferencia->d > 0 || $diferencia->h > 2*/ $horas < 2) {
		return true;
	} else {
		return false;
	}
}

?>