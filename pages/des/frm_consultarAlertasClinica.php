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
	<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>	
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:210px; z-index:12; }
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:19px; z-index:11; }
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:350px; z-index:12; overflow:scroll}
		#titulo-alertas { position:absolute; left:30px; top:146px; width:592px; height:22px; z-index:11; }
		#btns-regpdf { position:absolute; left:38px; top:577px; width:956px; height:53px; z-index:11; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Trabajadores los Cuales Tienen Programado un Examen Medico</div>
	
	<?php
	
		//Conectarse a la BD d ela clinica para verificar a cuales trabajadores se les tiene programados un examen medico
		$conn = conecta("bd_clinica");
		
		//Crear la Sentencia SQL para obtener el departamento al cual pertenece el empleado al cual se le programo el historial medico
		$stm_sql = "SELECT DISTINCT id_alerta_exa, puesto_realizar, fecha_exp, fecha_programada,alerta_examen.nom_empleado,
		 catalogo_departamentos_id_departamento, alerta_examen.id_empleados_empresa, estado FROM historial_clinico 
		 JOIN alerta_examen ON id_historial = historial_clinico_id_historial WHERE catalogo_departamentos_id_departamento = '3' AND estado = '1'";

		//EJecutar la Consulta
		$rs = mysql_query($stm_sql);?>			
			
		<form name="frm_mostrarHCProgramados" action="frm_consultarAlertasHisClinico.php" method="post" >
		<div  class="borde_seccion2"id="form-datos-alertas">
		<table border="0" align="center" cellpadding="5" class="tabla_frm">
			<caption class='titulo_etiqueta'>Trabajadores que Deben Realizarse el Examen Medico en la Unidad de Salud Ocupacional</caption>					
			<tr>
				<td align="center" class="nombres_columnas">CLAVE ALERTA</td>
				<td align="center" class="nombres_columnas">NOMBRE TRABAJADOR</td>
				<td align="center" class="nombres_columnas">PUESTO</td>
				<td align="center" class="nombres_columnas">ULTIMO EXAMEN</td>
				<td align="center" class="nombres_columnas">PROXIMO EXAMEN PROGRAMADO</td>
			</tr><?php 				
				//Confirmar que la consulta de datos fue realizada con exito.
				if($datos=mysql_fetch_array($rs)){					
					$nom_clase = "renglon_gris";
					$cont = 1;											
					do{	
						echo "
							<tr>
								<td class='$nom_clase' align='center'>$datos[id_alerta_exa]</td>
								<td class='$nom_clase' align='center'>$datos[nom_empleado]</td>
								<td class='$nom_clase' align='center'>$datos[puesto_realizar]</td>
								<td class='$nom_clase' align='center'>".modFecha($datos['fecha_exp'],1)."</td>
								<td class='$nom_clase' align='center'>".modFecha($datos['fecha_programada'],1)."</td>";
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
				<td colspan="7" align="center">
					<input name="btn_regresar" id="btn_regresar" type="button" class="botones" value="Regresar" 
						onMouseOver="window.status='';return true" onclick="location.href='inicio_desarrollo.php'"
						 title="Regresar al Inicio Desarrollo"/>
				</td>
			</tr>
		</table>
		</div>
		</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>