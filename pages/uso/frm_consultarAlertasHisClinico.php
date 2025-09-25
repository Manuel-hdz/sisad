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
	<script type="text/javascript" src="../../includes/validacionClinica.js"></script>	
	
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
	<div class="titulo_barra" id="titulo-alertas">Alertas de Examenes Clinicos Pr&oacute;ximos a Realizarse </div><?php 
	
	if(!isset($_POST['rdb_examen'])) { 
		//Conectarse con la BD de la Clinica
		$conn = conecta("bd_clinica");		
				
		//Obtener las alertas registradas en la tabla de alertas_examen cuyo estado sea '0'(Alertas no atendidas)
		$stm_sql = "SELECT * FROM alerta_examen WHERE estado = '1' ORDER BY id_alerta_exa";?>	
		
		<form name="frm_resultadosPlanes" action="frm_consultarAlertasHisClinico.php" method="post" onsubmit="return valFormResultadosExamenesMedicos(this);">
		<div  class="borde_seccion2"id="form-datos-alertas">
		<table border="0" align="center" cellpadding="5" class="tabla_frm">
			<caption class='titulo_etiqueta'>Seleccionar el Trabajador al Cual se le Realizara un Nuevo Examen Cl&iacute;nico</caption>					
			<tr>
				<td align="center" class="nombres_columnas">SELECCIONAR</td>
				<td align="center" class="nombres_columnas">CLAVE HISTORIAL</td>
				<td align="center" class="nombres_columnas">EXAMEN</td>
				<td align="center" class="nombres_columnas">CLASIFICACI&Oacute;N</td>				
				<td align="center" class="nombres_columnas">NOMBRE</td>
				<td align="center" class="nombres_columnas">PUESTO</td>
				<td align="center" class="nombres_columnas">ULTIMO EXAMEN</td>
				<td align="center" class="nombres_columnas">VER ULTIMO HISTORIAL</td>
			</tr><?php 				
				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta de datos fue realizada con exito.
				if($datos=mysql_fetch_array($rs)){					
					$nom_clase = "renglon_gris";
					$cont = 1;											
					do{
						//Obtener los datos de los Historiales Clinicos que no han sido atendidos				
						$datosAlerta = mysql_fetch_array(mysql_query("SELECT DISTINCT id_historial, clasificacion_exa, tipo_clasificacion,
						 puesto_realizar, fecha_exp, nom_empleado, id_empleados_empresa FROM historial_clinico WHERE id_empleados_empresa  = '$datos[id_empleados_empresa]'"));
						 
						echo "
							<tr>
								<td class='nombres_filas' align='center'><input type='radio' name='rdb_examen' id='rdb_examen' value='$datosAlerta[id_empleados_empresa]' /></td>
								<td class='$nom_clase' align='center'>$datosAlerta[id_historial]</td>
								<td class='$nom_clase' align='center'>$datosAlerta[clasificacion_exa]</td>
								<td class='$nom_clase' align='center'>$datosAlerta[tipo_clasificacion]</td>
								<td class='$nom_clase' align='center'>$datosAlerta[nom_empleado]</td>
								<td class='$nom_clase' align='center'>$datosAlerta[puesto_realizar]</td>
								<td class='$nom_clase' align='center'>".modFecha($datosAlerta['fecha_exp'],1)."</td>";?>
								<td class='<?php echo $nom_clase;?>' align='center'>
								<input name="btn_verHC" type="button" class="botones_largos" id="btn_verHC"  value="Historial Medico" 
								title="Ver Ultimo Historial Clinico" 
								onClick="javascript:window.open('../../includes/generadorPDF/historialClinico.php?idHistorial=<?php echo $datos['historial_clinico_id_historial']?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"
								onmouseover="window.status='';return true" /> 
							</td><?php 								
							"</tr>";
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
					<input name="sbt_complementar" id="sbt_complementar" type="submit" class="botones_largos" value="Registrar Nuevo Examen" 
						onMouseOver="window.status='';return true" title="Generar un Nuevo Historial Clinico al Trabajador Seleccionado"   />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" value="Limpiar" title="Limpiar Datos del Formulario"/>
					&nbsp;&nbsp;&nbsp;
					<input type="button"  onclick="location.href='inicio_clinica.php'" class="botones" value="Cancelar" title="Regresar al Inicio de la Clinica"/>
			  </td>
			</tr>
		</table>
		</div>
		</form><?php 
	}//Cierre if(!isset($_POST['rdb_examen']))
	else{		
		//Guardaro los datos que seran prellenados en la Pagina para generar un nuevo historial
		echo "<meta http-equiv='refresh' content='0;url=frm_generarHistorialClinico2.php?rdb_examen=$_POST[rdb_examen]'>";
			
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>