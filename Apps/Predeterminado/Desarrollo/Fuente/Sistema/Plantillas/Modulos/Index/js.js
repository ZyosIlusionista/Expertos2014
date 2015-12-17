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
					swal("Error!", "Se ha presentado un error, validar con el administrador del sistema", "error");
				},
				success : function(resultado) {
					login.success(resultado);
				}
			});
		},
		success : function(info) {
			if(info.status == true) {
				location.href = info.data;
			}
			else {
				swal("Error!", info.data, "error");
			}
		}
	});