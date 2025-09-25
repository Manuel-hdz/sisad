<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye las operaciones para realizar el reporte de Pago del Seguro Social
		include ("op_reportePagoSeguroSocial.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>		
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-empleado {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-consultar-empleados2 {position:absolute; left:30px; top:198px; width:424px; height:198px; z-index:14;}
			#tabla-empleados { position:absolute; left:30px; top:190px; width:945px; height:380px; z-index:21; overflow:scroll; }
			#tabla-datos { position:absolute; left:404px; top:-10px; width:157px; height:56px; z-index:21;}
			#btns-regpdf { position: absolute; left:30px; top:620px; width:945px; height:40px; z-index:23; }
			#calendar-tres {position:absolute; left:233px; top:241px; width:30px; height:26px; z-index:18; }
			#calendar-cuatro {position:absolute; left:440px; top:241px; width:30px; height:26px; z-index:18; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Reporte de Pago Seguro Social </div><?php 
		//Verificamos si viene definido en el post el boton consultar
		if(isset($_POST["sbt_consultar"])){
			echo"<div align='center' id='tabla-empleados' class='borde_seccion2' width='100%' >";
				//Si viene definido el boton; mostrar el reporte de Pago de Seguro Social
				reporteSeguroSocial();
			echo "</div>";?>	
 <?php }
	  else{ ?>
			<fieldset class="borde_seccion" id="tabla-consultar-empleados2">
			<legend class="titulo_etiqueta">Reporte Pago del Seguro Social</legend>	
			<form  method="post" name="frm_reporteFecha" id="frm_reporteFecha"  onsubmit=" return valFormRptSegSocFecha(this);" >
			<table width="302" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
            	<td width="86"><div align="right">A&ntilde;o</div></td>
				<td>
				<?php
					$cmb_anio="";
					$conn = conecta("bd_recursos");
					$result=mysql_query("SELECT DISTINCT anio_insercion FROM nomina_bancaria ORDER BY anio_insercion");
					if($anios=mysql_fetch_array($result)){?>
					<select name="cmb_anio" id="cmb_anio" size="1" class="combo_box" onchange="cargarCombo(this.value,'bd_recursos','nomina_bancaria','mes','anio_insercion','cmb_mes','Mes','');">
					  <option value="">A&ntilde;o</option>
					  <?php 
						  do{
							if ($anios['anio_insercion'] == $cmb_anio){
								echo "<option value='$anios[anio_insercion]' selected='selected'>$anios[anio_insercion]</option>";
							}
							else{
								echo "<option value='$anios[anio_insercion]'>$anios[anio_insercion]</option>";
							}
						}while($anios=mysql_fetch_array($result)); 
						//Cerrar la conexion con la BD		
						mysql_close($conn);
					?>
					</select>
				<?php }
				else{
					echo "<label class='msje_correcto'> No hay A&ntilde;os Registrados</label>
					<input type='hidden' name='cmb_anio' id='cmb_anio'/>";
				  }?>
				</td>
            </tr>
            <tr>
            	<td width="86"><div align="right">Mes</div></td>
             	<td><select name="cmb_mes" id="cmb_mes" 
						onchange="cargarCombo(this.value,'bd_recursos','nomina_bancaria','semana','mes','cmb_semana','Semana','');">    	
						<option value="">Mes</option>
					</select></td>
          	</tr>
			<tr>
            	<td width="86" ><div align="right">Semana</div></td>
             	<td><select name="cmb_semana" id="cmb_semana">
						<option value="">Semana</option>
					</select></td>
          	</tr>
        </table>
        <div align="center">
				<p>
					<input name="sbt_consultar" type="submit" class="botones_largos" id="sbt_consultar" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte Pago Seguro Social"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" />
				</p>
			</div>
       </form>
</fieldset>
	        <?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>