<?php
	/**
	  * Nombre del M�dulo: Topografia                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 06/Septiembre/2012
	  * Descripci�n: En este archivo estan las funciones mostra las graficas de Topografia
	  **/ 
	  
	  $grafica=$_GET["grafica"];
	  echo "<img src='$grafica' width='100%' height='100%' onclick='window.close()'/>";
?>