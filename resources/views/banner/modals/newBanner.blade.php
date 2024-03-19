<div class="modal fade" id="modalNewBanner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Banner</h5>
            </div>
            <form autocomplete="off" id="formSubmit" enctype="multipart/form-data" action="banner/store" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        <div id="errName" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" class="form-control" id="image" name="image" placeholder="Image"
                            onchange="preview()">
                        <div id="errImage" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <img src="" id="frame" class="img-fluid" style="height: 80px; display: none">
                    </div>

                    <div class="form-check">
                        <div class="col-sm-10">
                            <input class="form-check-input" type="checkbox" id="cbxEditShowF" name="showF">
                            <label class="form-label" for="cbxEditShowF">
                                Show
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
                        $('#modalNewBanner').modal('hide');
                        getDataBanner();
                    }
                },
                error: function(response) {
                    $('#btnProcess').removeAttr('disabled');
                    $('#btnProcess').html('Save');

                    if (response.responseJSON.errors.name) {
                        $('#name').addClass('is-invalid');
                        $('#errName').html(response.responseJSON.errors.name);
                    } else {
                        $('#name').removeClass('is-invalid');
                        $('#errName').html('')
                    }

                    if (response.responseJSON.errors.image) {
                        $('#image').addClass('is-invalid');
                        $('#errImage').html(response.responseJSON.errors.image);
                    } else {
                        $('#image').removeClass('is-invalid');
                        $('#errImage').html('')
                    }
                    
                }
            })
        })
    })

    
</script>