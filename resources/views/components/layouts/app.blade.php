{{-- resources/views/components/layouts/app.blade.php --}}


<!DOCTYPE html>
<html lang="id">


<head>
    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>
        {{ $title ?? 'Toko App' }}
        —
        {{ config('app.name') }}
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    {{ $styles ?? '' }}
</head>


<body class="bg-light">


    <x-navbar />


    <main class="container-fluid px-4 py-4">


        {{-- Flash Messages --}}
        @if (session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif


        @if (session('error'))
            <x-alert type="danger">
                {{ session('error') }}
            </x-alert>
        @endif


        {{ $slot }}


    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    {{ $scripts ?? '' }}


</body>


</html>
