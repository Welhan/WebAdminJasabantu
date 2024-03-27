<div class="modal fade" id="modalEditPayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Payment</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="ID" name="ID" readonly value="{{ $payment['ID'] }}">
                    <div class="form-group">
                        <label>Payment Type</label>
                        <select class="form-control" aria-label="payment_type" id="Type" name="Type">
                            <option value="">Select Payment Type</option>
                            @foreach($payment_type as $pt)
                            <option value="{{ $pt['Value'] }}" {{($pt['Value']==$payment['Payment_type'] ? 'selected'
                                : '' ); }}>
                                {{ $pt['Value'] }}
                            </option>
                            @endforeach
                        </select>
                        <div id="errType" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Value</label>
                        <input type="text" class="form-control" id="Value" name="Value" placeholder="Value"
                            value="{{ $payment['Value'] }}">
                        <div id="errValue" class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label>Expired</label>
                        <input type="text" class="form-control" id="Expired" name="Expired" placeholder="Expired Time"
                            value="{{ $payment['Expired'] }}">
                        <div id="errExpired" class="invalid-feedback"></div>
                    </div>

                    <div class="form-check">
                        <div class="col-sm-10">
                            <input class="form-check-input" type="checkbox" id="cbxEditActiveF" name="ActiveF"
                                <?=($payment['ActiveF']==1) ? 'checked' : '' ; ?>>
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
            url: '/payment-method/update',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalEditPayment').modal('hide');
                    getDataPayment();
                }
            },
            error: function(response) {
                $('#btnProcess').removeAttr('disabled');
                $('#btnProcess').html('Save');

                if (response.responseJSON.errors.Type) {
                    $('#Type').addClass('is-invalid');
                    $('#errType').html(response.responseJSON.errors.Type);
                } else {
                    $('#Type').removeClass('is-invalid');
                    $('#errType').html('')
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