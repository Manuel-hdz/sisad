<?php
	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 19/Noviembre/2010
	  * Descripción: Este archivo contiene funciones para Mostrar el detalle de requisiciones por cada departamento
	  **/


	 //Mostrar el detalle de los materiales de acuerdo a los parametros seleccionados
	 function dibujarDetalle($clave_req,$departamento,$base){
	 	//Realizar la conexion a la BD de Almacen
		if($departamento == "sabinas")
			$conec=conecta_sabinas($base);
		else
			$conec=conecta($base);

		//Crear sentencia SQL
		$stm_sql="SELECT cant_req, unidad_medida, descripcion, aplicacion, materiales_id_material FROM detalle_requisicion WHERE requisiciones_id_requisicion='".$clave_req."'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "
				<table cellpadding='5' width='100%'>
				<tr>
			<td colspan='16' align='center' class='titulo_etiqueta'>Detalles de la Requisici&oacute;n ".$clave_req."</td>
  					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
        				<td class='nombres_columnas' align='center'>UNIDAD</td>
				        <td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
        				<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				?>
				<tr>
					<input style="text-align:center" name="txt_id_material<?php echo $cont; ?>" id="txt_id_material<?php echo $cont; ?>" type="hidden" class="caja_de_texto" onkeypress="return permite(event,'num');" size='10' value="<?php echo $datos["materiales_id_material"]; ?>"/>
					
					<td class='nombres_filas' align='center'>
						<input style="text-align:center" name="txt_cantidad<?php echo $cont; ?>" id="txt_cantidad<?php echo $cont; ?>" type="text" class="caja_de_texto" onkeypress="return permite(event,'num');" size='10' value="<?php echo $datos["cant_req"]; ?>"/>
					</td>
					<td class='$nom_clase' align='center'>
						<input style="text-align:center" name="txt_unidad<?php echo $cont; ?>" id="txt_unidad<?php echo $cont; ?>" type="text" class="caja_de_texto" size='20' maxlength="20" value="<?php echo $datos["unidad_medida"]; ?>"/>
					</td>
					<td class='$nom_clase' align='center'>
						<input style="text-align:center" name="txt_descripcion<?php echo $cont; ?>" id="txt_descripcion<?php echo $cont; ?>" type="text" class="caja_de_texto" size='40' maxlength="60" value="<?php echo htmlentities($datos["descripcion"]); ?>"/>
					</td>
					<td class='$nom_clase' align='center'>
						<input style="text-align:center" name="txt_aplicacion<?php echo $cont; ?>" id="txt_aplicacion<?php echo $cont; ?>" type="text" class="caja_de_texto" size='40' maxlength="60" value="<?php echo $datos["aplicacion"]; ?>"/>
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
			echo "<input type='hidden' name='hdn_numReg' id='hdn_numReg' value='$cont'>";
			echo "</table>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conec);
	 }//Fin de la funcion dibujarDetalle($requisicion,$departamento)

	//Esta funcion se encarga de guardar el comentario escrito para las requisiciones, además del estado asignado
	 function guardarComentario($idReq,$comentario,$departamento,$base,$estado){
	 	$comentario=strtoupper($comentario);
		 //Realizar la conexion a la BD de Almacen
		if($departamento == "sabinas")
			$conec=conecta_sabinas($base);
		else
			$conec=conecta($base);
		//Crear sentencia SQL
		if(($estado!="" && $estado!="AUTORIZADA"))
			$stm_sql="UPDATE requisiciones SET comentario_compras='$comentario',estado='$estado' WHERE id_requisicion='$idReq'";
		//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
		if ($rs){
			/*?>
			<script type="text/javascript" language="javascript">
			setTimeout("mensajeOk()",1000);

			function mensajeOk(){
				alert("Requisición <?php echo $idReq;?> Modificada Correctamente");
			}
			</script>
			<?php*/
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
			setTimeout("mensajeMal()",1000);

			function mensajeMal(){
				alert("La Requisición <?php echo $idReq;?> No se Pudo Modificar");
			}
			</script>
			<?php
		}
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
		if ($estado!="" && $estado!="AUTORIZADA"){
			//Se crea la conexion con la BD del departamento seleccionado, se quitan los espacios en blanco para poder conectarse
			if($departamento == "sabinas")
				$conec=conecta_sabinas($base);
			else
				$conec=conecta($base);
			//Si la base de datos no existe, redirecciona a la página de construccion, esta comparación no debe realizarse, pero se deja en caso extremo
			if (!$conec){
				echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
			}
			else{
				$stm_sql="UPDATE requisiciones SET estado='$estado' WHERE id_requisicion='$cve_req'";
				$rs=mysql_query($stm_sql);
				registrarOperacion("bd_compras",$cve_req,"CambiaEstadoRequisicion",$_SESSION['usr_reg']);
			}
			echo "<div id='Msje' class='msje_correcto' align='center'>*Estado ".$estado." asignado a la Requisici&oacute;n ".$cve_req."</div>";
		}else
			echo "<div id='Msje' class='msje_incorrecto' align='center'>*Requisici&oacute;n ".$cve_req." seleccionada, Seleccionar Estado para ser Asignado</div>";
	 }

	 function mostrarRequisicionDetalle($departamento,$base){
	 	//Verificar si hay Filtros de por medio, para agregarlos como Hidden y no perderlos
		if(isset($_POST["txa_filtro"]) && $_POST["txa_filtro"]!=""){
			?>
			<input type="hidden" name="txa_filtro" value="<?php echo $_POST["txa_filtro"]?>"/>
			<input type="hidden" name="cmb_filtro" value="<?php echo $_POST["cmb_filtro"]?>"/>
			<?php
		}
	 	$cve_req=$_POST["rdb_req"];
		//Conexion a la BD de requisiciones del departamento para ejecutar la obtención de datos de la Requisición seleccionada y que llega en el POST
		if($departamento == "sabinas")
			$conec=conecta_sabinas($base);
		else
			$conec=conecta($base);
		//Si la base de datos no existe, redirecciona a la página de construccion
			if (!$conec){
				echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
			}
			else{
				$cve_req=$_POST["rdb_req"];
				//Sentencia SQL con los datos de la requisicion a buscar
				$stm_sql="SELECT area_solicitante, solicitante_req, id_requisicion, fecha_req, justificacion_tec, comentario_compras, estado FROM requisiciones WHERE
				id_requisicion='$cve_req'";
				$rs=mysql_query($stm_sql);
				$row = mysql_fetch_array($rs);
				if ($_GET["bus"]=="fecha"){
					echo "<input type='hidden' name='txt_fechaIni' value='".$_POST["txt_fechaIni"]."'/>";
					echo "<input type='hidden' name='txt_fechaFin' value='".$_POST["txt_fechaFin"]."'/>";
				}
				else
					echo "<input type='hidden' name='cmb_estadoBuscar' value='".$_POST["cmb_estadoBuscar"]."'/>";
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
						if ($row["estado"]!="ENTREGADA"){
							echo "
								<select name='cmb_estado' id='cmb_estado'>";?>
									<option <?php if ($row["estado"]=="ENVIADA") echo "selected='selected' ";?>value="">Seleccionar</option>
									<option <?php if ($row["estado"]=="EN PROCESO") echo "selected='selected' ";?>value="EN PROCESO">EN PROCESO</option>
									<option <?php if ($row["estado"]=="COTIZANDO") echo "selected='selected' ";?>value="COTIZANDO">COTIZANDO</option>
									<option <?php if ($row["estado"]=="CANCELADA") echo "selected='selected' ";?>value="CANCELADA">CANCELADA</option>
									<option <?php if ($row["estado"]=="ENVIADA") echo "selected='selected' ";?>value="ENVIADA">ENVIADA</option>
									<option <?php if ($row["estado"]=="AUTORIZADA") echo "selected='selected' ";?>value="AUTORIZADA">AUTORIZADA</option>
									<option value="ENTREGADA">ENTREGADA</option>
									<?php
							echo "</select>";
						}
						else{
							echo "<input type='text' class='caja_de_num' value='ENTREGADA' id='cmb_estado".$row['id_requisicion']."' name='cmb_estado".$row['id_requisicion']."' readonly='readonly'/>";
						}
					echo "</td>";
					echo "<input type='hidden' name='hdn_prioridad' id='hdn_prioridad' value='$_POST[hdn_prioridad]'/>";
      		echo "</tr>";
			$elaborador=obtenerDato("bd_usuarios","credenciales","nombre","usuarios_usuario",$_SESSION["usr_reg"]);
			?>
			<td><div align="right">Elabor&oacute;</div></td>
		    <td>
				<input name="txt_elaboradorReq" type="text" id="txt_elaboradorReq" onkeypress="return permite(event,'num_car');" size="40" maxlength="60" value="<?php echo $elaborador;?>"/>
			</td>
    		</table>
			<?php
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
					$estado="EN PROCESO";
				break;
				case 3:
					$estado="COTIZANDO";
				break;
				case 4:
					$estado="CANCELADA";
				break;
				case 5:
					$estado="ENVIADA";
				break;
				case 6:
					$estado="ENTREGADA";
				break;
				case 7:
					$estado="";
				break;
			}
		}
		$ctrl=0;
		if($departamento == "sabinas")
			$conec=conecta_sabinas($base);
		else
			$conec=conecta($base);
		//Si la base de datos no existe, redirecciona a la página de construccion
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
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
      				</tr>";
			if (!isset($estado)){
				//Si el filtro es igual a NA, mostrar todas las requisiciones con los parametros indicados
				if($filtro=="")
					$stm_sql = "SELECT id_requisicion, fecha_req, hora, estado, prioridad FROM requisiciones JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
				else{
					//Verificar el tipo de filtro
					if($tipoFiltro=="descripcion")
						$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, requisiciones.estado, prioridad FROM requisiciones 
									JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion JOIN bitacora_movimientos ON id_requisicion = id_operacion 
									WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND descripcion LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
					if($tipoFiltro=="aplicacion")
						$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, requisiciones.estado, prioridad FROM requisiciones 
									JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion JOIN bitacora_movimientos ON id_requisicion = id_operacion 
									WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND aplicacion LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
					if($tipoFiltro=="justificacion_tec")
						$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, estado, prioridad FROM requisiciones 
									JOIN bitacora_movimientos ON id_requisicion = id_operacion 
									WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND justificacion_tec LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
				}
			}
			else{
				if ($estado!="")
					$stm_sql = "SELECT id_requisicion, fecha_req, hora, estado, prioridad FROM requisiciones JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								 WHERE estado='$estado' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
				else
					$stm_sql = "SELECT id_requisicion, fecha_req, hora, estado, prioridad FROM requisiciones JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' ORDER BY id_requisicion";
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
							document.getElementById(renglon).style.background="#5682DD";
							document.getElementById("cmb_estado"+combo).focus();
							setTimeout("document.getElementById(renglon).style.background=document.getElementById(renglonOr).style.background",3000);
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
					echo "<td class='nombres_filas' align='center'>".$row['id_requisicion']."</td>
							<td class='$nom_clase' align='center' id='renglonOr$row[id_requisicion]'>".modFecha($row['fecha_req'],2)." - ".modHora($row['hora'])."</td>
							<td class='$nom_clase' align='center' id='renglon$row[id_requisicion]'>";
						if ($estado!="ENTREGADA"){
							echo "
								<select name='cmb_estado".$row['id_requisicion']."' id='cmb_estado".$row['id_requisicion']."'>";?>
									<option <?php if ($estado=="ENVIADA") echo "selected='selected' ";?>value="">Seleccionar</option>
									<option <?php if ($estado=="EN PROCESO") echo "selected='selected' ";?>value="EN PROCESO">EN PROCESO</option>
									<option <?php if ($estado=="COTIZANDO") echo "selected='selected' ";?>value="COTIZANDO">COTIZANDO</option>
									<option <?php if ($estado=="CANCELADA") echo "selected='selected' ";?>value="CANCELADA">CANCELADA</option>
									<option <?php if ($estado=="ENVIADA") echo "selected='selected' ";?>value="ENVIADA">ENVIADA</option>
									<option <?php if ($estado=="ENTREGADA") echo "selected='selected' ";?>value="ENTREGADA">ENTREGADA</option>
									<option <?php if ($estado=="AUTORIZADA") echo "selected='selected' ";?>value="AUTORIZADA">AUTORIZADA</option>
									<?php
							echo "</select>";
						}
						else{
							if($estado=="ENTREGADA"){
								echo "<input type='text' class='caja_de_num' value='ENTREGADA' id='cmb_estado".$row['id_requisicion']."' name='cmb_estado".$row['id_requisicion']."' readonly='readonly'/>";
								//Esta variable ayuda a deshabilitar el boton de Generar Pedido y a guardar los comentarios que se le hagan a las requisiciones
								//echo "<input type='hidden' name='cmb_estado".$row['id_requisicion']."' value='ENTREGADA' />";
							}
						}
					echo"</td>
						<input type='hidden' name='hdn_prioridad' id='hdn_prioridad' value='$row[prioridad]'/>
						<td class='$nom_clase' align='center'>$row[prioridad]</td>
						<td class='$nom_clase' align='center'><input type='radio' name='rdb_req' id='rdb_req' value='".$row['id_requisicion']."' ".$seleccionado." ></td>";
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
?>
