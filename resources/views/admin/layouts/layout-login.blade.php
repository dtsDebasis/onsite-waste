<!doctype html>
<html lang="en">
@include('admin.components.head')
<body class="auth-body-bg">

    <div>
        @yield('content')
    </div>
    <!-- JAVASCRIPT -->
    @include('admin.components.scripts')

    <!-- owl.carousel js -->
    <script src="{{asset('/assets/libs/owl.carousel/owl.carousel.min.js')}}"></script>
    <!-- auth-2-carousel init -->
    <script src="{{asset('/assets/js/pages/auth-2-carousel.init.js')}}"></script>
    <!-- App js -->
    <!-- <script src="assets/js/app.js"></script> -->
</body>

</html>