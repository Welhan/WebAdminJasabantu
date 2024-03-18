<div class="modal fade" id="modalNewConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Config</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Config</label>
                        <input type="text" class="form-control" id="Config" name="Config" placeholder="Nama Config">
                        <div id="errConfig" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Value</label>
                        <input type="text" class="form-control" id="Value" name="Value" placeholder="Value">
                        <div id="errValue" class="invalid-feedback"></div>
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
            url: '/web-config/store',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalNewConfig').modal('hide');
                    getDataConfig();
                }
            },
            error: function(response) {
                console.log(response)
                $('#btnProcess').removeAttr('disabled');
                $('#btnProcess').html('Save');

                if (response.responseJSON.errors.Config) {
                    $('#Config').addClass('is-invalid');
                    $('#errConfig').html(response.responseJSON.errors.Config);
                } else {
                    $('#Config').removeClass('is-invalid');
                    $('#errConfig').html('')
                }

                if (response.responseJSON.errors.Value) {
                    $('#Value').addClass('is-invalid');
                    $('#errValue').html(response.responseJSON.errors.Value);
                } else {
                    $('#Value').removeClass('is-invalid');
                    $('#errValue').html('')
                }
                
            }
        })
    })

    $(document).ready(function () {
        

    })
</script>