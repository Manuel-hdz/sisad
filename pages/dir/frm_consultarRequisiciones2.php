<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Compras
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
	//Enviar a la pagina de acceso negado
		//echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que hace la consulta de las requisiciones publicadas por cada departamento
		include_once ("op_consultarRequisiciones2.php");
		include_once ("op_generarRequisicion.php");?>


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link href="includes/estiloGerencia.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" language="javascript">
		function habilitarFiltro(combo){
			if(combo!="NA")
				document.getElementById("txa_filtro").readOnly=false;
			else{
				document.getElementById("txa_filtro").value="";
				document.getElementById("txa_filtro").readOnly=true;
			}
		}
	</script>

    <style type="text/css">
		<!--
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		#requisiciones {position:absolute; left:30px; top:190px; width:900px; height:142px; z-index:12;}
		#titulo-requisicion {position:absolute;left:25px;top:146px;width:293px;height:22px;z-index:11;}
		#tabla-requisiciones {position:absolute;left:30px;top:190px;width:900px;height:180px;z-index:12;}
		#botones{position:absolute;left:30px;top:650px;width:900px;height:37px;z-index:13;}
		#tabla-resultados{position:absolute; left:30px; top:191px; width:940px; height:400px; z-index:15; overflow: scroll;}
		#detalle_Req{position:absolute;overflow:scroll;left:30px;top:400px;width:900px;height:200px;z-index:13;}
		#Msje{position:absolute; left:70px; top:260px; width:900px; height:100px; z-index:18;}
		#tabla-fechas { position:absolute; left:25px; top:190px; width:455px; height:170px; z-index:12; }
		#tabla-estado { position:absolute; left:530px; top:190px; width:420px; height:170px; z-index:12; }
		#calendar-uno { position:absolute; left:190px; top:224px; width:30px; height:26px; z-index:16; }
		#calendar-dos { position:absolute; left:405px; top:224px; width:30px; height:26px; z-index:17; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-requisicion">Requisiciones Departamentales</div><?php

	if (isset($_POST["btn_guardarC"])){
		$cveReq = $_POST["hdn_numero"];
		$comentario = strtoupper($_POST["txa_comentarios"]);
		$departamento = $_GET["depto"];
		$base = $_POST["hdn_bd"];
		//Llamamos a la funcion que almacena el comentario con la clave de requisicion, el comentario y el departamento de trabajo actual
		guardarComentario($cveReq,$comentario,$departamento,$base);
	}
	else if (isset($_POST["btn_guardarReg"])){
		guardarRequisicion2();
		?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="150" height="150"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div>
		<?php
	}
	//Variable que permitira mostrar la pantalla adecuada de las requisiciones, siempre y cuando se seleccione un estado para ellas
	$ctrl=0;
	//Esta comparaci�n se realiza para verificar que el departamento este declarado
	if (isset($_GET["depto"])){
/*		echo "<meta http-equiv='refresh' content='10;url=menu_requisiciones.php'>";
	}
	else{*/
		//Obtenemos el nombre del departamento
		switch ($_GET["depto"]){
			case "almacen":
				$departamento="ALMACEN";
				$base="bd_almacen";
				break;
			case "gerenciatecnica":
				$departamento="GERENCIA TECNICA";
				$base="bd_gerencia";
				break;
			case "recursoshumanos":
				$departamento="RECURSOS HUMANOS";
				$base="bd_recursos";
				break;
			case "produccion":
				$departamento="PRODUCCION";
				$base="bd_produccion";
				break;
			case "aseguramientodecalidad":
				$departamento="ASEGURAMIENTO DE CALIDAD";
				$base="bd_aseguramiento";
				break;
			case "desarrollo":
				$departamento="DESARROLLO";
				$base="bd_desarrollo";
				break;
			case "mantenimiento":
				$departamento="MANTENIMIENTO";
				$base="bd_mantenimiento";
				break;
			case "topografia":
				$departamento="TOPOGRAFIA";
				$base="bd_topografia";
				break;
			case "laboratorio":
				$departamento="LABORATORIO";
				$base="bd_laboratorio";
				break;
			case "seguridadindustrial":
				$departamento="SEGURIDAD INDUSTRIAL";
				$base="bd_seguridad";
				break;
			case "paileria":
				$departamento="PAILERIA";
				$base="bd_paileria";
				break;
			case "mttoElectrico":
				$departamento="MANTENIMIENTO ELECTRICO";
				$base="bd_mantenimientoE";
				break;
			case "clinica":
				$departamento="UNIDAD DE SALUD OCUPACIONAL";
				$base="bd_clinica";
				break;
		}//Cierre switch ($_GET["depto"])


		//Si en el arreglo POST existe btn_registrar, se obtiene del radiobutton la clave de la requisicion
		if (isset($_POST["btn_registrar"])){
			asignarEstado($departamento,$base);
			//Esta variable toma el valor de 1, siempre y cuando se haya asignado un estado a la requisicion
			$ctrl=1;
		}


		//Si en el POST se detecta btn_revisar, se envia a la pantalla la requisicion con los datos a Revisarse
		if (isset($_POST["btn_revisar"]) || isset($_POST["btn_guardarC"]) || isset($_POST["origen"])){
			$cve_req=$_POST["rdb_req"];
			//Definimos el FORM que actuara para los botontes declarados mas delante
			echo "<form method='post' name='frm_detallesRequisicion'>";
			echo "<fieldset id='tabla-requisiciones' class='borde_seccion' style='height:200px'>
				<legend class='titulo_etiqueta'>Requisici&oacute;n ".$_POST["rdb_req"]."</legend>";
				//Se ejecuta la funcion que muestra el detalle de las requisiciones del departamento
				$estado=mostrarRequisicionDetalle($departamento,$base);
			echo "</fieldset>";?>

			<div id='botones' align="center" style="top:670px">
				<input type="hidden" name="rdb_req" value="<?php echo $_POST["rdb_req"];?>"/>
				<!-- <input name="btn_guardarReg" type="submit" id="btn_guardarReg" class="botones" value="Guardar Requisici&oacute;n"
				onMouseOver="window.status='';return true" style="width:150px"/>
				&nbsp;&nbsp;&nbsp;&nbsp; -->
				<input name="btn_guardarC" type="submit" id="btn_guardarC" class="botones" value="Guardar Comentarios" style="width:150px"
				onMouseOver="window.status='';return true" title="Guardar Comentario y Estado para la Requisici&oacute;n <?php echo $_POST["rdb_req"];?>"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" title="Ver Archivo PDF de la Requisici�n Seleccionada" onmouseover="window.status='';return true"
				onclick="window.open('../../includes/generadorPDF/requisicion.php?id=<?php echo $rdb_req; ?>','_blank',
				'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no,location=no, directories=no')" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Regresar a las Requisiciones Publicadas" onMouseOver="window.status='';return true"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
                                <!-- aqui ira el boton para generar la requisicion de otras areas como req de almacen
                                
                                -->
                                
                                
                                <!--<input name="sbt_generar" type="submit" class="botones"
				onclick="document.frm_detallesRequisicion.action='frm_detallesDelPedido.php?depto=<?php //echo $base;?>&origen=req&deptto=<?php //echo $_GET["depto"]?>';document.frm_detallesRequisicion.submit();"
				id="sbt_generar" value="Registrar Pedido" onMouseOver="window.status='';return true"<?php/*

					//Revisar el Estado de la Requisicion seleccionada para deshabilitar el boton de registrar pedido cuando los estados sean PEDIDO o CANCELADA
					if($estado=="CANCELADA"){
						echo " disabled='disabled' title='No se Puede Registrar un Pedido de una Requisici&oacute;n Cancelada'";
					}
					else if($estado=="PEDIDO" || $estado=="ENTREGADA"){
						echo " disabled='disabled' title='Ya se Registr&oacute; un Pedido de La Requisici&oacute;n $rdb_req'";
					}
					else{
						echo " title='Registrar Pedido a partir de la Requisici&oacute;n'";
					}*/?>
				/>-->
			</div>
			<?php

			echo "<div id='detalle_Req' class='borde_seccion2' style='top:415px'>";
				//Se ejecuta la funcion que muestra el detalle de las requisiciones del departamento
				dibujarDetalle($rdb_req,$departamento,$base);
			echo "</div></form>";
		}//Fin del IF que revisa que se haya seleccionado una requisicion
		else if(!isset($_POST["btn_guardarReg"])){
			if (isset($_POST["sbt_consultarReq"]) || $ctrl==1 || isset($_POST["sbt_regresar"])){
				//Definimos el FORM que actuara para los botontes declarados mas delante
				echo "<form name='frm_consultarRequisiciones2' method='post' action=''>";
				echo "<div id='tabla-resultados' class='borde_seccion2'>";
					//Se ejecuta la funcion que muestra las requisiciones del departamento
					$ctrl = mostrarRequisiciones($departamento,$base);
				echo "</div>";?>
				<div id='botones' align="center"><?php
				//Verificar el valor de ctrl para mostrar u ocultar los botones de detalles
				if ($ctrl==1) {
					//Si el valor de bus es igual a fecha escribir los hidden con el valor de las fechas enviadas por POST
					if ($_GET["bus"]=="fecha"){?>
						<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST["txt_fechaIni"];?>"/>
						<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST["txt_fechaFin"];?>"/><?php
						if(isset($_POST["txa_filtro"]) && $_POST["txa_filtro"]!=""){
							?>
							<input type="hidden" name="txa_filtro" value="<?php echo $_POST["txa_filtro"]?>"/>
							<input type="hidden" name="cmb_filtro" value="<?php echo $_POST["cmb_filtro"]?>"/>
							<?php
						}
					}
					else{?>
						<input type="hidden" name="cmb_estadoBuscar" value="<?php echo $_POST["cmb_estadoBuscar"];?>"/><?php
					}?>
					<input type="hidden" name="bus" value="<?php echo $_GET["bus"]?>" />
					<input type='submit' name='btn_registrar' id='btn_registrar' class='botones' on value='Autorizar Requisiciones'
					title="Guardar Autorizaci&oacute;n de la Requisici&oacute;n" onMouseOver="window.status='';return true" style="width:170px"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type='submit' name='btn_revisar' id='btn_revisar' class='botones' value='Revisar Detalles'
					title="Revisar Detalle de la Requisici&oacute;n" onMouseOver="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php
				}?>
				<input type='button' onclick="location.href='frm_consultarRequisiciones2.php?depto=<?php echo $_GET["depto"];?>'" class='botones' value='Regresar'
				title="Regresar al Men&uacute; de Requisiciones" />
				</div>
				</form><?php
			}//Fin del IF que comprueba btn_consultar en el POST
			else{ 
				if(isset($_SESSION["activos"]))
					unset($_SESSION["activos"]);?>
				<fieldset id="tabla-fechas" class="borde_seccion">
				<legend class="titulo_etiqueta">Seleccionar Fechas</legend>
				<form name="frm_buscarRequisiciones" action="frm_consultarRequisiciones2.php?depto=<?php echo $_GET["depto"];?>&bus=fecha" method="post" onsubmit="return valFormReqFecha(this);">
				<table width="102%" class="tabla_frm" cellpadding="5" cellspacing="5">
					<tr>
					  <td width="12%"><div align="right">Fecha Inicio</div></td>
					  <td width="27%"><input type="text" size="10" name="txt_fechaIni" class="caja_de_texto" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" readonly="readonly"/></td>
					  <td width="18%"><div align="right">Fecha Fin</div></td>
					  <td width="43%"><input type="text" size="10" name="txt_fechaFin" class="caja_de_texto" value="<?php echo date("d/m/Y"); ?>" readonly="readonly"/></td>
					</tr>
					<tr>
					  <td width="12%" valign="top"><div align="right">Filtro</div></td>
						<td width="27%" valign="top">
							<select name="cmb_filtro" onchange="habilitarFiltro(this.value);">
								<option value="NA">Filtro</option>
								<option value="descripcion">MATERIAL</option>
								<option value="aplicacion">APLICACI&Oacute;N</option>
								<option value="justificacion_tec">JUSTIFICACI&Oacute;N</option>
							</select>
					  </td>
						<td valign="top"><div align="right">Concepto</div></td>
						<td valign="top">
							<textarea name='txa_filtro' id="txa_filtro" maxlength='120' onkeypress="return permite(event,'num_car', 0);" onkeyup='return ismaxlength(this)'
                        	onclick="value='';" rows='3' cols='30' class='caja_de_texto' readonly="readonly"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="4" align="center">
						<input type="submit" class="botones" name="sbt_consultarReq" value="Consultar" title="Consultar Requisiciones entre las Fechas proporcionadas" onmouseover="window.status='';return true;"/>
						&nbsp;&nbsp;&nbsp;
						<input type="reset" name="btn_reset" value="Reestablecer" title="Reestablecer el Formulario" class="botones" onclick="txa_filtro.readOnly=true"/>
						&nbsp;&nbsp;&nbsp;
						<input type="button" class="botones" name="btn_regresar" value="Cancelar" title="Regresar al Men&uacute; de Requisiciones" onmouseover="window.status='';return true;"
						onclick="location.href='menu_requisiciones2.php'"/>
					</td>
					</tr>
				</table>
				</form>
				</fieldset>

				<!-- <fieldset id="tabla-estado" class="borde_seccion">
				<legend class="titulo_etiqueta">Seleccionar por Estado</legend>
				<form name="frm_buscarRequisiciones2" action="frm_consultarRequisiciones2.php?depto=<?php echo $_GET["depto"];?>&bus=combo" method="post" onsubmit="return valFormReqEdo(this);">
				<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
					<tr>
						<td width="20%">Estado</td>
						<td>
							<select name="cmb_estadoBuscar" class="combo_box">
								<option value="">Seleccionar</option>
								<option value="1">SIN ESTADO</option>
								<option value="2">EN PROCESO</option>
								<option value="3">COTIZANDO</option>
								<option value="4">CANCELADA</option>
								<option value="5">ENVIADA</option>
								<option value="6">ENTREGADA</option>
								<option value="7">TODOS</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
						<input type="submit" class="botones" name="sbt_consultarReq" value="Consultar" title="Consultar Requisiciones entre las Fechas proporcionadas" onmouseover="window.status='';return true;"/>
						&nbsp;&nbsp;&nbsp;
						<input type="reset" name="btn_reset" value="Reestablecer" title="Reestablecer el Formulario" class="botones"/>
						&nbsp;&nbsp;&nbsp;
						<input type="button" class="botones" name="btn_regresar" value="Cancelar" title="Regresar al Men&uacute; de Requisiciones" onmouseover="window.status='';return true;"
						onclick="location.href='menu_requisiciones.php'"/>
						</td>
					</tr>
				</table>
				</form>
				</fieldset> -->

				<div id="calendar-uno">
					<input type="image" name="iniRepClientes" id="iniRepClientes" src="../../images/calendar.png"
					onclick="displayCalendar(document.frm_buscarRequisiciones.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
					width="25" height="25" border="0" align="absbottom" />
				</div>
				<div id="calendar-dos">
					<input type="image" name="finRepClientes" id="finRepClientes" src="../../images/calendar.png"
					onclick="displayCalendar(document.frm_buscarRequisiciones.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
					width="25" height="25" border="0" align="absbottom" />
				</div><?php
			}
		}
	}//Fin del else que verifica que este definido el departamento?>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>