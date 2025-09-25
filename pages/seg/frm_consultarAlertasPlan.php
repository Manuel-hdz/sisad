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
	<script type="text/javascript" src="../../includes/validacionSeguridad.js"></script>	
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:350px; z-index:12; overflow:scroll}
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:22px; z-index:11; }
		#btns-regpdf { position:absolute; left:38px; top:577px; width:956px; height:53px; z-index:11; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Planes de Contingencia Pr&oacute;ximos a Realizarse </div><?php 
	
	if(!isset($_POST['rdb_plan'])) { 	
		//Conectarse con la BD de Seguridad
		$conn = conecta("bd_seguridad");		
				
		//Obtener las alertas registradas en la tabla de alertas_planes_contingencia cuyo estado sea 'NO'(Alertas no atendidas)
		$stm_sql = "SELECT * FROM alertas_planes_contingencia WHERE estado = 'NO'";?>	
		
		<form name="frm_resultadosPlanes" action="frm_consultarAlertasPlan.php" method="post" onsubmit="return valFormResultadosPlanes(this);">
		<div  class="borde_seccion2"id="form-datos-alertas">
		<table border="0" align="center" cellpadding="5" class="tabla_frm">
			<caption class='titulo_etiqueta'>Seleccionar el Plan de Contingencia para Registrar Resultados</caption>					
			<tr>
				<td align="center" class="nombres_columnas">SELECCIONAR</td>
				<td align="center" class="nombres_columnas">ClAVE PLAN</td>
				<td align="center" class="nombres_columnas">RESPONSABLE</td>
				<td align="center" class="nombres_columnas">NOMBRE SIMULACRO</td>				
				<td align="center" class="nombres_columnas">&Aacute;REA</td>
				<td align="center" class="nombres_columnas">LUGAR</td>
				<td align="center" class="nombres_columnas">FECHA REGISTRO</td>
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
						$datosAlerta = mysql_fetch_array(mysql_query("SELECT DISTINCT id_plan, responsable, area, lugar, nom_simulacro, fecha_reg, fecha_programada 
						FROM  planes_contingencia WHERE id_plan = '$datos[planes_contingencia_id_plan]'"));
						
						echo "
							<tr>
								<td class='nombres_filas' align='center'><input type='radio' name='rdb_plan' id='rdb_plan' value='$datosAlerta[id_plan]' /></td>
								<td class='$nom_clase' align='center'>$datosAlerta[id_plan]</td>
								<td class='$nom_clase' align='left'>$datosAlerta[responsable]</td>
								<td class='$nom_clase' align='left'>$datosAlerta[nom_simulacro]</td>
								<td class='$nom_clase' align='left'>$datosAlerta[area]</td>
								<td class='$nom_clase' align='left'>$datosAlerta[lugar]</td>
								<td class='$nom_clase' align='center'>".modFecha($datosAlerta['fecha_reg'],1)."</td>
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
			mysql_close($conn); ?>
		  </table>
		  </div>
			<div id="btns-regpdf" align="center">
			<table cellpadding="5">
			<tr>
				<td colspan="7" align="center"><?php //Es necesario guardar nuevamente el origen, puesto que es reenviado mediante un nuevo POST ?>
					<input name="sbt_complementar" id="sbt_complementar" type="submit" class="botones" value="Complementar" 
						onMouseOver="window.status='';return true" title="Complementar los Resultados de los Planes de Contingencia"  />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" value="Limpiar" title="Limpiar Datos del Formulario"/>
					&nbsp;&nbsp;&nbsp;
					<input type="button"  onclick="location.href='inicio_seguridad.php'" class="botones" value="Cancelar" title="Regresar al Inicio de Seguridad"/>
			  </td>
			</tr>
		</table>
		</div>
		</form><?php 
	}//Cierre if(!isset($_POST['rdb_plan']))
	else{		
		//Guardaro los datos que seran prellenados en la Pagina para registrar los resultados del plan de pruebas
		//$_SESSION['datosAlertaPlanContingencia'] = array ("clavePlan"=>$_POST["hdn_idPlanCont".$rdb_plan]);			 
		echo "<meta http-equiv='refresh' content='0;url=frm_complementarPlanContingencia.php?rdb_plan=$_POST[rdb_plan]'>";	

	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>