<?
restrict();

top();

$count = 50;

$page = url(1);
$offset = $page * $count;
$actions = redis()->lrange('actions', $offset, $offset + $count);

h2('Showing Actions ' . $offset . '-' . ($offset + $count));


table();
foreach ($actions as $action) {
	$action = json_decode($action, true);
	$user = user($action['phone']);
	row(
		$user ? format_user($user) : $action['phone'],
		$action['action'],
		date(DATE_RFC822, $action['time'])
	);
}
endtable();

if (count($actions) == $count) {
	href('/actions/' . (intval($page) + 1), 'more');
}

bottom();

?>