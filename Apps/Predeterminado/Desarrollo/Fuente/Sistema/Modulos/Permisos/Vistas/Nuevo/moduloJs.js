	var peticion = new Object();
	jQuery.extend(peticion, {
		init: function(formulario) {
			peticion.peticionAjax(formulario);
		},
		peticionAjax: function(formulario) {
			jQuery.ajax({
				url : "{{ RUTA_APP|e }}/Permisos/Nuevo/moduloProcesar",
				async : false,
				data : jQuery(formulario).serialize(),
				dataType : "json",
				type : "POST",
				cache : false,
				error : function(error_mensaje) {
					swal("Error!", "Se ha presentado un error, validar con el administrador del sistema", "error");
				},
				success : function(resultado) {
					peticion.success(resultado);
				}
			});
		},
		success : function(resultado) {
			if(resultado.status == true || resultado.codigo >= 1) {
				swal({
					title: "Nuevo Modulo Creado", 
					text: "Se ha creado con exito el Modulo con el ID: " + resultado.codigo, 
					type: "success", 
					showCancelButton: false,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Aceptar",
					cancelButtonText: "No, cancel plx!",
					closeOnConfirm: false,
					closeOnCancel: false
					}, function() {
						location.href="{{ RUTA_APP|e }}/Permisos/Index/modulos";
					});
			}
			else {
				swal({title: "Error! Codigo: " + resultado.codigo,   text: resultado.mensaje,   type: "error",   confirmButtonText: "Aceptar" });
			}
		}
	});