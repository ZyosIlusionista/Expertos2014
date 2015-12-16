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
	 * CONSTANTE ROOT
	 * Define la ruta principal de la aplicacion
	 **/
	if(defined('ROOT') == false):
		define('ROOT', dirname(dirname(dirname(__DIR__))));
	endif;
	
	/**
	 * CONSTANTE ROOT_APPS
	 * Define la ruta de acceso a las aplicaciones
	 **/
	if(defined('ROOT_APPS') == false):
		define('ROOT_APPS', implode(DIRECTORY_SEPARATOR, array(ROOT, 'Apps')));
	endif;
	
	/**
	 * CONSTANTE ROOT_LIBRERIA
	 * Define la ruta de acceso a las librerias de Neural
	 **/
	if(defined('ROOT_LIBRERIA') == false):
		define('ROOT_LIBRERIA', implode(DIRECTORY_SEPARATOR, array(ROOT, 'Librerias')));
	endif;
	
	/**
	 * CONSTANTE ROOT_COMPLEMENTO
	 * define la ruta de los complementos
	 **/
	if(defined('ROOT_COMPLEMENTO') == false):
		define('ROOT_COMPLEMENTO', implode(DIRECTORY_SEPARATOR, array(ROOT_LIBRERIA, 'Complementos')));
	endif;
	
	/**
	 * COSNTANTE ROOT_CONFIG
	 * Define la ruta de acceso a la configuracion
	 **/
	if(defined('ROOT_CONFIG') == false):
		define('ROOT_CONFIG', implode(DIRECTORY_SEPARATOR, array(ROOT_LIBRERIA, 'Configuracion')));
	endif;
	
	/**
	 * CONSTANTE ROOT_LOG
	 * define la ruta de los logs de NeuralPHP Framework
	 **/
	if(defined('ROOT_LOG') == false):
		define('ROOT_LOG', implode(DIRECTORY_SEPARATOR, array(ROOT_COMPLEMENTO, 'Log')));
	endif;
	
	/**
	 * COSNTANTE ROOT_PROVEEDOR
	 * Define la ruta de acceso a los proveedores
	 **/
	if(defined('ROOT_PROVEEDOR') == false):
		define('ROOT_PROVEEDOR', implode(DIRECTORY_SEPARATOR, array(ROOT_COMPLEMENTO, 'Proveedores')));
	endif;
	
	/**
	 * CONSTANTE ROOT_SISTEMA
	 * Define la ruta del sistema
	 */
	if(defined('ROOT_SISTEMA') == false):
		define('ROOT_SISTEMA', implode(DIRECTORY_SEPARATOR, array(ROOT_LIBRERIA, 'Sistema')));
	endif;
	
	/**
	 * CONSTANTE ROOT_SRC
	 * De fine la ruta a los archivos publicos
	 */
	if(defined('ROOT_SRC') == false):
		define('ROOT_SRC', implode(DIRECTORY_SEPARATOR, array(ROOT, 'Src')));
	endif;