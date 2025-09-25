<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 07/Noviembre/2011
	  * Descripción: En este archivo estan las funciones para mostrar los Consumos realizados en un Registro
	  **/ 
	
	//Módulo de conexión a la BD
	include ("../../includes/conexion.inc");
	//Operaciones con la Base de Datos
	include("../../includes/op_operacionesBD.php");
	?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>	
	<?php
	$no=$_GET["no"];
	?>
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		
		function habilitarBoton(){
			window.opener.document.getElementById("btn_verConsumos<?php echo $no;?>").disabled=false;
		}
		//-->
	</script>
	
	<style type="text/css">
		<!--
		#tabla-resultados{position:absolute; overflow:scroll; width:90%; height:410px;}
		#botones{position:absolute; left:30px;top:569px; width:90%;}
		-->
	</style>
	
</head>
<body onunload="habilitarBoton();">
	<?php 
	//Realizar la conexion a la BD de Desarollo
	$conn = conecta("bd_desarrollo");
	$id_bit=$_GET["id_bitacora"];
	$tipo_reg=$_GET["tipoReg"];
	$stm_sql="SELECT nombre,unidad_medida,cantidad FROM consumos WHERE tipo_registro='$tipo_reg' AND (bitacora_avance_id_bitacora='$id_bit' OR bitacora_retro_bull_id_bitacora='$id_bit')";
	$rs=mysql_query($stm_sql);
	if($datos=mysql_fetch_array($rs)){
		echo "				
				<br>
				<table cellpadding='5' width='100%'> 
				<caption class='titulo_etiqueta'>Consumos del Registro Seleccionado</caption>
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>NO.</th>
						<th class='nombres_columnas' align='center'>NOMBRE</th>
        				<th class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</th>
				        <th class='nombres_columnas' align='center'>CANTIDAD</th>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>$cont</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
						<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
						<td class='$nom_clase' align='center'>$datos[cantidad]</td>
						</tr>
						";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";			
		}
		else{
			//Si no hay provedores registrados por Compras, se indica esto al usuario
			echo "<p class='msje_correcto' align='center'><br><br><br><br><br><br><br><br><br>No hay Registros de Consumos para el Registro Seleccionado</u></em></p>";
		}
		?>
			<p align="center"><input type="button" value="Cerrar" onclick="window.close()" name="btn_cerrar" class="botones"/></p>
		<?php
	?>
</body>
</html>