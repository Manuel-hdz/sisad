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
	</script>
<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 02/Julio/2011
	  * Descripción: Este archivo contiene funciones para Ver las Pruebas de calidad del Catalogo y agregarselas a las Mezclas
	  **/
	   
	//Verificamos si viene la operacion en la URL ($_GET); de ser asi llamar la funcion seleccionarPruebas()
	if(isset($_GET['accion'])){		
		//Iniciar la SESSION para accesar a los datos guradados en ella
		session_start();
		seleccionarPruebas();
	}
	
	
	//Si esta definido el boton de Asignar, pasar las claves a la Session
	if(isset($_POST["sbt_asignar"])){
		//Iniciar la SESSION para almcenar los datos en ella
		session_start();
		
		//Obtener la cantidad de registros(pruebas) desplegados
		$tam = $_POST["hdn_tam"];
		$registroPruebas = array();
		
		//Guardar cada prueba seleccionada en el arreglo registroPruebas[]
		for($i=1;$i<$tam;$i++){
			if(isset($_POST["ckb_id$i"])){
				$registroPruebas[]=$_POST["ckb_id$i"];
			}
		}
		
		//Si la bandera vale 'si' vaciar los datos prexistentes en la SESSION, para guardar los nuevos
		if ($_POST["hdn_band"]=="si")
			unset($_SESSION["pruebasEjecutadas"]);
		
		//Guardar las pruebas seleccionadas en la SESSION
		$_SESSION["pruebasEjecutadas"] = $registroPruebas;?>
		
		
		<script type="text/javascript" language="javascript">
			//En la Ventana padre indicar que las Pruebas fueron cargadas y Deshabilitar el Boton de Cargar Pruebas
			window.opener.document.getElementById("hdn_pruebasCargadas").value = "si";
			window.opener.document.getElementById("btn_verPruebasLab").disabled = true;
			window.opener.document.getElementById("btn_verPruebasLab").title = "Las Pruebas ya Fueron Agregadas";
			window.close();
		</script><?php
	}//Cierre if(isset($_POST["sbt_asignar"]))
	
	
		
	//Función que permite mostrar las Pruebas
	function seleccionarPruebas(){	
		//Incluimos arrchivo de conexion
		include ("../../includes/conexion.inc");
		//Incluimos el archivo para modificar las fechas para la consulta
		include ("../../includes/func_fechas.php");
		//Hoja de estilo para la ventana emergente
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
		//Archivo de validacion para indicar pruebas seleccionadas
		echo "<script type='text/javascript' src='../../includes/validacionLaboratorio.js'></script>";
			

		//Creamos la consulta para mostrar las Pruebas
		$sql = "SELECT id_prueba,norma,nombre,descripcion FROM catalogo_pruebas";
	
		//Abrir conexion a la Base de Datos
		$conn = conecta("bd_laboratorio");
		//Ejecutar la consulta
		$rs = mysql_query($sql);
		if($datos = mysql_fetch_array($rs)){
			echo "	
			<form name='frm_relacionarPrueba' onsubmit='return valSeleccionarPruebas(this);' method='post' action='verPruebasLab.php'>
			<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>Seleccione las Pruebas Ejecutadas</caption></br>";
				echo "
					<tr>
						<td colspan='2' class='nombres_columnas' align='center'>REGISTRO</td>
						<td class='nombres_columnas' align='center'>NORMA</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
					<tr>					
						<td class='nombres_filas' align='center'><input type='checkbox' name='ckb_id$cont' id='ckb_id$cont' value='$datos[id_prueba]'/></td>
						<td class='nombres_filas' align='right'>$cont.-</td>
						<td class='$nom_clase' align='center'>$datos[norma]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						";						
			echo "</tr>";
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));?>
			
			
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan="5" align="center"><?php 
					//La variable bandera nos permitira indicar si ya estan definidos datos en la session, 
					//de modo que se pueda sustituir los datos siempre y cuando el usuario lo solicite
					$bandera="no";
					if (isset($_SESSION["pruebasEjecutadas"])){
						$bandera="si";
					}?>
					<input type="hidden" name="hdn_tam" id="hdn_tam" value="<?php echo $cont;?>"/>
					<input type="hidden" name="hdn_band" id="hdn_band" value="<?php echo $bandera;?>"/>
					<input type="submit" name="sbt_asignar" value="Agregar" class="botones" title="Agregar las Pruebas Seleccionadas al Registro del Resultado" 
					onMouseOver="window.estatus='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
					<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" 
					onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
				</td>
			</tr><?php
			echo "</form>";
		}
		else{
			echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
			echo "<p align='center' class='titulo_etiqueta'><b>No hay Pruebas Registradas en el Cat&aacute;logo de Pruebas</p>";?>
			<br /><br />
			<p align="center">
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" 
				onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
			</p><?php
		}
		//Cerrar la conexion
		mysql_close($conn);
	}//fin de la funcion seleccionarPruebas 
	
	
?>