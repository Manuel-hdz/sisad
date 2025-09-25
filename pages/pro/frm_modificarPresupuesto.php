<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
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
	<script type="text/javascript" src="../../includes/validacionProduccion.js" ></script>
    <script type="text/javascript" src="includes/ajax/verificarRangoFechas.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>   
	
	
	
	
	
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#tabla-modificarPresupuesto {position:absolute;left:30px;top:190px;width:719px;height:300px;z-index:14;}
		#tabla-busqPresupuesto {position:absolute;left:30px;top:190px;width:506px;height:120px;z-index:14;}
		#calendario-Ini {position:absolute;left:316px;top:232px;width:29px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:317px;top:268px;width:30px;height:26px;z-index:14;}
		#presupuestosReg {position:absolute;left:34px;top:344px;width:914px;height:318px;z-index:12;overflow:scroll}
		-->
    </style>
</head>
<body><?php

	if(isset($_POST['sbt_guardarMod']))
		guardarModPresupuesto();
		
	if(!isset($_POST['ckb_idPresupuesto'])){?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-modificar">Modificar Presupuesto Mensual</div>		
		<fieldset class="borde_seccion" id="tabla-busqPresupuesto" name="tabla-busqPresupuesto">
		<legend class="titulo_etiqueta">Seleccionar Periodo</legend>	
		<br>
		<form onSubmit="return valFormBusqPresupuesto(this);" name="frm_modificarPresupuesto" method="post" action="frm_modificarPresupuesto.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
              <td><div align="right">Destino</div></td>
              <td width="57"><?php
					$cmb_destino="";
					$conn = conecta("bd_produccion");
					$result=mysql_query("SELECT id_destino ,destino FROM catalogo_destino ORDER BY destino");?>
                  <select name="cmb_destino" id="cmb_destino"  class="combo_box" 
					onchange="cargarComboOrdenado(this.value,'bd_produccion','presupuesto','periodo','catalogo_destino_id_destino','cmb_periodo','Periodo','','fecha_fin')">	
					<option value="">Destino</option>
                    <?php
							 while ($row=mysql_fetch_array($result)){
								if ($row['id_destino'] == $cmb_destino){
									echo "<option value='$row[id_destino]' selected='selected'>$row[destino]</option>";
								}
								else{
									echo "<option value='$row[id_destino]'>$row[destino]</option>";
								}
							} 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
                  </select>
              </td>
              <td><div align="right">Periodo</div></td>
              <td width="147"><select name="cmb_periodo" id="cmb_periodo" class="combo_box">
                  <option value="">Seleccione</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="4"><div align="center">
                  <input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar"  value="Continuar" title="Continuar"
                         onmouseover="window.status='';return true"/>
                &nbsp;&nbsp;&nbsp;
                <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute;  Planeación" 
						onmouseover="window.status='';return true" onclick="location.href='menu_presupuesto.php';" />
              </div></td>
              <td>&nbsp;</td>
            </tr>
          </table>
		</form>
		
</fieldset>
<?php
		//Si viene en el post sbt_continuar desplegar la tabla de resultados
		if(isset($_POST['cmb_periodo']) && isset($_POST['cmb_destino'])){?>
        	<form name="frm_seleccionarPresupuesto" method="post">
			<div id='presupuestosReg' class='borde_seccion2'>
			<?php		
				mostrarPresupuestos();?>
			</div>
            </form><?php 
		}
	}//FIN 	if(!isset($_POST['ckb_idPresupuesto']))
	
	if(isset($_POST['ckb_idPresupuesto'])){
		//Relizar la consulta con el id del presupuesto seleccionad para poder precargar los datos 
		//Conectar a la BD de Produccion
		$conn = conecta("bd_produccion");

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM presupuesto JOIN catalogo_destino ON id_destino = catalogo_destino_id_destino  WHERE id_presupuesto = '$_POST[ckb_idPresupuesto]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);?>
    
<?php //Falta la funcion de javaScrip que coloque en TEXPAD ?>
				
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-modificar">Modificar Presupuesto Mensual</div>		
		<fieldset class="borde_seccion" id="tabla-modificarPresupuesto" name="tabla-modificarPresupuesto">
		<legend class="titulo_etiqueta">Ingresar Datos del Presupuesto Mensual</legend>	
		<br>
		<form onSubmit="return valFormRegPresupuesto(this);" name="frm_registrarPresupuesto" method="post" action="frm_modificarPresupuesto.php">
		<table width="716" height="272" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="145"><div align="right">Fecha Inicio</div></td>
				<td width="157"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10"
				value="<?php echo modFecha($datos['fecha_inicio'],1) ?>" 
				onchange="sumarDiasMes();calcularDiasLaborales();calcularDomingos();verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,cmb_destino.value)"
				readonly="readonly"/>
			  </td>
			  <td width="95"><div align="right">D&iacute;as Laborables</div></td>
			  <td width="116"><input type="text" class="caja_de_texto" value="<?php echo $datos['dias_habiles'];?>" name="txt_diasLaborales" id="txt_diasLaborales" 
			  size="4" onkeypress="return permite(event,'num',3);" onchange="formatCero();"/></td>
			</tr>     
			<tr>
			  <td width="145"><div align="right">Fecha Fin</div></td>
				<td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo modFecha($datos['fecha_fin'],1) ?>" 
				readonly="readonly" 
                onchange="calcularDomingos();verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,cmb_destino.value)"/></td>
			  <td width="95"><div align="right">Domingos</div></td>
				<td><input type="text" class="caja_de_texto" value="<?php echo $datos['dias_inhabiles'];?>" name="txt_domingos" id="txt_domingos" size="4" readonly="readonly"/></td>        
			</tr> 
			<tr>
			  <td width="145"><div align="right">*Volumen Presupuestado</div></td>
				<td><input type="text" name="txt_volPresupuestado" id="txt_volPresupuestado" maxlength="10" size="10" class="caja_de_texto" 
				onkeypress="return permite(event,'num',2);" value="<?php echo $datos['vol_ppto_mes'] ?>" 
				onchange="formatCurrency(this.value,'txt_volPresupuestado'); txt_presupuestoDiario.value= parseFloat(txt_volPresupuestado.value.replace(/,/g,''))/26;formatCurrency(txt_presupuestoDiario.value,'txt_presupuestoDiario');"/>m&sup3;</td>
				<td><div align="right">*Volumen Diario</div></td>
				<td><input type="text" name="txt_presupuestoDiario" id="txt_presupuestoDiario" value="<?php echo $datos['vol_ppto_dia'] ?>" maxlength="10" 
                size="10" class="caja_de_texto"
				onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_presupuestoDiario')"/></td>
			</tr>
	       <tr>
                <td><div align="right">*Destino</div></td>
                <td><?php 
                    	$idDestino=obtenerDato("bd_produccion","catalogo_destino","id_destino","destino",$datos['destino']);
	                    $result=cargarComboConId("cmb_destino","destino","id_destino","catalogo_destino","bd_produccion","Seleccione",$idDestino,"verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,cmb_destino.value);"); 
	                if($result==0) {
                        echo "<label class='msje_correcto'>No hay Destinos Registrados</label>
                        <input type='hidden' name='cmb_destino' id='cmb_destino' disabled='disabled'/>";
					}?> 

                </td>
                <td width="116"><div align="right">
                <input type="checkbox" name="ckb_nuevoDestino" id="ckb_nuevoDestino" onclick="agregarNuevoDestino(this, 'txt_nuevoDestino', 'cmb_destino');" 
                title="Seleccione para Escribir el Nombre de un Destino que no Exista"/> 
                Agregar Nuevo Destino
                </div>
             </td>
             <td colspan="2" width="150"><input name="txt_nuevoDestino" id="txt_nuevoDestino" type="text" class="caja_de_texto" size="30" 
                readonly="readonly" onkeypress="return permite(event,'num',2);"/></td>
          </tr>	  
			<tr>
				<td colspan="5"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="5">
					<div align="center">
                    	<input type='hidden' name='cmb_periodo' value="<?php echo $_POST['cmb_periodo']?>"/>
						<input type="hidden" name="hdn_fechas" id="hdn_fechas" value="0"/>
                        <input type="hidden" name="hdn_band" id="hdn_band" value="si"/>
                        <input type="hidden" name="hdn_claveDefinida" id="hdn_claveDefinida" value="<?php echo $datos['id_presupuesto']?>"/>
						<input name="sbt_guardarMod" type="submit" class="botones" id="sbt_guardarMod"  value="Guardar" 
                        title="Guardar Modificación del Presupuesto Mensual"  onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones" value="Restablecer" title="Limpiar Formulario" onclick="restablecePresupuesto();"/>
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar " 
						onMouseOver="window.status='';return true" onclick="hdn_band.value='no'" />
					</div>          
				</td>
			</tr>
		</table>
		</form>
</fieldset>
		<div id="calendario-Ini">
			<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_registrarPresupuesto.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Inicio"/> 
</div>
		
		<div id="calendario-Fin">
			<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_registrarPresupuesto.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Fin"/> 
</div><?php
    }//FIN if(isset($_POST['ckb_idPresupuesto']))?>
</body>

<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>