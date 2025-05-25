# Módulo DNS para phpIPAM

Este módulo extiende la funcionalidad de phpIPAM para gestionar registros DNS de manera eficiente.

## Requisitos del Sistema

- Debian 10 o superior
- Apache2
- PHP 7.4 o superior
- MySQL 5.7 o superior
- PEAR Net_DNS2

## Instalación

1. Clona este repositorio:
```bash
git clone https://github.com/rubenmagne/phpipam_ti.git
cd phpipam_ti
```

2. Configura MySQL:
   - Asegúrate de tener MySQL instalado y funcionando
   - Verifica que el usuario root tenga una contraseña configurada
   - Si no tienes contraseña para root, puedes configurarla con:
     ```bash
     sudo mysql_secure_installation
     ```

3. Ejecuta el script de instalación como root:
```bash
chmod +x install.sh
sudo ./install.sh
```
   - El script te pedirá la contraseña de MySQL root
   - Si prefieres no ingresar la contraseña manualmente, puedes:
     a) Editar el archivo mysql_config.cnf con tus credenciales
     b) Usar el archivo de configuración: `sudo ./install.sh --config mysql_config.cnf`

4. Accede a la aplicación:
```
http://localhost/phpipam
```

## Estructura del Proyecto

```
phpipam-dns/
├── app/
│   └── dns/
│       ├── index.php
│       ├── add.php
│       ├── edit.php
│       └── delete.php
├── functions/
│   └── classes/
│       └── class.DNS.php
├── install.sh
├── mysql_config.cnf
└── README.md
```

## Características

- Gestión de registros DNS (A, AAAA, PTR, CNAME, MX, TXT)
- Validación de formatos de hostname y direcciones IP
- Búsqueda y filtrado de registros
- Interfaz web intuitiva
- Integración con phpIPAM

## Uso

1. Accede a la interfaz web
2. Navega a la sección DNS
3. Utiliza los botones de acción para:
   - Ver todos los registros
   - Añadir nuevos registros
   - Editar registros existentes
   - Eliminar registros

## Solución de Problemas

### Error de Acceso a MySQL
Si recibes un error de acceso denegado a MySQL:
1. Verifica que MySQL esté instalado y funcionando:
   ```bash
   sudo systemctl status mysql
   ```
2. Asegúrate de que el usuario root tenga una contraseña:
   ```bash
   sudo mysql_secure_installation
   ```
3. Intenta conectarte manualmente a MySQL:
   ```bash
   mysql -u root -p
   ```

### Error de Permisos
Si tienes problemas de permisos:
1. Verifica los permisos del directorio:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/phpipam
   sudo chmod -R 755 /var/www/html/phpipam
   ```
2. Verifica los logs de Apache:
   ```bash
   sudo tail -f /var/log/apache2/error.log
   ```

## Seguridad

- Cambia las credenciales por defecto después de la instalación
- Configura HTTPS para acceso seguro
- Implementa control de acceso basado en roles
- Realiza copias de seguridad regulares de la base de datos

## Soporte

Para reportar problemas o solicitar ayuda:
1. Abre un issue en GitHub
2. Proporciona detalles del problema
3. Incluye logs relevantes

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo LICENSE para más detalles.
