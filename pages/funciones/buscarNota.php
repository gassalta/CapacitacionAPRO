<?php
function consultaNotaFinalSola($vConexion,$IdEstudiante,$IdEspCurr){
	$Nota = array();
	$SQL="SELECT calificacion FROM calificacionfinalxespcurr WHERE estudiante='$IdEstudiante' and espacioCurricular='$IdEspCurr'";
	$rs=mysqli_query($vConexion,$SQL);
	if($data = mysqli_fetch_array($rs)) {
                    $Nota['CALIFICACION'] = $data['calificacion'];
                }
        
        return $Nota['CALIFICACION'];
}
?>