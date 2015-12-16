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
	
	use \Sistema\Bootstrap\Boot;
	use \Sistema\Utilidades\ExcepcionHandler;
	
	/**
	 * ENV_MOD
	 * 
	 * Asigna el valor correspondiente para
	 * utilizar el mod_rewrite de apache
	 */
	define('ENV_APACHE', false);
	
	/**
	 * Carga de Bootstrap
	 * 
	 * Genera la carga del framework y la carga
	 * del administrador de excepciones para
	 * el proceso correspondiente.
	 * 
	 * Se genera la carga del autocargador correspondiente 
	 * para la carga de clases por autoload
	 */
	try {
		require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'Librerias', 'Cargador.php'));
		$boot = new Boot();
		$boot->ejecutar();
	}
	catch(exception $excepcion) {
		$excepciones = new ExcepcionHandler($excepcion);
		echo $excepciones->mostrarError();
	}