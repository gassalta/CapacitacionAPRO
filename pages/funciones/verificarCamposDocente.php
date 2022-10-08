<?php
	function verificarCamposDoc(){
		if (empty($_POST['Apellido']) || empty($_POST['Nombre']) || empty($_POST['DNI']) || empty($_POST['FechaNacim']) || empty($_POST['Titulo']) || empty($_POST['categorias']) || empty($_POST['Mail'])) {
			return "Todos los campos obligatorios deben estar completos";
		} else{
			if (strlen($_POST['DNI']) != 8) {
				return "El DNI debe contener 8 dígitos";
			}

			if (strlen($_POST['Mail']) < 22) {
				return "El mail ingresado no es válido";
			}
			$pos = strpos($_POST['Mail'], '@escuelasproa.edu.ar');
			if ($pos === false) {
				return "El mail ingresado no es válido. Debe ingresar el mail institucional";
			}
			if(!empty($_POST['FechaEscalafon']) && strtotime($_POST['FechaNacim'])>strtotime($_POST['FechaEscalafon'])) {
				return "La fecha de Escalafón no puede ser anterior a la de Nacimiento";
			}
		}
	}
?>