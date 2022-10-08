<?php
	require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();
			$sql="select Area,Logrado,Pendiente from graficobarras1 order by Area";
	
    $registros=mysqli_query($MiConexion,$sql) or die ("Error en el select SqlArea");
	while($Fila=mysqli_fetch_array($registros))
		  			 {  $Area[]=$Fila['Area'];
						$Ab[]=$Fila['Logrado'];
						$So[]=$Fila['Pendiente'];
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
                title: 'Estado de Estudiantes por área',
				font: {size: 12, color:'#0d47a1'},
				
				xaxis: {size:7, tickangle: -15 }, 
				yaxis: {title: "Cantidad de estudiantes",font:9},
				barmode: 'group'//'stack', acumulado para arriba
				
		};
    //lo hacemos responsivo// 
	var config = {responsive: true,
	              //displayModeBar: false,fija la barra de navegacion de plotly
				  toImageButtonOptions: {
    format: 'svg', // one of png, svg, jpeg, webp
    filename: 'Estudiantes_por_area',
    height: 500,
    width: 700,
    scale: 1, // Multiply title/legend/axis/canvas sizes by this factor
  },};
    
	Plotly.newPlot('graficaBarra',data,layout,config);


</script>
