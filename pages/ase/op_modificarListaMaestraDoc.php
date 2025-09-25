<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 09/Noviembre/2011                                      			
	  * Descripción: Este archivo permite descargar los documentos en el servidor asi como en la Base de datos
	  **/
	 	
	
	//Función que permite mostrar los documentos Registrados en las fechas especificadas
	function mostrarResultados(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT DISTINCT id_manual, nombre, manual_calidad.no_rev AS revManu, manual_calidad.entrada_vigor,id_clausula, titulo_clausula,id_procedimiento,
		           nombre_procedimiento, catalogo_procedimientos.no_rev AS revProc, catalogo_procedimientos.entrada_vigor,no_forma_instructivo,
				   nombre_forma_instructivo, rev_forma_instructivo, entrada_vigor_forma_instructivo FROM ((((manual_calidad JOIN catalogo_clausulas ON 
				   id_manual=manual_calidad_id_manual) JOIN catalogo_procedimientos ON catalogo_clausulas_id_clausula=id_clausula)JOIN lista_maestra_documentos 
				   ON id_procedimiento=catalogo_procedimientos_id_procedimiento)) WHERE lista_maestra_documentos.manual_calidad_id_manual='$_POST[cmb_manu]' 
				   AND id_clausula='$_POST[cmb_clausula]'AND id_procedimiento='$_POST[cmb_procedimiento]'";
		

		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		$procedimiento="";
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){?>	
			
			<table cellpadding='5' width='100%'>
				<tr><td colspan='9' class='nombres_columnas' align='center'><?php echo $datos['id_manual'];?>.-  <?php echo $datos['nombre'];?> REVISI&Oacute;N  NO. <?php echo $datos['revManu'];?></td></tr>
				<tr><td colspan='9' class='nombres_columnas' align='center'>CLAUSULA <?php echo $datos['id_clausula'];?>.- <?php echo $datos['titulo_clausula'];?></td></tr>
			</table>			
			<table cellpadding='5' width='100%'>	
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>CLAVE PROCEDIMIENTO</td>
					<td class='nombres_columnas' align='center'>NOMBRE PROCEDIMIENTO</td>
					<td class='nombres_columnas' align='center'>REV. PROCEDIMIENTO</td>
					<td class='nombres_columnas' align='center'>ENTRADA VIGOR PROCEDIMIENTO</td>
					<td class='nombres_columnas' align='center'>NO. FORMA/INST</td>
					<td class='nombres_columnas' align='center'>NOMBRE FORMA/INST</td>
					<td class='nombres_columnas' align='center'>REV. FORMA/INST</td>
					<td class='nombres_columnas' align='center'>ENTRADA VIGOR</td>
				</tr><?php 
			$nom_clase = "renglon_gris";
			$cont = 1;	
			
			do{	
			$contarNorma=mysql_num_rows(mysql_query("SELECT no_forma_instructivo FROM (lista_maestra_documentos 
					JOIN catalogo_procedimientos ON catalogo_procedimientos_id_procedimiento=id_procedimiento) WHERE 
														catalogo_procedimientos_id_procedimiento='$datos[id_procedimiento]' 
														AND lista_maestra_documentos.manual_calidad_id_manual='$_POST[cmb_manu]'"));												
				if($datos['id_procedimiento']!=$procedimiento){?>
					<tr>
						<td rowspan="<?php echo $contarNorma;?>" class='nombres_filas' align='center'>
							<input type='radio' id='rdb_id' name='rdb_id' value="<?php echo $datos['id_manual']."/".$datos['id_procedimiento'];?>"/>
						</td>				
						<td rowspan="<?php echo $contarNorma;?>" class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['id_procedimiento'];?></td>					
						<td rowspan="<?php echo $contarNorma;?>" class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['nombre_procedimiento'];?></td>
						<td rowspan="<?php echo $contarNorma;?>" class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['revProc'];?></td>
						<td rowspan="<?php echo $contarNorma;?>" class='<?php echo $nom_clase;?>' align='center'><?php echo modFecha($datos['entrada_vigor'],6);?></td>
				<?php }?>
						<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['no_forma_instructivo'];?></td>
						<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['nombre_forma_instructivo'];?></td>
						<td class='<?php echo $nom_clase;?>' align='center'><?php echo $datos['rev_forma_instructivo'];?></td>
						<td class='<?php echo $nom_clase;?>' align='center'><?php echo modFecha($datos['entrada_vigor_forma_instructivo'],6);?></td>
				</tr><?php 
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			$procedimiento=$datos['id_procedimiento'];	
			}while($datos=mysql_fetch_array($rs)); 	
			?><input type="hidden" name="hdn_procedimiento" id="hdn_procedimiento" value="<?php echo $_POST['cmb_procedimiento'];?>"/>
			<input type="hidden" name="hdn_clausula" id="hdn_clausula" value="<?php echo $_POST['cmb_clausula'];?>"/><?php 
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Registros en Lista Maestra de Documentos </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	
	//Verificamos si viene definido el boton; de ser asi almacenar la información
	if(isset($_POST["sbt_guardar"])){
		//Si no existe la lista Maestra; quiere decir que se guardo un registro por lo tanto se dara de baja el arreglo lista; 
		//Arreglo que funcion como bandera indicando que se guardo un registro
		if(!isset($_SESSION["lista_maestra"])){
			//Para evitar que marque errores; se verifica que la sesion exista
			if(isset($_SESSION['lista'])){
				//Liberamos la sesion
				unset($_SESSION['lista']);
			}
			//Si el usuario presiona guardar y no existe el arreglo de sesion quiere decir que estan intentando guardar sin alamcenar los formatos
			//Enviamos mensaje al Usuario indicando que no se pueden guardar registros si no se han guardado formatos
			?>
				<script language="javascript" type="text/javascript">
					setTimeout("alert('No se Puede Guardar el Registro no se han Registrado Formatos')",500);
				</script>
			<?php 
		}// Cierre if(!isset($_SESSION["lista_maestra"]))
		
		//Si existe el arreglo de sesión lista Maestra y se ah presionado guardar; se permite el registro llamando a la función correspondiente
		if(isset($_SESSION['lista_maestra'])){
			//Llamamos la funcion guardarModificacionRegistro
			guardarModificacionRegistro();
		}
		
			
		//Si existe el arregloo de sesion lista (funciona como bandera ya que se activa cuando se guarda con exito); enviamos el mensaje indicando que se guardo 
		//el registro con éxito
		if(isset($_SESSION['lista'])&&isset($_POST['sbt_guardar'])){?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('Registro de Clasula Guardado Con Éxito. Agregue Más Clausulas o Presione Cancelar para Salir Del Registro');enviarMensaje();",1000);
				//Función que permite enviar un mensaje de notificación al usuario indicando que el registro se realizo con éxito
				function enviarMensaje(){
					//Limpiamos las cajas de texto correspondientes a la clausula
					document.getElementById("txt_claveClausula").value="";
					document.getElementById("txa_tituloClausula").value="";
				}
			</script><?php
		}// Cierre if(isset($_SESSION['lista']))
	}//Cierre if(isset($_POST["sbt_guardar"])
	
	

	//Esta funcion permite registrar los Archivos en la BD
	function guardarModificacionRegistro(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Declaramos $band para verificar si hubo errores	
		$bandProc = 0;
		$bandForm = 0;
		$bandClau = 0;
		$bandManu = 0;
				
	
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha = modFecha($_POST["txt_fecha"],3);
		$tituloManual = strtoupper($_POST["txa_tituloManual"]);
		$claveManual = strtoupper($_POST["txt_claveManual"]);
		$claveClausula = strtoupper($_POST["txt_claveClausula"]);
		$noRevision = $_POST["txt_noRevManu"];
		$tituloClausula = strtoupper($_POST["txa_tituloClausula"]);									  
		
		//Variable para controlar el proceso de insercion dentro del foreach
		$conta = 0;		
				
		//Guardamos el valor de la clave anterior; ya que solo se debe realizar registros en la BD siempre y cuando sean diferentes
		$claveAnterior = $_SESSION['lista_maestra'][$conta]['cveProc'];									  
		
		//Recuperamos los datos necesarios en variables para el facil manejo
		$cveProc = $_POST['hdn_procedimiento'];
		
		//Ejecutamos la sentencia para eliminar los registros y proceder a almacenar los nuevos registros
		$stmDelProc = "DELETE FROM catalogo_procedimientos WHERE id_procedimiento='$cveProc' AND manual_calidad_id_manual='$claveManual'";
		
		//Ejecutamos la sentencia previamante creada
		$rsEliminarProc = mysql_query($stmDelProc);	
		
		if($_POST['hdn_clausulaOriginal']=!$claveClausula){
			//Eliminamos los registros de la clausula
			$stmDelClausula = "DELETE FROM catalogo_clausulas WHERE id_clausula = '$_POST[hdn_clausulaOriginal]'  AND manual_calidad_id_manual='$claveManual'";
			
			//Ejecutamos la sentencia previamante creada
			$rsEliminarClausula = mysql_query($stmDelClausula);
		}
		//Eliminamos los registros de norma
		$stmDelNorm = "DELETE FROM lista_maestra_documentos WHERE catalogo_procedimientos_id_procedimiento = '$_POST[hdn_procedimiento]' 
					   AND manual_calidad_id_manual='$claveManual'";
		
		//Ejecutamos la sentencia previamante creada
		$rsEliminarNorma = mysql_query($stmDelNorm);
		
		
		//Recorremos el arreglo lista_maestra para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['lista_maestra'] as $ind => $lista_maestra){								
			
			//Compraramos para conocer si el registro actual es diferente al anterior o si $conta es igual a cero; lo que nos da a entender que es el primer
			//registro por eso se permite la insercion
			if($claveAnterior!=$lista_maestra['cveProc'] || $conta==0){
				//Verificamos que el dato sea la fecha para modificarla y realizar la insercion en la Base de datos
				if($lista_maestra['fecha1']){
					$fecha1=modFecha($lista_maestra['fecha1'],3);
				}
				//Creamos la sentencia SQL para insertar los datos en la tabla catalogo_procedimientos
				$stm_sqlProc="INSERT INTO catalogo_procedimientos (id_procedimiento,catalogo_clausulas_id_clausula, nombre_procedimiento, no_rev, entrada_vigor,
							 manual_calidad_id_manual) 
							VALUES('$lista_maestra[cveProc]', '$claveClausula', '$lista_maestra[tituloProc]','$lista_maestra[noRev]', '$fecha1','$claveManual')";
				//Ejecutamos la sentencia previamante creada
				$rs2 = mysql_query($stm_sqlProc);
				if(!$rs2){
					$bandProc = 1;						
				}

				//Dejar la clave actual como anterior
				$claveAnterior=$_SESSION['lista_maestra'][$conta]['cveProc'];	
				
				//Incrementar el Contador
				$conta++;
			}
			//CAmbiamos el formato de la fecha
			if($lista_maestra['fecha2']){
				$fecha2=modFecha($lista_maestra['fecha2'],3);
			}				
				
			//Creamos la sentencia SQL para insertar los datos en lista_maestra_documentos
			$stm_sqlForma="INSERT INTO lista_maestra_documentos (catalogo_procedimientos_id_procedimiento,no_forma_instructivo, nombre_forma_instructivo,
						   rev_forma_instructivo, entrada_vigor_forma_instructivo,manual_calidad_id_manual)
						   VALUES('$lista_maestra[cveProc]', '$lista_maestra[noFormatoProc]',
						   '$lista_maestra[nombreForma]','$lista_maestra[noRevision]', '$fecha2','$claveManual')";
			
			//Ejecutar la sentencia previamente creadas
			$rs3 = mysql_query($stm_sqlForma);
			if(!$rs3){
				$bandForm = 1;						
			}			
		}//foreach($_SESSION['lista_maestra'] as $ind => $lista_maestra)
		
		//VErificamos que no exista el manual; si existe no se permite el registro
		$resClau=obtenerDato("bd_aseguramiento", "catalogo_clausulas", "id_clausula", "id_clausula", $claveClausula);
		if($resClau==""){			
			//Crear la sentencia para realizar el registro de los datos
			$stm_sqlClausla = "INSERT INTO catalogo_clausulas(id_clausula,manual_calidad_id_manual, titulo_clausula) VALUES('$claveClausula','$claveManual', 
						  '$tituloClausula')";	
			//Ejecutar la sentencia previamante creada
			$rs4 = mysql_query($stm_sqlClausla);
			if(!$rs4){
				$bandClau = 1;						
			}
		}										  
		//VErificamos que no exista el manual; si existe no se permite el registro
		$res=obtenerDato("bd_aseguramiento", "manual_calidad", "id_manual", "id_manual", $claveManual);
		//Comprobamos si $res es igual a vacio
		if($res==""){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "INSERT INTO manual_calidad(id_manual,nombre, no_rev, entrada_vigor)VALUES('$claveManual','$tituloManual', '$noRevision','$fecha')";			
			//Ejecutar la sentencia previamente creada 
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$bandManu = 1;						
			}
		}
		//Si band tomo el valor a 1; hubo errores en alguna ejecución de sentencias
		if($bandProc==1||$bandForm==1||$bandManu==1||$bandClau==1){
	
			//Variable para controlar el proceso de insercion dentro del foreach
			$conta=0;		
			
			//Recorremos el arreglo lista_maestra para insertar en la BD los datos guardados en el mismo
			foreach($_SESSION['lista_maestra'] as $ind => $lista_maestra){								
			
			//Compraramos para conocer si el registro actual es diferente al anterior o si $conta es igual a cero; lo que nos da a entender que es el primer
			//registro por eso se permite la insercion
			if($claveAnterior!=$lista_maestra['cveProc'] || $conta==0){
				//Verificamos que el dato sea la fecha para modificarla y realizar la insercion en la Base de datos
				if($lista_maestra['fecha1']){
					$fecha1=modFecha($lista_maestra['fecha1'],3);
				}
			 	//Creamos la sentencia SQL para insertar los datos en la tabla catalogo_procedimientos
				$stm_sqlProcDEL="DELETE FROM catalogo_procedimientos WHERE  manual_calidad_id_manual='$claveManual'";
				
				//Ejecutamos la sentencia previamante creada
				$rs2 = mysql_query($stm_sqlProcDEL);

				//Dejar la clave actual como anterior
				$claveAnterior=$_SESSION['lista_maestra'][$conta]['cveProc'];	
				
				//Incrementar el Contador
				$conta++;
			}
			
			if($bandProc==1||$bandForm==1||$bandManu==1||$bandClau==1){
				//CAmbiamos el formato de la fecha
				if($lista_maestra['fecha2']){
					$fecha2=modFecha($lista_maestra['fecha2'],3);
				}				
				
				//Creamos la sentencia SQL para insertar los datos en lista_maestra_documentos
				$stm_sqlForma="DELETE  FROM lista_maestra_documentos WHERE  manual_calidad_id_manual='$claveManual'";
			
				//Ejecutar la sentencia previamente creadas
				$rs3 = mysql_query($stm_sqlForma);
			}			
		}//foreach($_SESSION['lista_maestra'] as $ind => $lista_maestra)
		
		if($bandProc==1||$bandForm==1||$bandManu==1||$bandClau==1){
			//Crear la sentencia para ejecutar la eliminacion del manual seleccionado por el usuario
			$stm_sqlDelManual = "DELETE FROM manual_calidad WHERE id_manual='$claveManual'";					
			//Ejecutar la sentencia previamente creada
			$rsDel = mysql_query($stm_sqlDelManual);
		}
		if($bandProc==1||$bandForm==1||$bandManu==1||$bandClau==1){
			//Crear la sentencia para ejecutar la eliminacion del manual seleccionado por el usuario
			$stm_sqlDelCalidad = "DELETE FROM catalogo_clausulas WHERE manual_calidad_id_manual='$claveManual'";					
			//Ejecutar la sentencia previamente creada
			$rsDelCalidad = mysql_query($stm_sqlDelCalidad);			
		}?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('Registro No Guardado.\n Verificque Valores o Intente Nuevamente');",500);
			</script><?php 
			
			$error = mysql_error();	
			//echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$claveManual,"ModListaMaestraDoc",$_SESSION['usr_reg']);			 
			//Liberamos la sesion Lista MAestra
			unset($_SESSION["lista_maestra"]);
			//Creamos la sesion Lista
			$_SESSION['lista']=1;
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}	

	
	//Funcion encargada de mostrar la lista_maestra en una ventana pop up en caso de existir
	function mostrarListaMaestra($lista_maestra){
		//Verificamos que exista la session
		if($_SESSION['lista_maestra']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Detalle del Registro </caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>CVE PROC.</td>
					<td class='nombres_columnas' align='center'>TITULO PROC.</td>
					<td class='nombres_columnas' align='center'>ENTADA EN VIGOR PROC.</td>
					<td class='nombres_columnas' align='center'>NO. FORMATO PROC.</td>
					<td class='nombres_columnas' align='center'>CVE FORMA</td>
					<td class='nombres_columnas' align='center'>NOMBRE FORMA</td>
					<td class='nombres_columnas' align='center'>NO. REVISI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>ENTRADA EN VIGOR FORMA</td>
					<td class='nombres_columnas' align='center'>BORRAR</td>
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['lista_maestra'] as $key => $arrVale){
				echo "<tr>
						<td align='center'  class='nombres_filas'>$cont</td>
						<td align='center'  class='$nom_clase'>$arrVale[cveProc]</td>
						<td align='center'  class='$nom_clase'>$arrVale[tituloProc]</td>
						<td align='center'  class='$nom_clase'>$arrVale[fecha1]</td>
						<td align='center'  class='$nom_clase'>$arrVale[noRev]</td>
						<td align='center'  class='$nom_clase'>$arrVale[noFormatoProc]</td>
						<td align='center'  class='$nom_clase'>$arrVale[nombreForma]</td>
						<td align='center'  class='$nom_clase'>$arrVale[noRevision]</td>
						<td align='center'  class='$nom_clase'>$arrVale[fecha2]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verRegFormatos.php?noRegistro=<?php echo $key;?>'"/>
					</td><?php				
			echo "</tr>";					
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			};
			echo " </table>";
		}
	}
?>