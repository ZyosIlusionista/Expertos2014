	var login = new Object();
	jQuery.extend(login, {
		init: function(formulario) {
			login.peticion(formulario);
		},
		peticion: function(formulario) {
			jQuery.ajax({
				url : "{{ RUTA_APP|e }}/Index/Index/autenticacion",
				async : false,
				data : jQuery(formulario).serialize(),
				dataType : "json",
				type : "POST",
				cache : false,
				error : function(error_mensaje) {
					alert(error_mensaje);
					console.log(error_mensaje.responseText);
				},
				success : function(resultado) {
					alert(resultado);
				}
			});
		}
	});