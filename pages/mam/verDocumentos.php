<?php

	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Antonio de Jesús jiménez Cuevas
	  * Fecha: 01/Marzo/2011
	  * Descripción: Este archivo contiene funciones para Ver la documentacion relacionada con los Equipos de Mtto
	  **/ 

if(isset($_GET['id_equipo']))
	verDocumentos();

function verDocumentos(){
	//Arcivos que se incluyen para obtener informacion del equipo
	include_once ("../../includes/conexion.inc");
	include_once ("../../includes/op_operacionesBD.php");?>
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
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Obtener el nombre del Equipo
	$nombre = obtenerDato("bd_mantenimiento", "equipos", "nom_equipo", "id_equipo", $_GET["id_equipo"]);
	echo "<p align='center' class='titulo_etiqueta'><b>Equipo $_GET[id_equipo]-$nombre</b></p>";
	//Realizar la conexion con la BD
	$conn = conecta("bd_mantenimiento");
	//Ruta donde se almacenan los documentos
	$carpeta="documentos/".$_GET["id_equipo"];
	//Revisar si se han agregado documentos a la Base de Datos
	$stm_sql="SELECT * FROM expediente_equipos WHERE equipos_id_equipo='$_GET[id_equipo]'";
	//Ejecutar sentencia SQL
	$rs=mysql_query($stm_sql);
	//Verificar que se hayan encontrado resultados
	if ($datos=mysql_fetch_array($rs)){
		echo "				
			<table cellpadding='5' width='100%' align='center'> 
			<caption class='titulo_etiqueta'>Documentaci&oacute;n Registrada de ".$_GET["id_equipo"]."</caption></br>";
		echo "
			<tr>
				<td class='nombres_columnas' align='center'>DOCUMENTO</td>
				<td class='nombres_columnas' align='center'>ESTATUS</td>
				<td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
				<td class='nombres_columnas' align='center'>ARCHIVO</td>
			</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;	
		//Creamos la variable que permitira saber si los archivos de la BD corresponden con los del servidor
		$contArchivos=0;
		//Contador para saber el numero de revisiones que hace dentro de la carpeta seleccionada
		$contador=0;
		do{										
			echo "
				<tr>					
					<td class='$nom_clase' align='center'>$datos[nom_docto]</td>					
					<td class='$nom_clase' align='center'>$datos[estatus]</td>
					<td class='$nom_clase' align='center'>$datos[ubicacion]</td>";
				
			/*************************************************************************/	
			//Verificar que la carpeta buscada exista
			if (is_dir($carpeta)){
				//Abrir la carpeta seleccionada
				if ($gestor = opendir($carpeta)) {
					//Recorrer la carpeta
		   			while (false !== ($arch = readdir($gestor))) {
						//Incrementar el contador en 1 por cada revision
						$contador++;
						//Excluir los archivos punteros o apuntadores de la busqueda y para el despliegue de informacion
						if ($arch=="$datos[nom_archivo]") {
							//Variable incializada vacia que contendra la ruta del archivo
							$archivo="";
							//Variable con la ruta del archivo
							$archivo=$carpeta."/".$arch;
							//Verificar si el archivo es un documento PDF, ya que de ser asi, se forza la descarga
							if (substr($arch,-4)=='.pdf')
								//Mostrar los documentos que corresponden con su respectivo enlace de descarga
								echo "<td class='$nom_clase' align='center'><a href='marco_descarga.php?archivo=$archivo&nom=$arch'&tipo=pdf>$arch</a></td>";
							//Si el archivo es DOC o una imagen se muestra el enlace de esta manera
							else
								echo "<td class='$nom_clase' align='center'><a href='marco_descarga.php?archivo=$archivo&nom=$arch'>$arch</a></td>";
								//Mostrar los documentos a modo de lista con un enlace
								//echo "<td class='$nom_clase' align='center'><a href=\"$carpeta/$arch\" class=\"linkli\">".$arch."</a></td>";
						}
						else{
							//Si no corresponde el archivo con el asignado a la BD, incrementar el contador en 1
							$contArchivos++;
						}
					}
					//Cerrar el directorio
					closedir($gestor);
				}
			}
			//Comprobar los contadores de archivos y revisiones de la carpeta, si son iguales, aunque exista el archivo agregado en la BD, no esta cargado al Servidor
			if ($contArchivos==$contador)
				echo "<td class='$nom_clase' align='center'>Sin Archivo</td>";
			/*************************************************************************/
			echo "</tr>";			
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
		echo  "<p align='center'> <img src='../../images/no-documentos.png' align='center' width='350' height='350' border='0'/></p>";
		echo "<p align='center' class='titulo_etiqueta'><b>No Hay Documentos Cargados al Sistema del Equipo $nombre</b></p>";
	}
	//Cerrar la conexion con la BD
	mysql_close($conn);

	?>
	<br /><br />
	<p align="center">
		<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
	</p>
	<?php
}

?>
