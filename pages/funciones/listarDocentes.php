<?php
function Listar_Docentes($vConexion) {
    $Listado=array();

    	$SQL = "SELECT D.Id, D.Apellido, D.Nombre, D.DNI, D.FechaNacim, D.NroLegajoJunta, D.Titulo, D.FechaEscalafon, C.Denominacion AS Categoria, D.Mail, D.UltIngreso
        FROM docentes D,categorias C
        WHERE D.Categoria = C.Id
        ORDER BY D.Apellido";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['APELLIDO'] = $data['Apellido'];
            $Listado[$i]['NOMBRE'] = $data['Nombre'];
            $Listado[$i]['DNI'] = $data['DNI'];
            $Listado[$i]['FECHANACIM'] = $data['FechaNacim'];
            $Listado[$i]['NROLEGAJOJUNTA'] = $data['NroLegajoJunta'];
            $Listado[$i]['TITULO'] = $data['Titulo'];
            $Listado[$i]['FECHAESCALAFON'] = $data['FechaEscalafon'];
            $Listado[$i]['CATEGORIA'] = $data['Categoria'];
            $Listado[$i]['MAIL'] = $data['Mail'];
            $Listado[$i]['ULTINGRESO'] = $data['UltIngreso'];

            $i++;
    }


    return $Listado;

}
?>