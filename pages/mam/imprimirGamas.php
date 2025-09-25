<?php
 
 //inicirar la sesion para poder acceder a la infomración guardada en los arreglos de session
 session_start();
 include ("../../includes/conexion.inc");
 include ("../../includes/op_operacionesBD.php");

//si viene definido  el boton imprimir gamas llamar la funcion que abre el doc de word
 if(isset($_GET['btn_impGama']))
	gamasOT();

	//Funcion que se encarga de mostrar las gamas que se han asociando a la Orden de Trabajo
	function gamasOT(){
		//Cabeceras que permiten el contenido el archivo como contenido de archivo de texto plano, con la extension TXT
		header("Content-type: application/vnd.ms-word");
		header("Content-Disposition: attachment; filename=Gamas.doc");?>		
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.titulo_pagina {font-family: Arial, Helvetica, sans-serif; font-weight:bold; font-size:14px; }
				-->
			</style>
		</head>	
		<body>
		<p align="center" class="titulo_pagina">Gamas Agregadas a la Orden de Trabajo: <u><?php echo $_SESSION['datosOT']['orden_trabajo']; ?></u></p><?php										

		//Recorrer el Arreglo de Gamas para obtener el Id y el Nombre de cada una, el detalle de las Gamas se obtiene de la Base de Datos
		$cont = 1;
		foreach ($_SESSION['gamasOT'] as $ind => $datosGama) {			
			echo "
				<table align='center' cellpadding='8' width='50%'>
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>ID DE LA GAMA</td>
						<td class='nombres_columnas' align='center'>NOMBRE DE LA GAMA</td>
					</tr>
					<tr>
						<td class='renglon_gris' width='10%' align='center'>".($ind+1)."</td>
						<td class='renglon_gris' width='30%'>".$datosGama['id_gama']."</td>
						<td class='renglon_gris' width='60%'>".$datosGama['nom_gama']."</td>
					</tr>
				</table>";
			detalles($datosGama['id_gama']);												
			echo "<br><br><br>";
		}
		?></body><?php
	}// Fin de la function gamasOT(){
			
	
	//Funcion que se encarga de mostrar el Detalle de cada Gama agregada
	function detalles($value){		
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Crear la Sentecnia SQL para i
		$stm_sql = "SELECT sistema, aplicacion, actividades.descripcion FROM (actividades JOIN gama_actividades ON id_actividad = actividades_id_actividad) 
		JOIN gama ON gama_id_gama=id_gama  WHERE id_gama='$value'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if(  $datos=mysql_fetch_array($rs)){
			
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td class='nombres_columnas' align='center'>SISTEMA</td>
					<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase'>$datos[sistema]</td>
						<td class='$nom_clase'>$datos[aplicacion]</td>
						<td class='$nom_clase'>$datos[descripcion]</td>
					</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "					
				</table>";
		}// fin  if($datos=mysql_fetch_array($rs))
		//Cerrar conexion con MySQL
		mysql_close($conn);
	}
?>