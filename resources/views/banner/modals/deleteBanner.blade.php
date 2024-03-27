<div class="modal fade" id="modalDeleteBanner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Banner</h5>
            </div>
            <form id="formSubmit">
                <div class="modal-body">
                    @csrf

                    <input type="hidden" id="id" name="id" value="{{ $banner['ID'] }}">

                    <table class="table table-bordered">
                        <thead>
                            <th>Name</th>
                            <th>Image</th>
                        </thead>
                        <tbody>
                            <th>{{ $banner['Name'] }}</th>
                            <th><img src="{{ asset('storage/' . $banner['Image']) }}" style="width: 80px"></th>
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
            url: '/banner/delete',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalDeleteBanner').modal('hide');
                    getDataBanner();
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