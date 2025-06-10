<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Formularios' }}</title>
    @vite(['resources/js/app.js', "resources/js/{$component}.js"])
</head>
<body>
    <div id="app"></div>
</body>
</html>