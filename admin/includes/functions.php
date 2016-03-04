<?php

function is_valid_value($value) {
	if($value == "" || !isset($value)) {
		return false;
	}
	return true;
}

?>