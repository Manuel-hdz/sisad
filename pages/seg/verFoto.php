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
	/**
	  * Nombre del Módulo: Seguridad                                               
	  * Nombre Programador: Nadia Mdahí López Hernandez
	  * Fecha: 15/Marzo/2012
	  * Descripción: Este archivo contiene funciones para Ver las evidencias relacionadas con el Plan de Contingencia
	  **/ 

	//verificar que este definido el ID del tipo de reporte a mostrar
	if (isset($_GET["idPlan"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente, es decir las variables que vienen en la url
		$idPlan = $_GET["idPlan"];
		//Se conecta a la Base de Seguridad para obtener los datosPermiso que se han agregado recientemente
		//$conn=conecta("bd_seguridad");
		mostrarImagen($idPlan);
	}	
	
function mostrarImagen($id_plan, $foto){	
	//Variable que nos permite realizarlas consultas	
	$sql_stm = "";
	if($foto==1){		
		//Crear la Sentencia SQL para obtener la Imagen
		$sql_stm = "SELECT evidencia1, mime1 FROM tiempos_simulacro WHERE planes_contingencia_id_plan = '$id_plan'";
	}
	if($foto==2){	
		//Crear la Sentencia SQL para obtener la Imagen
		$sql_stm = "SELECT evidencia2, mime2 FROM tiempos_simulacro WHERE planes_contingencia_id_plan = '$id_plan'";
	}
	if($foto==3){	
		//Crear la Sentencia SQL para obtener la Imagen
		$sql_stm = "SELECT evidencia3, mime3 FROM tiempos_simulacro WHERE planes_contingencia_id_plan = '$id_plan'";
	}
	if($foto==4){	
		//Crear la Sentencia SQL para obtener la Imagen
		$sql_stm = "SELECT evidencia4, mime4 FROM tiempos_simulacro WHERE planes_contingencia_id_plan = '$id_plan'";
	}
	if($foto==5){	
		//Crear la Sentencia SQL para obtener la Imagen
		$sql_stm = "SELECT evidencia5, mime5 FROM tiempos_simulacro WHERE planes_contingencia_id_plan = '$id_plan'";
	}
	
	//Ejecutar la Sentencia
	$rs = mysql_query($sql_stm);
	//Extraer los datos del ResultSets
	if($datos = mysql_fetch_array($rs)){
		//Obtener la Imagen
		$imagen = $datos[0];
		//Obtener el MIME Type para indicar el contenido de la ventana
		$mime = $datos[1];
		if($mime!=""){
			$rnd = rand(0,1000);
			$archivo = "tmp/imagen$rnd.png";
			
			$fp = fopen($archivo,'a');
			fwrite($fp, $imagen);
		}
		else{
			$archivo = "";
		}	
	}
	//Imprimir la Imagen
	return $archivo;
}//mostrarImagen($idPlan)


?>