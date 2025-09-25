<?php
	/**
	  * Nombre del M�dulo: Compras
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 19/Noviembre/2010
	  * Descripci�n: Este archivo contiene funciones para Mostrar el detalle de requisiciones por cada departamento
	  **/


	 //Mostrar el detalle de los materiales de acuerdo a los parametros seleccionados
	 function dibujarDetalle($clave_req,$departamento,$base){
	 	//Realizar la conexion a la BD de Almacen
		$conn = conecta($base);

		//Crear sentencia SQL
		$stm_sql="SELECT cant_req, unidad_medida, descripcion, aplicacion, precio_unit, mat_pedido, id_control_costos, tipo_moneda, estado, partida, cant_entrega, tipo_entrega, comentarios, id_equipo FROM detalle_requisicion WHERE requisiciones_id_requisicion='".$clave_req."'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "
				<table cellpadding='5' width='1400px'>
				<tr>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>PROVEEDORES</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
        				<td class='nombres_columnas' align='center'>UNIDAD</td>
				        <td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
        				<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>
						<td class='nombres_columnas' align='center'>TIPO MONEDA</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
						<td class='nombres_columnas' align='center'>TIEMPO ENTREGA</td>
						<td class='nombres_columnas' align='center'>COMENTARIOS</td>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				if ($datos["id_equipo"] != "N/A") {
					$aplicacion = $datos["id_equipo"];
				} else {
					if (substr($datos["aplicacion"], 0, 3) == "CAT") {
						$aplicacion = strtoupper(obtenerDatoTabla('categorias_mat','id_categoria',$datos["aplicacion"],"bd_almacen"));
					}
					else if($datos['aplicacion'] != "N/A"){
						$aplicacion = $datos['aplicacion'];
					}
					else{
						$aplicacion = obtenerCentroCosto('control_costos','id_control_costos',$datos['id_control_costos']);
					}
				}
					?>
					<tr>
					<td class="nombres_filas" align="center">
						<input type="submit" value="COTIZACION" class="botones" onclick="document.getElementById('frm_detallesRequisicion').action='frm_cotizacionProveedores.php?partida=<?php echo $datos['partida'];; ?>'"/>
					</td>
					<?php
				echo "
					<!-- <td class='nombres_filas' align='center'>"; ?> <input type="checkbox" name="ckb_agr<?php echo $cont?>" id="ckb_agr<?php echo $cont?>" checked="true"/> <?php echo "</td> -->
					<td class='nombres_filas' align='center'>$datos[cant_req]</td><input type='hidden' class='caja_de_texto' id='hdn_cr$cont' name='hdn_cr$cont' value='$datos[cant_req]'/>
					<td class='$nom_clase' align='center'>$datos[unidad_medida]</td><input type='hidden' class='caja_de_texto' id='hdn_um$cont' name='hdn_um$cont' value='$datos[unidad_medida]'/>
					<td class='$nom_clase' align='center'>$datos[descripcion]</td><input type='hidden' class='caja_de_texto' id='hdn_desc$cont' name='hdn_desc$cont' value='$datos[descripcion]'/>
					<td class='$nom_clase' align='center'>$aplicacion</td><input type='hidden' class='caja_de_texto' id='hdn_apl$cont' name='hdn_apl$cont' value='$datos[aplicacion]'/>
					<td class='$nom_clase' align='center'>"; ?>
					$&nbsp;<?php echo $datos["precio_unit"]; ?><input type='hidden' class='caja_de_texto' id='cost_uni<?php echo $cont?>' name='cost_uni<?php echo $cont?>' style="text-align:center;"
					onkeypress="return permite(event,'num', 2);" size='10' maxlength='15'/ value="<?php echo $datos["precio_unit"]; ?>"></td>
					<?php
					echo "<td class='$nom_clase' align='center'>$datos[tipo_moneda]"; ?>
							<input type="hidden" name="txt_moneda<?php echo $cont; ?>" id="txt_moneda<?php echo $cont; ?>" class="caja_de_num" 
							value="<?php echo $datos['tipo_moneda']; ?>" size="10" readonly="readonly"/>
							<!-- 
							<option <?php if ($datos["tipo_moneda"]=="") echo "selected='selected' ";?>value="">Seleccionar</option>
							<option <?php if ($datos["tipo_moneda"]=="PESOS") echo "selected='selected' ";?>value="PESOS">PESOS</option>
							<option <?php if ($datos["tipo_moneda"]=="DOLARES") echo "selected='selected' ";?>value="DOLARES">DOLARES</option>
							<option <?php if ($datos["tipo_moneda"]=="EUROS") echo "selected='selected' ";?>value="EUROS">EUROS</option>
							-->
						<?php
					echo "</td>";
					echo "<td class='$nom_clase' align='center'>
						<select name='txt_estadoReq".$cont."' id='txt_estadoReq".$cont."'>"; ?>
							<option <?php if ($datos["estado"]=="1") echo "selected='selected' ";?>value="1">Seleccionar</option>
							<option <?php if ($datos["estado"]=="4") echo "selected='selected' ";?>value="4">COTIZANDO</option>
							<option <?php if ($datos["estado"]=="5") echo "selected='selected' ";?>value="5">EN PROCESO</option>
							<option <?php if ($datos["estado"]=="6") echo "selected='selected' ";?>value="6">EN TRANSITO</option>
							<option <?php if ($datos["estado"]=="2") echo "selected='selected' ";?>value="2">PEDIDO</option>
							<option <?php if ($datos["estado"]=="7") echo "selected='selected' ";?>value="7">ENTREGADA</option>
							<option <?php if ($datos["estado"]=="3") echo "selected='selected' ";?>value="3">CANCELADA</option>
							<option <?php if ($datos["estado"]=="8") echo "selected='selected' ";?>value="8">AUTORIZADA</option>
							<option <?php if ($datos["estado"]=="9") echo "selected='selected' ";?>value="9">NO AUTORIZADA</option>
					<?php
					echo "</select></td>";
					echo "<td class='$nom_clase' align='center'>";
						?>
							<input type="text" id="txt_cantidadEntr<?php echo $cont; ?>" name="txt_cantidadEntr<?php echo $cont; ?>" class="caja_de_num"
							onkeypress="return permite(event,'num', 2);" size="2" maxlength="3" value="<?php echo $datos['cant_entrega']; ?>"/>
							<select name="txt_entregaTipo<?php echo $cont; ?>" id="<?php echo $cont; ?>" >
								<option <?php if ($datos["tipo_entrega"]=="") echo "selected='selected' ";?>value="">Seleccionar</option>
								<option <?php if ($datos["tipo_entrega"]=="DIAS") echo "selected='selected' ";?>value="DIAS">DIAS</option>
								<option <?php if ($datos["tipo_entrega"]=="SEMANAS") echo "selected='selected' ";?>value="SEMANAS">SEMANAS</option>
								<option <?php if ($datos["tipo_entrega"]=="MESES") echo "selected='selected' ";?>value="MESES">MESES</option>
							</select>
						<?php
					echo "</td>";
					echo "<td class='$nom_clase' align='center'>";
						?>
							<textarea name="txa_comentariosDet<?php echo $cont; ?>" id="txa_comentariosDet<?php echo $cont; ?>" maxlength='120' onkeyup='return ismaxlength(this)' 
							rows="3" cols="40" class="caja_de_texto" style="resize:none"><?php echo $datos['comentarios']; ?></textarea>
							<input type="hidden" name="txt_partida<?php echo $cont; ?>" id="txt_partida<?php echo $cont; ?>" value="<?php echo $datos['partida']; ?>"/>
						<?php
					echo "</td>";
				echo "	</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";

			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
			?> 
			<input type="hidden" name="hdn_numpar" id="hdn_numpar" value="<?php echo $cont-1; ?>"/>
			<input type="hidden" name="bus" id="bus" value="<?php echo $_GET['bus']; ?>"/>
			<input type="hidden" name="depto" id="depto" value="<?php echo $_GET['depto']; ?>"/>
			<input type="hidden" name="cmb_estadoBuscar" value="<?php echo $_POST["cmb_estadoBuscar"];?>"/>
			<?php
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	 }//Fin de la funcion dibujarDetalle($requisicion,$departamento)

	//Esta funcion se encarga de guardar el comentario escrito para las requisiciones, adem�s del estado asignado
	 function guardarComentario($idReq,$comentario,$departamento,$base,$estado){
	 	$comentario=strtoupper($comentario);
		 //Realizar la conexion a la BD de Almacen
		$conn = conecta($base);
		//Crear sentencia SQL
		if($estado == ""){
			$estado = "ENVIADA";
		}
		$stm_sql="UPDATE requisiciones SET comentario_compras='$comentario',estado='COTIZANDO' WHERE id_requisicion='$idReq'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		if ($rs){
			for($i=1; $i<=$_POST["hdn_numpar"]; $i++){
				$precio = $_POST["cost_uni".$i];
				$moneda = strtoupper($_POST["txt_moneda".$i]);
				$cr = $_POST["hdn_cr".$i];
				$uni = $_POST["hdn_um".$i];
				$des = $_POST["hdn_desc".$i];
				$apl = $_POST["hdn_apl".$i];
				$stm_sql = "UPDATE detalle_requisicion SET precio_unit='$precio', tipo_moneda='$moneda'
							WHERE requisiciones_id_requisicion='$idReq'
							AND cant_req='$cr'
							AND unidad_medida LIKE '$uni'
							AND descripcion LIKE '$des'
							AND aplicacion LIKE '$apl'";
				
				$rs = mysql_query($stm_sql);
			}
			registrarOperacion("bd_compras",$idReq,"CotizarRequisicion",$_SESSION['usr_reg']);
			?>
			<script type="text/javascript" language="javascript">
			setTimeout("mensajeOk()",1000);

			function mensajeOk(){
				alert("Requisici�n <?php echo $idReq;?> Modificada Correctamente");
			}
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
			setTimeout("mensajeMal()",1000);

			function mensajeMal(){
				alert("La Requisici�n <?php echo $idReq;?> No se Pudo Modificar");
			}
			</script>
			<?php
		}
	 }
	 
	 //Esta funcion se encarga de guardar el estado escrito para las requisiciones, adem�s del estado asignado
	 function guardarEstado($idReq,$comentario,$departamento,$base,$estado){
		
		$band = 1;
		$comentario_comp = strtoupper($comentario);
		$conn = conecta($base);
		for($i=1; $i<=$_POST["hdn_numpar"]; $i++){
			$fecha = date("Y-m-d");
			$estado = $_POST["txt_estadoReq".$i];
			$cantEnt = $_POST["txt_cantidadEntr".$i];
			$tipoEnt = $_POST["txt_entregaTipo".$i];
			$partida = $_POST["txt_partida".$i];
			$comentario = strtoupper($_POST["txa_comentariosDet".$i]);
			
			if($estado != 6){
				$cantEnt = 0;
				$tipoEnt = "";
			}
			
			$est_part = obtenerEstadoPartida($idReq,$base,$partida);
			
			if($estado == $est_part){
				$stm_sql = "UPDATE detalle_requisicion SET 
								estado='$estado',
								cant_entrega='$cantEnt',
								tipo_entrega='$tipoEnt',
								comentarios='$comentario' 
							WHERE requisiciones_id_requisicion='$idReq' 
							AND partida='$partida'";
			} else {
				$stm_sql = "UPDATE detalle_requisicion SET 
								estado='$estado',
								cant_entrega='$cantEnt',
								tipo_entrega='$tipoEnt',
								fecha_estado='$fecha',
								comentarios='$comentario' 
							WHERE requisiciones_id_requisicion='$idReq' 
							AND partida='$partida'";
			}
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band = 0;
			}
		}
		mysql_close($conn);
		if($band == 1){
			$estadoReq = obtenerEstadoPrioritario($idReq,$base);
			mysql_query("UPDATE requisiciones SET estado='$estadoReq[0]',cant_entrega='$estadoReq[1]',tipo_entrega='$estadoReq[2]',comentario_compras='$comentario_comp' WHERE id_requisicion='$idReq'");
			registrarOperacion("bd_compras",$idReq,"CambiaEstadoDetalleRequisicion",$_SESSION['usr_reg']);
			?>
			<script type="text/javascript" language="javascript">
			setTimeout("mensajeOk()",1000);

			function mensajeOk(){
				alert("Partidas de la Requisici�n <?php echo $idReq; ?> Modificadas Correctamente");
			}
			</script>
			<?php
		} else {
			?>
			<script type="text/javascript" language="javascript">
			setTimeout("mensajeMal()",1000);

			function mensajeMal(){
				alert("La Requisici�n <?php echo $idReq;?> No se Pudo Modificar");
			}
			</script>
			<?php
		}
	 	/*$comentario=strtoupper($comentario);
		 //Realizar la conexion a la BD de Almacen
		$conn = conecta($base);
		//Crear sentencia SQL
		if($estado == ""){
			$estado = "ENVIADA";
		}
		$stm_sql="UPDATE requisiciones SET comentario_compras='$comentario',estado='$estado' WHERE id_requisicion='$idReq'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		if ($rs){
			
			registrarOperacion("bd_compras",$idReq,"CambiaEstadoRequisicion$estado",$_SESSION['usr_reg']);
			?>
			<script type="text/javascript" language="javascript">
			setTimeout("mensajeOk()",1000);

			function mensajeOk(){
				alert("Requisici�n <?php echo $idReq;?> Modificada Correctamente");
			}
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
			setTimeout("mensajeMal()",1000);

			function mensajeMal(){
				alert("La Requisici�n <?php echo $idReq;?> No se Pudo Modificar");
			}
			</script>
			<?php
		}*/
	 }
	 //*******************************************************************************************************************************//
	 //*******************************************************************************************************************************//
	 //*******************************************************************************************************************************//
	 //De aqui en delante son modificaciones para una pagina generica de consulta de requisiciones, con la funcionalidad por completo //
	 //*******************************************************************************************************************************//
	 //*******************************************************************************************************************************//
	 //*******************************************************************************************************************************//

	 //Esta funcion esta encargada de asignar el estado a la requisicion seleccionada
	 function asignarEstado($departamento,$base){
		$cve_req=$_POST["rdb_req"];
		$estado="";
		if(isset($_POST["cmb_estado$cve_req"]))
			$estado=$_POST["cmb_estado$cve_req"];
		//Si el estado es diferente de vacio, se selecciono un estado nuevo, entonces se realiza la conexion a la BD para actualizar el estado de las requisiciones
		if ($estado!=""){
			$cant=$_POST["txt_cantidadEntr$cve_req"];
			$tipo=$_POST["txt_entregaTipo$cve_req"];
			$fecha=date("Y-m-d");
			
			if($estado != "EN TRANSITO"){
				$cant=0;
				$tipo="";
			}
			switch($estado){
				case "ENVIADA":
					$estadoDet=1;
				break;
				case "PEDIDO":
					$estadoDet=2;
				break;
				case "CANCELADA":
					$estadoDet=3;
				break;
				case "COTIZANDO":
					$estadoDet=4;
				break;
				case "EN PROCESO":
					$estadoDet=5;
				break;
				case "EN TRANSITO":
					$estadoDet=6;
				break;
				case "ENTREGADA":
					$estadoDet=7;
				break;
				case "AUTORIZADA":
					$estadoDet=8;
				break;
				case "NO AUTORIZADA":
					$estadoDet=9;
				break;
			}
			//Se crea la conexion con la BD del departamento seleccionado, se quitan los espacios en blanco para poder conectarse
			$conec=conecta($base);
			//Si la base de datos no existe, redirecciona a la p�gina de construccion, esta comparaci�n no debe realizarse, pero se deja en caso extremo
			if (!$conec){
				echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
			}
			else{
				$stm_sql="UPDATE requisiciones SET estado='$estado', cant_entrega='$cant', tipo_entrega='$tipo' WHERE id_requisicion='$cve_req'";
				$rs=mysql_query($stm_sql);
				mysql_query("UPDATE detalle_requisicion SET estado='$estadoDet', cant_entrega='$cant', tipo_entrega='$tipo', fecha_estado = '$fecha' WHERE requisiciones_id_requisicion='$cve_req' AND estado != '7'");
				registrarOperacion("bd_compras",$cve_req,"CambiaEstadoRequisicion$estado",$_SESSION['usr_reg']);
			}
			echo "<div id='Msje' class='msje_correcto' align='center'>*Estado ".$estado." asignado a la Requisici&oacute;n ".$cve_req."</div>";
		}else
			echo "<div id='Msje' class='msje_incorrecto' align='center'>*Requisici&oacute;n ".$cve_req." seleccionada, Seleccionar Estado para ser Asignado</div>";
	 }

	 function mostrarRequisicionDetalle($departamento,$base,$id_requis){
	 	//Verificar si hay Filtros de por medio, para agregarlos como Hidden y no perderlos
		if(isset($_POST["txa_filtro"]) && $_POST["txa_filtro"]!=""){
			?>
			<input type="hidden" name="txa_filtro" value="<?php echo $_POST["txa_filtro"]?>"/>
			<input type="hidden" name="cmb_filtro" value="<?php echo $_POST["cmb_filtro"]?>"/>
			<?php
		}
	 	$cve_req=$id_requis;
		//Conexion a la BD de requisiciones del departamento para ejecutar la obtenci�n de datos de la Requisici�n seleccionada y que llega en el POST
		$conec=conecta($base);
		//Si la base de datos no existe, redirecciona a la p�gina de construccion
			if (!$conec){
				echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
			}
			else{
				$cve_req=$id_requis;
				//Sentencia SQL con los datos de la requisicion a buscar
				$stm_sql="SELECT area_solicitante, solicitante_req, id_requisicion, fecha_req, justificacion_tec, comentario_compras,estado FROM requisiciones WHERE
				id_requisicion='$cve_req'";
				$rs=mysql_query($stm_sql);
				$row = mysql_fetch_array($rs);
				if ($_GET["bus"]=="fecha"){
					echo "<input type='hidden' name='txt_fechaIni' value='".$_POST["txt_fechaIni"]."'/>";
					echo "<input type='hidden' name='txt_fechaFin' value='".$_POST["txt_fechaFin"]."'/>";
				}
				else{
					if($_GET["bus"]==1){
						echo "<input type='hidden' name='cmb_estadoBuscar' value='ENVIADA'/>";
					} else{
						echo "<input type='hidden' name='cmb_estadoBuscar' value='".$_POST["cmb_estadoBuscar"]."'/>";
					}
				}
				echo "<table width='700' cellpadding='5' cellspacing='5' class='tabla_frm'>
					<tr>
						<input type='hidden' name='hdn_estado' value='$row[estado]'>
						<td align='right'>&Aacute;rea Solicitante</td>
						<td><input name='txt_areaSolicitante' type='text' class='caja_de_texto' size='50' readonly=true value='".$row["area_solicitante"]."'/></td>
						<td align='right'>N&uacute;mero</td>
						<td><input name='txt_numero' type='text' class='caja_de_texto' readonly=true value='".$row["id_requisicion"]."'/>
							<input type='hidden' name='hdn_numero' value='".$row["id_requisicion"]."'/>
							<input type='hidden' name='hdn_bd' value='".$base."'/>
						</td>
					</tr>
					<tr>
						<td align='right'>Solicita</td>
						<td><input name='txt_solicita' type='text' class='caja_de_texto' size='50' readonly=true value='".$row["solicitante_req"]."'/></td>
						<td align='right'>Fecha Requisici&oacute;n</td>
						<td><input name='txt_fecha' type='text' class='caja_de_texto' size='30' readonly=true value='".modFecha($row["fecha_req"],2)."'/></td>
					</tr>
		      		<tr>
        				<td align='right' valign='top'>Justificaci&oacute;n</td>
		       			<td><textarea name='txa_justificacion' class='caja_de_texto' rows='4' cols='30' readonly='readonly'>".$row['justificacion_tec']."</textarea>
						</td>
		        		<td align='right' valign='top'>Comentarios</td>";
					if ($row['comentario_compras']==''){
		     	    	?>"<td><textarea name='txa_comentarios' maxlength='120' onkeypress="return permite(event,'num_car', 0);" onkeyup='return ismaxlength(this)'
                        onclick="value='';" rows='5' cols='30' class='caja_de_texto'>Click aqui para Agregar Comentario</textarea></td>"<?php
					}
					else
						echo "<td><textarea name='txa_comentarios' maxlength='120' onkeyup='return ismaxlength(this)' rows='4' cols='30' class='caja_de_texto'>".$row[
						'comentario_compras']."</textarea></td>";
					echo "
						<td valign='top'>Estado</td>
						<td valign='top'>";
						if ($row["estado"]!="ENTREGADA" && $row["estado"]!="NO AUTORIZADA"){
							?>
							<input name="cmb_estado" id="cmb_estado" class="caja_de_texto" value="<?php echo $row['estado']; ?>" readonly="readonly" size="17"/>
							<?php
							/*echo "
								<select name='cmb_estado' id='cmb_estado'>";?>
									<option <?php if ($row["estado"]=="ENVIADA") echo "selected='selected' ";?>value="">Seleccionar</option>
									<option <?php if ($row["estado"]=="EN PROCESO") echo "selected='selected' ";?>value="EN PROCESO">EN PROCESO</option>
									<option <?php if ($row["estado"]=="COTIZANDO") echo "selected='selected' ";?>value="COTIZANDO">COTIZANDO</option>
									<option <?php if ($row["estado"]=="CANCELADA") echo "selected='selected' ";?>value="CANCELADA">CANCELADA</option>
									<option <?php if ($row["estado"]=="EN TRANSITO") echo "selected='selected' ";?>value="CANCELADA">EN TRANSITO</option>
									<option <?php if ($row["estado"]=="PEDIDO") echo "selected='selected' ";?>value="PEDIDO">PEDIDO</option>
									<option <?php if ($row["estado"]=="ENTREGADA") echo "selected='selected' ";?>value="ENTREGADA">ENTREGADA</option>
									<option <?php if ($row["estado"]=="AUTORIZADA") echo "selected='selected' ";?>value="AUTORIZADA">AUTORIZADA</option>
									<option <?php if ($row["estado"]=="NO AUTORIZADA") echo "selected='selected' ";?>value="NO AUTORIZADA">NO AUTORIZADA</option>
									<?php
							echo "</select>";*/
						}
						else{
							echo "<input type='text' class='caja_de_num' size='10' value='$row[estado]' id='cmb_estado".$row['id_requisicion']."' name='cmb_estado".$row['id_requisicion']."' readonly='readonly'/>";
						}
					echo "</td>";
      		echo "</tr>
    		</table>";
			return $row["estado"];
			}
	 }


	 function mostrarRequisiciones($departamento,$base){
	 	$filtro="";
		//Si esta definido el filtro y es diferente de vacio
		if(isset($_POST["txa_filtro"]) && $_POST["txa_filtro"]!=""){
			//Obtener el filtro
			$filtro=strtoupper($_POST["txa_filtro"]);
			//Obtener el tipo de Filtro
			$tipoFiltro=$_POST["cmb_filtro"];
		}
	 	if ($_GET["bus"]=="fecha"){
			$fechaI=modFecha($_POST["txt_fechaIni"],3);
			$fechaF=modFecha($_POST["txt_fechaFin"],3);
		}
		else{
			switch($_POST["cmb_estadoBuscar"]){
				case 1:
					$estado="ENVIADA";
				break;
				case 2:
					$estado="PEDIDO";
				break;
				case 3:
					$estado="CANCELADA";
				break;
				case 4:
					$estado="COTIZANDO";
				break;
				case 5:
					$estado="EN PROCESO";
				break;
				case 6:
					$estado="EN TRANSITO";
				break;
				case 7:
					$estado="ENTREGADA";
				break;
				case 8:
					$estado="AUTORIZADA";
				break;
				case 9:
					$estado="NO AUTORIZADA";
				break;
				case 10:
					$estado="";
				break;
			}
		}
		$ctrl=0;
		$conec=conecta($base);
		//Si la base de datos no existe, redirecciona a la p�gina de construccion
		if (!$conec){
			echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
		}
		else{
			echo "
				<p>
				<table cellpadding='5' cellspacing='5' class='tabla_frm' width='100%'>
					<tr>";
					if (!isset($estado))
						echo "<td colspan='7' align='center' class='titulo_etiqueta'>Requisiciones Publicadas por ".$departamento." entre el ".modFecha($fechaI,2)." y el ".modFecha($fechaF,2)."</td>";
					else{
						if ($estado!="")
							echo "<td colspan='7' align='center' class='titulo_etiqueta'>Requisiciones Publicadas por $departamento con el Estado $estado</td>";
						else
							echo "<td colspan='7' align='center' class='titulo_etiqueta'>Requisiciones Publicadas por $departamento</td>";
					}
			echo "
					</tr>
					<tr>
    		    		<td colspan='4'>&nbsp;</td>
	      			</tr>
					<tr align='center'>
						<td class='nombres_columnas'>N&Uacute;MERO</td>
	        			<td class='nombres_columnas'>FECHA DE PUBLICACI&Oacute;N</td>
						<td class='nombres_columnas'>ESTADO</td>
						<td class='nombres_columnas'>PRIORIDAD</td>
						<td class='nombres_columnas' align='center'>TIEMPO ENTREGA</td>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
      				</tr>";
			if (!isset($estado)){
				//Si el filtro es igual a NA, mostrar todas las requisiciones con los parametros indicados
				if($filtro=="")
					$stm_sql = "SELECT id_requisicion, fecha_req, hora, estado, prioridad, cant_entrega, tipo_entrega FROM requisiciones JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND autorizada = 1 AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
				else{
					//Verificar el tipo de filtro
					if($tipoFiltro=="descripcion")
						$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, requisiciones.estado, prioridad, requisiciones.cant_entrega, requisiciones.tipo_entrega FROM requisiciones 
									JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion JOIN bitacora_movimientos ON id_requisicion = id_operacion 
									WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND autorizada = 1 AND descripcion LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
					if($tipoFiltro=="aplicacion")
						$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, requisiciones.estado, prioridad, requisiciones.cant_entrega, requisiciones.tipo_entrega FROM requisiciones 
									JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion JOIN bitacora_movimientos ON id_requisicion = id_operacion 
									WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND autorizada = 1 AND aplicacion LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
					if($tipoFiltro=="justificacion_tec")
						$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, estado, prioridad, requisiciones.cant_entrega, requisiciones.tipo_entrega FROM requisiciones 
									JOIN bitacora_movimientos ON id_requisicion = id_operacion 
									WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND autorizada = 1 AND justificacion_tec LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
				}
			}
			else{
				//if ($estado!="")
					$stm_sql = "SELECT id_requisicion, fecha_req, hora, estado, prioridad, cant_entrega, tipo_entrega FROM requisiciones JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								 WHERE estado='$estado' AND autorizada = 1 AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
				/*else
					$stm_sql = "SELECT id_requisicion, fecha_req, hora, estado, prioridad, cant_entrega, tipo_entrega FROM requisiciones JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";*/
			}
			$rs=mysql_query($stm_sql);
			//Variable bandera inicializada en 0
			$bandera=0;
			if($row = mysql_fetch_array($rs)){
				if(isset($_POST["hdn_numero"])){
					?>
					<script type="text/javascript" language="javascript">
						setTimeout("focoReq('<?php echo $_POST["hdn_numero"]?>')",1000);
						function focoReq(combo){
							renglon="renglon"+combo;
							renglonOr="renglonOr"+combo;
							prioridad="prioridad"+combo;
							seleccion="seleccion"+combo;
							tEntrega="tEntrega"+combo;
							document.getElementById(renglon).style.background="#5682DD";
							document.getElementById(renglonOr).style.background="#5682DD";
							document.getElementById(prioridad).style.background="#5682DD";
							document.getElementById(seleccion).style.background="#5682DD";
							document.getElementById(tEntrega).style.background="#5682DD";
							document.getElementById("cmb_estado"+combo).focus();
							//setTimeout("document.getElementById(renglon).style.background=document.getElementById(renglonOr).style.background",3000);
						}
					</script>
					<?php
				}
				$cont=1;
				//Si existe por lo menos un registro la variable bandera toma el valor de 1
				$bandera=1;
				$nom_clase="renglon_gris";
				$seleccionado = "checked='checked'";
				do{
					if($cont>1)
						$seleccionado = "";
					$estado=$row["estado"];
					echo "<tr>";
					echo "
							<td class='nombres_filas' align='center'>".$row['id_requisicion']."</td>
							<td class='$nom_clase' align='center' id='renglonOr$row[id_requisicion]'>".modFecha($row['fecha_req'],2)." - ".modHora($row['hora'])."</td>
							<td class='$nom_clase' align='center' id='renglon$row[id_requisicion]'>";
						if ($estado!="ENTREGADA" && $estado!="NO AUTORIZADA"){
							echo "
								<select name='cmb_estado".$row['id_requisicion']."' id='cmb_estado".$row['id_requisicion']."'>";?>
									<option <?php if ($estado=="ENVIADA") echo "selected='selected' ";?>value="">Seleccionar</option>
									<option <?php if ($estado=="EN PROCESO") echo "selected='selected' ";?>value="EN PROCESO">EN PROCESO</option>
									<option <?php if ($estado=="COTIZANDO") echo "selected='selected' ";?>value="COTIZANDO">COTIZANDO</option>
									<option <?php if ($estado=="CANCELADA") echo "selected='selected' ";?>value="CANCELADA">CANCELADA</option>
									<option <?php if ($estado=="EN TRANSITO") echo "selected='selected' ";?>value="EN TRANSITO">EN TRANSITO</option>
									<option <?php if ($estado=="PEDIDO") echo "selected='selected' ";?>value="PEDIDO">PEDIDO</option>
									<option <?php if ($estado=="ENTREGADA") echo "selected='selected' ";?>value="ENTREGADA">ENTREGADA</option>
									<option <?php if ($estado=="AUTORIZADA") echo "selected='selected' ";?>value="AUTORIZADA">AUTORIZADA</option>
									<option <?php if ($estado=="NO AUTORIZADA") echo "selected='selected' ";?>value="NO AUTORIZADA">NO AUTORIZADA</option>
									<?php
							echo "</select>";
						}
						else{
							if($estado=="ENTREGADA" || $estado=="NO AUTORIZADA"){
								echo "<input type='text' class='caja_de_num' value='$estado' id='cmb_estado".$row['id_requisicion']."' name='cmb_estado".$row['id_requisicion']."' readonly='readonly'/>";
								//Esta variable ayuda a deshabilitar el boton de Generar Pedido y a guardar los comentarios que se le hagan a las requisiciones
								//echo "<input type='hidden' name='cmb_estado".$row['id_requisicion']."' value='ENTREGADA' />";
							}
						}
					echo"</td>
						<td class='$nom_clase' align='center' id='prioridad$row[id_requisicion]'>$row[prioridad]</td>";
						echo "<td class='$nom_clase' align='center' id='tEntrega$row[id_requisicion]'>";
						?>
							<input type="text" id="txt_cantidadEntr<?php echo $row['id_requisicion']; ?>" name="txt_cantidadEntr<?php echo $row['id_requisicion']; ?>" class="caja_de_num"
							onkeypress="return permite(event,'num', 2);" size="2" maxlength="3" value="<?php echo $row['cant_entrega']; ?>"/>
							<select name="txt_entregaTipo<?php echo $row['id_requisicion']; ?>" id="<?php echo $row['id_requisicion']; ?>" >
								<option <?php if ($row["tipo_entrega"]=="") echo "selected='selected' ";?>value="">Seleccionar</option>
								<option <?php if ($row["tipo_entrega"]=="DIAS") echo "selected='selected' ";?>value="DIAS">DIAS</option>
								<option <?php if ($row["tipo_entrega"]=="SEMANAS") echo "selected='selected' ";?>value="SEMANAS">SEMANAS</option>
								<option <?php if ($row["tipo_entrega"]=="MESES") echo "selected='selected' ";?>value="MESES">MESES</option>
							</select>
						<?php
					echo "</td>
							<td class='$nom_clase' align='center' id='seleccion$row[id_requisicion]'><input type='radio' name='rdb_req' id='rdb_req' value='".$row['id_requisicion']."' ".$seleccionado." ></td>";
					echo "</tr>";
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while ($row = mysql_fetch_array($rs));
				//ctrl vale 1 en caso de haber requisiciones
				$ctrl=1;
			}else{
				//En caso de no haber requisiciones, se muestra el siguiente mensaje
				echo "<tr><td colspan='5' align='center' class='msje_correcto'><br/>No existen Requisiciones del Departamento ".$departamento."</td>
					</tr>";?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('El Departamento <?php echo $departamento; ?> no tiene Requisiciones Publicadas con los Datos Proporcionados')",500);
				</script><?php
				//ctrl vale 0 en caso de haber requisiciones
				$ctrl=0;
			}
			echo "</table>
			</p>";
			//Retornar el valor que ctrl haya tomado
			return $ctrl;
		}
	}
	
	function obtenerCentroCosto($tabla,$busq,$valor){
		$dat = $valor; 
		$con = conecta("bd_recursos");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		return $dat;
		mysql_close($con);
	}
	
	function obtenerEstadoPartida($id_req,$bd,$partida){
		$valor = 0;
		$conn = conecta($bd);
		$stm_sql = "SELECT estado
					FROM detalle_requisicion
					WHERE requisiciones_id_requisicion LIKE  '$id_req'
					AND partida = '$partida'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$valor= $datos[0];
			}
		}
		return $valor;
	}
	
	function obtenerEstadoPrioritario($id_req,$bd){
		$estado = "1";
		$conn = conecta($bd);
		$stm_sql = "SELECT * , COUNT( * ) AS contador
					FROM detalle_requisicion
					WHERE requisiciones_id_requisicion LIKE  '$id_req'
					GROUP BY estado
					ORDER BY contador DESC 
					LIMIT 1";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$estado = $datos["estado"];
				$cant = $datos["cant_entrega"];
				$tipo = $datos["tipo_entrega"];
			}
		}
		if ($estado == 1)
			$estado = "ENVIADA";
		else if ($estado == 2)
			$estado = "PEDIDO";
		else if ($estado == 3)
			$estado = "CANCELADA";
		else if ($estado == 4)
			$estado = "COTIZANDO";
		else if ($estado == 5)
			$estado = "EN PROCESO";
		else if ($estado == 6)
			$estado = "EN TRANSITO";
		else if ($estado == 7)
			$estado = "ENTREGADA";
		else if ($estado == 8)
			$estado = "AUTORIZADA";
		else if ($estado == 9)
			$estado = "NO AUTORIZADA";
		
		return array($estado,$cant,$tipo);
	}
	
	function obtenerDatoTabla($tabla,$busq,$valor,$bd){
		$dat = $valor; 
		$con = conecta("$bd");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		//mysql_close($con);
		return $dat;
	}
?>
