<?php
include ('pc_controller.php');

$controller = $_POST["controller"];
$data = $_POST["data"];
//var_dump($data);

switch ($controller) {
	case 'pc':
	switch ($data["cmd"]) {
		case 'get_all':
		$result = pc_get_all_pc();
		echo $result;
		break;

		case 'get_mac':
		$result = pc_get_mac($data["ip"]);
		echo $result;
		break;

		case 'wake_up':
		$result = pc_wake_up($data["mac"]);
		echo $result;
		break;

		case 'shut_down':
		$result = pc_shut_down($data["ip"]);
		echo $result;
		break;

		case 'reset':
		$result = pc_reset($data["ip"]);
		echo $result;
		break;

		case 'do_ping':
		$result = pc_do_ping($data["ip"]);
		echo $result;
		break;

		case 'update_mac':
		$mac = pc_get_mac($data["ip"]);
		$result = pc_update_mac($mac);
		echo $result;
		break;

		case 'update_status':
		$result = pc_update_status($data["ip"]);
		echo $result;
		break;

		case 'system_data':
		$result = pc_system_data($data["ip"]);
		echo $result;
		break;
		
		
		default:
		$result -1;
		break;
	}
	break;
	
	default:
	$result -1;
	break;
}
