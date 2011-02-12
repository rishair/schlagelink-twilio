<?

restrict();
top();
?>


<h2>Users</h2>
<?
$users = redis()->hgetall('users');

function sort_users($a, $b) { return $a['name'] > $b['name']; }

usort(array_values($users), 'sort_users');

table();
foreach ($users as $user) {
	$user = json_decode($user, true);
	row(
		format_user($user),
		o($user, 'phone'),
		_href('/users/' . o($user, 'phone') . '/edit', '(edit)'),
		_href('javascript:if(confirm("Are you sure you want to delete ' . o($user, 'name') . '?")) window.location="/users/' . o($user, 'phone') . '/delete"', '(delete)')
	);
}
endtable();

href('/users/create', 'Create New User');

bottom();

?>