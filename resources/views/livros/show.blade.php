<!DOCTYPE html>
<html>
<head><title>Livro</title></head>
<body>
    <h1>{{ $livro->titulo }}</h1>
    <p>Autor ID: {{ $livro->autor_id }}</p>
    <p>ISBN: {{ $livro->isbn }}</p>
    <p>Data de Publicação: {{ $livro->data_publicacao }}</p>
</body>
</html>
