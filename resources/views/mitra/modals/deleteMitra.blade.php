<div class="modal fade" id="modalDeleteMitra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Mitra</h5>
            </div>
            <form action="" id="formSubmit">
                <div class="modal-body">
                    @csrf

                    <input type="hidden" class="form-control" id="Uniqueid" name="Uniqueid" readonly value="{{ $id }}">

                    <table class="table table-bordered">
                        <thead>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </thead>
                        <tbody>
                            <th>{{ $mitra['Name'] }}</th>
                            <th>{{ $mitra['Email'] }}</th>
                            <th>{{ $mitra['Phone'] }}</th>
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
            url: '/mitra-management/delete',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalDeleteMitra').modal('hide');
                    getDataMitra();
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