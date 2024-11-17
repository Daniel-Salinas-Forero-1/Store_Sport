Tienda Deportiva - Gestión de Productos y Órdenes
Este proyecto es una aplicación backend desarrollada en Laravel para gestionar productos y órdenes en una tienda deportiva. Proporciona funcionalidades de  CRUD completo para productos y órdenes, y permite realizar pruebas fácilmente mediante Postman.

Requisitos previos
PHP 8.1 o superior
Composer 2.x
MySQL 8.0 o superior
Postman (opcional, para probar los endpoints)

paquetes descargados para la exportacion en excel 

composer require maatwebsite/excel


Clona el repositorio y navega al directorio del proyecto:

bash
Copy code
git clone https://github.com/usuario/tienda-deportiva.git
cd tienda-deportiva


Instala las dependencias del proyecto:

bash
Copy code
composer install


Copia el archivo de configuración y configura la base de datos en .env:

bash
Copy code
cp .env.example .env
Edita las variables DB_DATABASE, DB_USERNAME y DB_PASSWORD con los datos de tu base de datos.


Ejecuta las migraciones y seeders para crear las tablas y datos iniciales:

bash
Copy code
php artisan migrate --seed
Inicia el servidor de desarrollo:

bash
Copy code
php artisan serve
La aplicación estará disponible en http://127.0.0.1:8000.