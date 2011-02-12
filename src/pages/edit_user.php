<?

restrict();

$phone = url(1);
if ($phone == 'create') $phone = false;

if (url(2) == 'delete') {
	redis()->hdel('users', $phone);
	success('User successfully deleted');
	r('/users');
}

if (p('func') == 'create_user' || p('func') == 'edit_user') {
	
	if (!number(p('phone')) || !p('name')) {
		error('Invalid input');
	} else {
		
		redis()->hdel('users', $phone);
		$u = user($phone);
		redis()->hdel('gmails', $u['gmail']);
	
		$no = number(p('phone'));
		$user = array(
			'phone' => $no,
			'name' => p('name'),
			'company' => p('company'),
			'gmail' => p('gmail'),
		);
		redis()->hset('users', $no, json_encode($user));
		redis()->hset('gmails', strtolower(p('gmail')), $no);
		success((p('func') == 'create_user' ? 'Added' : 'Modified') . ' ' . p('name') . ' successfully!');
		r('/users');
	}
}


$user = $phone ? json_decode(redis()->hget('users', $phone), true) : null;
if ($phone && !$user) {
	error('Couldn\'t find a user with that number.');
	r('/users');
}




top();

form();
label('Name');
textfield('name', o($user, 'name'));
label('Phone #');
textfield('phone', o($user, 'phone'));
label('Company');
textfield('company', o($user, 'company'));
label('Gmail');
textfield('gmail', o($user, 'gmail'));
func($phone ? 'edit_user' : 'create_user');
label();
submit($phone ? 'Save Changes' : 'Create User');


bottom();


?>