<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
        <h1>Bienvenido a Competenias para ganar</h1>

        <h2>
            Usuarios:
        </h2>
        <h3><ul>
            @foreach ($usuarios as $usuario)
                <li>{{$usuario->nombre}}</li>    
            @endforeach
        </ul></h3>
    </body>
</html>
