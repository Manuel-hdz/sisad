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
		
		include ("op_registrarHoroOdometro.php");	

?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
   	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#titulo-registrarHorometro { position:absolute;left:30px;top:146px;width:215px;height:20px;z-index:11;}
		#tabla-registrarHorometro {position:absolute;left:30px;top:190px;width:833px;height:189px;z-index:2;}
		#tabla-registo { position:absolute; left:0px; top:270px; width:960px; height:190px;	z-index:3; overflow:scroll;}
		#titulo-tabla {	position:absolute; left:-10px; top:216px;	width:960px; height:36px; z-index:8;}
		#btns-reglimcan { position:absolute; left:0px; top:470px; width:960px; height:40px; z-index:4;}
		#calendario {position:absolute;left:439px;top:119px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>
	<?php //Evitar que la variable $cmb_area marque un error por no estar definida			
    if(!isset($_POST['cmb_area']))
		$cmb_area = "";

	$txt_fecha=date("d/m/Y");
	
		/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
		$atributo = "";
		$area = "";
		$estado = 1;//El estado 1 Indica que el usuario con la SESSION abierta es AuxMtto
		if($_SESSION['depto']=="MttoConcreto"){
			$area = "CONCRETO";
			$atributo = "disabled='disabled'";
			$estado = 0;
		}
		else if($_SESSION['depto']=="MttoMina"){
			$area = "MINA";
			$atributo = "disabled='disabled'";
			$estado = 0;
		}

	//Codigo que nos permite recuperar los valores preseleccionados en cada uno de los combos?> 
	
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrarHorometro">Registrar Hor&oacute;metros </div>        
    <fieldset class="borde_seccion" id="tabla-registrarHorometro" name="tabla-registrarHorometro">
    <legend class="titulo_etiqueta">Seleccionar el Equipos para hacer el Registro</legend>	
    <br>
    <form onSubmit="" name="frm_registrarHorometro" method="post" action="frm_registrarHorometro.php" >
    <table width="828"  cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="27" height="55"><div align="right">&Aacute;rea</div></td>
            <td width="247">
				<?php if($estado==1) {?>
                    <select name="cmb_area" class="combo_box" 
                        onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');">
                        <option value="">&Aacute;rea</option>						
                        <option <?php if($cmb_area=="CONCRETO") echo "selected='selected'"; ?> value="CONCRETO">CONCRETO</option>
                        <option <?php if($cmb_area=="MINA") echo "selected='selected'"; ?> value="MINA">MINA</option>
                    </select>
				<?php } 
                else { ?>
                    <select name="cmb_area" class="combo_box" 
                        onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');" 
                        <?php echo $atributo; ?>>
                        <option value="">&Aacute;rea</option>						
                        <option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
                        <option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
                    </select>		
                    <input type="hidden" name="cmb_area" value="<?php echo $area; ?>" />
                <?php } ?>            
            </td>
            <td width="42"><div align="right">Familia</div></td>
            <td width="445">
               <?php 
				$val = cargarComboBicondicional("cmb_familia","familia","equipos","bd_mantenimiento",$area,"area","ACTIVO","estado","Familia","","javascript:document.frm_registrarHorometro.submit()");
				if($val==0){?>
					<input type="hidden" name="cmb_familia" id="cmb_familia" value=""/>
					<label class='msje_correcto'> No hay Familias Registradas</label>
				<?php }?>
            </td>
        </tr>
	</table>
    </form>
	
    <form onSubmit="return valFormHoro(this);" name="frm_registrarHorometroEquipo" method="post" action="op_registrarHoroOdometro.php" >  
    <table width="830"  cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>   
            <td width="32"><div align="right">Turno</div></td>
            <td width="313"><select name="cmb_turno" id="cmb_turno">
      		<?php //Evitar que la variable $cmb_turno marque un error por no estar definida			
        	if(!isset($_POST['cmb_turno']))
            	$cmb_turno = "";?>
                    <option selected="selected" value="">Turno</option>
      				<option <?php if($cmb_turno=='TURNO DE PRIMERA') echo "selected='selected'"?> value="TURNO DE PRIMERA">TURNO DE PRIMERA</option>
                  	<option <?php if($cmb_turno=='TURNO DE SEGUNDA') echo "selected='selected'"?> value="TURNO DE SEGUNDA">TURNO DE SEGUNDA</option>
                    <option <?php if($cmb_turno=='TURNO DE TERCERA') echo "selected='selected'"?> value="TURNO DE TERCERA">TURNO DE TERCERA</option>
                </select>
          </td>
                <td width="73"><div align="right">Fecha</div></td>
                <td width="146"><input type="text" name="txt_fechaHorometro" id="txt_fechaHorometro" size="10" class="caja_de_texto" 
                    readonly="readonly" value="<?php echo $txt_fecha;?>"/></td> 
                <td width="210"><div align="right">Observaciones</div></td>
                <td width="363">
              <textarea name="txa_comentarios" id="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto"
                    rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);" ></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="6" align="center">
                <input name="btn_regresar"  type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Equipos" 
				onclick="location.href='menu_metricas.php';"/>
            </td>
        </tr>      
    </table><?php
    
	 if(isset($_POST['cmb_familia'])){?>
        <div id="titulo-tabla" align="center"> 
             <?php // Llamada a la funcion donde solo se desplega el titulo de la tabla
			 mostrarTituloHorometro(); ?>
        </div>
        <div id="tabla-registo" align="center"> 
             <?php // Llamada que despliega el resultado de la consulta 
			 $result = mostrarEquiposHorometro(); ?>
        </div><?php
	 }?>
    
    <div id="btns-reglimcan" align="center">
       	<?php $nom_boton = "Regresar";
		if(isset($_POST['cmb_familia'])){
			$nom_boton = "Cancelar";?>
	        <input name="sbt_registrar" type="submit" class="botones"  value="Registrar" title="Registrar Horometros" onmouseover="window.status='';return true"
			<?php if(!$result){ ?> disabled="disabled" <?php }?> />
            &nbsp;&nbsp;&nbsp;
            <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onclick="desabilitar('HORO');"  
            onmouseover="window.status='';return true"/>
            &nbsp;&nbsp;&nbsp;
            <input name="btn_regresar" type="button" class="botones"  value="Cancelar" title="Cancelar y Regresar al Men&uacute; Equipos" 
            onclick="confirmarSalida('menu_metricas.php')"/><?php
        }?>
    </div>  
    </form>
    <div id="calendario">
      <input type="image" name="txt_fechaHorometro" id="txt_fechaHorometro" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_registrarHorometroEquipo.txt_fechaHorometro,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Fin"/> 
    </div>	
</fieldset>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>