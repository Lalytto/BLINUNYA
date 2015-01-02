	function numeros(l){
		var charCode = (l.which) ? l.which : event.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
		return true;
	}
	
	function letras(l){
		var charCode = (l.which) ? l.which : event.keyCode;
		var retun = true;
		if(charCode > 31 && (charCode < 97 || charCode > 122)  && (charCode < 65 || charCode > 90)) retun = false;
		if(charCode == 32) retun = true;
		return retun;
	}
	
/* ********************************************************************* 
   *********************************************************************
   ********************* F U N C I O N E S *****************************
   ********************************************************************* 
   ********************************************************************* */
	function funcionCargando(opcion){
		if(opcion) {
			$('.formDivBotones').hide();
			$('.jQueryLoading').show();
			$('.jQueryLoading').html('<img src="./Include/imagenes/gif/loader.gif">');
		} else {
			$('.formDivBotones').show();
			$('.jQueryLoading').hide();
		}
	}
   
	function funcionValidateError(error, element){
		element.tooltip({ disabled: false });
		element.tooltip({
            content: error,
            items: 'div',
            position: {
                my: 'center bottom-20',
					at: 'center top',
					using: function(position, feedback) {
                    $(this).css(position);
                    $('<div>').addClass('arrow').addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
					}
				}
		});
		element.on('mouseover',function(){
			element.tooltip("destroy");
		});
		setTimeout(function(){
			element.tooltip("destroy");
		}, 2000);
	}
	
	function funcionJsonError(jqXHR, exception){
		var mensaje = '';
		if (jqXHR.status === 0) mensaje = 'Not connect.\n Verify Network.';
		else if (jqXHR.status == 404) mensaje = 'Requested page not found. [404]';
		else if (jqXHR.status == 500) mensaje = 'Internal Server Error [500].';
		else if (exception === 'parsererror') mensaje = 'Requested JSON parse failed.';
		else if (exception === 'timeout') mensaje = 'Time out error.';
		else if (exception === 'abort') mensaje = 'Ajax request aborted.';
		else mensaje = 'Uncaught Error.\n' + jqXHR.responseText;
		funcionAbrirDialogAlerta('<b>jQueryError: </b>'+mensaje+'<br>Would you like to notify about this problem? <a href="/Contact">Click here!</a>');
	}
   
	function funcionAjax(query,strf,form){
		$.ajax({
			beforeSend:function(){
				funcionCargando(true);
			},
			type: 'POST',
			dataType: 'json',
			cache: false,
			url: query,
			data: strf,
			error: function(jqXHR, exception) {
				funcionJsonError(jqXHR, exception);
            },
			success: function(json) {
				funcionAbrirDialogAlerta(json.mensaje);
				if(json.estado){
					if(json.enviarGet) form.submit();
					//else location.reload();
					else {
						$(form)[0].reset();
						$('div .form-group').removeClass('has-success');
					}
				}
				funcionCargando(false);
			}
		});
	}
	
	function funcionAjaxArray(query){
		var retun = [];
		$.ajax({
			type: 'GET',
			dataType: 'json',
			async: false,
			url: query,
			error: function(jqXHR, exception) {
				funcionJsonError(jqXHR, exception);
			}
		}).done(function(json) {
			retun = json;
		});
		return retun;
	}
	
	function funcionAbrirDialogAlerta(mensaje){
		$('#dialogAlertaMensaje').html(mensaje);
		$('#myModalBoootstrap').modal('show');
	}
	
	function funcionFieldError(id,estado){
		id = id.attr('id');
		if(estado.estado){
			$('#'+id).closest('div .form-group').removeClass('has-error has-feedbac').addClass('has-success');
			$('.form-control-feedback').addClass('oculto');
		} else {
			$('#'+id).addClass('bordesError').closest('div .form-group').removeClass('has-success').addClass('has-error has-feedbac');
			$('#'+id).closest('div .input-group').append('<span class="glyphicon glyphicon-remove form-control-feedback" '+
														'data-toggle="tooltip" data-placement="right" title="'+estado.mensaje+'" '+
														'aria-hidden="true"></span>').tooltip('show');
		}
	}
	
	function funcionResetForm(strf,form){
		resultado = funcionAjaxArray(strf);
		if(resultado.estado) {
			$(form)[0].reset();
			$('div .form-group').removeClass('has-success');
		}
		funcionAbrirDialogAlerta(resultado.mensaje);
	}
	
	
	// V A R I A B L E S
	var td, dataTipo, campo, valor, viejoValor, id;
	
	function resetTD(){
		td.html('<span>'+valor+'</span>');
		$('td:not(.id, .Noeditable)').addClass('editable');
	}
	
	$(document).on('click','td.editable span',function(e){
		e.preventDefault();
		$('td:not(.id, .Noeditable)').removeClass('editable');
		td = $(this).closest('td');
		campo = $(this).closest('td').data('campo');
		dataTipo = $(this).closest('td').data('tipofield');
		valor = $(this).text();
		id = $(this).closest('tr').find('.id').text();
		if(dataTipo=='input') td.text('').html('<input type="text" class="campoValor" name="'+campo+'" value="'+valor+'"><a class="enlace guardar" href="#">Guardar</a><a class="enlace cancelar" href="#">Cancelar</a><a class="enlace eliminar" href="#">Eliminar</a>');
		else if(dataTipo=='txtarea') td.text('').html('<textarea class="campoValor" rows="3" name="'+campo+'" >'+valor+'</textarea><a class="enlace guardar" href="#">Guardar</a><a class="enlace cancelar" href="#">Cancelar</a><a class="enlace eliminar" href="#">Eliminar</a>');
		else if(dataTipo=='select') td.text('').html('<input class="campoValor" name="'+campo+'" value="'+valor+'" readonly><a class="enlace guardar" href="#">Guardar</a><a class="enlace cancelar" href="#">Cancelar</a><a class="enlace eliminar" href="#">Eliminar</a>');
		else if(dataTipo=='datepicker') {
			td.text('').html('<input class="campoValor datepicker" name="'+campo+'" placeholder="'+valor+'" readonly><a class="enlace guardar" href="#">Guardar</a><a class="enlace cancelar" href="#">Cancelar</a><a class="enlace eliminar" href="#">Eliminar</a>');
			$('.datepicker').datepicker({changeMonth: true, changeYear: true})
							.datepicker( "option", "dateFormat", "DD, d MM, yy" );
		}
		
	});
		
	$(document).on('click','.cancelar',function(e){
		e.preventDefault();
		resetTD();
	});
	
