<?php
	
	namespace Consola\SQL;
	use \Mvc\Consola;
	
	class Tablas extends Consola {
		
		private $conexion, $esquema, $plataforma, $opcForeign;
		private $estados;
		private $usuarios_empresa, $usuarios_cargo, $usuarios;
		private $permisos, $permisos_modulo, $permisos_acceso, $permisos_seleccion;
		private $guiones, $guiones_asignacion, $guiones_registro_tipo, $guiones_registro, $guiones_registro_afectacion, $guiones_registro_ubicacion;
		
		function __construct() {
			$conexion = new \Neural\BD\Conexion(APPBD);
			$this->conexion = $conexion->doctrineDBAL();
			$this->esquema = new \Doctrine\DBAL\Schema\Schema();
			$this->plataforma = $this->conexion->getDatabasePlatform();
			$this->opcForeign = array('onDelete' => 'no action', 'onUpdate' => 'no action');
		}
		
		public function ejecutar() {
			#---------------------------------------
			$this->tbl_estados();
			#---------------------------------------
			$this->tbl_permisos();
			$this->tbl_permisos_modulos();
			$this->tbl_permisos_acceso();
			$this->tbl_permisos_seleccion();
			#---------------------------------------
			$this->tbl_usuarios_empresa();
			$this->tbl_usuarios_cargo();
			$this->tbl_usuarios();
			#---------------------------------------
			$this->tbl_guiones();
			$this->tbl_guiones_asignacion();
			$this->tbl_guiones_registro_tipo_formulario();
			$this->tbl_guiones_registro_afectacion();
			$this->tbl_guiones_registro_ubicacion();
			$this->tbl_guiones_registro();
			#---------------------------------------
			
			
			#---------------------------------------
			$queries = $this->esquema->toSql($this->plataforma);
			$dropSchema = $this->esquema->toDropSql($this->plataforma);
			#---------------------------------------
			//print_r($dropSchema);
			//print_r($queries);
			
			print_r("\n\n");
			print_r(implode("\n", $dropSchema));
			print_r("\n\n");
			print_r("\n\n");
			print_r(implode("\n\n", $queries));
			print_r("\n\n");
			
			foreach ($queries as $sql):
				$this->conexion->executeQuery($sql);
			endforeach;
			
			$this->conexion->insert('ESTADOS', array('NOMBRE' => 'ACTIVO'));
			$this->conexion->insert('ESTADOS', array('NOMBRE' => 'INACTIVO'));
			$this->conexion->insert('ESTADOS', array('NOMBRE' => 'PENDIENTE'));
			$this->conexion->insert('ESTADOS', array('NOMBRE' => 'FINALIZADO'));
			$this->conexion->insert('ESTADOS', array('NOMBRE' => 'GUARDADO'));
			$this->conexion->insert('ESTADOS', array('NOMBRE' => 'ELIMINADO'));
			
			$this->conexion->insert('PERMISOS_MODULOS', array('ID' => 1, 'NOMBRE' => 'Central', 'ESTADO' => 1));
			$this->conexion->insert('PERMISOS_MODULOS', array('ID' => 2, 'NOMBRE' => 'Usuarios', 'ESTADO' => 1));
			$this->conexion->insert('PERMISOS_MODULOS', array('ID' => 3, 'NOMBRE' => 'Guiones', 'ESTADO' => 1));
			$this->conexion->insert('PERMISOS_MODULOS', array('ID' => 4, 'NOMBRE' => 'Permisos', 'ESTADO' => 1));
			
			$this->conexion->insert('PERMISOS_ACCESO', array('ID' => 1, 'NOMBRE' => 'ACCESO TOTAL', 'LECTURA' => 1, 'ESCRITURA' => 1, 'ACTUALIZAR' => 1, 'ELIMINAR' => 1));
			
			$this->conexion->insert('PERMISOS', array('ID' => 1, 'NOMBRE' => 'ADMINISTRADOR', 'ESTADO' => 1));
			$this->conexion->insert('PERMISOS_SELECCION', array('ID' => 1, 'PERMISO' => 1, 'MODULO' => 1, 'ACCESO' => 1));
			$this->conexion->insert('PERMISOS_SELECCION', array('ID' => 2, 'PERMISO' => 1, 'MODULO' => 2, 'ACCESO' => 1));
			$this->conexion->insert('PERMISOS_SELECCION', array('ID' => 3, 'PERMISO' => 1, 'MODULO' => 3, 'ACCESO' => 1));
			$this->conexion->insert('PERMISOS_SELECCION', array('ID' => 4, 'PERMISO' => 1, 'MODULO' => 4, 'ACCESO' => 1));
			
			$this->conexion->insert('USUARIOS_EMPRESA', array('ID' => 1, 'NOMBRE' => 'CLARO', 'ESTADO' => 1));
			$this->conexion->insert('USUARIOS_CARGO', array('ID' => 1, 'NOMBRE' => 'ADMINISTRADOR DEL SISTEMA'));
			$this->conexion->insert('USUARIOS', array('ID' => 1, 'USUARIO' => 'ADMIN', 'PASSWORD' => sha1('123'), 'NOMBRE' => 'ADMINISTRADOR', 'APELLIDO' => 'DEL SISTEMA', 'CEDULA' => 1234567890, 'USUARIO_RR' => 'ADMINRR', 'CORREO' => 'ADMIN@ADMIN.COM', 'ESTADO' => 1, 'EMPRESA' => 1, 'CARGO' => 1, 'PERMISO' => 1));
			
			$this->conexion->insert('GUIONES_REGISTRO_TIPO', array('ID' => 1, 'NOMBRE' => 'HFC', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_TIPO', array('ID' => 2, 'NOMBRE' => 'MATRIZ', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_TIPO', array('ID' => 3, 'NOMBRE' => 'PLATAFORMA', 'ESTADO' => 1));
			
			$this->conexion->insert('GUIONES_REGISTRO_AFECTACION', array('ID' => 1, 'NOMBRE' => 'SIN SEÑAL', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_AFECTACION', array('ID' => 2, 'NOMBRE' => 'SEÑAL DEFICIENTE', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_AFECTACION', array('ID' => 3, 'NOMBRE' => 'SEÑAL PIXELADA', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_AFECTACION', array('ID' => 4, 'NOMBRE' => 'SEÑAL PIXELADA PLATAFORMA', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_AFECTACION', array('ID' => 5, 'NOMBRE' => 'SIN NIVELES', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_AFECTACION', array('ID' => 6, 'NOMBRE' => 'DESFASE RUIDO', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_AFECTACION', array('ID' => 7, 'NOMBRE' => 'DESFASE POTENCIA', 'ESTADO' => 1));
			$this->conexion->insert('GUIONES_REGISTRO_AFECTACION', array('ID' => 8, 'NOMBRE' => 'DESFASE NIVELES', 'ESTADO' => 1));
			
			
		}
		
		/**
		 * Tablas::tbl_estados()
		 * 
		 * Genera la tabla general de estados
		 * @return void
		 */
		private function tbl_estados() {
			$this->estados = $this->esquema->createTable('ESTADOS');
			$this->estados->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del Estado'
			));
			$this->estados->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre del Estado'
			));
			$this->estados->setPrimaryKey(array('ID'));
		}
		
		/**
		 * Tablas::tbl_usuarios_empresa()
		 * 
		 * Genera la tabla de usuarios empresa
		 * @return void
		 */
		private function tbl_usuarios_empresa() {
			$this->usuarios_empresa = $this->esquema->createTable('USUARIOS_EMPRESA');
			$this->usuarios_empresa->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID de la Empresa'
			));
			$this->usuarios_empresa->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre de la Empresa'
			));
			$this->usuarios_empresa->addColumn('ESTADO', 'bigint', array(
				'notnull' => true,
				'length' => 20,
				'comment' => 'Estado de la Empresa [ID de la tabla ESTADOS]'
			));
			$this->usuarios_empresa->setPrimaryKey(array('ID'));
			$this->usuarios_empresa->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_usuarios_cargo()
		 * 
		 * Genera la tabla de cargos de usuario
		 * @return void
		 */
		private function tbl_usuarios_cargo() {
			$this->usuarios_cargo = $this->esquema->createTable('USUARIOS_CARGO');
			$this->usuarios_cargo->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del Cargo'
			));
			$this->usuarios_cargo->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre del Cargo'
			));
			$this->usuarios_cargo->setPrimaryKey(array('ID'));
		}
		
		/**
		 * Tablas::tbl_permisos()
		 * 
		 * Genera la tabla de permisos
		 * @foreign Estados ID 
		 * @return void
		 */
		private function tbl_permisos() {
			$this->permisos = $this->esquema->createTable('PERMISOS');
			$this->permisos->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del Permiso'
			));
			$this->permisos->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre del Permiso'
			));
			$this->permisos->addColumn('ESTADO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Estado del Permiso [ID de la tabla ESTADOS]'
			));
			$this->permisos->setPrimaryKey(array('ID'));
			$this->permisos->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_permisos_modulos()
		 * 
		 * Genera la tabla de permisos modulos
		 * donde lista los modulos de la aplicacion
		 * 
		 * @foreign ESTADOS
		 * @return void
		 */
		private function tbl_permisos_modulos() {
			$this->permisos_modulo = $this->esquema->createTable('PERMISOS_MODULOS');
			$this->permisos_modulo->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del Modulo'
			));
			$this->permisos_modulo->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre del Modulo'
			));
			$this->permisos_modulo->addColumn('ESTADO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Estado del Modulo [ID de la tabla ESTADOS]'
			));
			$this->permisos_modulo->setPrimaryKey(array('ID'));
			$this->permisos_modulo->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_usuarios()
		 * 
		 * Genera la tabla de usuarios correspondientes
		 * 
		 * @foreign ESTADOS
		 * @foreign USUARIOS_EMPRESA
		 * @foreign USUARIOS_CARGO
		 * 
		 * @return void
		 */
		private function tbl_usuarios() {
			$this->usuarios = $this->esquema->createTable('USUARIOS');
			$this->usuarios->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del Usuario'
			));
			$this->usuarios->addColumn('USUARIO', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Usuario de Ingreso'
			));
			$this->usuarios->addColumn('PASSWORD', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Contraseña de Ingreso'
			));
			$this->usuarios->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombres del Usuario'
			));
			$this->usuarios->addColumn('APELLIDO', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Apellidos del Usuario'
			));
			$this->usuarios->addColumn('CEDULA', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'Cedula del Usuario'
			));
			$this->usuarios->addColumn('USUARIO_RR', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Usuario de RR'
			));
			$this->usuarios->addColumn('CORREO', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Correo electronico del Usuario'
			));
			$this->usuarios->addColumn('ESTADO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Estado del Usuario [ID de la tabla ESTADOS]'
			));
			$this->usuarios->addColumn('EMPRESA', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID de la Empresa del Usuario [ID de la tabla USUARIOS_EMPRESA]'
			));
			$this->usuarios->addColumn('CARGO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Cargo del Usuario [ID de la tabla USUARIOS_CARGO]'
			));
			$this->usuarios->addColumn('PERMISO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Permiso del Usuario [ID de la tabla PERMISOS]'
			));
			
			$this->usuarios->setPrimaryKey(array('ID'));
			$this->usuarios->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
			$this->usuarios->addForeignKeyConstraint($this->usuarios_empresa, array('EMPRESA'), array('ID'), $this->opcForeign);
			$this->usuarios->addForeignKeyConstraint($this->usuarios_cargo, array('CARGO'), array('ID'), $this->opcForeign);
			$this->usuarios->addForeignKeyConstraint($this->permisos, array('PERMISO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_permisos_acceso()
		 * 
		 * Genera la tabla de referencia de acceso de permisos
		 * @return void
		 */
		private function tbl_permisos_acceso() {
			$this->permisos_acceso = $this->esquema->createTable('PERMISOS_ACCESO');
			$this->permisos_acceso->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del Permiso Acceso'
			));
			$this->permisos_acceso->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre de los permisos del Acceso'
			));
			$this->permisos_acceso->addColumn('LECTURA', 'boolean', array(
				'notnull' => true, 
				'default' => 0,
				'comment' => 'Permiso de Lectura'
			));
			$this->permisos_acceso->addColumn('ESCRITURA', 'boolean', array(
				'notnull' => true, 
				'default' => 0,
				'comment' => 'Permiso de Escritura'
			));
			$this->permisos_acceso->addColumn('ACTUALIZAR', 'boolean', array(
				'notnull' => true, 
				'default' => 0,
				'comment' => 'Permiso de Actualizar'
			));
			$this->permisos_acceso->addColumn('ELIMINAR', 'boolean', array(
				'notnull' => true, 
				'default' => 0,
				'comment' => 'Permiso de Eliminar'
			));
			$this->permisos_acceso->setPrimaryKey(array('ID'));
		}
		
		/**
		 * Tablas::tbl_permisos_seleccion()
		 * 
		 * Genera la tabla intermedia de permisos para el proceso
		 * de asignacion de permisos
		 * 
		 * @foreign PERMISOS
		 * @foreign PERMISOS_MODULOS
		 * @foreign PERMISOS_ACCESO
		 * @return void
		 */
		private function tbl_permisos_seleccion() {
			$this->permisos_seleccion = $this->esquema->createTable('PERMISOS_SELECCION');
			$this->permisos_seleccion->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del Permiso Seleccion'
			));
			$this->permisos_seleccion->addColumn('PERMISO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Permiso del Usuario [ID de la tabla PERMISOS]'
			));
			$this->permisos_seleccion->addColumn('MODULO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Permiso del Modulo [ID de la tabla PERMISOS_MODULOS]'
			));
			$this->permisos_seleccion->addColumn('ACCESO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Permiso del Permiso Acceso [ID de la tabla PERMISOS_ACCESO]'
			));
			$this->permisos_seleccion->setPrimaryKey(array('ID'));
			$this->permisos_seleccion->addForeignKeyConstraint($this->permisos, array('PERMISO'), array('ID'), $this->opcForeign);
			$this->permisos_seleccion->addForeignKeyConstraint($this->permisos_modulo, array('MODULO'), array('ID'), $this->opcForeign);
			$this->permisos_seleccion->addForeignKeyConstraint($this->permisos_acceso, array('ACCESO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_guiones()
		 * 
		 * Genera la tabla para las distintas plantillas de
		 * los diferentes guiones
		 * 
		 * @foreign ESTADOS
		 * @return void
		 */
		private function tbl_guiones() {
			$this->guiones = $this->esquema->createTable('GUIONES');
			$this->guiones->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID de guion'
			));
			$this->guiones->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre de la plantilla del guion'
			));
			$this->guiones->addColumn('PLANTILLA', 'text', array(
				'notnull' => true, 
				'length' => false,
				'comment' => 'plantilla del guion'
			));
			$this->guiones->addColumn('ESTADO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Estado del Guion [ID de la tabla ESTADOS]'
			));
			$this->guiones->setPrimaryKey(array('ID'));
			$this->guiones->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_guiones_asignacion()
		 * 
		 * Genera la tabla de asignaciones de plantillas
		 * para las diferentes partes o formularios que 
		 * se requieren
		 * 
		 * @foreign GUIONES
		 * @foreign ESTADOS
		 * 
		 * @return void
		 */
		private function tbl_guiones_asignacion() {
			$this->guiones_asignacion = $this->esquema->createTable('GUIONES_ASIGNACION');
			$this->guiones_asignacion->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID de asignación del guion'
			));
			$this->guiones_asignacion->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre de la asignacion'
			));
			$this->guiones_asignacion->addColumn('GUION', 'bigint', array(
				'notnull' => true,
				'length' => 20,
				'comment' => 'ID del del guion [ID de la tabla de GUIONES]'
			));
			$this->guiones_asignacion->addColumn('ESTADO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Estado de la asignacion [ID de la tabla ESTADOS]'
			));
			$this->guiones_asignacion->setPrimaryKey(array('ID'));
			$this->guiones_asignacion->addForeignKeyConstraint($this->guiones, array('GUION'), array('ID'), $this->opcForeign);
			$this->guiones_asignacion->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_guiones_tipo_formulario()
		 * 
		 * Genera el listado de tipos de guion los cuales seran utilizados
		 * para generar los distintos tipos de estadisticas
		 * 
		 * @foreign ESTADOS
		 * @return void
		 */
		private function tbl_guiones_registro_tipo_formulario() {
			$this->guiones_registro_tipo = $this->esquema->createTable('GUIONES_REGISTRO_TIPO');
			$this->guiones_registro_tipo->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del tipo de guion'
			));
			$this->guiones_registro_tipo->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre del tipo de guion'
			));
			$this->guiones_registro_tipo->addColumn('ESTADO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Estado del tipo de Guion [ID de la tabla ESTADOS]'
			));
			$this->guiones_registro_tipo->setPrimaryKey(array('ID'));
			$this->guiones_registro_tipo->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_guiones_registro_afectacion()
		 * 
		 * Genera la tabla correspondiente a las afectaciones que
		 * pueden tener los guiones registros
		 * 
		 * @foreign ESTADOS
		 * @return void
		 */
		private function tbl_guiones_registro_afectacion() {
			$this->guiones_registro_afectacion = $this->esquema->createTable('GUIONES_REGISTRO_AFECTACION');
			$this->guiones_registro_afectacion->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID de la afectacion'
			));
			$this->guiones_registro_afectacion->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre de la afectacion'
			));
			$this->guiones_registro_afectacion->addColumn('ESTADO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Estado de la afectacion [ID de la tabla ESTADOS]'
			));
			$this->guiones_registro_afectacion->setPrimaryKey(array('ID'));
			$this->guiones_registro_afectacion->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_guiones_registro_ubicacion()
		 * 
		 * Genera la tabla donde se establece la ubicacion del
		 * problema en este caso si es sector, nodo, regional, ciudad
		 * 
		 * @foreign ESTADOS
		 * @return void
		 */
		private function tbl_guiones_registro_ubicacion() {
			$this->guiones_registro_ubicacion = $this->esquema->createTable('GUIONES_REGISTRO_UBICACION');
			$this->guiones_registro_ubicacion->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID de la ubicacion'
			));
			$this->guiones_registro_ubicacion->addColumn('NOMBRE', 'string', array(
				'notnull' => true, 
				'length' => 255,
				'comment' => 'Nombre de la ubicacion'
			));
			$this->guiones_registro_ubicacion->addColumn('ESTADO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del Estado de la ubicacion [ID de la tabla ESTADOS]'
			));
			$this->guiones_registro_ubicacion->setPrimaryKey(array('ID'));
			$this->guiones_registro_ubicacion->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), $this->opcForeign);
		}
		
		/**
		 * Tablas::tbl_guiones_registro()
		 *
		 * tabla de registro de guiones ingresados a la base
		 * de datos
		 * 
		 * @foreign USUARIOS
		 * @foreign GUIONES_REGISTRO_TIPO
		 * @foreign GUIONES_REGISTRO_AFECTACION
		 * @foreign GUIONES_REGISTRO_UBICACION
		 * @return void
		 */
		private function tbl_guiones_registro() {
			$this->guiones_registro = $this->esquema->createTable('GUIONES_REGISTRO');
			$this->guiones_registro->addColumn('ID', 'bigint', array(
				'notnull' => true,
				'autoincrement' => true, 
				'length' => 20,
				'comment' => 'ID del registro del guion'
			));
			$this->guiones_registro->addColumn('FECHA', 'datetime', array(
				'notnull' => false,
				'comment' => 'Fecha de ingreso del registro'
			));
			$this->guiones_registro->addColumn('USUARIO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del usuario del registro [ID de la tabla USUARIOS]'
			));
			$this->guiones_registro->addColumn('TIPO', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID del tipo de registro [ID de la tabla GUIONES_REGISTRO_TIPO]'
			));
			$this->guiones_registro->addColumn('AFECTACION', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID de la afectacion del registro [ID de la tabla GUIONES_REGISTRO_AFECTACION]'
			));
			$this->guiones_registro->addColumn('AVISO', 'bigint', array(
				'notnull' => false,
				'default' => 0,
				'length' => 20,
				'comment' => 'Numero del aviso del registro'
			));
			$this->guiones_registro->addColumn('UBICACION', 'bigint', array(
				'notnull' => true, 
				'length' => 20,
				'comment' => 'ID de la UBICACION del registro [ID de la tabla GUIONES_REGISTRO_UBICACION]'
			));
			$this->guiones_registro->setPrimaryKey(array('ID'));
			$this->guiones_registro->addForeignKeyConstraint($this->usuarios, array('USUARIO'), array('ID'), $this->opcForeign);
			$this->guiones_registro->addForeignKeyConstraint($this->guiones_registro_tipo, array('TIPO'), array('ID'), $this->opcForeign);
			$this->guiones_registro->addForeignKeyConstraint($this->guiones_registro_afectacion, array('AFECTACION'), array('ID'), $this->opcForeign);
			$this->guiones_registro->addForeignKeyConstraint($this->guiones_registro_ubicacion, array('UBICACION'), array('ID'), $this->opcForeign);
		}
	}