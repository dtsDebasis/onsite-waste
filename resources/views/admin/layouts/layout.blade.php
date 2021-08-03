<!DOCTYPE html>
<html lang="en">
@include('admin.components.head')


<body data-sidebar="dark">
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
    @include('admin.components.header')

        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.components.sidemenu')
        <!-- Left Sidebar End -->
        @include('admin.components.flash-message')


        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    @include('admin.components.breadcrumb')
                    <!-- end page title -->

                    <!-- start flash message -->
                    @include('admin.components.messages')
                    <!-- end flash message -->

                    @yield('content')


                    <!-- end row -->
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- Modal -->

            <!-- end modal -->
            <div id="cover-spin"></div>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © OnSite Waste Technologies
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-right d-none d-sm-block">
                               
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!-- JAVASCRIPT -->
    @include('admin.components.scripts')
</body>

</html>