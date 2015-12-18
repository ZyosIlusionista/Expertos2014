<?php
	
	namespace Consola\SQL;
	use \Mvc\Consola;
	
	class Tablas extends Consola {
		
		private $conexion, $esquema, $plataforma;
		private $estados;
		private $usuarios_empresa, $usuarios_cargo, $usuarios;
		private $permisos, $permisos_modulo, $permisos_acceso, $permisos_seleccion;
		
		function __construct() {
			$conexion = new \Neural\BD\Conexion(APPBD, APP);
			$this->conexion = $conexion->doctrineDBAL();
			$this->esquema = new \Doctrine\DBAL\Schema\Schema();
			$this->plataforma = $this->conexion->getDatabasePlatform();
		}
		
		public function ejecutar() {
			$this->tbl_estados();
			$this->tbl_permisos();
			$this->tbl_permisos_modulos();
			$this->tbl_permisos_acceso();
			$this->tbl_permisos_seleccion();
			$this->tbl_usuarios_empresa();
			$this->tbl_usuarios_cargo();
			$this->tbl_usuarios();
			
			
			$queries = $this->esquema->toSql($this->plataforma);
			$dropSchema = $this->esquema->toDropSql($this->plataforma);
			
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
			
			$this->conexion->insert('PERMISOS_ACCESO', array('ID' => 1, 'NOMBRE' => 'ACCESO TOTAL', 'LECTURA' => 1, 'ESCRITURA' => 1, 'ACTUALIZAR' => 1, 'ELIMINAR' => 1));
			
			$this->conexion->insert('PERMISOS', array('ID' => 1, 'NOMBRE' => 'ADMINISTRADOR', 'ESTADO' => 1));
			$this->conexion->insert('PERMISOS_SELECCION', array('ID' => 1, 'PERMISO' => 1, 'MODULO' => 1, 'ACCESO' => 1));
			$this->conexion->insert('PERMISOS_SELECCION', array('ID' => 2, 'PERMISO' => 1, 'MODULO' => 2, 'ACCESO' => 1));
			
			$this->conexion->insert('USUARIOS_EMPRESA', array('ID' => 1, 'NOMBRE' => 'CLARO'));
			$this->conexion->insert('USUARIOS_CARGO', array('ID' => 1, 'NOMBRE' => 'ADMINISTRADOR DEL SISTEMA'));
			$this->conexion->insert('USUARIOS', array('ID' => 1, 'USUARIO' => 'ADMIN', 'PASSWORD' => sha1('123'), 'NOMBRE' => 'ADMINISTRADOR', 'APELLIDO' => 'DEL SISTEMA', 'USUARIO_RR' => 'ADMINRR', 'CORREO' => 'ADMIN@ADMIN.COM', 'ESTADO' => 1, 'EMPRESA' => 1, 'CARGO' => 1, 'PERMISO' => 1));
			
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
			$this->usuarios_empresa->setPrimaryKey(array('ID'));
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
			$this->permisos->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
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
			$this->permisos_modulo->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
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
			$this->usuarios->addForeignKeyConstraint($this->estados, array('ESTADO'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
			$this->usuarios->addForeignKeyConstraint($this->usuarios_empresa, array('EMPRESA'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
			$this->usuarios->addForeignKeyConstraint($this->usuarios_cargo, array('CARGO'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
			$this->usuarios->addForeignKeyConstraint($this->permisos, array('PERMISO'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
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
			$this->permisos_seleccion->addForeignKeyConstraint($this->permisos, array('PERMISO'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
			$this->permisos_seleccion->addForeignKeyConstraint($this->permisos_modulo, array('MODULO'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
			$this->permisos_seleccion->addForeignKeyConstraint($this->permisos_acceso, array('ACCESO'), array('ID'), array('onDelete' => 'no action', 'onUpdate' => 'no action'));
		}
	}