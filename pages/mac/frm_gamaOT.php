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
		include ("op_generarOrdenTrabajo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#titulo-gamaOT {position:absolute;left:30px;top:146px;width:323px;height:20px;z-index:11;}
		#tabla-gamaOT {position:absolute;left:30px;top:190px;width:513px;height:70px;z-index:12;padding:15px;padding-top:0px;}
		#agregados{position:absolute;width:462px;height:148px;z-index:14;left:86px;top:320px;}		
		#btns{position:absolute;width:533px;height:42px;z-index:15;left:40px;top:506px;}		
		-->
    </style>
</head>
<body><?php 
		
	//Obtener datos de la SESSION para asociar las gamas a la Oren de Trabajo que se esta creando
	$metrica = $_SESSION['datosOT']['metrica']; // para desplegar el titulo del combo segun la metrica con la que cuente el equipo
	$area = $_SESSION['datosOT']['area'];
	$familia = $_SESSION['datosOT']['familia'];
	// declarar la variable para evitar que marque error
	$msg_tabla= "";
			
	if(!isset($_POST['btn_continuar'])){ 
					
		//Verificamos que se haya pulsado el boton de agregar otro para proceder a cargar los datos en el arreglo de sesion
		if(isset($_POST['sbt_agregar'])){
			//Si ya esta definido el arreglo $gamasOT, entonces agregar el siguiente registro a el
			if (isset($_SESSION['gamasOT'])){
				if(!in_array($cmb_metrica,$_SESSION['gamasOT'])){
					//Obtener el nombre de la Gama
					$nomGama = obtenerDato("bd_mantenimiento", "gama", "nom_gama", "id_gama", $_POST['cmb_metrica']);
				
					//Guardar los datos en la SESSION
					$_SESSION['gamasOT'][] = array ("id_gama"=>$_POST['cmb_metrica'], "nom_gama"=>$nomGama);					
				}	
				else
					$msg_tabla = "La gama ya esta agregada";//En el caso de que la gama ya este registrada, desplegar mensaje
			}			
			else if (!isset($_SESSION['gamasOT'])){//Si no esta definido el arreglo, definirlo
				//Obtener el nombre de la Gama
				$nomGama = obtenerDato("bd_mantenimiento", "gama", "nom_gama", "id_gama", $_POST['cmb_metrica']);
				
				//Guardar los datos en la SESSION
				$_SESSION['gamasOT'] = array(array("id_gama"=>$_POST['cmb_metrica'], "nom_gama"=>$nomGama));	
				$msg_tabla= "";
			}
		}//Fin del if(isset($_POST['btn_agregar']))
		
		
		//Eliminar una Gama del Registro
		if(isset($_POST['sbt_eliminarGama']) && isset($_POST['rdb_gama'])){												
			$cmb_metrica = $_SESSION['gamasOT'][$_POST['rdb_gama']] [$_POST['rdb_gama']];
			//Eliminar una gama del registro 
			unset($_SESSION['gamasOT'][$_POST['rdb_gama']]);
			//Desplegar mensaje cuando se elimina una Actividad del Registro
			$msg_tabla = "La gama fue eliminada";	
			if (count($_SESSION['gamasOT'])==0)	
				unset ($_SESSION['gamasOT']);	
		}?>    
					
        <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
        <div class="titulo_barra" id="titulo-gamaOT">Agregar Gamas a la Orden de Trabajo</div>
            
        <fieldset class="borde_seccion" id="tabla-gamaOT" name="tabla-gamaOT">
        <legend class="titulo_etiqueta">Agregar Gamas a la Orden de Trabajo</legend>
        <br>
        <form onSubmit="return valFormGamaOT(this);" name="frm_gamaOT" method="post" action="frm_gamaOT.php">        
        <table cellpadding="5" cellspacing="5" class="tabla_frm">	
            <tr>
                <td>Seleccionar Gama</td>
                <td><?php					
					//Desplegar las Gamas registradas de acuerdo al area y familia del equipo	
					$conn = conecta("bd_mantenimiento");
					$result=mysql_query("SELECT id_gama, nom_gama FROM gama WHERE  area_aplicacion= '$area' AND familia_aplicacion= '$familia' ;");?>
	                <select name="cmb_metrica" id="cmb_metrica" class="combo_box">
                        <option value="">Gama <?php echo $metrica;?></option><?php
                        while ($row=mysql_fetch_array($result)){?>
							<option value="<?php echo $row['id_gama'];?>" title="<?php echo $row['id_gama']?>"><?php echo $row['nom_gama']?></option><?php
						}
                        //Cerrar la conexion con la BD		
                        mysql_close($conn);?>
					</select>
                </td>                
                <td>			
                	<input name="sbt_agregar" type="submit" class="botones" value="Agregar" title="Agregar Gama Seleccionada" 
						onmouseover="window.status='';return true" />              
                </td>
            </tr>
        </table>
        </form>
        </fieldset>
        
		<form onsubmit="return valFormTablaGamas(this);" name="frm_tablaGamas" method="post" action="frm_gamaOT.php"><?php
        //Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados
        if(isset($_SESSION['gamasOT'])){?>
            <div id="agregados" name="agregados" align="center">
                <?php gamasAgregadas($msg_tabla);?>
            </div><?php
        }?>
        
        <div id="btns" name="btns" align="center">
        	<?php if(isset($_SESSION['gamasOT'])){?> 
                <input name="btn_continuar" type="button" class="botones"  value="Continuar" title="Continuar Orden de Trabajo" 
                onmouseover="window.status='';return true" onclick="location.href='frm_generarOrdenTrabajo.php'"/>
            <?php }?>
            &nbsp;&nbsp;&nbsp;
            <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar el registro de Gamas" 
            onmouseover="window.status='';return true" onclick="confirmarSalida('frm_generarOrdenTrabajo.php?cancelar=si');" />
            &nbsp;&nbsp;&nbsp;
            <input type="submit" name="sbt_eliminarGama" value="Eliminar Gama" class="botones" title="Eliminar Gama Seleccionada"
            onmouseover="window.status='';return true" <?php if(!isset($_SESSION['gamasOT'])){ ?>
            disabled="disabled" <?php } ?>/>
        </div>
		</form><?php
	}//Cierre 	if(!isset($_POST['btn_continuar'])){ 
    else {
		//Redireccionar a la Pagina de generar vale mantenimiento
		echo "<meta http-equiv='refresh' content='0;url=frm_generarOrdenTrabajo.php'>"; 
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>