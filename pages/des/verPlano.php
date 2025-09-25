<?php

	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández	
	  * Fecha: 26/Mayo/2011
	  * Descripción: Este archivo contiene funciones para Ver los planos registrados
	  **/ 
	//Verificamos si viene el id del plano que  en el get; de ser asi llamar la funcion mostrarPlano()
	if(isset($_GET['id_plano'])){
		mostrarplano();
	}
		
	//Función que permite mostrar el plano seleccionado
	function mostrarPlano(){
		?>
		<script type="text/javascript" language="javascript">
			setTimeout("alert('Los Planos Listados son para Descargarse, su Equipo debe Contener un Visor de Archivos DWG Compatible, de lo Contrario, los Planos no Podrán Visualizarse.')",1000);
		</script>
		<?php	
		//Incluimos arrchivo de conexion
		include ("../../includes/conexion.inc");
		//Incluimos el archivo para modificar las fechas para la consulta
		include ("../../includes/func_fechas.php");
		//Hoja de estilo para la ventana emergente
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		
		//Recuperar el ID del empleado
		$id_plano=$_GET["id_plano"];
		//Recuperamos los datos del GET y los modificamos para ser utilizados
		$fecha=str_replace("/","",modFecha($_GET["fecha"],1));
		$hora=substr($_GET["hora"],0,5);
		$horaFinal=str_replace(":","",$hora);
		
		//Titulo de la etiqueta
		echo "<p align='center' class='titulo_etiqueta'><b>Plano <br />$id_plano</b></p>";
		
		//Creamos la variable carpeta donde se almacenará la ruta
		$carpeta="../top/documentos/".$fecha."/".$horaFinal."/";
			//Verificamos se es posible abrir la carpeta
			if($gestor = opendir($carpeta)){
				echo "<ul>";
	    		while (false !== ($arch = readdir($gestor))){
					//Verificamos que arch sea diferente.. a . a ..; esto porque los archivos generan estas dos opciones y pueden causar problemas
		   			if ($arch != "." && $arch != ".."){
			   			echo "<li><a href=\"$carpeta/".$id_plano."\" class=\"linkli\">".$id_plano."</a></li>\n";
		   			}
	    		}
	    	closedir($gestor);
			echo "</ul>";
			}
			else{
				echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
				echo "<p align='center' class='titulo_etiqueta'><b>No hay registro del Plano <br />$id_plano</b></p>";?>
			<br /><br />
			<p align="center">
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" 
				onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
			</p>
			<?php
			}
}
?>
