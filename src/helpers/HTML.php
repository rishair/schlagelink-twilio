<?

function html($t) {
	return htmlspecialchars($t);
}
function form($f = false) {
	echo '<form method="post"';
	if ($f) echo ' action="' . $f . '"';
	echo ">\n";
}

function endform() {
	echo "</form>\n";
}

function _blue($text) {
	return _color($text, '5c77ff');
}

function _gray($text) {
	return _color($text, '999');
}
function _color($text, $color) {
	return '<span style="color:#' . $color . '">' . $text . '</span>';
}

function label($t = '') {
	echo "<label>$t</label>\n";
}

function textfield($name, $value = '') {
	echo '<input class="inline" id="' . $name . '" type="textfield" value="' . $value . '" name="' . $name . '" />';
}

function password($name) {
	echo '<input class="inline" type="password" name="' . $name . '" />';
}

function hidden($name, $value) {
	echo '<input type="hidden" value="' . $value . '" name="' . $name . '" />';
}

function func($value) {
	hidden('func', $value);
}

function submit($name = 'Submit') {
	echo '<input class="submit" type="submit" value="' . $name . '" />';
}

function spacer() {
	echo '<div class="clear"></div> <br />';
}

function url($i) {
	$url = explode('/', substr($_SERVER['REQUEST_URI'], 1));
	return isset($url[$i]) ? $url[$i] : null;
}
function h2($text){
	echo _h2($text);
}
function _h2($text) {
	return "<h2>$text</h2>";
}
function _href($url, $text) {
	return '<a href="' . html($url) . '">' . $text . '</a>';
}
function href($url, $text) {
	echo _href($url, $text);
}
function _strong($text) {
	return '<strong>' . $text . '</strong>';
}
function strong($text) {
	echo _strong($text);
}

function table() {
	echo '<table class="data">';
}

function cell($t) {
	echo '<td>' . $t .'</td>';
}

function row() {
	echo '<tr>';
	foreach (func_get_args() as $arg) {
		cell($arg);
	}
	echo '</tr>';
}

function endtable() {
	echo '</table>';
}

?>