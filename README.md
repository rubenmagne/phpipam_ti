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
git clone https://github.com/tu-usuario/phpipam-dns.git
cd phpipam-dns
```

2. Ejecuta el script de instalación como root:
```bash
chmod +x install.sh
sudo ./install.sh
```

3. Accede a la aplicación:
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
