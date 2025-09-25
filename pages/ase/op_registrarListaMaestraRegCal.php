<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                           
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 16/Noviembre/2011                                      			
	  * Descripción: Este archivo permite guardar los registros dela Lista Maestra así como mostrar informacion para las diversas operaciones que consciernen
	  **/
	
	//Función que permite mostrar los Departamento para agregarlos al registro
	function mostrarDepartamentos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_usuarios");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT DISTINCT UPPER (depto) as depto FROM usuarios WHERE depto NOT LIKE 'Panel' AND depto NOT LIKE 'DireccionGral' ORDER BY depto";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>";
			echo "	
					<tr>
						<td colspan='2' class='nombres_columnas' align='center'>SELECCIONAR TODO </td>
						<td class='nombres_columnas' align='center'>
							<input type='checkbox' id='ckb_todo' name='ckb_todo' onclick='seleccionarTodo(this);'/>
						</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO </td>
						<td class='nombres_columnas' align='center'>DEPARTAMENTO </td>
					</tr>
					";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								<input type='checkbox' id='ckb_dpto$cont' name='ckb_dpto$cont' value='$datos[depto]' onclick='quitar(this);'/>
							</td>				
							<td class='$nom_clase' align='center'>$cont.-</td>					
							<td class='$nom_clase' align='left'>$datos[depto]</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			?>
				<input type="hidden" name="hdn_cant" id="hdn_cant" value="<?php echo $cont;?>"/>
			<?php 
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Departamentos Registrados </label>";
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Verificamos si viene definido el boton; de ser asi almacenar la información
	if(isset($_POST["sbt_guardar"])){
		//Llamamos la funcion guardarRegistro
		guardarRegistro();
	}	
	//Esta funcion permite registrar los Archivos en la BD
	function guardarRegistro(){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos variables para insercion de valores en la BD 
		$id_acceso="";
		
		//Verificamos que el combo acceso este definido y que no venga vacio. Si es asi recuperar el id correspondiente para almacenarlo en la BD
		if(isset($_POST["cmb_acceso"])&&$_POST["cmb_acceso"]!=""){
			$acceso = $_POST["cmb_acceso"];
			//Obtenemos el id del acceso que se encuentra almacenado en la BD para realizar la inserción
			$id_acceso=obtenerDato("bd_aseguramiento", "catalogo_acceso", "id_acceso", "acceso", $_POST["cmb_acceso"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_acceso
		if(isset($_POST["txt_acceso"])&&$_POST["txt_acceso"]!=""){
			$id_acceso=obtenerDato("bd_aseguramiento", "catalogo_acceso", "id_acceso", "acceso", $_POST["txt_acceso"]);
			if($id_acceso==""){
				//Obtenemos el id del acceso para realizar la insercion en la BD
				$id_acceso = obtenerIdAcceso();
				$stm_sql2 = "INSERT INTO catalogo_acceso(id_acceso,acceso) VALUES('$id_acceso','$_POST[txt_acceso]')";
				//Ejecutar la sentencia previamente creada
				$rs2 = mysql_query($stm_sql2);
				$acceso=$_POST['txt_acceso'];
			}
		}
		
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha=modFecha($_POST["txt_fecha"],3);
		$depto=$_POST["cmb_depto"];
		$indexacion=strtoupper($_POST["txt_indexacion"]);
		$no_formato=strtoupper($_POST["txt_noFormato"]);
		$perMtto=strtoupper($_POST["txt_perMtto"]);
		$noRevision=$_POST["txt_noRevision"];
		$dispFinal=strtoupper($_POST["txt_dispFinal"]);
		$docAso=strtoupper($_POST["txt_docAso"]);
		$titulo=strtoupper($_POST["txa_titulo"]);
		$metColeccion=strtoupper($_POST["txa_metColeccion"]);
		$ubicacion=strtoupper($_POST["txt_ubicacion"]);
		$id_reg=obtenerIdRegCal();
		
		//Crear la sentencia para realizar el registro de los datos
		$stm_sql = "INSERT INTO lista_maestra_registros_calidad(id_registro,catalogo_acceso_id_acceso, dpto_emisor, codigo_forma, no_rev, fecha_revision, titulo, ubicacion, metodo_coleccion, indexacion, periodo_mantenimiento, disposicion_final, doc_aso)
		 			                                      VALUES('$id_reg','$id_acceso', '$depto','$no_formato','$noRevision', '$fecha','$titulo','$ubicacion', '$metColeccion','$indexacion','$perMtto', '$dispFinal', '$docAso')";
						
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);
		if(!$rs){
			$band = 1;						
		}
		if ($band==1){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$no_formato." ".$depto,"RegListMaestRegCal",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	
	//Funcion que permite obtener el id de Acceso
	function obtenerIdAcceso(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_acceso)+1 AS cant FROM catalogo_acceso";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			if($cant==NULL)
				$id_cadena=1;
			else
				$id_cadena = $datos[0];
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()


	//Funcion que permite obtener el id de lista maestra
	function obtenerIdRegCal(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_registro)+1 AS cant FROM lista_maestra_registros_calidad";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant==NULL)
				$id_cadena=1;
			else
				$id_cadena = $datos[0];
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	?>