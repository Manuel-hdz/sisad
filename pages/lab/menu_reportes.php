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
		//Incluimos archivo para generar alertas de las pruebas a las mezclas
		include_once ("alertas_pruebas.php");
		//Incluimos Archivo para generar las alertas de los equipos que estan proximos a recibir mantenimiento
		include_once ("alertas_mtto.php");
		//Desplegar las alertas registradas en la BD 
		//Función para Generar las Alertas de Pruebas a mezclas inlcuido en alertas_pruebas.php
		desplegarAlertas();
		//Función para generar las Alertas de Pruenas a Mezclas incluido en alertas_mtto.php
		desplegarAlertasMtto();?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:136px; top:168px; width:731px; height:545px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1" >
  	<table class="tabla_frm" width="750" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="50%" align="center">
	    		<div align="center">
					<form action="frm_reporteResistencias.php">
						<input type="image" onclick="location.href='frm_reporteResistencias.php'" src="images/add-reporteresistencias.png" width="150" height="208" border="0" 
						title="Reporte de Resistencias" onmouseover="window.status='';return true" /><br/>
						<input type="image" src="../../images/btn-gen.png" name="btn1" id="btn1" width="118" height="46" border="0" title="Reporte de Pruebas a Mezclas"
						onclick="MM_nbGroup('down','group1','btn1','',1)" 
						onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
					</form>
   		  		</div>			
			</td>
      		<td width="50%" align="center">
	    		<div align="center">
					<form action="frm_reporteAgregados.php">
						<input type="image" onclick="location.href='frm_reporteAgregados.php'" src="images/add-reporteagregados.png" width="150" height="208" border="0" title="Reporte de Pruebas a Agregados"
						onmouseover="window.status='';return true" /><br/>
						<input type="image" src="../../images/btn-gen.png" name="btn2" id="btn2" width="118" height="46" border="0" title="Reporte de Pruebas a Agregados"
						onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1);window.estatus='';return true" 
						onmouseout="MM_nbGroup('out')"/>
					</form>
   		  		</div>
			</td>
		</tr>
    	<tr>
    	  <td align="center">
		  	<div align="center">
				<form action="frm_reporteMttoEquipoLab.php">
					<input type="image" onclick="location.hfer='frm_reporteMttoEquipoLab.php'" src="images/add-reportemtto.png" width="150" height="208" border="0" 
					title="Reporte de Servicios de Mantenimiento a los Equipos de Laboratorio" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn3" id="btn3" width="118" height="46" border="0" title="Reporte de Servicios de Mantenimiento a los Equipos de Laboratorio"
					onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-gen-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
	    	</div>		  </td>
  	      <td align="center"><div align="center"><form action="frm_reporteRendimiento.php"><input type="image" onclick="location.hfer='frm_reporteRendimiento.php'" src="images/add-reprendimiento.png" width="150" height="208" border="0" 
					title="Reporte Rendimiento" onmouseover="window.status='';return true" /><br/>
			  <input type="image" src="../../images/btn-gen.png" name="btn4" id="btn4" width="118" height="46" border="0" title="Reporte Rendimiento"
					onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-gen-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
			</form>
    	  </div>		  </td>
		  <td align="center">
			<form action="frm_reporteRequisiciones.php">
				<input type="image" src="images/add-requisicion.png" width="150" height="208" border="0" title="Generar Reporte Requisiciones" 
                onmouseover="window.status='';return true" />
				<input type="image" src="../../images/btn-gen.png" name="btn5" id="btn5" width="118" height="46" border="0" 
                title="Generar Reporte Requisiciones" 
				onclick="MM_nbGroup('down','group1','btn5','',1)" 
                onmouseover="MM_nbGroup('over','btn5','../../images/btn-gen-over.png','',1);window.status='';return true" 
                onmouseout="MM_nbGroup('out')"/>
			</form>	  		
		  </td>
      </tr>
  	</table>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>