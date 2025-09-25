<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
	//	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
    <link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/reportesRecursos.js"></script>
	<script type="text/javascript" src="../../includes/validacionDireccion.js"></script>
	
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#tabla-resultadosEmpleados").dataTable({
			"sPaginationType": "scrolling"
		});
	});
	</script>

	<style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#form-selecPeriodo {position:absolute;left:30px;top:190px;width:300px;height:180px;z-index:14;}
		#resultado{position:absolute;left:30px;top:191px;width:981px; height:473px;;z-index:15;overflow:scroll;}
		#boton{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}
		-->
    </style>
	
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte de Pr&eacute;stamos </div>
	
	<div id="resultado">
		<?php
		//Creamos la sentencia SQL para mostrar los datoa de todos los empleados
		$stm_sql="SELECT * FROM empleados WHERE id_empleados_empresa>=0 ORDER BY id_empleados_empresa";
		//Creamos el titulo de la tabla
		$titulo="Datos de Todos los Empleados";
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' id='tabla-resultadosEmpleados'>";
			echo "<caption class='titulo_etiqueta' style='color:#FFF'>$titulo</caption>
				<thead>";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>RFC</th>
						<th class='nombres_columnas' align='center'>CURP</th>
						<th class='nombres_columnas' align='center'>ID EMPRESA</th>
						<th class='nombres_columnas' align='center'>ID &Aacute;REA</th>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>TIPO SANGRE</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SEGURO SOCIAL</td>
						<td class='nombres_columnas' align='center'>FECHA INGRESO</td>
						<td class='nombres_columnas' align='center'>ANTIG&Uuml;EDAD</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO CUENTA</td>
						<th class='nombres_columnas' align='center'>&Aacute;REA</th>
						<td class='nombres_columnas' align='center'>JORNADA</td>
						<th class='nombres_columnas' align='center'>OCUPACI&Oacute;N ESPEC&Iacute;FICA</th>
						
						<th class='nombres_columnas' align='center'>M&Aacute;XIMO NIVEL DE ESTUDIOS</th>
						<th class='nombres_columnas' align='center'>T&Iacute;TULO</th>
						<th class='nombres_columnas' align='center'>CARRERA</th>
						<th class='nombres_columnas' align='center'>TIPO ESCUELA</th>
						
						<td class='nombres_columnas' align='center'>DIRECCI&Oacute;N</td>
						<th class='nombres_columnas' align='center'>MUNICIPIO/ LOCALIDAD</th>
						<th class='nombres_columnas' align='center'>ESTADO</th>
						<th class='nombres_columnas' align='center'>PAIS</th>
						<th class='nombres_columnas' align='center'>NACIONALIDAD</th>
						<th class='nombres_columnas' align='center'>TEL&Eacute;FONO</th>
						<th class='nombres_columnas' align='center'>LUGAR NACIMIENTO</th>
						<th class='nombres_columnas' align='center'>FECHA NACIMIENTO</th>
						<th class='nombres_columnas' align='center'>ESTADO CIVIL</th>
						<th class='nombres_columnas' align='center'>DISCAPACIDAD</th>
						<th class='nombres_columnas' align='center'>HIJOS DEPENDIENTES ECONOMICOS</th>
						<td class='nombres_columnas' align='center'>CONTACTO POR ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
						
						<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
						<td class='nombres_columnas' align='center'>BENEFICIARIOS</td>
						<td class='nombres_columnas' align='center'>CAPACITACIONES</td>
						<td class='nombres_columnas' align='center'>BECARIOS</td>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;				
			echo "<tbody>";
			do{	
				$ctrl_imagen = "";
				if($datos['mime']=="")
					$ctrl_imagen = "disabled='disabled'";	
				
				$nivelEstudios="";
				switch($datos["nivel_estudio"]){
					case 1:
						$nivelEstudios="PRIMARIA";
						break;
					case 2:
						$nivelEstudios="SECUNDARIA";
						break;
					case 3:
						$nivelEstudios="BACHILLERATO";
						break;
					case 4:
						$nivelEstudios="CARRERA T&Eacute;CNICA";
						break;
					case 5:
						$nivelEstudios="LICENCIATURA";
						break;
					case 6:
						$nivelEstudios="ESPECIALIDAD";
						break;
					case 7:
						$nivelEstudios="MAESTR&Iacute;A";
						break;
					case 8:
						$nivelEstudios="DOCTORADO";
						break;
				}
				
				$titulo="";
				switch($datos["titulo"]){
					case 1:
						$titulo="T&Iacute;TULO";
						break;
					case 2:
						$titulo="CERTIFICADO";
						break;
					case 3:
						$titulo="DIPLOMA";
						break;
					case 4:
						$titulo="OTRO";
						break;
				}
				
				$tipoEscuela="";
				switch($datos["tipo_escuela"]){
					case 1:
						$tipoEscuela="P&Uacute;BLICA";
						break;
					case 2:
						$tipoEscuela="PRIVADA";
						break;
				}
				
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[rfc_empleado]</td>
						<td class='$nom_clase' align='left'>$datos[curp]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_area]</td>
						<td class='$nom_clase' align='center'>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["sueldo_diario"],2,".",",")."</td>
						<td class='$nom_clase' align='left'>$datos[tipo_sangre]</td>
						<td class='$nom_clase' align='center'>$datos[no_ss]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_ingreso"],2)."</td>
						<td class='$nom_clase' align='left'>".round((restarFechas($datos["fecha_ingreso"],date("Y-m-d"))/365),2)." a&ntilde;os</td>
						<td class='$nom_clase' align='left'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[no_cta]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='center'>$datos[jornada]&nbsp;Hrs.</td>
						<td class='$nom_clase' align='center'>$datos[oc_esp]</td>
						
						<td class='$nom_clase' align='center'>$nivelEstudios</td>
						<td class='$nom_clase' align='center'>$titulo</td>
						<td class='$nom_clase' align='center'>$datos[carrera]</td>
						<td class='$nom_clase' align='center'>$tipoEscuela</td>
						
						<td class='$nom_clase' align='center'>$datos[calle] $datos[num_ext] $datos[num_int] $datos[colonia]</td>
						<td class='$nom_clase' align='center'>$datos[localidad]</td>
						<td class='$nom_clase' align='center'>$datos[estado]</td>
						<td class='$nom_clase' align='center'>$datos[pais]</td>
						<td class='$nom_clase' align='center'>$datos[nacionalidad]</td>
						<td class='$nom_clase' align='center'>$datos[telefono]</td>
						<td class='$nom_clase' align='center'>$datos[lugar_nacimiento]</td>
						<td class='$nom_clase' align='center'>".modFecha(calcularFecha(substr($datos["rfc_empleado"],4,6)),2)."</td>
						<td class='$nom_clase' align='center'>$datos[edo_civil]</td>
						<td class='$nom_clase' align='center'>$datos[discapacidad]</td>
						<td class='$nom_clase' align='center'>$datos[hijos_dep_eco]</td>
						<td class='$nom_clase' align='left'>Nombre: $datos[nom_accidente]<br>Tel: $datos[tel_accidente]<br>Cel: $datos[cel_accidente]</td>
						<td class='$nom_clase' align='left'>$datos[observaciones]</td>
						";
						?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFoto" class="botones" value="Foto" onMouseOver="window.estatus='';return true" title="Ver Foto del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verImagen.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'verInfoEmp','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" <?php echo $ctrl_imagen; ?>/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verBeneficiarios" class="botones" value="Beneficiarios" onMouseOver="window.estatus='';return true" title="Ver Beneficiarios del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verBeneficiarios.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'verInfoEmp','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verCapacitaciones" class="botones" value="Capacitaciones" onMouseOver="window.estatus='';return true" title="Ver verCapacitaciones del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verCapacitaciones.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'verInfoEmp','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verBecarios" class="botones" value="Becarios" onMouseOver="window.estatus='';return true" title="Ver Becarios del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verBecarios.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'verInfoEmp','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td><?php
				echo "
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 
			echo "</tbody>";	
			echo "</table>";
		}
		?>
	</div>

	<div id="boton" align="center">
		<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Reportes de Recursos Humanos" onClick="borrarHistorial();location.href='submenu_recursos.php'" />
	</div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>