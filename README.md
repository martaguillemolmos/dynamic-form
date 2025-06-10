# Formulario dinámico

## Descripción del proyecto

Este proyecto es una aplicación web desarrollada con Laravel 10, MongoDB, Vue 3, Vite y Bootstrap 5, orientada a la gestión dinámica de formularios personalizados. La solución está diseñada para que los usuarios puedan crear, gestionar y recopilar información a través de formularios configurables, sin necesidad de modificar el código fuente.

### Funcionalidades principales

1. Gestión de plantillas de formularios
    
    La aplicación permite la creación, edición, visualización y eliminación de formularios dinámicos (denominados plantillas), almacenados como documentos en MongoDB. Cada plantilla contiene:

    - Un nombre descriptivo.

    - Un conjunto de campos dinámicos, definidos por el usuario, que pueden ser de distintos tipos:

            text, number, email

            textarea

            select con opciones personalizadas.

    - Indicadores de requerido para cada campo, lo que permite validar que se completen correctamente antes de enviarlos.

2. Campos dinámicos configurables

    Durante la creación o edición de un formulario, los usuarios pueden definir la estructura de los campos que formarán parte del mismo. Cada campo incluye:

        Etiqueta (label)

        Nombre (name)

        Tipo de campo (type)

        Opciones (si aplica, como en el caso de select)

        Validación requerida (required)

    Esta flexibilidad permite que cada formulario sea completamente adaptable a diferentes necesidades de captura de datos.

3. Visualización e interacción con formularios

    Desde la interfaz, el usuario puede:

    - Visualizar el detalle de cualquier plantilla de formulario.

    - Editar su configuración, añadiendo o eliminando campos según sea necesario.

    - Eliminar formularios que ya no se necesiten.

    - Llenar el formulario, es decir, enviar respuestas.

4. Gestión de respuestas a formularios

    Una vez creado un formulario, cualquier usuario puede cumplimentarlo a través de una vista que interpreta automáticamente su estructura, permitiendo:

    - Introducir datos en los campos definidos.

    - Enviar la información, que será almacenada en una colección separada de MongoDB correspondiente a las respuestas.


##  Arquitectura y tecnologías utilizadas

- Backend: Laravel 10 + Laravel MongoDB driver.

- Frontend: Vue 3 (con script setup) + Vite.

- Estilos: Bootstrap 5 + Bootstrap Icons.

- Base de datos: MongoDB para almacenar tanto plantillas como respuestas.

- API REST: Los formularios y respuestas se gestionan mediante rutas API (api.php).

- Sin middleware restrictivo: Las rutas están actualmente accesibles sin autenticación o autorización.

## 🛠 Instalación

### 1. Clonar repositorio

    git clone [url-repositorio]
    cd [dynamic-form]

### 2. Instalar dependencias

#### Backend
    composer install

#### Frontend
    npm install

### 3. Configurar entorno

    cp .env.example .env
    php artisan key:generate

- Configurar MongoDB en .env:

        DB_CONNECTION=mongodb
        DB_HOST=127.0.0.1
        DB_PORT=27017
        DB_DATABASE=forms

### 4. Ejecutar migraciones y seeder
    php artisan migrate
    php artisan db:seed --class=FormTemplateSeeder

### 5. Compilar assets
- Compilar assets

        npm run build

- Iniciar servidor

        php artisan serve
        npm run dev

## Estructura clave

    app/
    ├── Http/Controllers/
    │   ├── FormTemplateController.php
    │   └── FormResponseController.php
    database/
    ├── migrations/
    └── seeders/
        └── FormTemplateSeeder.php
    resources/js/
    ├── components/   
    └── router/

## Uso
Accede a la aplicación en tu navegador:
   
    http://localhost:8000
    

## Endpoints API

    Método	Endpoint	Descripción
    GET	/api/forms	Listar formularios
    POST	/api/forms	Crear formulario
    GET	/api/forms/{id}	Obtener formulario
    PUT	/api/forms/{id}	Actualizar formulario
    DELETE	/api/forms/{id}	Eliminar formulario
    POST	/api/responses	Enviar respuesta