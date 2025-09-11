<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Mapa de equipos</title>
	<link rel="stylesheet" href="">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>
<style type="text/css" media="screen">
	html, body{
		border: 0;
		margin:0;
		padding: 0;
	}
	#cargando{
		width: 100vw;
		height: 100vh;
		position: fixed;
		z-index: 9999;
		background-color: rgba(0,0,0,0.3);
	}	

	#cargando img{
		margin:25% calc(50% - 30px);
	}

	#log{
		float: left;
		font-family: "consolas";
		margin-left: 30px;
		padding-top: 10px;
	}

	#content{
		#width: 100%;
		margin-top: 15px;
	}

	#buscador{
		width: 200px;
		height: 50px;
		margin-top: 15px;
		position: fixed;
		left: 25px;
	}

	li:hover{
		background-color: #147491 !important;
	}

	#consola_maxi{
		display: none;
		position: relative;
		z-index: 99;
	}

</style>
<body>
	<input style="margin-top: -100px;opacity: 0;" type="text" name="inputxxx" id="inputxxx">
	<div id="cargando">
		<img height="60" src="./img/loading.gif" alt="Cargando...">
	</div>
	<div id="log">
		<p style="margin:0">Cargando...</p>
	</div>
	<div id="consola_maxi" style="background: rgb(0,0,0);position: fixed;color: white;width: 100%;overflow-y: auto;">
		<div id="consola_maxi_content"></div>
		<div onclick="$('#consola_maxi').hide();" style="position: fixed; bottom: 50px;cursor: pointer; border: 1px solid white; width: 100px;margin-left: 10px;padding: 10px;text-align: center;background:#29C0EE">Cerrar</div>
	</div>
	<div id="buscador">
		<input type="text" id="savage_search" placeholder="Nombre, IP, MAC">
	</div>
	<div id="content">
		
	</div>
	<script>

		function cargarPc(){
			var parameter = { "controller": "pc", "data": { "cmd": "get_all" } };
			$.ajax({
				data:parameter,
				method: "POST",
				url: "controller/main_controller.php",
				beforeSend: function(rrr) {
					$("#log").append("<p>Consultando IP's...</p>");
				},
				success: function (response) {
					$("#log")    .append("<p>" + response.length + " IP encontradas...</p>");
					$("#log")    .append("<p>Dibujando oficina...</p>");
					$("#content").css("height", parseInt(window.innerHeight - 120)  + "px")
					var html      = "<div style='float:left;'>";
					var ancho     = parseInt($(window).width());
					var acumulado = 0;
					var enter     = 0;
					
					for(var x = 0; x<response.length; x++){
						html      += "<div class='main' id='host-" + response[x].id + "' style='margin-left: 50px;float:left;width: " + parseInt(window.innerWidth)/4 + "px; height: " + parseInt(window.innerHeight - 120)  + "px' host-id='" + response[x].id + "' host-nombre='" + response[x].nombre + "' host-ip='" + response[x].ip + "' host-mac='" + response[x].mac + "'>";
						acumulado += parseInt(window.innerWidth)/4;
						acumulado += parseInt(50);
						
						html      += "<div style='float:left; text-align: #center;font-family: sans-serif'>";
						html      += "<p class='nombre'><span style='font-weight:900'>Nombre:</span> ";
						html      += response[x].nombre;
						html      += "&nbsp;&nbsp;<img alt='copiar' height='20px' src='./img/copiar.png' onclick='copiar(\"" + response[x].nombre + "\")' style='float: right;cursor:pointer'/></p>";
						html      += "<p class='ip'><span style='font-weight:900'>IP: </span> ";
						html      += response[x].ip;
						html      += "&nbsp;&nbsp;<img alt='copiar' height='20px' src='./img/copiar.png' onclick='copiar(\"" + response[x].ip + "\")' style='float: right;cursor:pointer'/></p>";
						html      += "<p class='mac'><span style='font-weight:900'>MAC: </span> ";
						html      += response[x].mac;
						html      += "&nbsp;&nbsp;<img alt='copiar' height='20px' src='./img/copiar.png' onclick='copiar(\"" + response[x].mac + "\")' style='float: right;cursor:pointer'/></p>";
						
						html      += "<img id='img_host-" + response[x].id + "' width= " + parseInt(window.innerWidth)/4 + "px style='float:left; cursor:pointer;' src='./img/pc.png' alt='pc image'/>";
						html      += "</div>";
						html      += "<div onclick='expandir(\"consola_host-" + response[x].id + "\")' style='cursor:pointer;float:left; width: 100%;font-weight: 900;'>Consola&nbsp;&nbsp;&nbsp;<img alt='expandir' src='./img/expan.png' height='15px;'/></div>";
						html      += "<div style='float:left; width: 100%;border: solid 1px black;height: 150px;font-family: monospace; overflow-y: auto;' id='consola_host-" + response[x].id + "'></div>";
						html      += "<div class='menu' id='menu_host-"+response[x].id +"' style='display:none;margin-top: -520px;float: left;'></div>"
						html      += "</div>";

						if(acumulado > ancho && enter == 0){
							enter = 1;
							grupo = x;
						}
					}

					html += "</div>";
					

					$("#log")     .append("<p>Terminado...</p>");
					$("#log")     .html("");
					$("#cargando").fadeOut(400);
					$("#content") .html(html);
					var ancho       = parseInt($(window).width());
					var ancho_grupo = grupo * parseInt(window.innerWidth)/4 + grupo + 2 * 50;
					var diferencia  = parseInt(ancho - ancho_grupo - 50);
					
					$("#content").css("margin-left", (diferencia/2) + "px");
					
					$("img[id^=img_host]").each(function(index, el) {
						$(this).bind({
							click: function (){
								var idd = $(this).parent("div").parent("div").attr("id");
								var estado = $("#menu_"+ idd ).css("display");
								$(".menu").css("display", "none");
								console.log($("#menu_"+ idd ).css("display"));
								if(estado == "none"){
									muestra_menu(idd);
									$("#menu_"+ idd ).css("display", "block");
								}else{
									$("#menu_"+ idd ).css("display", "none");
								}
							},
							touch: function(){
								var idd = $(this).parent("div").parent("div").attr("id");
								var estado = $("#menu_"+ idd ).css("display");
								$(".menu").css("display", "none");
								console.log($("#menu_"+ idd ).css("display"));
								if(estado == "none"){
									muestra_menu(idd);
									$("#menu_"+ idd ).css("display", "block");
								}else{
									$("#menu_"+ idd ).css("display", "none");
								}
							}
						});
					});

					$(".main").each(function(index, el) {
						var idd = $(this).attr("id");
						actualizarEstado(idd);
					});
				},
				dataType: "JSON"
			});
}

		//funcion que mostrara el menu del PC
		function muestra_menu(id){

			var html = "";
			html += "<div class='menu_' style='position: relative; font-family: consolas;float:left;'>";
			html += "<ul style='list-style: none;padding: 0; margin: 0;'>";
			html += "<li style='color: white ;background-color: #29C0EE; padding: 5px;cursor: pointer;' onclick='encender(\""+ id + "\")'>Encender</li>";
			html += "<li style='color: white ;background-color: #29C0EE; padding: 5px;cursor: pointer;' onclick='apagar(\""+id + "\")'>Apagar</li>";
			html += "<li style='color: white ;background-color: #29C0EE; padding: 5px;cursor: pointer;' onclick='reiniciar(\""+id + "\")'>Reiniciar</li>";
			html += "<li style='color: white ;background-color: #29C0EE; padding: 5px;cursor: pointer;' onclick='ping(\"" + id + "\")'>Ping</li>";
			html += "<li style='color: white;background-color: #29C0EE; padding: 5px;cursor:  pointer;' onclick='updateMAC(\"" + id + "\")'>Actualizar MAC</li>";
			html += "<li style='color: white;background-color: #29C0EE; padding: 5px;cursor:  pointer;' onclick='actualizarEstado(\"" + id + "\")'>Actualizar ESTADO</li>";
			html += "<li style='color: white;background-color: #29C0EE; padding: 5px;cursor:  pointer;' onclick='datosSistema(\"" + id + "\")'>Datos de sistema</li>";
			html += "</ul>";
			html += "</div>";
			$("#menu_"+id).html(html);
		}

		//funcion que enciende el pc objetivo
		function encender(id){
			var ip = $("#"+ id).attr("host-ip");
			var mac = $("#"+ id).attr("host-mac");

			var parameter = { "controller": "pc", "data": { "cmd": "wake_up", "ip": ip, "mac": mac } };
			$.ajax({
				data:parameter,
				method: "POST",
				url: "controller/main_controller.php",
				beforeSend: function(rrr) {
					$(".menu").css("display", "none");
					$("#consola_"+id).append("<p>Enviando peticion: Encender...</p>");
				},
				success: function (response) {
					$("#consola_"+id).append("<p>Respuesta recibida.</p>");
					setTimeout(function (){
						actualizarEstado(id);						
					},20000);
				},
				dataType: "JSON"
			});
		}

		//funcion que apaga el pc objetivo
		function apagar(id){
			var ip = $("#"+ id).attr("host-ip");
			var mac = $("#"+ id).attr("host-mac");

			var parameter = { "controller": "pc", "data": { "cmd": "shut_down", "ip": ip, "mac": mac } };
			$.ajax({
				data:parameter,
				method: "POST",
				url: "controller/main_controller.php",
				beforeSend: function(rrr) {
					$(".menu").css("display", "none");
					$("#consola_"+id).append("<p>Enviando peticion: Apagar...</p>");
				},
				success: function (response) {
					$("#consola_"+id).append("<p>Respuesta recibida.</p>");
					setTimeout(function (){
						actualizarEstado(id);						
					},20000);
				},
				dataType: "JSON"
			});
		}

		function reiniciar(id){
			var ip = $("#"+ id).attr("host-ip");
			var mac = $("#"+ id).attr("host-mac");

			var parameter = { "controller": "pc", "data": { "cmd": "reset", "ip": ip, "mac": mac } };
			$.ajax({
				data:parameter,
				method: "POST",
				url: "controller/main_controller.php",
				beforeSend: function(rrr) {
					$(".menu").css("display", "none");
					$("#consola_"+id).append("<p>Enviando peticion: Reiniciar...</p>");
				},
				success: function (response) {
					$("#consola_"+id).append("<p>Respuesta recibida.</p>");
				},
				dataType: "JSON"
			});
		}

		//funcion que hace un ping de 4 turnos
		function ping(id){
			var ip = $("#"+ id).attr("host-ip");
			var mac = $("#"+ id).attr("host-mac");

			var parameter = { "controller": "pc", "data": { "cmd": "do_ping", "ip": ip, "mac": mac } };
			$.ajax({
				data:parameter,
				method: "POST",
				url: "controller/main_controller.php",
				beforeSend: function(rrr) {
					$(".menu").css("display", "none");
					$("#consola_"+id).append("<p>Enviando ping...</p>");
				},
				success: function (response) {
					for (var i = 0; i < response.length; i++) {
						$("#consola_"+id).append("<pre>" + response[i] + "</pre>");						
					}
				},
				dataType: "JSON"
			});
		}

		//funcion que obtiene la mac del ordenador objetivo y la actualiza en la bd
		function updateMAC(id){
			var ip = $("#"+ id).attr("host-ip");
			var mac = $("#"+ id).attr("host-mac");

			var parameter = { "controller": "pc", "data": { "cmd": "update_mac", "ip": ip, "mac": mac } };
			$.ajax({
				data:parameter,
				method: "POST",
				url: "controller/main_controller.php",
				beforeSend: function(rrr) {
					$(".menu").css("display", "none");
					$("#consola_"+id).append("<p>Enviando peticion: actualizar MAC...</p>");
				},
				success: function (response) {
					if(response.status == true){
						$("#consola_"+id).append("<p>Update correcto!</p>");
						$(".main[host-ip='" + response.ip + "']").children('div').children('p.mac').html("MAC: " + response.mac);
						$(".main[host-ip='" + response.ip + "']").attr("host-mac", response.mac);
					}
				},
				dataType: "JSON"
			});
		}

		//funcion que actualiza el estado del ordenador objetivo
		function actualizarEstado(id){
			var ip = $("#"+ id).attr("host-ip");
			var mac = $("#"+ id).attr("host-mac");

			var parameter = { "controller": "pc", "data": { "cmd": "update_status", "ip": ip, "mac": mac } };
			$.ajax({
				data:parameter,
				method: "POST",
				url: "controller/main_controller.php",
				beforeSend: function(rrr) {
					$(".menu").css("display", "none");
					$("#consola_"+id).append("<p>Enviando petcion: actualizar estado...</p>");
				},
				success: function (response) {
					$("#consola_"+id).append("<p>Estado actualizado!</p>");
					if(response.status == "KO"){
						$("#img_"+ id).attr("src", "./img/pc-ko.png")
					}else{
						$("#img_"+ id).attr("src", "./img/pc.png")
					}
				},
				dataType: "JSON"
			});
		}

		//funcion que proporciona datos de sistema
		function datosSistema(id){
			var ip = $("#"+ id).attr("host-ip");
			var mac = $("#"+ id).attr("host-mac");

			var parameter = { "controller": "pc", "data": { "cmd": "system_data", "ip": ip, "mac": mac } };
			$.ajax({
				data:parameter,
				method: "POST",
				url: "controller/main_controller.php",
				beforeSend: function(rrr) {
					$(".menu").css("display", "none");
					$("#consola_"+id).append("<p>Enviando peticion...</p>");
				},
				success: function (response) {
					for (var i = 0; i < response.data.length; i++) {
						$("#consola_"+id).append("<pre>" + response.data[i] + "</pre>");						
					}		
				},
				dataType: "JSON"
			});
		}

		//funcion que expande la pantalla de consola
		function expandir(id){
			$("#consola_maxi_content").html($("#"+id).html());
			$("#consola_maxi").css("height",  parseInt(window.innerHeight) +"px")			
			$("#consola_maxi").show();
		}	
		

		function copiar (texto){
			$("#inputxxx").val(texto);
			var copyText = document.getElementById("inputxxx");
			copyText.select();
			copyText.setSelectionRange(0, 99999)
			document.execCommand("copy");
			Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: 'Copiado en portapapeles!',
				showConfirmButton: false,
				timer: 1500
			});
		}

		$(document).ready(function() {
			$("#savage_search").keyup(function(event) {
				$(".main").hide();
				var busca = $(this).val().trim().toUpperCase();
				$(".main").each(function(index, el) {
					var nombre, ip, mac;
					nombre = $(el).attr("host-nombre").toUpperCase();

					ip = $(el).attr("host-ip").toUpperCase();

					mac = $(el).attr("host-mac").toUpperCase();

					if(nombre.includes(busca) || ip.includes(busca) || mac.includes(busca) ){
						$(el).show();
					}
				});
			});
		});
	</script>

</body>
</html>
<script>
	cargarPc();
</script>
