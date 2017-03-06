<?php
namespace App\Helpers;

use App\Models\Book;

class CrossDomainHelper {

    /* check url exists
     * code from: http://www.phpddt.com/php/php-image-exist.html
     */
    public static function url_exists($url, &$real_url=false) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        $retCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_getinfo($ch);
        curl_close($ch);

        $headers = array();

        $found = false;
        switch($retCode)
        {
            case 200: $found = true; if($real_url!==false) $real_url = $url; break;
            case 302: $found = true; if($real_url!==false) $real_url = $response['redirect_url']; break;
            default: break;
        }

        return $found;
    }
}


