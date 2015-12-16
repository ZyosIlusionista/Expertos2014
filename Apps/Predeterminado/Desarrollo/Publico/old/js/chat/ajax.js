	
	/**
	 *	funcion resetForm(idForm)
	 *
	 *	genera el reset del formulario correspondiente
	 *	@param idForm: id del formulario
	 *	@return void
	 */
	function resetForm(idForm) {
		$("#" + idForm)[0].reset();
	}
	
	/**
	 *	funcion construirMensajeChat(array)
	 *
	 *	genera las etiquetas html para mostrar el mensaje correspondiente
	 *	@param array: array de datos para construir el articulo
	 *	@return string
	 */
	function construirMensajeChat(array, usuario) {
		var constructor = [];
		if(array.USUARIO === usuario) {
			constructor.push('<li class="left clearfix">');
			constructor.push('<span class="chat-img pull-left">');
	//		constructor.push('<img src="http://placehold.it/50/55C1E7/fff" alt="User Avatar" class="img-circle" />');
            constructor.push('<img src="http://localhost:8080/Expertos/Web/img/chat_me.PNG" alt="User Avatar" />');
			constructor.push('</span>');
			constructor.push('<div class="chat-body clearfix">');
			constructor.push('<div class="header">');
			constructor.push('<strong class="primary-font">' + array.ID_USUARIO + '</strong>');
			constructor.push('<small class="pull-right text-muted">');
			constructor.push('<i class="fa fa-clock-o fa-fw"></i> ' + array.FECHA);
			constructor.push('</small>');
			constructor.push('</div>');
			constructor.push('<p>' + array.MENSAJE + '</p>');
			constructor.push('</div>');
			constructor.push('</li>');
		}
		else {
			constructor.push('<li class="right clearfix">');
			constructor.push('<span class="chat-img pull-right">');
	//		constructor.push('<img src="http://placehold.it/50/FA6F57/fff" alt="User Avatar" class="img-circle" />');
		    constructor.push('<img src="http://localhost:8080/Expertos/Web/img/chat_all.PNG" />');
            constructor.push('</span>');
			constructor.push('<div class="chat-body clearfix">');
			constructor.push('<div class="header">');
			constructor.push('<small class=" text-muted">');
			constructor.push('<i class="fa fa-clock-o fa-fw"></i> ' + array.FECHA);
			constructor.push('</small>');
			constructor.push('<strong class="pull-right primary-font">' + array.ID_USUARIO + '</strong>');
			constructor.push('</div>');
			constructor.push('<p>' + array.MENSAJE + '</p>');
			constructor.push('</div>');
			constructor.push('</li>');
		}
		return constructor.join("\n");
	}
	
	/**
	 *	funcion construirListaMensajes(array, idRespuesta)
	 *
	 *	genera el listado ordenado de los mensajes obtenidos
	 *	@param array: array de datos
	 *	@param idRespuesta: id donde se cargaran los datos
	 */
	function construirListaMensajes(array, idRespuesta, usuario) {
		var cantidad = array.length;
		var constructor = [];
		for (i = 0; i < cantidad; i++) {
			constructor[i] = construirMensajeChat(array[i], usuario);
		}
		$("#" + idRespuesta).html(constructor.join(''));
	}
	
	/**
	 *	funcion peticionAjaxFormGuardar(idForm, idRespuesta, urlDireccion)
	 *
	 *	genera la peticion ajax correspondiente para la construccion del
	 *	chat correspondiente
	 *	@param idForm: id del formulario
	 *	@param idRespuesta: id donde se cargaran los datos
	 *	@param urlDireccion: direccion donde se debe enviar la peticion ajax
	 */
	function peticionAjaxFormGuardar(idForm, idRespuesta, urlDireccion, usuario) {
		$.ajax({
			type : "POST",
			dataType : "json",
			data : $("#" + idForm).serialize(),
			url : urlDireccion,
			beforeSend : function() {
				$("#" + idRespuesta).html('Espere un Momento Por Favor... !!!!');
			},
			success : function(resultado) {
				resetForm(idForm);
				$("#" + idRespuesta).attr('data', resultado['0'].ID);
				construirListaMensajes(resultado, idRespuesta, usuario);
			}
		});
	}
	
	/**
	 *	funcion peticionAjaxListado(idRespuesta, urlDireccion)
	 *
	 *	genera la peticion ajax correspondiente para la construccion del
	 *	chat correspondiente
	 *	@param idRespuesta: id donde se cargaran los datos
	 *	@param urlDireccion: direccion donde se debe enviar la peticion ajax
	 */
	function peticionAjaxListado(idRespuesta, urlDireccion, usuario) {
		$.ajax({
			type : "POST",
			dataType : "json",
			url : urlDireccion,
			success : function(resultado) {
				var cantidad = resultado.length;
				var dataId = $("#" + idRespuesta).attr('data');
				if(cantidad >= 1) {
					if(dataId != resultado['0'].ID) {
						$("#" + idRespuesta).attr('data', resultado['0'].ID);
						construirListaMensajes(resultado, idRespuesta, usuario);
					}
				}
			}
		});
	}
	
	function peticionAjaxSala(idRespuesta, urlDireccion, usuario, sala) {
		$.ajax({
			type : "POST",
			dataType : "json",
			data : { usuario : usuario, sala : sala },
			url : urlDireccion,
			success : function(resultado) {
				var cantidad = resultado.length;
				var dataId = $("#" + idRespuesta).attr('data');
				construirListaMensajes(resultado, idRespuesta, usuario);
				/*if(cantidad >= 1) {
					if(dataId != resultado['0'].ID) {
						$("#" + idRespuesta).attr('data', resultado['0'].ID);
						construirListaMensajes(resultado, idRespuesta, usuario);
					}
				}*/
			}
		});
	}