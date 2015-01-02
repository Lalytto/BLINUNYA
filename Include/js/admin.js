$(document).ready(function(){
/* ********************************************************************* 
   *********************************************************************
   ************************ I N I C I O ********************************
   ********************************************************************* 
   ********************************************************************* */
	// V A R I A B L E S
	var q = '../Include/Clases/funciones.php?q=';
	var td, campo, valor, viejoValor, id;
	var cedulaUsuario = false, emailUsuario = false, loginUsuario = false;
   
/* ********************************************************************* 
   *********************************************************************
   ********************* F U N C I O N E S *****************************
   ********************************************************************* 
   ********************************************************************* */
	$('#formNuevoUsuarioCI, #formNuevoUsuarioEmail, #formNuevoUsuarioUsuarioLogin').blur(function(){
		mostrar = $(this).attr('id');
		if(mostrar == 'formNuevoUsuarioCI'){
			if($(this).val().length>9){
				strf = q+'validaCI&ci='+$(this).val();
				resultado = funcionAjaxArray(strf);
				cedulaUsuario = resultado.estado;
			}
		} else if (mostrar == 'formNuevoUsuarioEmail') {
			strf = q+'consultaCampo&campo=email&valor='+$(this).val();
			resultado = funcionAjaxArray(strf);
			emailUsuario = resultado.estado;
		} else if (mostrar == 'formNuevoUsuarioUsuarioLogin') {
			strf = q+'consultaCampo&campo=usuario&valor='+$(this).val();
			resultado = funcionAjaxArray(strf);
			loginUsuario = resultado.estado;
		}
		funcionFieldError($(this),resultado);
	});
	
	function inicioUsuarios(){
		funcionGetUsuarios($('#opcionjQuery').val());
	}
	
	inicioUsuarios();
	
/* ********************************************************************* 
   *********************************************************************
   *********************** B O T O N E S *******************************
   ********************************************************************* 
   ********************************************************************* */
	
	// A D M I N I S T R A D O R
	function funcionGetUsuarios(tabla){
		$('.'+tabla).empty().append('<thead><tr><th style="display:none;">ID</th><th>Usuario</th><th>Perfil</th><th>Estado</th></tr></thead>');
		strf = q+'getSome&opcion=usuarios&valor=0';
		resultado = funcionAjaxArray(strf);
	  if(resultado.length>0 && typeof resultado.length!=='undefined'){
		for(var i=0; i<resultado.length; i++){
		  if(typeof resultado[i].id!=='undefined'){
			$('.'+tabla).append('<tr><td class="id" style="display: none;">'+resultado[i].id+'</td>'+
									'<td class="Noeditable"><span>'+resultado[i].login+'</span></td>'+
									'<td class="editable" data-campo="usu_perfil"><span>'+resultado[i].perfil+'</span></td>'+
									'<td class="editable" data-campo="usu_estado"><span>'+resultado[i].estado+'</span></td></tr>');
		  } else funcionAbrirDialogAlerta('nada para mostrar');
		}
	  } else funcionAbrirDialogAlerta(resultado.mensaje);
	}
	
	/*
	$(document).on('click','.eliminar',function(e){
		e.preventDefault();
		strf = '&campo='+campo+'&id='+id;
		funcionAbrirDialogConfirma('<b>Note: </b> Are you sure you wanna delete this item?');
	});
	
	$(document).on('click','.guardar',function(e){
		e.preventDefault();
		nuevovalor=$(this).closest('td').find('input').val();
		if(nuevovalor!=valor){
			if(nuevovalor.trim()!=''){
				strf = '&campo='+campo+'&valor='+nuevovalor+'&id='+id;
				resultado = funcionAjaxArray('../Include/Clases/funciones.php?q=setUsuario'+strf);
					if(resultado.estado){
						td.html('<span>'+nuevovalor+'</span>');
						//funcionGetUsuarios(); 
					} else resetTD();
					funcionAbrirDialogAlerta(resultado.mensaje);
				$('td:not(.id, .Noeditable)').addClass('editable');
			} else funcionAbrirDialogAlerta('<p><b>Alerta:</b>Debe ingresar un valor!</p>');
		} else resetTD();
	});
	*/
	
	$('.wrapper').on('click','#buscarCI, #buscarNom, #buscarUser',function(e){
		e.preventDefault();
		mostrar = $(this).attr('id');
		if(mostrar == 'buscarCI'){
			opcion = 'perso_cedula';
		} else if(mostrar == 'buscarNom') {
			opcion = 'perso_nombre';
		} else if(mostrar == 'buscarUser') {
			opcion = 'usu_login';
		}
			resultado = funcionAjaxArray('../Include/Clases/funciones.php?q=listadeUsuarios&campo='+opcion+'&valor='+$('#formbuscarUsuario').val());
			$('#presentaresultado').show();
			$('#resultados').html(resultado.mensaje);
	});
	
	
	$('#formNuevaPersonaSubmit').click(function(){
		$('#formNuevaPersona').validate({
			debug: true,
			rules: {
				formNuevoUsuarioNombre: "required",
				formNuevoUsuarioCI: {	required: true,
										minlength: 10
				},
				formNuevoUsuarioCumpleanos: "required",
				formNuevoUsuarioEmail:{ required: true,
										email: true
				}
			},
			messages: {
				formNuevoUsuarioNombre: "Ingrese su nombre",
				formNuevoUsuarioCI: {	required: "Ingrese su numero de cédula",
										minlength: "Su cédula debe tener 10 dígitos"
				},
				formNuevoUsuarioCumpleanos: "Seleccione su fecha de nacimiento",
				formNuevoUsuarioEmail: { required: "Este campo es obligatorio",
										 email: "Ingrese un e-mail válido"
				}
			},
			errorPlacement: function (error, element) {
				funcionValidateError(error.text(),element);
			},
			submitHandler: function (form) {
				if(emailUsuario && cedulaUsuario){
					$('#formNuevaPersona').addClass('oculto');
					$('#formNuevoUsuario').removeClass('oculto');
				} else funcionAbrirDialogAlerta('<b>Nota </b>Existen algunos errores, para conocer de ello ponga el mouse sobre la <b>x</b> al lado del campo en rojo');
			}
		});
	});
	
	$('#formNuevoUsuarioBack').click(function(e){
		e.preventDefault();
		$('#formNuevaPersona').removeClass('oculto');
		$('#formNuevoUsuario').addClass('oculto');
	});
	
	$('#formNuevoUsuarioSubmit').click(function(){
		$('#formNuevoUsuario').validate({
			debug: true,
			rules: {
				formNuevoUsuarioUsuarioLogin: "required",
				formNuevoUsuarioPerfil: "required",
				formNuevoUsuarioUsuarioPass: "required",
				formNuevoUsuarioUsuarioPass_: {	required: true,
												equalTo: "#formNuevoUsuarioUsuarioPass"
				}
			},
			messages: {
				formNuevoUsuarioUsuarioLogin: "Ingrese su el ide de usuario",
				formNuevoUsuarioPerfil: "Seleccione el perfil de usuario",
				formNuevoUsuarioUsuarioPass: "Ingrese la contraseña",
				formNuevoUsuarioUsuarioPass_: {	required: 'Confirme su contraseña',
												equalTo: 'Sus contraseñas no coinciden'
				}
			},
			errorPlacement: function (error, element) {
				funcionValidateError(error.text(),element);
			},
			submitHandler: function (form) {
				if(loginUsuario){
					funcionAjax('../Include/Clases/funciones.php?q=nuevoUsuario',$('#formNuevaPersona,#formNuevoUsuario').serialize(),form);
				} else funcionAbrirDialogAlerta('<b>Nota </b>Existen algunos errores, para conocer de ello ponga el mouse sobre la <b>x</b> al lado del campo en rojo');
			}
		});
	});
	
	
});