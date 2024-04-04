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
                        <label for="config">Config</label>
                        <input type="text" class="form-control" id="config" name="config" placeholder="Nama Config">
                        <div id="errConfig" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="value">Value</label>
                        <input type="text" class="form-control" id="value" name="value" placeholder="Value">
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
                $('#btnProcess').html('Submit');

                if (response.responseJSON.errors.config) {
                    $('#config').addClass('is-invalid');
                    $('#errConfig').html(response.responseJSON.errors.config);
                } else {
                    $('#config').removeClass('is-invalid');
                    $('#errConfig').html('')
                }

                if (response.responseJSON.errors.value) {
                    $('#value').addClass('is-invalid');
                    $('#errValue').html(response.responseJSON.errors.value);
                } else {
                    $('#value').removeClass('is-invalid');
                    $('#errValue').html('')
                }
                
            }
        })
    })

    $(document).ready(function () {
        

    })
</script>