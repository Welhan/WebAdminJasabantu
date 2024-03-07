<div class="modal fade" id="modalDeleteMidtrans" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Midtrans</h5>
            </div>
            <form action="" id="formSubmit">
                <div class="modal-body">
                    @csrf

                    <input type="hidden" id="ID" name="ID" value="{{ $midtrans['ID'] }}">

                    <table class="table table-bordered">
                        <thead>
                            <th>Config</th>
                            <th>Value 1</th>
                            <th>Value 2</th>
                            <th>Type</th>
                        </thead>
                        <tbody>
                            <th>{{ $midtrans['Config'] }}</th>
                            <th>{{ $midtrans['Value_1'] }}</th>
                            <th>{{ $midtrans['Value_2'] }}</th>
                            <th>{{ $midtrans['Type'] }}</th>
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
            url: '/midtrans/delete',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalDeleteMidtrans').modal('hide');
                    getDataMidtrans();
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