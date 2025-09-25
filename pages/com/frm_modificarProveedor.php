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
		//Este archivo ejecuta el mostrar los datos del proveedor que se pretende eliminar con opcion a modificarlos en la tabla
		include ("op_modificarProveedor.php");
		//Este archivo ejecuta el mostrar los documentos del proveedor que tiene registrados
		include ("op_consultarProveedor.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
    <style type="text/css">
		<!--
		#titulo-consultar { position:absolute; left:30px; top:146px; width:155px; height:21px; z-index:11; }
		#tabla-consultar {	position:absolute; left:30px; top:190px; width:545px; height:133px;	z-index:12;}
		#tabla-modificar {	position:absolute; left:30px; top:190px; width:679px; height:490px;	z-index:12;}
		#tabla-documentos{position:absolute; left:30px; top:190px; width:900px; height:300px; z-index:12; overflow:scroll; }
		#botones{position:absolute; left:30px; top:550px; width:900px; height:21px; z-index:13;}
         -->
    </style>
</head>
<body>      
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Modificar Proveedor</div><?php 
	//Si las variables $cmb_material y $txt_clave no estan definidas mostrar los formularios para seleccionar el material a modificar
	if(!isset($_GET['btn'])&&!isset($_POST['txt_razonSoc'])&&!isset($_POST['btn_modificar'])){?>	  
 		<fieldset class="borde_seccion" id="tabla-consultar" name="tabla-consultar">
            <legend class="titulo_etiqueta">Modificar Proveedor</legend>	
            <br>
            <form onSubmit="return valFormmodificarProveedor(this);" name="frm_modificarProveedor" method="post" action="frm_modificarProveedor.php">
            <p class="titulo_etiqueta">	Buscar Proveedor por Nombre</p>
            <table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
            	<tr valign="top">
                	<td width="50">Nombre</td>
                    <td width="130">
                    	<input type="text" name="txt_razonSoc" id="txt_razonSoc" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" 
                        value="" size="30" maxlength="80" onkeypress="return permite(event,'num_car', 0);"/>
                        <div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
                        	<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                            <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
                        </div>
                  </td>
                     <td align="center"><input name="sbt_consultar" type="submit" value="Modificar" class="botones" 
                     	title="Modificar Información del Proveedor Seleccionado " onmouseover="window.status='';return true" />
                     </td>
                     <td align="center"><input name="btn_cancelar" type="button" value="Cancelar" class="botones" 
                     	title="Regresar a la página de Inicio de Compras" onclick="location.href='menu_proveedores.php'" />
                     </td>
              </tr>
              </table>
        </form>
		</fieldset>	
	<?php }
	else{
		//Variable que controla las operaciones
		$ctrl=0;
		//Si la variable btn_Modificar viene en el POST, realizar la actualizacion de datos
		if (isset($_POST["btn_Modificar"])){
			guardarCambios();
			$ctrl=1;
		} 
		if(isset($_GET["btn"])){
			//Si en el GET esta declarado btn_modificarDoc, mostrar los documentos que estan registrados para el Proveedor seleccionado
			if($_GET["btn"]=="btn_modificarDoc"&&$ctrl==0){
				//echo "<form name='frm_botones'>";
				echo "<div id='tabla-documentos' class='borde_seccion2' name='tabla-documentos'>";
				$res=mostrarDocumentos();
				echo "</div>";?>
				<div id='botones' name='botones'>
					<form name="frm_botones" method="post" action="frm_modificarProveedor.php">
					<table class='tabla_frm' border='0' align="center">
						<tr>
							<td>
								<input type="hidden" name="txt_nombre" value="<?php echo $txt_nombre; ?>" />
                            	<input type="hidden" name="hdn_nombre" id="hdn_nombre" value="<?php echo $_POST["txt_nombre"];?>" />
                                <input type="hidden" name="hdn_rfc" id="hdn_rfc" value="<?php echo $_POST["txt_rfc"];?>" />
                                <input name='btn_agregar' id="btn_agregar" type='button' value='Agregar' class='botones'
                                title='Agregar nuevo documento al expediente del proveedor' onmouseover="window.status='';return true" 
                                onclick="document.frm_botones.action='frm_agregarProveedorRegDoc.php?btn=agregar';document.frm_botones.submit();"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input name='btn_eliminar' id='btn_eliminar' type='button' value='Eliminar' class='botones' <?php echo $res;?>
                                title='Eliminar documento del expediente del proveedor' onmouseover="window.status='';return true"
                                onclick="document.frm_botones.action='frm_agregarProveedorRegDoc.php?btn=eliminar';document.frm_botones.submit();"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								
								<?php /*Si la consulta de la documentacion vienen desde COnsultar Proveedor, colocar el boton que redirecciona al Inicio de Compras, 
								sino colocar el boton que redirecciona a Modificar Proveedor */
								if(isset($_GET['org']) && $_GET['org']=="consulta"){ ?>
                                	<input name='btn_cancelar' id="btn_cancelar" type='button' value='Cancelar' class='botones'
                                	title='Regresar al Inicio de Compras' onclick="location.href='inicio_compras.php';" />
								<?php } else {?>
									<input name='sbt_cancelar' id="sbt_cancelar" type='submit' value='Cancelar' class='botones'
                                	title='Regresar a la página de Modificar Proveedor' onmouseover="window.status='';return true" />
								<?php } ?>
							</td>
						</tr>
					</table>
				    </form>
				</div>
				<?php
				$ctrl=1;
			}//Cierre if($_GET["btn"]=="btn_modificarDoc"&&$ctrl==0)
		}//Cierre if(isset($_GET["btn"]))
		
		
		//Si ctrl cambio de Valor, se ejecutó alguna de las sentencias anteriors, entonces la siguiente no ejecutarla
		if ($ctrl==0){
			$nombre=$_POST["txt_razonSoc"];?>
			<fieldset class="borde_seccion" id="tabla-modificar" name="tabla-modificar">
			<legend class="titulo_etiqueta">Modificar Proveedores</legend>
<br><?php
				mostrarProveedor($nombre);
			?></fieldset><?php            
		}
	} ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>