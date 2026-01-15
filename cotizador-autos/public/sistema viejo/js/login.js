$(document).ready(inicio);

function inicio()
{
	$('#user').focusout(verficar_user);
	$('#pass').focusout(verficar_pass);

}



$(document).on('click','.login',function(e){

$('#error_pass').slideUp('fast');

	var parametros = new FormData(document.getElementById('formulario'));
	
	$.ajax({
		data:parametros,
		type:'post',
		url:'metodos/login.php',
		context: this,
		contentType:false,
		processData:false,
		cache:false,
		success:function(data)
		{
			
			if(data.trim()==0)
			{
				$('#error_pass').html('¡Los datos no coinciden. Verificar la información!');
				$('#error_pass').slideDown('fast');
			}
			else
			{
				
				window.location ='inicio.php';
			}


		}

	});
});

function verficar_user()
{
	var user = $('#user').val();
	if(user=='')
	{
		$('#error_user').html('¡Debes ingresar un usuario!');
		$('#error_user').slideDown('fast');
		return false;
	}
	else
	{
		$('#error_user').slideUp('fast');
		return true;
	}
}

function verficar_pass()
{
var pass = $('#pass').val();
	if(pass=='')
	{
		$('#error_pass').html('¡Debes ingresar un password!');
		$('#error_pass').slideDown('fast');
		return false;
	}
	else
	{
		$('#error_pass').slideUp('fast');
		return true;
	}
}