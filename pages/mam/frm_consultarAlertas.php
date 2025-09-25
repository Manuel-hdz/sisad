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
		include ("head_menu.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js"></script>	
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:210px; z-index:12; }
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:19px; z-index:11; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Equipo Pr&oacute;ximo a Recibir Mantenimiento Preventivo </div><?php 
	
	
	if(!isset($_POST['rdb_equipoSelect'])) { 
	
		//Conectarse con la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		$tipoUnidades = "";		
		
		/*Determinar cual usuario esta logeado y en base a ello desplegar las alertas que le Corresponden*/
		$parametro = "";
		$param_odo = "AND origen='ODO'";
		$param_horo = "AND origen='HORO'";
		if($_SESSION['depto']=="MttoConcreto"){
			$parametro = "AND area='CONCRETO'";
			$param_odo = "AND (origen='ODO' OR origen='HORO')";
		}
		else if($_SESSION['depto']=="MttoMina"){
			$parametro = "AND area='MINA'";
			$param_horo = "AND (origen='ODO' OR origen='HORO')";
		}
		
		//Evaluamos si el origen desde se llega a esta ventana Orden de Trabajo para un equipo con Odometro o con Horometro
		if ($hdn_org=="odometro"){
			//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Equipos con Odometro
			$stm_sql = "SELECT id_equipo, nom_equipo, metrica, ultimo_reg, cant_restante, area, familia FROM equipos
						JOIN alertas ON id_equipo=equipos_id_equipo AND alertas.estado=1 ".$param_odo." ".$parametro." ORDER BY id_equipo";	
			$tipoUnidades = "KILOMETROS";			
		}
		else{
			//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Equipos con Horometro
			$stm_sql = "SELECT id_equipo, nom_equipo, metrica, ultimo_reg, cant_restante, area, familia FROM equipos
						JOIN alertas ON id_equipo=equipos_id_equipo AND alertas.estado=1 ".$param_horo." ".$parametro." ORDER BY id_equipo";	 
			$tipoUnidades = "HORAS";
		}?>	
		
		
		<div id="form-datos-alertas">
		<p align="center" class="titulo_etiqueta">Completar la Informaci&oacute;n para Generar la Orden de Trabajo</p>
		<form onSubmit="return valFormVerEquipos(this);" name="frm_verEquipos" action="frm_consultarAlertas.php" method="post">
		<table border="0" align="center" cellpadding="5" class="tabla_frm">
			<tr>
				<td align="center" class="nombres_columnas">SELECCIONAR</td>
				<td align="center" class="nombres_columnas">CLAVE</td>
				<td align="center" class="nombres_columnas">EQUIPO</td>
				<td align="center" class="nombres_columnas">TIPO DE MEDIDA</td>
				<td align="center" class="nombres_columnas">HORAS ACUMULADAS</td>
				<td align="center" class="nombres_columnas"><?php echo $tipoUnidades; ?> FALTANTES</td>
				<td align="center" class="nombres_columnas">ULTIMO MANTENIMIENTO</td>
			</tr><?php 				
				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta de datos fue realizada con exito.
				if($datos=mysql_fetch_array($rs)){
					$nom_clase = "renglon_gris";
					$cont = 1;											
					do{
						//Determinar cuando un Equipo ha sobrepasado las Horas o los Kilometros para recibir su Mtto. Preventivo
						$cantRestante = $datos['cant_restante'];
											
						echo "
							<input type='hidden' name='hdn_clave$cont' id='hdn_clave$cont' value='$datos[id_equipo]' />
							<input type='hidden' name='hdn_area$cont' id='hdn_area$cont' value='$datos[area]' />
							<input type='hidden' name='hdn_familia$cont' id='hdn_familia$cont' value='$datos[familia]' />
							<input type='hidden' name='hdn_metrica$cont' id='hdn_metrica$cont' value='$datos[metrica]' />
							<input type='hidden' name='hdn_ultimoReg$cont' id='hdn_ultimoReg$cont' value='$datos[ultimo_reg]' />							
							<tr>
								<td class='nombres_filas' align='center'><input type='radio' name='rdb_equipoSelect' id='rdb_equipoSelect' value='$cont' /></td>
								<td class='$nom_clase' align='center'>$datos[id_equipo]</td>
								<td class='$nom_clase' align='left'>$datos[nom_equipo]</td>
								<td class='$nom_clase' align='center'>$datos[metrica]</td>
								<td class='$nom_clase' align='center'>".number_format($datos['ultimo_reg'],0,".",",")."</td>";
								
						if($cantRestante<0){
							if($tipoUnidades=="HORAS") $msj = "Cantidad de Horas Sobrepasadas";
							else $msj = "Cantidad de Kil&oacute;metros Sobrepasados";
							$cantRestante *= -1;
							echo "<td class='$nom_clase' align='center' title='$msj'><font color='#FF0000'><strong><u>".number_format($cantRestante,0,".",",")."</u></strong></font></td>";
						}
						else
							echo "<td class='$nom_clase' align='center'>".number_format($cantRestante,0,".",",")."</td>";
							
						//Obtener la ultima Fecha de Mantenimiento del Equipo
						$fecha=mysql_fetch_array(mysql_query("SELECT MAX(fecha_mtto) as fecha FROM bitacora_mtto WHERE equipos_id_equipo='$datos[id_equipo]' AND tipo_mtto='PREVENTIVO'"));
						$fechaMtto=$fecha["fecha"];
						//Verificar la fecha que regresa como resultado
						if($fechaMtto==NULL || $fechaMtto=="0000-00-00")
							echo "<td class='$nom_clase' align='center' title='NO Hay Registro de Mantenimiento Previo'><font color='#FF0000'><strong>ND</strong></font></td>";
						else
							echo "<td class='$nom_clase' align='center' title='&Uacute;ltimo Mantenimiento Registrado el ".modFecha($fechaMtto,2)."'>".modFecha($fechaMtto,1)."</td>";
						
						echo "
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
			<tr>
				<td colspan="7" align="center"><br/><br/>
					<?php //Es necesario guardar nuevamente el origen, puesto que es reenviado mediante un nuevo POST ?>
					<input type="hidden" name="hdn_org" id="hdn_org" value="<?php echo $hdn_org; ?>"/>
					<input type="submit" class="botones" value="Registrar" onMouseOver="window.status='';return true" title="Registrar Orden de Trabajo"/>
					&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" value="Limpiar" title="Limpiar Datos del Formulario"/>
					&nbsp;&nbsp;&nbsp;
					<input type="button"  onclick="location.href='inicio_mantenimiento.php'" class="botones" value="Cancelar" title="Regresar al Inicio de Mantenimiento"/>
				</td>
			</tr>
		</table>
		</form>
		</div>
	<?php }//Cierre if(!isset($_POST['rdb_equipoSelect']))
	else{		
		//Guardaro los datos que seran prellenados en la Pagina de Orden de Trabajo
		$_SESSION['datosEquipoAlerta'] = array ("area"=>$_POST["hdn_area".$rdb_equipoSelect], "familia"=>$_POST["hdn_familia".$rdb_equipoSelect],
		"claveEquipo"=>$_POST["hdn_clave".$rdb_equipoSelect], "metrica"=>$_POST["hdn_metrica".$rdb_equipoSelect], "cantidadMetrica"=>$_POST["hdn_ultimoReg".$rdb_equipoSelect]);
			 
		echo "<meta http-equiv='refresh' content='0;url=frm_generarOrdenTrabajo.php?cancelar=si'>";				
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>