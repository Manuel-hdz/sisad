<?php

	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 08/Julio/2011
	  * Descripción: Este archivo contiene funciones para registrar pruebas adelantas a las Mezclas
	  **/
	   
	//Incluimos arrchivo de conexion
	include ("../../includes/conexion.inc");
	//Incluimos el archivo para modificar las fechas para la consulta
	include ("../../includes/func_fechas.php");
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>";
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
	//Archivo de validacion para indicar pruebas seleccionadas
	echo "<script type='text/javascript' src='../../includes/validacionLaboratorio.js'></script>";?>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;						
		//-->
	</script><?php 

	//Verificamos si viene la edad en el get; de ser asi llamar la funcion seleccionarPruebas()
	if(isset($_GET['idMuestra']) && isset($_GET['fechaR'])){
		seleccionarProgramacion();
	}
	
	//Si esta definido el boton de Asignar, pasar las claves a la Session
	if(isset($_POST["sbt_guardar"])){
		$conn=conecta("bd_laboratorio");
		$idPrueba=$_POST["rdb_prueba"];
		$stm_sql="UPDATE plan_pruebas SET estado='1' WHERE id_plan_prueba='$idPrueba'";
		$rs=(mysql_query($stm_sql));
		mysql_close($conn);
		?>
		<script type="text/javascript" language="javascript">
			window.close();
		</script>
		<?php
	}
		
	//Función que permite mostrar las Pruebas
	function seleccionarProgramacion(){
		$conn = conecta("bd_laboratorio");
		
		//Recuperar las variables del GET
		$idMuestra = $_GET["idMuestra"];
		$fechaR = $_GET["fechaR"];
		
		$sql_stm = "SELECT id_plan_prueba,fecha_programada FROM plan_pruebas WHERE estado='0' AND muestras_id_muestra='$idMuestra' AND fecha_programada>'$fechaR'";
		$rs = mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			echo "<form name='frm_seleccionarAdelantoPrueba' onSubmit='return valSeleccionarAdelantoPrueba(this);' method='post' action='verPruebasProgramadas.php'>";
			echo "<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>$idMuestra</caption>";
			echo "  
				<tr>
					<td colspan='2' class='nombres_columnas' width='15%' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				echo "<tr>";?>	
				<td class="nombres_filas" align="center"><input type="radio" name="rdb_prueba" id="rdb_prueba" value="<?php echo $datos["id_plan_prueba"];?>"/></td><?php
				echo "
						<td class='$nom_clase' align='center'>$cont.-</td>
						<td class='$nom_clase' align='center'>".modFecha($datos["fecha_programada"],1)."</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";    
			}while($datos=mysql_fetch_array($rs));?>
				<tr>
					<td colspan="3" align="center">
					<br />
						<input type="submit" name="sbt_guardar" title="Registra el Adelanto de la Prueba Seleccionada" onmouseover="window.status='';return true;" 
						class="botones" value="Registrar"/>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_cerrar" title="Cancela el Registro de la Prueba Adelantada" class="botones" value="Cerrar" onclick="window.close();"/>
					</td>
				</tr>
			</table>
			</form><?php
		}
		mysql_close($conn);
	}//fin de la funcion seleccionarPruebas
	
	
?>