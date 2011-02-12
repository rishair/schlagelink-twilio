<?


include 'include.php';

session_start();

$__routes = array(
	'(login)?' => 'login',
	'start' => 'start',
	'users' => 'users',
	'users/create' => 'edit_user',
	'users/[0-9]+/(edit|delete)' => 'edit_user',
	'actions(/[0-9]+)?' => 'actions',
	'logout' => 'logout',
	'password' => 'password',
	'sms' => 'sms',
	'call' => 'call',
);

$uri = substr($_SERVER['REQUEST_URI'], 1);
$page = false;
foreach ($__routes as $key => $value) {
	if (preg_match('~^' . $key . '$~i', $uri)) {
		$page = $value;
		break;
	}
}
function error($e = NULL) {
	if ($e !== NULL) s('error', $e);
	return s('error');
}
function success($s = NULL) {
	if ($s !== NULL) s('success', $s);
	return s('success');
}
function top() {

?>

<html>
	<head>
		<title><?=TITLE?></title>
		<link rel="stylesheet" type="text/css" media="all" href="/main.css" /> 
	</head>
	<body>
		<body id="main"> 
			<div id="mama"> 
				<h1><?=TITLE?></h1>
				<hr />
				<?= s('logged_in') ? 
					_href('/users', 'Users') . ' | ' . 
					_href('/actions', 'Actions') . ' | ' . 
					_href('/logout', 'Logout') . '<hr/>' : ''?>
				<?
				if (error()) echo '<div class="error">' . error() . '</div>';
				if (success()) echo '<div class="success">' . success() . '</div>';
				s('error', false);
				s('success', false);
}

function bottom() {
	spacer();
?>
				<hr />
				<?=
					_blue(stats('authorized')) . ' unlocks, ' .
					_blue(stats('authorized', 'today')) . ' of which were today.'
				?>
				<hr />
				Created by <a href="mailto:windowasher@gmail.com">Rishi Ishairzay</a>
				<hr />
			</div>
		</div>
		
	</body>
</html>
<?
}


if ($page && file_exists('src/pages/' . $page . '.php')) {
	include 'src/pages/' . $page . '.php';
} else {
	top();
	echo '<marquee><strong>Unable to find page!</strong></marquee>';
	bottom();
}

?>