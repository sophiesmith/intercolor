$(function() {
	$("#signup").submit(function(e) {
		if ( $.trim( $('#user_su').val() ) == '' ) {
		    alert('Username cannot be left blank!');
		    e.preventDefault(e);
		}
		else if ( $.trim( $('#pw_su').val() ) == '' ) {
		    alert('Password cannot be left blank!');
		    e.preventDefault(e);
		}
		else if ( $.trim( $('#confirm_su').val() ) == '' ) {
		    alert('Please confirm your password!');
		    e.preventDefault(e);
		}
		else if ($('#pw_su').val() != $('#confirm_su').val()) {
			alert('Passwords do not match!');
			e.preventDefault(e);
		}
	});
    
});

$(function() {
	$("#login").submit(function(e) {
		if ( $.trim( $('#user_li').val() ) == '' ) {
		    alert('Username cannot be left blank!');
		    e.preventDefault(e);
		}
		else if ( $.trim( $('#pw_li').val() ) == '' ) {
		    alert('Password cannot be left blank!');
		    e.preventDefault(e);
		}

	});
    
});