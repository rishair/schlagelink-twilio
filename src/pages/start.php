<?

if (p('func') == 'authenticate') {
	$u = p('schlage_username');
	$p = p('schlage_password');
	if ($u && $p) {
		$schlage = new Schlage($u, $p);
		if (!$schlage->Login()) {
			error('Invalid login, try again');
		} else {
			schlage_username($u);
			schlage_password($p);
			success('Good to go!');
			s('logged_in', 1);
			r('/users');
		}
	}
}


top();

form();
func('authenticate');
label('Username');
textfield('schlage_username');
label('Password');
textfield('schlage_password');

label();
submit('Authenticate');


bottom();


?>