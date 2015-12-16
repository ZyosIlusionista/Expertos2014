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
	
	namespace Sistema\Bootstrap;
	use \Neural\Excepcion;
	
	class Estructura {
		
		/**
		 * Estructura::ejecutar()
		 * 
		 * Genera el proceso de validacion de estructura
		 * @param string $directorio
		 * @return void
		 */
		protected function ejecutarEstructura($directorio = false) {
			$this->seleccion($directorio, 'Desarrollo');
			$this->seleccion($directorio, 'Produccion');
		}
		
		/**
		 * Estructura::ejecutarEstructuraModulo()
		 * 
		 * Genera la lista de directorios segun el
		 * entorno
		 * 
		 * @param string $directorio
		 * @param string $entorno
		 * @param string $modulo
		 * @return void
		 */
		private function ejecutarEstructuraModulo($directorio = false, $entorno = false, $modulo = false) {
			$listado = $this->estructuraModulo(ROOT_APPS, $directorio, $entorno, $modulo);
			foreach ($listado AS $ruta):
				$this->validacionExistencia($ruta);
			endforeach;
		}
		
		/**
		 * Estructura::seleccion()
		 * 
		 * Genera la lista de directorios segun el
		 * entorno
		 * 
		 * @param string $directorio
		 * @param string $entorno
		 * @return void
		 */
		private function seleccion($directorio = false, $entorno = false) {
			$listado = $this->estructura(ROOT_APPS, $directorio, $entorno);
			foreach ($listado as $ruta):
				$this->validacionExistencia($ruta);
			endforeach;
			unset($listado, $ruta);
		}
		
		/**
		 * Estructura::validacionExistencia()
		 * 
		 * Ejecuta las validaciones correspondientes
		 * @param array $array
		 * @return void
		 */
		private function validacionExistencia($array = false) {
			$this->directorioExistencia($array);
			$this->directorioLectura($array);
		}
		
		/**
		 * Estructura::directorioExistencia()
		 * 
		 * Genera la validacion de la existencia
		 * del directorio correspondiente
		 * 
		 * @param array $array
		 * @return void
		 */
		private function directorioExistencia($array = false) {
			if(is_dir($array['ruta']) == false):
				throw new Excepcion(sprintf('El directorio: %s no existe dentro de la aplicación.', $array['directorio']), 0);
				break;
			endif;
		}
		
		/**
		 * Estructura::directorioLectura()
		 * 
		 * Genera la validacion de la lectura del
		 * directorio correspondiente
		 * 
		 * @param array $array
		 * @return void
		 */
		private function directorioLectura($array = false) {
			if(is_readable($array['ruta']) == false):
				throw new Excepcion(sprintf('El directorio: %s no es posible leerlo.', $array['directorio']), 0);
				break;
			endif;
		}
		
		/**
		 * Estructura::estructura()
		 * 
		 * Genera el listado correspondiente de directorios de 
		 * la aplicacion
		 * 
		 * @param string $root
		 * @param string $directorio
		 * @param string $entorno
		 * @return array
		 */
		protected function estructura($root = false, $directorio = false, $entorno = false) {
			$lista[] = array('directorio' => $entorno, 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno)));
			$lista[] = array('directorio' => $entorno.' - Fuente', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos - Consola', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos', 'Consola')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos - Formularios', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos', 'Formularios')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos - Interfaces', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos', 'Interfaces')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos - ORM', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos', 'ORM')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos - ORM', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos', 'ORM', 'Entidades')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos - ORM', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos', 'ORM', 'Proxy')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos - Temporal', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos', 'Temporal')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Complementos - Utilidades', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Complementos', 'Utilidades')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Configuracion', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Configuracion')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Sistema', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Sistema')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Sistema - Modulos', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Sistema', 'Modulos')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Sistema - MVC', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Sistema', 'MVC')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Sistema - MVC - Controladores', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Sistema', 'MVC', 'Controladores')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Sistema - MVC - Modelos', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Sistema', 'MVC', 'Modelos')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Sistema - MVC - Vistas', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Sistema', 'MVC', 'Vistas')));
			$lista[] = array('directorio' => $entorno.' - Fuente - Sistema - Plantillas', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Fuente', 'Sistema', 'Plantillas')));
			$lista[] = array('directorio' => $entorno.' - Publico', 'ruta' => implode(DIRECTORY_SEPARATOR, array($root, $directorio, $entorno, 'Publico')));
			return $lista;
		}
		
		/**
		 * Estructura::estructuraModulo()
		 * 
		 * Genera el listado correspondiente de directorios de 
		 * la aplicacion
		 * 
		 * @param string $root
		 * @param string $directorio
		 * @param string $entorno
		 * @param string $modulo
		 * @return array
		 */
		protected function estructuraModulo($root = false, $directorio = false, $entorno = false, $modulo = false) {
			$lista[] = array('directorio' => $entorno.' - Modulo: '.$modulo, 'ruta' => implode(DIRECTORY_SEPARATOR, array($directorio)));
			$lista[] = array('directorio' => $entorno.' - Modulo: '.$modulo.' - Controladores', 'ruta' => implode(DIRECTORY_SEPARATOR, array($directorio, 'Controladores')));
			$lista[] = array('directorio' => $entorno.' - Modulo: '.$modulo.' - Modelos', 'ruta' => implode(DIRECTORY_SEPARATOR, array($directorio, 'Modelos')));
			$lista[] = array('directorio' => $entorno.' - Modulo: '.$modulo.' - Vistas', 'ruta' => implode(DIRECTORY_SEPARATOR, array($directorio, 'Vistas')));
			foreach ($lista AS $valor):
				$this->validacionExistencia($valor);
			endforeach;
		}
	}