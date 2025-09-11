<?php
include ('../BD/connect.php');

$response = [];

//Proporciona todos los usuarios que tienes PC
function pc_get_all_pc(){
	global $response;
	$link = new_connection();
	$consulta = "SELECT id, nombre, ip, mac FROM equipos;";
	$result = mysqli_query($link, $consulta);
	while ($pos = mysqli_fetch_array($result)) {
		$t = array(
			"id"     => $pos[0],
			"nombre" => $pos[1],
			"ip"     => $pos[2],
			"mac"    => $pos[3]
		);
		array_push($response, $t);
	}
	return json_encode($response);
}

//Obtiene una mac mediante una IP
function pc_get_mac($ip){
	global $response;
	$output = shell_exec('arp -a '.$ip);
	//partimos en trozos por \t \r \n
	$output = preg_split("/[\s]+/", $output);
	
	//ahora busco la IP en el array
	$index = 0;
	for($x=0; $x<count($output); $x++){
		if($output[$x] == $ip){
			$index = $x;
		}
	}

	$mac = $output[intval($index+1)];
	
	$t = array(
		"ip"     => $ip,
		"mac" => $mac
	);
	
	return json_encode($t);
}

//Manda una peticion de wol
function pc_wake_up($mac){
	wol("255.255.255.255", $mac);

	$t = array(
		"mac" => $mac
	);
	
	return json_encode($t);
}

//Manda una peticion de apagado	
function pc_shut_down($ip){
	shell_exec('SHUTDOWN /m \\\\'.$ip.' /f /s /t 0');
	$t = array(
		"ip" => $ip
	);
	
	return json_encode($t);
}

//Manda una peticion de reinicio	
function pc_reset($ip){
	shell_exec('SHUTDOWN /m \\\\'.$ip.' /f /r /t 0');
	$t = array(
		"ip" => $ip
	);
	
	return json_encode($t);
}

//Manda una peticion de ping a la consola
function pc_do_ping($ip){
	global $response;
	exec('ping ' . $ip, $response, $r);
	
	$t = [];

	for ($i=0; $i < count($response) ; $i++) { 
		array_push($t, utf8_encode($response[$i]));	
	}
	return json_encode($t);
}

function pc_update_mac($mac){
	$obj = json_decode($mac);
	$dir = $obj->mac;
	$ip  = $obj->ip;
	//acondicionamos la direccion mac
	$dir = str_replace('-', ':', $dir);
	$dir = strtoupper($dir);
	
	$link     = new_connection();
	$consulta = "UPDATE equipos SET mac = '$dir' WHERE ip = '$ip';";
	$result   = mysqli_query($link, $consulta);

	$t = array(
		"mac" => $dir,
		"ip"=> $ip,
		"status" => $result
	);
	return json_encode($t);
}

function pc_update_status($ip){
	$salida;
	$status = "OK";	
	exec('ping -n 2 ' . $ip, $salida);

	$t = [];
	//utf-8
	for ($i=0; $i < count($salida) ; $i++) { 
		array_push($t, utf8_encode($salida[$i]));	
	}

	//split para ver si tiene mas de 1
	for ($i=0; $i < count($t) ; $i++) { 
		$temp = [];
		$temp = preg_split('/inaccesible/', $t[$i]);
		if(count($temp) > 1){
			$status = "KO";
		}
	}	

	$tt = array(
		"ip"=> $ip,
		"status" => $status
	);
	return json_encode($tt);
}

function pc_system_data($ip){
	exec('systeminfo /S ' . $ip, $response, $r);
	
	$t = [];

	for ($i=0; $i < count($response) ; $i++) { 
		array_push($t, utf8_encode($response[$i]));	
	}
	
	$tt = array(
		"ip" => $ip,
		"data" => $t
	);

	return json_encode($tt);
}


//funcion secundaria
function wol($broadcast, $mac){
	$mac_array = preg_split('#:#', $mac); 
	$hwaddr = '';
	foreach($mac_array AS $octet){
		$hwaddr .= chr(hexdec($octet));
	}
    //Magic Packet
	$packet = '';
	for ($i = 1; $i <= 6; $i++){
		$packet .= chr(255);
	}
	for ($i = 1; $i <= 16; $i++){
		$packet .= $hwaddr;
	}
    //set up socket
	$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	if ($sock){
		$options = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, true);
		if ($options >=0){    
			$e = socket_sendto($sock, $packet, strlen($packet), 0, $broadcast, 7);
			socket_close($sock);
		}    
	}
}  