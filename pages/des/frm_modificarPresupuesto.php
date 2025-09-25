<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarPresupuesto.php");?>
	
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
    <script type="text/javascript" src="includes/ajax/verificarRangoFechas.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>   
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#tabla-modificarPresupuesto {position:absolute;left:30px;top:190px;width:880px;height:341px;z-index:14;}
		#tabla-busqPresupuesto {position:absolute;left:30px;top:190px;width:555px;height:120px;z-index:14;}
		#calendario-Ini {position:absolute;left:315px;top:232px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:315px;top:270px;width:30px;height:26px;z-index:14;}
		#presupuestosReg {position:absolute;left:32px;top:344px;width:925px;height:310px;z-index:12;overflow:scroll;}
		-->
    </style>
</head>
<body><?php
	if(isset($_POST['sbt_guardarMod']))
		guardarModPresupuesto();
	if(!isset($_POST['ckb_idPresupuesto'])){?>
	
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-modificar">Modificar Avance Presupuestal</div>		
        <fieldset class="borde_seccion" id="tabla-busqPresupuesto" name="tabla-busqPresupuesto">
		<legend class="titulo_etiqueta">Seleccionar Periodo</legend>	
		<br>
		<form onSubmit="return valFormBusqPresupuesto(this);" name="frm_modificarPresupuesto" method="post" action="frm_modificarPresupuesto.php">
			<table cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">Clientes</div></td>
					<td width="57"><?php
						$cmb_cliente="";
						$conn = conecta("bd_desarrollo");
						$result=mysql_query("SELECT id_cliente, nom_cliente FROM catalogo_clientes ORDER BY id_cliente");?>
						<select name="cmb_cliente" id="cmb_cliente" size="1" class="combo_box" 	
						onchange="cargarComboOrdenado(this.value,'bd_desarrollo','presupuesto','periodo','catalogo_clientes_id_cliente','cmb_periodo','Periodo','','fecha_fin')">				
							<option value="">Clientes</option><?php
								 while ($row=mysql_fetch_array($result)){
									if ($row['id_cliente'] == $cmb_cliente){
										echo "<option value='$row[id_cliente]' selected='selected'>$row[nom_cliente]</option>";
									}
									else{
										echo "<option value='$row[id_cliente]'>$row[nom_cliente]</option>";
									}
								}
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
						</select>
					</td>
					<td><div align="right">Periodo</div></td>
					<td width="147">
						<select name="cmb_periodo" id="cmb_periodo" class="combo_box">
							<option value="">Seleccione</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div align="center">
							<input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar"  value="Continuar" title="Continuar"
							 onmouseover="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute;  Presupuesto" 
							onMouseOver="window.status='';return true" onclick="location.href='menu_presupuesto.php';" />
						</div>          
					</td>
				</tr>
		</table>
		</form>
</fieldset>
	

		<?php 
		//Si viene el botonn sbt_regresar, cuando el usuario se encuentre dentro del formulario de modificar, no mastrara el div que contiene los resultados
		 if(!isset($_POST['sbt_regresar'])){	
			//Si viene en el post cmb_periodo desplegar la tabla de resultados
			if(isset($_POST['cmb_periodo']) && isset($_POST['cmb_cliente'])){?>
				<form name="frm_seleccionarPresupuesto" method="post">
					<div id='presupuestosReg' class='borde_seccion2'><?php
						mostrarPresupuestos();?>
					</div>
				</form><?php
			}
		}//FIN 	if(!isset($_POST['sbt_regresar'])){
	}//FIN 	if(!isset($_POST['ckb_idPresupuesto']))
	
	if(isset($_POST['ckb_idPresupuesto'])){
		//Relizar la consulta con el id del presupuesto seleccionad para poder precargar los datos 
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM presupuesto JOIN catalogo_clientes ON  id_cliente=catalogo_clientes_id_cliente WHERE id_presupuesto= '$_POST[ckb_idPresupuesto]'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);?>
    
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-modificar">Modificar Avance Presupuestado</div>		
		<fieldset class="borde_seccion" id="tabla-modificarPresupuesto" name="tabla-modificarPresupuesto">
		<legend class="titulo_etiqueta">Ingresar Datos del Presupuesto Mensual</legend>	
		<br>
		<form onSubmit="return valFormModPresupuesto(this);" name="frm_modificarPresupuesto" method="post" action="frm_modificarPresupuesto.php" enctype="multipart/form-data">
			<table width="860"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="147"><div align="right">Fecha Inicio</div></td>
					<td width="229">
						<input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10"
						value="<?php echo modFecha($datos['fecha_inicio'],1) ?>" 
						onchange="sumarDiasMes(); calcularDomingos();
						verificarRangoValido(this.value,txt_fechaFin.value,cmb_obra.value);"
						readonly="readonly"/>					</td>
					<td width="134"><div align="right">D&iacute;as Laborables</div></td>
					<td width="243">
						<input name="txt_diasLaborales" type="text" class="caja_de_texto" 
						id="txt_diasLaborales" onchange="calcularPptoDiario(); formatCero();" value="<?php echo $datos['dias_habiles'] ?>" size="3" maxlength="3" 
						onkeypress="return permite(event,'num',3);"/>					
					</td>
				</tr>     
				<tr>
					<td><div align="right">Fecha Fin</div></td>
					<td>
						<input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo modFecha($datos['fecha_fin'],1) ?>" 
						readonly="readonly" onchange="calcularDomingos(); verificarRangoValido(txt_fechaIni.value,this.value,cmb_obra.value);"/>					</td>
					<td><div align="right">Domingos</div></td>
					<td>
						<input name="txt_domingos" type="text" class="caja_de_texto" id="txt_domingos" value="<?php echo $datos['dias_inhabiles'] ?>" size="3" maxlength="3" 
						readonly="readonly" onkeypress="return permite(event,'num',3);"/>					
					</td>        
				</tr> 
				<tr>
					<td><div align="right">*Clientes</div></td>
					<td><?php 
						$idCliente=obtenerDato("bd_desarrollo","catalogo_clientes","nom_cliente","nom_cliente",$datos['nom_cliente']);
						$result=cargarComboOrdenado("cmb_cliente","nom_cliente","catalogo_clientes","bd_desarrollo","Seleccionar",$idCliente,"id_cliente"); 
							if($result==0) {
								echo "<label class='msje_correcto'>No hay Cliente Registrados</label>
								<input type='hidden' name='cmb_cliente' id='cmb_cliente'/>";
							}
						?>
					</td>				
					<td><div align="right">
						<input type="checkbox" name="ckb_nuevoCliente" id="ckb_nuevoCliente"
						 onclick="agregarNuevoCliente(this, 'txt_nuevoCliente', 'cmb_cliente');"/>Agregar Cliente</div>					 
					</td>
					<td>
						<input name="txt_nuevoCliente" id="txt_nuevoCliente" type="text" class="caja_de_texto" size="40" 
						readonly="readonly" onkeypress="return permite(event,'num',2);"/>					
					</td>
				</tr>
				<tr>
					<td><div align="right">*Mts. Presupuestados</div></td>
					<td>
						<input type="text" name="txt_mtsPresupuestados" id="txt_mtsPresupuestados" maxlength="10" size="8" class="caja_de_texto" 
						onkeypress="return permite(event,'num',2);" value="<?php echo $datos['mts_mes'] ?>" 
						onchange="formatCurrency(this.value,'txt_mtsPresupuestados');calcularPptoDiario(); " />						
						m					
					</td>
					<td><div align="right">*Mts. Presupuestados Diario</div></td>
					<td>
						<input type="text" name="txt_mtsPresupuestadosDiarios" id="txt_mtsPresupuestadosDiarios" 
						value="<?php echo $datos['mts_mes_dia'] ?>" maxlength="10" size="8" class="caja_de_texto"
						onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_mtsPresupuestadosDiarios');calcularPptoDiario();"
						readonly="readonly"/>
						m					
					</td>
				</tr>
				<tr>
					<td><div align="right">*Mts. Quincena 1</div></td>
					<td>
						<input name="txt_mtsQuincena1" type="text" class="caja_de_texto" id="txt_mtsQuincena1" 
						onchange="formatCurrency(this.value,'txt_mtsQuincena1');" 
						onkeypress="return permite(event,'num',2);" value="<?php echo $datos['mts_quincena1'] ?>" size="8" maxlength="10" />
						m
					</td>
					<td><div align="right">*Mts. Quincena 2</div></td>
					<td>
						<input type="text" name="txt_mtsQuincena2" id="txt_mtsQuincena2" value="<?php echo $datos['mts_quincena2'] ?>" maxlength="10" 
						size="8" class="caja_de_texto"
						onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_mtsQuincena2');"/>
						m					
					</td>
				</tr>
				<tr>
					<td><div align="right">*Disparos por D&iacute;a</div></td>
					<td>
						<input type="text" name="txt_disparosDia" id="txt_disparosDia" maxlength="10" size="8" class="caja_de_texto" 
						onkeypress="return permite(event,'num',3);" value="<?php echo $datos['disparos_dia'] ?>" 
						onchange="formatCurrency(this.value,'txt_disparosDia');" />            </td>
					<td><div align="right">*Disparos por Turno</div></td>
					<td>
						<input type="text" name="txt_disparosTurno" id="txt_disparosTurno" value="<?php echo $datos['disparos_turno'] ?>" 
						maxlength="10" size="8" class="caja_de_texto"
						onkeypress="return permite(event,'num',3);" onchange="formatCurrency(this.value,'txt_disparosTurno');"/>					
					</td>
				</tr>
				<tr>
					<td colspan="6"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
					<tr>
					<td colspan="6"><div align="center">
						<input type='hidden' name='cmb_periodo' value="<?php echo $_POST['cmb_periodo']?>"/>
						<input type="hidden" name="hdn_fechas" id="hdn_fechas" value="0"/>					
						<input type="hidden" name="hdn_band" id="hdn_band" value="si"/>
						<input type="hidden" name="hdn_claveDefinida" id="hdn_claveDefinida" value="<?php echo $datos['id_presupuesto']?>"/>
						<input name="sbt_guardarMod" type="submit" class="botones" id="sbt_guardarMod"  value="Guardar" 
						title="Guardar Modificación del Presupuesto Mensual"  onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Formulario" 
						onMouseOver="window.status='';return true" onclick="restablecePresupuesto();" /> 
						&nbsp;&nbsp;&nbsp;
							
						<input name="sbt_regresar" type="submit" class="botones" value="Cancelar" title="Cancelar o Seleccionar otro Presupuesto" 
						onMouseOver="window.status='';return true" onclick="hdn_band.value='no'; confirmarSalida('frm_modificarPresupuesto.php')" />
					
					</div></td>
				</tr>
			</table>
    	</form>
</fieldset>

        <div id="calendario-Ini">
            <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_modificarPresupuesto.txt_fechaIni,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Inicio"/> 
        </div>
        
        <div id="calendario-Fin">
            <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_modificarPresupuesto.txt_fechaFin,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Fin"/> 
        </div><?php
	}//FIN if(isset($_POST['ckb_idPresupuesto']))
 //} //Fin del?>	 
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>