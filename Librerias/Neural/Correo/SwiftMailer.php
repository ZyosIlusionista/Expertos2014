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
	use \Sistema\Utilidades\ConfigCorreo;
	
	class SwiftMailer implements InterfaceCorreo {
		
		private $entorno = false;
		private $confg = false;
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
		 * SwiftMailer::__construct()
		 * 
		 * Genera las variables necesarias para el proceso
		 * @param string $configuracion
		 * @param string $entorno
		 * @return void
		 */
		function __construct($configuracion = false, $entorno = false) {
			$this->entorno = $this->inputEntorno($entorno);
			$this->confg = $this->inputConfg($configuracion);
		}
		
		/**
		 * SwiftMailer::inputEntorno()
		 * 
		 * Genera la validacion del entorno correspondiente
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntorno($entorno = false) {
			if(is_string($entorno) == true):
				return $this->inputEntornoSeleccion($entorno);
			else:
				return $this->inputEntornoExistencia();
			endif;
		}
		
		/**
		 * SwiftMailer::inputEntornoExistencia()
		 * 
		 * Valida la existencia de la variable de entorno
		 * @return string
		 */
		private function inputEntornoExistencia() {
			if(defined('ENV_ENTORNO') == true):
				return $this->inputEntornoSeleccion(ENV_ENTORNO);
			else:
				throw new Excepcion('No hay un entorno indicado para el proceso de SwiftMailer', 0);
			endif;
		}
		
		/**
		 * SwiftMailer::inputEntornoSeleccion()
		 * 
		 * Genera el proceso de seleccion de entorno correspondiente
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntornoSeleccion($entorno = false) {
			if(array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion'))) == true):
				return $entorno;
			else:
				throw new Excepcion(sprintf('El entorno: %s no existe o es incorrecto para el proceso de SwiftMailer', $entorno), 0);
			endif;
		}
		
		/**
		 * SwiftMailer::inputConfg()
		 * 
		 * Genera la validacion de ingreso de la configuracion
		 * @param bool $confg
		 * @return array
		 */
		private function inputConfg($confg = false) {
			if(is_string($confg) == true):
				return $this->inputConfgExistencia($confg);
			else:
				throw new Excepcion('Es necesario el nombre de la configuración de correo para el proceso de SwiftMailer', 0);
			endif;
		}
		
		/**
		 * SwiftMailer::inputConfgExistencia()
		 * 
		 * Valida la existencia del de la configuracion
		 * @param string $confg
		 * @return array
		 */
		private function inputConfgExistencia($confg = false) {
			if(ConfigCorreo::confgExistencia($confg) == true):
				return ConfigCorreo::leer($confg, $this->entorno);
			else:
				throw new Excepcion(sprintf('La configuración: %s, no existe en el archivo de configuración de correo en el proceso de SwiftMailer'), 0);
			endif;
		}
		
		/**
		 * SwiftMailer::prioridadCorreo()
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
				throw new Excepcion('La prioridad debe ser numerico [ 1 - 5 ] en el proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::prioridadCorreoSeleccion()
		 * 
		 * Genera la asignacion de la prioridad
		 * @param integer $prioridad
		 * @return object
		 */
		private function prioridadCorreoSeleccion($prioridad = 3) {
			if($prioridad >= 1 AND $prioridad <= 5):
				$this->prioridad = $prioridad;
			else:
				throw new Excepcion(sprintf('La prioridad: %d, no es correcta solo es aceptado [ 1 - 5 ] en el proceso de SwiftMailer'), 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoAsunto()
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
				throw new Excepcion('Debe ingresar el asunto para el proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoRemitente()
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
				throw new Excepcion('Debe ingresar el correo electronico del Remitente del Proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoRemitenteNombre()
		 * 
		 * Genera la asignacion del correo electronico
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		private function correoRemitenteNombre($correo = false, $nombre = false) {
			if(is_string($nombre) == true):
				$this->remitente[$correo] = $nombre;
			else:
				$this->remitente[] = $correo;
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoDestinatario()
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
				throw new Excepcion('Debe ingresar el correo electronico del Destinatario del Proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoDestinatarioNombre()
		 * 
		 * Genera la asignacion correspondiente
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		private function correoDestinatarioNombre($correo = false, $nombre = false) {
			if(is_string($nombre) == true):
				$this->para[$correo] = $nombre;
			else:
				$this->para[] = $correo;
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoCopia()
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
				throw new Excepcion('Debe ingresar el correo electronico para la Copia de Destinatario del Proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoCopiaNombre()
		 * 
		 * Genera la asignacion correspondiente
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		private function correoCopiaNombre($correo = false, $nombre = false) {
			if(is_string($correo) == true):
				$this->copia[$correo] = $nombre;
			else:
				$this->copia[] = $correo;
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoCopiaOculta()
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
				throw new Excepcion('Debe ingresar el correo electronico para la Copia Oculta de Destinatario del Proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoCopiaOcultaNombre()
		 * 
		 * Genera la asignacion correspondiente
		 * @param string $correo
		 * @param string $nombre
		 * @return object
		 */
		private function correoCopiaOcultaNombre($correo = false, $nombre = false) {
			if(is_string($nombre) == true):
				$this->copiaOculta[$correo] = $nombre;
			else:
				$this->copiaOculta[] = $correo;
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoAdjunto()
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
				throw new Excepcion('Debe ingresar el archivo adjunto del Proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoAdjuntoExistencia()
		 * 
		 * Genera el proceso de existencia del archivo adjunto
		 * @param string $archivo
		 * @return object
		 */
		private function correoAdjuntoExistencia($archivo = false) {
			if(file_exists($archivo) == true):
				$this->adjunto[] = $archivo;
			else:
				throw new Excepcion('El archivo adjunto no existe en el proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoMensaje()
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
				throw new Excepcion('Debe ingresar el mensaje para el proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::correoMensajeAlternativo()
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
				throw new Excepcion('Debe ingresar el mensaje alternativo para el proceso de SwiftMailer', 0);
			endif;
			return $this;
		}
		
		/**
		 * SwiftMailer::enviarCorreo()
		 * 
		 * Genera el proceso del envio de correo electronico
		 * @return bool
		 */
		public function enviarCorreo() {
			$this->inputValidarArray('remitente', 'Debe ingresar el Remitente');
			$this->inputValidarArray('para', 'Debe ingresar el/los Destinatario(s)');
			$this->inputValidarString('asunto', 'Debe ingresar el Asunto del Mensaje');
			$this->inputValidarString('mensaje', 'Debe ingresar el Mensaje');
			
			return $this->enviarProcesoMensaje();
		}
		
		/**
		 * SwiftMailer::enviarProcesoMensaje()
		 * 
		 * Genera el proceso de creacion del mensaje 
		 * correspondiente
		 * 
		 * @return bool
		 */
		private function enviarProcesoMensaje() {
			$correo = \Swift_Message::newInstance();
			$correo->setSubject($this->asunto);
			$correo->setFrom($this->remitente);
			$correo->setTo($this->para);
			$correo->setPriority($this->prioridad);
			$correo->setBody($this->mensaje, 'text/html', 'utf-8');
			
			if(is_array($this->copia) == true):
				$correo->setCc($this->copia);
			endif;
			
			if(is_array($this->copiaOculta) == true):
				$correo->setBcc($this->copiaOculta);
			endif;
			
			if(is_string($this->mensajeAlt) == true):
				$correo->addPart($this->mensajeAlt, 'text/plain', 'utf-8');
			endif;
			
			if(is_array($this->adjunto) == true):
				foreach ($this->adjunto as $archivo):
					$correo->attach(\Swift_Attachment::fromPath($archivo));
				endforeach;
			endif;
			
			return $this->enviarProcesoTransporte($correo);
		}
		
		/**
		 * SwiftMailer::enviarProcesoTransporte()
		 * 
		 * Asigna las variables correspondientes para 
		 * preparar el envio del correo
		 * 
		 * @param string $correo
		 * @return bool
		 */
		private function enviarProcesoTransporte($correo = false) {
			$transporte = \Swift_SmtpTransport::newInstance();
			
			foreach ($this->confg AS $entrada => $valor):
				$formato = 'set'.ucfirst($entrada);
				$transporte->{$formato}($valor);
			endforeach;
			
			$eMail = \Swift_Mailer::newInstance($transporte);
			return $eMail->send($correo);
		}
		
		/**
		 * SwiftMailer::inputValidarArray()
		 *
		 * Genera el proceso de validacion de la variable indicada 
		 * @param string $variable
		 * @param string $mensaje
		 * @return void
		 */
		private function inputValidarArray($variable = false, $mensaje = false) {
			if(is_array($this->{$variable}) == false):
				throw new Excepcion(sprintf('%s para el proceso de SwiftMailer', $mensaje), 0);
			endif;
		}
		
		/**
		 * SwiftMailer::inputValidarString()
		 * 
		 * Genera el proceso de validacion de la variable indicada 
		 * @param string $variable
		 * @param string $mensaje
		 * @return void
		 */
		private function inputValidarString($variable = false, $mensaje = false) {
			if(is_string($this->{$variable}) == false):
				throw new Excepcion(sprintf('%s para el proceso de SwiftMailer', $mensaje), 0);
			endif;
		}
	}