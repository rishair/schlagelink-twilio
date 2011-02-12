<?

if (s('logged_in')) r('/users');

if (p('func') == 'login') {
	if (strtolower(p('username')) == strtolower(schlage_username()) && p('password') == schlage_password()) {
		success('Welcome back!');
		s('logged_in', 1);
		r('/users');
	} else {
		error('Invalid login');
	}
}

top();


form();
func('login');

label('Username');
textfield('username');

label('Password');
password('password');

label();
submit('Log in');
spacer();

endform();


bottom();

?>