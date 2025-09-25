<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 21/Septiembre/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de AgregarMaterial en la BD
	  **/
	
	//Funcion que carga el inventario de almacen a partir de un archivo CSV
	function cargarInventario(){
		//Capturar los errores
		$errores="<table cellpadding='5' width='80%'><caption align='center' class='msje_correcto'>LISTA DE ERRORES GENERADOS DURANTE LA CARGA DEL INVENTARIO</caption><tr><td class='nombres_columnas' align='center'>N&Uacute;MERO</td><td class='nombres_columnas' align='center'>CLAVE DEL MATERIAL</td><td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N DETALLADA DEL ERROR</td></tr>";
		//Conectar a la BD de Almacen
		$conn=conecta("bd_almacen");
		//Creamos la variable ruta  para poder manejar los directorios
		$ruta='';
		//Asignamos a la variable carpeta; es decir asignamos el nombre de la misma
		$carpeta='tmp';
		//Creamos la variable $dir que permite abrir la ruta del archivo
		$dir = opendir($ruta);
		//Declaramos la variable para el control de registros correctos e incorrectos
		$ok=0;
		$error=0;
		//Se verifica que el archivo sea subido
		if (is_uploaded_file($_FILES['txt_archivo']['tmp_name'])){ 
			//Se comprueba que exista la carpeta
			if (!file_exists($carpeta."/"))
				//Si no existe se crea la carpeta; es creada con mkdir
				mkdir($carpeta."/", 0777); 
				$rs=false;
				//De lo contrario lo guarda en la carpeta existente
			if (!file_exists($_FILES['txt_archivo']['name'])){
				//Mueve el archivo a la carpeta creada
				move_uploaded_file($_FILES['txt_archivo']['tmp_name'], $carpeta."/".$_FILES['txt_archivo']['name']); 
				//Abrimos el archivo
				$fp = fopen ($carpeta."/".$_FILES['txt_archivo']['name'],"r");
				$cont=0;
				//Guardamos el archivo en $data y es recorrido
				while ($datos = fgetcsv ($fp, 1000, ",")) {	
					if($cont>0){
						if(isset($datos[3])){
							$idMaterial=str_replace("\t","",$datos[0]);
							$existencia=str_replace(",","",$datos[3]);
							if($existencia==""){
								$error++;
								if($error%2==0)
									$nom_clase = "renglon_blanco";
								else
									$nom_clase = "renglon_gris";
								$errores.="<tr><td class='nombres_filas' align='center'>$error</td><td class='$nom_clase' align='center'>$idMaterial</td><td class='$nom_clase' align='center'>NO Hay Existencia Asignada</td></tr>";
							}else{
								//Creamos la consulta
								$sql="UPDATE materiales SET existencia='$existencia' WHERE id_material='$idMaterial'";
								//Ejecutar la sentencia previamente creada
								$rs=mysql_query($sql);
								if($rs)
									$ok++;
								else{
									$error++;
									if($error%2==0)
										$nom_clase = "renglon_blanco";
									else
										$nom_clase = "renglon_gris";
									$errores.="<tr><td class='nombres_filas' align='center'>$error</td><td class='$nom_clase' align='center'>$idMaterial</td><td class='$nom_clase' align='center'>".mysql_error()."</td></tr>";
								}
							}
						}
						else{
							$idMaterial=str_replace("\t","",$datos[0]);
							$error++;
							if($error%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
							$errores.="<tr><td class='nombres_filas' align='center'>$error</td><td class='$nom_clase' align='center'>$idMaterial</td><td class='$nom_clase' align='center'>NO Hay Existencia Asignada</td></tr>";
						}
					}
					$cont++;
				}//Cierre del While	
				//Cerramos $fp 
				fclose ($fp);
			}
			//elimina el Archivo
			@unlink($carpeta."/".$_FILES['txt_archivo']['name']);
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
		if($error>0){
			$errores.="</table>";
			echo "<div id='tabla-inventario' class='borde_seccion2' align='center'>";
			echo $errores;
			echo "</div>";
		}
		if ($ok>0){
			registrarOperacion("bd_almacen","CargarInventario","CargarInventario",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='2;url=exito.php'>";
		}
		return $ok."¬".$error;
	}
	
	function cargarInventarioCompleto(){
		//Capturar los errores
		$errores="<table cellpadding='5' width='80%'><caption align='center' class='msje_correcto'>LISTA DE ERRORES GENERADOS DURANTE LA CARGA DEL INVENTARIO</caption><tr><td class='nombres_columnas' align='center'>N&Uacute;MERO</td><td class='nombres_columnas' align='center'>CLAVE DEL MATERIAL</td><td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N DETALLADA DEL ERROR</td></tr>";
		//Conectar a la BD de Almacen
		$conn=conecta("bd_almacen");
		//Creamos la variable ruta para poder manejar los directorios
		$ruta='';
		//Asignamos a la variable carpeta; es decir asignamos el nombre de la misma
		$carpeta='tmp';
		//Creamos la variable $dir que permite abrir la ruta del archivo
		$dir = opendir($ruta);
		//Declaramos la variable para el control de registros correctos e incorrectos
		$ok=0;
		$error=0;
		//Se verifica que el archivo sea subido
		if (is_uploaded_file($_FILES['txt_archivo']['tmp_name'])){
			//Se comprueba que exista la carpeta, Si no existe se crea la carpeta; es creada con mkdir
			if (!file_exists($carpeta."/"))
				mkdir($carpeta."/", 0777);
				//Si el archivo no existe, guardarlo
			if (!file_exists($carpeta."/".$_FILES['txt_archivo']['name'])){
				//Mueve el archivo a la carpeta creada
				move_uploaded_file($_FILES['txt_archivo']['tmp_name'], $carpeta."/".$_FILES['txt_archivo']['name']); 
				//Abrimos el archivo
				$fp = fopen ($carpeta."/".$_FILES['txt_archivo']['name'],"r");
				//Variable contador para verificar los registros que se agregan
				$cont=0;
				//Guardamos el archivo en $data y es recorrido
				while ($datos = fgetcsv ($fp, 1000, ",")) {	
					//Verificar que sea del segundo ciclo en delante para agregar datos
					//ya que la lectura del primer reistro es para hacer referencia a los encabezados
					if($cont>0){
						$txt_clave=strtoupper($datos[0]);
						$txt_nombre=strtoupper($datos[1]);
						$existencia=str_replace(",","",$datos[3]);
						$txt_nivelMinimo=str_replace(",","",$datos[4]);
						$txt_nivelMaximo=str_replace(",","",$datos[5]);
						$txt_puntoReorden=str_replace(",","",$datos[6]);
						$txt_costoUnidad=str_replace(",","",$datos[7]);
						$lineaArticulo=strtoupper($datos[8]);
						$grupo=strtoupper($datos[9]);
						$cmb_relevancia=strtoupper($datos[10]);
						$cmb_proveedor=strtoupper($datos[11]);
						$fecha=$datos[12];
						$txt_ubicacion=strtoupper($datos[13]);
						$txa_comentarios=strtoupper($datos[14]);
						$txt_codigoBarras=strtoupper($datos[15]);
						
						$unidadMedida=strtoupper($datos[2]);
						//Verificar si el codigo de barras esta repetido, en caso no estarlo, agregar el material
						$codigoBarrasRepetido=verificarCodigoBarrasClave($txt_clave);
						if($codigoBarrasRepetido==""){
							//Creamos la consulta
							$sql="INSERT INTO materiales (id_material,nom_material,existencia,nivel_minimo,nivel_maximo,re_orden,costo_unidad,linea_articulo,
								grupo,relevancia,proveedor,fecha_alta,ubicacion,comentarios,codigo_barras) 
								VALUES ('$txt_clave','$txt_nombre','$existencia','$txt_nivelMinimo','$txt_nivelMaximo','$txt_puntoReorden','$txt_costoUnidad','$lineaArticulo',
								'$grupo','$cmb_relevancia','$cmb_proveedor','$fecha','$txt_ubicacion','$txa_comentarios','$txt_codigoBarras')";
							//Ejecutar la sentencia previamente creada
							$rs=mysql_query($sql);
							if($rs){
								$sql="INSERT INTO unidad_medida (materiales_id_material,unidad_medida,factor_conv,unidad_despacho) VALUES ('$txt_clave','$unidadMedida','1','$unidadMedida')";
								$rs=mysql_query($sql);
								if($rs)
									$ok++;
								else{
									mysql_query("DELETE FROM materiales WHERE id_material='$txt_clave'");
									$error++;
									$errores.="
									<tr>
										<td class='nombres_filas' align='center'>$error</td>
										<td class='$nom_clase' align='center'>$txt_clave</td>
										<td class='$nom_clase' align='center'>Error al Registrar el Detalle del Material</td>
									</tr>";
								}
							}
							else{
								$error++;
								if($error%2==0)
									$nom_clase = "renglon_blanco";
								else
									$nom_clase = "renglon_gris";
								$errores.="
									<tr>
										<td class='nombres_filas' align='center'>$error</td>
										<td class='$nom_clase' align='center'>$txt_clave</td>
										<td class='$nom_clase' align='center'>".mysql_error()."</td>
									</tr>";
							}
						}
						else{
							$error++;
							if($error%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
							$errores.="
								<tr>
									<td class='nombres_filas' align='center'>$error</td>
									<td class='$nom_clase' align='center'>$txt_clave</td>
									<td class='$nom_clase' align='center'>C&oacute;digo de Barras REPETIDO Pertenece a $codigoBarrasRepetido</td>
								</tr>";
						}
					}
					$cont++;
				}//Cierre del While	
				//Cerramos $fp 
				fclose ($fp);
			}
			//elimina el Archivo
			@unlink($carpeta."/".$_FILES['txt_archivo']['name']);
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
		if($error>0){
			$errores.="</table>";
			echo "<div id='tabla-inventario' class='borde_seccion2' align='center'>";
			echo $errores;
			echo "</div>";
		}
		if ($error==0){
			registrarOperacion("bd_almacen","CargarInventario","CargarInventario",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='2;url=exito.php'>";
		}
		return $ok."¬".$error;
	}
	
	//Funcion que verifica el codigo de Barra, para ver que este no se repita en la Base de Datos
	//true	=>	El codigo SI esta repetido
	//false	=>	El codigo NO esta repetido
	function verificarCodigoBarrasClave($codigo){
		$rs=mysql_query("SELECT id_material FROM materiales WHERE codigo_barras='$codigo'");
		if($resultados=mysql_fetch_array($rs))
			$res=$resultados["id_material"];
		else
			$res="";
		return $res;
	}
?>