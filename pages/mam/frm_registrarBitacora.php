<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		
		include ("op_registrarBitacora.php");
		include("op_consultarOrdenTrabajo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <script type="text/javascript" src=""></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {	position:absolute;	left:30px;	top:146px;	width:170px;	height:20px;	z-index:11;}
		#tabla-escogerBitacora{position:absolute;left:30px;top:190px;width:300px;height:110px;z-index:12;padding:15px;padding-top:0px;}
		#tabla-escogerOT {position:absolute;	left:30px;	top:190px;	width:498px;	height:149px;	z-index:12;	padding:15px;	padding-top:0px;}
		-->
    </style>
</head>
<body>
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-registrar">Registrar Bit&aacute;cora</div><?php


	//verificar que este definido el ID del equipo a mostrar
	if (isset($_GET["id_bit"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente
		$id_bitacora=$_GET["id_bit"];
		//Si en el GET viene definido el valor CANCELAR, entonces procedemos a borrar los archivos que se hayan cargado al servidor, pasando el nombre de la carpeta como parametro
		//El boton de CANCELAR se presiona desde la pantalla siguiente, es decir, la de Agregar Documentacion de Equipos
		if(isset($_GET["cancelar"])&& isset($_SESSION["fotos"]))
		borrarFotos($id_bitacora);		
		
		//Verificamos que el arreglo de documentos no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["fotos"])){
			unset($_SESSION["fotos"]);
		}
		//Verificamos que el arreglo de docTemporal no este declarado, en caso de ser asi, vaciarlo
	}
	/*****************BITÁCORA***********/
	//Verificamos que el arreglo de actividades no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["actividades"])){
		unset($_SESSION["actividades"]);
	}
	//Verificamos que el arreglo de mecanicos no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["mecanicos"])){
		unset($_SESSION["mecanicos"]);
	}
	//Verificamos que el arreglo de materialesMtto no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["valesMtto"])){
		unset($_SESSION["valesMtto"]);
	}
	//Verificamos que el arreglo de regSinValeMtto no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION['regSinValeMtto'])){
		unset($_SESSION["regSinValeMtto"]);			
	}	
	//Verificamos que el arreglo de materialesMtto no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["materialesMtto"])){
		unset($_SESSION["materialesMtto"]);
	}
	//Verificamos que el arreglo de bitacoraPrev no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["bitacoraPrev"])){
		unset($_SESSION["bitacoraPrev"]);
	}
	//Verificamos que el arreglo de bitacoraCorr no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["bitacoraCorr"])){
		unset($_SESSION["bitacoraCorr"]);
	}
	//Verificamos que el arreglo de fotos no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["fotos"])){
		unset($_SESSION["fotos"]);
	}
	//Verificamos que el arreglo de fotos no este declarado, en caso de ser asi, vaciarlo
	if(isset($_SESSION["docTemporal"])){
		unset($_SESSION["docTemporal"]);
	}

	
	//Verificamos el tipo de mantenimiento a seleccionar
	if(isset($_GET["cmb_tipoMtto"])){
		if($_GET["cmb_tipoMtto"]=="preventivo"){?>
        	<fieldset class="borde_seccion" id="tabla-escogerBitacora" name="tabla-escogerBitacora">
			<legend class="titulo_etiqueta">Seleccionar Orden de Trabajo</legend>	
			<form name="frm_elegirOrdenTrabajo" method="post" action="frm_bitacoraMttoPreventivo.php" onsubmit="return valFormRegistrarBitacora(this);">
    		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
                	<td align="center">Orden de Trabajo</td>
					<td><?php 
						$ot = cargarComboOT("cmb_OT","Seleccionar", "");
						if($ot==0)
							echo "<label class='msje_correcto'> No hay Ordenes de Trabajo</label>";?>                    	
					</td>
                </tr>
            	<tr>
					<td colspan="2" align="center"><?php 
						//Si $ot viene = a cero el estado de la orden de trabajo es 1 por lo cual no se pueden mostrar OTs y no se puede presionar continuar
						if($ot==0){ ?>      	    	
							<input name="sbt_continuar" id="sbt_continuar"type="submit" class="botones" value="Continuar" title="Complementar Bit&aacute;cora"
							onmouseover="window.status='';return true" disabled="disabled"/>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php 
						}
						else{?>
							<input name="sbt_continuar" id="sbt_continuar"type="submit" class="botones" value="Continuar" title="Complementar Bit&aacute;cora"
							onmouseover="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php 
						}?>
						<input name="btn_cancelar" id="btn_cancelar"type="button" class="botones" value="Cancelar" 
						title="Regresar a Elegir otro tipo de Mantenimiento" onclick="location.href='frm_registrarBitacora.php'"/>
					</td>
		 		</tr>
			</table>
			</form>
			</fieldset><?php
		}//Cierre de if($_GET["cmb_tipoMtto"]=="preventivo")
		if($_GET["cmb_tipoMtto"]=="correctivo"){
			echo "<meta http-equiv='refresh' content='0;url=frm_bitacoraMttoCorrectivo.php'";				
		}
	}
	
	
    if(!isset($_GET["cmb_tipoMtto"])){
		//Verificamos que el arreglo de materialesMtto no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["valesMtto"])){
			unset($_SESSION["valesMtto"]);
		}?>    
		
        <fieldset class="borde_seccion" id="tabla-escogerBitacora" name="tabla-escogerBitacora">
        <legend class="titulo_etiqueta">Escoger Tipo de Mantenimiento</legend>	
        <br>
        <form name="frm_tipoMtto" >
        <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
              <td><div align="right">Mantenimiento</div></td>
                <td>
                 	<div align="left">
                    	<select name="cmb_tipoMtto" id="cmb_tipoMtto" onChange="javascript:document.frm_tipoMtto.submit();" >
                        	<option selected="selected" value="">Tipo de Matenimiento</option>
                        	<option value="preventivo">PREVENTIVO</option>
                        	<option value="correctivo">CORRECTIVO</option>
                      	</select>
                 	</div>
				</td>
			</tr>
          	<tr>
            	<td colspan="2">
                    <div align="center">       	    	
                        <input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
                        title="Regresar al Men&uacute; Bit&aacute;cora"
                        onclick="location.href='menu_bitacora.php'" onmouseover="window.status='';return true"/>					
                    </div>
                </td>
          	</tr>
        </table>
        </form>
    	</fieldset><?php 
	} //Cierre de  if(!isset($_GET["cmb_tipoMtto"]))?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>