<div class="modal fade" id="modalEditMitra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Mitra</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>UniqueID</label>
                        <input type="text" class="form-control" id="Uniqueid" name="Uniqueid" readonly
                            value="{{ $mitra['UniqueID'] }}">
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="Name" name="Name" value="{{ $mitra['Name'] }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail3">Email address</label>
                        <input type="email" class="form-control" id="Email" name="Email" value="{{ $mitra['Email'] }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword4">Phone</label>
                        <input type="text" class="form-control" id="Phone" name="Phone" value="{{ $mitra['Phone'] }}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" id="Address" name="Address"
                            value="{{ $mitra['Address'] }}">
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
            url: '/mitra-management/update',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalEditMitra').modal('hide');
                    getDataMitra();
                }
            },
            error: function(response) {
                $('#btnProcess').removeAttr('disabled');
                $('#btnProcess').html('Save');

                if (response.responseJSON.errors.Name) {
                    $('#Name').addClass('is-invalid');
                    $('#errName').html(response.responseJSON.errors.Name);
                } else {
                    $('#Name').removeClass('is-invalid');
                    $('#errName').html('')
                }

                if (response.responseJSON.errors.Email) {
                    $('#Email').addClass('is-invalid');
                    $('#errEmail').html(response.responseJSON.errors.Email);
                } else {
                    $('#Email').removeClass('is-invalid');
                    $('#errEmail').html('')
                }

                if (response.responseJSON.errors.Phone) {
                    $('#Phone').addClass('is-invalid');
                    $('#errPhone').html(response.responseJSON.errors.Phone);
                } else {
                    $('#Phone').removeClass('is-invalid');
                    $('#errPhone').html('')
                }

                if (response.responseJSON.errors.Address) {
                    $('#Address').addClass('is-invalid');
                    $('#errAddress').html(response.responseJSON.errors.Address);
                } else {
                    $('#Address').removeClass('is-invalid');
                    $('#errAddress').html('')
                }

            }
        })
    })

    $(document).ready(function () {
    })
</script>