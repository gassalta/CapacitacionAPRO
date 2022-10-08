<?php
	function verificarCamposTutor(){
		if (empty($_POST['ApellidoTutor']) || empty($_POST['NombreTutor']) || empty($_POST['DNITutor']) || empty($_POST['telefonoTutor']) || empty($_POST['NacionalidadTutor']) || empty($_POST['MailTutor'])) {
			return "Todos los campos obligatorios deben estar completos";
		} else{
			if (strlen($_POST['DNITutor']) != 8) {
				return "El DNI debe contener 8 digitos";
			}

			if (strlen($_POST['MailTutor']) < 15) {
				return "El mail ingresado no es valido";
			}
		}
	}
?>