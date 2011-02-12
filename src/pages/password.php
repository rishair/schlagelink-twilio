<?

restrict();

if (p('func') == 'change_password' && p('password')) {
	if (p('password') != p('confirm_password')) {
		error('Passwords don\'t match!');
	} else {
		redis()->set('schlage:password', p('password'));
		success('Password changed!');
		r('/password');
	}
} else {
	$schlage = Schlage::GetInstance();
	if ($schlage->Login()) {
		success('Your current password properly authenticates');
	} else {
		error('Your password cannot authenticate, please update it!');
	}
}


top();

h2('Change your password');

form();
label('New Password');
password('password');
label('Confirm Password');
password('confirm_password');
label();
submit('Change Password');
func('change_password');
endform();

bottom();


?>