<?php //Terminar de adaptar
function ListarTutores($vConexion) {
    $Listado=array();

    	$SQL = "SELECT T.id, T.apellido, T.nombre, T.dni, T.telefono, T.mail, T.ocupacion, T.telTrabajo, T.nacionalidad, N.nacion AS nacion
        FROM tutores T, nacionalidades N
        WHERE T.nacionalidad = N.id
        ORDER BY apellido";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['APELLIDO'] = $data['apellido'];
            $Listado[$i]['NOMBRE'] = $data['nombre'];
            $Listado[$i]['DNI'] = $data['dni'];
            $Listado[$i]['TELEFONO'] = $data['telefono'];
            $Listado[$i]['MAIL'] = $data['mail'];
            $Listado[$i]['OCUPACION'] = $data['ocupacion'];
            $Listado[$i]['TELTRABAJO'] = $data['telTrabajo'];
            $Listado[$i]['NACIONALIDAD'] = $data['nacionalidad'];
            $Listado[$i]['NACION'] = $data['nacion'];

            $i++;
    }


    return $Listado;

}
?>