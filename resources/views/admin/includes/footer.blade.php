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
{{-- <script src="{{asset('admin/vendors/js/datatables/datatables.min.js')}}"></script> --}}
<script src="{{asset('js/toast.js')}}"></script>
<!-- End Page Snippets -->
@include('sweetalert::alert')
@yield('js')
<script>
function editModal(url,header){
    $('#update').modal('show');
    $.ajax({
        url: url,
        method: 'get',
        success:res=>{
            res = $.parseJSON(res);
            $('#update-form').attr('action',res.route);
            $('#update #updateInput').html(res.section);
            $('#update .modal-title').html(header);
        }
    });
}
$('#update').on('submit',e=>{
    e.preventDefault();
    const data = $("#update-form").serialize();
    const url = $("#update-form").attr('action');
    const method = $("#update-form").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success:res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                toast('success',res.message);
                $("#update-form .close").click();
                readData();
            }else{
                toast('error',res.message);
            }
        }
    });

});
function deleteModal(url,header){
    $('#delete-modal').modal('show');
    $('#delete-form').attr('action',url)
}
// Delete Data Modal Script
$("#delete-form").on('submit',e=>{
    e.preventDefault();
    const data = $("#delete-form").serialize();
    const url = $("#delete-form").attr('action');
    const method = $("#delete-form").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success:res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                toast('success',res.message);
                $("#delete-form .close").click();
                readData();
            }else{
                toast('error',res.message);
            }
        }
    });
});
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
