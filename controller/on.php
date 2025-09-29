<?php

$request = $_GET["ip"];
$ip = "127.0.0.1";

$link = new mysqli($ip, "root", "", "control_maestro");
$link->set_charset("utf8") or die();

$consulta = "SELECT mac FROM equipos WHERE ip = '$request';";
$result = mysqli_query($link, $consulta);
while ($pos = mysqli_fetch_array($result)) {
	$mac = $pos[0];
}
//$mac = $output[intval($index+1)];


$mac_array = preg_split('#:#', $mac);
$hwaddr = '';
foreach ($mac_array as $octet) {
	$hwaddr .= chr(hexdec($octet));
}
//Magic Packet
$packet = '';
for ($i = 1; $i <= 6; $i++) {
	$packet .= chr(255);
}
for ($i = 1; $i <= 16; $i++) {
	$packet .= $hwaddr;
}
//set up socket
$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if ($sock) {
	$options = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, true);
	if ($options >= 0) {
		$e = socket_sendto($sock, $packet, strlen($packet), 0, "255.255.255.255", 7);
		socket_close($sock);
	}
}
