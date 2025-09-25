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
		//Este archivo muestra los pedidos registrados en la BD
		include ("op_consultarPedido.php");
	
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<style type="text/css">
		<!--
		#titulo-consultaPedido { position:absolute;	left:30px; top:146px; width:163px; height:22px;	z-index:11;	}
		#tabla-departamento { position:absolute; left:30px; top:190px;	width:452px; height:144px; z-index:12;  }
		#tabla-pedido { position:absolute; left:530px; top:190px; width:340px; height:144px; z-index:13;	}
		#boton-cancelar{position:absolute;left:290px;top:650px;width:227px;height:37px;z-index:14;}
		#botones{position:absolute;left:34px;top:650px;width:919px;height:37px;z-index:19;}
		#calendario_pedido_ini {position:absolute; left:238px; top:266px; width:30px; height:26px; z-index:15; }
		#calendario_pedido_fin {position:absolute; left:460px; top:266px; width:30px; height:26px; z-index:16; }
		#calendario_lista_pedido1 {position:absolute; left:755px; top:233px; width:30px; height:26px; z-index:17; }
		#calendario_lista_pedido2 {position:absolute; left:755px; top:269px; width:30px; height:26px; z-index:18; }
		#resultados {position:absolute; left:30px; top:190px; width:900px; height:400px; z-index:15; overflow:scroll; }
		#detallePedido {position:absolute; left:20px; top:180px; width:957px; height:440px; z-index:15; overflow:auto; }
		#botones_mod{position:absolute;left:34px;top:650px;width:919px;height:37px;z-index:19;}
		-->
   	</style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultaPedido">Consulta de Pedidos </div>
	<?php 
		if(isset($_GET["cancelPed"])){
			cancelarPedido($_GET["cancelPed"],$_GET["cancelReq"]);
		}
	if (count($_POST)<1){//Si POST es mayor a 0, significa que no se ha presionado ningun boton
		if(isset($_SESSION["detalle_pedido"]))
			unset($_SESSION["detalle_pedido"]);?>
		<fieldset class="borde_seccion" id="tabla-departamento" name="tabla-departamento" >
		<legend class="titulo_etiqueta"> Consultar Pedidos por Departamento</legend>
		<br />
		<form onsubmit="return valFormConsultaPedido(this);" name="frm_consultadePedido" method="post" action="frm_consultadePedido.php">
		<table cellpadding="5" cellspacing="5" width="100%" class="tabla_frm">
			<tr>
			  <td width="21%"><div align="right">Departamento</div></td>
		       	<td colspan="3">
				<?php
					$res=cargarCombo("cmb_departamento","departamento","organigrama","bd_recursos","Departamento","");
					if ($res==""){
						echo "No Hay Departamentos Registrados, Consulte a Recursos Humanos";
					}
				?>				</td>
			</tr>
				<td>
                   	<div align="right">Fecha Inicio </div>
                </td>
	    	  	<td width="27%">
                   	<input name="txt_fechaPed1"  type="text" id="txt_fechaPed1" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" maxlength="15" readonly=true width="90" />
				</td>
				<td width="23%"><div align="right">Fecha Fin </div></td>
        		<td width="29%">
                   	<input name="txt_fechaPed2"  type="text" id="txt_fechaPed2" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" readonly=true width="90" />
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input name="btn_depto" type="submit" class="botones" id="btn_depto" value="Consultar" onmouseover="window.status='';return true;" title="Consultar Pedido" />
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Pedidos"
		            onclick="location.href='menu_pedidos.php'"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	
		<fieldset class="borde_seccion" id="tabla-pedido" name="tabla-pedido" >
		<legend class="titulo_etiqueta"> Consultar Lista de Pedidos</legend>
		<br />
		<form onsubmit="return valFormConsultaPedido2(this);" name="frm_consultadePedido2" method="post" action="frm_consultadePedido.php">
		<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%" >
    		<tr>
				<td width="34%" align="right" >Fecha Inicio</td>
   			  	<td width="66%">
		  			<input name="txt_fechaPed3" type="text" id="txt_fechaPed3" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" maxlength="15" readonly="true" width="90"/>
			  </td>
			</tr>
			<tr>
				<td align="right" >Fecha Fin</td>
   			  	<td width="66%">
					<input name="txt_fechaPed4" type="text" id="txt_fechaPed4" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" readonly="true" width="90"/>
		  	  </td>	    	    
    		</tr>
			<tr>
				<td colspan="4" align="center">
					<input name="btn_pedido" type="submit" class="botones" id="btn_pedido"  value="Consultar" onmouseover="window.status='';return true;" 
                    title="Consultar Pedido" />
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Pedidos"
		            onclick="location.href='menu_pedidos.php'"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<?php //Declaracion de los layers con los calendarios?>
		<div id="calendario_pedido_ini">
		  	<input name="calendario_ped1" type="image" id="calendario_ped1" onclick="displayCalendar(document.frm_consultadePedido.txt_fechaPed1,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<div id="calendario_pedido_fin">
		  	<input name="calendario_ped2" type="image" id="calendario_ped2" onclick="displayCalendar(document.frm_consultadePedido.txt_fechaPed2,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>

		<div id="calendario_lista_pedido1">
		  	<input name="calendario_lis1" type="image" id="calendario_lis1" onclick="displayCalendar(document.frm_consultadePedido2.txt_fechaPed3,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<div id="calendario_lista_pedido2">
		  	<input name="calendario_lis2" type="image" id="calendario_lis2" onclick="displayCalendar(document.frm_consultadePedido2.txt_fechaPed4,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
	
	<?php 
	}
	else{
		if (isset($_POST["sbt_detalle"])){
			echo "<div id='resultados' class='borde_seccion2'>";
			mostrarDetallePedido();
			echo "</div>";
			//Obtener la Requisicion Asociada al Pedido
			$requisicion=obtenerdato("bd_compras","pedido","requisiciones_id_requisicion","id_pedido",$_POST["rdb_idPedido"]);
			//Obtener el Estado del Pedido
			$estado=obtenerdato("bd_compras","pedido","estado","id_pedido",$_POST["rdb_idPedido"]);
			//Variable con las propiedades de titulo y habilitacion/deshabilitacion para el boton de Cancelar Pedido
			$propBtn="title='Cancelar el Pedido $_POST[rdb_idPedido]'";
			if($estado=="CANCELADO"){
				$propBtn="title='El Pedido ya fue Cancelado' disabled='disabled'";
			}
			?>
			
			<script type="text/javascript" language="javascript">
				function cancelarPedidoConsultado(idPedido,idReq){
					if(confirm("¡Aviso! \nEsta a punto de Cancelar el Pedido \""+idPedido+"\" y la Requisición Asociada \""+idReq+"\". ¿Desea Continuar?"))
						location.href="frm_consultadePedido.php?cancelPed="+idPedido+"&cancelReq="+idReq;
				}
			</script>
			
			<div id='botones' align='center'>
			<form name="frm_regresarConsultaPedido" method="post" action="frm_consultadePedido.php">
				<input type="hidden" name="hdn_noReq" id="hdn_noReq" value="<?php echo $requisicion?>"/>
				<?php if($_POST['hdn_btnSeleccionado']=="btn_pedido"){//Colocar los datos cuando la consulta es por un fecha determinada ?>
					<input type="hidden" name="btn_pedido" value="1" />
					<input type="hidden" name="txt_fechaPed3" value="<?php echo $_POST["hdn_fechaPed3"]; ?>" />
					<input type="hidden" name="txt_fechaPed4" value="<?php echo $_POST["hdn_fechaPed4"]; ?>" />
				<?php }
				if($_POST['hdn_btnSeleccionado']=="btn_depto"){//Colocar los datos cuando la consulta es por Depto y un rango de fecha ?>
					<input type="hidden" name="btn_depto" value="1" />
					<input type="hidden" name="cmb_departamento" value="<?php echo $_POST["hdn_departamento"]; ?>" />
					<input type="hidden" name="txt_fechaPed1" value="<?php echo $_POST["hdn_fechaPed1"]; ?>" />
					<input type="hidden" name="txt_fechaPed2" value="<?php echo $_POST["hdn_fechaPed2"]; ?>" />
				<?php } ?>
				
				<input type="hidden" id="txt_idPedido" name="txt_idPedido" value="<?php echo $_POST["rdb_idPedido"]; ?>" />
				<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" title="Ver Archivo PDF del Pedido" onmouseover="window.status='';return true" 					
                onclick="window.open('../../includes/generadorPDF/pedido2.php?id=<?php echo $_POST["rdb_idPedido"]; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no,   directories=no')" />
                &nbsp;&nbsp;
				<?php 
				if(busqEntradaPed($_POST["rdb_idPedido"]) == 0) { 
				?>
				<input name="btn_modificar" type="submit" value="Modificar" class="botones" title="Modificar Pedido" onmouseover="window.status='';return true" />
				&nbsp;&nbsp;
				<?php 
				} 
				?>
				<input name="btn_regresar" type="submit" value="Regresar" class="botones" title="Regresar a la Consulta de Pedidos" onmouseover="window.status='';return true" />
				&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" value="Cancelar Pedido" class="botones" onclick="cancelarPedidoConsultado('<?php echo $_POST["rdb_idPedido"]; ?>',hdn_noReq.value);"
				<?php echo $propBtn;?>/>
			</form>
			</div>
			<?php
		}
		else if(isset($_POST["btn_modificar"])){
			?>
			<form name='frm_modificarDetallePedido' method='post' action='frm_modificarPedido.php' onSubmit="return valFormDetallesPedido1(this);">
				<div id='detallePedido' class='borde_seccion' >
					<?php
					modificarDetallePedido();
					?>
				</div>
				<div id='botones_mod' align='center'>
					<input type="hidden" name="hdn_iva" id="hdn_iva"/>
					<input name="btn_continuarModificacion" type="submit" class="botones" value="Continuar" onmouseover="window.status='';return true;" title="Modificar Pedido"/>
					&nbsp;
					<input name="btn_return" type="button" value="Regresar" class="botones" title="Regresar a la Consulta de Pedidos" 
					onmouseover="window.status='';return true" onclick="location.href='frm_consultadePedido.php'"/>
				</div>
			</form>
			<?php
		}
		else{
			echo "<form name='frm_consultaDetallePedido' onsubmit='return valFormConsultaDetallePedido(this);' method='post' action=''>";
				echo "<div id='resultados' class='borde_seccion2'>";
				if (isset($_POST["btn_pedido"]))
					mostrarPedidos(1);
				if (isset($_POST["btn_depto"]))
					mostrarPedidos(2);
				echo "</div>";?>
				<div id='botones' align='center'>
					<input name="sbt_detalle" type="submit" class="botones_largos" id="sbt_detalle" value="Consultar Detalle" 
                    title="Consultar Detalle de Pedido" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar a la Consulta de Pedidos"
                    onclick="location.href='frm_consultadePedido.php'"/>
				</div><?php	
			echo "</form>";
		}
	}?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>