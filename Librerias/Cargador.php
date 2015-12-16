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
	
	/**
	 * Inclusion de Librerias
	 * 
	 * Genera la solicitud de archivos requeridos
	 * para el proceso del framework
	 */
	require 'Sistema'.DIRECTORY_SEPARATOR.'Variables'.DIRECTORY_SEPARATOR.'Rutas.php';
	require ROOT_SISTEMA.DIRECTORY_SEPARATOR.'AutoCargador.php';
	require ROOT_COMPLEMENTO.DIRECTORY_SEPARATOR.'Proveedores'.DIRECTORY_SEPARATOR.'Composer'.DIRECTORY_SEPARATOR.'autoload.php';
	
	/**
	 * Constante PREDETERMINADO
	 * 
	 * Asigna la aplicacion predeterminada dentro
	 * del framework
	 */
	define('PREDETERMINADO', 'Predeterminado');
	
	
	/**
	 * Autocargador de Clases
	 * 
	 * Se registran las diferentes clases
	 * con el autocargador de clases
	 */
	$sistema = new AutoCargador('Sistema', ROOT_LIBRERIA);
	$sistema->registrar();
	
	$neural = new AutoCargador('Neural', ROOT_LIBRERIA);
	$neural->registrar();
	
	$mvc = new AutoCargador('Mvc', ROOT_SISTEMA);
	$mvc->registrar();
	
	$cli = new AutoCargador('Cli', ROOT_SISTEMA);
	$cli->registrar();