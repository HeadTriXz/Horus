<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Horus</title>

    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        @include('Layouts/navigation.php')

        <main>
            @content
        </main>
    </div>
</body>
</html>
