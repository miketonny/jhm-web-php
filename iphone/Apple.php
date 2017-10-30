<?php

/**

 * Description of Encryption

 * @author girish

 */

class Lib_Apple {

    

    public  function sendAppleNotification($dataArr) {

        $message = $dataArr['message'];
        $deviceToken = $dataArr['deviceToken'];
        $sandbox = FALSE;
        $badge = 1;
        $sound = 'default';
        $nid = $dataArr['emer_id'];
        $passphrase = '123';
        $host =  $sandbox ? 'gateway.sandbox.push.apple.com' : 'gateway.push.apple.com';
        $port = 2195;
        $cert = 'ruok_dev.pem';

        

        // Build the payload

        $payload[ 'aps' ] = array( 'alert' => $message , 'badge' => $badge , 'sound' => $sound , 'nid' => $nid );

        $payload = json_encode( $payload );



        $stream_context = stream_context_create();

		

        stream_context_set_option( $stream_context , 'ssl' , 'local_cert' , $cert );



        if ($passphrase){

                stream_context_set_option( $stream_context , 'ssl' , 'passphrase' , $passphrase );
		}


        $apns = stream_socket_client( 'ssl://' . $host . ':' . $port , $error , $error_string , 60 , STREAM_CLIENT_CONNECT , $stream_context );

        

        $message = chr(0) . chr(0) . chr(32) . pack( 'H*' , str_replace( ' ' , '' , $deviceToken ) ) . chr(0) . chr( strlen( $payload ) ) . $payload;

        $result  = fwrite( $apns , $message );
		 $host = "52.26.47.6";
$port = 2195;
$timeout = 5;
 
$tbegin = microtime(true); 
 
$fp = fsockopen($host, $port, $errno, $errstr, $timeout); 
 
$responding = 1;
if (!$fp) { $responding = 0; } 
 
$tend = microtime(true);
 
fclose($fp);
 
$mstime = ($tend - $tbegin) * 1000;
$mstime = round($mstime, 2);
 
if($responding)
{
    echo "$host responded to requests on port $port in $mstime milliseconds!\n";
}
else
{
    echo "$host is not responding to requests on port $port!";
}
die();

        //socket_close( $apns );

        fclose( $apns );

        if (!$result )

        {

            echo "\n Message not delivered" . PHP_EOL;

        }else{

            echo "\n Message delivered" . PHP_EOL;

        }

    }

    

}

?>

