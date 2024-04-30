<div class="modal fade" id="modalEditMerchant" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Merchant</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="Uniqueid" name="Uniqueid" readonly value="{{ $id }}">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="Name" name="Name" value="{{ $merchant['Name'] }}"
                            required>
                        <div id="errName" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="Email" id="Email" value="{{ $merchant['Email'] }}"
                            required>
                        <div id="errEmail" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="Phone" id="Phone" value="{{ $merchant['Phone'] }}"
                            required>
                        <div id="errPhone" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" id="Address" name="Address"
                            value="{{ $merchant['Address'] }}" required>
                        <div id="errAddress" class="invalid-feedback"></div>
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
    // $("#checkEmail").on("click", function(e){
    //     var email = $('#Email').val();
    //     var id = $('#Uniqueid').val();
    //     e.preventDefault()
    //     $.ajax({
    //         url : "/merchant-management/checkEmail",
    //         type : "GET",
    //         dataType : "JSON",
    //         data : {
    //             email,
    //             id
    //         },
    //         success: function(response){
    //             console.log(response)
    //             if (response.status === 'failed') {
    //                 $('#Email').addClass('is-invalid');
    //                 $('#errEmail').html(response.message);
    //             } else {
    //                 $('#Email').removeClass('is-invalid');
    //                 $('#errEmail').html(response.message);
    //             }
    //         },
    //         error : function(xhr, res, error){
    //             alert(error)
    //         }
    //     })
    // })

    // $("#checkPhone").on("click", function(e){
    //     var phone = $('#Phone').val();
    //     var id = String($('#Uniqueid').val());
    //     e.preventDefault()
    //     $.ajax({
    //         url : "/merchant-management/checkPhone",
    //         type : "GET",
    //         dataType : "JSON",
    //         data : {
    //             phone,
    //             id
    //         },
    //         success: function(response){
    //             console.log(response)
    //             if (response.status === 'failed') {
    //                 $('#Phone').addClass('is-invalid');
    //                 $('#errPhone').html(response.message);
    //             } else {
    //                 $('#Phone').removeClass('is-invalid');
    //                 $('#errPhone').html(response.message);
    //             }
    //         },
    //         error : function(xhr, res, error){
    //             alert(error)
    //         }
    //     })
    // })
    
    $('#formSubmit').submit(function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/merchant-management/update',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                console.log(response)
                if(response.success){
                    $('#modalEditMerchant').modal('hide');
                    getDataMerchant();
                }
            },
            error: function(response) {
                console.log(response)
                $('#btnProcess').removeAttr('disabled');
                $('#btnProcess').html('Save');

                if (response.responseJSON.errors.Name) {
                    $('#Name').addClass('is-invalid');
                    $('#errName').html(response.responseJSON.errors.Name);
                } else {
                    $('#Name').removeClass('is-invalid');
                    $('#errName').html('')
                }

                if (response.responseJSON.errors.Email) {
                    $('#Email').addClass('is-invalid');
                    $('#errEmail').html(response.responseJSON.errors.Email);
                } else {
                    $('#Email').removeClass('is-invalid');
                    $('#errEmail').html('')
                }

                if (response.responseJSON.errors.Phone) {
                    $('#Phone').addClass('is-invalid');
                    $('#errPhone').html(response.responseJSON.errors.Phone);
                } else {
                    $('#Phone').removeClass('is-invalid');
                    $('#errPhone').html('')
                }

                if (response.responseJSON.errors.Address) {
                    $('#Address').addClass('is-invalid');
                    $('#errAddress').html(response.responseJSON.errors.Address);
                } else {
                    $('#Address').removeClass('is-invalid');
                    $('#errAddress').html('')
                }

            }
        })
    })

    $(document).ready(function () {
    })
</script>