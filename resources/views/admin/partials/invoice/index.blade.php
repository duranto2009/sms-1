@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-bank"></i> All Invoice List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-warning" data-toggle="modal" data-target="#massInv">
                            <i class="la la-plus"></i> Add Mass Invoice
                        </button>
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#singleInv">
                            <i class="la la-plus"></i> Add Single Invoice
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <form id="getInv" action="{{route('invoice.filter')}}" method="get" autocomplete="off">
                        <div class="form-group row d-flex align-items-center mt-3 mb-5 justify-content-center">
                            <div class="col-lg-4">
                                <div id="reportrange" style="cursor: pointer;" class="form-control">
                                    <i class="la la-calendar"></i>&nbsp;<span id="selectedValue"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <select name="class_id" id="class_id" class=" form-control">
                                    <option value="0" disabled selected>SELECT A CLASS</option>
                                    @foreach ($classes as $class)
                                    <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-outline-success" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="invoice-table text-center"></div>
                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
    <!-- End Row -->
    <!--Add Single Invoice Modal -->
    <div class="modal fade" id="singleInv" tabindex="-1" role="dialog" aria-labelledby="singleInvLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='singleInvForm' action="{{route('invoice.store')}}" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Add Single Invoice</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="class_id_on_create">Class</label>
                                <select name="class_id" id="class_id_on_create" class="form-control"
                                    onchange="classWiseStudent(this.value)">
                                    <option value="" selected disabled>Select A Class</option>
                                    @foreach ($classes as $class)
                                    <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="student_id_on_create">Select Student</label>
                                <div id="student_content">
                                    <select name="student_id" id="student_id_on_create" class="form-control">
                                        <option value="" selected disabled>Select A Student</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="title">Invoice Title</label>
                                <input type="text" class="form-control invTitle" id="title" name="title" >
                                <div class="invSuggest">
                                    <div class="invSuggest-inner">
                                        <div id="da">
                                            <ul>
                                                <li data-da='Registration Fee'>Registration Fee</li>
                                                <li data-da='Session Fee'>Session Fee</li>
                                                <li data-da='Exam Fee'>Exam Fee</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="amount">Total Amount (USD)</label>
                                <input type="number" class="form-control" id="amount" name="amount">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="paid_amount">Paid Amount (USD)</label>
                                <input type="number" class="form-control" id="paidAmount" name="paidAmount">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" >
                                    <option value="" selected disabled>Select A Status</option>
                                    <option value="1">Paid</option>
                                    <option value="0">Unpaid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-success">Create Invoice</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Add Mass Invoice Modal -->
    <div class="modal fade" id="massInv" tabindex="-1" role="dialog" aria-labelledby="massInvLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='massInvForm' action="{{route('invoice.store')}}" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Add Expense</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-info">Generate Mass Invoice</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Container -->
@endsection
@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
readData();
// Static Inv Suggestion
$(".invSuggest").hide();
$(".invTitle").on('click',(e)=>{
    $(".invSuggest").toggle();
});
$(".invSuggest").on('click',e=>{
    const result = $(e.target).attr('data-da');
    $(".invTitle").val(result);
    $(".invSuggest").hide();
});
// End Suggestion

// Date Picker Start
var start = moment().subtract(29, 'days');
var end = moment();
function cb(start, end) {
    $('#reportrange span').html(start.format('MMMM D YYYY') + ' - ' + end.format('MMMM D YYYY'));
}
$('#reportrange').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb);
cb(start, end);
// Date Picker End
function classWiseStudent(class_id){
    $.ajax({
        url:'{{route('invoice.getStudent')}}',
        method:'get',
        data:{id:class_id},
        success:res=>{
            let studentList = '<select name="student_id" id="student_id_on_create" class="form-control">';
            studentList += '<option value="" selected disabled>Select A Student</option>';
            $.each(res.students,function(i,val){
                studentList += '<option value="'+val.id+'">'+val.name+'</option>';
            });
            studentList += '</select>';
            $('#student_content').html(studentList);
        }
    });
}
$("#getInv").on('submit',(e)=>{
    e.preventDefault();
    const date = $('#selectedValue').text();
    const class_id = $('#class_id').val();
    const url = $("#getInv").attr('action');
    const method = $("#getInv").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:{date:date,id:class_id},
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                toast('success','Successful!');
                invTable(res.invoices);
            }else{
                toast('error',res.error);
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

// Create Single Expense
$("#singleInvForm").on('submit',(e)=>{
    e.preventDefault();
    if($("#status").val() =='1' & $("#amount").val() != $("#paidAmount").val()){
        toast('error','Please Check Status');
        return false;
    }
    const data = $("#singleInvForm").serialize();
    const url = $("#singleInvForm").attr('action');
    const method = $("#singleInvForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Envoice Create Successful!');
                $("#singleInv .close").click();
                readData();
            }else{
                toast('error',res.error);
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
function readData(){
    $.ajax({
        url:'{{route("invoice.getInv")}}',
        method:'get',
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                invTable(res.invoices);
            }else{
                toast('error',res.error);
            }
        }
    });
}
function invTable(invoices){
    let invoice = '';
    invoice +='<div class="table-responsive">';
    invoice +='<table id="dbTable" class="table mb-0 table-hover">';
    invoice +='<thead>';
    invoice +='<tr>';
    invoice +='<th>Invoice No</th>';
    invoice +='<th>Student</th>';
    invoice +='<th>Invoice Title</th>';
    invoice +='<th>Total Amount</th>';
    invoice +='<th>Paid Amount</th>';
    invoice +='<th>Status</th>';
    invoice +='<th>Action</th>';
    invoice +='</tr>';
    invoice +='</thead>';
    invoice +='<tbody>';
    $.each(invoices,function(i,v){
        invoice +='<tr>';
        invoice +='<td>'+ (v.id).toString().padStart(8, '0') +'</td>';
        invoice +='<td>'+ v.student.name +'<br><span style="font-size:11px;">Class: '+v.class.name  +'</span></td>';
        invoice +='<td>'+ v.title +'</td>';
        invoice +='<td>'+ v.amount.toFixed(2) +'<br><span style="font-size:11px;">Created At: '+moment(v.created_at).format('ddd, MMM D, YYYY') +'</span></td>';
        invoice +='<td>'+ v.paidAmount.toFixed(2) +'<br><span style="font-size:11px;">Payment Date: '+ moment(v.created_at).format('ddd, MMM D, YYYY') +'</span></td>';
        invoice +='<td>'+(v.status==0?"<span class='alert alert-danger'>Unpaid</span> ":"<span class='alert alert-success'>Paid</span>")+'</td>';
        invoice +='<td class="td-actions">';
        invoice+='<a href="javascript:void(0);" onclick="editModal('+"'"+document.URL+'/'+v.id+'/edit'+"','Update Invoice'"+')"><i data-id='+ v.id +' id="edit" class="la la-edit edit" title="Edit Invoice"></i></a>';
        invoice+='<a href="javascript:void(0);" onclick="deleteModal('+"'"+document.URL+'/'+v.id+"','Delete Invoice'"+')"><i data-id='+ v.id +' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
        invoice +='</td>';
        invoice +='</tr>';
    });
    invoice +='</tbody>';
    invoice +='</table>';
    invoice +='</div>';

    $(".invoice-table").html(invoice);
}
</script>
@endsection
