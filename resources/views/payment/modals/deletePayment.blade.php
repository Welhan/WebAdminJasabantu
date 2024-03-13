<div class="modal fade" id="modalDeletePayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Payment</h5>
            </div>
            <form action="" id="formSubmit">
                <div class="modal-body">
                    @csrf

                    <input type="hidden" id="ID" name="ID" value="{{ $payment['ID'] }}">

                    <table class="table table-bordered">
                        <thead>
                            <th>Payment Type</th>
                            <th>Value</th>
                        </thead>
                        <tbody>
                            <th>{{ $payment['Payment_type'] }}</th>
                            <th>{{ $payment['Value'] }}</th>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" id="btnProcess">Delete</button>
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
            url: '/payment-method/delete',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalDeletePayment').modal('hide');
                    getDataPayment();
                }
            },
            error: function(response) {
                $('#btnProcess').removeAttr('disabled');
                $('#btnProcess').html('Delete');
            }
        })
    })
    
    $(document).ready(function () {
    })
</script>