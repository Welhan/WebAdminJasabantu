<div class="modal fade" id="modalNewMitra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Mitra</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    {{-- <div class="form-check">
                        <label class="form-check-label">
                            isUser?
                            <input type="checkbox" class="form-check-input" id="isExist" name="isExist">
                            <i class="input-helper"></i></label>
                    </div>
                    <div class="isExist" style="display: none">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control" style="width: 100%;" id="search_user" name="User">
                            </select>
                        </div>
                    </div>
                    <div class="isNotExist" style="display: none">
                        <div class="form-group">
                            <label>UniqueID</label>
                            <input type="text" class="form-control" value="Auto" readonly name="uniqueid">
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Name">
                        <div id="errName" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" class="form-control" id="Email" name="Email" placeholder="Email">
                        <div id="errEmail" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" id="Phone" name="Phone" placeholder="Phone">
                        <div id="errPhone" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" id="Address" name="Address" placeholder="Location">
                        <div id="errAddress" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>PIN</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="Pin" id="Pin" readonly>
                            <button class="btn btn-primary" type="button" id="generatePin">Generate</button>
                            <div id="errPIN" class="invalid-feedback"></div>
                        </div>
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
    $('#search_user').select2({
        dropdownParent: $('#modalNewMitra'),
        theme: "classic",
        ajax: {
            url: '/mitra-management/getUser',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var data = {
                    User: params.term,
                }
                return data;
            },
            processResults: function(data) {
                return {
                    results: [{
                        id: data[0].UniqueID,
                        text: data[0].Name
                    }]
                };
            }
        }
    });

    $('input[name="isExist"]').on("click", function() {
        let isExist = $(this).prop("checked");
        if(isExist){
            $('.isNotExist').hide()
            $('.isExist').show()
        }else{
            $('.isExist').hide()
            $('.isNotExist').show()
        }
    });

    $("#generatePin").on("click", function(e){
        e.preventDefault()
        $.ajax({
            url : "/mitra-management/auto_generate",
            type : "GET",
            dataType : "JSON",
            success: function(response){
                $('#Pin').val(response.number);
            },
            error : function(xhr, res, error){
                alert(error)
            }
        })
    })

    $('#formSubmit').submit(function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/mitra-management/store',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                    $('#modalNewMitra').modal('hide');
                    getDataMitra();
                }
            },
            error: function(response) {
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

                if (response.responseJSON.errors.Pin) {
                    $('#Pin').addClass('is-invalid');
                    $('#errPIN').html(response.responseJSON.errors.Pin);
                } else {
                    $('#Pin').removeClass('is-invalid');
                    $('#errPIN').html('')
                }
                
            }
        })
    })

    $(document).ready(function () {

    
        $('.isExist').hide()
        $('.isNotExist').show()
        $('#select2').select2({
            theme: 'skydash' // Atur tema Select2 sesuai dengan tema Skydash
        });



    })
</script>