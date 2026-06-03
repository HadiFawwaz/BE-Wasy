<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wasy - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --wt-navy: #0A192F;
            --wt-aqua: #00E5FF;
            --wt-aqua-dark: #00B8CC;
            --wt-surface: #FFFFFF;
        }
    </style>
</head>
<body class="bg-[var(--wt-surface)]">
    @yield('content')
</body>
</html>
