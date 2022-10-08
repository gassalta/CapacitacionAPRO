<?php //Terminar de adaptar
function Listar_Barrios($vConexion) {
    $Listado=array();

    	$SQL = "SELECT id, nombre
        FROM barrios
        ORDER BY nombre";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['NOMBRE'] = $data['nombre'];

            $i++;
    }
    
    return $Listado;

}
?>