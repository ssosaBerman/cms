var debug0;

$(document).ready(function(){

	// append users to dropdown
	$.get('/handler/listUsers.php', function (data){
		
		var parseArray = JSON.parse(data);
		
		$.each(parseArray, function (userIndex, userValue){
			
			var option = $('<option/>').attr('value', userValue.ID).text(userValue.username);

			$('.userList').append(option);
		});
	});
	
	$('.usersTableInstaller').find('form').on('submit', function (e){		
		
		e.preventDefault();
		
		var formObject = $(this);
		
		$.post($(this).attr('action'), function (response){
			
			if (response.indexOf('success') > -1){

				$('.userList').find('option').each(function (userIndex, userValue){
					
					if( $(userValue).attr('disabled') === undefined ){

						$(this).remove()
					}
				});
			}

			formObject.parents('.usersTableInstaller').find('.feedback').html(response);
		});
	});

	$('.registerUser').find('form').on('submit', function (e){
		
		e.preventDefault();
		
		var formObject = $(this);
		
		var newUserObj = {

			username : formObject.find('.username').val(),

			password : formObject.find('.password').val()
		}

		$.post($(this).attr('action'), newUserObj, function (response){
			
			if ( isNaN(response) ) {
				
				formObject.parents('.registerUser').find('.feedback').html(response);
			}else{

				formObject.parents('.registerUser').find('.feedback').html('sucess');

				var option = $('<option/>').attr('value', response).html(newUserObj.username);

				$('.userList').append(option);
			};
		});
	});

	$('.updateUser').find('form').on('submit', function (e){
		e.preventDefault();

		var formObject = $(this);
		
		updateUserObj = {

			userID 		: formObject.find('.userList').children('option:selected').val(),
			newUsername : formObject.find('.newUsername').val(),
			newPassword : formObject.find('.newPassword').val()
		};

		$.post($(this).attr('action'), updateUserObj, function (response){

			if ( response.indexOf('success') > -1 ) {
				
				var userID = formObject.find('.userList').children('option:selected').attr('value');

				$('.userList').find('option').each(function (){

					if ( $(this).attr('value') == userID ) {

						$(this).html(updateUserObj.newUsername);
					}
				})
			}
			formObject.parents('.updateUser').find('.feedback').html(response);
		})
	})

	$('.deleteUser').find('form').on('submit', function (e){
		e.preventDefault();

		var formObject = $(this);
		
		deleteUserObj = {
			
			deleteID : formObject.find('.userList').children('option:selected').val(),
		}

		$.post($(this).attr('action'), deleteUserObj, function (response){
			
			if ( response.indexOf('success') > -1 ) {
				
				var userID = formObject.find('.userList').children('option:selected').attr('value')
				
				$('.userList').find('option').each(function (){

					if ( $(this).attr('value') == userID ) {

						$(this).remove();
					}
				})
			}

			formObject.parents('.deleteUser').find('.feedback').html(response);
		})
	})
});