<?php
	function verificarCamposEstud(){
		if (empty($_POST['Apellido']) || empty($_POST['Nombre']) || empty($_POST['DNI']) || empty($_POST['telefono']) || empty($_POST['fechaNacim']) || empty($_POST['Nacionalidad']) || empty($_POST['lugarNacim']) || empty($_POST['Mail']) || empty($_POST['domicilio']) || empty($_POST['Barrio'])) {
			return "Todos los campos obligatorios deben estar completos";
		} else{
			if (strlen($_POST['DNI']) != 8) {
				return "El DNI debe contener 8 digitos";
			}

			if (strlen($_POST['Mail']) < 22) {
				return "El mail ingresado no es valido";
			}

			$pos = strpos($_POST['Mail'], '@escuelasproa.edu.ar');
			if ($pos === false) {
				return "El mail ingresado no es válido. Debe ingresar el mail institucional";
			}
			
			if(!empty($_POST['fechaPreinscripcion']) && strtotime($_POST['fechaNacim'])>strtotime($_POST['fechaPreinscripcion'])) {
				return "La fecha de Preinscripcion no puede ser anterior a la de Nacimiento";
			}
		}
	}
?>