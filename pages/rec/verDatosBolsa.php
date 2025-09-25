	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �CONCRETO LANZADO DE FRESNILLO MARCA');
			}
		}
		document.onmousedown=click;						
		//-->
	</script>
<?php

	/**
	  * Nombre del M�dulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 15/abril/2011
	  * Descripci�n: Este archivo contiene funciones para Ver los datos de �rea y Puesto a los que aspiran las personas que esan siendo contratados
	  				 desde la Bolsa de Trabajo
	  **/ 

	if(isset($_GET['id_bolsa']) && isset($_GET['consulta'])){
		if ($_GET["consulta"]=="areapuesto")
			mostrarAreaPuesto();
	}

	function mostrarAreaPuesto(){	
		include ("../../includes/conexion.inc");
		include ("../../includes/op_operacionesBD.php");
		//Hoja de estilo para la ventana emergente
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		
		//Recuperar el ID del empleado en la Bolsa de Trabajo
		$id_bolsa=$_GET["id_bolsa"];
	
		//Recuperar los datos de las areas/puestos almacenados en la tabla correspondiente
		$sql = "SELECT area,puesto,nombre,ap_paterno,ap_materno FROM area_puesto JOIN bolsa_trabajo ON folio_aspirante=bolsa_trabajo_folio_aspirante WHERE bolsa_trabajo_folio_aspirante = '$id_bolsa'";
		
		//Abrir conexion a la Base de Datos
		$conn = conecta("bd_recursos");
		//Ejecutar la consulta
		$rs = mysql_query($sql);
		if($datos = mysql_fetch_array($rs)){
			echo "				
			<table cellpadding='5' width='100%' align='center'> 
			<caption class='titulo_etiqueta'>&Aacute;reas y Puestos Aspirados de $datos[nombre] $datos[ap_paterno] $datos[ap_materno]</caption></br>";
			echo "
			<tr>
				<td class='nombres_columnas' align='center'>&Aacute;REA</td>
				<td class='nombres_columnas' align='center'>PUESTO</td>
			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
				<tr>					
					<td class='$nom_clase' align='center'>$datos[area]</td>					
					<td class='$nom_clase' align='center'>$datos[puesto]</td>
				</tr>
				";
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 
			?>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan="4" align="center">
					<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
				</td>
			</tr>
			<?php
		}
		//Cerrar la conexion
		mysql_close($conn);
	}
?>
