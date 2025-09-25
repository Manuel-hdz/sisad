<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_pruebasMuestras.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
   	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;	width:431px;height:20px;z-index:11;}
		#tabla-agregarPrueba {position:absolute;left:30px;top:190px;width:739px;height:162px;z-index:14;}
		#calendario-fechaProg {position:absolute;left:740px;top:230px;width:30px;height:26px;z-index:13;}
		#pruebasAgregadas {position:absolute;left:31px;top:393px;width:735px;height:190px;z-index:14; overflow:scroll}
		--> 
    </style>
</head>
<body><?php
	
	
	
	//El boton continuar 'sbt_continuar' viene desde el formulario frm_pruebasMuestras.php
	if(isset($_POST['sbt_continuar'])){
		//Guardar los datos de la Muestra en la SESSION
		$_SESSION['datosMuestra'] = array("idMuestra"=>$_POST['rdb_idMuestra']);
	}//FIN if(isset($_POST['sbt_continuar']))
	
	
	
	//Verificamos que se haya pulsado el boton de agregar otro para proceder a cargar los datos en el arreglo de SESSION
	if(isset($_POST['sbt_agregar'])){

		//Esta variable indica si el registro esta repetido o no
		$repetido = 0;
		if(isset($_SESSION['pruebas'])){
			//Verificar que el registro no este repetido en el Arreglo de SESSION
			foreach($_SESSION["pruebas"] as $ind => $registro){
				if($_POST["txt_fechaProg"]==$registro["fechaProg"]){
					$repetido = 1;
					break;
				}
			}
		}
		if($repetido!=1){		
			//Si esta definido el arreglo, añadir el siguiente elemento a el	
			if(isset($_SESSION["pruebas"])){	
				$pruebas[] = array ("fechaProg"=> $_POST["txt_fechaProg"]);
				//Guardar los datos en la SESSION
				$_SESSION["pruebas"] = $pruebas;	
			}				
			else{//Si no esta definido el arreglo, definirlo
				//Crear el arreglo con los datos 
				$pruebas = array(array ("fechaProg"=> $_POST["txt_fechaProg"]));
				//Guardar los datos en la SESSION
				$_SESSION["pruebas"] = $pruebas;
			}
		}//FIN if($repetido!=1)
		else{?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('Registro Repetido')", 500);
			</script>
			<?php
		}	
	}//FIN if(isset($_POST['sbt_agregar']))
	
	
	
	//Si viene en el get el boton finalizar proceder a guardar la informacion almacenada
	if(isset($_GET['btn_finalizar']))
		guardarPruebasMuestras();
	
	
	//Recuperar la clave de la Muestra de los datos Guardados en la SESSION	
	if(isset($_SESSION['datosMuestra']))	
		$claveMuestra = $_SESSION['datosMuestra']['idMuestra'];	
	else
		$claveMuestra = ""; 
		
	//Obtener la Fecha de Colado de la muestra y el tipo de Prueba
	$fechaColado = obtenerDato("bd_laboratorio", "muestras", "fecha_colado", "id_muestra", $claveMuestra); 
	$tipoPrueba = obtenerDato("bd_laboratorio", "muestras", "tipo_prueba", "id_muestra", $claveMuestra);?>
		
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Programar Fechas de Realizaci&oacute;n de Pruebas</div>
	
	<fieldset class="borde_seccion" id="tabla-agregarPrueba" name="tabla-agregarPrueba">
	<legend class="titulo_etiqueta">Ingrese las Fechas para las Pruebas a Realizar</legend>	
	<br>
	<form onSubmit="return valFormProgPruebasMuestras(this);" name="frm_progPruebasMuestras" method="post" action="frm_pruebasMuestras2.php">
    <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="20%"><div align="right">Id de Muestra</div></td>
            <td width="40%"><input type="text" name="txt_idMezcla" id="txt_idMezcla" value="<?php echo $claveMuestra; ?>" readonly="readonly" size="53"/></td>
            <td width="20%" align="right">*Fecha Programada</td>
            <td width="20%">
				<input name="txt_fechaProg" id="txt_fechaProg" type="text" class="caja_de_texto" size="10"value="<?php echo date("d/m/Y");?>" 
                readonly="readonly"/>            
			</td>
        </tr>
		<tr>
			<td align="right">Tipo de Prueba</td>
			<td><input type="text" name="txt_tipoPrueba" class="caja_de_texto" readonly="readonly" value="<?php echo $tipoPrueba; ?>" size="25" /></td>
			<td align="right">Fecha Colado</td>
			<td><input type="text" name="txt_fechaColado" class="caja_de_texto" readonly="readonly" value="<?php echo modFecha($fechaColado,1); ?>" size="10" /></td>
		</tr>
		<tr>
			<td colspan="4"><div align="center">
				<input type="hidden" name="hdn_fecha" id="hdn_fecha" value="<?php echo date("d/m/Y");?>"/><?php					
				if (isset($_SESSION['pruebas'])){?>
					<input name="btn_finalizar" type="button" class="botones" id="btn_finalizar"  value="Finalizar" title="Finalizar Registro de Pruebas" 
					onmouseover="window.status='';return true" onclick="location.href='frm_pruebasMuestras2.php?btn_finalizar'"/>
					&nbsp;&nbsp;&nbsp;<?php
				}?>
				<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar otra Prueba" 
				onmouseover="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Mezclas " 
				onmouseover="window.status='';return true" onclick="confirmarSalida('menu_mezclas.php');"/></div>			</td>   	
		</tr>        
	</table>
	</form>
	</fieldset>	
	
    <div id="calendario-fechaProg">
        <input type="image" name="txt_fechaProg" id="txt_fechaProg" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_progPruebasMuestras.txt_fechaProg,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha Programada"/> 
	</div><?php
	
	
	
	//Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados
	if(isset($_SESSION['pruebas'])){?>
		<div id='pruebasAgregadas' class='borde_seccion2'><?php
			mostrarPruebasAdd();?>
		</div>
		<?php
	}?>		
	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>