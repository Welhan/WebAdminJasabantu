<div class="modal fade" id="modalEditCategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
            </div>
            <form id="formSubmit" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" class="form-control" id="Category" name="Category"
                            placeholder="Nama Category" value="{{ $category['Category'] }}">
                        <div id="errCategory" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Icon</label>
                        <input type="text" class="form-control" id="Icon" name="Icon" placeholder="Icon"
                            value="{{ $category['Icon'] }}">
                        <div id="errIcon" class="invalid-feedback"></div>
                    </div>
                    <div class="form-check">
                        <div class="col-sm-10">
                            <input class="form-check-input" type="checkbox" id="cbxEditActiveF" name="ActiveF"
                                <?=($category['ActiveF']==1) ? 'checked' : '' ; ?>>
                            <label class="form-label" for="cbxEditActiveF">
                                Active
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnProcess">Submit</button>
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
            url: '/category/update',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalEditCategory').modal('hide');
                    getDataCategory();
                }
            },
            error: function(response) {
                $('#btnProcess').removeAttr('disabled');
                $('#btnProcess').html('Save');

                if (response.responseJSON.errors.Category) {
                    $('#Category').addClass('is-invalid');
                    $('#errCategory').html(response.responseJSON.errors.Category);
                } else {
                    $('#Category').removeClass('is-invalid');
                    $('#errCategory').html('')
                }

                if (response.responseJSON.errors.Icon) {
                    $('#Icon').addClass('is-invalid');
                    $('#errIcon').html(response.responseJSON.errors.Icon);
                } else {
                    $('#Icon').removeClass('is-invalid');
                    $('#errIcon').html('')
                }
                
            }
        })
    })

    $(document).ready(function () {
        

    })
</script>