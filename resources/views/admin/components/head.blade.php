<head>

    <meta charset="utf-8" />
    <title>{{ \Config::get('settings.company_name') }} - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" /> -->
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('/assets/images/favicon.ico')}}">
    @stack('pageplugincss')
    <!-- Bootstrap Css -->
    <link href="{{asset('/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Custom Css-->
    <link href="{{asset('assets/css/custom.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="{{ asset('/administrator/assets/libs/toastr/build/toastr.min.css') }}">
    <link href="{{ asset('/administrator/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/administrator/assets/css/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />
 
    <script> 
        var _token = '{{csrf_token()}}';
    </script>
</head>