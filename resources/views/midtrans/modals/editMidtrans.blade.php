<div class="modal fade" id="modalEditMidtrans" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Midtrans Config</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="ID" name="ID" readonly value="{{ $midtrans['ID'] }}">
                    <div class="form-group">
                        <label>Config</label>
                        <input type="text" class="form-control" id="Config" name="Config" placeholder="Nama Config"
                            value="{{ $midtrans['Config'] }}">
                        <div id="errConfig" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Value 1</label>
                        <input type="text" class="form-control" id="Value1" name="Value1" placeholder="Value 1 Config"
                            value="{{ $midtrans['Value_1'] }}">
                        <div id="errValue1" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Value 2</label>
                        <input type="text" class="form-control" id="Value2" name="Value2" placeholder="Value 2 Config"
                            value="{{ $midtrans['Value_2'] }}">
                        <div id="errValue2" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-select" aria-label="type" id="Type" name="Type">
                            <option value="" selected="">Pilih Pasaran</option>
                            <option value="%" {{ $midtrans['Type']=='%' ? 'selected' : '' }}>%</option>
                            <option value="Rp" {{ $midtrans['Type']=='Rp' ? 'selected' : '' }}>Rp</option>
                        </select>
                        <div id="errType" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" id="Desc" name="Desc" placeholder="Description"
                            value="{{ $midtrans['Desc'] }}">
                        <div id="errDesc" class="invalid-feedback"></div>
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
            url: '/midtrans/update',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalEditMidtrans').modal('hide');
                    getDataMidtrans();
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

                if (response.responseJSON.errors.Value1) {
                    $('#Value1').addClass('is-invalid');
                    $('#errValue1').html(response.responseJSON.errors.Value1);
                } else {
                    $('#Value1').removeClass('is-invalid');
                    $('#errValue1').html('')
                }

                if (response.responseJSON.errors.Value2) {
                    $('#Value2').addClass('is-invalid');
                    $('#errValue2').html(response.responseJSON.errors.Value2);
                } else {
                    $('#Value2').removeClass('is-invalid');
                    $('#errValue2').html('')
                }

                if (response.responseJSON.errors.Type) {
                    $('#Type').addClass('is-invalid');
                    $('#errType').html(response.responseJSON.errors.Type);
                } else {
                    $('#Type').removeClass('is-invalid');
                    $('#errType').html('')
                }

                if (response.responseJSON.errors.Desc) {
                    $('#Desc').addClass('is-invalid');
                    $('#errDesc').html(response.responseJSON.errors.Desc);
                } else {
                    $('#Desc').removeClass('is-invalid');
                    $('#errDesc').html('')
                }
                
            }
        })
    })

    $(document).ready(function () {
        

    })
</script>