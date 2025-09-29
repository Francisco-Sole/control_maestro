# 🖥️ Control Maestro

Aplicación en desarrollo para **controlar equipos en la misma red local**, ofreciendo:

- 🔌 **Apagado remoto** de PCs  
- 🌐 **Wake-on-LAN (WOL)** para encender equipos mediante su dirección MAC  

---

## ✨ Características

- 🔻 Apagado remoto de equipos registrados  
- ⚡ Wake-on-LAN con envío de “paquete mágico”  
- 📜 Registro de acciones realizadas (qué, cuándo y a quién)  
- 🗂️ Gestión de equipos con nombre, IP, MAC y descripción  

---

## ⚙️ Requisitos

- 🐘 PHP 7.4 o superior  
- 🌍 Servidor web local (Apache/Nginx)  
- 🗄️ MySQL/MariaDB  
- 📡 Todos los equipos deben estar en la **misma LAN**  
- 🔧 Wake-on-LAN habilitado en BIOS/UEFI y en el sistema operativo del equipo objetivo  

---

## 📂 Estructura del proyecto

- 📁 **BD/** → Scripts SQL y estructura de base de datos  
- 📁 **controller/** → Controladores PHP (acciones de apagado, WOL)  
- 🖼️ **img/** → Recursos gráficos  
- 🏠 **index.php** → Panel principal de control  
- 🎛️ **escenario.php** → Gestión de escenarios de control  

---

## 🗄️ Base de datos y esquema

Antes de ejecutar el proyecto, debes crear la base de datos y sus tablas.  
En la carpeta [`/BD`](./BD) encontrarás el archivo con el esquema.

### 🚀 Importación rápida

```bash
mysql -u usuario -p < BD/schema.sql
```

# 📘 BD/schema.sql

```bash
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS control_maestro
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE control_maestro;

-- Tabla de equipos a controlar
CREATE TABLE IF NOT EXISTS equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    mac VARCHAR(20) NOT NULL,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de acciones realizadas sobre los equipos
CREATE TABLE IF NOT EXISTS acciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    tipo_accion ENUM('APAGADO','WOL') NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    resultado VARCHAR(255),
    FOREIGN KEY (id_equipo) REFERENCES equipos(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Tabla de usuarios (opcional, para control de acceso)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','user') DEFAULT 'user',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

# 🧪 Ejemplos de inserción
```bash
-- Insertar un equipo
INSERT INTO equipos (nombre, ip, mac, descripcion)
VALUES ('PC-Salon', '192.168.1.50', 'AA:BB:CC:DD:EE:FF', 'Ordenador del salón');

-- Registrar una acción de Wake-on-LAN
INSERT INTO acciones (id_equipo, tipo_accion, resultado)
VALUES (1, 'WOL', 'Paquete mágico enviado');

```

# 🚀 Puesta en marcha
📥 Clona el repositorio:

```bash
git clone https://github.com/Francisco-Sole/control_maestro.git
```
🗄️ Crea la base de datos importando BD/schema.sql.

🔑 Configura las credenciales en tu archivo de conexión PHP.

🖥️ Registra los equipos con su IP y MAC.

🌐 Abre index.php en tu navegador local y prueba las funciones.


# 📡 Cómo obtener la dirección MAC
La dirección MAC identifica la interfaz de red del equipo objetivo. Es necesaria para Wake-on-LAN.

## 🪟 Windows
```bash
ipconfig /all
```
👉 Busca el adaptador activo → Dirección física (ej. AA-BB-CC-DD-EE-FF).

## 🐧 Linux
```bash
ip addr show
# o
ifconfig

```
👉 En la interfaz activa (eth0, wlan0, etc.) → campo link/ether (ej. aa:bb:cc:dd:ee:ff).

## 🍏 macOS
```bash
ifconfig en0
# o
ifconfig en1
```

👉 Campo ether → dirección MAC (ej. aa:bb:cc:dd:ee:ff).

💡 Consejo: usa la MAC de la interfaz realmente conectada a tu LAN. Para WOL suele ser la de Ethernet.

# 🔧 Recomendaciones para Wake‑on‑LAN
⚙️ BIOS/UEFI: Habilita “Wake on LAN” o “Power on by PCI-E”.

🖥️ Sistema operativo: Activa WOL en el adaptador de red.

📡 Router/switch: Debe permitir broadcast ARP/Magic Packet.

📝 Formato MAC: Usa AA:BB:CC:DD:EE:FF o AA-BB-CC-DD-EE-FF (homogeneiza en tu app).

# 🚧 Estado del proyecto
Este proyecto está en desarrollo activo:

🔄 La estructura de tablas y endpoints puede cambiar.

🛠️ Algunas funciones aún no están implementadas.

🧪 Uso actual: prototipo/demostración en entorno local.

# 👤 Créditos
✍️ Autor: Francisco Solé

📍 Proyecto desarrollado en Barcelona, España

💡 Inspirado en la necesidad de gestionar equipos en red local de forma sencilla

# 📜 Licencia
Uso personal y educativo.
