<?php
	
	/**
	 * NeuralPHP Framework
	 * Marco de trabajo para aplicaciones web.
	 * 
	 * @author Zyos (Carlos Parra) <Neural.Framework@gmail.com>
	 * @copyright 2006-2015 NeuralPHP Framework
	 * @license GNU General Public License as published by the Free Software Foundation; either version 2 of the License.  
	 * @see http://neuralframework.com/
	 * @version 4.0
	 * 
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or 1 any later version.
	 * 
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 */
	
	namespace Neural\Correo;
	
	interface InterfaceCorreo {
		
		/**
		 * InterfaceCorreo::prioridadCorreo()
		 * 
		 * Asigna la prioridad del correo
		 * 1 - prioridad Urgente
		 * 2 - prioridad Alta
		 * 3 - prioridad Normal
		 * 4 - prioridad Baja
		 * 5 - prioridad Muy Baja
		 * 
		 * @param integer $prioridad
		 * @return object
		 */
		public function prioridadCorreo($prioridad);
		
		/**
		 * InterfaceCorreo::correoAsunto()
		 * 
		 * Genera la asignacion del asunto del
		 * correo electronido
		 * 
		 * @param string $asunto
		 * @return object
		 */
		public function correoAsunto($asunto);
		
		/**
		 * InterfaceCorreo::correoRemitente()
		 * 
		 * Genera la asignacion del remitente del
		 * correo electronico
		 * 
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		public function correoRemitente($correo, $nombre);
		
		/**
		 * InterfaceCorreo::correoDestinatario()
		 * 
		 * Genera la asignacion del destinatario al cual
		 * se le enviara el correo correspondiente
		 * 
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		public function correoDestinatario($correo, $nombre);
		
		/**
		 * InterfaceCorreo::correoCopia()
		 * 
		 * Genera la asignacion del destinatario al cual
		 * se le enviara copia del correo correspondiente
		 * 
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		public function correoCopia($correo, $nombre);
		
		/**
		 * InterfaceCorreo::correoCopiaOculta()
		 * 
		 * Genera la asignacion del destinatario al cual
		 * se le enviara copia del correo correspondiente
		 * 
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		public function correoCopiaOculta($correo, $nombre);
		
		/**
		 * InterfaceCorreo::correoAdjunto()
		 * 
		 * Genera el proceso de adjuntar un archivo
		 * para ser enviado en el correo correspondiente
		 * 
		 * @param string $archivo
		 * @return object
		 */
		public function correoAdjunto($archivo);
		
		/**
		 * InterfaceCorreo::correoMensaje()
		 * 
		 * Genera la asignacion del mensaje del correo
		 * el cual es HTML
		 * 
		 * @param string $mensaje
		 * @return object
		 */
		public function correoMensaje($mensaje);
		
		/**
		 * InterfaceCorreo::correoMensajeAlternativo()
		 * 
		 * Genera la asignacion del mensaje del correo
		 * el cual es texto plano
		 * 
		 * @param string $mensaje
		 * @return object
		 */
		public function correoMensajeAlternativo($mensaje);
		
		/**
		 * InterfaceCorreo::enviarCorreo()
		 * 
		 * Genera el proceso del envio de correo electronico
		 * @return bool
		 */
		public function enviarCorreo();
	}