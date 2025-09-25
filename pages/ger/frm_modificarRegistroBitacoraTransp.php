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
		include ("op_modificarRegistroBitacoraTransp.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>  
	<script type="text/javascript" src="includes/ajax/cargarComboGT.js"></script>  
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>     
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:230px;height:20px;z-index:11;}
		#tabla-modificarRegistro {position:absolute;left:30px;top:190px;width:533px;height:150px;z-index:14;}
		#mostrarBit {position:absolute;left:32px;top:191px;width:914px;height:424px;z-index:12;overflow:scroll}
		#btnReg {position:absolute;left:30px;top:665px;width:914px;height:55px;z-index:12;}
		#calendario_fechaRegistro {position:absolute;left:555px;top:232px;width:30px;height:27px;z-index:4;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Registro de la Bit&aacute;cora</div><?php

	if (isset ($_POST['sbt_eliminar'])){
		eliminarRegSeleccionado();
	}
    
	if(isset($_POST['sbt_continuar']) || isset($_GET['txt_fecha'])){?>
	    <form onSubmit="return valFormEliminarRegTrans(this);" name="frm_modificarRegistroBitacoraTransp" method="post" action="frm_modificarRegistroBitacoraTransp.php"><?php 
        	if(isset($_GET['cmb_ubicacion']) || isset($_GET['cmb_periodo']) || isset($_GET['cmb_cuadrilla'])){?>
            	<input type="hidden" name="cmb_ubicacion" value="<?php echo $_GET['cmb_ubicacion'];?>"/>
                <input type="hidden" name="txt_fecha" value="<?php echo $_GET['txt_fecha'];?>"/>
				<input type="hidden" name="sbt_continuar" value="Continuar"/><?php
				$ubicacion= $_GET['cmb_ubicacion'];
				$fecha=$_GET['txt_fecha'];
            }
			else{?>
            	<input type="hidden" name="cmb_ubicacion" value="<?php echo $_POST['cmb_ubicacion'];?>"/>
                <input type="hidden" name="txt_fecha" value="<?php echo $_POST['txt_fecha'];?>"/>
		        <input type="hidden" name="sbt_continuar" value="Continuar"/><?php
				$ubicacion= $_POST['cmb_ubicacion'];
				$fecha=$_POST['txt_fecha'];
				
            }?>    
            <div id='mostrarBit' class='borde_seccion2'><?php
                $result=mostrarRegBitacoraTrans();?>
            </div>
            <div id='btnReg' align="center"><?php
                if($result==1){?>
				<input type="hidden" name="hdn_accion" id="hdn_accion" value=""/>
					<input name="sbt_agregar" type="submit" class="botones" value="Modificar" title="Modificar Registro Seleccionado" 
					onmouseover="window.status='';return true" 
					onclick="hdn_accion.value='Modificar';document.frm_modificarRegistroBitacoraTransp.action='frm_modificarBitTransporte.php?mod=si&ubicacion=<?php echo $ubicacion;?>&fecha=<?php echo $fecha;?>';"/>   
					&nbsp;&nbsp;&nbsp;
                    <input name="sbt_eliminar" type="submit" class="botones" id="sbt_eliminar"  value="Eliminar" title="Eliminar el Registro Seleccionado" 
                    onmouseover="window.status='';return true" onclick="hdn_accion.value='Eliminar';document.frm_modificarRegistroBitacoraTransp.action='frm_modificarRegistroBitacoraTransp.php';"/><?php
                }?>
                 &nbsp;&nbsp;&nbsp;
                <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar" 
                onMouseOver="window.status='';return true" onclick="location.href='frm_modificarRegistroBitacoraTransp.php';"/>
          </div>
		</form><?php
	}	//FIN if(isset($_POST['sbt_continuar']) || isset($_GET['txt_fecha']))
	
	if(!isset($_POST['sbt_continuar']) && !isset($_GET['cmb_ubicacion'])){?>
        <fieldset class="borde_seccion" id="tabla-modificarRegistro" name="tabla-modificarRegistro">
        <legend class="titulo_etiqueta">Ingresar la Informaci&oacute;n del Registro</legend>	
        <br>
        <form onSubmit="return valFormModRegBitTransp(this);" name="frm_modificarRegistroBitacora" method="post" action="frm_modificarRegistroBitacoraTransp.php">
        <table width="538" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
              <td width="69"><div align="right">Ubicaci&oacute;n</div></td>
                <td width="156"><?php
					//Cargar las ubicaciones disponibles, a partir de esta seleccion, obtener las cuadrillas y los periodos registrados a esta ubicación
					$result=cargarComboConId("cmb_ubicacion","ubicacion","id_ubicacion","catalogo_ubicaciones","bd_gerencia","Ubicaci&oacute;n","","");
					
					if($result==0){
						echo "<label class='msje_correcto'> No hay Ubicaciones Registradas</label>
						<input type='hidden' name='cmb_ubicacion' id='cmb_ubicacion'/>";
					}?>				</td>
              <td width="135"><div align="right">Fecha Registro</div></td>
            	<td width="111">
                    <input type="text" name="txt_fecha" id="txt_fecha" value="<?php echo date("d/m/Y");?>" class="caja_de_texto" readonly="readonly" size="10"/>              
			  </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div align="center">
                        <input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar"  value="Continuar" title="Continuar" 
                        onmouseover="window.status='';return true"/>
                        &nbsp;&nbsp;&nbsp;
                        <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Pantalla Anterior" 
                        onMouseOver="window.status='';return true" onclick="location.href='frm_selRegistroBitacoraMod.php';"/>
                    </div>                </td>
            </tr>
        </table>
        </form>
</fieldset>
	<div id="calendario_fechaRegistro">
          <input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_modificarRegistroBitacora.txt_fecha,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Registro"/>
</div><?php
	}//if(!isset($_POST['sbt_continuar']) && !isset($_GET['cmb_ubicacion']))?>
</body>

<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>