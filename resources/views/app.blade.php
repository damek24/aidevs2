<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->

    @vite(['resources/js/app.ts', 'resources/scss/app.scss', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="d-flex flex-column h-100">
<div id="app" class="d-flex flex-column h-100" data-page="{{ json_encode($page) }}"></div>
</body>
</html>
