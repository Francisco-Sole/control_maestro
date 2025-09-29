<?php
$output = exec('ping 127.0.0.1', $output);
echo "<pre>$output</pre>";
// $output = shell_exec('SHUTDOWN /m \\\\10.2.234.32 /f /s /t 0');
// echo "<pre>$output</pre>";

// $output = shell_exec('whoami');
// echo "<pre>$output</pre>";


// $mymac = "A0:48:1C:8F:61:6F";
// wol("255.255.255.255", $mymac);
// echo 'WOL sent to '.$mymac;

// function wol($broadcast, $mac){
// $mac_array = preg_split('#:#', $mac); //print_r($mac_array);
// $hwaddr = '';
//     foreach($mac_array AS $octet){
//     $hwaddr .= chr(hexdec($octet));
//     }
//     //Magic Packet
//     $packet = '';
//     for ($i = 1; $i <= 6; $i++){
//     $packet .= chr(255);
//     }
//     for ($i = 1; $i <= 16; $i++){
//     $packet .= $hwaddr;
//     }
//     //set up socket
//     $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
//     if ($sock){
//     $options = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, true);
//         if ($options >=0){    
//         $e = socket_sendto($sock, $packet, strlen($packet), 0, $broadcast, 7);
//         socket_close($sock);
//         }    
//     }
// }  
