<div class="modal fade" id="modalDeleteSubCategory" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Sub Category</h5>
            </div>
            <form action="" id="formSubmit">
                <div class="modal-body">
                    @csrf

                    <input type="hidden" id="ID" name="ID" value="{{ $subcategory->ID }}">

                    <table class="table table-bordered">
                        <thead>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Icon</th>
                        </thead>
                        <tbody>
                            <th>{{ $subcategory->Category }}</th>
                            <th>{{ $subcategory->Sub_Category }}</th>
                            <th><img src="{{ asset('storage/' . $subcategory->Icon) }}" style="width: 80px"></th>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" id="btnProcess">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#formSubmit').submit(function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/sub-category/delete',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalDeleteSubCategory').modal('hide');
                    getDataSubCategory();
                }
            },
            error: function(response) {
                $('#btnProcess').removeAttr('disabled');
                $('#btnProcess').html('Delete');
            }
        })
    })
    
    $(document).ready(function () {
    })
</script>