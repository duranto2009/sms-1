<!--Delete Class Modal -->
<div class="modal modal-top fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="delete-form" action="" method="post" autocomplete="off">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-modal">DELETE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <input type="hidden" name="id" id="deleteId"> --}}
                    <h3>Are you sure want to delete this?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
