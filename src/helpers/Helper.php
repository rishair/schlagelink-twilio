<?

function restrict() {
	if (!s('logged_in')) {
		error('Unauthorized access!');
		r('/login');
	}
}

function incr_stat($key, $count = 1) {
	redis()->hincrby('stats', $key, $count);
	redis()->hincrby('stats', $key . '_' . strtolower(date('M')), $count);
	redis()->hincrby('stats', $key . '_' . strtolower(date('M')) . '_' . date('j'), $count);
}

function stats($key, $when = false) {
	if ($when == 'today') {
		$when = strtolower(date('M_j'));
	}
	if ($when == 'month') {
		$when = strtolower(date('M'));
	}
	if (!$when) $when = '';
	if ($when) $when = '_' . $when;
	return intval(redis()->hget('stats', $key . $when));
}

function log_action($user, $action) {
	if (!is_array($user) && strlen(number($user)) < 5)
		if ($u = user($user)) $user = $u;
	if (is_array($user)) $user = $user['phone'];
	redis()->lpush('actions', json_encode(array(
		'phone' => $user,
		'action' => $action,
		'time' => time(),
	)));
}

function format_user($user) {
	$c = o($user, 'company');
	return ($c ? _gray('[' . $c . '] ') : '') .  _strong(o($user, 'name'));
}



?>