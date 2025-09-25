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
	<div class="titulo_barra" id="titulo-alertas">Detalle de Movimientos NO Reportados a Gerencia Administrativa</div>
	
	<?php
	
		//Conectarse a la BD d ela clinica para verificar a cuales trabajadores se les tiene programados un examen medico
		$conn = conecta("bd_compras");
		
		//Crear la Sentencia SQL para obtener el departamento al cual pertenece el empleado al cual se le programo el historial medico
		$stm_sql = "SELECT DISTINCT caja_chica_id_caja_chica, fecha, responsable, descripcion, cant_entregada, estado, departamento
		FROM detalle_caja_chica   WHERE estado = '0' AND departamento = 'SEGURIDAD'";

		//EJecutar la Consulta
		$rs = mysql_query($stm_sql);?>			
			
		<form name="frm_mostrarAlertasMovCompras" action="frm_consultarAlertasCompras.php" method="post" >
		<div  class="borde_seccion2"id="form-datos-alertas">
		<table border="0" align="center" cellpadding="5" class="tabla_frm">
			<caption class='titulo_etiqueta'>Movimientos que No han sido Reportados al Departamento de Compras</caption>					
			<tr>
				<td align="center" class="nombres_columnas">CLAVE MOVIMIENTO</td>
				<td align="center" class="nombres_columnas">FECHA MOVIMIENTO</td>
				<td align="center" class="nombres_columnas">NOMBRE RESPONSABLE</td>
				<td align="center" class="nombres_columnas">DESCRIPCI&Oacute;N</td>
				<td align="center" class="nombres_columnas">CANTIDAD ENTREGADA</td>
			</tr><?php 				
				//Confirmar que la consulta de datos fue realizada con exito.
				if($datos=mysql_fetch_array($rs)){					
					$nom_clase = "renglon_gris";
					$cont = 1;											
					do{	
						echo "
							<tr>
								<td class='$nom_clase' align='center'>$datos[caja_chica_id_caja_chica]</td>
								<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>
								<td class='$nom_clase' align='center'>$datos[responsable]</td>
								<td class='$nom_clase' align='center'>$datos[descripcion]</td>
								<td class='$nom_clase' align='center'>$ ".number_format($datos['cant_entregada'],2,".",",")."</td>";					
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
						onMouseOver="window.status='';return true" onclick="location.href='inicio_seguridad.php'"
						 title="Regresar al Inicio de Seguridad"/>
				</td>
			</tr>
		</table>
		</div>
		</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>