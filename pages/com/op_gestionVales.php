<?php
/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 31/Julio/2012
	  * Descripción: Este archivo contiene funciones para la Gestion de los Vales de Compras
	  **/
	  
//Funcion que muestra los materiales del Detalle de Vale segun el arreglo de Sesion
function mostrarDetallesVale($detallesVale){
	echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Materiales Agregados al Vale</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>CANTIDAD</td>
				<td class='nombres_columnas' align='center'>CONCEPTO</td>
        		<td class='nombres_columnas' align='center'>UNIDAD</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($detallesVale as $ind => $detalle) {
			echo "<tr>";
			foreach ($detalle as $key => $value) {
				switch($key){
					case "concepto":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cantidad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "medida":
						echo "<td class='$nom_clase' align='center'>$value</td>";
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
}

//Funcion que obtiene el ID de Vale
function obtenerIDVale(){
	//Realizar la conexion a la BD de la Clinica
	$conn = conecta("bd_compras");
	$id=1;
	//Crear la sentencia para obtener la clave de la Empresa
	$stm_sql = "SELECT MAX(id_vale) AS cant FROM vale";
	$rs = mysql_query($stm_sql);
	if($datos=mysql_fetch_array($rs)){
		if($datos["cant"]!=NULL)
			$id = ($datos['cant'])+1;
	}
	mysql_close($conn);
	return $id;
}//Fin de function obtenerIDVale()

//funcion que Guarda el Vale en la Base de Datos
function registrarVale(){
	//Variable Bandera para verificar si el vale fue guardado correctamente
	$band=0;
	//Obtener los datos Principales del Vale
	$idVale=obtenerIDVale();
	$rfcProv=obtenerDato("bd_compras","proveedores","rfc","razon_social",$_POST["txt_nomProveedor"]);
	$noVale=$_POST["txt_noVale"];
	$fecha=modFecha($_POST["txt_fecha"],3);
	$obra=strtoupper($_POST["txt_obra"]);
	$autorizo=$_POST["txt_autorizo"];
	//Sentencia SQL para guardar el Vale
	$sql_stm="INSERT INTO vale (id_vale,proveedores_rfc,no_vale,fecha,obra,autorizo) VALUES ('$idVale','$rfcProv','$noVale','$fecha','$obra','$autorizo')";
	//Abrir la BD
	$conn=conecta("bd_compras");
	$rs=mysql_query($sql_stm);
	if($rs){
		foreach($_SESSION["detallesVale"] as $ind => $materiales){
			$sql="INSERT INTO detalle_vale (vale_id_vale,partida,id_material,cantidad,concepto,medida) VALUES
				('$idVale','$materiales[partida]','$materiales[idMaterial]','$materiales[cantidad]','$materiales[concepto]','$materiales[medida]')";
			$rs=mysql_query($sql);
			if(!$rs){
				$band=1;
				break;
			}
		}
	}
	//Independientemente del Exito o Error, Borrar el arreglo de Sesion
	unset($_SESSION["detallesVale"]);
	//Verificar si la bandera se activo o no
	if($band==0){
		//Cerrar la conexion
		mysql_close($conn);
		//Registrar la Operacion
		registrarOperacion("bd_compras",$idVale,"RegistrarVale",$_SESSION['usr_reg']);
		//Redireccionar a Exito
		echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
	}
	else{
		//Capturar el error
		$error=mysql_error();
		//Borrar el vale y el detalle registrado
		mysql_query("DELETE FROM vale WHERE id_vale='$idVale'");
		mysql_query("DELETE FROM detalle_vale WHERE vale_id_vale='$idVale'");
		//Cerrar la conexion
		mysql_close($conn);
		//Redireccionar a error
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
	}
}

function mostrarVales(){
	$fechaI=modFecha($_POST["txt_fechaIni"],3);
	$fechaF=modFecha($_POST["txt_fechaFin"],3);
	$conn=conecta("bd_compras");
	$sql="SELECT id_vale,no_vale,fecha,obra,autorizo,estado FROM vale WHERE fecha BETWEEN '$fechaI' AND '$fechaF'";
	$rs=mysql_query($sql);
	if($datos=mysql_fetch_array($rs)){
		//Desplegar los resultados de la consulta en una tabla
		echo "
		<table cellpadding='5' width='100%'>								
			<caption align='center' class='titulo_etiqueta'>Vales Registrados</caption>
			<tr>
				<td class='nombres_columnas' align='center'>VER DETALLE</td>
				<td class='nombres_columnas' align='center'>N0. VALE</td>
				<td class='nombres_columnas' align='center'>FECHA</td>
				<td class='nombres_columnas' align='center'>OBRA</td>
				<td class='nombres_columnas' align='center'>AUTORIZ&Oacute;</td>
				<td class='nombres_columnas' align='center'>ESTADO</td>
			</tr>				
			<form name='frm_detalleVales' method='post' action='frm_consultarVale.php'>
			<input type='hidden' name='verDetalle' value='si' />";

		$nom_clase = "renglon_gris";
		$cont = 1;	
		do{	
			//Mostrar todos los registros que han sido completados
			echo "
				<tr>
					<td class='nombres_filas' width='6%' align='center'><input type='checkbox' name='ckb_vale' id='ckb_vale' value='$datos[id_vale]' 
					onClick='javascript:document.frm_detalleVales.submit();'/></td>
					<td class='$nom_clase' width='11%'>$datos[no_vale]</td>
					<td class='$nom_clase' width='17%'>".modFecha($datos['fecha'],1)."</td>
					<td class='$nom_clase' width='10%'>$datos[obra]</td>
					<td class='$nom_clase' width='20%'>$datos[autorizo]</td>";
			switch($datos["estado"]){
				case 1:
					echo "<td class='$nom_clase'>NO COMPLEMENTADA</td>";
					break;
				case 2:
					echo "<td class='$nom_clase'>COMPLEMENTADA</td>";
					break;
				case 3:
					echo "<td class='$nom_clase'>CANCELADA</td>";
					break;
			}
			echo "
				</tr>";
				
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";				
		}while($datos=mysql_fetch_array($rs));
		?>
		<input type="hidden" name="txt_fechaIni" id="txt_fechaIni" value="<?php echo $_POST["txt_fechaIni"]?>"/>
		<input type="hidden" name="txt_fechaFin" id="txt_fechaFin" value="<?php echo $_POST["txt_fechaFin"]?>"/>
		<?php
		//Fin de la tabla donde se muestran los resultados de la consulta
		echo "</form>	
		</table>";
	}
}

function mostrarMaterialesVale($idVale){
	$conn=conecta("bd_compras");
	$sql="SELECT partida,cantidad,concepto,medida,costo_unitario FROM detalle_vale WHERE vale_id_vale='$idVale'";
	$rs=mysql_query($sql);
	if($datos=mysql_fetch_array($rs)){
		//Desplegar los resultados de la consulta en una tabla
		echo "
		<table cellpadding='5' width='100%'>								
			<caption align='center' class='titulo_etiqueta'>Materiales del Vale</caption>
			<tr>
				<td class='nombres_columnas' align='center'>PARTIDA</td>
				<td class='nombres_columnas' align='center'>CANTIDAD</td>
				<td class='nombres_columnas' align='center'>CONCEPTO</td>
				<td class='nombres_columnas' align='center'>MEDIDA</td>
				<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>
			</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;	
		do{	
			//Mostrar todos los registros que han sido completados
			echo "
				<tr>
					<td class='$nom_clase' align='center'>$datos[partida]</td>
					<td class='$nom_clase' align='center'>$datos[cantidad]</td>
					<td class='$nom_clase' align='center'>$datos[concepto]</td>
					<td class='$nom_clase' align='center'>$datos[medida]</td>";
			?>
				<td class="<?php echo $nom_clase?>" align="center">
					$<input type="text" class="caja_de_num" name="txt_precioU<?php echo $cont?>" id="txt_precioU<?php echo $datos["partida"]?>" 
					value="<?php echo number_format($datos["costo_unitario"],2,".",",")?>" size="8" maxlength="10" 
					onchange="formatCurrency(this.value,this.name);"/>
				</td>
			<?php
			echo "
				</tr>";
				
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";				
		}while($datos=mysql_fetch_array($rs));
		$cont--;
		//Fin de la tabla donde se muestran los resultados de la consulta
		echo "
		<input type='hidden' name='hdn_cantidad' id='hdn_cantidad' value='$cont'/>
		</table>";
	}
}

function guardarModificacion(){
	//Recuperar los datos del formulario
	$idVale=$_POST["hdn_vale"];
	$rfcProv=obtenerDato("bd_compras","proveedores","rfc","razon_social",$_POST["txt_nomProveedor"]);
	$noVale=$_POST["txt_noVale"];
	$fecha=modFecha($_POST["txt_fecha"],3);
	$obra=strtoupper($_POST["txt_obra"]);
	$autorizo=$_POST["txt_autorizo"];
	$moneda=$_POST["cmb_tipoMoneda"];
	$estado=$_POST["cmb_estado"];
	//Conectar a la BD
	$conn=conecta("bd_compras");
	//Crear sentencia SQL
	$sql="UPDATE vale SET no_vale='$noVale',fecha='$fecha',obra='$obra',autorizo='$autorizo',moneda='$moneda',estado='$estado' WHERE id_vale='$idVale'";
	//Ejecutar la sentencia SQL
	$rs=mysql_query($sql);
	if($rs){
		//Obtener la cantidad de Materiales
		$cant=$_POST["hdn_cantidad"];
		//Variable para recorrer los materiales
		$cont=1;
		//Recorrer los check
		do{
			$costoMat=str_replace(",","",$_POST["txt_precioU$cont"]);
			$sql="UPDATE detalle_vale SET costo_unitario='$costoMat' WHERE vale_id_vale='$idVale' AND partida='$cont'";
			//Ejecutar la sentencia SQL
			$rs=mysql_query($sql);
			if(!$rs){
				break;
			}	
			$cont++;
		}while($cont<=$cant);
		if($cant>$cont){
			//Capturar el error
			$error=mysql_error();
			//Cerrar la conexion
			mysql_close($conn);
			//Redireccionar a error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		else{
			//Cerrar la BD
			mysql_close($conn);
			//Registrar la Operacion
			registrarOperacion("bd_compras",$idVale,"ActualizarVale",$_SESSION['usr_reg']);
			//Redireccionar a Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
	}
}
?>