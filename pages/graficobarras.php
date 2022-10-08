<?php

function graficoBarras($IdEst)
{
	require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();
//$sql="select * from vistagrafico3 where IdEst='6'";
	$sql="select * from vistagrafico3 where IdEst='$IdEst'";
    $registros=mysqli_query($MiConexion,$sql) or die ("Error en el select SqlArea");
	if(mysqli_num_rows($registros)!=0){
	while($Fila=mysqli_fetch_array($registros))
		  			 {  $Area[]=$Fila['Anio'];
						$Ab[]=$Fila['Aprobado'];
						$So[]=$Fila['Reprobado'];
						//$Ca[]=$Fila['Cancelados'];
					 }


	$datosArea=json_encode($Area);
	$datosAb=json_encode($Ab);
	$datosSo=json_encode($So);
   
?>
<div id="graficaBarra"></div>

<script type="text/javascript">
	function crearCadenaBarras(json){
		var parsed = JSON.parse(json);
		var arr = [];
		for(var x in parsed){
			arr.push(parsed[x]);
		}
		return arr;
	}
</script>

<script type="text/javascript">

	datosArea=crearCadenaBarras('<?php echo $datosArea ?>');
	datosAb=crearCadenaBarras('<?php echo $datosAb ?>');
	datosSo=crearCadenaBarras('<?php echo $datosSo ?>');
	
    //configuramos los datos del grafico// 
	var A=
		{
			x: datosArea,
			y: datosAb,
		    name: "Aprobados",	
			type: 'bar',//lines,histogram,
			//orientation: 'h',..si quisieramos hacer barras horizontales
			marker: {
                color: '#0d47a1',line: { //color relleno
                       width: 1.5,//ancho linea
					   dash:'solid',
					   color:'#0d47a1',//color borde line
                       },
					 },
		};
		var S=	
		{
			x: datosArea,
			y: datosSo,
			name: "Reprobados",	
			type: 'bar',//lines,bar,
			  
			marker: {
                color: '#B71c1c',line: { //color relleno
                       width: 1.5,//ancho linea
					   dash:'solid',
					   color:'#B71c1c',//color borde line
                       },
		            },
		};
			
	
	var data = [A,S];
	
	//configuramos el título//
	var layout = { 
	             bargap: 0.2, 
                 bargroupgap: 0, 
                title: 'Trayectoria escolar del estudiante',
				font: {size: 12, color:'#0d47a1'},
				
				xaxis: {title: "Año cursado",size:9}, 
				yaxis: {title: "Cantidad de Espacios Curriculares",font:9},
				barmode: 'group'//'stack', acumulado para arriba
				
		};
    //lo hacemos responsivo// 
	var config = {responsive: true,
	              //displayModeBar: false,fija la barra de navegacion de plotly
				  toImageButtonOptions: {
    format: 'svg', // one of png, svg, jpeg, webp
    filename: 'ECurricualares_historicos',
    height: 500,
    width: 700,
    scale: 1, // Multiply title/legend/axis/canvas sizes by this factor
  },};
    
	Plotly.newPlot('graficaBarra',data,layout,config);


</script>

<?php
}
else
{echo '<div class="row">
 <div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>El estudiante seleccionado no tiene calificaciones finales registradas</center></strong></div></div>
 </div>';}
}
 ?>