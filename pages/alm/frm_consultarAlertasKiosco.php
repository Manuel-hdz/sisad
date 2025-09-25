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
		include ("head_menu.php");
		//Identificar si es Requisición u Órden de Compra para evitar problemas con funciones de nombres iguales en alguno de los 2 archivos de operaciones
		include_once("op_generarRequisicion.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>	
	<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>	
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:500px; z-index:12; overflow:auto; }
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:19px; z-index:11; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">VALES DEL KIOSCO</div>
	<div id="form-datos-alertas">
		<p align="center" class="titulo_etiqueta">Vales de Kiosco Pendientes</p>
		<table border="0" align="center" cellpadding="5" class="tabla_frm" width="100%">
			<tr>
				<td align="center" class="nombres_columnas">SELECCIONE</td>
				<td align="center" class="nombres_columnas">VALE</td>
				<td align="center" class="nombres_columnas">ID EMPLEADO</td>
				<td align="center" class="nombres_columnas">EMPLEADO</td>
				<td align="center" class="nombres_columnas">FECHA</td>
				<td align="center" class="nombres_columnas">EQUIPO DE SEGURIDAD</td>
			</tr>
			<?php 
			$conn = conecta("bd_kiosco");
			$stm_sql = "SELECT * 
						FROM  `vale_kiosco` 
						JOIN alertas
						USING (  `id_vale_kiosco` ) 
						WHERE estado =1 ";	
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{
					if($datos["epp"] == 1)
						$epp = "SI";
					else
						$epp = "NO";
					echo "
					<form name='frm_verVales$cont' action='frm_salidaMaterial.php' method='post'>
						<tr>
							<td class='nombres_filas' align='center'>
								<input type='radio' name='vale_kiosco' id='vale_kiosco' value='$datos[id_vale_kiosco],$datos[id_empleados_empresa]' onclick='submit()'/>
								<input type='hidden' id='es_epp' name='es_epp' value='$datos[epp]'/>
							</td>
							<td class='$nom_clase' align='center'>
								$datos[id_vale_kiosco]
								<input type='hidden' id='id_kiosco' name='id_kiosco' value='$datos[id_empleados_empresa]'/>
							</td>
							<td class='$nom_clase' align='center'>
								$datos[id_empleados_empresa]
								<input type='hidden' id='id_empl' name='id_empl' value='$datos[id_empleados_empresa]'/>
							</td>
							<td class='$nom_clase' align='left'>
								$datos[nombre_empleado]
							</td>
							<td class='$nom_clase' align='left'>
								".modFecha($datos['fecha'],2)."
							</td>
							<td class='$nom_clase' align='center'>
								$epp
							</td>
						</tr>
					</form>
					";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
			}
			mysql_close($conn);
			?>
		</table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>