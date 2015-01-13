<?php
function sanitize($input) {
    if (is_array($input)) {
        $output = array();
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    } else {
    	$output = "";
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = $input;
    }
    return $output;
}
function cleanInput($input) {

	$search = array(
	   	'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
	    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
	    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
	    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

	$output = preg_replace($search, '', $input);
    return $output;
}

function gen_uuid($len=8) {

    $hex = md5("saltySaltSALT" . uniqid("", true));

    $pack = pack('H*', $hex);
    $tmp =  base64_encode($pack);

    $uid = preg_replace("#(*UTF8)[^A-Za-z0-9]#", "", $tmp);

    $len = max(4, min(128, $len));

    while (strlen($uid) < $len)
        $uid .= gen_uuid(22);

    return substr($uid, 0, $len);
}
?>