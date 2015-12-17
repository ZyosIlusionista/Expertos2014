	var neonNotificador = new Object;
	
	jQuery.extend(neonNotificador, {
		
		init: function(URL_SERVER, user) {
			
			if('sessionStorage' in window && window['sessionStorage'] !== null) {
				
				neonNotificador.procesarUsuario(URL_SERVER, user);
				setInterval(function() {
					neonNotificador.procesarUsuario(URL_SERVER, user);
				}, 6000);
				
			}
			
		},
		
		procesarUsuario : function(URL_SERVER, user) {
			var usuario = neonNotificador.obtener('usuario');
			
			if(usuario == null) {
				sessionStorage.clear();
				neonNotificador.asignar('usuario', user);
				neonNotificador.procesarPeticion(URL_SERVER);
			}
			else {
				
				if(usuario == user) {
					neonNotificador.procesarPeticion(URL_SERVER);
				}
				else {
					sessionStorage.clear();
					neonNotificador.asignar('usuario', user);
					neonNotificador.procesarPeticion(URL_SERVER);
				}
				
			}
		},
		
		procesarPeticion: function(URL_SERVER) {
			var peticion = neonNotificador.peticionAjax(URL_SERVER);
			jQuery("#cantidadChat").removeClass('is-hidden');
			jQuery("#cantidadChat").text(peticion['Cantidad']);
			neonNotificador.circularSalas(peticion['Salas']);
		},
		
		circularSalas : function(matrizSalas) {
			
			for (i=0; i<matrizSalas.length; i++) {
				neonNotificador.notificacionSala(matrizSalas[i]['SALA'], matrizSalas[i]['CANTIDAD']);
			}
		},
		
		notificacionSala: function(sala, cantidad) {
			var valida = neonNotificador.obtener('Sala_' + sala);
			
			if(valida == null) {
				neonNotificador.asignar('Sala_' + sala, cantidad);
				jQuery("#Notificacion_Sala_" + sala).removeClass('is-hidden');
				jQuery("#Notificacion_Sala_" + sala).text(cantidad);
			}
			else {
				var cantidad_1 = Number(cantidad);
				var cantidad_2 = Number(valida);
				var total = (cantidad_1 - cantidad_2);
				
				neonNotificador.asignar('Sala_' + sala, cantidad);
				if(total >= 1) {
					jQuery("#Notificacion_Sala_" + sala).removeClass('is-hidden');
					jQuery("#Notificacion_Sala_" + sala).text(total);
				}
			}
		},
		
		obtener: function(llave) {
			return sessionStorage.getItem(llave);
		},
		
		asignar: function(llave, valor) {
			sessionStorage.setItem(llave, valor);
		},
		
		peticionAjax: function(URL_SERVER) {
			var respuesta = '';
			jQuery.ajax({
				type: 'POST',
				dataType : "json",
				cache : false,
				async: false,
				url : URL_SERVER + '/Chat/Historial',
				success : function(resultado) {
					respuesta = resultado;
				}
			});
			return respuesta;
		}
		
	});