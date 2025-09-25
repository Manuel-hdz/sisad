	<?php //Archivos que permtien desabilitar teclas especificas, as� como desabilitar el clic derecho?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
<?php 
	/**
	  * Nombre del M�dulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Mart�nez Fern�ndez
	  * Fecha: 21/Noviembre/2011
	  * Descripci�n: Archivo que permite ver quienes tienen participacion en las copias entregadas del plan de acciones
	  **/ 

	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_registrarListaMaestraRegCal.php");
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Importamos archivo para realizar la conexion con la BD
	include_once("../../includes/conexion.inc");
	
	//Incluimos archivo para modificar fechas
	include_once("../../includes/func_fechas.php");
	
	//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
	include_once("../../includes/op_operacionesBD.php");
	
	//Realizar la conexion a la BD de Aseguramiento
	$conn = conecta("bd_aseguramiento");
	
	//Guardamos el id del plan de Acciones
	$idPA = $_GET['idPlanAcciones'];
	
	//Creamos la sentencia SQL
	$stm_sql ="SELECT * FROM copias_entregadas WHERE plan_acciones_id_plan_acciones='$idPA'";
				
	//Ejecutamos la sentencia SQL
	$rs = mysql_query($stm_sql);
	
	//Si la consulta trajo datos creamos la tabla para mostrarlos
	if($datos = mysql_fetch_array($rs)){		
		echo "				
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>Copias Entregadas - Plan De Acciones</caption>";
		echo "	<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
				</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;	
		do{	
			$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datos['catalogo_departamentos_id_departamento']);
			echo "
			<tr>
				<td align='center' class='$nom_clase'>$cont</td>
				<td class='$nom_clase'>".strtoupper($nomDepto)."</td>
			</tr>";
			//Determinar el color del siguiente renglon a dibujar
			$cont++;	
		}while($datos=mysql_fetch_array($rs)); 	
		echo "</tbody>";
		echo "</table>";	
		return 1;
	}
	else{
		//Si no se encuentra ningun resultado desplegar un mensaje					
		echo "<label class='msje_correcto'>  No existen Departamentos Registrados </label>";
		return 0;
	}?>							
	<?php
	//Cerrar la conexion con la BD
	mysql_close($conn);
?>