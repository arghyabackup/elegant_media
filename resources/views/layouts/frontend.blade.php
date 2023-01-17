<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Arghya Assignment</title>
    <link href="{{ asset('/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/css/dataTables.bootstrap4.min.css') }}" />
     <link rel="stylesheet" href="{{ asset('/assets/css/magnific-popup.css') }}" />
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/css/notie.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/css/sweetalert2.min.css') }}" />
    <link href="{{ asset('/assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

@yield('content')

<script src="{{ asset('/assets/js/jquery-3.2.1.min.js') }}"></script> 
<script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/assets/js/popper.min.js') }}"></script> 
<script src="{{ asset('/assets/js/notie.min.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/assets/js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/assets/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/assets/js/main.js') }}"></script>
@yield('scripts')
</body>
</html>    