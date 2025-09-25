# SISAD - Sistema de Administración de Seguridad

📌 **Sisad** es un sistema web desarrollado en PHP para la gestión de seguridad, reportes y administración documental.  
Su objetivo es centralizar información, facilitar el seguimiento de incidentes y ofrecer un punto único de control para usuarios y administradores.

---

## 🚀 Características principales
- Gestión de reportes de seguridad.
- Administración de usuarios.
- Carga de documentos y reportes (Excel, PDF, etc.).
- Panel de control con indicadores básicos.
- Arquitectura modular para futuras ampliaciones.

---

## 📂 Estructura del proyecto


Sisad/
├── includes/ # Archivos compartidos y de configuración
├── pages/ # Vistas y módulos principales
│ ├── seg/ # Seguridad
│ ├── usuarios/ # Administración de usuarios
│ └── ... # Otros módulos
├── assets/ # Estilos, JS, imágenes
└── index.php # Entrada principal


---

## 🛠️ Requisitos
- Servidor web (Apache o Nginx).
- PHP >= 7.4
- MySQL/MariaDB
- Extensiones PHP: `mysqli`, `gd`, `mbstring`, `zip` (dependiendo del uso).

---

## ⚙️ Instalación
1. Clonar el repositorio:
   ```bash
   git clone https://github.com/Manuel-hdz/sisad.git


Configurar la base de datos en includes/config.php.

Importar el archivo SQL en tu motor de base de datos.

Levantar el servidor local:

php -S localhost:8000


Acceder en tu navegador a:

http://localhost:8000

🔑 Credenciales de prueba

Usuario: admin

Contraseña: admin123

(puedes cambiarlas en la base de datos después de la instalación).

🎨 Futuras mejoras

Migración a Laravel para contar con rutas, ORM y autenticación integrada.

Implementación de Bootstrap/Tailwind para mejorar la experiencia de usuario.

Optimización de módulos para carga dinámica.

Manejo de variables de entorno con .env.

📜 Licencia

Este proyecto se distribuye bajo la licencia MIT.
Eres libre de usarlo, modificarlo y mejorarlo.

✍️ Autor: Manuel Hdz
