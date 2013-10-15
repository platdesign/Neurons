<?PHP


function clog($input) {
	
	if( is_string($input) ) {
		$input = "'".$input."'";
	} else if( is_object($input) ) {
		$input = json_encode($input);
	} else if( is_array($input) ) {
		$input = json_encode($input);
	}
	
	
	
	echo '<script type="text/javascript" charset="utf-8">
		console.log('.$input.');
	</script>';
}

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