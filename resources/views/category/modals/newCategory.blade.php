<div class="modal fade" id="modalNewCategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
            </div>
            <form autocomplete="off" id="formSubmit" enctype="multipart/form-data" action="category/store"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" id="category" name="category"
                            placeholder="Nama Category">
                        <div id="errCategory" class="invalid-feedback"></div>
                    </div>
                    <div id="errType" class="invalid-feedback"></div>
                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <input type="file" class="form-control" id="icon" name="icon" placeholder="Icon"
                            onchange="preview()">
                        <div id="errIcon" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <img src="" id="frame" class="img-fluid" style="height: 80px; display: none">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnProcess">Submit</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<script>
    function preview() {
        frame.src = URL.createObjectURL(event.target.files[0]);
        $('#frame').show()
    }

    $(document).ready(function () {
        $('#formSubmit').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btnProcess').attr('disabled', 'disabled');
                    $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(response) {
                console.log(response)
                    if(response.success){
                        $('#modalNewCategory').modal('hide');
                        getDataCategory();
                    }
                },
                error: function(response) {
                    $('#btnProcess').removeAttr('disabled');
                    $('#btnProcess').html('Save');

                    if (response.responseJSON.errors.category) {
                        $('#category').addClass('is-invalid');
                        $('#errCategory').html(response.responseJSON.errors.category);
                    } else {
                        $('#category').removeClass('is-invalid');
                        $('#errCategory').html('')
                    }

                    if (response.responseJSON.errors.icon) {
                        $('#icon').addClass('is-invalid');
                        $('#errIcon').html(response.responseJSON.errors.icon);
                    } else {
                        $('#icon').removeClass('is-invalid');
                        $('#errIcon').html('')
                    }
                    
                }
            })
        })
    })

    
</script>