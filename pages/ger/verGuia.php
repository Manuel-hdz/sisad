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
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 19/Abril/2011
	  * Descripción: Este archivo muestra la imagen de los estados que puede tomar un empleado en el Kardex
	  **/ 
	
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";?>
	
	<table class='tabla_frm' cellpadding='5' width="100%" height="85%">
		<tr>
			<td class="nombres_columnas"><div align="center">CLAVE</div></td>
			<td class="nombres_columnas"><div align="center">DESCRIPCI&Oacute;N</div></td>
		</tr>
		<tr>
			<td class='nombres_filas'><div align="center"><strong>A</strong></div></td>
			<td class='renglon_gris'>ASISTENCIA</td>
		</tr>
		<tr>
			<td class='nombres_filas'><div align="center"><strong>F</strong></div></td>
			<td class='renglon_blanco'>FALTA</td>
		</tr>
		<tr>
			<td class='nombres_filas'><div align="center"><strong>V</strong></div></td>
			<td class='renglon_gris'>VACACIONES</td>
		</tr>
		<tr>
			<td class='nombres_filas'><div align="center"><strong>R</strong></div></td>
			<td class='renglon_blanco'>RETRASO</td>
		</tr>
		<tr>
			<td class='nombres_filas'><div align="center"><strong>J</strong></div></td>
			<td class='renglon_gris'>JUSTIFICACI&Oacute;N</td>
		</tr>
		<tr>
			<td class='nombres_filas'><div align="center"><strong>I</strong></div></td>
			<td class='renglon_blanco'>INCAPACIDAD</td>
		</tr>
		<tr>
		  <td class='nombres_filas'><div align="center"><strong>I-R-T</strong></div></td>
		  <td class='renglon_gris'>INCAPACIDAD POR RIESGO DE TRABAJO</td>
	  </tr>
		<tr>
		  <td class='nombres_filas'><div align="center"><strong>I-E</strong></div></td>
		  <td class='renglon_blanco'>INCAPACIDAD POR ENFERMEDAD</td>
	  </tr>
		<tr>
		  <td class='nombres_filas'><div align="center"><strong>RE</strong></div></td>
		  <td class='renglon_gris'>REGRESADO</td>
	  </tr>
	</table>
	<br />
	<p align="center">
		<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" 
		onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
	</p>
