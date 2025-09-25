	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;						
		//-->
	</script>
<?php
	/**
	  * Nombre del M�dulo: Laboratorio                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 01/Marzo/2011
	  * Descripci�n: Este archivo contiene funciones para Ver la Im�gen 
	  **/ 

	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");

	if(isset($_GET['id_material']))
		mostrarTabla($id_material);

	function mostrarTabla($id_material){		
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		//Conectarse a la BD de Almacen
		$conn = conecta("bd_almacen");
		//Recuper la foto almacenada en la tabla
		$sql = "SELECT * FROM equivalencias WHERE materiales_id_material='$id_material'";
		//Ejecutar la consulta
		$rs = mysql_query($sql);
		$nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $id_material);
		if($datos = mysql_fetch_array($rs)){		
			echo "				
			<table cellpadding='5' width='700'> 
				<caption class='titulo_etiqueta'>Equivalencias Disponibles para $nombre</caption>			
				<tr>
					<td class='nombres_columnas' align='center'>CLAVE</td>
					<td class='nombres_columnas' align='center'>MATERIAL</td>
        			<td class='nombres_columnas' align='center'>CLAVE MATERIAL EQUIVALENTE</td>
			        <td class='nombres_columnas' align='center'>MATERIAL EQUIVALENTE</td>
    	    		<td class='nombres_columnas' align='center'>PROVEEDOR</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{								
				echo "	<tr>
							<td class='nombres_filas' align='center'>$datos[materiales_id_material]</td>
							<td class='$nom_clase' align='center'>$nombre</td>
							<td class='$nom_clase' align='center'>$datos[clave_equivalente]</td>
							<td class='$nom_clase' align='center'>$datos[nombre]</td>
							<td class='$nom_clase' align='center'>$datos[proveedor]</td>
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
			echo  "<p align='center'> <img src='../../images/no-equivalencia.png' align='center' width='350' height='350' border='0'/></p>";
			echo "<p align='center' class='titulo_etiqueta'><b>No hay Equivalencias Disponibles para $nombre</b></p>";
		}		
		//Cerrar la conexion
		mysql_close($conn);
	}	
?>
<br /><br /><br />
<p align="center">
<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true"  onclick="window.close();" />
</p>