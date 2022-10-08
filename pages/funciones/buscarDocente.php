<?php
	function buscarDocente($vConexion,$id){
		$Docente=array();
		$SQL = "SELECT D.Id, D.Apellido, D.Nombre, D.DNI, D.FechaNacim, D.NroLegajoJunta, D.Titulo, D.FechaEscalafon, D.Categoria, C.Denominacion AS DenCategoria, D.Contrasenia, D.Mail, D.UltIngreso
               FROM docentes D, categorias C
                WHERE D.Id='$id' AND D.Categoria=C.Id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                	$Docente['ID'] = $data['Id'];
            		$Docente['APELLIDO'] = $data['Apellido'];
            		$Docente['NOMBRE'] = $data['Nombre'];
            		$Docente['DNI'] = $data['DNI'];
            		$Docente['FECHANACIM'] = $data['FechaNacim'];
            		$Docente['NROLEGAJOJUNTA'] = $data['NroLegajoJunta'];
            		$Docente['TITULO'] = $data['Titulo'];
            		$Docente['FECHAESCALAFON'] = $data['FechaEscalafon'];
            		$Docente['CATEGORIA'] = $data['Categoria'];
                    $Docente['DENCATEGORIA'] = $data['DenCategoria'];
            		$Docente['MAIL'] = $data['Mail'];
            		$Docente['ULTINGRESO'] = $data['UltIngreso'];
                    $Docente['CONTRASENIA'] = $data['Contrasenia'];
                }
        return $Docente;
	}

	function eliminarElDocente($vConexion,$id){
		$SQL = "DELETE FROM dbcalificacionesproa.docentes WHERE Id='$id'";
		if (mysqli_query($vConexion, $SQL)) {
			return "El docente se eliminó correctamente";
		} else {
			return "Error al intentar eliminar docente";
		}
	}
    function docenteExiste($vConexion,$DNI){
        $Docente=array();
        $mensaje = "";
        $SQL = "SELECT Id
               FROM docentes
                WHERE DNI='$DNI'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Docente['ID'] = $data['Id'];
                    $mensaje = "El DNI ingresado ya existe en la base de datos, y su nro de identificacion es: ".$Docente['ID'];
                }
        
        return $mensaje;
    }
    function docenteExisteOtro($vConexion,$DNI, $Id){
        $Docente=array();
        $mensaje = "";
        $SQL = "SELECT Id
               FROM docentes
                WHERE DNI='$DNI' AND Id!='$Id'";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Docente['ID'] = $data['Id'];
                    $mensaje = "El DNI ingresado corresponde al docente nro: ".$Docente['ID'];
                }
        
        return $mensaje;
    }
    function buscarDocenteXDNI($vConexion,$DNI){
        $Docente=array();
        $SQL = "SELECT D.Id, D.Apellido, D.Nombre, D.DNI, D.FechaNacim, D.NroLegajoJunta, D.Titulo, D.FechaEscalafon, C.Denominacion AS Categoria, D.Contrasenia, D.Mail, D.UltIngreso
               FROM docentes D, categorias C
                WHERE D.DNI='$DNI' AND D.Categoria=C.Id";
                 $rs = mysqli_query($vConexion, $SQL);
                if($data = mysqli_fetch_array($rs)) {
                    $Docente['ID'] = $data['Id'];
                    $Docente['APELLIDO'] = $data['Apellido'];
                    $Docente['NOMBRE'] = $data['Nombre'];
                    $Docente['DNI'] = $data['DNI'];
                    $Docente['FECHANACIM'] = $data['FechaNacim'];
                    $Docente['NROLEGAJOJUNTA'] = $data['NroLegajoJunta'];
                    $Docente['TITULO'] = $data['Titulo'];
                    $Docente['FECHAESCALAFON'] = $data['FechaEscalafon'];
                    $Docente['CATEGORIA'] = $data['Categoria'];
                    $Docente['MAIL'] = $data['Mail'];
                    $Docente['ULTINGRESO'] = $data['UltIngreso'];
                }
        return $Docente;
    }
?>