<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 25/Mayo/2011                                      			
	  * Descripción: Este archivo permite eliminar los planos en el servidor asi como en la Base de datos
	  **/
	 	
	//Verificamos que este presente el botón eliminar; es decir que haya sido presionado
	if(isset($_POST["sbt_eliminar"])){
		//Si es asi llamamos la función de eliminarPlano()
		eliminarPlano();
	}
	
	//Función que permite mostrar los planos Registrados en las fechas especificadas
	function mostrarPlanos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Tomamos los datos que vienen del post y las modificamos para la consulta
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT *	FROM planos WHERE fecha>='$fechaIni' AND fecha<='$fechaFin' ";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>Planos Registrados de <u><em>". modFecha($fechaIni,2)."</u></em> al <u><em>".modFecha($fechaFin, 2)."</u></em></caption>					
				<tr>
					<td  class='nombres_columnas'>SELECCIONAR</td>
					<td class='nombres_columnas'>NO</td>
					<td class='nombres_columnas'>ID PLANO</td>
					<td class='nombres_columnas'>NOMBRE ARCHIVO</td>
					<td class='nombres_columnas'>NOMBRE PLANO</td>
					<td class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas'>FECHA</td>
					<td class='nombres_columnas'>HORA</td>
			</tr>";		
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>
						<td class='nombres_filas' align='center'>
							<input type='radio' id='rdb_plano' name='rdb_plano' value='$datos[id_plano]'/>
						</td>		
						<td align='center' class='$nom_clase'>$cont</td>
						<td align='center' class='$nom_clase'>$datos[id_plano]</td>
						<td align='center' class='$nom_clase'>$datos[nom_archivo]</td>
						<td align='center' class='$nom_clase'>$datos[nom_plano]</td>
						<td align='center' class='$nom_clase'>$datos[descripcion]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>					
						<td align='center' class='$nom_clase'>$datos[hora]</td>
					</tr>";									
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "</table>";
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Planos Registrados de <u><em>". modFecha($fechaIni,2)."</u></em> al <u><em>".modFecha($fechaFin, 2)."</u></em></label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	//Función que permite eliminar el plano segun sea seleccionado
	function eliminarPlano(){
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Creamos la consulta
		$stm_sql ="SELECT id_plano,nom_archivo, fecha, hora FROM planos WHERE id_plano='$_POST[rdb_plano]'";
				
		//Verificar si la sentencia ejecutada se genero con exito
		$rs=mysql_query($stm_sql);
		
		//verificamos que la sentencia sea ejecutada con exito
		if ($rs){
			//Guardamos los datos necesarios para poder tomarlos de la consulta e indicar que archivo sera eliminado
			if($datos=mysql_fetch_array($rs)){						
				$archivo=$datos["nom_archivo"];
				$fecha=modFecha($datos["fecha"],1);
				$hora=substr($datos["hora"],0,5);
			}
			//Modificamos datos para su posterior uso
			$fechaReg=str_replace("/","",$fecha);
			$horaReg=str_replace(":","",$hora);
			
			//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
			@unlink("documentos/".$fechaReg."/".$horaReg."/".$archivo);

			//Creamos arreglos para verificar si las carpetas tienen datos; ya que si tienen datos no pueden ser eliminadas
			$archivos=array();
			$archivosFecha=array();
			
			//Abrimos el archivo y reccorremos en busqueda de sub-carpetas o archivos
			if($gestor = opendir("documentos/".$fechaReg."/".$horaReg)) {
	    		while(false !== ($arch = readdir($gestor))){
					if ($arch != "." && $arch != ".."){
				   		$archivos[]= $arch;
					}
	    		}
			}
	   	 	closedir($gestor);
						
			//Si archivos es menor que uno; no contiene archivos y por lo tanto se puede eliminar
			if(count($archivos)<1)
				rmdir("documentos/".$fechaReg."/".$horaReg);
			
			//Abrimos el archivo y reccorremos en busqueda de sub-carpetas o archivos
			if ($gestor = opendir("documentos/".$fechaReg)) {
	    		while(false !== ($archi = readdir($gestor))) {
					if ($archi != "." && $archi != "..") {
				   		$archivosFecha[]= $archi;
					}
	    		}
			}
	   	 	closedir($gestor);
			
			//Si archivos es menor que uno; no contiene archivos y por lo tanto se puede eliminar			
			if(count($archivosFecha)<1)
				rmdir("documentos/".$fechaReg);
			
			//Creamos la conslulta SQL que permite eliminar el plano de la BD
			$stm_sql2 ="DELETE FROM planos WHERE id_plano='$_POST[rdb_plano]'";
			
			//Ejecutamos la consulta
			$rs2=mysql_query($stm_sql2);
			//Registramos la operación en la bitacora de movimientos
			registrarOperacion("bd_topografia",$_POST["rdb_plano"],"EliminoPlano",$_SESSION['usr_reg']);
			//Redireccionamos a la pantalla de éxito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	}

?>