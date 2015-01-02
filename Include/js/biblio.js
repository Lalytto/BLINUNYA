$(document).ready(function(){
/* ********************************************************************* 
   *********************************************************************
   ************************ I N I C I O ********************************
   ********************************************************************* 
   ********************************************************************* */
	// V A R I A B L E S
	var q = './Include/Clases/funciones.php?q=';
	var tipoNombre = false, categoriaNombre = false, codigoLibro = false;
	
	$('#myTab a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	});
	
	$('.tab-content button').click(function (e) {
		$('#form'+$(this).attr('id')).removeClass('oculto');
	});
   
/* ********************************************************************* 
   *********************************************************************
   ********************* F U N C I O N E S *****************************
   ********************************************************************* 
   ********************************************************************* */
	function funcionGetLibros(campo){
		if(campo=='listaLibros') {
			$('.'+campo).empty().append('<thead><tr><th style="display:none;">ID</th><th>Titulo</th><th>Autor</th><th>Publicado</th><th>Editorial</th><th>Tipo</th><th>Categoria</th><th>Estado</th></tr></thead>');
		} else if(campo=='formNuevoEjemplarAutor' || campo=='formNuevoEjemplarID') {
			$('#'+campo).empty().append('<option disabled selected>Seleccione el criterio</option>');
		} else if(campo=='catalogacion') {
			$('.showTipo').empty().append('<thead><tr><th style="display:none;">ID</th><th>Tipo</th><th>Descripcion</th></tr></thead>');
			$('.showCategoria').empty().append('<thead><tr><th style="display:none;">ID</th><th>Categoria</th><th>Descripcion</th></tr></thead>');
		} else if(campo=='formNuevoLibro') {
			$('#formNuevoLibroTipo').empty().append('<option disabled selected>Seleccione un tipo</option>');
			$('#formNuevoLibroCategoria').empty().append('<option disabled selected>Seleccione una categoria</option>');
		}
		
		strf = q+'getSome&opcion='+campo+'&valor=0';
		//if(campo=='formNuevoEjemplarID') strf = '&valor='+$('#formNuevoEjemplarAutor').val();
		if(campo=='formNuevoEjemplarID') strf = q+'getSome&opcion='+campo+'&valor='+$('#formNuevoEjemplarAutor').val();
		
		//resultado = funcionAjaxArray('./Include/Clases/funciones.php?q=getSome&opcion='+campo+strf);
		resultado = funcionAjaxArray(strf);
	  if(resultado.length>0 && typeof resultado.length!=='undefined'){
		for(var i=0; i<resultado.length; i++){
			if(campo=='formNuevoEjemplarAutor') {
				if(typeof resultado[i].autor!=='undefined') $('#'+campo).append('<option value="'+resultado[i].autor+'">'+resultado[i].autor+'</option>'); 
			} else if(campo=='formNuevoEjemplarID') {
				if(typeof resultado[i].id!=='undefined') $('#'+campo).append('<option value="'+resultado[i].id+'">'+resultado[i].titulo+'</option>');
			} else if(campo=='catalogacion') {
				if(typeof resultado[i].tipoid!=='undefined'){
					$('.showTipo').append('<tr><td class="id" style="display: none;">'+resultado[i].tipoid+'</td>'+
												'<td class="editable" data-campo="tipo_nombre" data-tipofield="input"><span>'+resultado[i].tiponombre+'</span></td>'+
												'<td class="editable" data-campo="tipo_comentario" data-tipofield="txtarea"><span>'+resultado[i].tipocomentario+'</span></td></tr>');
				}
				if(typeof resultado[i].categoriaid!=='undefined'){
					$('.showCategoria').append('<tr><td class="id" style="display: none;">'+resultado[i].categoriaid+'</td>'+
												'<td class="editable" data-campo="categoria_nombre" data-tipofield="input"><span>'+resultado[i].categorianombre+'</span></td>'+
												'<td class="editable" data-campo="categoria_comentario" data-tipofield="txtarea"><span>'+resultado[i].categoriacomentario+'</span></td></tr>');
				}
			} else if(campo=='formNuevoLibro') {
				if(typeof resultado[i].tipoid!=='undefined') $('#formNuevoLibroTipo').append('<option value="'+resultado[i].tipoid+'">'+resultado[i].tiponombre+'</option>');
				if(typeof resultado[i].categoriaid!=='undefined') $('#formNuevoLibroCategoria').append('<option value="'+resultado[i].categoriaid+'">'+resultado[i].categorianombre+'</option>');
			} else if(campo=='listaLibros') {
				if(typeof resultado[i].id!=='undefined'){
					$('.'+campo).append('<tr><td class="id" style="display: none;">'+resultado[i].id+'</td>'+
											'<td class="editable" data-campo="libro_titulo" data-tipofield="input"><span>'+resultado[i].titulo+'</span></td>'+
											'<td class="editable" data-campo="libro_autor" data-tipofield="input"><span>'+resultado[i].autor+'</span></td>'+
											'<td class="editable" data-campo="libro_fechapublicacion" data-tipofield="datepicker"><span>'+resultado[i].fecha+'</span></td>'+
											'<td class="editable" data-campo="libro_editorial" data-tipofield="input"><span>'+resultado[i].editorial+'</span></td>'+
											'<td class="editable" data-campo="tipoo_nombre" data-tipofield="select"><span>'+resultado[i].tipo+'</span></td>'+
											'<td class="editable" data-campo="categoria_nombre" data-tipofield="select"><span>'+resultado[i].categoria+'</span></td>'+
											'<td class="Noeditable"><span>'+resultado[i].estado+'</span></td></tr>');
				}
			}
		}
	  } else funcionAbrirDialogAlerta(resultado.mensaje);
	}
	
	$(document).on('click','.guardar',function(e){
		e.preventDefault();
		nuevovalor = $(this).closest('td').find('.campoValor').val();
		if(nuevovalor!=valor){
			if(nuevovalor.trim()!=''){
				strf = q+'setSome&id='+id+'&campo='+campo+'&valor='+nuevovalor;
				resultado = funcionAjaxArray(strf);
					if(resultado.estado) {
						//td.html('<span>'+nuevovalor+'</span>');
						if(campo=='libro_fechapublicacion') nuevovalor = funcionAjaxArray('./Include/Clases/funciones.php?q=fechaEspanol&fecha='+nuevovalor);
						td.html('<span>'+nuevovalor+'</span>');
					}
					funcionAbrirDialogAlerta(resultado.mensaje);
				$('td:not(.id, .Noeditable)').addClass('editable');
			} else funcionAbrirDialogAlerta('<p><b>Alerta:</b>Debe ingresar un valor!</p>');
		} else resetTD();
	});
	
	function inicioLibros(){
		if(typeof $('#opcionjQuery').val()!=='undefined') funcionGetLibros($('#opcionjQuery').val());
	}
	inicioLibros();
	
	$('#formNuevoTipoNombre, #formNuevaCategoriaNombre, #formNuevoEjemplarCodigo').blur(function(){
		mostrar = $(this).attr('id');
		if(mostrar == 'formNuevoTipoNombre'){
			strf = q+'findSome&campo=tipo&valor='+$(this).val();
			resultado = funcionAjaxArray(strf);
			tipoNombre = resultado.estado;
		} else if (mostrar == 'formNuevaCategoriaNombre') {
			strf = q+'findSome&campo=categoria&valor='+$(this).val();
			resultado = funcionAjaxArray(strf);
			categoriaNombre = resultado.estado;
		} else if (mostrar == 'formNuevoEjemplarCodigo') {
			strf = q+'findSome&campo=codigo&valor='+$(this).val();
			resultado = funcionAjaxArray(strf);
			codigoLibro = resultado.estado;
		}
		
		funcionFieldError($(this),resultado);
	});
	
	
/* ********************************************************************* 
   *********************************************************************
   *********************** B O T O N E S *******************************
   ********************************************************************* 
   ********************************************************************* */
	$('#formNuevoTipoSubmit').click(function(){
		$('#formNuevoTipo').validate({
			debug: true,
			rules: {
				formNuevoTipoNombre: "required",
				formNuevoTipoComentario: "required"
			},
			messages: {
				formNuevoTipoNombre: "Ingrese un nombre",
				formNuevoTipoComentario: "Ingrese un pequeño comentario"
			},
			errorPlacement: function (error, element) {
				funcionValidateError(error.text(),element);
			},
			submitHandler: function (form) {
				if(tipoNombre){
					strf = q+'addSome&opcion=tipo&nombre='+$('#formNuevoTipoNombre').val()+'&comentario='+$('#formNuevoTipoComentario').val();
					funcionResetForm(strf,form);
					inicioLibros();
				} else funcionAbrirDialogAlerta('<b>Nota </b>Existen algunos errores, para conocer de ello ponga el mouse sobre la <b>x</b> al lado del campo en rojo');
			}
		});
	});
	
	$('#formNuevaCategoriaSubmit').click(function(){
		$('#formNuevaCategoria').validate({
			debug: true,
			rules: {
				formNuevaCategoriaNombre: "required",
				formNuevaCategoriaComentario: "required"
			},
			messages: {
				formNuevaCategoriaNombre: "Ingrese un nombre",
				formNuevaCategoriaComentario: "Ingrese un pequeño comentario"
			},
			errorPlacement: function (error, element) {
				funcionValidateError(error.text(),element);
			},
			submitHandler: function (form) {
				if(categoriaNombre){
					strf = q+'addSome&opcion=categoria&nombre='+$('#formNuevaCategoriaNombre').val()+'&comentario='+$('#formNuevaCategoriaComentario').val();
					funcionResetForm(strf,form);
					inicioLibros();
				} else funcionAbrirDialogAlerta('<b>Nota </b>Existen algunos errores, para conocer de ello ponga el mouse sobre la <b>x</b> al lado del campo en rojo');
			}
		});
	});
	
	
	$('#formNuevoLibroSubmit').click(function(){
		$('#formNuevoLibro').validate({
			debug: true,
			rules: {
				formNuevoLibroTipo: "required",
				formNuevoLibroCategoria: "required",
				formNuevoLibroTitulo: "required",
				formNuevoLibroAutor: "required",
				formNuevoLibroEditorial: "required",
				formNuevoLibroFechaPublic: "required"
			},
			messages: {
				formNuevoLibroTipo: "Seleccione tipo del libro",
				formNuevoLibroCategoria: "Seleccione categoría del libro",
				formNuevoLibroTitulo: "Ingrese el título del libro",
				formNuevoLibroAutor: "Ingrese el autor del libro",
				formNuevoLibroEditorial: "Casa editorial del libro",
				formNuevoLibroFechaPublic: "Ingrese la fecha de publicación del libro"
			},
			errorPlacement: function (error, element) {
				funcionValidateError(error.text(),element);
			},
			submitHandler: function (form) {
				funcionAjax('./Include/Clases/funciones.php?q=nuevoLibro',$('#formNuevoLibro').serialize(),form);
			}
		});
	});
	
	$('#formNuevoEjemplarAutor').change(function(){
		funcionGetLibros('formNuevoEjemplarID');
	});
	
	$('#formNuevoEjemplarSubmit').click(function(){
		$('#formNuevoEjemplar').validate({
			debug: true,
			rules: {
				formNuevoEjemplarAutor: "required",
				formNuevoEjemplarID: "required",
				formNuevoEjemplarCodigo: "required"
			},
			messages: {
				formNuevoEjemplarAutor: "Debe seleccionar el autor",
				formNuevoEjemplarID: "Debe seleccionar el libro",
				formNuevoEjemplarCodigo: "Ingrese el código único del libro"
			},
			errorPlacement: function (error, element) {
				funcionValidateError(error.text(),element);
			},
			submitHandler: function (form) {
				if(codigoLibro){
					funcionAjax('./Include/Clases/funciones.php?q=nuevoEjemplar',$('#formNuevoEjemplar').serialize(),form);
				} else funcionAbrirDialogAlerta('<b>Nota </b>Existen algunos errores, para conocer de ello ponga el mouse sobre la <b>x</b> al lado del campo en rojo');
			}
		});
	});
	
});