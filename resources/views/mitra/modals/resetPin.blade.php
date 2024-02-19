<div class="modal fade" id="resetPinMitra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reset PIN Mitra</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" readonly value="{{ $id }}">
                        <div id="errName" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" readonly>
                        <div id="errPhone" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>PIN</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="Pin" id="Pin" readonly>
                            <button class="btn btn-primary" type="button" id="generatePin">Generate</button>
                            <div id="errPIN" class="invalid-feedback"></div>
                        </div>
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
    $("#generatePin").on("click", function(e){
        e.preventDefault()
        $.ajax({
            url : "/mitra-management/auto_generate",
            type : "GET",
            dataType : "JSON",
            success: function(response){
                $('#Pin').val(response.number);
            },
            error : function(xhr, res, error){
                alert(error)
            }
        })
    })

    $('#formSubmit').submit(function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/mitra-management/resetPIN',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#resetPinMitra').modal('hide');
                    getDataMitra();
                }
            },
            error: function(response) {
                $('#btnProcess').removeAttr('disabled');
                $('#btnProcess').html('Save');

                if (response.responseJSON.errors.Pin) {
                    $('#Pin').addClass('is-invalid');
                    $('#errPIN').html(response.responseJSON.errors.Pin);
                } else {
                    $('#Pin').removeClass('is-invalid');
                    $('#errPIN').html('')
                }
                
            }
        })
    })

    $(document).ready(function () {

    })
</script>