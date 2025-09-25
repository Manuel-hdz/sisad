<?php
	/**
	  * Nombre del Módulo: Topografia                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 06/Septiembre/2012
	  * Descripción: En este archivo estan las funciones mostra las graficas de Topografia
	  **/ 
	  
	  $grafica=$_GET["grafica"];
	  echo "<img src='$grafica' width='100%' height='100%' onclick='window.close()'/>";
?>