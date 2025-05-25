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
mkdir -p /var/www/html/phpipam

# Copiar archivos
echo -e "${YELLOW}Copiando archivos...${NC}"
cp -r app/ /var/www/html/phpipam/
cp -r functions/ /var/www/html/phpipam/

# Configurar permisos
echo -e "${YELLOW}Configurando permisos...${NC}"
chown -R www-data:www-data /var/www/html/phpipam
chmod -R 755 /var/www/html/phpipam

# Crear base de datos
echo -e "${YELLOW}Creando base de datos...${NC}"
mysql -e "CREATE DATABASE IF NOT EXISTS phpipam;"
mysql -e "CREATE USER IF NOT EXISTS 'phpipam'@'localhost' IDENTIFIED BY 'phpipam123';"
mysql -e "GRANT ALL PRIVILEGES ON phpipam.* TO 'phpipam'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Crear tabla DNS
echo -e "${YELLOW}Creando tabla DNS...${NC}"
mysql phpipam << EOF
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