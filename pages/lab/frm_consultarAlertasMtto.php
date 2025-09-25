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
	<div class="titulo_barra" id="titulo-alertas">Equipos Pr&oacute;ximos a tener Matenimiento </div><?php 
	
	//Desplegar los datos de los equipos que han generado una alerta cuando son 2 o mas
	if(!isset($_POST['rdb_equipo'])) { 
	
		//Conectarse con la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a las mezclas
		$stm_sql = "SELECT id_servicio, no_interno, nombre, marca, tipo_servicio, no_serie, fecha_mtto FROM (equipo_lab JOIN alertas_mtto ON no_interno=alertas_mtto.equipo_lab_no_interno) 
					JOIN cronograma_servicios ON no_interno=cronograma_servicios.equipo_lab_no_interno AND origen=tipo_servicio
					WHERE alertas_mtto.estado = 1 ORDER BY no_interno";?>	
		
		<form name="frm_resultadosMtto" id="frm_resultadosMtto" onsubmit="return valFormValidarAlertaMtto(this);" action="frm_consultarAlertasMtto.php" method="post">
		<div  class="borde_seccion2"id="form-datos-alertas">
		<table border="0" align="center" cellpadding="5" class="tabla_frm">
			<caption class='titulo_etiqueta'>Seleccionar Equipo para Registrar Resultados del Mantenimiento</caption>					
			<tr>
				<td align="center" class="nombres_columnas">SELECCIONAR</td>
				<td align="center" class="nombres_columnas">ClAVE EQUIPO</td>
				<td align="center" class="nombres_columnas">EQUIPO</td>
				<td align="center" class="nombres_columnas">MARCA</td>
				<td align="center" class="nombres_columnas">TIPO SERVICIO</td>
				<td align="center" class="nombres_columnas">NO SERIE</td>
				<td align="center" class="nombres_columnas">FECHA PROGRAMADA DEL MTTO</td>
			</tr><?php 				
				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta de datos fue realizada con exito.
				if($datos=mysql_fetch_array($rs)){
					$nom_clase = "renglon_gris";
					$cont = 1;											
					do{				
						echo "
							<input type='hidden' name='hdn_idServicio$cont' id='hdn_idServicio$cont' value='$datos[id_servicio]'/>
							<input type='hidden' name='hdn_noInterno$cont' id='hdn_noInterno$cont' value='$datos[no_interno]'/>
							<input type='hidden' name='hdn_nombre$cont' id='hdn_nombre$cont' value='$datos[nombre]'/>
							<input type='hidden' name='hdn_marca$cont' id='hdn_marca$cont' value='$datos[marca]'/>
							<input type='hidden' name='hdn_tipoServicio$cont' id='hdn_tipoServicio$cont' value='$datos[tipo_servicio]'/>
							<input type='hidden' name='hdn_noSerie$cont' id='hdn_noSerie$cont' value='$datos[no_serie]'/>
							<input type='hidden' name='hdn_fechaMtto$cont' id='hdn_fechaMtto$cont' value='$datos[fecha_mtto]'/>							
							<tr>
								<td class='nombres_filas' align='center'><input type='radio' name='rdb_equipo' id='rdb_equipo' value='$cont' /></td>
								<td class='$nom_clase' align='center'>$datos[no_interno]</td>
								<td class='$nom_clase' align='left'>$datos[nombre]</td>
								<td class='$nom_clase' align='center'>$datos[marca]</td>
								<td class='$nom_clase' align='left'>$datos[tipo_servicio]</td>
								<td class='$nom_clase' align='center'>$datos[no_serie]</td>
								<td class='$nom_clase' align='center'>".modFecha($datos['fecha_mtto'],1)."</td>
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
			//mysql_close($conn);
			?>
		  </table>
		  </div>
			<div id="btns-regpdf" align="center">
			<table width="76%" cellpadding="12">
			<tr>
				<td colspan="7" align="center"><?php //Es necesario guardar nuevamente el origen, puesto que es reenviado mediante un nuevo POST ?>
					<input type="submit" class="botones" name="sbt_registrar" id="sbt_registrar" value="Registrar" onMouseOver="window.status='';return true" 
					title="Registrar Resultados del Mantenimiento"/>
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
		$_SESSION['datosEquipoAlerta'] = array ("idServicio"=>$_POST["hdn_idServicio".$rdb_equipo],"idEquipo"=>$_POST["hdn_noInterno".$rdb_equipo], "nombre"=>$_POST["hdn_nombre".$rdb_equipo],
										"marca"=>$_POST["hdn_marca".$rdb_equipo], "tipoServicio"=>$_POST["hdn_tipoServicio".$rdb_equipo],
										"noSerie"=>$_POST['hdn_noSerie'.$rdb_equipo], "fechaMtto"=>$_POST["hdn_fechaMtto".$rdb_equipo]);
			 
		echo "<meta http-equiv='refresh' content='0;url=frm_registrarMttoEquipo2.php?cancelar=si'>";		
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>