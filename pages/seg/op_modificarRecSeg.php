<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 17/Febrero/2012
	  * Descripción: Este archivo contiene funciones para modificar la información relacionada con el registro de los recorridos de seguridad
	**/
		
	//Función que permite mostrar los documentos Registrados en las fechas especificadas
	function mostrarRegistros(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
			
		//Verificamos el contenido del post
		if(isset($_POST['cmb_id'])){
			//Recuperamos los datos del POST
			$id = $_POST['cmb_id'];	
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM recorridos_seguridad   WHERE id_recorrido = '$id' ORDER BY id_recorrido ";
			//Creaqmos titulo
			$titulo = "REGISTROS DE RECORRIDOS DE SEGURIDAD CON CLAVE <u><em><strong>$id</strong></em></u>";
			//Titulo en caso de no existir registros
			$noTitulo = "No existen Registros de Recorrido de Seguridad con Clave <strong>$id</strong>";
		}
		else{//En caso de que en el post existan las fechas
			//Modificamos las fechas para el uso con la sentencia SQL
			$fechaIni = modFecha($_POST['txt_fechaIni'],3);
			$fechaFin = modFecha($_POST['txt_fechaFin'],3);
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM recorridos_seguridad WHERE  fecha>='$fechaIni' AND fecha<='$fechaFin' ORDER BY id_recorrido";
			//Creamos el titulo
			$titulo = "REGISTROS DE RECORRIDOS DE SEGURIDAD DE <em>".$_POST['txt_fechaIni']."</em> A <em>".$_POST['txt_fechaFin']."";
			//Creamos el titulo en caso de no existir registros
			$noTitulo = "No existen Registros de Recorrido de Seguridad de <em>".$_POST['txt_fechaIni']."</em> a <em>".$_POST['txt_fechaFin']."";
		}
		
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$titulo</caption></br>
					<tr>
						<td class='nombres_columnas' align='center'>MODIFICAR</td>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>RESPONSABLE</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
						<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A</td>
						<td class='nombres_columnas' align='center'>DEPARTAMENTOS</td>
						<td class='nombres_columnas' align='center'>REGISTRO FOTOGR&Aacute;FICO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Arreglo que permite el almacenamiento de el resultado de la consulta		
				$arrArch=array();
				//Consulta que permite descargar el archivo 
				$stm_sqlArch = "SELECT * FROM registro_fotografico WHERE recorridos_seguridad_id_recorrido='$datos[id_recorrido]' ORDER BY nom_archivo";
				//Ejecutamos la sentencia Previamente creada
				$rs2=mysql_query($stm_sqlArch);
				//Contamos el Numero de archivos
				$noReg = mysql_num_rows(mysql_query($stm_sqlArch));
				//Guardamos el resultado de la consulta en $arrArch
				$arrArch=mysql_fetch_array($rs2);			
				echo "	<tr>	
							<td class='$nom_clase' align='center'>";?>
								<input type="radio" id="rdb_id" name="rdb_id" value="<?php echo $datos['id_recorrido'];?>" 
								onclick="hdn_btn.value='radio';cambiarSubmitRS();document.frm_verDetalle.submit();"/><?php echo "
							</td>
							<td class='$nom_clase' align='center'>$cont</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase' align='center'>$datos[responsable]</td>	
							<td class='$nom_clase' align='center'>$datos[observaciones]</td>";?>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<input name="btn_verReg" type="button" class="botones" id="btn_verReg"  value="Anomal&iacute;a" 
								title="Ver Anomal&iacute;as Registradas" onclick="verAnomalias('<?php echo $datos['id_recorrido'];?>');"
								onmouseover="window.status='';return true" /> 
							</td>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<input name="bn_verDpto" type="button" class="botones" id="bn_verDpto"  value="Departamentos" 
								title="Ver Departamentos Registrados" onclick="verDepartamentosRecSeg('<?php echo $datos['id_recorrido'];?>');"
								onmouseover="window.status='';return true" />
							</td>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<?php
								//Verificamos el numero de registros para ver que boton sera el mostrado
								if($noReg==1){?>
									<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
									title="Descargar Imagen <?php echo $arrArch['nom_archivo'];?>" 
									onClick="javascript:window.open('marco_descargaRS.php?id=<?php echo $arrArch['detalle_recorridos_seguridad_id_detalle_recorrido_seguridad'];?>&nomArchivo=<?php echo $arrArch['nom_archivo'];?>&ruta=<?php echo $datos['id_recorrido'];?>',
									'_blank','top=300, left=450, width=1, height=1, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/><?php 
								}
								elseif($noReg>1){?>
									<input name="btn_verFoto" type="button" class="botones" id="btn_verFoto"  value="Ver Fotos" 
									title="Ver Registro Fotogr&aacute;fico" onclick="verFotografias('<?php echo $datos['id_recorrido'];?>');"
									onmouseover="window.status='';return true" /><?php 
								}
								elseif($noReg==0){?>
									<input name="btn_verFoto" type="button" class="botones" id="btn_verFoto"  value="Descargar"  disabled="disabled"
									title="No Existe Registro Fotog&aacute;ficon Para El Registro Seleccionado" 
									onclick="verFotografias('<?php echo $datos['id_recorrido'];?>');"
									onmouseover="window.status='';return true" /><?php 
								}?>
							</td><?php
						$idRec = $datos['id_recorrido'];
					echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
			?>
			<input type='hidden' name="hdn_consulta" id="hdn_consulta" value="<?php echo $stm_sql;?>"/>
			<input type='hidden' name="hdn_tipoReporte" id="hdn_tipoReporte" value="reporteRecorridosSeguridad"/>
			<input type='hidden' name="hdn_nomReporte" id="hdn_nomReporte" value="ReporteRecorridosSeguridad"/>
			<input type='hidden' name="hdn_msg" id="hdn_msg" value="REPORTE <?php echo $titulo;?>"/>
			<?php 
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<p class='msje_correcto' align='center'></br></br></br></br>$noTitulo</p>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Función que permite mostrar las fotos registradas segun el id
	function mostrarFotosRS($id){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL si viene de la alerta
		$stm_sql ="SELECT * FROM registro_fotografico WHERE recorridos_seguridad_id_recorrido='$id' ORDER BY nom_archivo";
					
		//Ejecutamos la sentencia SQL 
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>REGISTRO FOTOGR&Aacute;FICO</caption>
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>ARCHIVO</td>
						<td class='nombres_columnas' align='center'>DESCARGAR</td>
					</tr>";
			//Contamos el Numero de archivos
			$noReg = mysql_num_rows(mysql_query($stm_sql));
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas'>$cont</td>
					<td class='$nom_clase'>$datos[nom_archivo]</td>";?>
					<td class="<?php echo $nom_clase;?>">
						<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
						title="Descargar Imagen<?php echo $datos['nom_archivo'];?>" 
						onClick="javascript:window.open('marco_descargaRS.php?id=<?php echo $datos['detalle_recorridos_seguridad_id_detalle_recorrido_seguridad'];?>&nomArchivo=<?php echo $datos['nom_archivo'];?>&ruta=<?php echo $id;?>',
								'_blank','top=300, left=450, width=1, height=1, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/><?php 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Archivos Registrados </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Función que permite mostrar los documentos Registrados
	function mostrarDepartamentosRecSeg($id){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL si viene de la alerta
		$stm_sql ="SELECT catalogo_departamentos_id_departamento FROM alertas_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$id'";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>DEPARTAMENTOS REGISTRADOS</caption>
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
					</tr>";
			//Contamos el Numero de archivos
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Obtenemos el nombre del Departamento
				$depto=obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datos['catalogo_departamentos_id_departamento']);
				//Ponemos en mayuscula dicho valor
				$depto = strtoupper($depto);		
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'>$cont</td>
						<td class='$nom_clase'>$depto</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Departamentos Registrados </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	//Desplegar la informacion de los Recorridos REgistrados
	function mostrarRegRecorridos($recorridosSeg, $claveReg){
	if(isset($_SESSION["recorridosSeg"]))
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Detalle del Registro</strong></p></caption>   			
				<tr>
					<td class='nombres_columnas' align='center'>BORRAR</td>
					<td class='nombres_columnas' align='center'>NO.</td>
        			<td class='nombres_columnas' align='center'>&Aacute;REA</td>
					<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A DETECTADA</td>
					<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A CORREGIDA</td>
					<td class='nombres_columnas' align='center'>LUGAR</td>
					<td class='nombres_columnas' align='center'>REGISTRO FOTOGR&Aacute;FICO</td>
      			</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$contar =count($recorridosSeg);
		foreach ($recorridosSeg as $ind => $registro) {
			echo "<tr>";?>
			<script language="javascript" type="text/javascript">
				function confirmarBorrado(){
						if(confirm("¿Esta Seguro que Quiere Borrar Este Registro?\n La Siguiente Información Relacionada con el se Perderá:\n*Registro De Recorrido de Seguridad\n *Alertas de Seguridad a Departamentos")){
							location.href='op_modificarRecSeg.php?id=<?php echo $claveReg;?>&noAn=<?php echo $registro['noAn'];?>';
						}
					}				
				</script>
					<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="<?php if($contar!=1){ ?>location.href='frm_modificarRecSeg2.php?noRegistro=<?php echo $ind;?>&noAn=<?php echo $registro['noAn'];?>&claveReg=<?php echo $claveReg?>';<?php } else if($contar==1){?>confirmarBorrado();<?php }?>"/>
					</td><?php 
			echo   "<td align='center' class='$nom_clase' align='center'>$cont</td>
					<td align='center' class='$nom_clase' align='center'>$registro[area]</td>
					<td align='center' class='$nom_clase' align='center'>$registro[anomaliaDet]</td>
					<td align='center' class='$nom_clase' align='center'>$registro[anomaliaCor]</td>
					<td align='center' class='$nom_clase' align='center'>$registro[lugar]</td>";?>
					<td class="<?php echo $nom_clase;?>" align="center">
						<input name="btn_regFot" type="button" class="botones_largos" id="btn_regFot"  value="Registro Fotogr&aacute;fico" 
						title="Registro Fotogr&aacute;fico" onclick="abrirModRegistroFot('<?php echo $registro['noAn'];?>', '<?php echo $claveReg; ?>');"
						onmouseover="window.status='';return true" />
					</td><?php 			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion 	
	
	
	//Función que permite eliminar la foto al presionar el boton eliminar registro en el formulario
	function eliminarFoto($id, $idAnom){
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia para consultar 
		$stm_sql ="SELECT * FROM registro_fotografico WHERE recorridos_seguridad_id_recorrido='$id'  
				   AND detalle_recorridos_seguridad_id_detalle_recorrido_seguridad='$idAnom'";
				
		//Ejecutamos la sentencia
		$rs=mysql_query($stm_sql);
		
		//Creamos la sentencia para eliminar el registro de la tabla de registro Fotografico 
		$stmDelRegFot = "DELETE  FROM registro_fotografico WHERE recorridos_seguridad_id_recorrido='$id' AND
				 		 detalle_recorridos_seguridad_id_detalle_recorrido_seguridad='$idAnom'";
			
		//Ejecutamos la sentencia para eliminar el registro de la tabla de registro fotografico
		$rsDelRegFot = mysql_query($stmDelRegFot);
		
		//Creamos la sentencia para eliminar el registro de la tabla de de detalle del registro
		$stmDelDetFot = "DELETE  FROM detalle_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$id' AND id_detalle_recorrido_seguridad='$idAnom'";
			
		//Ejecutamos la sentencia para eliminar el registro de la tabla de registro fotografico
		$rsDelDetFot = mysql_query($stmDelDetFot);
		
		//verificamos que la sentencia sea ejecutada con exito
		if($datos=mysql_fetch_array($rs)){
			do{
				//Guardamos los datos necesarios para poder tomarlos de la consulta e indicar que archivo sera eliminado						
				$ruta="documentos/".$datos["recorridos_seguridad_id_recorrido"];
				$nombreArchivo=$datos["nom_archivo"];	
			
				//Abrimos el archivo y reccorremos en busqueda de sub-carpetas o archivos
				if($gestor = opendir($ruta)) {
	    			while(false !== ($arch = readdir($gestor))){
						if ($arch != "." && $arch != ".."){
					   		//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
							@unlink($ruta."/".$nombreArchivo);
						}
	    			}
				}
		   	 	closedir($gestor);
			}while($datos=mysql_fetch_array($rs));
		}
		else{//De lo contrario se elimina solo el registro de la sesion
			if(isset($_SESSION['registroFotografico'])){
				//Verificamos la existencia del directorio
				if($gestor = opendir("documentos/".$id)){
	    			while(false !== ($arch = readdir($gestor))){
						if ($arch != "." && $arch != ".."){
							$archSecc=split("_", $arch);
							if($archSecc[0]==$idAnom){
								@unlink("documentos/".$id."/".$arch);
							}
						}
		    		}
				}
				//Cerramos el directorio
				closedir($gestor);
				//Declaramos la variable pos que nos servira para almacenar la posicion
				$pos="";
				//Ciclo que nos permite contar las fotos registradas para dicha anomalia
				foreach($_SESSION['registroFotografico'] as $key => $arrVale){
				//Comparamos loq ue viene en la sesión con la anomalia tomada del get
						//Almacenamos la posicion del arreglo donde esta contenida la foto que se desea eliminar					
						$pos = $key;
						//Frenamos el ciclo
						break;	
						if($arrVale['anomalia']==$idAnom){
						

					}
				}
				//Comprobamos que $pos no se encuentre vacia; esto quiere decirq ue entro al ciclo anterior por lo cual tiene un valor
				if($pos!=""){
					//Liberamos la sesion en la posicion indicada
					unset($_SESSION['registroFotografico'][$pos]);
					//Recorremos el registro
					$_SESSION['registroFotografico'] = array_values($_SESSION['registroFotografico']);
				}
			}
		}
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	}
	
	
	//Desplegar las fotos registradas en el apartado Registro Fotografico
	function mostrarFotossReg($registroFotografico, $anomalia, $tam){
		echo "<div id='tabla-fotos' class='borde_seccion2'>";
			if(isset($_SESSION["registroFotografico"])){
				$clave=$_SESSION["registroFotografico"][0]["clave"];
			}
			echo "<table cellpadding='5' width='100%'>";
			echo "<caption><p class='msje_correcto'><strong>Registro Fotogr&aacute;fico Recorrido de Seguridad No. ". $clave."</strong></p></caption>
				  <tr>
					<td class='nombres_columnas' align='center'>NO.</td>
        			<td class='nombres_columnas' align='center'>NOMBRE ARCHIVO</td>
      			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			foreach($_SESSION['registroFotografico'] as $key => $arrVale){
				//VErificamos que la nomalia del arreglo sea la anomalia enviada en el get; la cual indica el registro que se debe mostrar
				if($arrVale['anomalia']==$anomalia){
					echo "<tr>
							<td align='center'  class='$nom_clase'>$cont</td>
							<td align='center'  class='$nom_clase'>$arrVale[archivo]</td>";				
					echo "</tr>";					
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}
			}		
			echo "</table>";
		echo "</div>";
	}//Fin de la funcion 	
	
	
	//Esta funcion permite subir Archivos que forman parte del registro de los recorridos de seguridad
	function subirFotos($clave, $anomalia, $cont){
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Bandera que permite saber si el archivo cargado es verdadera
		$band=true;
	
		//Verificamos el tipo de archivo
		if((substr($_FILES['file_documento']['type'],0,5) != 'image')){
			?>
				<script>
					setTimeout("alert('El Formato del Archivo no es Válido. Sólo se Permiten Imágenes');",500);
				</script>
			<?php			 
			$band = false;
			
		}
		//De lo contrario el archivo es valido y se procedera a subir el archivo en el lugar correspondiente
		else{
			//Creamos la variable ruta que servira para abrir la misma	
			$Ruta='';	
				
			//Creamos la carpeta inicial dentro de documentos
			$carpeta='documentos/'.$clave;
			//Abrimos la ruta para crear la carpeta
   			$dir = opendir($Ruta); 
			//Verificamos que el archivo haya sido updated
			 
			//Si $carpeta no ah sido creado se crea con mkdir
			if(!file_exists($carpeta."/")){
				mkdir($carpeta."/", 0777);
			}
			//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
			if (!file_exists($carpeta."/".$_FILES['file_documento']['name'])){
		    	move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta."/".$_FILES['file_documento']['name']); 
				//llamar la funcion que se encarga de reducir el peso de la fotografia 
				redimensionarFoto($carpeta."/".$_FILES['file_documento']['name'],$_FILES['file_documento']['name'],$carpeta."/",100,100);
				rename($carpeta."/".$_FILES['file_documento']['name'], $carpeta."/".$anomalia."_".$cont."_".$_FILES['file_documento']['name']);?>
				<script>
					setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Cargado Correctamente');",500);
				</script>
				<?php
			}
			else{
				$band=false;?>
				<script>
					setTimeout("alert('El Archivo <?php echo $_FILES['file_documento']['name'];?> ya existe');",500);
				</script><?php
			}
		}
		
		return $band;
		
	}//Fin de la funcion subirFotos($clave)
	
	//Funcion que borra el arreglo de sesion y las fotos en caso de cancelar
	function borrarFotos($carpeta){
		foreach ($_SESSION["registroFotografico"] as $ind => $doc){
			//Variable que obtiene el nombre del Archivo
			$nombreArchivo=$doc["archivo"];
			//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
			@unlink("documentos/".$carpeta."/".$nombreArchivo);
		}
	}//Fin de la funcion borrarFotos()
	
	//Funcion que nos permite obntener el id del detalle del registro 
	function obtenerIdDetRS(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		$id_cadena="";
			
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_detalle_recorrido_seguridad)+1 AS cant FROM detalle_recorridos_seguridad";
		
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant==NULL)
				$id_cadena=1;
			else
				$id_cadena = $datos['cant'];
		}
			
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	
	/*************************************************************************************************************************************
	 ******************************************************PROCESO DE REGISTRO************************************************************
	 ************************************************************************************************************************************/
	
	//Esta funcion permite registrar los archivos de la bitacora en que se este trabajando
	function registrarFotosRecorrido($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		$band=0;
		//Creamos la sentencia para eliminar el contenido del registro fotografico
		$stmDel = "DELETE  FROM registro_fotografico WHERE recorridos_seguridad_id_recorrido='$clave'";
			
		//Ejecutamos la sentencia para eliminar el registro de la tabla del registro fotografico
		$rsDel = mysql_query($stmDel);
		
		//Registrar todos los materiales dados de alta en el arreglo $fotos
		foreach ($_SESSION["registroFotografico"] as $ind => $doc){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "INSERT INTO registro_fotografico(recorridos_seguridad_id_recorrido,detalle_recorridos_seguridad_id_detalle_recorrido_seguridad,nom_archivo)
			VALUES('$clave','$doc[anomalia]','$doc[archivo]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de registro_fotografico
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro  en el caso de que existan errores	
			if($band==1)
				break;	
		}
		if ($band==1){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		else{
			registrarOperacion("bd_seguridad",$clave,"ModificarRecSeg",$_SESSION['usr_reg']);
			$conn = conecta("bd_seguridad");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	if(isset($_POST['sbt_guardar'])){
		registrarRecorridos();
	}
	
	 //Funcion para guardar la informacion de los Recorridos de Seguridad 
	function registrarRecorridos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		
		//Recuperar la informacion del post
		$clave=$_POST['txt_clave'];
		$fecha = modFecha($_POST['txt_fecha'],3);
		$responsable = strtoupper($_POST['txt_responsable']);
		$observaciones = strtoupper($_POST['txa_observaciones']);
		$divDeptos = explode(",",$_POST['txt_ubicacion']);
		$idAlerta=obtenerIdAlertaRS();
 
		//Creamos la sentencia para eliminar el contenido de las alertas
		$stmDelAler = "DELETE  FROM alertas_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$clave'";
			
		//Ejecutamos la sentencia para eliminar el registro de la tabla del registro fotografico
		$rsDelAler = mysql_query($stmDelAler);
		
		//Guardamos la fecha actual
		$fechaAct= date("Y-m-d");
		//Ciclo que permite registrar los departamentos a los cuales se les enviara dicho concepto
		foreach($divDeptos as $key => $depto){
			//Obtenemos el id del departamento
			$idDpto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "depto", $depto);
			//Realizar la conexion a la BD de Seguridad Industrial
			$conn = conecta("bd_seguridad");
			//Crear la sentencia para realizar el registro de los datos
			$stm_sqlDepo = "INSERT INTO alertas_recorridos_seguridad(id_alerta_recorrido,recorridos_seguridad_id_recorrido,catalogo_departamentos_id_departamento,
				estatus, fecha_programada)
				VALUES('$idAlerta','$clave','$idDpto', '1','$fechaAct')";
								
			//Ejecutar la sentencia previamente creada 
			$rsDepto = mysql_query($stm_sqlDepo);
			if(!$rsDepto){
				borrarFotos($clave);
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
				break;			
			}
		}
		//Consulta Principal
 		$stm_sql= "UPDATE recorridos_seguridad SET id_recorrido='$clave', fecha='$fecha', responsable='$responsable', observaciones='$observaciones' 
				   WHERE id_recorrido='$clave'";
	
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
				//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarDetalle($clave);
		}
		else{
			borrarFotos($clave);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	 //Funcion para guardar la informacion de los Recorridos de Seguridad 
	function registrarDetalle($clave){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		$band=0;
		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		 
		$fecha =date("Y-m-d");
		
		//Creamos la sentencia para eliminar el contenido de la tabla de detalle de registro
		$stmDelDet = "DELETE FROM detalle_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$clave'";
			
		//Ejecutamos la sentencia para eliminar el registro de la tabla de detalle de registro 
		$rsDelDet = mysql_query($stmDelDet);
		
		//Ciclo que permite registrar los departamentos a los cuales se les enviara dicho concepto
		foreach($_SESSION['recorridosSeg'] as $key => $arrreg){
			$claveDet = obtenerIdDetRS();	
			//Crear la Sentencia SQL para Alamcenar los materiales agregados 
			$stm_sqlDet= "INSERT INTO detalle_recorridos_seguridad (id_detalle_recorrido_seguridad , recorridos_seguridad_id_recorrido,area, lugar,
					 anomalia,correccion_anomalia, fecha)
					VALUES ('$claveDet', '$clave', '$arrreg[area]', '$arrreg[lugar]', '$arrreg[anomaliaDet]', '$arrreg[anomaliaCor]', '$fecha')";
	
			//Ejecutar la Sentencia
			$rsDet=mysql_query($stm_sqlDet);
			if(!$rsDet){
				$band=1;	
			}
		}
		//Verificar Resultado
		if ($band==0){
			if(isset($_SESSION['registroFotografico'])){
				//Guardar la operacion realizada
				registrarFotosRecorrido($clave);
			}
			else{
				registrarOperacion("bd_seguridad",$clave,"ModificarRecSeg",$_SESSION['usr_reg']);
				$conn = conecta("bd_seguridad");
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
		}
		else{
			borrarFotos($clave);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	//Funcion que permiteobtener el id de la alerta
	function obtenerIdAlertaRS(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las tres letras la clave de la Bitacora
		$id_cadena = "ARC";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_alerta_recorrido) AS cant FROM alertas_recorridos_seguridad WHERE id_alerta_recorrido LIKE 'ARC$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
			
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()	
	//Función que permite mostrar las Anomalias
	function mostrarAnomalias($id){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL si viene de la alerta
		$stm_sql ="SELECT * FROM detalle_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$id'";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>ANOMAL&Iacute;AS REGISTRADAS</caption>
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>LUGAR</td>
						<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A</td>
						<td class='nombres_columnas' align='center'>CORRECCI&Oacute;N ANOMAL&Iacute;A</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
					</tr>";
			//Contamos el Numero de archivos
			$noReg = mysql_num_rows(mysql_query($stm_sql));
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas'>$cont</td>
					<td class='$nom_clase'>$datos[area]</td>
					<td class='$nom_clase'>$datos[lugar]</td>
					<td class='$nom_clase'>$datos[anomalia]</td>
					<td class='$nom_clase'>$datos[correccion_anomalia]</td>
					<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
				</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Anomal&iacute;as Registradas </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	
	
	
	if(isset($_GET['id'])){
		eliminarTodo($_GET['id'], $noAn);
	}

	
	function eliminarTodo($claveReg, $noAn){
		session_start();
	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizamos la conexion con la Base de datos
		$conn = conecta("bd_seguridad");
		
		//Variable que nos permtie conocer que el proceso se realizo con exito
		$band = 0;
		
		//Creamos la sentencia SQL que permitira el borrado ddel registro general
		$stm_delGral = "DELETE FROM recorridos_seguridad WHERE id_recorrido = '$claveReg'";
		
		//Ejecutamos la sentencia previamente Creada
		$rsDelGral = mysql_query($stm_delGral);
		
		if($rsDelGral){
			$band=1;
		}
		
		//Creamos la sentencia SQL que permitira el borrado ddel registro general
		$stm_delAler = "DELETE FROM alertas_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido = '$claveReg'";
		
		//Ejecutamos la sentencia previamente Creada
		$rsDelAler = mysql_query($stm_delAler);
		
		if($rsDelAler){
			$band=1;
		}
		
		$stm_delDet = "DELETE FROM detalle_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido = '$claveReg'";
		
		//Ejecutamos la sentencia previamente Creada
		$rsDelDet = mysql_query($stm_delDet);
		
		if($rsDelDet){
			$band=1;
		}
		 eliminarFoto($claveReg, $noAn);
		 	
		echo "<meta http-equiv='refresh' content='0;url=exito.php'>";

	}
	
?>