var debug0;
// console.log()
$(document).ready(function(){

	// append users to drop down
	$.get('/handler/listUsers.php', function (data){
		
		var parseArray = JSON.parse(data);

		if ( typeof parseArray == 'object') {

			$.each(parseArray, function (userIndex, userValue){
				
				var option = $('<option/>').attr('value', userValue.ID).html(userValue.username);

				$('.userList').append(option);
			});
		}
	});

	/*$.get('/handler/listUsers.php').done(function (data){
		
		var parseArray = JSON.parse(data);

		if ( typeof parseArray == 'object') {

			$.each(parseArray, function (userIndex, userValue){
				
				var option = $('<option/>').attr('value', userValue.ID).html(userValue.username);

				$('.userList').append(option);
			});
		}
	});*/
	
	// Create or Delete users table
	$('.usersTableInstaller').find('form').on('submit', function (e){		
		e.preventDefault();
		
		var formObject = $(this);
		
		$.post($(this).attr('action'), function (response){
			
			if (response.indexOf('success') > -1){

				$('.userList').find('option').each(function (userIndex, userValue){
					
					// Remove all option tag expect default option
					if( $(userValue).attr('disabled') === undefined ){

						$(this).remove()
					}
				});
			}

			formObject.parents('.usersTableInstaller').find('.feedback').html(response);
		});
	});

	// Adds user info to users table
	$('.registerUser').find('form').on('submit', function (e){
		e.preventDefault();
		
		var formObject = $(this);
		
		var newUserObj = {

			username : formObject.find('.username').val(),

			password : formObject.find('.password').val()
		}

		$.post($(this).attr('action'), newUserObj ).done(function (response){
				
			if ( isNaN(response) ) {// registration fail
				
				var parseResponse = $.parseJSON(response);
				
				formObject.parents('.registerUser').find('.feedback').html(objectToText(parseResponse));
			}else{ //registration successful 
				
				formObject.parents('.registerUser').find('.feedback').html('sucess');

				var option = $('<option/>').attr('value', response).html(newUserObj.username);

				$('.userList').append(option);
			};
		});
	});

	// Modify existing user info in users table
	$('.updateUser').find('form').on('submit', function (e){
		e.preventDefault();

		var formObject = $(this);
		
		if ( formObject.find('.userList').children('option:selected').val() == '') {

			formObject.parents('.updateUser').find('.feedback').html('Pick user');
			return false;
		}

		var updateUserObj = {
			userID 		: formObject.find('.userList').children('option:selected').val(),
			username	: ( formObject.find('.newUsername').val().length == 0 ) ? formObject.find('.userList').children('option:selected').text() : formObject.find('.newUsername').val(),
			password	: formObject.find('.newPassword').val()
		};

		$.post($(this).attr('action'), updateUserObj, function (response){

			var parseResponse = $.parseJSON(response);
				
			if ( response.indexOf('success') > -1 ) {
				
				var userID = formObject.find('.userList').children('option:selected').attr('value');

				$('.userList').find('option').each(function (){

					if ( $(this).attr('value') == userID && updateUserObj.username != '') {
						$(this).html(updateUserObj.username);
					}
				})
			}
			
			formObject.parents('.updateUser').find('.feedback').html(objectToText(parseResponse));
		})
	})

	// Deletes selected user from users table
	$('.deleteUser').find('form').on('submit', function (e){
		e.preventDefault();

		var formObject = $(this);
		
		if ( formObject.find('.userList').children('option:selected').val() == '') {

			formObject.parents('.deleteUser').find('.feedback').html('Pick user');
			return false;
		};

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

	// Adds user info to users table
	$('.loginuser').find('form').on('submit', function (e){
		e.preventDefault();
		
		var formObject = $(this);
		
		var userObj = {

			username : formObject.find('.username').val(),

			password : formObject.find('.password').val()
		}

		$.post($(this).attr('action'), userObj, function (response){
			
			formObject.parents('.loginUser').find('.feedback').html(response);
		});
	});
});

function objectToText (theObject) {

	return "<pre>" + JSON.stringify(theObject, null, 4) + "</pre>";
}