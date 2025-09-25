<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reporteComparativoMensual.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>       
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-reporte {position:absolute;left:30px;top:146px;width:328px;height:20px;z-index:11;}
		#tabla-busqReporte {position:absolute;left:30px;top:190px;width:480px;height:120px;z-index:14;}
		#tabla-comparativo {position:absolute;left:32px;top:186px;width:914px;height:450px;z-index:15; overflow:scroll}
		#presupuestosReg {position:absolute;left:32px;top:344px;width:914px;height:161px;z-index:12; overflow:scroll;}
		#btns-regpdf { position: absolute; left:30px; top:680px; width:945px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-reporte">Reporte Comparativo Mensual</div><?php
   	if(!isset($_POST['ckb_idPresupuesto'])){?>
        <fieldset class="borde_seccion" id="tabla-busqReporte" name="tabla-busqReporte">
        <legend class="titulo_etiqueta">Seleccionar Ubicaci&oacute;n y Periodo</legend>	
        <br>
        <form onSubmit="return valFormRepCompMens(this);" name="frm_reporteComparativoMensual" method="post" action="frm_reporteComparativoMensual.php">
        <table cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td width="61"><div align="right">Ubicaci&oacute;n</div></td>
                <td width="118"><?php
                    $cmb_ubicacion="";
                    $conn = conecta("bd_gerencia");
                    $result=mysql_query("SELECT id_ubicacion,ubicacion FROM catalogo_ubicaciones WHERE ubicacion!='COLADOS' AND ubicacion!='VIA SECA' 
                    ORDER BY id_ubicacion");
					$contReg=mysql_num_rows(mysql_query("SELECT id_ubicacion,ubicacion FROM catalogo_ubicaciones WHERE ubicacion!='COLADOS' AND ubicacion!='VIA SECA'                    ORDER BY id_ubicacion"));
					if($contReg!=0){?>
                    <select name="cmb_ubicacion" id="cmb_ubicacion" size="1" class="combo_box" 
                    onchange="cargarComboOrdenado(this.value,'bd_gerencia','presupuesto','periodo','catalogo_ubicaciones_id_ubicacion','cmb_periodo','Periodo','','fecha_fin')">
                    <option value="">Ubicaci&oacute;n</option><?php
                    while ($row=mysql_fetch_array($result)){
                        if ($row['id_ubicacion'] == $cmb_ubicacion){
                            echo "<option value='$row[id_ubicacion]' selected='selected'>$row[ubicacion]</option>";
                        }
                        else{
                            echo "<option value='$row[id_ubicacion]'>$row[ubicacion]</option>";
                        }
                    } 
                    //Cerrar la conexion con la BD		
                    mysql_close($conn);?>
                    </select>
				<?php } if($contReg==0){?>
							<label class="msje_correcto">No Hay Ubicaciones Registradas</label>
                        	<input type="hidden" name="cmb_ubicacion" value="" /><?php 
					   }?>                </td>
                <td width="44"><div align="right">Periodo</div></td>
                <td width="177">
				<?php if($contReg!=0){?>
					<select name="cmb_periodo" id="cmb_periodo" class="combo_box">
                  		<option value="">Periodo</option>
                	</select>
				<?php }else{?>
						<label class="msje_correcto">No Hay Periodos Registrados</label>
    	               	<input type="hidden" name="cmb_periodo" value="" /><?php 
					}?>
			  </td>
            </tr>    
            <tr>
                <td colspan="4">
                    <div align="center">
                        <input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar"  value="Consultar" title="Consultar Periodo Seleccionado"
                         onmouseover="window.status='';return true"/>
                        &nbsp;&nbsp;&nbsp;
                        <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute;  Reportes" 
                        onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php';" />
                    </div>          
                </td>
            </tr>
        </table>
        </form>
        </fieldset>
		
		<?php
            
        //Si viene en el post cmb_periodo desplegar la tabla de resultados
        if(isset($_POST['cmb_periodo']) && isset($_POST['cmb_ubicacion'])){?>
            <form name="frm_seleccionarPresupuesto" method="post">
                <div id='presupuestosReg' class='borde_seccion2'>
					<?php
                    mostrarPresupuestos();
					?>
				<input type="hidden" name="hdn_empleados" id="hdn_empleados" value=""/>
            </form><?php
        }
	}//FIN 	if(!isset($_POST['ckb_idPresupuesto']))	
	if(isset($_POST['ckb_idPresupuesto'])){?>
		<div id='tabla-comparativo' class='borde_seccion2'><?php
        	$res=mostrarComparativo();?>
        </div>
		<div id="btns-regpdf" align="center" >
		<table width="100%">
			<tr>
				<td align="center">
					<?php 
					if($res==1){ 
						$periodo=$_POST['cmb_periodo'];
						$ubicacion=$_POST['hdn_ubicacion'];
						$numEmp=$_POST["hdn_empleados"];
						?>
						<input type="button" class="botones" value="Exportar a Excel" name="btn_exportar" id="btn_exportar" title="Exportar a Excel el Reporte" 
						onclick="location.href='guardar_reporte.php?periodo=<?php echo $periodo?>&ubicacion=<?php echo $ubicacion?>&numEmp=<?php echo $numEmp?>&tipoRep=RepCompMes'"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a Seleccionar Nuevos Parametros" 
					onMouseOver="window.estatus='';return true" 
					onclick="location.href='frm_reporteComparativoMensual.php'" />			  
				</td>
			</tr>
		</table>			
</div>
		<?php
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>