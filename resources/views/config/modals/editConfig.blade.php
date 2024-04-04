<div class="modal fade" id="modalEditConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Config</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="ID" name="ID" readonly value="{{ $config['ID'] }}">
                    <div class="form-group">
                        <label for="config">Config</label>
                        <input type="text" class="form-control" id="config" name="config" placeholder="Nama Config"
                            value="{{ $config['Config'] }}">
                        <div id="errConfig" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="value">Value</label>
                        <input type="text" class="form-control" id="value" name="value" placeholder="Value"
                            value="{{ $config['Value'] }}">
                        <div id="errValue" class="invalid-feedback"></div>
                    </div>

                    <div class="form-check">
                        <div class="col-sm-10">
                            <input class="form-check-input" type="checkbox" id="cbxEditActiveF" name="ActiveF"
                                <?=($config['ActiveF']==1) ? 'checked' : '' ; ?>>
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
            url: '/web-config/update',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalEditConfig').modal('hide');
                    getDataConfig();
                }
            },
            error: function(response) {
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