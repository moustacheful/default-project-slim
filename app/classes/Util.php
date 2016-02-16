<?php
class Util {

	public function fail($message,$status=0,$http_status=400){
		# TODO
	}

	public static function json($data,$status = FALSE){
		# Already included?
	}


	public static function slugify($str,$delimiter){
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		return $clean;
	}

}
?>