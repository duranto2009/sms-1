@extends('admin.layout.admin')
@section('css')
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap3.min.css"> --}}
<link rel="stylesheet" href="{{asset('admin/css/datatables/datatables.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-book"></i> All Book List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addBook">
                            <i class="la la-plus"></i> Add Book
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Book Name</th>
                                            <th>Author</th>
                                            <th>Copies</th>
                                            <th>Available Copies</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-content">
                                        @foreach ($booklists as $i=>$book)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$book->name}}</td>
                                            <td>{{$book->author}}</td>
                                            <td>{{$book->copies}}</td>
                                            <td>{{$book->aval_copies}}</td>
                                            <td class="td-actions">
                                                <a href="javascript:void(0);" onclick="editModal('{{route('booklist.edit', $book->id)}}','Update Section')">
                                                    <i data-id='.$cls->id.' id="edit" class="la la-edit edit" title="Edit Class"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteModal('{{route('booklist.destroy', $book->id)}}','Delete Section')">
                                                    <i data-id='.$cls->id.' id="delete" class="la la-close delete" title="Delete Class"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
    <!-- End Row -->

    <!--Add Book Modal -->
    <div class="modal fade" id="addBook" tabindex="-1" role="dialog" aria-labelledby="addBookLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addBookForm' action="{{route('booklist.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addBookLabel">Add Book</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Book Name</label>
                                <input type="text" class="form-control" id="name" name="name" required="">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="author">Author</label>
                                <input type="text" class="form-control" id="author" name="author" required="">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="copies">Number Of Copy</label>
                                <input type="number" class="form-control" id="copies" name="copies" min="0" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-info">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- End Container -->
@endsection
@section('js')
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $('#example').DataTable();
    $('select').attr('name','example_length').addClass('form-control');
    $('input').attr('aria-controls','example').addClass('form-control');
    readData();
$("#addBookForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addBookForm").serialize();
    const url = $("#addBookForm").attr('action');
    const method = $("#addBookForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Book Create Successful!');
                $("#addBook .close").click();
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
    const url = '{{route("booklist.readData")}}';
    $.ajax({
        url:url,
        method:'get',
        success: res=>{
            if(res.status == 200){
                let book = '';
                $.each(res.books,function(i,v){
                    book +='<tr>';
                    book += '<td>'+(i+1)+'</td>'
                    book += '<td>'+v.name+'</td>'
                    book += '<td>'+v.author+'</td>'
                    book += '<td>'+v.copies+'</td>'
                    book += '<td>'+v.aval_copies+'</td>'
                    book +='<td class="td-actions">';
                    book+='<a href="javascript:void(0);" onclick="editModal('+"'"+document.URL+'/'+v.id+'/edit'+"','Update book'"+')"><i data-id='+ v.id +' id="edit" class="la la-edit edit" title="Edit book"></i></a>';
                    book+='<a href="javascript:void(0);" onclick="deleteModal('+"'"+document.URL+'/'+v.id+"','Delete book'"+')"><i data-id='+ v.id +' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
                    book +='</td>';
                    book +='</tr>';
                });
                $(".table-content").html(book);
            }else{
                toast('error',res.error);
            }
        }
    });
}
</script>
@endsection
