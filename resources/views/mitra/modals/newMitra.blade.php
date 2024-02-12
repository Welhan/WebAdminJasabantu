<div class="modal fade" id="modalNewMitra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">New Mitra</h5>
        </div>
        <form class="formSubmit">
            <div class="modal-body">
                <div class="form-check">
                    <label class="form-check-label">
                        isExist?
                      <input type="checkbox" class="form-check-input" id="isExist" name="isExist">
                    <i class="input-helper"></i></label>
                  </div>          
                <div class="isExist" style="display: none">
                    <div class="form-group">
                        <label>UniqueID</label>
                        <select class="form-control select2" id="select2">
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                        </select>                                         
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="Name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail3">Email address</label>
                        <input type="email" class="form-control" id="Email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword4">Phone</label>
                        <input type="text" class="form-control" id="Phone" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" id="Address" placeholder="Location">
                    </div>
                </div>                             
                <div class="isNotExist" style="display: none">
                    <div class="form-group">
                        <label>UniqueID</label>
                        <input type="text" class="form-control" value="Auto" readonly>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="Name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail3">Email address</label>
                        <input type="email" class="form-control" id="Email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword4">Phone</label>
                        <input type="text" class="form-control" id="Phone" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" id="Address" placeholder="Location">
                    </div>
                </div>                             
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>
<script>
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
    $(document).ready(function () {
        $('.isExist').hide()
        $('.isNotExist').show()
        $('#select2').select2({
            theme: 'skydash' // Atur tema Select2 sesuai dengan tema Skydash
        });
    })
</script>
