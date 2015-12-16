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
	use \Neural\Excepcion;
	
	class MailPHP implements InterfaceCorreo {
		
		private $prioridad = 3;
		private $asunto = false;
		private $remitente = false;
		private $para = false;
		private $copia = false;
		private $copiaOculta = false;
		private $adjunto = false;
		private $mensaje = false;
		private $mensajeAlt = false;
		
		/**
		 * MailPHP::prioridadCorreo()
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
		public function prioridadCorreo($prioridad = 3) {
			if(is_numeric($prioridad) == true):
				$this->prioridadCorreoSeleccion($prioridad);
			else:
				throw new Excepcion('La prioridad debe ser numerico [ 1 - 5 ] en el proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::prioridadCorreoSeleccion()
		 * 
		 * Genera la asignacion de la prioridad
		 * @param integer $prioridad
		 * @return object
		 */
		private function prioridadCorreoSeleccion($prioridad = 3) {
			if($prioridad >= 1 AND $prioridad <= 5):
				$this->prioridad = $prioridad;
			else:
				throw new Excepcion(sprintf('La prioridad: %d, no es correcta solo es aceptado [ 1 - 5 ] en el proceso de MailPHP'), 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoAsunto()
		 * 
		 * Genera la asignacion del asunto del
		 * correo electronido
		 * 
		 * @param string $asunto
		 * @return object
		 */
		public function correoAsunto($asunto = false) {
			if(is_string($asunto) == true OR is_numeric($asunto) == true):
				$this->asunto = $asunto;
			else:
				throw new Excepcion('Debe ingresar el asunto para el proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoRemitente()
		 * 
		 * Genera la asignacion del remitente del
		 * correo electronico
		 * 
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		public function correoRemitente($correo = false, $nombre = false) {
			if(is_string($correo) == true):
				$this->correoRemitenteNombre($correo, $nombre);
			else:
				throw new Excepcion('Debe ingresar el correo electronico del Remitente del Proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoRemitenteNombre()
		 * 
		 * Genera la asignacion del correo electronico
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		private function correoRemitenteNombre($correo = false, $nombre = false) {
			if(is_string($nombre) == true):
				$this->remitente[$nombre] = $correo;
			else:
				$this->remitente[] = $correo;
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoDestinatario()
		 * 
		 * Genera la asignacion del destinatario al cual
		 * se le enviara el correo correspondiente
		 * 
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		public function correoDestinatario($correo = false, $nombre = false) {
			if(is_string($correo) == true):
				$this->correoDestinatarioNombre($correo, $nombre);
			else:
				throw new Excepcion('Debe ingresar el correo electronico del Destinatario del Proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoDestinatarioNombre()
		 * 
		 * Genera la asignacion correspondiente
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		private function correoDestinatarioNombre($correo = false, $nombre = false) {
			if(is_string($nombre) == true):
				$this->para[$nombre] = $correo;
			else:
				$this->para[] = $correo;
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoCopia()
		 * 
		 * Genera la asignacion del destinatario al cual
		 * se le enviara copia del correo correspondiente
		 * 
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		public function correoCopia($correo = false, $nombre = false) {
			if(is_string($correo) == true):
				$this->correoCopiaNombre($correo, $nombre);
			else:
				throw new Excepcion('Debe ingresar el correo electronico para la Copia de Destinatario del Proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoCopiaNombre()
		 * 
		 * Genera la asignacion correspondiente
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		private function correoCopiaNombre($correo = false, $nombre = false) {
			if(is_string($correo) == true):
				$this->copia[$nombre] = $correo;
			else:
				$this->copia[] = $correo;
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoCopiaOculta()
		 * 
		 * Genera la asignacion del destinatario al cual
		 * se le enviara copia del correo correspondiente
		 * 
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		public function correoCopiaOculta($correo = false, $nombre = false) {
			if(is_string($correo) == true):
				$this->correoCopiaOcultaNombre($correo, $nombre);
			else:
				throw new Excepcion('Debe ingresar el correo electronico para la Copia Oculta de Destinatario del Proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoCopiaOcultaNombre()
		 * 
		 * Genera la asignacion correspondiente
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		private function correoCopiaOcultaNombre($correo = false, $nombre = false) {
			if(is_string($nombre) == true):
				$this->copiaOculta[$nombre] = $correo;
			else:
				$this->copiaOculta[] = $correo;
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoAdjunto()
		 * 
		 * Genera el proceso de adjuntar un archivo
		 * para ser enviado en el correo correspondiente
		 * 
		 * @param string $archivo
		 * @return object
		 */
		public function correoAdjunto($archivo = false) {
			if(is_string($archivo) == true):
				$this->correoAdjuntoExistencia($archivo);
			else:
				throw new Excepcion('Debe ingresar el archivo adjunto del Proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoAdjuntoExistencia()
		 * 
		 * Genera el proceso de existencia del archivo adjunto
		 * @param string $archivo
		 * @return object
		 */
		private function correoAdjuntoExistencia($archivo = false) {
			if(file_exists($archivo) == true):
				$this->adjunto[] = $archivo;
			else:
				throw new Excepcion('El archivo adjunto no existe en el proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoMensaje()
		 * 
		 * Genera la asignacion del mensaje del correo
		 * el cual es HTML
		 * 
		 * @param string $mensaje
		 * @return object
		 */
		public function correoMensaje($mensaje = false) {
			if(is_string($mensaje) == true):
				$this->mensaje = $mensaje;
			else:
				throw new Excepcion('Debe ingresar el mensaje para el proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::correoMensajeAlternativo()
		 * 
		 * Genera la asignacion del mensaje del correo
		 * el cual es texto plano
		 * 
		 * @param string $mensaje
		 * @return object
		 */
		public function correoMensajeAlternativo($mensaje = false) {
			if(is_string($mensaje) == true):
				$this->mensajeAlt = $mensaje;
			else:
				throw new Excepcion('Debe ingresar el mensaje alternativo para el proceso de MailPHP', 0);
			endif;
			return $this;
		}
		
		/**
		 * MailPHP::enviarCorreo()
		 * 
		 * Genera el proceso de envio de correo electronico
		 * @return bool
		 */
		public function enviarCorreo() {
			$this->inputValidarArray('remitente', 'Debe ingresar el Remitente');
			$this->inputValidarArray('para', 'Debe ingresar el/los Destinatario(s)');
			$this->inputValidarString('asunto', 'Debe ingresar el Asunto del Mensaje');
			$this->inputValidarString('mensaje', 'Debe ingresar el Mensaje');
			
			return $this->enviarCorreoProcesar();
		}
		
		/**
		 * MailPHP::enviarCorreoProcesar()
		 * 
		 * Genera el proceso para el envio del correo
		 * electronico
		 * 
		 * @return bool
		 */
		private function enviarCorreoProcesar() {
			$cabecera[] = 'MIME-Version: 1.0';
			$cabecera[] = 'Content-type: text/html; charset=utf-8';
			$cabecera[] = 'X-Priority: '.$this->prioridad;
			$cabecera[] = 'To: '.$this->cabeceraPara();
			$cabecera[] = 'From: '.$this->cabeceraRemitente();
			
			if(is_array($this->copia) == true):
				$cabecera[] = 'Cc: '.implode(', ', $this->copia);
			endif;
			
			if(is_array($this->copiaOculta) == true):
				$cabecera[] = 'Bcc: '.implode(', ', $this->copiaOculta);
			endif;
			
			$cabecera[] = 'Reply-To: '.$this->cabeceraRemitente();
			$cabecera[] = 'Subject: '.$this->asunto;
			$cabecera[] = 'X-Mailer: PHP/'.phpversion();
			
			return \mail(implode(', ', $this->para), $this->asunto, $this->mensaje, implode("\r\n", $cabecera));
		}
		
		/**
		 * MailPHP::cabeceraRemitente()
		 * 
		 * Organiza la cabecera para el proceso
		 * del envio del correo
		 * 
		 * @return string
		 */
		private function cabeceraRemitente() {
			foreach ($this->remitente AS $nombre => $correo):
				$lista[] = sprintf('%s <%s>', $nombre, $correo);
			endforeach;
			return implode(', ', $lista);
		}
		
		/**
		 * MailPHP::cabeceraPara()
		 * 
		 * Organiza la cabecera para el proceso
		 * del envio del correo
		 * 
		 * @return string
		 */
		private function cabeceraPara() {
			foreach ($this->para AS $nombre => $correo):
				$lista[] = (is_numeric($nombre) == true) ? $correo : sprintf('%s <%s%>', $nombre, $correo);
			endforeach;
			return implode(', ', $lista);
		}
		
		/**
		 * MailPHP::inputValidarArray()
		 *
		 * Genera el proceso de validacion de la variable indicada 
		 * @param string $variable
		 * @param string $mensaje
		 * @return void
		 */
		private function inputValidarArray($variable = false, $mensaje = false) {
			if(is_array($this->{$variable}) == false):
				throw new Excepcion(sprintf('%s para el proceso de MailPHP', $mensaje), 0);
			endif;
		}
		
		/**
		 * MailPHP::inputValidarString()
		 * 
		 * Genera el proceso de validacion de la variable indicada 
		 * @param string $variable
		 * @param string $mensaje
		 * @return void
		 */
		private function inputValidarString($variable = false, $mensaje = false) {
			if(is_string($this->{$variable}) == false):
				throw new Excepcion(sprintf('%s para el proceso de MailPHP', $mensaje), 0);
			endif;
		}
	}