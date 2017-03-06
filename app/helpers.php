<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 22/02/2017
 * Time: 9:05 AM
 */

function url_file_exists($url) {
	\EasyWeChat\Support\Log::info($url);
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	$result = curl_exec($curl);
	$found = false;
	if ($result !== false) {
		$info = curl_getinfo($curl);
		$http_code = $info['http_code'];
		if($http_code == '200'){
			$found = true;
		}
	}
	return $found;
}