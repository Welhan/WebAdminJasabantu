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
                        <label for="Config">Config</label>
                        <input type="text" class="form-control" id="Config" name="Config" placeholder="Nama Config"
                            value="{{ $config['Config'] }}">
                        <div id="errConfig" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="Value">Value</label>
                        <input type="text" class="form-control" id="Value" name="Value" placeholder="Value"
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