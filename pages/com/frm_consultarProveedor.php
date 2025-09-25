<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para mostrar la informacion del proveedor que se esta consultando
		include ("op_consultarProveedor.php");
	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:173px; height:25px; z-index:11; }
		#consultar-proveedor {position:absolute; left:30px; top:191px; width:592px; height:88px; z-index:14;}
		#consultar-proveedor2 {position:absolute; left:30px; top:310px; width:590px; height:88px; z-index:13; }
		#consultar-proveedor3 {position:absolute; left:680px; top:191px; width:280px; height:88px; z-index:10; }
		#ver-proveedor { position:absolute; left:30px; top:430px; width:940px; height:220px; z-index:12; overflow: scroll; }
		#ver-proveedores { position:absolute; left:30px; top:191px; width:940px; height:400px; z-index:15; overflow: scroll; }
		#boton { position:absolute; left:30px; top:640px; width:940px; height:25px; z-index:15;}
		#consultar-relevancia { position:absolute; left:684px; top:314px; width:280px; height:88px; z-index:16; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar Proveedor xd</div><?php 
	if(isset($_POST['sbt_todos'])){
		echo "<div id='ver-proveedores' class='borde_seccion2'>";
		mostrarProveedores("",3);//3: Consultar todos los Proveedores registrados
		echo "</div>";
		?>
		<div id="boton">
		<table align="center">
			<tr>
				<td>
            		<input name="btn_cancelar" type="button" value="Regresar" class="botones" title="Regresar al Men&uacute; de Opciones de B&uacute;squeda" 
              		onclick="location.href='frm_consultarProveedor.php'" onmouseover="window.status='';return true"/>
	       		</td>
			</tr>
		</table>
		</div><?php
	}else{?>
		<fieldset class="borde_seccion" id="consultar-proveedor">
		<legend class="titulo_etiqueta">Consultar Proveedor por Nombre</legend>	
		<br>		
		<form onSubmit="return valFormconsultarProveedor(this);"name="frm_consultarProveedor" method="post" action="frm_consultarProveedor.php">
            <table width="587" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
                <tr valign="top">			
                  <td width="99"><div align="right">Proveedor</div></td>
                    <td width="180" align="center">
                        <input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" 
                        value="" size="30" maxlength="80" />
                        <div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
                            <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                            <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
                        </div>
                  	</td>
                    <td width="123" align="center">
                        <input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" 
                        title="Consultar Informaci&oacute;n del Proveedor Seleccionado"  onmouseover="window.status='';return true" value="Consultar" />
               	  </td>
                    <td width="120" align="center">
                        <input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Proveedores"
                        onclick="location.href='menu_proveedores.php'" />
                  	</td>
                </tr>
            </table>    
		</form>    			 		
		</fieldset>	
			
		<fieldset id="consultar-proveedor2" class="borde_seccion">
		<legend class="titulo_etiqueta">Consultar Proveedor por Material y/&oacute; Servicio</legend>	
		<br>
		<form onSubmit="return valFormconsultarProveedor2(this);" name="frm_consultarProveedor2" method="post" action="frm_consultarProveedor.php">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
	        	<tr valign="top">
    	    		<td><div align="right">Material y/&oacute; Servicio  </div></td>
        			<td>
						<input type="text" name="txt_matServ" id="txt_matServ" onkeyup="lookup(this,'bd_compras','proveedores','mat_servicio','2');" value=""
                        size="30" maxlength="120" />
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
			    		</div>
        	    	<td>
						<input name="sbt_consultar2" type="submit" class="botones" id="sbt_consultar2" value="Consultar" 
						title="Consultar Informaci&oacute;n del Proveedor de Acuerdo al Servicio o Material que Ofrece" onmouseover="window.status='';return true" />
					</td>
    	        	<td>
                    	<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Proveedores"
                        onclick="location.href='menu_proveedores.php'" /></td>
				</tr>
			</table>
   		</form>	   
		</fieldset>

		<fieldset class="borde_seccion" id="consultar-proveedor3" name="consultar-proveedor3">
		<legend class="titulo_etiqueta">Consultar Todos los Proveedores</legend>	
		<br>
		<form name="frm_consultarProveedor3" method="post" action="frm_consultarProveedor.php">
			<table align="center" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
    	       		<td>
                		<input name="sbt_todos" type="submit" value="Consultar" class="botones" title="Consultar todos los Proveedores"
                    	onmouseover="window.status='';return true"/>
               		</td>
				</tr>
			</table>
   		</form>	   
		</fieldset>	
		
		<fieldset class="borde_seccion" id="consultar-relevancia" name="consultar-relevancia">
		<legend class="titulo_etiqueta">Consultar Proveedores por Relevancia</legend>
			<form onSubmit="return valFormConsultarRelevancia(this)" name="frm_consultarRelevancia" method="post" action="frm_consultarProveedor.php">
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" align="center">
					<tr>
						<td>Relevancia</td>
						<td>
							<select name="cmb_relevancia" class="combo_box">
								<option value="">Relevancia</option>
								<option value="NO CRITICO">NO CRITICO</option>
								<option value="CRITICO">CRITICO</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input name="sbt_relevancia" type="submit" value="Consultar" class="botones" title="Consultar Proveedores por su Relevancia"
                    		onmouseover="window.status='';return true"/>
						</td>
					</tr>
				</table>
			</form>
		</fieldset><?php
		
		if(isset($_POST['txt_nombre']) && $txt_nombre!=""){?>
			<div id="ver-proveedor" class="borde_seccion2"><?php
				mostrarProveedores($txt_nombre,1);//1: Consultar Proveedor por Nombre?>
			</div><?php }
		if(isset($_POST['txt_matServ']) && $txt_matServ!=""){?>
			<div id="ver-proveedor" class="borde_seccion2"><?php
				mostrarProveedores($txt_matServ,2);//2: Consultar Proveedor por Material o Servicio Ofrecido?>
			</div><?php 
		}
		if(isset($_POST['cmb_relevancia']) && $cmb_relevancia!=""){?>
			<div id="ver-proveedor" class="borde_seccion2"><?php
				mostrarProveedores($cmb_relevancia,4);//4: Consultar Proveedor por Relevancia?>
			</div><?php 
		}
	}//Cierre del Else?>        
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>