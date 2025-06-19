<h2>Nuevo mensaje desde el formulario de contacto</h2>

<p><strong>Nombre:</strong> {{ $nombre }}</p>
<p><strong>Email:</strong> {{ $email }}</p>
<p><strong>Mensaje:</strong></p>
<p>{!! nl2br(e($contenido)) !!}</p>
