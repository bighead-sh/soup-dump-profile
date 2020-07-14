<?php


function curlGet ( $url, $trytimes ){

    echo '. ';

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTPHEADER     => array(
            "Connection: keep-alive",
            "x-requested-with: XMLHttpRequest",
            "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36",
            "x-prototype-version: 1.7.1"
        )
    ));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($trytimes == -10 || $trytimes > 0){
        if($httpcode != 200 ){
            sleep(1);
            if($trytimes == -10){
                return curlGet($url, $trytimes);
            }else{
                return curlGet($url, --$trytimes);
            }
        }else{
            return $content;
        }
    }else{
        return false;
    }
}


function getpage($url){

    $get = curlGet ( $url, -10 );

    $content = explode('|', $get, 2);
    preg_match('/since\/([0-9]*)/', $content[0], $out);


    return array(
            'next' => $out[1],
            'content' => $content[1]
        );
}




function clearCytat($text){
    $text = htmlspecialchars_decode( $text );
    $text = str_replace(array('&ldquo;', '&rdquo;'), '', $text);
    return trim($text);


}



function saveFile( $text, $file){
    $filename = dirname(__FILE__).'/dump/'.$file.'.txt';
    if(!file_exists($filename)){
        $fp = fopen( $filename, "w");
        flock($fp, 2);
        fwrite($fp, $text);
        flock($fp, 3);
        fclose($fp);
    }
}


function saveImg( $url, $file ){
    $filename = dirname(__FILE__).'/dump/'.$file.'.jpeg';
    if(!file_exists($filename)){
        $img = curlGet($url, 15);
        if($img !== false){
            $fp = fopen($filename, "w");
            flock($fp, 2);
            fwrite($fp, $img);
            flock($fp, 3);
            fclose($fp);
        }
    }
}
