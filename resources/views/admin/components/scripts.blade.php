<script src="{{asset('/assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>

<!-- Sweet Alerts js -->
<script src="{{ asset('/administrator/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- apexcharts -->
<!-- <script src="{{asset('/assets/libs/apexcharts/apexcharts.min.js')}}"></script> -->

<!-- apexcharts init -->
<!-- <script src="{{asset('/assets/js/pages/apexcharts.init.js')}}"></script> -->

<!-- Tinymce js -->
<!-- <script src="{{ asset('/administrator/assets/libs/tinymce/jquery.tinymce.min.js') }}"></script> -->
<!-- App js -->
<script src="{{ asset('/administrator/assets/libs/bootbox/bootbox.all.min.js') }}"></script>
<script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
<script src="{{asset('/assets/js/app.js')}}"></script>
<script src="{{asset('/assets/js/custom.js')}}"></script>
<script src="{{ asset('/administrator/admin-form-plugins/app.js')}}"></script>
<script src="{{ asset('/administrator/admin-form-plugins/form-controls.js')}}"></script>
<script src="{{ asset('/administrator/assets/js/pages/bootstrap-tagsinput.min.js')}}"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
@stack('pagejs')
