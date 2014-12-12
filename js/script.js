console.log();
var debug0;

$(document).ready(function(){
	
	//check if for active sessions
	if ( $.cookie('sessionID') !== undefined ) {
		
		$.get('/handler/resumeSession.php', function (user){

			var parseUserData = $.parseJSON(user);

			displayUser(parseUserData['username'])
		})
	};

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

		$.post($(this).attr('action'), newUserObj, function (response){
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
			username	: formObject.find('.userList').children('option:selected').text(),
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
			
			if ( response.indexOf('Not found') > -1 ) {
			
				formObject.parents('.loginUser').find('.feedback').html(response);	
			} else {
				
				if ( formObject.find('.rememberMe:checked').size() == 1 ) {

					$.cookie('sessionID', $.cookie('activeSession'), {expires: 365});
				} else {

					$.cookie('sessionID', $.cookie('activeSession'));
				};

				formObject.parents('.loginUser').find('.feedback').html('ok');	
				displayUser(formObject.find('.username').val())
			}
		});
	});

	$('.currentUser').find('form').on('submit', function (e){
		e.preventDefault();

		var formObject = $(this);

		$.get('handler/logout.php', function(response){
			
			if ( response == 'ok' ) {

				formObject.html('Not logged-in');

				$.removeCookie('sessionID');
			};
		})

	});
});

function objectToText (theObject) {

	return "<pre>" + JSON.stringify(theObject, null, 4) + "</pre>";
}

function displayUser (username) {

	var logoutButton = $('<input/>').attr({
		'type': 'submit',
		'value': 'Logout'
	});

	$('.currentUser').find('form')
	.html(username)
	.append(logoutButton);
}