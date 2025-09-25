<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 17/Febrero/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../conexion.inc");			
	/**   Código en: \includes\ajax\cargarCombo.php                                   
      **/	
	
	//Funcion usada principalmente para traer Equipos de un Area que esten dadas de ALTA e ignorar las BAJAS
	if(isset($_GET["nomCampoRef1"]) && isset($_GET["nomCampoRef2"])){
		//Recuperar los datos a buscar de la URL
		$datoBusq = $_GET["datoBusq"];
		$BD = $_GET["BD"];	
		$tabla = $_GET["tabla"];
		$campoBusq = $_GET["campoBusq"];
		$campoRef = $_GET["campoRef"];
		$nomCampoEspecifico= $_GET["nomCampoEspecifico"];
		$nomCampoRefEsp= $_GET["nomCampoRef1"];
		$nomCampoRefEsp2= $_GET["nomCampoRef2"];
		$nomCampoEspecifico2= $_GET["nomCampoEspecifico2"];
		$conn = conecta($BD);
		//Crear la Sentencia SQL
		$sql_stm = "SELECT $campoBusq FROM $tabla WHERE $campoRef='$datoBusq' AND $campoBusq!='' AND $nomCampoEspecifico='$nomCampoRefEsp' AND $nomCampoEspecifico2='$nomCampoRefEsp2' ORDER BY $campoBusq";
		//Ejecutar la Sentencia previamente creada
		$rs_d = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs_d);
		$cont = 1;
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs_d)){
			echo "<existe><valor>true</valor><tam>$tam</tam>";
			do{
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("<dato$cont>$datos[$campoBusq]</dato$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs_d));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}
	else{
		//Funcion para extraer ID y Nombre, usado para Materiales principalmente
		if(isset($_GET["ord"])){
			//Recuperar los datos a buscar de la URL
			$datoBusq = $_GET["datoBusq"];		
			$BD = $_GET["BD"];	
			$tabla = $_GET["tabla"];
			$campoBusq = $_GET["campoBusq"];
			$campoId = $_GET["campoId"];
			$campoRef = $_GET["campoRef"];
			$ord=$_GET["ord"];
			//Conectarse a la BD
			$conn = conecta("$BD");
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT $campoId, $campoBusq FROM $tabla WHERE $campoRef='$datoBusq' AND $campoBusq!='' ORDER BY $ord";
			//Ejecutar la Sentencia previamente creada
			$rs = mysql_query($sql_stm);
			$tam = mysql_num_rows($rs);
			$cont = 1;
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");	 
			//Comparar los resultados obtenidos 
			if($datos=mysql_fetch_array($rs)){
				echo "<existe><valor>true</valor><tam>$tam</tam>";
				do{
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("
						<datoId$cont>$datos[$campoId]</datoId$cont>
						<dato$cont>$datos[$campoBusq]</dato$cont>");
					$cont++;
				}while($datos=mysql_fetch_array($rs));
				echo "</existe>";
			}
			else{
				echo "<valor>false</valor>";
			}
			//Cerrar la conexion a la BD
			mysql_close($conn);
		}//Cierre del if(isset($_GET['ord']))
		else{
			//Funciones para ordenar un combo mediante un campo
			if(isset($_GET['nomCampoOrd'])){
				//Recuperar los datos a buscar de la URL
				$datoBusq = $_GET["datoBusq"];		
				$BD = $_GET["BD"];	
				$tabla = $_GET["tabla"];
				$campoBusq = $_GET["campoBusq"];
				$campoRef = $_GET["campoRef"];		
				$nomCampoOrd=$_GET["nomCampoOrd"];
				//Conectarse a la BD
				$conn = conecta("$BD");
				//Crear la Sentencia SQL
				$sql_stm = "SELECT DISTINCT $campoBusq FROM $tabla WHERE $campoRef='$datoBusq' AND $campoBusq!='' ORDER BY $nomCampoOrd";
				//Ejecutar la Sentencia previamente creada
				$rs = mysql_query($sql_stm);
				$tam = mysql_num_rows($rs);
				$cont = 1;
				//Definir el tipo de contenido que tendra el archivo creado
				header("Content-type: text/xml");	 
				//Comparar los resultados obtenidos 
				if($datos=mysql_fetch_array($rs)){
					echo "<existe><valor>true</valor><tam>$tam</tam>";
					do{
						//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
						echo utf8_encode("<dato$cont>$datos[$campoBusq]</dato$cont>");
						$cont++;
					}while($datos=mysql_fetch_array($rs));
					echo "</existe>";
				}
				else{
					echo "<valor>false</valor>";
				}
				//Cerrar la conexion a la BD
				mysql_close($conn);
			}
			else{		
				//Obtener los datos para cargar el combo con el Id en la Propiedad value del ComboBox
				if(isset($_GET['campoId'])){
					//Recuperar los datos a buscar de la URL
					$datoBusq = $_GET["datoBusq"];		
					$BD = $_GET["BD"];	
					$tabla = $_GET["tabla"];
					$campoBusq = $_GET["campoBusq"];
					$campoId = $_GET["campoId"];
					$campoRef = $_GET["campoRef"];
					//Conectarse a la BD
					$conn = conecta("$BD");
					//Crear la Sentencia SQL
					$sql_stm = "SELECT DISTINCT $campoId, $campoBusq FROM $tabla WHERE $campoRef='$datoBusq' AND $campoBusq!='' AND puesto = 'LANZADOR' ORDER BY $campoId";
					//Ejecutar la Sentencia previamente creada
					$rs = mysql_query($sql_stm);
					$tam = mysql_num_rows($rs);
					$cont = 1;
					//Definir el tipo de contenido que tendra el archivo creado
					header("Content-type: text/xml");	 
					//Comparar los resultados obtenidos 
					if($datos=mysql_fetch_array($rs)){
						echo "<existe><valor>true</valor><tam>$tam</tam>";
						do{
							//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
							echo utf8_encode("
								<datoId$cont>$datos[$campoId]</datoId$cont>
								<dato$cont>$datos[$campoBusq]</dato$cont>");
							$cont++;
						}while($datos=mysql_fetch_array($rs));
						echo "</existe>";
					}
					else{
						echo "<valor>false</valor>";
					}
					//Cerrar la conexion a la BD
					mysql_close($conn);
				}//Cierre if(isset($_GET['campoId']))
				else{		 
					if(isset($_GET['nomCampoEspecifico'])){
						//Recuperar los datos a buscar de la URL
						$datoBusq = $_GET["datoBusq"];
						$BD = $_GET["BD"];	
						$tabla = $_GET["tabla"];
						$campoBusq = $_GET["campoBusq"];
						$campoRef = $_GET["campoRef"];
						$nomCampoEspecifico= $_GET["nomCampoEspecifico"];
						$nomCampoRefEsp= $_GET["nomCampoRefEsp"];
						$conn = conecta($BD);
						//Crear la Sentencia SQL
						$sql_stm = "SELECT $campoBusq FROM $tabla WHERE $campoRef='$datoBusq' AND $campoBusq!='' AND $nomCampoEspecifico='$nomCampoRefEsp' ORDER BY $campoBusq";
						//Ejecutar la Sentencia previamente creada
						$rs_d = mysql_query($sql_stm);
						$tam = mysql_num_rows($rs_d);
						$cont = 1;
						//Definir el tipo de contenido que tendra el archivo creado
						header("Content-type: text/xml");	 
						//Comparar los resultados obtenidos 
						if($datos=mysql_fetch_array($rs_d)){
							echo "<existe><valor>true</valor><tam>$tam</tam>";
							do{
								//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
								echo utf8_encode("<dato$cont>$datos[$campoBusq]</dato$cont>");
								$cont++;
							}while($datos=mysql_fetch_array($rs_d));
							echo "</existe>";
						}
						else{
							echo "<valor>false</valor>";
						}
						mysql_close($conn);
			
					}
					else{
						if(isset($_GET["opcCombo"]) && $_GET["opcCombo"]==1){
							$bd = $_GET["BD"];
							$tabla = $_GET["tabla"];
							$campoBusq = $_GET["campoBusq"];
							$id = $_GET["campoIdCombo"];
							//Conectarse a la BD
							$conn = conecta("$bd");
							//Crear la Sentencia SQL
							$sql_stm = "SELECT $id,$campoBusq FROM $tabla ORDER BY $id";
							//Ejecutar la Sentencia previamente creada
							$rs = mysql_query($sql_stm);
							//Extraer la cantidad de resultados
							$tam = mysql_num_rows($rs);
							//Contador para controlar los id de los tags
							$cont = 1;
							//Definir el tipo de contenido que tendra el archivo creado
							header("Content-type: text/xml");	 
							//Comparar los resultados obtenidos 
							if($datos=mysql_fetch_array($rs)){
								echo "<existe><valor>true</valor><tam>$tam</tam>";
								do{
									//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
									echo utf8_encode("
										<datoId$cont>$datos[$id]</datoId$cont>
										<dato$cont>$datos[$campoBusq]</dato$cont>
									");
									$cont++;
								}while($datos=mysql_fetch_array($rs));
								echo "</existe>";
							}
							else{
								echo "<valor>false</valor>";
							}
							//Cerrar la conexion a la BD
							mysql_close($conn);
						}
						else{
							//Recuperar los datos a buscar de la URL
							$datoBusq = $_GET["datoBusq"];
							$BD = $_GET["BD"];	
							$tabla = $_GET["tabla"];
							$campoBusq = $_GET["campoBusq"];
							$campoRef = $_GET["campoRef"];
							//Conectarse a la BD
							$conn = conecta("$BD");
							//Crear la Sentencia SQL
							$sql_stm = "SELECT DISTINCT $campoBusq FROM $tabla WHERE $campoRef='$datoBusq' AND $campoBusq!='' ORDER BY $campoBusq";
							//Ejecutar la Sentencia previamente creada
							$rs = mysql_query($sql_stm);
							$tam = mysql_num_rows($rs);
							$cont = 1;
							//Definir el tipo de contenido que tendra el archivo creado
							header("Content-type: text/xml");	 
							//Comparar los resultados obtenidos 
							if($datos=mysql_fetch_array($rs)){
								echo "<existe><valor>true</valor><tam>$tam</tam>";
								do{
									//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
									echo utf8_encode("<dato$cont>$datos[$campoBusq]</dato$cont>");
									$cont++;
								}while($datos=mysql_fetch_array($rs));
								echo "</existe>";
							}
							else{
								echo "<valor>false</valor>";
							}
							//Cerrar la conexion a la BD
							mysql_close($conn);
						}
					}	
				}//Cierre Else del if(isset($_GET['campoId']))
			}//FIN del else if(isset($_GET['nomCampoOrd'))
		}//FIN del else para if(isset($_GET['ord']))
	}//FIN del else para if(isset($_GET["nomCampoRef1"]) && isset($_GET["nomCampoRef2"]))

?>
