<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
    <style type="text/css">
		<!--
		#clientes {	position:absolute;left:30px;top:190px;width:900px;height:400px;z-index:12;overflow:scroll}		
		#titulo-exportacion { position:absolute; left:30px; top:146px; width:136px; height:20px; z-index:11; }					
		#tabla-fecha { position:absolute; left:30px; top:190px; width:430px; height:190px; z-index:14; }
		#calendar-uno {position:absolute;left:265px;top:270px;width:30px;height:26px;z-index:20;}
		#calendar-dos {position:absolute;left:265px;top:234px;	width:30px;	height:26px;z-index:19;}
		#botones { position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:23; }
				
		-->
    </style>
</head>
<body>

	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-exportacion">Exportar CSV </div> 
	<?php if(!isset($_POST["sbt_verClientes"])){//Verificamos que se haya presionado el boton sbt_verClientes?>  
			<fieldset class="borde_seccion" id="tabla-fecha" name="tabla-fecha">
			<legend class="titulo_etiqueta">Exportar CSV Clientes</legend>
				<br />
				<form name="frm_fechaCSV" onsubmit="return valFormExportarCSV(this)" action="frm_exportarCSV.php" method="post">
		   			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
                   	  <td width="102"><div align="right">Fecha Inicio</div></td>
	                    <td width="278">
						<input name="txt_fechaIni" type="text" value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" size="10" maxlength="15" readonly=true
						width="90" />
					  </td>
					</tr>
					<tr>
						<td><div align="right">Fecha Fin</div></td>
						<td><input name="txt_fechaFin" type="text" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" readonly=true width="90" /></td>
					</tr>
					<tr>
						<td>&nbsp;
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
                        <input name="sbt_verClientes" type="submit" class="botones" value="Consultar" 
						onmouseover="window.status='';return true" title="Mostrar Clientes Por Fecha" />&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="btn_restablecer" type="reset" class="botones" value="Restablecer" 
						onmouseover="window.status='';return true" title="Restablecer Formulario" />&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="btn_cancelar" type="button" class="botones" value="Cancelar" onclick="location.href='menu_clientes.php'" 
                        title="Regresar al Men&uacute; Clientes" />
                        </td>
					</tr>
					</table>
				</form>
</fieldset>
	
    		<div id="calendar-uno">
				<input type="image" name="finRepFecha2" id="finRepFecha2" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_fechaCSV.txt_fechaFin,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Fin"/> 
			</div>
            
            <div id="calendar-dos">
				<input type="image" name="iniRepFecha" id="iniRepFecha" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_fechaCSV.txt_fechaIni,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Inicio" />
			</div>
    <?php
		}//Cierre del IF que comprueba boton presionado
		else{?>
			<form name="frm_verClientes" action="op_exportarCSV.php" method="post" onsubmit="return valFormSeleccionarClientes(this);">
			<div align="center" class="borde_seccion2" id="clientes" name="clientes">
			<p class="titulo_etiqueta" id="titulo_cliente">Seleccione Cliente</p>
				<?php 
					$conn=conecta("bd_compras");
					$fecha1=modFecha($_POST["txt_fechaIni"],3);
					$fecha2=modFecha($_POST["txt_fechaFin"],3);
					$stm_sql="SELECT rfc,razon_social, CONCAT(calle,' ',numero_ext,' ',colonia,' ',municipio,' ',estado) AS direccion FROM clientes WHERE fecha_alta>='$fecha1' AND fecha_alta<='$fecha2'";
					$rs=mysql_query($stm_sql);
					$estado="";
					if($datos=mysql_fetch_array($rs)){
						echo "								
							<table cellpadding='5' width='100%'>
							<caption class='titulo_etiqueta'>Clientes dados de Alta entre el ".$_POST["txt_fechaIni"]." y el ".$_POST["txt_fechaFin"]."</caption>					
							<tr>		
								<td class='nombres_columnas' colspan='4'><input type='checkbox' name='ckb_todo' id='ckb_todo' value='todos' onclick='seleccionarTodo(this);'/>Seleccionar Todos</td>
							</tr>
							<tr>
								<td class='nombres_columnas'>SELECCIONAR</td>
								<td class='nombres_columnas'>RFC</td>
								<td class='nombres_columnas'>RAZ&Oacute;N SOCIAL</td>
								<td class='nombres_columnas'>DIRECCI&Oacute;N</td>
							</tr>";
						$nom_clase = "renglon_gris";
						$cont = 1;	
						$cant_total = 0;
						echo "";	
						do{
							echo "	
							<tr>		
								<td class='nombres_filas'><input onclick='quitar(this);' type='checkbox' name='ckb_$cont' value='$datos[rfc]' id='ckb_$cont'/></td>			
								<td class='$nom_clase'>$datos[rfc]</td>					
								<td class='$nom_clase'>$datos[razon_social]</td>
								<td class='$nom_clase'>$datos[direccion]</td>
							</tr>";							
						//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						
						}while($datos=mysql_fetch_array($rs));						
						echo "</table>";
					}
					else{?>
							<script>
								titulo_cliente.style.visibility="hidden";
							</script>
						<?php
						echo "<span class='msje_correcto'> No hay clientes con los datos proporcionados</span>";
						$estado="disabled='disabled'";
					}
				?>
			</div>
            <div id="botones" align="center">
            	<input type="submit" name="sbt_enviar" <?php echo $estado;?> class="botones_largos" value="Exportar Clientes" title="Generar Archivo CSV con los datos de los Clientes seleccionados" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Volver a Elegir otro rango de Fechas" onclick="location.href='frm_exportarCSV.php';"/>
            </div>
            </form>
		<?php 
		}//Cierre del ELSE
	}//Comprueba el usuario de la sesion y sus permisos?>
</body>
</html>