$(document).ready(function(){
/* ********************************************************************* 
   *********************************************************************
   ************************ I N I C I O ********************************
   ********************************************************************* 
   ********************************************************************* */
   
	// I N I C I O    D E    J Q U E R I 
	$('.datepicker').datepicker({changeMonth: true, changeYear: true})
					.datepicker( "option", "dateFormat", "DD, d MM, yy" );
   
/* ********************************************************************* 
   *********************************************************************
   ********************* F U N C I O N E S *****************************
   ********************************************************************* 
   ********************************************************************* */
	
	
/* ********************************************************************* 
   *********************************************************************
   *********************** B O T O N E S *******************************
   ********************************************************************* 
   ********************************************************************* */
	$('#formLoginSubmit').click(function(){
		$('#formLogin').validate({
			debug: true,
			rules: {
				formLoginUsuario: "required",
				formLoginPass: "required"
			},
			messages: {
				formLoginUsuario: "Ingrese su id de usuario",
				formLoginPass: "Ingrese su contraseña"
			},
			errorPlacement: function (error, element) {
				funcionValidateError(error.text(),element);
			},
			submitHandler: function (form) {
				funcionAjax('./Include/Clases/funciones.php?q=LogIn',$('#formLogin').serialize(),form);
			}
		});
	});
	
	
});