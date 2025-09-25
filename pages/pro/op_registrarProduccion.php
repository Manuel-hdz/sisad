<?php
	/**
	  * Nombre del Módulo: Producción
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 18/Julio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de registrar Produccion en la bitacora
	**/
	//Verificamos que exista el boton guardar de ser asi verificamos si existe el combo y el valor contenido
	if(isset($_POST["sbt_guardar"])){
		if(isset($_POST["hdn_cmbTipo"])){
			if($_POST["hdn_cmbTipo"]=="COLADO"||$_POST["hdn_cmbTipo"]=="COLADOS")
			guardarDetalleColado();
		}
		else
			unset($_SESSION["produccion"]);
		if(isset($_POST["txt_nuevoDestino"])){
			if($_POST["txt_nuevoDestino"]=="COLADOS"||$_POST["txt_nuevoDestino"]=="COLADO")
			guardarDetalleColado();
		}
		else
			unset($_SESSION["produccion"]);
	}
	
	 //Funcion para almacenar la información de la Bitácora en la BD
	function guardarRegistroBitacora(){
	//Incluimos los archivos de conexión
		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");

		//Recuperar la informacion del post
		$fecha = modFecha($_POST['txt_fecha'],3);
		$volProducido = str_replace(",","",$_POST['txt_volProducido']);
		
		//Verificamos si viene el combo Activo de Destino o viene de la caja de texto para proceder a almacenarlo
		if (isset($_POST["cmb_destino"]))
			$destino=$_POST["cmb_destino"];
		else{
			$destino=strtoupper($_POST["txt_nuevoDestino"]);
			$idDestino=generarIdDestino();
			//Mandar llamar la funcion que guarda el nuevo destino
			guardarDestino($idDestino,$destino);
			$destino=$idDestino;
		}
		
		$id_concepto=generarIdConcepto($fecha);
		$observaciones=strtoupper($_POST['txa_observaciones']);
		
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "INSERT INTO datos_bitacora(catalogo_destino_id_destino,bitacora_produccion_fecha, vol_producido, observaciones, no_concepto)
		VALUES ('$destino','$fecha','$volProducido','$observaciones', '$id_concepto')";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			$stm_sql2="INSERT INTO bitacora_produccion(fecha) VALUES ('$fecha')";
			//Ejecutar la Sentencia
			$rs2=mysql_query($stm_sql2);
			//Guardar la operacion realizada
			registrarOperacion("bd_produccion",$fecha,"RegistrarProduccion",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		//mysql_close($conn); 
	 }// Fin function guardarRegistroBitacora()	
	
	//Funcion quen nos permite guardar el Detalle del colado en la ventana emergente
	function guardarDetalleColado(){
	//Incluimos los archivos de conexión
		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
		//Conectamos con la BD
		$conn = conecta("bd_produccion");
		//Declaramos la variable bandera para control de la consulta
		$band=0;
		$fecha=modFecha($_POST["txt_fecha"],3);
		//Verificamos si viene el combo Activo de Destino o viene de la caja de texto para proceder a almacenarlo
		if (isset($_POST["cmb_destino"]))
			$destino=$_POST["cmb_destino"];
		else{
			$destino=strtoupper($_POST["txt_nuevoDestino"]);
			$idDestino=generarIdDestino();
			//Mandar llamar la funcion que guarda el nuevo destino
			guardarDestino($idDestino,$destino);
			$destino=$idDestino;
		}
		
		$id_concepto=generarIdConcepto($fecha);
		//Recorremos el arreglo produccion para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['produccion'] as $ind => $produccion){
			$volumen = str_replace(",","",$produccion['volumen']);
			//Creamos la sentencia SQL para insertar los datos en la tabla detalle colados
			$stm_sql="INSERT INTO detalle_colados (catalogo_destino_id_destino,bitacora_produccion_fecha, cliente, volumen, colado, observaciones, factura, tipo_colado, no_concepto, no_remision, pagado, costo)
			VALUES('$destino','$fecha','$produccion[cliente]', '$volumen', '$produccion[colado]', 
			'$produccion[observaciones]', '$produccion[factura]', '$produccion[tipo]','$id_concepto', '$produccion[remision]', '$produccion[pagado]', '$produccion[costo]')";
					
			//Ejecutar la sentencia previamente creadas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		
		if ($band==1){
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		}
		else{
			registrarOperacion("bd_produccion",$fecha,"RegistroDetalleColado",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");								
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);		
	}
	
	//Desplegar los registros de la produccion
	function mostrarProduccion($produccion){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Datos del Colado</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>CLIENTE</td>
				<td class='nombres_columnas' align='center'>M&sup3;</td>
				<td class='nombres_columnas' align='center'>COLADO</td>
				<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
				<td class='nombres_columnas' align='center'>FACTURA</td>
				<td class='nombres_columnas' align='center'>TIPO</td>
				<td class='nombres_columnas' align='center'>NO. REMISI&Oacute;N</td>
				<td class='nombres_columnas' align='center'>PAGADO</td>
				<td class='nombres_columnas' align='center'>COSTO</td>
        	</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($produccion as $ind => $registro) {
			echo "<tr>";
			foreach ($registro as $key => $value) {
				switch($key){
					case "cliente":
						echo "<td align='center'  class='nombres_filas'>$value</td>";
					break;
					case "volumen":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "colado":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "observaciones":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "factura":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "tipo":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "remision":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "pagado":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "costo":
						echo "<td align='center' class='$nom_clase'>$$value</td>";
					break;
				}				
			}
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
	
	//Funcion que nos permite calcular los datos de la sesion 
	function calcularVolumen(){
		//Igualamos la variable volumen 0 para no encontrar la variable como indefinida
		$totalVolumen=0;
		//Verificamos que exista la sesion
		if(isset($_SESSION['produccion'])){
			//Recorremos el arreglo en busqueda del dato
			foreach ($_SESSION['produccion'] as $key => $value) {
				foreach ($_SESSION['produccion'][$key] as $ind => $valor) {
					switch($ind){
						case "volumen":
							$totalVolumen+=str_replace(",","",$valor);
						break;
					}
				}
			}
		}
		return $totalVolumen;
	}
	
	//Funcion que permite obtener el id del Destino
	function generarIdConcepto($fecha){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_produccion");
		$id="";
		//Crear la sentencia para obtener el maximo id registrado en el catalogo
		$stm_sql = "SELECT COUNT(no_concepto) AS num, MAX(no_concepto)+1 AS cant FROM datos_bitacora WHERE bitacora_produccion_fecha='$fecha'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Si el resultado es menor que cero concatenamos la cantidad
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			//De lo contrario concatenamos uno
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}
	
	/**********************************************************************REGISTRO DE EQUIPOS*******************************************************************/
	
	function mostrarEncabezado(){
		echo "<caption><strong>Ingresar los Datos del Equipo</strong></caption>";
			echo "<table  width='100%'>      			
			<tr>
				<td width='10%' class='nombres_columnas'>SELECCIONAR</td>
				<td width='20%' class='nombres_columnas'>EQUIPO</td>
				<td width='10%' class='nombres_columnas'>M&sup3;</td>
				<td width='60%' class='nombres_columnas'>OBSERVACIONES</td>
			</tr>
		</table>";
	}
	
	//Funcion que permite consultar los equipos
	function mostrarEquipos(){
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
						
		//Crear sentencia SQL
		$sql_stm = "SELECT id_equipo,nom_equipo FROM equipos WHERE familia='MIXER' OR familia='TROMPOS' OR familia='ALPHA' ORDER BY id_equipo";
	
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_alerta = "	<label class='msje_correcto' align='center'>Ingresar los Datos del Equipo</em>
		</label>";										

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			echo "<table  width='100%'>";
			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{	
			echo "     			
				<tr>
					<td width='10%' class='$nom_clase'>"; ?>
						<input type="checkbox" name="ckb_equipo<?php echo $cont;?>" id="ckb_equipo<?php echo $cont;?>" 
						value="<?php echo $datos["id_equipo"]?>" onClick="activarCampos(this, <?php echo $cont; ?>)" 
						onkeypress="return permite(event,'num', 2);"/><?php
					echo "</td>	
					<td width='20%' class='$nom_clase'>$datos[id_equipo]</td>
					<td width='10%' class='$nom_clase'>"; ?>
						<input type="hidden" name="hdn_nombre<?php echo $cont; ?>"  value="<?php echo $datos["id_equipo"];?>" id="hdn_nombre<?php echo $cont; ?>"/>
						<input type="text" name="txt_metros<?php echo $cont;?>" id="txt_metros<?php echo $cont;?>" size="10" readonly="readonly"
						onkeypress="return permite(event,'num', 2);" class="caja_de_texto"
						onchange="formatCurrency(value,'txt_metros<?php echo $cont;?>');" /><?php 
						 
					echo "</td>
					<td width='60%' class='$nom_clase'>"; ?>
						<input type="text" name="txt_observaciones<?php echo $cont;?>" class="caja_de_texto" maxlength="120" 
						id="txt_observaciones<?php echo $cont;?>" size="80" 
						readonly="readonly" onkeypress="return permite(event,'num_car', 0);"/><?php 
					echo "</td>		
				</tr>";
					//Gurdar los datos en arreglo 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "	</table>";
			
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>No Existen Equipos Registrados</label>";// fin  if($datos=mysql_fetch_array($rs))
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//fin function 

	//Verificamos que exista en el post el boton y si es asi guardar los registros
	if(isset($_POST["sbt_guardarEquipo"]))
		guardarEquipo();

	//Funcion que guarda los cambios en los registros seleccionados
	function guardarEquipo(){
		//Se incluye el archivo de conexión para manejo de bd
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");
		include_once ("../../includes/func_fechas.php");
		//Conectamos con la BD
		$conn = conecta("bd_produccion");
		//Creamos la variable cantidad de la function mostrar Equipos para saber el numero de registros
		$cantidad=$_POST["hdn_cant"]-1;

		//Iniciamos la variable de control interna
		$ctrl=0;

		//Variable bandera para la insercion de datos
		$flag=0;

		//Variable para almacenar el error en caso de generarse
		$error="";
		do{
			//Incrementamos el contador interno; para saber el numero de registro
			$ctrl++;
			//Verificamos que este definido el Odometro inicial y agregamos variable ctrl para saber que registro sera insertado en la BD
			if(isset($_POST["txt_metros$ctrl"])&&$_POST["txt_metros$ctrl"]!=""){
				//creamos la variable para almacenar la fecha
				$fecha = modFecha ($_POST["txt_fecha"],3);
				$ckb_equipo = $_POST["ckb_equipo$ctrl"];
				$txt_metros = str_replace(",","",$_POST["txt_metros$ctrl"]);
				$txt_observaciones = strtoupper($_POST["txt_observaciones$ctrl"]);
				//Creamos la sentencia SQL
				$stm_sql="INSERT INTO equipos (bitacora_produccion_fecha,nom_equipo, vol_producido,observaciones)
				VALUES('$fecha','$ckb_equipo','$txt_metros','$txt_observaciones')";
		
				//Ejecutar la sentencia previamente creada
				$rs=mysql_query($stm_sql);
				//Guardar el registro de movimientos
				registrarOperacion("bd_produccion",$ckb_equipo,"RegistroProdEquipo",$_SESSION['usr_reg']);
				//Conectamos con la BD
				$conn = conecta("bd_produccion");
				//verificamos que la sentencia sea ejecutada con exito
				if (!$rs){
					$flag=1;
					//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
					$error="**** Error : ".mysql_error();
					break;
				}
			}
		//Mientras que control sea menor a la cantidad se registraran los datos en la BD	
		}while($ctrl<=$cantidad);
			
		//verificamos que la sentencia sea ejecutada con exito
		if ($flag==0){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
	
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);	
	
	}// Fin de la funcion 

	/**********************************************************************REGISTRO DE SEGURIDAD*******************************************************************/
	
	//Desplegar los registros de seguridad
	function mostrarResultados($seguridad){
		
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><strong><label align='center'>INCIDENTES/ACCIDENTES DE SEGURIDAD</label></strong></caption>";
		echo "      			
			<tr>
				<td width='10%' class='nombres_columnas' align='center'>N&Uacute;MERO</td>
				<td width='20%' class='nombres_columnas' align='center'>TIPO</td>
				<td width='70%' class='nombres_columnas' align='center'>OBSERVACIONES</td>
        	</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($seguridad as $ind => $registro) {
			echo "<tr>";
			foreach ($registro as $key => $value) {
				switch($key){
					case "partida":
						echo "<td align='center'  class='nombres_filas'>$value</td>";
					break;
					case "tipo":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "observaciones":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarSeguridad($seguridad)	

//Si se encuentra definindo el boton sbt_finalizarRegistro procedemos a guardar el registro
if(isset($_POST["sbt_finalizarRegistro"]))
	guardarSeguridad();

	//Funcion que permite guardar los registros de seguridad
	function guardarSeguridad(){
	//Incluimos los archivos de conexión
		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
		//Conectamos con la BD
		$conn = conecta("bd_produccion");
		//Declaramos la variable bandera para control de la consulta
		$band=0;
		//Guardamos la fecha en el formato necesario
		$fecha=modFecha($_POST["txt_fecha"],3);
		
		//Recorremos el arreglo mecanicos para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['seguridad'] as $ind => $seguridad){
			//Creamos la sentencia SQL para insertar los datos en la tabla Mecanico
			$stm_sql="INSERT INTO seguridad (bitacora_produccion_fecha,num, tipo,  observaciones)
			VALUES('$fecha','$seguridad[partida]','$seguridad[tipo]', '$seguridad[observaciones]')";
					
			//Ejecutar la sentencia previamente creadas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		
		if ($band==1){
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		}
		else{
			registrarOperacion("bd_produccion",$fecha,"RegistroSeguridad",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");								
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);		
	}
	
	//Funcion que permite obtener el id del Destino
	function generarIdSeguridad($fecha){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_produccion");
		$id="";
		$fecha=modFecha($fecha,3);
		//Crear la sentencia para obtener el maximo id registrado en el catalogo
		$stm_sql = "SELECT COUNT(num) AS num, MAX(num)+1 AS cant FROM seguridad WHERE bitacora_produccion_fecha='$fecha'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Si el resultado es menor que cero concatenamos la cantidad
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			//De lo contrario concatenamos uno
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}
?>