<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php


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
		include ("op_modificarMezcla.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>	
    <script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:210px;height:20px;z-index:11;}
		#tabla-modificarMezclaFecha {position:absolute;left:40px;top:190px;width:360px;height:151px;z-index:12;}
		#tabla-modificarMezclaClave {position:absolute;left:478px;top:190px;width:369px;height:151px;z-index:13;}
		#calendario-Ini {position:absolute;left:277px;top:232px;width:30px;height:26px;z-index:14;}
		#calendario-Fin {position:absolute;left:277px;top:270px;width:30px;height:26px;z-index:15;}
		#detalle_mezcla {position:absolute;left:40px;top:371px;width:906px;height:177px;z-index:16;overflow:scroll;}
		#btn-modificar {position:absolute;left:47px;top:600px;width:946px;height:40px;z-index:18;}
		
		#titulo-agregar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarMezcla {position:absolute; left:30px; top:190px; width:780px; height:250px; z-index:12;}
		#div-calendario { position:absolute; left:735px; top:305px; width:30px; height:26px; z-index:13; }
		
		#detalle_materiales {position:absolute;left:40px;top:191px;width:906px;height:347px;z-index:12;overflow:scroll;}
		#btns {position:absolute;left:47px;top:600px;width:946px;height:40px;z-index:13;}
		
		#tabla-agregarMezcla2 {position:absolute;left:30px;top:190px;width:914px;height:240px;z-index:14;}
		
		#materialesAgregados {position:absolute;left:32px;top:474px;width:914px;height:161px;z-index:12;overflow:scroll}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Modificar Mezclas</div><?php
	
	//Cuando el boton de modificar Mezcla sea presionado, no mostrar nada y esperar a que la pagina sea redireccionada a la pantalla de Exito
	if(!isset($_POST["sbt_modificar"])){
		
		//Si presionamos continuar guardar el id de la mezcla en la session
		if(isset($_POST['sbt_continuar']) && !isset($_POST['sbt_eliminar'])){	
			if(isset($_POST["sbt_continuar"])&&(!isset($_POST['sbt_regresarMod']))){
				unset($_SESSION['datosMezcla']);
			}
			
			//Guardar en la SESSION el ID de la Mezcla seleccionada para ser editada
			if(!isset($_SESSION['datosMezcla'])){
				$idmezcla = array("idMezcla"=>$_POST['rdb']);
				//Guardar los datos en la SESSION
				$_SESSION['datosMezcla'] = $idmezcla; 	
			}
			//Mostrar los datos de la Mezcla en el formulario para su modificacion
			modificarMezclaSeleccionada();				
		}//if(isset($_POST['sbt_continuar']) && !isset($_POST['sbt_eliminar']))
		
			
		//Llamar la funcion que permite eliminar el material seleccionado
		if (isset($_POST['sbt_eliminar'])){
			eliminarMaterialSeleccionado();
		}
		
		//Si esta se ha presionado el boton finalizar proceder a guardar los datos de los materiales almacenados en la SESSION en la BD de Laboratorio
		if(isset($_POST['sbt_finalizarMat'])){
			guardarMateriales();
		}
		
		 
		
		//Obtener la fecha del sistema para la fecha inicio y fecha fin
		$txt_fechaIni = date("d/m/Y", strtotime("-30 day"));
		$txt_fechaFin = date("d/m/Y");
		
		//Verificar las variables disponibles para mostrar los elementos correspondientes		
		if(!isset($_GET['btn_agregar']) && !isset($_POST['sbt_agregar'])){
			
			if(!isset($_POST['btn_modComponentes'])  && !isset($_POST['sbt_eliminar']) && !isset($_POST['sbt_finalizarMat'])){			
				if (!isset($_POST['sbt_continuar'])){
					
					//Mostrar el formulario para buscar las mezclas por ID de la Mezcla?>	
					<fieldset class="borde_seccion" id="tabla-modificarMezclaClave" name="tabla-modificarMezclaClave">
					<legend class="titulo_etiqueta">Modificar  por Clave</legend>	
					<br>
					<form onSubmit="return valFormModificarMezclaClave(this);" name="frm_modificarMezcla" method="post" action="frm_modificarMezcla.php">
					<table width="371" height="132" cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="140" height="50"><div align="right">Seleccione Id Mezcla </div></td>
							<td width="194"><?php 
								$result = cargarComboEspecifico("cmb_claveMezcla","id_mezcla","mezclas","bd_laboratorio","1","estado","Mezcla","");
								if($result==0){
									echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Mezclas Registradas</label>";
								}?>            
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><?php 
								if($result==1){?>
									<input name="sbt_consultar2" type="submit" class="botones" value="Continuar" onmouseover="window.status='';return true" 
									title="Consultar Mezclas" />
									&nbsp;&nbsp;&nbsp;<?php 
								}?>
								<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_mezclas.php';"
								title="Regresar al men&uacute; de Mezclas"/>
							</td>
						</tr>
					</table>
					</form>
					</fieldset>
					
					<?php //Mostrar el formulario para buscar las Mezclas por Fecha de Registro?>	
					<fieldset class="borde_seccion" id="tabla-modificarMezclaFecha">
					<legend class="titulo_etiqueta">Modificar por Fecha de Registro</legend>	
					<br>
					<form onSubmit="return valFormModificarMezclaFecha(this);" name="frm_modificarMezcla2" method="post" action="frm_modificarMezcla.php">
					<table width="372" height="36" cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="106"><div align="right">Fecha Inicio</div></td>
							<td width="229"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10"value="<?php echo $txt_fechaIni;?>" 
								readonly="readonly"/>
							</td>
						</tr>
						<tr>
							<td><div align="right">Fecha Fin </div></td>
							<td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaFin;?>" 
								readonly="readonly"/>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<input name="sbt_consultar" type="submit" class="botones" value="Continuar" onmouseover="window.status='';return true" 
								title="Consultar Mezclas"/>
								&nbsp;&nbsp;&nbsp;
								<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_mezclas.php';"
								title="Regresar al men&uacute; de Mezclas"/>
							</td>
						</tr>
					</table>
					</form>   
					</fieldset>
					
					<div id="calendario-Ini">
					  <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
						onclick="displayCalendar(document.frm_modificarMezcla2.txt_fechaIni,'dd/mm/yyyy',this)" 
						onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
						title="Seleccionar Fecha de Inicio"/> 
					</div>
					
					<div id="calendario-Fin">
					  <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
						onclick="displayCalendar(document.frm_modificarMezcla2.txt_fechaFin,'dd/mm/yyyy',this)" 
						onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
						title="Seleccionar Fecha de Fin"/> 
					</div><?php
					
					
					
					
					//Mostras la(s) Mezcla(s) de acuerdo al ID seleccionado o al periodo de fechas de registro seleccionado 
					if(isset($_POST["sbt_consultar2"]) || isset($_POST['sbt_consultar']) || isset ($_POST['sbt_continuar'])){?>
						<form onSubmit="return valFormModificarMezcla(this);" name="frm_modificarMezcla" method="post" action="frm_modificarMezcla.php"><?php
							if(isset($_POST["sbt_consultar2"])){?>
								<input type="hidden" name="cmb_claveMezcla" value="<?php echo $_POST['cmb_claveMezcla'];?>" />
								<input type="hidden" name="sbt_consultar2" value="Consultar"/><?php
							 }
							if(isset($_POST["sbt_consultar"])){?>
								<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>" />
								<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>" />
								<input type="hidden" name="sbt_consultar" value="Consultar" /><?php
							 }?>
							<div id='detalle_mezcla' class='borde_seccion2' align="center"><?php
								//Mostrar los datos de las Mezclas de acuerdo a los parametros de busqueda seleccionados (Fecha o ID de la Mezcla)
								$control = mostrarMezclas(); ?>
							</div><?php
							//Verificar si el resultado de la busqueda arroja resultados para mostrar el boton de modificar
							if ($control==1){?>
								<div id='btn-modificar' align="center">
									<input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar" value="Modificar" 
									onmouseover="window.status='';return true" title="Modificar Mezcla Seleccionada" />
								</div><?php
							 }?>
						</form><?php
					}//Cierre if(isset($_POST["sbt_consultar2"]) || isset($_POST['sbt_consultar']) || isset ($_POST['sbt_continuar']))				
				}//fin 	if (!isset($_POST['sbt_continuar']))			
			}//fin 	if(!isset($_POST['btn_modComponentes'])  && !isset($_POST['sbt_eliminar']) && !isset($_POST['btn_agregar']))
			
				
					
			//Mostraer los materiales registrrados en la Mezcla seleccionada para ser Editada
			if (isset($_POST['btn_modComponentes']) || isset($_POST['sbt_finalizarMat']) || isset($_POST['sbt_eliminar'])){?>
				<form onSubmit="return valFormModificarMat(this);" name="frm_modificarMezcla" method="post" action="frm_modificarMezcla.php">
					
					<input type="hidden" name="txt_idMezcla" value="<?php echo $_POST['txt_idMezcla'];?>"/><?php
					
					if(isset($_POST["sbt_consultar2"])){ ?>
						<input type="hidden" name="cmb_claveMezcla" value="<?php echo $_POST['cmb_claveMezcla'];?>" />
						<input type="hidden" name="sbt_consultar2" value="Consultar"/>
						<input type="hidden" name="sbt_continuar" value="Continuar"/><?php
					}
					if(isset($_POST["sbt_consultar"])){ ?>
						<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>" />
						<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>" />
						<input type="hidden" name="sbt_consultar" value="Consultar" />
						<input type="hidden" name="sbt_continuar" value="Continuar" /><?php
					}
					//Colocar estas variables cuando se regresa de a pagina de agregar un nuevo material a la Mezcla que esta siendo modificada
					if(isset($_POST['sbt_regresarFin'])){?>  
						<input type="hidden" name="sbt_consultar2" value="Consultar" />
						<input type="hidden" name="sbt_continuar" value="Continuar" /><?php 
					}
					//Colocar estas variables al terminar de agregar los datos de los materiales registrados en la SESSION
					if(isset($_POST["sbt_finalizarMat"])){ ?>
						<input type="hidden" name="sbt_consultar2" value="Consultar" />
						<input type="hidden" name="sbt_continuar" value="Continuar" /><?php
					}
					
					//Mostrar los materiales registrados en la Mezcla seleccionada?>				
					<div id='detalle_materiales' class='borde_seccion2' align="center"><?php					
						$control = mostrarMatMezcla();?>
					</div><?php
					
					
					//Verificar si el resultado de la busqueda arroja resultados para mostrar el boton de modificar?>
					<div id='btns' align="center">
						<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="" />
						<input name="btn_agregar" type="button" class="botones" id="btn_agregar" value="Agregar" 
						onmouseover="window.status='';return true" title="Agregar nuevo Material" 
						onclick="location.href='frm_modificarMezcla.php?btn_agregar'"/>
						&nbsp;&nbsp;&nbsp;<?php
						if ($control==1){?>
							<input name="sbt_eliminar" type="submit" class="botones" id="sbt_eliminar" value="Eliminar" 
							onmouseover="window.status='';return true" title="Eliminar el Material Seleccionado" />
							&nbsp;&nbsp;&nbsp;<?php
						}?>					
						<input name="sbt_regresarMod"  id="sbt_regresarMod" type="submit" class="botones" value="Regresar"
						title="Regresar a Modificar Mezcla" onclick="hdn_botonSel.value='regresar'"/>					
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
						title="Cancelar y Regresar al Men&uacute; de Mezclas " onmouseover="window.status='';return true" 
						onclick="confirmarSalida('menu_mezclas.php');"/>
					</div>
				</form><?php
			}//Cierre if (isset($_POST['btn_modComponentes']) || isset($_POST['sbt_eliminar']))		
		}//FIN if(!isset($_POST['btn_agregar']))
		
		
		
		//Agregar información de los materiales en la SESSION, los cuales están siendo agregados a la Mezcla que esta siendo editada
		else if(isset($_GET['btn_agregar']) || isset($_POST['sbt_agregar'])){
			//Verificamos que se haya pulsado el boton de agregar otro para proceder a cargar los datos en el arreglo de sesion
			if(isset($_POST['sbt_agregar'])){				
				//Verificar que el registro no este duplicado 	
				$repetido = 0;	
				if(isset($_SESSION["materiales"])){			
					foreach($_SESSION["materiales"] as $ind => $registro){
						if($_POST["cmb_nombre"]==$registro["claveMat"]){
							$repetido = 1;
							break;
						}
					}
				}
				
				
				//Verificamos la existencia del nombre para que regrese el id del material
				//Verificamos que el agregado no se encuentre ya en la mezcla.
				$idMezcla = $_SESSION['datosMezcla']['idMezcla'];
				
				
				//Realizar la conexion a la BD
				$conn = conecta("bd_laboratorio");
			
				//Creamos la sentencia SQL
				$stm_sql ="SELECT COUNT(catalogo_materiales_id_material) as num FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$idMezcla' 
								AND catalogo_materiales_id_material='$_POST[cmb_nombre]'";
			
	
				//Ejecutamos la sentencia SQL
				$rs = mysql_query($stm_sql);
				if($datos = mysql_fetch_array($rs)){
					$repetidoMaterial=$datos['num'];
				}
				
								
				//Si el registro no esta 
				if($repetido==0&&$repetidoMaterial==0){
					//Si esta definido el arreglo, añadir el siguiente elemento a el	
					$materiales[] = array("claveMat"=>$_POST['cmb_nombre'], "categoria"=>$_POST['cmb_categoria'], "cantidad"=>$_POST['txt_cantidad'], 
											"unidad"=>strtoupper($_POST['txt_unidadMedida']));
					//Guardar los datos en la SESSION
					$_SESSION['materiales'] = $materiales;	
				}				
				else{?>
					<script language="javascript" type="text/javascript">
						setTimeout("alert('El Agregado ya se encuentra incluido en la Mezcla');", 1000);
					</script><?php
				}
			}
			//Mandar llamar la funcion que nos permite agregar materiales a la mezcla seleccionada
			agregarMatMezcla();
		} //Cierre else if(isset($_POST['btn_agregar']))
		
		
	}//Cierre if(!isset($_POST["sbt_modificar"]))?>
	
	
</body><?php 
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>