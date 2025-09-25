<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 18/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Agregar mezcla en la BD
	**/
	
	
	/*esta funcion guarda los datos de la muestra registrada*/
	function guardarDatosMuestra(){
		
		//Conectarse con la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Recuperar los datos de la Muestra del POST
		$idMuestra = $_POST['txt_idMuestra'];
		$idMezcla = $_POST['cmb_idMezcla'];
		$numMuestra = $_POST['txt_noMuestra'];
		$tipoPrueba = $_POST['cmb_tipoPrueba'];
		//Verificar el tipo de Prueba y obtener la Localizacion o el Código de CONCRETO
		$codigoLoc = "";
		if($tipoPrueba=="CONCRETO")
			$codigoLoc = $_POST['txt_codigo'];
		else if($tipoPrueba!="")
			$codigoLoc = $_POST['cmb_localizacion'];
						
		$fechaColado = modFecha($_POST['txt_fechaColado'],3);
		$revenimeinto = str_replace(",","",$_POST['txt_revenimiento']);
		$fPrimaC = str_replace(",","",$_POST['txt_fProyecto']);
		
		//Obtener el diametro de acuerdo al tipo de prueba seleccionado, Para Obra Externa y Concreto es de 15 cm y para Obra de Zarpeo es de 7 cm
		$diametro = 0;
		if($tipoPrueba=="CONCRETO" || $tipoPrueba=="OBRA EXTERNA")
			$diametro = 15;
		else if($tipoPrueba=="OBRA DE ZARPEO")
			$diametro = 7;
		
		//Calcular el Area p x r2
		$radio = $diametro/2;
		$area = 3.1416 * pow($radio,2);
		//Redondear el resultado del área con dos decimales de presición
		$area =  round($area, 2);
		
		//Crear la Sentencia SQL para guardar los datos de la Muestra
		$sql_stm = "INSERT INTO muestras (id_muestra,mezclas_id_mezcla,num_muestra,tipo_prueba,codigo_localizacion,fecha_colado,revenimiento,
					fprimac_proyecto,diametro,area) VALUES('$idMuestra','$idMezcla','$numMuestra','$tipoPrueba','$codigoLoc','$fechaColado','$revenimeinto','$fPrimaC',
					$diametro,$area)";
		
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);		
		
		if($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$idMuestra,"RegistrarMuestra",$_SESSION['usr_reg']);
			//Redireccionar a la pagina de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Obtener el Error para redireccionar a la pagina de Error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
			
	}//Cierre de la función guardarDatosMuestra()
	
	
	
		/*esta funcion guarda los datos de la muestra registrada*/
	function modificarDatosMuestra(){
		
		//Conectarse con la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Recuperar los datos de la Muestra del POST
		$idMuestraOriginal = $_POST['hdn_idMuestraOriginal'];
		$idMuestra = $_POST['txt_idMuestra'];
		$idMezcla = $_POST['cmb_idMezcla'];
		$numMuestra = $_POST['txt_noMuestra'];
		$tipoPrueba = $_POST['cmb_tipoPrueba'];
		//Verificar el tipo de Prueba y obtener la Localizacion o el Código de CONCRETO
		$codigoLoc = "";
		if($tipoPrueba=="CONCRETO")
			$codigoLoc = $_POST['txt_codigo'];
		else if($tipoPrueba!="")
			$codigoLoc = $_POST['cmb_localizacion'];
						
		$fechaColado = modFecha($_POST['txt_fechaColado'],3);
		$revenimeinto = str_replace(",","",$_POST['txt_revenimiento']);
		$fPrimaC = str_replace(",","",$_POST['txt_fProyecto']);
		
		//Obtener el diametro de acuerdo al tipo de prueba seleccionado, Para Obra Externa y Concreto es de 15 cm y para Obra de Zarpeo es de 7 cm
		$diametro = 0;
		if($tipoPrueba=="CONCRETO" || $tipoPrueba=="OBRA EXTERNA")
			$diametro = 15;
		else if($tipoPrueba=="OBRA DE ZARPEO")
			$diametro = 7;
		
		//Calcular el Area p x r2
		$radio = $diametro/2;
		$area = 3.1416 * pow($radio,2);
		
		//Crear la Sentencia SQL para actualizar los datos de la Muestra
		$sql_stm = "UPDATE muestras SET id_muestra='$idMuestra', mezclas_id_mezcla='$idMezcla', num_muestra='$numMuestra', tipo_prueba='$tipoPrueba',
		 			codigo_localizacion='$codigoLoc', fecha_colado='$fechaColado', revenimiento='$revenimeinto',fprimac_proyecto='$fPrimaC', 
					diametro=$diametro,area=$area WHERE id_muestra = '$idMuestraOriginal'";
		
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);		
		
		if($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$idMuestraOriginal,"ModificarMuestra",$_SESSION['usr_reg']);
			//Redireccionar a la pagina de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Obtener el Error para redireccionar a la pagina de Error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
			
	}//Cierre de la función guardarDatosMuestra()
	
	 	 
?>