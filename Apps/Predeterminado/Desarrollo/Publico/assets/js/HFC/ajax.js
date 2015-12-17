	
	/**
	 *	peticionAjax(idForm, idRespuesta, urlDireccion)
	 *
	 *	Genera la peticion ajax y genera el proceso correspondiente
	 *	de respuesta html
	 *	@param idForm: id del formulario
	 *	@param idRespuesta: id donde se cargara la respuesta
	 *	@param urlDireccion: url de la peticion
	 */
	function peticionAjax(idForm, idRespuesta, urlDireccion) {
		$.ajax({
				type : "POST",
				dataType : "html",
				data : $("#" + idForm).serialize(),
				url : urlDireccion,
				cache : false,
				beforeSend : function() {
					$("#" + idRespuesta).html('... Espere un Momento Por Favor ... !!!!');
				},
				success : function(resultado) {
					$("#" + idRespuesta).html(resultado);
				}
			});
	}