<div class="modal fade" id="modalEditCategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
            </div>
            <form autocomplete="off" id="formSubmit" enctype="multipart/form-data" action="category/update"
                method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" readonly value="{{ $category['ID'] }}">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" class="form-control" id="category" name="category"
                            placeholder="Nama Category" value="{{ $category['Category'] }}">
                        <div id="errCategory" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Icon</label>
                        <input type="file" class="form-control" id="icon" name="icon" placeholder="Icon"
                            onchange="preview()">
                        <div id="errIcon" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$category['Icon']) }}" style="width: 80px" id="frame"
                            class="img-fluid">
                    </div>
                    <div class="form-check">
                        <div class="col-sm-10">
                            <input class="form-check-input" type="checkbox" id="cbxEditActiveF" name="activeF"
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
                        $('#modalEditCategory').modal('hide');
                        getDataCategory();
                    }
                },
                error: function(response) {
                    $('#btnProcess').removeAttr('disabled');
                    $('#btnProcess').html('Submit');

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