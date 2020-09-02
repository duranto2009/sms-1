<!-- Begin Vendor Js -->
<script src="{{asset('admin/vendors/js/base/jquery.min.js')}}"></script>
<script src="{{asset('admin/vendors/js/base/core.min.js')}}"></script>
<!-- End Vendor Js -->
<!-- Begin Page Vendor Js -->
<script src="{{asset('admin/vendors/js/nicescroll/nicescroll.min.js')}}"></script>
<script src="{{asset('admin/vendors/js/chart/chart.min.js')}}"></script>
<script src="{{asset('admin/vendors/js/progress/circle-progress.min.js')}}"></script>
<script src="{{asset('admin/vendors/js/calendar/moment.min.js')}}"></script>
<script src="{{asset('admin/vendors/js/calendar/fullcalendar.min.js')}}"></script>
<script src="{{asset('admin/vendors/js/owl-carousel/owl.carousel.min.js')}}"></script>
<script src="{{asset('admin/vendors/js/app/app.js')}}"></script>
<!-- End Page Vendor Js -->
<!-- Begin Page Snippets -->
<script src="{{asset('admin/js/dashboard/db-default-dark.js')}}"></script>
<script src="{{asset('admin/vendors/js/datatables/datatables.min.js')}}"></script>
<script src="{{asset('js/toast.js')}}"></script>
<!-- End Page Snippets -->
@include('sweetalert::alert')
@yield('js')
<script>
function toast(status,header,msg) {
    Command: toastr[status](header, msg)
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "500",
        "hideDuration": "1000",
        "timeOut": "2000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
}
</script>
</body>

</html>
