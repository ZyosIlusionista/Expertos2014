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
	
	namespace Sistema\Controlador\ValidarFormulario;
	use \Neural\Excepcion;
	
	class ReflexionFormato {
		
		/**
		 * ReflexionFormato::comentario()
		 * 
		 * Genera el formato correspondiente
		 * 
		 * @param string $comentario
		 * @return array
		 */
		public static function comentario($campo = false, $comentario = false) {
			if(is_string($comentario) == true):
				return self::formato($campo, $comentario);
			else:
				throw new Excepcion('No hay comentario para procesar en la propiedad correspondiente', 0);
			endif;
		}
		
		/**
		 * ReflexionFormato::formato()
		 *
		 * Retira los caracteres correspondiente al comentario
		 *  
		 * @param string $comentario
		 * @return array
		 */
		private static function formato($campo = false, $comentario = false) {
			$comentario = preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $comentario);
			$comentario = explode("\n", trim($comentario));
			return self::seleccion($campo, $comentario);
		}
		
		/**
		 * ReflexionFormato::seleccion()
		 * 
		 * Genera la seleccion correspondiente del comentario
		 * a base de los parametros indicados
		 * 
		 * @param array $array
		 * @return array
		 */
		private static function seleccion($campo = false, $array = false) {
			$parametros = array();
			foreach ($array as $comentario):
				if(preg_match('/@/', $comentario, $resultado, PREG_OFFSET_CAPTURE) == true):
					$param = self::seleccionFormato($campo, $comentario);
					$parametros[$param['nombre']] = $param['param'];
				endif;
			endforeach;
			return $parametros;
		}
		
		/**
		 * ReflexionFormato::seleccionFormato()
		 * 
		 * Genera el formato del parametro correspondiente
		 * @param string $comentario
		 * @return array
		 */
		private static function seleccionFormato($campo = false, $comentario = false) {
			$array = explode(' ', trim($comentario), 2);
			
			if(isset($array[0]) == true AND $array[1] == true):
				return array(
					'nombre' => mb_strtolower(str_replace('@', '', $array[0])),
					'param' => json_decode($array[1], true)
				);
			else:
				throw new Excepcion(sprintf('No se ha encontrado un parametro configurado para el procedimiento del campo: [ %s ] de validación de formularios', $campo), 0);
			endif;
		}
	}