<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 28/Octubre/2011                                      			
	  * Descripción: Este archivo se encarga de borrar los registros en la BD cuando se cancela el registro en la Bitácora de Fallas y Consumos
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
	
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["tipoOper"])){
		//Conectarse a la BD
		$conn = conecta("bd_desarrollo");
		
		//Obtener el Tipo de Operación para proceder a la ejecución del código correspondiente
		$tipoOperacion = $_GET["tipoOper"];
		
		
				
		//Implementar las operaciones para borrar los registros de la bitacora indicada
		if($tipoOperacion=="borrar"){
			//Obtener los datos necesarios para borrar los registros de Fallas, Consumos o Explosivos
			$idBitacora = $_GET["idBit"];
			$tipoBitacora = $_GET["tipoBit"];		
			$tipoRegistro = $_GET["tipoReg"];
			$nomTabla = $_GET["nomTabla"];
			
			//Identificar la tabla de cual se borraran los registros (Bitacora Falla o Consumos)
			$tabla = "";
			if($nomTabla=="fallas")
				$tabla = "bitacora_fallas";
			else if($nomTabla=="consumos")
				$tabla = "consumos";
			else if($nomTabla=="explosivos")
				$tabla = "explosivos_empleados";
			
					
			//Crear la Sentencia SQL dependiendo de la Bitácora desde la cual se agregaron registros a la Bitácora de Fallas
			switch($tipoBitacora){
				case "bitAvance":
					if($tabla=="explosivos_empleados"){
						//Sentencia SQL para quitar los explosivos empleados asociados a la Bitácora de Avance
						$sql_stm = "DELETE FROM $tabla WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					}
					else{
						//Sentencia SQL para quitar las fallas asociadas a la Bitácora de Avance
						$sql_stm = "DELETE FROM $tabla WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
					}
				break;
				case "bitRetroBull":
					//Sentencia SQL para quitar las fallas asociadas a la Bitácora de Retro y Bull
					$sql_stm = "DELETE FROM $tabla WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
				break;
			}
							
			//Ejecutar la Sentencia previamente creada
			$rs = mysql_query($sql_stm);
			//Revisar el resultados de la ejecucion de la Sentencia SQL
			if($rs){			
				//Definir el tipo de contenido que tendra el archivo creado
				header("Content-type: text/xml");	 
				//Crear XML de la clave Generada
				echo utf8_encode("
					<existe>
						<valor>true</valor>					
					</existe>");
			}
			else{
				//Definir el tipo de contenido que tendra el archivo creado
				header("Content-type: text/xml");	 
				//Crear XML de la clave Generada
				echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
			}
		}//Cierre if($tipoOperacion=="borrar")
		
		
		
		else if($tipoOper=="borrarBitacoras"){
			//Obtener los datos necesarios para borrar los registros de Barrenación, Voladura y Rezagado
			$idBitacora = $_GET["idBit"];
			$tipoBitacora = $_GET["tipoBit"];
			
			//Crear la Sentencia SQL dependiendo de la Bitácora desde la cual se agregaron registros a la Bitácora de Fallas
			switch($tipoBitacora){
				//Sentencias SQL para borrar los registros de Barrenación, Voladuras y Rezagado asociados a la Bitacora de Avance actual cuando se cancela el registro
				case "bitAvance":					
					//Sentencias SQL para borrar registros en tablas comunes (bitacora_fallas, consumos, equipo, personal)
					$del_fallas = "DELETE FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					$del_consumos = "DELETE FROM consumos WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					$del_equipo = "DELETE FROM equipo WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					$del_personal = "DELETE FROM personal WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					
					//Sentencias SQL para borrar registros de Barrenación con Jumbo (barrenacion_jumbo, barrenos y registro_brazos)
					$del_jumbo = "DELETE FROM barrenacion_jumbo WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					$del_ju_barrenos = "DELETE FROM barrenos WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					$del_ju_brazos = "DELETE FROM registro_brazos WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					
					//Sentencias SQL para borrar registros de Barrenación con Maquina de Pierna (barrenacion_maq_pierna)
					$del_barr_mp = "DELETE FROM barrenacion_maq_pierna WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					
					//Sentencias SQL para borrar registros de Voladura(voladuras)
					$del_voladura = "DELETE FROM voladuras WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					
					//Sentencias SQL para borrar registros de Rezagado(rezagado)
					$del_rezagado = "DELETE FROM rezagado WHERE bitacora_avance_id_bitacora = '$idBitacora'";
					
					//Borrar de la Bitácora de Avance
					$del_avance = "DELETE FROM bitacora_avance WHERE id_bitacora = '$idBitacora'";
					
					//Esta variable indicara si la ejecucion de las sentencias fue existosa
					$resultadoEjecucion = 1;
										
					if($resultadoEjecucion==1){ if(!mysql_query($del_fallas)){ $resultadoEjecucion = 0;} }
					if($resultadoEjecucion==1){ if(!mysql_query($del_consumos)){ $resultadoEjecucion = 0;} }
					if($resultadoEjecucion==1){ if(!mysql_query($del_equipo)){ $resultadoEjecucion = 0;} }
					if($resultadoEjecucion==1){ if(!mysql_query($del_personal)){ $resultadoEjecucion = 0;} }					

					if($resultadoEjecucion==1){ if(!mysql_query($del_jumbo)){ $resultadoEjecucion = 0;} }
					if($resultadoEjecucion==1){ if(!mysql_query($del_ju_barrenos)){ $resultadoEjecucion = 0;} }
					if($resultadoEjecucion==1){ if(!mysql_query($del_ju_brazos)){ $resultadoEjecucion = 0;} }

					if($resultadoEjecucion==1){ if(!mysql_query($del_barr_mp)){ $resultadoEjecucion = 0;} }
					if($resultadoEjecucion==1){ if(!mysql_query($del_voladura)){ $resultadoEjecucion = 0;} }
					if($resultadoEjecucion==1){ if(!mysql_query($del_rezagado)){ $resultadoEjecucion = 0;} }
					if($resultadoEjecucion==1){ if(!mysql_query($del_avance)){ $resultadoEjecucion = 0;} }
				
					//Revisar el resultados de la ejecucion de la Sentencia SQL
					if($resultadoEjecucion==1){			
						//Definir el tipo de contenido que tendra el archivo creado
						header("Content-type: text/xml");
						//Crear XML de la clave Generada
						echo utf8_encode("
							<existe>
								<valor>true</valor>
							</existe>");
					}
					else{
						//Definir el tipo de contenido que tendra el archivo creado
						header("Content-type: text/xml");	 
						//Crear XML de la clave Generada
						echo utf8_encode("
							<existe>
								<valor>false</valor>
							</existe>");
					}													
				break;
				case "bitRetroBull":
					//Colocar la Sentencia SQL para eliminar los registros asociados a la Bitacora de Retro-Bull
				break;
			}//Cierre switch($tipoBitacora){
						
		}//Cierre else if(tipoOper="borrarBitacoras")				
		
		
		
		//Cargar los datos del Registro de Falla o Consumo seleccionado para ser Borrado o Modificado
		else if($tipoOperacion=="cargar"){		
			//Obtener los datos necesarios para borrar los registros de Fallas, Consumos o Explosivos
			$idBitacora = $_GET["idBit"];
			$tipoBitacora = $_GET["tipoBit"];		
			$tipoRegistro = $_GET["tipoReg"];			
			//Obtener datos adicionales necesarios para la carga de datos
			$noReg = $_GET["noReg"];
			
			
			//Identificar la tabla de cual se obtendran los registros (Bitacora Falla o Consumos)
			$tabla = "";
			$campo = "";
			if($tipoRegistro=="fallas"){
				$tabla = "bitacora_fallas";
				$campo = "no_falla";
			}
			else if($tipoRegistro=="consumos"){
				$tabla = "consumos";
				$campo = "no_registro";
			}
			else if($tipoRegistro=="explosivos"){
				$tabla = "explosivos_empleados";
				$campo = "catalogo_explosivos_id_explosivos";
				//La Bitácora de Explosivos solo aplica para la Bitácora de Avance
				$tipoBitacora = "bitAvance";
			}
			
					
			//Crear la Sentencia SQL dependiendo de la Bitácora desde la cual se agregaron registros a la Bitácora de Fallas
			$sql_stm = "";
			switch($tipoBitacora){
				case "bitAvance":
					//Sentencia SQL para quitar las fallas de la Bitácora de Rezagado
					$sql_stm = "SELECT * FROM $tabla WHERE bitacora_avance_id_bitacora = '$idBitacora' AND $campo = $noReg";		
				break;
				case "bitRetroBull":
					//Sentencia SQL para quitar las fallas asociadas a la Bitácora de Retro y Bull
					$sql_stm = "SELECT * FROM $tabla WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND $campo = $noReg";		
				break;
			}
							
							
			//Ejecutar la Sentencia previamente creada
			$rs = mysql_query($sql_stm);
			//Revisar el resultados de la ejecucion de la Sentencia SQL
			if($datosReg=mysql_fetch_array($rs)){			
				//Definir el tipo de contenido que tendra el archivo creado
				header("Content-type: text/xml");	 
				
				if($tipoRegistro=="fallas"){//Crear XML con los datos de la Falla
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>		
							<tipoFalla>$datosReg[tipo]</tipoFalla>		
							<descripcion>$datosReg[descripcion]</descripcion>		
							<tiempo>$datosReg[tiempo_gastado]</tiempo>					
						</existe>");
				}
				else if($tipoRegistro=="consumos"){//Crear XML con los datos del Consumo
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>									
							<cantidad>$datosReg[cantidad]</cantidad>					
						</existe>");
				}
				else if($tipoRegistro=="explosivos"){//Crear XML con los datos del Explosivo
				
					//Obtener los datos del Explosivo
					$datosExp = mysql_fetch_array(mysql_query("SELECT * FROM catalogo_explosivos WHERE id_explosivos = $noReg"));
					
					//Arreglo que contendra las Categorias de los explosivos
					$categorias = array(1=>"DISPARO EN TOPE SIN AGUA",2=>"DISPARO EN TOPE CON AGUA",3=>"AMBOS");
				
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>									
							<idExplosivo>$noReg</idExplosivo>
							<medida>$datosExp[medida]</medida>
							<categoria>".$categorias[$datosExp['categoria']]."</categoria>
							<cantidad>$datosReg[cantidad]</cantidad>					
						</existe>");
				}
			}//Cierre if($datosReg=mysql_fetch_array($rs))
			else{
				//Definir el tipo de contenido que tendra el archivo creado
				header("Content-type: text/xml");	 
				//Crear XML de la clave Generada
				echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
			}
		}//Cierre if($tipoOperacion=="cargar")	
		
		
		
		//Cargar los datos complementarios que seran mostrados en la pagina donde se registra el consumo de explosivos
		else if($tipoOperacion=="cargarDatosTNT"){
			//Obtener los datos necesarios para borrar los registros de Fallas, Consumos o Explosivos
			$idTNT = $_GET["idTNT"];										
			
			//Sentencia SQL para quitar las fallas de la Bitácora de Rezagado
			$sql_stm = "SELECT medida, categoria FROM catalogo_explosivos WHERE id_explosivos = '$idTNT'";
						
			//Ejecutar la Sentencia previamente creada
			$rs = mysql_query($sql_stm);
			//Revisar el resultados de la ejecucion de la Sentencia SQL
			if($datosTNT=mysql_fetch_array($rs)){			
				//Determinar la categoria de acuerdo a los comentarios de la tabla 'catalogo_explosivos' de la BD de Desarrollo
				//1 => Disparo en Tope Sin Agua, 2 => Disparo en Tope con Agua, 3 => Disparo en Tope Sin Agua y Con Agua
				$categoria = "";
				if($datosTNT['categoria']==1)
					$categoria = "Disparo en Tope Sin Agua";
				else if($datosTNT['categoria']==2)
					$categoria = "Disparo en Tope Con Agua";
				else if($datosTNT['categoria']==3)	
					$categoria = "Disparo en Tope Sin Agua y Con Agua";
				
				//Definir el tipo de contenido que tendra el archivo creado
				header("Content-type: text/xml");	 
				//Crear XML de la clave Generada
				echo utf8_encode("
					<existe>
						<valor>true</valor>		
						<medida>$datosTNT[medida]</medida>		
						<categoria>$categoria</categoria>		
					</existe>");
			}
			else{
				//Definir el tipo de contenido que tendra el archivo creado
				header("Content-type: text/xml");	 
				//Crear XML de la clave Generada
				echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
			}
		}//Cierre else if($tipoOperacion=="cargarDatosTNT")
		
		
		
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if (isset($_GET["idBit"]))
	
	
?>