@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="{{asset('admin/css/bootstrap-select/bootstrap-select.min.css')}}">
@endsection
@section('content')
<style>
.form-mark {
width: 75px;
height: 40px;
background: #2c304d;
border: none;
float: left;
margin-right: 10px;
border-radius: .5rem;
color: #fff;
font-size: 17px;
font-weight: bold;
text-align: center;
}
.form-mark:focus {
border:1px solid #5d5386;
background:#fff;
color: #2c304d;
}
.toll-free-box i {
position: absolute;
left: 0;
bottom: 0px;
font-size: 6rem;
opacity: .3;
-webkit-transform: rotate(0deg);
transform: rotate(0deg);
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="ti-rocket text-info"></i>  Student Promotion</h1>
                </div>
                <div class="widget-body">
                    <form id="managePromotion" action="{{route('student.promotion')}}" method="get" autocomplete="off">
                        <div class="form-group row d-flex align-items-center mt-3 mb-5 justify-content-center">
                            <div class="col-lg-2">
                                <label for="session_from">Current Session</label>
                                <select id="session_from" name="session_from"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option disabled selected>Session From</option>
                                    @foreach ($sessions as $session)
                                    <option {{$session->status == 1 ? 'selected':''}} value="{{$session->title}}">{{$session->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="session_to">Next Session</label>
                                <select id="session_to" name="session_to"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option disabled selected>Session To</option>
                                    @foreach ($sessions as $session)
                                    <option value="{{$session->title}}">{{$session->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="class_id_from">Promoting From</label>
                                <select id="class_id_from" name="class_id_from"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option disabled selected>Promoting From</option>
                                    @foreach ($class as $cls)
                                    <option value="{{$cls->id}}" cls="{{$cls->name}}">{{$cls->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="class_id_to">Promoting To</label>
                                <select id="class_id_to" name="class_id_to"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option disabled selected>Promoting To</option>
                                    @foreach ($class as $cls)
                                    <option value="{{$cls->id}}" cls="{{$cls->name}}">{{$cls->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-outline-success" type="submit">Manage Promotion</button>
                            </div>
                        </div>
                    </form>
                    <div class="empty-table text-center">
                        <img src="{{asset('admin/img/empty_box.png')}} " alt="...." class="img-fluid" width="250px">
                        <br>
                        <p>No Data Found</p>
                    </div>
                    <div class="ptop mb-4"></div>
                    <table id="db-table" class=" table table-striped" style="display:none;width: 100% !important;">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="students[]" id="chkall">
                                </th>
                                <th>SL</th>
                                <th>Student Name</th>
                                <th>Image</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="promotion-content">

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
</div>
<!-- End Container -->
@endsection
@section('js')
<script src="{{asset('admin/vendors/js/bootstrap-select/bootstrap-select.js')}}"></script>
<script>
$('#session_from').on('change',()=>{
    $(".empty-table").show();
    $("#db-table").hide();
    $(".ptop").hide();
});
$('#session_to').on('change',()=>{
    $(".empty-table").show();
    $("#db-table").hide();
    $(".ptop").hide();
});
$('#class_id_to').on('change',()=>{
    $(".empty-table").show();
    $("#db-table").hide();$(".ptop").hide();
});
$('#class_id_from').on('change',()=>{
    $(".empty-table").show();
    $("#db-table").hide();$(".ptop").hide();
});
$("#managePromotion").on('submit',function(e)
{
    e.preventDefault();
    const data = $("#managePromotion").serialize();
    const url = $("#managePromotion").attr('action');
    const method = $("#managePromotion").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            if(res.status == 200){
                if(res.data.length > 0){
                    const currentClass = $("#class_id_from option:selected").attr('cls');
                    const nextClass = $("#class_id_to option:selected").attr('cls');
                    toast('success',res.message);
                    $(".empty-table").hide();
                    var html = '';
                    var phead = '<div class="row justify-content-md-center"><div class="col-md-4 mt-2"><div class="card text-white" style=": none;: #303450;"><div class="card-body"><div class="toll-free-box text-center"><h2> <i class="ti-ruler-alt-2"></i> Promote Student</h2><h5>Class From: '+currentClass+' To : '+nextClass+'</h5><h5>Session From: '+$("#session_from").val()+' To : '+$("#session_to").val()+'</h5></div></div></div></div></div>';
                    $.each(res.data,function(i,v){
                        html += '<tr>';
                        html += '<td><input type="checkbox" class="checkbox" data-id="'+v.id+'"></td>';
                        html += '<td>'+(i+1)+'</td>';
                        html += '<td>'+v.name+'<br/><b>Student ID: </b>'+v.student_id+'</td>';
                        html += '<td><img src="'+v.image+'" height="50" alt="..."></td>';
                        html += '<td><span>'+v.section+'</span></td>';
                        html += '<td><span class="status_'+v.id+'">Not Promoted Yet</span></td>';
                        html += '<td><button class="btn btn-outline-success" onclick="promotion('+v.id+')"><i class="ti-angle-double-up"></i> '+nextClass+'</button> <button class="btn btn-outline-danger" onclick="demotion('+v.id+')"><i class="ti-angle-double-down"></i> '+currentClass+'</button></td>';
                        html += '</tr>';
                    });
                    html +='<tr class="bulkPromotion" style="display: none;"><td colspan="7"><button class="btn btn-outline-success" onclick="bulkPromotion()"><i class="ti-angle-double-up"></i></button> <button class="btn btn-outline-danger" onclick="bulkDemotion()"><i class="ti-angle-double-down"></i> </button></td></tr>';
                    $(".promotion-content").html(html);
                    $(".ptop").html(phead);
                    $(".ptop").show();
                    $("#db-table").show();
                }else{
                    toast('warning','Data not found');
                }
            }else{
                toast('error',res.message);
            }
        },
        error: err=>{
            const errors = err.responseJSON;
            if($.isEmptyObject(errors) == false){
                $.each(errors.errors,function(key,value){
                    toast('error',value);
                });
            }
        }
    });
});
function promotion(student_id){
    let session = $("#session_to").val();
    let class_id = $("#class_id_to").val();
    $.ajax({
        url:'{{route("promotion.update")}}',
        method:'get',
        data:{
            session:session,
            class_id:class_id,
            student_id:student_id
        },
        success:res=>{
            if(res.status == 200){
                toast('success',res.message);
                $('.status_'+student_id).addClass('text-info').text('PROMOTED');
            }else{
                toast('error',res.message);
            }
        }
    });
}
function demotion(student_id){
    let session = $("#session_to").val();
    let class_id = $("#class_id_from").val()
    $.ajax({
        url:'{{route("promotion.update")}}',
        method:'get',
        data:{
            session:session,
            class_id:class_id,
            student_id:student_id
        },
        success:res=>{
            if(res.status == 200){
                toast('success',res.message);
                $('.status_'+student_id).addClass('text-info').text('PROMOTED');
            }else{
                toast('error',res.message);
            }
        }
    });
}
$("#chkall").click(function () {
    $('input:checkbox').not(this).prop('checked', this.checked);
    if($(this).is(':checked')){
        $(".bulkPromotion").show();
    }else{
        $(".bulkPromotion").hide();
    }
});
// $('.checkbox').on('click',function(){
//         alert('a')
//     if($(this).is(':checked')){
//         // $('#chkall').prop('checked',true);
//         $(".bulkPromotion").show();
//     }else{
//         // $('#chkall').prop('checked',false);
//         $(".bulkPromotion").hide();
//     }
// });
function bulkPromotion(){
    let session = $("#session_to").val();
    let class_id = $("#class_id_to").val();
    // let students = $('input:checkbox').val();
    // console.log(students);

    var idsArr = [];
    $(".checkbox:checked").each(function() {
        idsArr.push($(this).attr('data-id'));
    });
    if(idsArr.length <=0) {
        alert("Please select atleast one record to delete.");
    } else {
        if(confirm("Are you sure?")){
            $.ajax({
                url: "{{ route('bulk.promotion') }}" ,
                type: 'put' ,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')},
                data: {
                    student_id:idsArr,
                    session:session,
                    class_id:class_id
                },
                success: res=> {
                    if(res.status == 200){
                        toast('success',res.message);
                    }else{
                        toast('error',res.message);
                    }

                },
                error: err=> {
                    toast('error','Somting goes wrong!');
                }
            });
        }
    }
}
</script>
@endsection
