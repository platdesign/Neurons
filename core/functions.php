<?PHP

function is_email($input) {
	return filter_var($input, FILTER_VALIDATE_EMAIL);
}

function is_url($input) {
	return filter_var ($input, FILTER_VALIDATE_URL);
}

function is_ip($input) {
	return filter_var($input, FILTER_VALIDATE_IP);
}

?>