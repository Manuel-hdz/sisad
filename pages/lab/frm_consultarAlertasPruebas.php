<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js"></script>	
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:350px; z-index:12; overflow:scroll}
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:22px; z-index:11; }
		#btns-regpdf { position:absolute; left:37px; top:596px; width:956px; height:67px; z-index:11; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Mezclas Pr&oacute;ximas a Probarse </div><?php 
	
	if(!isset($_POST['rdb_prueba'])) { 	
		//Conectarse con la BD de Laboratorio
		$conn = conecta("bd_laboratorio");		
				
		//Obtener las alertas registradas en la tabla de alertas_prueba cuyo estado sea 1(Alertas no atendidas)
		$stm_sql = "SELECT * FROM alertas_prueba WHERE estado = 1";?>	
		
		<form name="frm_resultadosPruebas" action="frm_consultarAlertasPruebas.php" method="post" onsubmit="return valFormValidarAlertaPruebas(this);">
		<div  class="borde_seccion2"id="form-datos-alertas">
		<table border="0" align="center" cellpadding="5" class="tabla_frm">
			<caption class='titulo_etiqueta'>Seleccionar Mezcla para Registrar Resultados</caption>					
			<tr>
				<td align="center" class="nombres_columnas">SELECCIONAR</td>
				<td align="center" class="nombres_columnas">ClAVE MUESTRA</td>
				<td align="center" class="nombres_columnas">MEZCLA</td>
				<td align="center" class="nombres_columnas">FECHA COLADO</td>
				<td align="center" class="nombres_columnas">FECHA PROGRAMADA</td>
			</tr><?php 				
				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta de datos fue realizada con exito.
				if($datos=mysql_fetch_array($rs)){					
					$nom_clase = "renglon_gris";
					$cont = 1;											
					do{
						//Obtener los datos de la muestra que seran mostrados				
						$datosAlerta = mysql_fetch_array(mysql_query("SELECT id_muestra, fecha_colado, fecha_programada, nombre 
						FROM (plan_pruebas JOIN muestras ON muestras_id_muestra=id_muestra) JOIN mezclas ON mezclas_id_mezcla = id_mezcla 
						WHERE id_plan_prueba='$datos[plan_pruebas_id_plan_prueba]'"));
						
						echo "
							<input type='hidden' name='hdn_muestra$cont' id='hdn_muestra$cont' value='$datosAlerta[id_muestra]'/>
							<input type='hidden' name='hdn_fechaPrograma$cont' id='hdn_fechaPrograma' value='$datosAlerta[fecha_programada]'/>							
							<tr>
								<td class='nombres_filas' align='center'><input type='radio' name='rdb_prueba' id='rdb_prueba' value='$cont' /></td>
								<td class='$nom_clase' align='center'>$datosAlerta[id_muestra]</td>
								<td class='$nom_clase' align='left'>$datosAlerta[nombre]</td>
								<td class='$nom_clase' align='center'>".modFecha($datosAlerta['fecha_colado'],1)."</td>
								<td class='$nom_clase' align='center'>".modFecha($datosAlerta['fecha_programada'],1)."</td>
							</tr>";
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}while($datos=mysql_fetch_array($rs));
				}
			//Cerrar la Conexion con la BD	
			mysql_close($conn);
			?>
		  </table>
		  </div>
			<div id="btns-regpdf" align="center">
			<table width="76%" cellpadding="12">
			<tr>
				<td colspan="7" align="center"><?php //Es necesario guardar nuevamente el origen, puesto que es reenviado mediante un nuevo POST ?>
					<input type="submit" class="botones" value="Registrar" onMouseOver="window.status='';return true" title="Registrar Resultados de las Pruebas"/>
					&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" value="Limpiar" title="Limpiar Datos del Formulario"/>
					&nbsp;&nbsp;&nbsp;
					<input type="button"  onclick="location.href='inicio_laboratorio.php'" class="botones" value="Cancelar" title="Regresar al Inicio de Laboratorio"/>
			  </td>
			</tr>
		</table>
		</div>
		</form><?php 
	}//Cierre if(!isset($_POST['rdb_prueba']))
	else{		
	
		//Guardaro los datos que seran prellenados en la Pagina para registrar los resultados del plan de pruebas
		$_SESSION['datosPruebaAlerta'] = array ("idMuestra"=>$_POST["hdn_muestra".$rdb_prueba], "fechaPrograma"=>$_POST["hdn_fechaPrograma".$rdb_prueba]);
			 
		echo "<meta http-equiv='refresh' content='0;url=frm_registrarPruebasMuestras2.php?cancelar=si'>";		
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>