#!/bin/bash

# Colores para mensajes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}Iniciando instalación de phpIPAM DNS Module...${NC}"

# Verificar si se está ejecutando como root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Por favor ejecute este script como root${NC}"
    exit 1
fi

# Solicitar contraseña de MySQL
echo -e "${YELLOW}Por favor, ingrese la contraseña de MySQL root:${NC}"
read -s MYSQL_ROOT_PASSWORD

# Verificar conexión a MySQL
if ! mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; then
    echo -e "${RED}Error: No se pudo conectar a MySQL. Verifique la contraseña.${NC}"
    exit 1
fi

# Actualizar repositorios
echo -e "${YELLOW}Actualizando repositorios...${NC}"
apt-get update

# Instalar dependencias
echo -e "${YELLOW}Instalando dependencias...${NC}"
apt-get install -y apache2 php php-mysql php-pear php-curl php-gd php-mbstring php-xml php-zip mysql-server

# Instalar Net_DNS2
echo -e "${YELLOW}Instalando Net_DNS2...${NC}"
pear install Net_DNS2

# Crear directorio para la aplicación
echo -e "${YELLOW}Creando directorio de la aplicación...${NC}"
mkdir -p /var/www/html/phpipam/app/dns
mkdir -p /var/www/html/phpipam/functions/classes

# Copiar archivos
echo -e "${YELLOW}Copiando archivos...${NC}"
cp -r app/dns/* /var/www/html/phpipam/app/dns/
cp -r functions/classes/* /var/www/html/phpipam/functions/classes/

# Configurar permisos
echo -e "${YELLOW}Configurando permisos...${NC}"
chown -R www-data:www-data /var/www/html/phpipam
chmod -R 755 /var/www/html/phpipam

# Crear base de datos
echo -e "${YELLOW}Creando base de datos...${NC}"
mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS phpipam;"
mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "CREATE USER IF NOT EXISTS 'phpipam'@'localhost' IDENTIFIED BY 'phpipam123';"
mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "GRANT ALL PRIVILEGES ON phpipam.* TO 'phpipam'@'localhost';"
mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "FLUSH PRIVILEGES;"

# Crear tabla DNS
echo -e "${YELLOW}Creando tabla DNS...${NC}"
mysql -u root -p"$MYSQL_ROOT_PASSWORD" phpipam << EOF
CREATE TABLE IF NOT EXISTS dns_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hostname VARCHAR(255) NOT NULL,
    ip_addr VARCHAR(45) NOT NULL,
    record_type VARCHAR(10) NOT NULL,
    ttl INT DEFAULT 3600,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    version VARCHAR(32) NOT NULL,
    description TEXT,
    menu_order INT DEFAULT 0,
    menu_icon VARCHAR(32),
    menu_name VARCHAR(64),
    menu_href VARCHAR(64),
    menu_visible TINYINT(1) DEFAULT 1,
    menu_parent VARCHAR(64),
    menu_position VARCHAR(32) DEFAULT 'left',
    menu_show TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY name (name)
);
EOF

# Habilitar módulo en phpIPAM
echo -e "${YELLOW}Habilitando módulo DNS...${NC}"
mysql -u root -p"$MYSQL_ROOT_PASSWORD" phpipam << EOF
INSERT INTO settings (name, value) VALUES ('enableDNS', '1') ON DUPLICATE KEY UPDATE value = '1';
INSERT INTO modules (name, version, description, menu_order, menu_icon, menu_name, menu_href, menu_visible, menu_parent, menu_position, menu_show) 
VALUES ('DNS', '1.0', 'DNS records management module', 50, 'fa-server', 'DNS', 'dns', 1, NULL, 'left', 1)
ON DUPLICATE KEY UPDATE 
version = '1.0',
description = 'DNS records management module',
menu_order = 50,
menu_icon = 'fa-server',
menu_name = 'DNS',
menu_href = 'dns',
menu_visible = 1,
menu_parent = NULL,
menu_position = 'left',
menu_show = 1;
EOF

# Reiniciar servicios
echo -e "${YELLOW}Reiniciando servicios...${NC}"
systemctl restart apache2
systemctl restart mysql

echo -e "${GREEN}Instalación completada!${NC}"
echo -e "${YELLOW}Accede a http://localhost/phpipam para comenzar a usar el módulo DNS${NC}"
echo -e "${YELLOW}Credenciales de la base de datos:${NC}"
echo -e "Usuario: phpipam"
echo -e "Contraseña: phpipam123"
echo -e "Base de datos: phpipam"

# Limpiar la variable de contraseña
unset MYSQL_ROOT_PASSWORD 