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
                    <h1> <i class="la la-user-secret"></i> All Student List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#expense">
                            <i class="la la-plus"></i> Add Expense
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <form id="getExpense" action="{{route('expense.filter')}}" method="get" autocomplete="off">
                        <div class="form-group row d-flex align-items-center mt-3 mb-5 justify-content-center">
                            <div class="col-lg-4">
                                <div id="reportrange" style="cursor: pointer;" class="form-control">
                                    <i class="la la-calendar"></i>&nbsp;<span id="selectedValue"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <select name="expense_category" id="expense_category" class=" form-control">
                                    <option value="0" disabled selected>SELECT A CATEGORY</option>
                                    @foreach ($expenseCats as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-outline-success" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="student-table text-center">
                        <img src="{{asset('admin/img/empty_box.png')}} " alt="...." class="img-fluid" width="250px">
                        <br>
                        <p>No Data Found</p>
                    </div>
                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
    <!-- End Row -->
    <!--Add Expense Modal -->
    <div class="modal fade" id="expense" tabindex="-1" role="dialog" aria-labelledby="expenseLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='expenseForm' action="{{route('expense.store')}}" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="expenseLabel">Add Expense</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-group row">
                            <label for="date" class="col-md-3 col-form-label text-md-right">Date</label>
                            <div class="col-md-8">
                                <input id="date" type="date" class="form-control" name="date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amt" class="col-md-3 col-form-label text-md-right">Amount</label>
                            <div class="col-md-8">
                                <input id="amt" type="number" class="form-control" name="amount">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cat" class="col-md-3 col-form-label text-md-right">Amount</label>
                            <div class="col-md-8">
                                <select name="expense_categorie_id" class=" form-control">
                                    <option value="0" disabled selected>SELECT A CATEGORY</option>
                                    @foreach ($expenseCats as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-success">Save</button>
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
// Date Picker Start
var start = moment().subtract(29, 'days');
var end = moment();
function cb(start, end) {
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
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

$("#getExpense").on('submit',(e)=>{
    e.preventDefault();
    const date = $('#selectedValue').text();
    const exp_id = $('#expense_category').val();
    const url = $("#getExpense").attr('action');
    const method = $("#getExpense").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:{date:date,id:exp_id},
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                toast('success','Successful!');
                $(".student-table").html(res.student);
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

$("#expenseForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#expenseForm").serialize();
    const url = $("#expenseForm").attr('action');
    const method = $("#expenseForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Expense Create Successful!');
                $("#expense .close").click();
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
    const data = $("#getExpense").serialize();
    const url = $("#getExpense").attr('action');
    const method = $("#getExpense").attr('method');
    $.ajax({
    url:url,
    method:method,
    data:data,
    success: res=>{
    res = $.parseJSON(res);
    if(res.status == 200){
    $(".student-table").html(res.student);
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
}
</script>
@endsection
