<form method="post" {{ $attributes->merge(['action' => '#', 'class' => 'form-horizontal']) }} enctype="multipart/form-data">
    @csrf

    {{ $slot }}
</form>
