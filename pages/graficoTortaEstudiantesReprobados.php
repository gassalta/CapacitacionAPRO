<?php
require_once 'funciones/conexion.php';
$MiConexion = ConexionBD();
require_once 'funciones/baseDeDatos.php';


$sql = "select count(aprobados) as aprobados,sum(reprobados>3) as reprobados from situacionestudiantes";
$registros = mysqli_query($MiConexion, $sql) or die("Error en el select SqlArea");

$Fila = mysqli_fetch_array($registros);


$estudiante[] = 'Aprobados (' . ($Fila['aprobados'] - $Fila['reprobados']) . ')';
$estudiante[] = 'En Riesgo (' . $Fila['reprobados'] . ')';

$total[] = ($Fila['aprobados'] - $Fila['reprobados']);
$total[] = $Fila['reprobados'];
$cant[] = 'Total: ' . $Fila['aprobados'];



$datosEstudiante = json_encode($estudiante);
$datosTotal = json_encode($total);
$datosCant = json_encode($cant);
/*$sql="select * from situacionestudiantes";
	$registros=mysqli_query($MiConexion,$sql) or die ("Error en el select SqlArea");
	while($Fila=mysqli_fetch_array($registros))
	{
		     $Listado[$i]['Aprobados'] = $Fila['aprobados'];
            $Listado[$i]['Reprobados'] =$Fila['reprobados'];

            $i++;
	}
	$Cant=count($Fila['reprobados']);
     for($i=0; $i < $Cant; $i++) 
	  {
		 
	   }*/

?>
<div id="graficaTorta"></div>

<script type="text/javascript">
	function crearCadenaBarras(json) {
		var parsed = JSON.parse(json);
		var arr = [];
		for (var x in parsed) {
			arr.push(parsed[x]);
		}
		return arr;
	}
</script>

<script type="text/javascript">
	datosEstudiante = crearCadenaBarras('<?php echo $datosEstudiante ?>');
	datosCant = crearCadenaBarras('<?php echo $datosCant ?>')
	datosTotal = crearCadenaBarras('<?php echo $datosTotal ?>');

	//configuramos los datos del grafico// 
	var A = {
		labels: datosEstudiante,
		values: datosTotal,

		name: "Estados",
		type: 'pie', //lines,histogram,
		//orientation: 'h',..si quisieramos hacer barras horizontales
		marker: {
			color: 'grey',
			line: { //color relleno
				width: 0.5, //ancho linea
				dash: 'solid',
				color: 'grey', //color borde line
			},
		},

		automargin: true,
		textinfo: "label+percent", //"label+percent" si queremos que ademas muestre la etiqueta
		insidetextorientation: "radial" //, si queremeos que siga con la orientacion del grafico

	}

	;



	var data = [A];

	//configuramos el título//
	var layout = {
		bargap: 0.05,
		bargroupgap: 0.2,
		title: 'Estudiantes en Riesgo',
		font: {
			size: 12,
			color: '#0d47a1'
		},
	};
	//lo hacemos responsivo// 
	var config = {
		responsive: true,

		toImageButtonOptions: {
			format: 'jpeg', // one of png, svg, jpeg, webp
			filename: 'Estudiantes_en_Riesgo',
			height: 400,
			width: 600,
			scale: 1, // Multiply title/legend/axis/canvas sizes by this factor
		},
	};

	Plotly.newPlot('graficaTorta', data, layout, config);
</script>