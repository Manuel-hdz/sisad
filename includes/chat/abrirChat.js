/**
  * Nombre del M�dulo: Chat
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 05/Octubre/2012
  * Descripci�n: Este archivo contiene funciones para ejecutar el Chat en Ventana Emergente
  */

function abrirVentanaChat(){
	ventanaChat = 'ventanaChat';
	window.open('verChat.php',ventanaChat,'top=10, left=10, width=1035, height=610, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}