<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 03/Mayo/2011
	  * Descripción: Este archivo contiene funciones para Ver las Refacciones relacionadas con los Equipos
	  **/ 

if(isset($_GET['id_equipo']))
	verRefacciones();

function verRefacciones(){
	//Arcivos que se incluyen para obtener informacion del equipo
	include_once ("../../includes/conexion.inc");
	include_once ("../../includes/op_operacionesBD.php");
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";?>
	<?php //Archivos que permtien desabilitar teclas especificas, así como desabilitar el clic derecho?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script><?php 
	//Obtener el nombre del Equipo
	$nombre = obtenerDato("bd_mantenimiento", "equipos", "nom_equipo", "id_equipo", $_GET["id_equipo"]);
	echo "<p align='center' class='titulo_etiqueta'><b>Equipo $_GET[id_equipo]-$nombre</b></p>";
	//Realizar la conexion con la BD
	$conn = conecta("bd_mantenimiento");
	//Revisar si se han agregado refacciones a la Base de Datos
	$stm_sql="SELECT * FROM refacciones WHERE equipos_id_equipo='$_GET[id_equipo]'";
	//Ejecutar sentencia SQL
	$rs=mysql_query($stm_sql);
	//Verificar que se hayan encontrado resultados
	if ($datos=mysql_fetch_array($rs)){
		echo "				
			<table cellpadding='5' width='700' align='center'> 
			<caption class='titulo_etiqueta'>Refacciones Registradas de ".$_GET["id_equipo"]."</caption></br>";
		echo "
			<tr>
				<td class='nombres_columnas' align='center' width='40%'>NOMBRE</td>
				<td class='nombres_columnas' align='center' width='60%'>DESCRIPCI&Oacute;N</td>
			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{								
				echo "	<tr>
							<td class='$nom_clase' align='center' width='40%'>$datos[nombre]</td>
							<td class='$nom_clase' align='center' width='60%'>$datos[descripcion]</td>
						</tr>";			
		
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 			
			echo "</table>";	
		}
		else{
			//Mostrar la Etiqueta en la pagina
			echo  "<p align='center'> <img src='../../images/no-refacciones.png' align='center' width='350' height='350' border='0'/></p>";
			echo "<p align='center' class='titulo_etiqueta'><b>No hay Refacciones Disponibles para $nombre</b></p>";
		}		
		//Cerrar la conexion
		mysql_close($conn);
	}?>	
<br /><br /><br />
<p align="center">
<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true"  onclick="window.close();" />
</p>