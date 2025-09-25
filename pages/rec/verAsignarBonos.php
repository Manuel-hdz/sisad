<?php
	/**
	  * Nombre del M�dulo: Recursos Humanos                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 22/Marzo/2011
	  * Descripci�n: Este archivo contiene el listado de bonos que pueden ser asignados a un empleado
	  **/ 

	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("../../includes/func_fechas.php");?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />	
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �CONCRETO LANZADO DE FRESNILLO MARCA');
			}
		}
		document.onmousedown=click;				
		//-->
	</script>	
	
	<style type="text/css">
		<!--
		#tabla-bonos {position:absolute; left:20px; top:10px; width:780px; height:550px; z-index:1; overflow:scroll; }				
		#botones { position:absolute; left:20px; top:600px; width:810px; height:40px; z-index:2; }
		#div-procesando { position:absolute; left:20px; top:10px; width:780px; height:246px; z-index:3; }
		-->
    </style>
</head>
<body><?php	
	
	if(!isset($_POST['sbt_asignar'])){?>		
		<form onSubmit="return valFormSeleccionarBonos(this);" name="frm_seleccionarBonos" method="post" action="">
		<fieldset class="borde_seccion2" id="tabla-bonos" name="tabla-bonos">		
			<p align="center" class="titulo_etiqueta">Seleccionar Bonos para Ser Asignados al Empleado(a): <?php echo $_GET['nomEmpleado']; ?></p><?php		
			//Conectarse con la bd de Recursos Humanos
			$conn = conecta("bd_recursos");
			
			//Crear y Ejecutar la Sentencia SQL para consultar los Bonos
			$rs_bonos = mysql_query("SELECT * FROM bonos ORDER BY id");
			
			//Esta variable guardar� la cantidad de bonos existentes
			$cantBonos =  mysql_num_rows($rs_bonos);
			
			//Esta variable indicar� si aparece activo o desactivado el boton de ASIGNAR dependiendo de la existencia de bonos
			$bonosReg = 0;
			
			if($datosBonos=mysql_fetch_array($rs_bonos)){
				$bonosReg = 1;?>
				
				<table width="100%" cellpadding="5" class="tabla_frm">
					<caption class="titulo_etiqueta">
						Total Bonificaci&oacute;n&nbsp;$&nbsp;
						<input type="text" name="txt_totalBono" id="txt_totalBono" class="caja_de_texto" readonly="readonly" size="15" value="0.00" style="text-align:right;" />
						<input type="hidden" name="hdn_cantBonos" id="hdn_cantBonos" value="<?php echo $cantBonos; ?>" />
					</caption>
					<tr>
						<td class="nombres_columnas" align="center">SELECCIONAR</td>
						<td class="nombres_columnas" align="center">ID BONO</td>
						<td class="nombres_columnas" align="center">NOMBRE</td>
						<td class="nombres_columnas" align="center">DESCRIPC&Oacute;N</td>
						<td class="nombres_columnas" align="center">CANTIDAD</td>
						<td class="nombres_columnas" align="center">AUTORIZ&Oacute;</td>
						<td class="nombres_columnas" align="center">FECHA REGISTRO</td>
					</tr><?php
				//Manipular el Estilo de los renglones dibujados en la tabla
				$nom_clase = "renglon_gris";
				$cont = 1;
				
				do{?>
					<tr>
						<td class="nombres_filas" align="center">
							<input type="checkbox" name="chk_idBono<?php echo $cont; ?>" id="chk_idBono<?php echo $cont; ?>" 
							value="<?php echo $datosBonos['nom_bono']."_".$datosBonos['cantidad']; ?>" 
							onclick="sumarBono(this,<?php echo $datosBonos['cantidad']; ?>);" />
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datosBonos['id']; ?></td>
						<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datosBonos['nom_bono']; ?></td>
						<td class="<?php echo $nom_clase; ?>" align="left"><?php echo $datosBonos['descripcion']; ?></td>
						<td class="<?php echo $nom_clase; ?>" align="center">$ <?php echo number_format($datosBonos['cantidad'],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datosBonos['autorizo']; ?></td>
						<td class="<?php echo $nom_clase; ?>" align="center"><?php echo modFecha($datosBonos['fecha_bono'],1); ?></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datosBonos=mysql_fetch_array($rs_bonos));?>
				</table><?php			
			}//Cierre if($datosBonos=mysql_fetch_array($rs_bonos))
			else{?>
				<p align="center" class="msje_correcto">No Hay Bonos Registrados, Ir a la Secci&oacute;n de Bonos para Agregar</p><?php
			}?>
				
		</fieldset>
		
		<div id="botones" align="center">
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td align="center"><?php
						if($bonosReg==1){?>
							<input type="submit" name="sbt_asignar" id="sbt_asignar" class="botones" title="Asignar Bonos al Empleado" value="Asignar" 
							onmouseover="window.status='';return true" />
							&nbsp;&nbsp;&nbsp;<?php
						}?>
						<input type="button" name="btn_cerrar" id="btn_cerrar" class="botones" title="Cancelar Asignaci&oacute;n de Bonos" value="Cerrar" 
						onclick="window.close();" />
					</td>
				</tr>
			</table>
		</div>
	
		</form><?php
	}//Cierre if(!isset($_POST['sbt_asignar'])) 
	else{
			
		//Concatenar el ID de los bonos seleccionados
		$idBonos = "";
		foreach($_POST as $ind => $valor){
			if(substr($ind,0,3)=="chk"){
				//Despues de agregar el primer ID, agregar una coma antes de agregar el siguiente ID
				if(strlen($idBonos)>0)
					$idBonos .= "�";
					
				//Agregar el ID de cada Bono seleccionado	
				$idBonos .= $valor;
			}
		}
		
		//Obtener el valor total de la Bonificaci�n y del Sueldo Total
		$bonificacion = doubleval(str_replace(",","",$_POST['txt_totalBono']));//Quitar coma ','
		$sueldoTotal = str_replace(",","",$_GET['sueldoTotal']);//Quitar coma ','
		$sueldoTotal = doubleval(str_replace("$","",$sueldoTotal));//Quitar signo de moneda '$'
		//Obtener el sueldo total
		$total = $bonificacion + $sueldoTotal; ?>				
		
		<script type="text/javascript" language="javascript">
			<?php //Asignar el valor de los bonos asignados a la caja de la columna de bonificaci�n ?>
			window.opener.document.getElementById("txt_bonificacion"+<?php echo $_GET['noReg']; ?>).value = "$<?php echo $_POST['txt_totalBono']; ?>";
			window.opener.document.getElementById("hdn_idBonos"+<?php echo $_GET['noReg']; ?>).value = "<?php echo $idBonos; ?>";
			
			<?php //Asignar el nuevo total a la Caja de texto que lo mostrar� en la ventana Padre ?>
			window.opener.document.getElementById("txt_sueldoTotal"+<?php echo $_GET['noReg']; ?>).value = "$<?php echo number_format($total,2,".",","); ?>";
						
			<?php //Cerrar la Ventana ?>
			window.close();			
		</script><?php		
	}?>
	
</body>
</html>