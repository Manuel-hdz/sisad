<?php
	/**
	  * Nombre del M�dulo: Almac�n                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 30/Septiembre/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de entradaMaterial en la BD
	  **/
	 
	//Esta funci�n se encarga de generar el Id de la Entrada de acurdo a los registros existentes en la BD
	function obtenerIdEntrada(){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");		
		//Definir las dos letras en la Id de la Entrada
		$id_cadena = "EM";	
		
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el a�o actual para ser agregados en la consulta y asi obtener las entradas del mes y a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de entradas registradas en la BD
		$stm_sql = "SELECT COUNT(id_entrada) AS cant FROM entradas WHERE id_entrada LIKE 'EM$mes$anio%'";
		
		$stm_sql = "SELECT MAX( CAST( SUBSTR( id_entrada, 7) AS UNSIGNED ) ) AS cant
					FROM entradas
					WHERE id_entrada LIKE  'EM$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>99 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>=1000)
				$id_cadena .= $cant;
		}
			
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdEntrada()
	
	
	/*Esta funci�n obtiene el o los pedidos asociados a la requisicion en la tabla de PEDIDO de la Base de Datos de COMPRAS*/
	function obtenerNoPedidos($idRequisicion){
		//Conectarse a la BD de Compras
		$conn = conecta("bd_compras");
		$noPedidos = "";
		
		$rs = mysql_query("SELECT id_pedido FROM pedido WHERE requisiciones_id_requisicion LIKE '$idRequisicion%'");
		
		//Recorrer el Result Set y concatenar los numeros de pedido en la variable $noPedidos
		while($datos=mysql_fetch_array($rs)){
			$noPedidos .= $datos['id_pedido']." - ";
		}
		
		//Retirar el ultimo caracter de la cadena que contiene los numeros de los pedidos
		$noPedidos = substr($noPedidos,0,(strlen($noPedidos)-3));
		
		return $noPedidos;
		//El cierre de la Conexion se hace cuando se termina de ejecutar el Script
	}//Cierre de la funcion obtenerNoPedidos($idRequisicion)
	
	
	function obtenerCantidadPedido($idreq,$descmat){
		$conn = conecta("bd_compras");
		$sql = "SELECT T1.cantidad
				FROM detalles_pedido AS T1
				JOIN pedido AS T2 ON T2.id_pedido = T1.pedido_id_pedido
				WHERE T2.requisiciones_id_requisicion =  '$idreq'
				AND T1.descripcion =  '$descmat'";
		$rs = mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			return $datos[0];
		}
	}
	
	/*Esta funcion recibe como parametro el Nombre del Departamento y determina cual es el nombre de la BD correspondiente*/
	function obtenerNomBD($departamento){
		$base = "";
		switch ($departamento){
			case "almacen":				
				$base="bd_almacen";
			break;
			case "gerenciatecnica":
				$base="bd_gerencia";
			break;
			case "recursoshumanos":
				$base="bd_recursos";
			break;
			case "produccion":
				$base="bd_produccion";
			break;
			case "aseguramientodecalidad":
				$base="bd_aseguramiento";
			break;
			case "desarrollo":
				$base="bd_desarrollo";
			break;
			case "mantenimiento":
				$base="bd_mantenimiento";
			break;
			case "topografia":
				$base="bd_topografia";
			break;
			case "laboratorio":
				$base="bd_laboratorio";
			break;
			case "seguridadindustrial":
				$base="bd_seguridad";
			break;
			case "paileria":
				$base="bd_paileria";
			break;
			case "mttoE":
				$base="bd_mantenimientoE";
			break;
			case "clinica":
				$base="bd_clinica";
			break;
		}
		//Retornar el Nombre de la Base de Datos
		return $base;
	}
	  
	function obtenerBaseDatosReq($id_req){
		$bd = "sin base";
		switch(substr($id_req,0,3)){
			case "ALM":
				$bd="bd_almacen";
			break;
			case "GER":
				$bd="bd_gerencia";
			break;
			case "REC":
				$bd="bd_recursos";
			break;
			case "PRO":
				$bd="bd_produccion";
			break;
			case "ASE":
				$bd="bd_aseguramiento";
			break;
			case "DES":
				$bd="bd_desarrollo";
			break;
			case "MAN":
				$bd="bd_mantenimiento";
			break;
			case "MAC":
				$bd="bd_mantenimiento";
			break;
			case "MAM":
				$bd="bd_mantenimiento";
			break;
			case "MAE":
				$bd="bd_mantenimientoe";
			break;
			case "TOP":
				$bd="bd_topografia";
			break;
			case "LAB":
				$bd="bd_laboratorio";
			break;
			case "SEG":
				$bd="bd_seguridad";
			break;
			case "USO":
				$bd="bd_clinica";
			break;
			case "PAI":
				$bd="bd_paileria";
			break;
			case "MAI":
				$bd="bd_comaro";
			break;
		}
		return $bd; 
	}

	function enviarCorreoEntradaMat($correo, $body, $num_req){
		?>
		<script>
		<?php
		echo "
		function ejecutaAlerta() {   
			var w = window.open('','','top=250,left=500,width=600,height=200')
			w.document.write('<center><h1>SE ESTA ENVIANDO EL CORREO: ".$correo.", ESPERE UN MOMENTO POR FAVOR!</h1></center>')
			w.focus()
			setTimeout(function() {
				w.close();
			}, 15000)
		}";
		?>
		</script>

		<script> ejecutaAlerta(); </script>

		<?php
		/*
		error_reporting(E_STRICT);
		date_default_timezone_set('America/Mexico_City');
		require_once('../../includes/phpmailer/class.phpmailer.php');

		$correos = explode(";", $correo);

		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->Host       = "mail.concretolanzadodefresnillo.com";
		$mail->SMTPDebug  = 2;

		$mail->SMTPAuth   = true;
		$mail->Host       = "mail.concretolanzadodefresnillo.com";
		$mail->Port       = 465;
		$mail->Username   = "sisad@concretolanzadodefresnillo.com";
		$mail->Password   = "SistemasCLF.1214";

		$mail->SetFrom('sisad@concretolanzadodefresnillo.com', 'SISAD');
		$mail->Subject    = "MATERIAL ENTREGADO DE REQUISICION $num_req";

		$mail->Body       = $body;
		$mail->IsHTML(true);

		foreach ($correos as $email) {
			$email = trim($email);
			$mail->AddAddress($email, "USUARIO SISAD");
		}

		$mail->AddAddress($correo, "USUARIO SISAD");
		$mail->Send();

		*/
	}

	//Agregar el registro de la Entrada de Materiales a las tablas de detalle_entradas y entradas
	function guardarCambios($cmb_provedor,$txt_noFactura,$txt_fechaEntrada,$cmb_aceptado,$txa_comentarios){
		//Obtener el ID de la Entrada
		$idEntrada=obtenerIdEntrada();

		//Si la bandera se activa significa que hubo errores
		$band = 0;

		if (substr($_SESSION["no_origen"],0,3) == "PED") {
			$num_requi = obtenerDato("bd_compras","pedido","requisiciones_id_requisicion","id_pedido",$_SESSION['no_origen']);
		} else {
			$num_requi = $_SESSION["no_origen"];
		}
		$base_correo = obtenerBaseDatosReq($num_requi);
		if ($base_correo != "sin base") {
			$correo_requi = obtenerDato($base_correo,"requisiciones","correo","id_requisicion",$num_requi);
		} else {
			$correo_requi = "sin correo";
		}
		
		//Variable para establecer el contenido que se mandara via email
		$contenido_mensaje = "
			<body style='margin: 10px;'>
				<div style='width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 16px;'>
					<br>
					Material recibido por almacen de la requisicion $num_requi:<br>";

		//Registrar todos los materiales dados de alta en el arreglo $datosEntrada
		foreach ($_SESSION['datosEntrada'] as $ind => $material){
			
			//Registrar la entrada en la tabla de Detalle Requisicion y Detalle Orden de Compra para indicar que el material solicitado ya recibio entrada
			if(isset($_SESSION["bd"])){//Solo actualizar el estado de las REQUISICIONES registradas en el SISTEMA
				//La variable se inicializa por default en bd_almacen para Ordenes de Compra
				$base="bd_almacen";
				switch(substr($_SESSION["no_origen"],0,3)){
					case "ALM":
						$base="bd_almacen";
					break;
					case "GER":
						$base="bd_gerencia";
					break;
					case "REC":
						$base="bd_recursos";
					break;
					case "PRO":
						$base="bd_produccion";
					break;
					case "ASE":
						$base="bd_aseguramiento";
					break;
					case "DES":
						$base="bd_desarrollo";
					break;
					case "MAN":
						$base="bd_mantenimiento";
					break;
					case "MAC":
						$base="bd_mantenimiento";
					break;
					case "MAM":
						$base="bd_mantenimiento";
					break;
					case "TOP":
						$base="bd_topografia";
					break;
					case "LAB":
						$base="bd_laboratorio";
					break;
					case "SEG":
						$base="bd_seguridad";
					break;
					case "PAI":
						$base="bd_paileria";
					break;
					case "PED":
						//En caso de tener un Pedido como No de Origen, determinar mediante la Req asociada, la BD
						$reqPedido=obtenerDato("bd_compras","pedido","requisiciones_id_requisicion","id_pedido",$_SESSION['no_origen']);
						switch(substr($reqPedido,0,3)){
							case "ALM":
								$base="bd_almacen";
							break;
							case "GER":
								$base="bd_gerencia";
							break;
							case "REC":
								$base="bd_recursos";
							break;
							case "PRO":
								$base="bd_produccion";
							break;
							case "ASE":
								$base="bd_aseguramiento";
							break;
							case "DES":
								$base="bd_desarrollo";
							break;
							case "MAN":
								$base="bd_mantenimiento";
							break;
							case "MAC":
								$base="bd_mantenimiento";
							break;
							case "MAM":
								$base="bd_mantenimiento";
							break;
							case "MAE":
								$base="bd_mantenimientoe";
							break;
							case "TOP":
								$base="bd_topografia";
							break;
							case "LAB":
								$base="bd_laboratorio";
							break;
							case "SEG":
								$base="bd_seguridad";
							break;
							case "USO":
								$base="bd_clinica";
							break;
							case "PAI":
								$base="bd_paileria";
							break;
							default:
								$base="N/A";
							break;
						}//Cierre del switch para requisiciones de Pedidos
				}//Cierre del switch para requisiciones como No de Origen
				//Verificar si la variable base tomo valor diferente de N/A, para actualizar o no, los materiales relacionados
				if($base!="N/A")
					actualizarDetalleReqOC($material['clave'],$material['nombre'],$base,$material['cantEntrada'],$material['cant_req']);	
			}
			//Obtener la linea del material a guardar
			$linea=obtenerDato("bd_almacen","materiales","linea_articulo","id_material",$material["clave"]);
			//Realizar la conexion a la BD de Almacen
			$conn = conecta("bd_almacen");
			
			$cond = false;			
			//Registrar la entrada a la existencia del Material mediante el Id del mismo y Actualizar el costo de material que esta entrando
			$cond = ( actualizarCantMaterial($material['clave'],$material['cantEntrada']) && actualizarCostoMaterial($material['clave'],$material['costoUnidad']) && actualizarMonedaMaterial($material['clave'],$material['tipoMoneda']) ); 
			
			//Registrar la entrada en la tabla de alertas para indicar que el material requisitado ha recibido entrada y cambiar el estado de 2 a 3
			actualizarEstadoAlerta($material['clave']);
			
			if($cond){
				$contenido_mensaje .= "<br>$material[cantEntrada]&nbsp;$material[unidad]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$material[nombre]";
				if( is_numeric($material['partidaPed']) ){
					$partida_ped = $material['partidaPed'];
				} else {
					$partida_ped = 0;
				}
				//Crear la sentencia para realizar el registro de los datos del detalle de la Entrada de Material
				$stm_sql = "INSERT INTO detalle_entradas (entradas_id_entrada,materiales_id_material,nom_material,unidad_material,linea_material,cant_entrada,costo_unidad,costo_total,tipo_moneda,cant_restante,partida_pedido)
				VALUES('$idEntrada','$material[clave]','$material[nombre]','$material[unidad]','$linea','$material[cantEntrada]','$material[costoUnidad]','$material[costoTotal]','$material[tipoMoneda]','$material[cantEntrada]','$partida_ped')";
				
				//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_entradas
				$rs = mysql_query($stm_sql);
				if(!$rs)
					$band = 1;
			}
			else{
				$band = 1;
			}
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;
		}//Cierre foreach ($_SESSION['datosEntrada'] as $ind => $material)

		$contenido_mensaje .= "</div></body>";

		//Cuando los materiales registrados en la Entrada provengan de un PEDIDO, mandar llamar la funci�n que actualiza el 
		//estado en la tabla de detalles_pedido de la BD de COMPRAS
		if(isset($_SESSION["bd"]) && $_SESSION["bd"]=="bd_compras"){
			actualizarDetallePedido();
		}
	
		//Pasar a Mayusculas los datos de la Entrada
		$txt_noFactura = strtoupper($txt_noFactura); $cmb_provedor = strtoupper($cmb_provedor); $txa_comentarios = strtoupper($txa_comentarios);						
		if($band==0){
			//Obtener la Suma de los registros de la Entrada de Material
			$costoTotalEntrada = obtenerSumaRegistrosES($_SESSION['datosEntrada'],"costoTotal");																							
			//Obtener la hora actual
			$hora = date("H:i:s");
			
			//Crear la sentencia para almacenar los datos de la entrada en la BD
			$stm_sql = "INSERT INTO entradas(id_entrada,requisiciones_id_requisicion,orden_compra_id_orden_compra,comp_directa,proveedor,no_factura,
						costo_total,fecha_entrada,hora_entrada,aceptado,comentarios)
						VALUES('$idEntrada'";
			
			switch($_SESSION['origen']){
				case "Requisicion":					
					$stm_sql .= ",'$_SESSION[no_origen]','','',";
				break;
				case "Orden de Compra":
					$stm_sql .= ",'','$_SESSION[no_origen]','',";
				break;
				case "Compra Directa":
					$com_dir = strtoupper($_POST['txt_noOrigen']);
					$stm_sql .= ",'','','$com_dir',";
				break;
				case "Pedido":
					//Decomentar esta linea en caso de querer guardar la requisicion asociada al Pedido en lugar del ID del Pedido
					//$reqPedido=obtenerDato("bd_compras","pedido","requisiciones_id_requisicion","id_pedido",$_SESSION['no_origen']);
					//Guardar el Pedido en el espacio de las Requisiciones
					$stm_sql .= ",'$_SESSION[no_origen]','','',";
				break;
			}
			
			$stm_sql .= "'$cmb_provedor','$txt_noFactura',$costoTotalEntrada,'".modFecha($txt_fechaEntrada,3)."','$hora','$cmb_aceptado','$txa_comentarios')";
			//Reconectar a la BD de Almacen
			$conn = conecta("bd_almacen");
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);						
			//Confirmar que la insercion de datos fue realizada con exito.
			if($rs){
				if ($correo_requi != "sin correo") {
					enviarCorreoEntradaMat($correo_requi, $contenido_mensaje, $num_requi);
				}
				//Registrar la Operacion en la Bit�cora de Movimientos
				registrarOperacion("bd_almacen",$idEntrada,"EntradaMaterial",$_SESSION['usr_reg']);				
				//Vaciar la informaci�n almacenada en la SESSION						
				unset($_SESSION['datosEntrada']);
				unset($_SESSION['origen']);
				unset($_SESSION['no_origen']);
				unset($_SESSION['cmb_prm2']);
				//Vaciar la Informacion almacenada en la SESSION cuando el proceso de registro de nuevos materiales fue terminado con exito
				unset($_SESSION['procesoRegistroMat']);
				unset($_SESSION['clavesRegistradasMat']);
				//Verificar si esta declarado el arreglo de Sesion con las Claves Modificadas
				if(isset($_SESSION["clavesModificadasExistencia"]))
					unset($_SESSION["clavesModificadasExistencia"]);
				//Verificar si esta declarado el arreglo de Sesion con las Existencias Modificadas
				if(isset($_SESSION["clavesModificadasExistenciaCantidad"]))
					unset($_SESSION["clavesModificadasExistenciaCantidad"]);
				//Vaciar de la SESSION los nombre de los materiales obtenidos de un pedido
				unset($_SESSION['nomMaterialesPedido']);
				unset($_SESSION['bd']);
				
				//Redireccionar a la Pantalla de Exito																								
				echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial4A.php?clave_entrada=$idEntrada'>";
			}
			else{
				//Redireccionar a una pagina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			//Redireccionar a una pagina de error
			$error = "No se pudieron almacenar todos los registros del Detalle de Entradas";			
			$error = mysql_error();		
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		//Cerrar la conexion con la BD		
		//La conexion a la BD se cierra en la funcion registrarOperacion("bd_almacen",$_SESSION['id_entrada'],"entrada",$_SESSION['usr_reg']);
	}//Fin de la funcion guardarCambios($txt_proveedor,$txt_noRequisicion,$txt_noFactura,$txt_costo,$txt_fecha,$cmb_aceptado,$txa_comentarios)
	
	
	//Esta funci�n se encarga de agregar la cant. de entrada a la existencia de los materiales
	function actualizarCantMaterial($clave,$cantEntrada){
		//Variable para indicar si la cantidad de Material se actualizara
		$actualizar="si";
		//Verificar si esta declarado el arreglo de Sesion con las Claves Modificadas y de Existencia
		if(isset($_SESSION["clavesModificadasExistencia"])){
			//Para esto, recorrer el arreglo de claves con modificaciones de existencia
			foreach($_SESSION["clavesModificadasExistencia"] as $ind=>$valor){
				//Si la clave aparece es igual a la del valor del arreglo, NO actualizar ya que esto se realizo desde
				//el op_agregarMaterial.php
				if($valor==$clave){
					$actualizar="no";
					break;
				}
			}
		}
		if($actualizar=="si"){
			//Crear la sentencia para actualizar la existencia del material con la entrada
			$stm_sql = "UPDATE materiales SET existencia=existencia+$cantEntrada WHERE id_material='$clave'";
			//Ejecutar la sentencia
			$rs = mysql_query($stm_sql);
			//Comprobar el resultado de la actualizacion
			if($rs)
				return true;
			else
				return false;				
		}
		else
			return true;
	}//Cierre de la funcion registrarEntrada($clave,$cantEntrada)
	
	//Esta funcion se encarga de actualizar el estado de la alerta, en el caso de que el material al que se le esta dando entrada haya generado 1
	function actualizarEstadoAlerta($clave){
		//Crear la sentencia para actualizar el estado de la Alerta, en el caso de que se haya generado
		$stm_sql = "UPDATE alertas SET estado=3 WHERE materiales_id_material='$clave' AND (estado=2 OR estado=1)";
		//Ejecutar la sentencia
		$rs = mysql_query($stm_sql);		
	}
	
	//Esta funcion se encarga de actualizar el estado del material en el Detalle de la Requisicion para idicar que el material ha recibido entrada
	function actualizarDetalleReqOC($clave,$nom_material,$base,$cantEnt,$cant_req){		
		//Actualizar el estado de los materiales en el detalle de la Requisici�n
		if($_SESSION['origen']=="Requisicion"){						
			//Conectar a la BD que corresponde segun la Requisicion
			$conn=conecta($base);
			if($cant_req == $cantEnt){
			//Crear la sentencia para actualizar el estado de la Alerta, en el caso de que se haya generado
				$stm_sql = "UPDATE detalle_requisicion SET estado = 2 WHERE (materiales_id_material = '$clave' OR descripcion = '$nom_material') AND estado = 1 AND requisiciones_id_requisicion='$_SESSION[no_origen]'";
			} 
			//else {
				//$cant_actual = $cant_req - $cantEnt;
				//$stm_sql = "UPDATE detalle_requisicion SET cant_req = $cant_actual WHERE (materiales_id_material = '$clave' OR descripcion = '$nom_material') AND estado = 1 AND requisiciones_id_requisicion='$_SESSION[no_origen]'";
			//}
			//Ejecutar la sentencia
			$rs = mysql_query($stm_sql);
			//Cerrar la BD del departamento asociado
			mysql_close($conn);
			//Verificar y actualizar el posible Pedido asociado a la Requisicion
			verificarPedido($_SESSION['no_origen'],$nom_material,$cantEnt,$cant_req);
		}
		//Actualizar el estado de los materiales en el detalle de la Orden de Compra
		if($_SESSION['origen']=="Orden de Compra"){
			$conn=conecta("bd_almacen");
			//Crear la sentencia para actualizar el estado de la Alerta, en el caso de que se haya generado
			$stm_sql = "UPDATE detalle_oc SET estado=2 WHERE descripcion='$nom_material' AND estado=1 AND orden_compra_id_orden_compra='$_SESSION[no_origen]'";
			//Ejecutar la sentencia
			$rs = mysql_query($stm_sql);
			mysql_close($conn);
		}
		//Actualizar el estado de la Requisicion asociada al Pedido,cabe destacar que por ahora no se pueden actualizar las requisiciones hechas a multiples Pedidos
		if($_SESSION['origen']=="Pedido"){
			//Obtener la requisicion asociada al Pedido
			$reqPedido=obtenerDato("bd_compras","pedido","requisiciones_id_requisicion","id_pedido",$_SESSION['no_origen']);
			//Conectar a la BD de donde proviene la Requisicion
			$conn=conecta($base);
			//Crear la sentencia para actualizar el estado de la Alerta, en el caso de que se haya generado
			$stm_sql = "UPDATE detalle_requisicion SET estado = 2 WHERE (materiales_id_material = '$clave' OR descripcion = '$nom_material') AND estado = 1 AND requisiciones_id_requisicion='$reqPedido'";
			//Ejecutar la sentencia
			$rs = mysql_query($stm_sql);
			//Cerrar la BD que se haya abierto mediante el prefijo de la Requisicion
			mysql_close($conn);
			//Conectar a la BD de Compras para complementar la Fecha y la Hora de Entrada
			$conn=conecta("bd_compras");
			//Crear la sentencia que indica la Fecha y Hora de Llegada de Llegada del Material, para agregarlo a la BD de Compras
			$stm_sql = "UPDATE pedido SET hora_entrega=CURRENT_TIME(),fecha_entrega=CURRENT_DATE() WHERE id_pedido='$_SESSION[no_origen]'";
			//Ejecutar la sentencia
			$rs = mysql_query($stm_sql);
			//Cerrar la BD de compras
			mysql_close($conn);
		}
	}
	
	/*Esta funcion se encarga de verificar los Pedidos asociados a la Requisicion en cuestion*/
	function verificarPedido($idReq,$nom_material,$cantEnt,$cant_req){
		//Conectar a la BD
		$conn=conecta("bd_compras");
		//Sentencia SQL para extraer los Pedidos asociados a la Requisicion
		$sql_stm="SELECT id_pedido FROM pedido WHERE requisiciones_id_requisicion='$idReq'";
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		//Verificar si hay resultados
		if($datos=mysql_fetch_array($rs)){
			//Ciclo para obtener los pedidos asociados a la requisicion
			do{
				//Obtener el ID del Pedido
				$idPedido=$datos["id_pedido"];
				if($cant_req == $cantEnt){
				//Crear la sentencia para actualizar el estado del Detalle del Pedido
					$stm_sql2 = "UPDATE detalles_pedido SET estado = 2, cantidad = 0 WHERE descripcion = '$nom_material' AND estado = 1 AND pedido_id_pedido='$idPedido'";
				} else {
					$cant_actual = $cant_req - $cantEnt;
					$stm_sql2 = "UPDATE detalles_pedido SET cantidad = $cant_actual WHERE descripcion = '$nom_material' AND estado = 1 AND pedido_id_pedido='$idPedido'";
				}//Ejecutar la sentencia
				$rs2 = mysql_query($stm_sql2);
				//Crear la sentencia que indica la Fecha y Hora de Llegada de Llegada del Material, para agregarlo a la BD de Compras
				$stm_sql3 = "UPDATE pedido SET hora_entrega=CURRENT_TIME(),fecha_entrega=CURRENT_DATE() WHERE id_pedido='$idPedido'";
				//Ejecutar la sentencia
				$rs3 = mysql_query($stm_sql3);
			}while($datos=mysql_fetch_array($rs));
		}//Fin de if($datos=mysql_fetch_array($rs))
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Fin de function verificarPedido()
		
	//Esta funcion actualiza el estado de los materiales en la tabla de detalles_pedido en la BD de COMPRAS
	function actualizarDetallePedido(){
		foreach($_SESSION['nomMaterialesPedido'] as $ind => $nomMaterial){
			foreach ($_SESSION['datosEntrada'] as $ind => $material){
				$conn = conecta("bd_compras");
				
				if($material["cant_req"] == $material["cantEntrada"]){
					$stm_sql = "UPDATE detalles_pedido SET estado = 2, cantidad = 0 WHERE partida = '$material[partidaPed]' AND estado = 1 AND pedido_id_pedido='$_SESSION[no_origen]'";
				} else {
					$cant_actual = $material["cant_req"] - $material["cantEntrada"];
					$stm_sql = "UPDATE detalles_pedido SET cantidad = $cant_actual WHERE partida = '$material[partidaPed]' AND estado = 1 AND pedido_id_pedido='$_SESSION[no_origen]'";
				}
				
				$rs = mysql_query($stm_sql);
				mysql_close($conn);
				
				actualizarPartidaRequi($material["partidaReq"]);
			}
		}
	}
	
	//Esta funcion actualiza el costo del material en la tabla materiales sin previo aviso
	function actualizarCostoMaterial($clave,$costoUnitario){
		//Crear la sentencia para actualizar la existencia del material con la entrada
		$stm_sql = "UPDATE materiales SET costo_unidad=$costoUnitario WHERE id_material='$clave'";
		//Ejecutar la sentencia
		$rs = mysql_query($stm_sql);
		
		//Comprobar el resultado de la actualizacion
		if($rs)
			return true;
		else
			return false;
	}
	
	//Esta funcion actualiza el costo del material en la tabla materiales sin previo aviso
	function actualizarMonedaMaterial($clave,$tipoMoneda){
		//Crear la sentencia para actualizar la existencia del material con la entrada
		$stm_sql = "UPDATE materiales SET moneda='$tipoMoneda' WHERE id_material='$clave'";
		//Ejecutar la sentencia
		$rs = mysql_query($stm_sql);
		
		//Comprobar el resultado de la actualizacion
		if($rs)
			return true;
		else
			return false;
	}
	
	//Desplegar los materiales agregados a la Entrada de Materiales
	function mostrarRegistros($datosEntrada,$opc){
		echo "				
		<table width='100%' cellpadding='5'>      			
			<tr>
				<td class='nombres_columnas' align='center'>CLAVE</td>
        		<td class='nombres_columnas' align='center'>NOMBRE (DESCRIPCION)</td>
				<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
			    <td class='nombres_columnas' align='center'>EXISTENCIA</td>
				<td class='nombres_columnas' align='center'>CANT. ENTRADA</td>
				<td class='nombres_columnas' align='center'>COSTO UNIDAD</td>
				<td class='nombres_columnas' align='center'>SUBTOTAL</td>
				<td class='nombres_columnas' align='center'>MONEDA</td>";
			//Si la Solicitud viene de la primera pagina de Entrada de Material mostrar el icono que permite editar el Registro
			if($opc==1)
				echo "<td class='nombres_columnas'></td>";
      	echo "	</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($datosEntrada as $ind => $material) {
			echo "<tr>";
			foreach ($material as $key => $value) {
				switch($key){
					case "clave":
						if($value!="�NOVALE")
							echo "<td class='nombres_filas' align='center'>$value</td>";
						else
							echo "<td class='nombres_filas' align='center'>NO APLICA</td>";
					break;
					case "nombre":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "unidad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "existencia":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cantEntrada":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "costoUnidad":
						echo "<td class='$nom_clase' align='center'>$".number_format($value,2,".",",")."</td>";
					break;
					case "costoTotal":
						echo "<td class='$nom_clase' align='center'>$".number_format($value,2,".",",")."</td>";
					break;
					case "tipoMoneda":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
				}				
			}
			
			//Si la Solicitud viene de la primera pagina de Entrada de Material mostrar el icono que permite editar el Registro
			if($opc==1){
				//Colocar la Imagen para permitir la Edicion del registro seleccionado
				?><td class="<?php echo $nom_clase;?>">
					<input type="image" src="../../images/editar.png" width="30" height="25" border="0" title="Modificar Registro" 
					onclick="location.href='frm_editarRegistros.php?origen=entrada&pos=<?php echo $cont-1; ?>'" />
				</td><?php
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
	}//Fin de la funcion mostrarRegistros($datosEntrada)
	 
	 //Esta funci�n verifica que no se duplique un registro en el arreglo que guarda los datos del Detalle de la Entrada.	
	function verRegDuplicado($arr,$campo_clave,$campo_ref){
		$tam = count($arr);		
		$datos = $arr[$tam-1];
		//Regresa TRUE cuando el registro esta duplicado y FALSE cuando no lo esta
		if($datos[$campo_clave]==$campo_ref)
			return true;
		else 
			return false;		
	}
	
	
	/** Esta funcion Despliega los materiales incluidos en la requisicion indicada para darles entrada al Alamcen, asi como de cargar los datos al arreglo
	  * datosEntrada definido en la Session y redireccionar a la pagina frm_entradaMaterial2.php una vez realizada la operacion
	  */
	function mostrarMaterialesReq($id_requisicion,$base){					
		//Realizar la conexion a la BD que es enviada a traves del parametro
		$conn = conecta($base);

		//Verificar si ha sido definido algun CheckBox en el arreglo $_POST
		$control=false;
		foreach($_POST as $clave=>$valor){
			if (substr($clave,0,3)=='ckb')
				$control=true;
		}	
		
		//Si ningun CheckBox ha sido definido en el arreglo $_POST desplegar los materiales de la requisicion para darles Entrada
		if(!$control) {
			//DESPLEGAR SOLO LOS MATERIALES QUE ESTAN REGISTRADOS EN EL ALMACEN
			if ($base=="bd_almacen")
				//Generar la consulta para obtener los datos solicitados en la requsicion seleccionada que esten registrados en el Almacen
				$stm_sql = "SELECT materiales_id_material,cant_req,unidad_medida,descripcion,con_clave FROM detalle_requisicion 
							WHERE requisiciones_id_requisicion = '$id_requisicion' AND estado=1 ORDER BY con_clave DESC";
			else
				//Generar la consulta para obtener los datos solicitados en la requsicion seleccionada que esten registrados en los departamentos
				$stm_sql = "SELECT materiales_id_material,cant_req,unidad_medida,descripcion FROM detalle_requisicion 
							WHERE requisiciones_id_requisicion = '$id_requisicion' AND estado=1 ORDER BY materiales_id_material";
			$rs = mysql_query($stm_sql);
			echo "<div id='cargar-datos' align='center' class='borde_seccion2'>";			
			//Confirmar que la consulta de datos fue realizada con exito y desplegar los datos encontrados.
			if($datos=mysql_fetch_array($rs)){				
				//Mostrar los Materiales de la Requisicion que se encuentran registrados en el Almacen
				echo "
					<form onSubmit='return valFormSelecMateriales(this);' name='frm_selecMateriales' method='post' action='frm_entradaMaterial2A.php?in=req'>
					<table width='100%' cellpadding='5'>";
				if(isset($datos["con_clave"]) && $datos["con_clave"]==1)
					echo "<caption class='titulo_etiqueta'>Seleccionar Materiales de la Requisicion $id_requisicion para Registrarlo en la Entrada</caption>";
				else{
					if(isset($datos["materiales_id_material"])){
						if($datos["materiales_id_material"]!="N/A")
							echo "<caption class='titulo_etiqueta'>Seleccionar Materiales de la Requisicion $id_requisicion para Registrarlo en la Entrada</caption>";
						else
							echo "<caption class='titulo_etiqueta'>Los Siguientes Materiales de la Requisicion $id_requisicion <u>NO</u> se Encuentran Registrados en el Cat&aacute;logo de Almac&eacute;n</caption>";
					}
					else
						echo "<caption class='titulo_etiqueta'>Los Siguientes Materiales de la Requisicion $id_requisicion <u>NO</u> se Encuentran Registrados en el Cat&aacute;logo de Almac&eacute;n</caption>";
				}
				echo "
						<tr>
							<td class='nombres_columnas' align='center'>SELECCIONAR</td>
							<td class='nombres_columnas' align='center'>CLAVE</td>
    	    				<td class='nombres_columnas' align='left'>NOMBRE (DESCRIPCION)</td>
					        <td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
							<td class='nombres_columnas' align='center'>EXISTENCIA</td>
        					<td class='nombres_columnas' align='center'>CANT. REQUISITADA</td>
							<td class='nombres_columnas' align='center'>CANT. ENTRADA</td>        				
        					<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>																
	      				</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				$flag=0;
				do{	
					//Verificar si el material es NUEVO o ya es parte del Almacen
					if( (isset($datos["con_clave"]) && $datos["con_clave"]==1) || (isset($datos["materiales_id_material"]) && $datos["materiales_id_material"]!="N/A") ){
						$cantreq = obtenerCantidadPedido($id_requisicion,$datos["descripcion"]);
						//Obtener la existencia del material registrado en el almacen
						$existencia = obtenerDato("bd_almacen","materiales","existencia","id_material",$datos['materiales_id_material']);
						echo "	
							<tr>
								<td class='nombres_filas' align='center'>
									<input type='checkbox' name='ckb_mat$cont' id='ckb_mat$cont' value='$cont' />
									<input type='hidden' name='hdn_clave$cont' id='hdn_clave$cont' value='$datos[materiales_id_material]' />
									<input type='hidden' name='hdn_nombre$cont' id='hdn_nombre$cont' value='$datos[descripcion]' />
									<input type='hidden' name='hdn_unidad$cont' id='hdn_unidad$cont' value='$datos[unidad_medida]' />
									<input type='hidden' name='hdn_existencia$cont' id='hdn_existencia$cont' value='$existencia' />
									<input type='hidden' name='hdn_cant_req$cont' id='hdn_cant_req$cont' value='$cantreq' />
								</td>
								<td class='$nom_clase' align='center'>$datos[materiales_id_material]</td>
								<td class='$nom_clase' align='left'>$datos[descripcion]</td>
								<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
								<td class='$nom_clase' align='center'>$existencia</td>
								<td class='$nom_clase' align='center'>$cantreq</td>";?>
								<td class="<?php echo $nom_clase; ?>" align="center">
									<input name="<?php echo "txt_cant".$cont; ?>" id="<?php echo "txt_cant".$cont; ?>" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" 
									size="10" maxlength="15" />
								</td>
								<td class="<?php echo $nom_clase; ?>" align="center">
									$<input name="<?php echo "txt_cost".$cont; ?>" id="<?php echo "txt_cost".$cont; ?>" type="text" class="caja_de_num" 
									onkeypress="return permite(event,'num');" onchange="formatCurrency(value,'<?php echo "txt_cost".$cont; ?>');"  size="10" maxlength="15" />
								</td>
							</tr>															
							<?php													
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}
					else{
						//Variable bandera para dibujar el siguiente Renglon Solo una vez
						$flag++;
						if($flag==1 && $cont>1){
							echo"
								<tr>
									<td colspan='8'><p class='titulo_etiqueta'>Los Siguientes Materiales de la Requisicion $id_requisicion <u>NO</u> se Encuentran Registrados en el Cat&aacute;logo de Almac&eacute;n</p></td>
								</tr>
							";
						}
						//Obtener la existencia del material registrado en el almacen
						$existencia = "N/A";
						echo "	
							<tr>
								<td class='nombres_filas' align='center'>
									<input type='checkbox' name='ckb_matNR$flag' id='ckb_matNR$flag' value='$cont' />
									<input type='hidden' name='hdn_claveNR$flag' id='hdn_claveNR$flag' value='$datos[materiales_id_material]' />
									<input type='hidden' name='hdn_nombreNR$flag' id='hdn_nombreNR$flag' value='$datos[descripcion]' />
									<input type='hidden' name='hdn_unidadNR$flag' id='hdn_unidadNR$flag' value='$datos[unidad_medida]' />
									<input type='hidden' name='hdn_existenciaNR$flag' id='hdn_existenciaNR$flag' value='$existencia' />
								</td>
								<td class='$nom_clase' align='center'>$datos[materiales_id_material]</td>
								<td class='$nom_clase' align='left'>$datos[descripcion]</td>
								<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
								<td class='$nom_clase' align='center'>$existencia</td>
								<td class='$nom_clase' align='center'>$datos[cant_req]</td>";?>
								<td class="<?php echo $nom_clase; ?>" align="center">
									<input name="<?php echo "txt_cantNR".$flag; ?>" id="<?php echo "txt_cantNR".$flag; ?>" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" 
									size="10" maxlength="15" />
								</td>
								<td class="<?php echo $nom_clase; ?>" align="center">
									$<input name="<?php echo "txt_costNR".$flag; ?>" id="<?php echo "txt_costNR".$flag; ?>" type="text" class="caja_de_num" 
									onkeypress="return permite(event,'num');" onchange="formatCurrency(value,'<?php echo "txt_costNR".$flag; ?>');"  size="10" maxlength="15" />
								</td>
							</tr>															
							<?php													
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}
				}while($datos=mysql_fetch_array($rs));
				echo "</table>";
				echo "</div>";
				?>
				<div id="botones" align="center">
					<?php 
					//Esta variable ayuda a que se vuelva a ejecutar la funcion mostrarMaterialesReq() para que se puede ejecutar la funcion de cargarDatosArr() donde se
					//redirecciona a la pagina de Agregar Material cuando los materiales no estan registrados en el catalogo de almacen?>
					<input type="hidden" name="cmb_departamento" id="cmb_departamento" value="MiDepartamento"  />
					<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo ($cont-$flag)-1;//Esta variable se utiliza para realizar la validacion con Javascript ?>"/>
					<input type="hidden" name="cant_ckbsNR" id="cant_ckbsNR" value="<?php echo $flag;//Esta variable se utiliza para realizar la validacion con Javascript ?>"/>
					<input name="sbt_registrar" type="submit" class="botones" id="btn_registrar" value="Registrar" title="Registrar Material en la Entrada" onMouseOver="window.status='';return true"/>							
					&nbsp;&nbsp;							
					<input name="rst_limpiar" type="reset" class="botones" id="btn_registrar" value="Limpiar" title="Limpiar Formulario" />
					&nbsp;&nbsp;
					<input name="btn_Cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Regresar a la P&aacute;gina de Entrada de Material" 
					onclick="location.href='frm_entradaMaterial.php?lmp=si'" />
				</div>
				</form><?php
			}//if($datos=mysql_fetch_array($rs)) de los materiales registrados en el Alamcen
		}//Cierre if(!$control)		
		else{
			//Cargar en el arreglo de datosEntrada los materiales seleccionados por el usuario
			cargarDatosArr();
		}
		if ($conn)
			//Cerrar la conexion con la BD		
			mysql_close($conn);
	}//Fin de la funcion cargarMaterialesReq($id_requisicion)
	
	//Esta funcion Despliega los materiales incluidos en la requisicion indicada oara darles entrada al Alamcen
	function mostrarMaterialesOC($id_orden_compra,$base){
		//Verificar si ha sido definido algun CheckBox en el arreglo $_POST
		$control=false;
		foreach($_POST as $clave=>$valor){
			if (substr($clave,0,3)=='ckb')
				$control=true;
		}
		
		//Si ningun CheckBox ha sido definido en el arreglo $_POST desplegar los materiales de la requisicion para darles Entrada
		if(!$control) {		
			//Realizar la conexion a la BD de Almacen
			$conn = conecta($base);
			
			//Identificar cuantos materialas de la requisicion seleccionada estan registrados en el Catalogo de Almacen
			$datos_mat_oc = identificarRegistros($id_orden_compra);
			//Disminuir el tama�o del arreglo, ya que el ultimo registro contiene un arreglo informativo y no uno con los datos del material
			$tam = count($datos_mat_oc)-1;
			//Cantidad de materiales registrados en el Catalogo de Alamcen que fueron solicitados en la Orden de Compra Seleccionada				
			$mat_registrados = $datos_mat_oc[$tam]['cant_reg_cat'];
			//Cantidad de materiales no registrados en el Catalogo de Alamcen que fueron solicitados en la Orden de Compra Seleccionada				
			$mat_nr = $datos_mat_oc[$tam]['cant_reg_nr'];
			
			echo "<div id='cargar-datos' align='center' class='borde_seccion2'>";
			//DESPLEGAR SOLO LOS MATERIALES QUE ESTAN REGISTRADOS EN EL ALMACEN
			if($mat_registrados>0){																
				echo "				
					<form onSubmit='return valFormSelecMateriales(this);' name='frm_selecMateriales' method='post' action='frm_entradaMaterial2A.php?in=oc'>
					<table width='100%' cellpadding='5'>
						<caption class='titulo_etiqueta'>Seleccionar Materiales de la Orden de Compra $id_orden_compra para Registrarlo en la Entrada</caption>
						<tr>
							<td class='nombres_columnas' align='center'>SELECCIONAR</td>
							<td class='nombres_columnas' align='center'>CLAVE</td>
    	    				<td class='nombres_columnas' align='left'>NOMBRE (DESCRIPCION)</td>
					        <td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
							<td class='nombres_columnas' align='center'>EXISTENCIA</td>
        					<td class='nombres_columnas' align='center'>CANT. SOLICITADA</td>
							<td class='nombres_columnas' align='center'>CANT. ENTRADA</td>        				
        					<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>																
	      				</tr>";								
				$nom_clase = "renglon_gris";
				$cont = 1;
				$ind = 0;				 
				do{					
					//Obtener los datos del Arreglo $datos_mat_oc
					$clave = $datos_mat_oc[$ind]['clave'];
					if($clave!="N/A"){
						$nombre = $datos_mat_oc[$ind]['nombre'];
						$unidad = $datos_mat_oc[$ind]['unidad'];
						$existencia = $datos_mat_oc[$ind]['existencia'];
						$cant_oc = $datos_mat_oc[$ind]['cantSolicitada'];
						//Dibujar cada uno de los registros contenidos en el arreglo $datos_mat_oc
						echo "	
							<tr>
								<td class='nombres_filas' align='center'>
									<input type='checkbox' name='ckb_mat$cont' id='ckb_mat$cont' value='$cont' />
									<input type='hidden' name='hdn_clave$cont' id='hdn_clave$cont' value='$clave' />
									<input type='hidden' name='hdn_nombre$cont' id='hdn_nombre$cont' value='$nombre' />
									<input type='hidden' name='hdn_unidad$cont' id='hdn_unidad$cont' value='$unidad' />
									<input type='hidden' name='hdn_existencia$cont' id='hdn_existencia$cont' value='$existencia' />
								</td>
								<td class='$nom_clase' align='center'>$clave</td>
								<td class='$nom_clase' align='left'>$nombre</td>
								<td class='$nom_clase' align='center'>$unidad</td>
								<td class='$nom_clase' align='center'>$existencia</td>
								<td class='$nom_clase' align='center'>$cant_oc</td>";?>
								<td class="<?php echo $nom_clase; ?>" align="center">
									<input name="<?php echo "txt_cant".$cont; ?>" id="<?php echo "txt_cant".$cont; ?>" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" 
									size="10" maxlength="15" />
								</td>
								<td class="<?php echo $nom_clase; ?>" align="center">
									$<input name="<?php echo "txt_cost".$cont; ?>" id="<?php echo "txt_cost".$cont; ?>" type="text" class="caja_de_num" 
									onkeypress="return permite(event,'num');" onchange="formatCurrency(value,'<?php echo "txt_cost".$cont; ?>');"  size="10" maxlength="15" />
								</td>
							</tr>															
							<?php													
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";													
					}//Cierre if($clave!="N/A")			
					
					$ind++;		
				}while($ind<$tam);
				echo "</table>";
				echo "</div>";
				?>
				<div id="botones" align="center">
					<input type="hidden" name="registrados" id="registrados" value="si" />
					<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo $cont;//Esta variable se utiliza para realizar la validacion con Javascript ?>"  />														
					<input name="sbt_registrar" type="submit" class="botones" id="btn_registrar" value="Registrar" title="Registrar Material en la Entrada" onMouseOver="window.status='';return true"/>							
					&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones" id="btn_registrar" value="Limpiar" title="Limpiar Formulario" />
					&nbsp;&nbsp;
					<input name="btn_Cancelar2" type="button" class="botones" id="btn_Cancelar" value="Cancelar" title="Regresar a la P&aacute;gina de Entrada de Material" 
					onclick="location.href='frm_entradaMaterial.php?lmp=si'" />
				</div>
				</form>
				<?php
			}//Cierre if(mat_registrados>0)
			
			
			
			//DESPLEGAR SOLO LOS MATERIALES QUE NO ESTAN REGISTRADOS EN EL ALMACEN
			if($mat_nr>0){																
				echo "
					<form onSubmit='return valFormSelecMateriales(this);' name='frm_selecMaterialesNR' method='post' action='frm_entradaMaterial2A.php?in=oc'>
					<table width='100%' cellpadding='5'>
						<caption class='titulo_etiqueta'>Los Siguientes Materiales de la Orden de Compra $id_orden_compra <u>NO</u> se Encuentran Registrados en el Cat&aacute;logo de Almac&eacute;n</caption>
						<tr>
							<td class='nombres_columnas' align='center'>REGISTRAR</td>
							<td class='nombres_columnas' align='center'>CLAVE</td>
    	    				<td class='nombres_columnas' align='left'>NOMBRE (DESCRIPCION)</td>
					        <td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
							<td class='nombres_columnas' align='center'>EXISTENCIA</td>
        					<td class='nombres_columnas' align='center'>CANT. SOLICITADA</td>
							<td class='nombres_columnas' align='center'>CANT. ENTRADA</td>        				
        					<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>																
	      				</tr>";								
				$nom_clase = "renglon_gris";
				$cont = 1;
				$ind = 0;				 
				do{					
					//Obtener los datos del Arreglo $datos_mat_oc
					$clave = $datos_mat_oc[$ind]['clave'];
					if($clave=="N/A"){
						$nombre = $datos_mat_oc[$ind]['nombre'];
						$unidad = $datos_mat_oc[$ind]['unidad'];
						$existencia = $datos_mat_oc[$ind]['existencia'];
						$cant_oc = $datos_mat_oc[$ind]['cantSolicitada'];
						//Dibujar cada uno de los registros contenidos en el arreglo $datos_mat_oc
						echo "	
							<tr>
								<td class='nombres_filas' align='center'>
									<input type='checkbox' name='ckb_matNR$cont' id='ckb_matNR$cont' value='$cont' />
									<input type='hidden' name='hdn_claveNR$cont' id='hdn_claveNR$cont' value='$clave' />
									<input type='hidden' name='hdn_nombreNR$cont' id='hdn_nombreNR$cont' value='$nombre' />
									<input type='hidden' name='hdn_unidadNR$cont' id='hdn_unidadNR$cont' value='$unidad' />
									<input type='hidden' name='hdn_existenciaNR$cont' id='hdn_existenciaNR$cont' value='$existencia' />
								</td>
								<td class='$nom_clase' align='center'>$clave</td>
								<td class='$nom_clase' align='left'>$nombre</td>
								<td class='$nom_clase' align='center'>$unidad</td>
								<td class='$nom_clase' align='center'>$existencia</td>
								<td class='$nom_clase' align='center'>$cant_oc</td>";?>
								<td class="<?php echo $nom_clase; ?>" align="center">
									<input name="<?php echo "txt_cantNR".$cont; ?>" id="<?php echo "txt_cantNR".$cont; ?>" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" 
									size="10" maxlength="15" />
								</td>
								<td class="<?php echo $nom_clase; ?>" align="center">
									$<input name="<?php echo "txt_costNR".$cont; ?>" id="<?php echo "txt_costNR".$cont; ?>" type="text" class="caja_de_num" 
									onkeypress="return permite(event,'num');" onchange="formatCurrency(value,'<?php echo "txt_costNR".$cont; ?>');"  size="10" maxlength="15" />
								</td>
							</tr>															
							<?php													
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";													
					}//Cierre if($clave!="N/A")			
					
					$ind++;		
				}while($ind<$tam);
			echo "</table>";
			echo "</div>";
			?>
				<div id="botones" align="center">
					<input type="hidden" name="registrados" id="registrados" value="no"  />
					<input type="hidden" name="cant_ckbsNR" id="cant_ckbsNR" value="<?php echo $cont;//Esta variable se utiliza para realizar la validacion con Javascript ?>" />														
					<input name="sbt_registrar" type="submit" class="botones" id="btn_registrar" value="Registrar" title="Registrar Material en el Cat&aacute;logo de Almac&eacute;n" 
					onMouseOver="window.status='';return true"/>							
					&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones" id="btn_registrar" value="Limpiar" title="Limpiar Formulario" />
					&nbsp;&nbsp;
					<input name="btn_Cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Regresar a la P&aacute;gina de Entrada de Material" 
					onclick="location.href='frm_entradaMaterial.php?lmp=si'" />
				</div>
			</form><?php
			}//Cierre if(mat_nr>0)
			//Cerrar la conexion con la BD		
			mysql_close($conn);
		}//Cierre if(!$control)		
		else{
			//Cargar en el arreglo de datosEntrada los materiales seleccionados por el usuario
			cargarDatosArr();
		}
	}//Cierre de la funcion cargarMaterialesOC($id_orden_compra)
	
	//Esta funcion se encarga de almacenar los datos de la Requisicion u Orden de Compra en el Arreglo datosEntrada que esta definido en al SESSION
	function cargarDatosArr(){
		if(isset($_GET["ped"])){
			$cantReg = $_POST["cant_ckbs"]-1;
			$cont = 1;
			$agregarMaterial="no";
			$datosEntrada=array();
			//Crear un arreglo en la SESSION para que contenga el nombre original de los materiales seleccionados de un pedido y asi porder actualizar el estado en la tabla de DETALLES_PEDIDO
			$_SESSION['nomMaterialesPedido'] = array();
			while($cont<=$cantReg){
				//Verificar los materiales que vienen Checados
				if (isset($_POST["ckb_mat".$cont])){
					//Guardar el nombre real de los materiales seleccionados del PEDIDO
					$_SESSION['nomMaterialesPedido'][] = $_POST["hdn_nombre".$cont];
					//Variable que indica si un material es nuevo o no, se declara por defaulto como NO nuevo
					$nuevo="no";
					//Si el material seleccionado es NUEVO, obtener su nombres, clave, e indicarlo como NUEVO
					if($_POST['cmb_materialP'.$cont]=="MATERIAL NUEVO"){
						$nombre = $_POST["hdn_nombre".$cont];
						$clave = $_POST["hdn_clave".$cont];
						$nuevo="si";
						$agregarMaterial="si";
					}
					else{
						$nombre = $_POST["cmb_materialP".$cont];
						$clave = $_POST["hdn_claveStock".$cont];
					}
					$partida = $_POST["hdn_part$cont"];
					$partidaReq = $_POST["hdn_part_req$cont"];
					$unidad = $_POST["txt_unidadStock".$cont];
					$existencia = $_POST["txt_existencia".$cont];
					$cantEntrada = $_POST["txt_cant".$cont];
					$costoUnidad = $_POST["txt_cost".$cont];
					$cant_req = $_POST["hdn_cant_req".$cont];
					$tipo_moneda = $_POST["txt_moneda".$cont];
					//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
					$costoUnidad=str_replace(",","",$costoUnidad);
					//Crear el arreglo de datos de Entrada
					$datosEntrada[] = array("clave"=>$clave, "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>$existencia, "cant_req"=>$cant_req,
											"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad),
											"tipoMoneda"=>$tipo_moneda,"partidaPed"=>$partida,"partidaReq"=>$partidaReq);
				}//Cierre if (isset($_POST["ckb_mat".$cont]))
				$cont++;
			}
			//Guardar el arreglo datosEntrada en una variable de Sesion que se enviar� al formulario para registrar la entrada de material
			$_SESSION['datosEntrada'] = $datosEntrada;
			//Si el material es parte de la BD, enviar al siguiente formulario, de lo contrario, enviar a la pantalla de Agregar
			if($agregarMaterial=="no")
				echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial3A.php?ped'>";		
			else
				echo "<meta http-equiv='refresh' content='0;url=frm_agregarMaterial.php?ped'>";
		}
		else{
			//Contar los CheckBox para saber cuantos registros fueron seleccionados
			$cantReg = $_POST["cant_ckbs"]+$_POST["cant_ckbsNR"];
			$cont = 1;
			$flag=0;
			$datosEntrada=array();
			$datosEntradaNR=array();
			while($cont<=$cantReg){
				if(isset($_POST["ckb_mat$cont"])){
					$clave = $_POST["hdn_clave$cont"];
					$nombre = $_POST["hdn_nombre$cont"]; 
					$unidad = $_POST["hdn_unidad$cont"];
					$partida = $_POST["hdn_part$cont"];
					$partidaReq = $_POST["hdn_part_req$cont"];
					$existencia = $_POST["hdn_existencia$cont"];
					$cantEntrada = $_POST["txt_cant$cont"];
					$costoUnidad = $_POST["txt_cost$cont"];
					$cant_req = $_POST["hdn_cant_req$cont"];
					//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
					$costoUnidad=str_replace(",","",$costoUnidad);
		
					if($cont==1){
						$datosEntrada = array(array("clave"=>$clave, "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>$existencia, "cant_req"=>$cant_req,
														"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad), 
														"partidaPed"=>$partida, "partidaReq"=>$partidaReq));					
					}	
					else{
						$datosEntrada[] = array("clave"=>$clave, "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>$existencia, "cant_req"=>$cant_req,
												"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad), 
												"partidaPed"=>$partida, "partidaReq"=>$partidaReq);
					}
				}
				if(isset($_POST["ckb_matNR$cont"])){
					$clave = $_POST["hdn_claveNR$cont"];
					$nombre = $_POST["hdn_nombreNR$cont"]; 
					$unidad = $_POST["hdn_unidadNR$cont"];
					$existencia = $_POST["hdn_existenciaNR$cont"];
					$cantEntrada = $_POST["txt_cantNR$cont"];
					$costoUnidad = $_POST["txt_costNR$cont"];
					//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
					$costoUnidad=str_replace(",","",$costoUnidad);
					
					if(!isset($datosEntradaNR))
						$datosEntradaNR = array(array("clave"=>$clave, "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>$existencia, 
										"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad)));					
					else
						$datosEntradaNR[] = array("clave"=>$clave, "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>$existencia, 
											"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad));
					
					$flag=1;
				}
				$cont++;
			}
			//Guardar el arreglo datosEntrada en una variable de Sesion que se enviar� al formulario para registrar la entrada de material
			$_SESSION['datosEntrada']=$datosEntrada;
			$_SESSION['datosEntradaNR']=$datosEntradaNR;
			//Si flag se activo, hay registros que agregar
			if($flag=="1")
				echo "<meta http-equiv='refresh' content='0;url=frm_agregarMaterial.php'>";
			else
				echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial3A.php?in=req'>";
		}
	}//Cierre de cargarDatosArr()
	
	
	//Esta funcion se ecarga de verificar cuales de los materiales que se encuentran en la Orden de Compra seleccionada estan registrados en el Catalogo de Alamacen y cuales no,
	//regresa un arreglo que contiene la informacion de los datos de la OC y la cantidad de ellos que esta registrados y los que no lo estan.
	function identificarRegistros($id_orden_compra){
		//Generar la consulta para obtener los datos solicitados en la requsicion seleccionada
		$stm_sql = "SELECT cant_oc,descripcion FROM detalle_oc WHERE orden_compra_id_orden_compra='".$id_orden_compra."' AND estado=1";
		$rs = mysql_query($stm_sql);
		
		$cant_reg_oc = mysql_num_rows($rs);
		$cant_reg_cat = 0;
		$datos_mat_oc = array();
		if($datos=mysql_fetch_array($rs)){
			do{
				//Verificar si el material en curso esta registrado en el Catalogo de Almacen
				if($datos2 = mysql_fetch_array(mysql_query("SELECT id_material,existencia FROM materiales WHERE nom_material='$datos[descripcion]'"))){
					$unidad = obtenerDato("bd_almacen","unidad_medida","unidad_medida","materiales_id_material",$datos2['id_material']);
					$datos_mat_oc[] = array("clave"=>$datos2['id_material'],"nombre"=>$datos['descripcion'],"unidad"=>$unidad,"existencia"=>$datos2['existencia'],"cantSolicitada"=>$datos['cant_oc']);
					$cant_reg_cat++;
				}
				else{
					//Si el material no esta registrado en el Catalogo de Material, separlo para solicitar su registro.
					$datos_mat_oc[] = array("clave"=>"N/A","nombre"=>$datos['descripcion'],"unidad"=>"N/A","existencia"=>"N/A","cantSolicitada"=>$datos['cant_oc']);
				}
			}while($datos=mysql_fetch_array($rs));
		}
		
		//Guardar informacion complementaria en el Arreglo
		$datos_mat_oc[] = array("cant_reg_oc"=>$cant_reg_oc, "cant_reg_cat"=>$cant_reg_cat, "cant_reg_nr"=>($cant_reg_oc-$cant_reg_cat));
		
		return $datos_mat_oc;					
	}//Cierre de la funcion identificarRegistros($id_orden_compra)
	
	function mostrarMaterialesPedido($idPedido){
		//Conectarse a la BD de Compras
		$conn=conecta("bd_compras");
		//Generar la consulta para obtener los datos del pedido seleccionado
		$stm_sql = "SELECT descripcion, cantidad, unidad, precio_unitario, importe, tipo_moneda, partida, partida_requisicion
					FROM detalles_pedido
					JOIN pedido ON pedido_id_pedido = id_pedido
					WHERE pedido_id_pedido =  '$idPedido'
					AND detalles_pedido.estado =1";
		$rs = mysql_query($stm_sql);
		echo "<div id='cargar-datos' align='center' class='borde_seccion2'>";
		if($datos=mysql_fetch_array($rs)){		
			//Mostrar los Materiales de la Requisicion que se encuentran registrados en el Almacen
			echo "				
				<p align='center' class='titulo_etiqueta'>Seleccionar Materiales del Pedido $idPedido para Registrarlo en la Entrada</p>
				<form onSubmit='return valFormSelecMaterialesPedido(this);' name='frm_selecMateriales' method='post' action='frm_entradaMaterial2A.php?ped'>
				<table width='100%' cellpadding='5'>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>NOMBRE EN PEDIDO</td>
						<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
						<td class='nombres_columnas' align='center'>NOMBRE EN STOCK</td>
						<td class='nombres_columnas' align='center'>EXISTENCIA EN STOCK</td>
						<td class='nombres_columnas' align='center'>UNIDAD MEDIDA EN STOCK</td>
						<td class='nombres_columnas' align='center'>CANT. PEDIDA</td>
						<td class='nombres_columnas' align='center'>CANT. ENTRADA</td>
						<td class='nombres_columnas' align='center' width='20%'>COSTO UNITARIO</td>
						<td class='nombres_columnas' align='center'>MONEDA</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "	
					<tr>
						<td class='nombres_filas' align='center'>
							<input type='checkbox' name='ckb_mat$cont' id='ckb_mat$cont' value='$cont'/>
							<input type='hidden' name='hdn_clave$cont' id='hdn_clave$cont' value=''/>
							<input type='hidden' name='hdn_claveStock$cont' id='hdn_claveStock$cont' value=''/>
							<input type='hidden' name='hdn_nombre$cont' id='hdn_nombre$cont' value='$datos[descripcion]'/>
							<input type='hidden' name='hdn_cant_req$cont' id='hdn_cant_req$cont' value='$datos[cantidad]'/>
							<input type='hidden' name='hdn_part$cont' id='hdn_part$cont' value='$datos[partida]'/>
							<input type='hidden' name='hdn_part_req$cont' id='hdn_part_req$cont' value='$datos[partida_requisicion]'/>
						</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$datos[unidad]</td>
						<td class='$nom_clase' align='left'>";
				
						//Cajas de Texto para materiales con Busqueda Sphider
						?>
						<input type="text" name="cmb_materialP<?php echo $cont?>" id="cmb_materialP<?php echo $cont?>" onchange="hdn_validarDatoMaterial<?php echo $cont?>.value='NO'" onkeyup="lookup(this,<?php echo $cont;?>,'1');" 
						value="" size="30" maxlength="60" onkeypress="return permite(event,'num_car',0);" autocomplete="off"/>
						<div id="res-spider2">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
						<input type="hidden" name="hdn_validarDatoMaterial<?php echo $cont?>" id="hdn_validarDatoMaterial<?php echo $cont?>" value="NO"/>
						<?php
						
						echo "</td>
						<td class='$nom_clase' align='center'>";?>
							<input type="text" class="caja_de_num" name="txt_existencia<?php echo $cont;?>" id="txt_existencia<?php echo $cont;?>" size="10" readonly="readonly"/>
						<?php 
						echo "</td>
						<td class='$nom_clase' align='center'>";?>
							<input type="text" class="caja_de_num" name="txt_unidadStock<?php echo $cont;?>" id="txt_unidadStock<?php echo $cont;?>" size="10" value="<?php echo $datos["unidad"];?>"/>
						<?php 
						echo "</td>
						<td class='$nom_clase' align='center'>$datos[cantidad]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input name="<?php echo "txt_cant".$cont; ?>" id="<?php echo "txt_cant".$cont; ?>" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" size="10" maxlength="15" 
							onchange="validarCantidadEntrada(this.value, <?php echo $datos['cantidad']; ?>, this.name);" />
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php
							//Verificar si el costo unitario es igual a 0, en dicho caso, permitir editar el precio unitario
							$atr=" readonly='readonly'";
							if($datos["precio_unitario"]==0)
								$atr="";
							?>
							$<input name="<?php echo "txt_cost".$cont; ?>" id="<?php echo "txt_cost".$cont; ?>" type="text" class="caja_de_num" 
							onkeypress="return permite(event,'num');" onchange="formatCurrency(value,'<?php echo "txt_cost".$cont; ?>');"  size="10" maxlength="15" 
							value="<?php echo number_format($datos["precio_unitario"],2,".",",")?>"<?php echo $atr;?>/>
						</td>
						<?php 
						echo "</td>
						<td class='$nom_clase' align='center'>";?>
							<input type="text" class="caja_de_num" name="txt_moneda<?php echo $cont;?>" id="txt_moneda<?php echo $cont;?>" size="10" value="<?php echo $datos["tipo_moneda"];?>" readonly="readonly"/>
					</tr>															
					<?php													
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
			echo "</div>";
			?>
				<div id="botones" align="center">
						<input type="hidden" name="registrados" id="registrados" value="si"  />
						<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo $cont;//Esta variable se utiliza para realizar la validacion con Javascript ?>"  />							
						<input name="sbt_registrar" type="submit" class="botones" id="btn_registrar" value="Registrar" title="Registrar Material en la Entrada" onMouseOver="window.status='';return true"/>							
						&nbsp;&nbsp;							
						<input name="rst_limpiar" type="reset" class="botones" id="btn_registrar" value="Limpiar" title="Limpiar Formulario" />
						&nbsp;&nbsp;
						<input name="btn_Cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Regresar a la P&aacute;gina de Entrada de Material" 
						onclick="location.href='frm_entradaMaterial.php?lmp=si'" />
				</div>
			</form><?php																										
		}//if($datos=mysql_fetch_array($rs)) de los materiales no registrados en el Almacen	
	}
	
	function mostrarMaterialesPedidoPaileria($idPedido){
		//Conectarse a la BD de Compras
		$conn=conecta("bd_compras");
		//Generar la consulta para obtener los datos del pedido seleccionado
		$stm_sql = "SELECT descripcion,cantidad,unidad,precio_unitario,importe FROM detalles_pedido WHERE pedido_id_pedido='$idPedido' AND estado = 1";
		$rs = mysql_query($stm_sql);
		echo "<div id='cargar-datos' align='center' class='borde_seccion2'>";
		if($datos=mysql_fetch_array($rs)){		
			//Mostrar los Materiales de la Requisicion que se encuentran registrados en el Almacen
			echo "				
				<p align='center' class='titulo_etiqueta'>Seleccionar Materiales del Pedido $idPedido para Registrarlo en la Entrada</p>
				<form name='frm_selecMateriales' method='post' action='frm_entradaMaterial3A.php?PAI=$idPedido' onSubmit='return valFormMatPedPai(this);'>
				<table width='100%' cellpadding='5'>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
						<td class='nombres_columnas' align='center'>CANT. PEDIDA</td>
						<td class='nombres_columnas' align='center'>CANT. ENTRADA</td>        				
						<td class='nombres_columnas' align='center' width='20%'>COSTO UNITARIO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "	
					<tr>
						<td class='nombres_filas' align='center'>
							<input type='checkbox' name='ckb_mat$cont' id='ckb_mat$cont' value='$cont'/>
						</td>
						<td class='$nom_clase' align='left'><input type='text' class='caja_de_texto' name='txt_material$cont' id='txt_material$cont' value='$datos[descripcion]' size='60' readonly='readonly'/></td>
						<td class='$nom_clase' align='center'><input type='text' class='caja_de_texto' name='txt_unidad$cont' id='txt_unidad$cont' value='$datos[unidad]' size='15' readonly='readonly'/></td>
						<td class='$nom_clase' align='center'><input type='text' class='caja_de_num' name='txt_cantidad$cont' id='txt_cantidad$cont' value='$datos[cantidad]' size='5' readonly='readonly'/></td>";?>
						
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input name="<?php echo "txt_cant".$cont; ?>" id="<?php echo "txt_cant".$cont; ?>" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" size="10" maxlength="15" />
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php
							//Verificar si el costo unitario es igual a 0, en dicho caso, permitir editar el precio unitario
							$atr=" readonly='readonly'";
							if($datos["precio_unitario"]==0)
								$atr="";
							?>
							$<input name="<?php echo "txt_cost".$cont; ?>" id="<?php echo "txt_cost".$cont; ?>" type="text" class="caja_de_num" 
							onkeypress="return permite(event,'num');" onchange="formatCurrency(value,'<?php echo "txt_cost".$cont; ?>');"  size="10" maxlength="15" 
							value="<?php echo number_format($datos["precio_unitario"],2,".",",")?>"<?php echo $atr;?>/>
						</td>
					</tr>															
					<?php													
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
			echo "</div>";
			?>
				<div id="botones" align="center">
						<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo $cont;//Esta variable se utiliza para realizar la validacion con Javascript ?>"  />							
						<input name="sbt_registrar" type="submit" class="botones" id="btn_registrar" value="Registrar" title="Registrar Material en la Entrada" onMouseOver="window.status='';return true"/>							
						&nbsp;&nbsp;							
						<input name="rst_limpiar" type="reset" class="botones" id="btn_registrar" value="Limpiar" title="Limpiar Formulario" />
						&nbsp;&nbsp;
						<input name="btn_Cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Regresar a la P&aacute;gina de Entrada de Material" 
						onclick="location.href='frm_entradaMaterial.php?lmp=si'" />
				</div>
			</form><?php																										
		}//if($datos=mysql_fetch_array($rs)) de los materiales no registrados en el Almacen	
	}//Fin de la funcion mostrarMaterialesPedidoPaileria($idPedido)
	
	//Funcion que muestra las Requisiciones de Paileria que no forman parte del Stock de Almacen
	function mostrarMaterialesReqPaileria($id_requisicion){
		//Realizar la conexion a la BD que es enviada a traves del parametro
		$conn = conecta("bd_paileria");

		//Verificar si ha sido definido algun CheckBox en el arreglo $_POST
		$control=false;
		foreach($_POST as $clave=>$valor){
			if (substr($clave,0,3)=='ckb')
				$control=true;
		}	
		
		//Si ningun CheckBox ha sido definido en el arreglo $_POST desplegar los materiales de la requisicion para darles Entrada
		if(!$control) {
			//Generar la consulta para obtener los datos solicitados en la requsicion seleccionada que esten registrados en los departamentos
			$stm_sql = "SELECT cant_req,unidad_medida,descripcion FROM detalle_requisicion 
						WHERE requisiciones_id_requisicion = '$id_requisicion' AND estado=1 ORDER BY materiales_id_material";
			$rs = mysql_query($stm_sql);
			echo "<div id='cargar-datos' align='center' class='borde_seccion2'>";			
			//Confirmar que la consulta de datos fue realizada con exito y desplegar los datos encontrados.
			if($datos=mysql_fetch_array($rs)){				
				//Mostrar los Materiales de la Requisicion que se encuentran registrados en el Almacen
				echo "
					<form onSubmit='return valFormSelecMateriales(this);' name='frm_selecMateriales' method='post' action='frm_entradaMaterial2A.php?in=reqPai'>
					<table width='100%' cellpadding='5'>";
				echo "<caption class='titulo_etiqueta'>Seleccionar Materiales de la Requisicion $id_requisicion para Registrarlo en la Entrada</caption>";
				echo "
						<tr>
							<td class='nombres_columnas' align='center'>SELECCIONAR</td>
    	    				<td class='nombres_columnas' align='left'>NOMBRE (DESCRIPCION)</td>
					        <td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
        					<td class='nombres_columnas' align='center'>CANT. REQUISITADA</td>
							<td class='nombres_columnas' align='center'>CANT. ENTRADA</td>        				
        					<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>																
	      				</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				$flag=0;
				do{											
					echo "	
						<tr>
							<td class='nombres_filas' align='center'>
								<input type='checkbox' name='ckb_mat$cont' id='ckb_mat$cont' value='$cont' />
								<input type='hidden' name='hdn_nombre$cont' id='hdn_nombre$cont' value='$datos[descripcion]' />
								<input type='hidden' name='hdn_unidad$cont' id='hdn_unidad$cont' value='$datos[unidad_medida]' />
							</td>
							<td class='$nom_clase' align='left'>$datos[descripcion]</td>
							<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
							<td class='$nom_clase' align='center'>$datos[cant_req]</td>";?>
							<td class="<?php echo $nom_clase; ?>" align="center">
								<input name="<?php echo "txt_cant".$cont; ?>" id="<?php echo "txt_cant".$cont; ?>" type="text" class="caja_de_num" onkeypress="return permite(event,'num');" 
								size="10" maxlength="15" />
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center">
								$<input name="<?php echo "txt_cost".$cont; ?>" id="<?php echo "txt_cost".$cont; ?>" type="text" class="caja_de_num" 
								onkeypress="return permite(event,'num');" onchange="formatCurrency(value,'<?php echo "txt_cost".$cont; ?>');"  size="10" maxlength="15" />
							</td>
						</tr>															
						<?php													
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "</table>";
				echo "</div>";
				?>
				<div id="botones" align="center">
					<?php 
					//Esta variable ayuda a que se vuelva a ejecutar la funcion mostrarMaterialesReq() para que se puede ejecutar la funcion de cargarDatosArr() donde se
					//redirecciona a la pagina de Agregar Material cuando los materiales no estan registrados en el catalogo de almacen?>
					<input type="hidden" name="cmb_departamento" id="cmb_departamento" value="MiDepartamento"/>
					<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo ($cont-$flag)-1;//Esta variable se utiliza para realizar la validacion con Javascript ?>"/>
					<input type="hidden" name="cant_ckbsNR" id="cant_ckbsNR" value="<?php echo 0;//Esta variable se utiliza para realizar la validacion con Javascript ?>" />														
					<input type="hidden" name="hdn_req" value="<?php echo $id_requisicion?>"/>
					<input name="sbt_registrar" type="submit" class="botones" id="btn_registrar" value="Registrar" title="Registrar Material en la Entrada" onMouseOver="window.status='';return true"/>							
					&nbsp;&nbsp;							
					<input name="rst_limpiar" type="reset" class="botones" id="btn_registrar" value="Limpiar" title="Limpiar Formulario" />
					&nbsp;&nbsp;
					<input name="btn_Cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Regresar a la P&aacute;gina de Entrada de Material" 
					onclick="location.href='frm_entradaMaterial.php?lmp=si'" />
				</div>
				</form><?php
			}//if($datos=mysql_fetch_array($rs)) de los materiales registrados en el Alamcen
		}//Cierre if(!$control)		
		else{
			//Cargar en el arreglo de datosEntrada los materiales seleccionados por el usuario
			cargarDatosArr();
		}
		if ($conn)
			//Cerrar la conexion con la BD		
			mysql_close($conn);
	}//Fin de la function mostrarMaterialesReqPaileria($requisicion)
	
	//Agregar el registro de la Entrada/Salida de Materiales que se fueron pedidos mediante una requisicion elaborada por Paileria
	function guardarEntradaSalida($cmb_provedor,$txt_noFactura,$txt_fechaEntrada,$cmb_aceptado,$txa_comentarios){
		//Obtener el ID de la Entrada
		$idEntrada=obtenerIdEntrada();
		//Obtener el ID de la Salida
		$idSalida=obtenerIdSalida();
		//Si la bandera se activa significa que hubo errores
		$band = 0;									
		$base="bd_paileria";
		//Registrar todos los materiales dados de alta en el arreglo $datosEntrada
		foreach ($_SESSION['datosEntrada'] as $ind => $material){
			$conn=conecta("bd_almacen");
			//Crear la sentencia para realizar el registro de los datos del detalle de la Entrada de Material
			$stm_sql = "INSERT INTO detalle_entradas (entradas_id_entrada,materiales_id_material,nom_material,unidad_material,linea_material,cant_entrada,costo_unidad,costo_total)
			VALUES('$idEntrada','$material[clave]','$material[nombre]','$material[unidad]','PAILERIA','$material[cantEntrada]','$material[costoUnidad]','$material[costoTotal]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_entradas
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band = 1;
				//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
				break;
			}else{
				//Verificar si el origen del Registro de Entrada es un Pedido para actualizar la Requisicion asociada
				if(substr($_SESSION["no_origen"],0,3)=="PED"){
					//Obtener la requisicion asociada al Pedido
					$reqPedido=obtenerDato("bd_compras","pedido","requisiciones_id_requisicion","id_pedido",$_SESSION['no_origen']);
					//Conectar a la BD de Paileria
					$conn=conecta("bd_paileria");
					//Crear la sentencia para actualizar el estado de la Alerta, en el caso de que se haya generado
					$stm_sql = "UPDATE detalle_requisicion SET estado = 2 WHERE (materiales_id_material = '$material[clave]' OR descripcion = '$material[nombre]') 
								AND estado = 1 AND requisiciones_id_requisicion='$reqPedido'";
					//Ejecutar la sentencia
					$rs = mysql_query($stm_sql);
				}
			}
		}//Cierre foreach ($_SESSION['datosEntrada'] as $ind => $material)
		//Pasar a Mayusculas los datos de la Entrada
		$txt_noFactura = strtoupper($txt_noFactura); 
		$cmb_provedor = strtoupper($cmb_provedor); 
		$txa_comentarios = strtoupper($txa_comentarios);
		if($band==0){
			//Obtener la Suma de los registros de la Entrada de Material
			$costoTotalEntrada = obtenerSumaRegistrosES($_SESSION['datosEntrada'],"costoTotal");																							
			//Obtener la hora actual
			$hora = date("H:i:s");
			//Crear la sentencia para almacenar los datos de la entrada en la BD
			$stm_sql = "INSERT INTO entradas(id_entrada,requisiciones_id_requisicion,orden_compra_id_orden_compra,comp_directa,proveedor,no_factura,
						costo_total,fecha_entrada,hora_entrada,aceptado,comentarios)
						VALUES('$idEntrada'";
			//Decomentar esta linea en caso de querer guardar la requisicion asociada al Pedido en lugar del ID del Pedido
			//$reqPedido=obtenerDato("bd_compras","pedido","requisiciones_id_requisicion","id_pedido",$_SESSION['no_origen']);
			//Guardar el Pedido en el espacio de las Requisiciones
			$stm_sql .= ",'$_SESSION[no_origen]','','',";
			$stm_sql .= "'$cmb_provedor','$txt_noFactura',$costoTotalEntrada,'".modFecha($txt_fechaEntrada,3)."','$hora','$cmb_aceptado','$txa_comentarios')";
			//Reconectar a la BD de Almacen
			$conn = conecta("bd_almacen");
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);						
			//Confirmar que la insercion de datos fue realizada con exito.
			if($rs){
				//Cerrar la conexion, para reabrirla para la funcion que da la Salida
				mysql_close($conn);
				//Registrar la Operacion en la Bit�cora de Movimientos
				registrarOperacion("bd_almacen",$idEntrada,"EntradaMaterial",$_SESSION['usr_reg']);
				//Registrar la Salida de inmediato que se le dio Entrada
				$salida=guardarSalidaPaileria($idSalida,$txt_fechaEntrada,$costoTotalEntrada);
				//Verificar si esta definido el Arreglo de nomMaterialesPedido en la sesion para actualizar el detalle del Pedido y despues removerlo de la Sesion
				if (isset($_SESSION['nomMaterialesPedido'])){
					//Actualizar las partidad del Pedido para indicar que el Material ya recibio Entrada
					actualizarDetallePedido();
					//Quitar el arreglo de la Sesion
					unset($_SESSION['nomMaterialesPedido']);
				}
				if(isset($_SESSION["no_origen"]) && substr($_SESSION["no_origen"],0,3)=="PAI"){
					//Actualizar el detalle de la Requisicion y Pedidos asociados
					actualizarDetalleRequisicion();
				}
				//Vaciar la informaci�n almacenada en la SESSION
				unset($_SESSION['datosEntrada']);
				unset($_SESSION['origen']);
				unset($_SESSION['no_origen']);
				unset($_SESSION['cmb_prm2']);
				//Vaciar la Informacion almacenada en la SESSION cuando el proceso de registro de nuevos materiales fue terminado con exito
				unset($_SESSION['procesoRegistroMat']);
				unset($_SESSION['clavesRegistradasMat']);
				//Verificar si esta declarado el arreglo de Sesion con las Claves Modificadas
				if(isset($_SESSION["clavesModificadasExistencia"]))
					unset($_SESSION["clavesModificadasExistencia"]);
				//Verificar si esta declarado el arreglo de Sesion con las Existencias Modificadas
				if(isset($_SESSION["clavesModificadasExistenciaCantidad"]))
					unset($_SESSION["clavesModificadasExistenciaCantidad"]);
				//Vaciar de la SESSION los nombre de los materiales obtenidos de un pedido
				unset($_SESSION['nomMaterialesPedido']);
				unset($_SESSION['bd']);
				if($salida=="")
					//Redireccionar a la Pantalla 4 Para las Entradas
					echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial4A.php?clave_entrada=$idEntrada'>";
				else
					//Redireccionar a la pagina de Error
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$salida'>";
			}
			else{
				//Redireccionar a una pagina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			//Redireccionar a una pagina de error
			$error = "No se pudieron almacenar todos los registros del Detalle de Entradas";			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de la funcion guardarEntradaSalida($txt_proveedor,$txt_noRequisicion,$txt_noFactura,$txt_costo,$txt_fecha,$cmb_aceptado,$txa_comentarios)
	
	function actualizarDetalleRequisicion(){
		$band=0;
		foreach($_SESSION["datosEntrada"] as $ind => $material){
			//Conectar a la BD que corresponde segun la Requisicion
			$conn=conecta("bd_paileria");
			$nom_material=$material['nombre'];
			//Crear la sentencia para actualizar el estado de la Alerta, en el caso de que se haya generado
			$stm_sql = "UPDATE detalle_requisicion SET estado = 2 WHERE descripcion = '$nom_material' AND estado = 1 AND requisiciones_id_requisicion='$_SESSION[no_origen]'";
			//Ejecutar la sentencia
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band=1;
				break;
			}
			//Cerrar la BD de Paileria
			mysql_close($conn);
			//Conectar a la BD de compras para verificar los Pedidos y asi remover los elementos del pedido que ya no sean necesarios
			$conn=conecta("bd_compras");
			//Sentencia SQL para extraer los Pedidos asociados a la Requisicion
			$sql_stm="SELECT id_pedido FROM pedido WHERE requisiciones_id_requisicion='$_SESSION[no_origen]'";
			//Ejecutar la sentencia
			$rs=mysql_query($sql_stm);
			//Verificar si hay resultados
			if($datos=mysql_fetch_array($rs)){
				//Ciclo para obtener los pedidos asociados a la requisicion
				do{
					//Obtener el ID del Pedido
					$idPedido=$datos["id_pedido"];
					//Crear la sentencia para actualizar el estado del Detalle del Pedido
					$stm_sql2 = "UPDATE detalles_pedido SET estado = 2 WHERE descripcion = '$nom_material' AND estado = 1 AND pedido_id_pedido='$idPedido'";
					//Ejecutar la sentencia
					$rs2 = mysql_query($stm_sql2);
					//Crear la sentencia que indica la Fecha y Hora de Llegada de Llegada del Material, para agregarlo a la BD de Compras
					$stm_sql3 = "UPDATE pedido SET hora_entrega=CURRENT_TIME(),fecha_entrega=CURRENT_DATE() WHERE id_pedido='$idPedido'";
					//Ejecutar la sentencia
					$rs3 = mysql_query($stm_sql3);
				}while($datos=mysql_fetch_array($rs));
			}//Fin de if($datos=mysql_fetch_array($rs))
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
		return $band;
	}
	
	//Esta funcion genera la ID de la salida del material en base a las salidas registradas en la BD
	function obtenerIdSalida(){		
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
		
		//Definir las dos letras en la Id de la Salida
		$id_cadena = "SM";
		
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el a�o actual para ser agregados en la consulta y asi obtener las entradas del mes y a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de entradas registradas en la BD
		$stm_sql = "SELECT COUNT(id_salida) AS cant FROM salidas WHERE id_salida LIKE 'SM$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>99 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>=1000)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Cadena con el ID de la Salida
		return $id_cadena;
	}//Fin de la Funcion obtenerIdEntrada()
	
	//Agregar el registro de la Salida de Materiales a las tablas de detalle_salidas y salidas
	function guardarSalidaPaileria($idSalida,$fecha,$costoTotal){
		//Si la bandera se activa significa que hubo errores
		$band = 0;
		//Registrar todos los materiales dados de alta en el arreglo $datosEntrada
		foreach ($_SESSION['datosEntrada'] as $ind => $material) {
			$nom_material=$material["nombre"];
			$unidad_medida=$material["unidad"];
			//Verificar si el Origen es un Pedido o una Requisicion
			if(substr($_SESSION["no_origen"],0,3)=="PED")
				//Obtener el Equipo del detalle de Pedido
				$equipo=obtenerDatoBicondicional("bd_compras", "detalles_pedido", "equipo", "pedido_id_pedido", $_SESSION["no_origen"], "descripcion", $nom_material);
			else
				//Obtener la Aplicacion del detalle de Requisicion
				$equipo=obtenerDatoBicondicional("bd_paileria", "detalle_requisicion", "aplicacion", "requisiciones_id_requisicion", $_SESSION["no_origen"], "descripcion", $nom_material);
			//Realizar la conexion a la BD de Almacen
			$conn = conecta("bd_almacen");	
			//Crear la sentencia para realizar el registro de los datos del detalle de la Salida de Material
			$stm_sql = "INSERT INTO detalle_salidas (salidas_id_salida,materiales_id_material, nom_material, unidad_material, cant_salida, 
						costo_unidad, costo_total, id_equipo_destino) 
						VALUES('$idSalida','$material[clave]','$nom_material','$unidad_medida','$material[cantEntrada]',
						'$material[costoUnidad]','$material[costoTotal]','$equipo')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_salida
			$rs = mysql_query($stm_sql);
			//Cerrar la conexion con la BD de Almacen, se abre y cierra en varias ocasiones ya que la funcion obtenerDatoBicondicional() cierra la conexion con la BD que la haya abierto
			//de modo que al abrir y cerrar en un ciclo, se evita la apariciion de errores.
			mysql_close($conn);
			//Si alguna sentencia genera errores, romper el ciclo
			if(!$rs){
				$band = 1;
				//Romper el proceso de registro del detalle de la salida en el caso de que existan errores	
				break;
			}
		}
		//Si la bandera vale 0, no se generaron errores
		if($band==0){
			//Realizar la conexion a la BD de Almacen
			$conn = conecta("bd_almacen");	
			//Obtener la Hora Actual
			$hora=date("G");
			//Convertir la hora a numero
			$hora=intval($hora);
			if($hora>=6 && $hora<=14)
				$turno="TURNO DE PRIMERA";
			if($hora>14 && $hora<=22)
				$turno="TURNO DE SEGUNDA";
			if($hora<6 || $hora>22)
				$turno="TURNO DE TERCERA";
			//Crear la sentencia para almacenar los datos de la entrada en la BD
			$stm_sql = "INSERT INTO salidas (id_salida,fecha_salida,solicitante,destino,depto_solicitante,turno,costo_total,no_vale)
						VALUES('$idSalida','".modFecha($fecha,3)."','ARTURO PEREZ TIRADO','PAILERIA','PAILERIA',
						'$turno',$costoTotal,'NO APLICA')";
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
		
			//Confirmar que la insercion de datos fue realizada con exito.
			if($rs){
				//Cerrar la conexion con la BD de Almacen
				mysql_close($conn);
				//Registrar la Operacion en la Bit�cora de Movimientos
				registrarOperacion("bd_almacen",$idSalida,"SalidaMaterial",$_SESSION['usr_reg']);
				$error="";
			}			
			else
				//Capturar Error
				$error = mysql_error();
		}
		else
			$error = "No se pudieron almacenar todos los registros del Detalle de Salidas";
		return $error;
	}
	
	//Esta funcion se encarga de almacenar los datos de la Requisicion u Orden de Compra en el Arreglo datosEntrada que esta definido en al SESSION
	function cargarDatosArrReqPai(){
		//Contar los CheckBox para saber cuantos registros fueron seleccionados
		$cantReg = $_POST["cant_ckbs"];
		$cont = 1;
		$datosEntrada=array();
		while($cont<=$cantReg){
			if(isset($_POST["ckb_mat$cont"])){
				$nombre = $_POST["hdn_nombre$cont"]; 
				$unidad = $_POST["hdn_unidad$cont"];
				$cantEntrada = $_POST["txt_cant$cont"];
				$costoUnidad = $_POST["txt_cost$cont"];
				//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
				$costoUnidad=str_replace(",","",$costoUnidad);
	
				if($cont==1){
					$datosEntrada = array(array("clave"=>"�NOVALE", "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>"N/A", 
													"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad)));					
				}	
				else{
					$datosEntrada[] = array("clave"=>"�NOVALE", "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>"N/A", 
											"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad));
				}
			}
			$cont++;
		}
		//Guardar el arreglo datosEntrada en una variable de Sesion que se enviar� al formulario para registrar la entrada de material
		$_SESSION['datosEntrada']=$datosEntrada;
	}//Cierre de cargarDatosArr()
	
	function actualizarPartidaRequi($part_req){
		$base_datos = "";
		switch(substr($_SESSION["no_req"],0,3)){
			case "ALM":
				$base_datos = "bd_almacen";
			break;
			case "ASE":
				$base_datos = "bd_aseguramiento";
			break;
			case "USO":
				$base_datos = "bd_clinica";
			break;
			case "DES":
				$base_datos = "bd_desarrollo";
			break;
			case "GER":
				$base_datos = "bd_gerencia";
			break;
			case "LAB":
				$base_datos = "bd_laboratorio";
			break;
			case "MAN":
				$base_datos = "bd_mantenimiento";
			break;
			case "MAC":
				$base_datos = "bd_mantenimiento";
			break;
			case "PAI":
				$base_datos = "bd_paileria";
			break;
			case "PRO":
				$base_datos = "bd_produccion";
			break;
			case "REC":
				$base_datos = "bd_recursos";
			break;
			case "SEG":
				$base_datos = "bd_seguridad";
			break;
			case "TOP":
				$base_datos = "bd_topografia";
			break;
		}
		$conn = conecta($base_datos);
		
		mysql_query("UPDATE detalle_requisicion SET estado='7' WHERE requisiciones_id_requisicion='$_SESSION[no_req]' AND partida='$part_req'");
		mysql_query("UPDATE requisiciones SET estado='ENTREGADA' WHERE id_requisicion='$_SESSION[no_req]'");
		
		mysql_close($conn);
	}
?>
	<script type="text/javascript" language="javascript">
		function asignarExistencia(combo,num){
			var cantidad=combo.value.split("|�");
			if (cantidad!="matNuevo"){
				document.getElementById("txt_existencia"+num).value=cantidad[0];
				document.getElementById("hdn_clave"+num).value=cantidad[1];
			}
			else{
				document.getElementById("txt_existencia"+num).value=0;
				document.getElementById("hdn_clave"+num).value="";
			}
		}
	</script>