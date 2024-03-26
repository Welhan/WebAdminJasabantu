<div class="modal fade" id="modalEditSubCategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
            </div>
            <form autocomplete="off" id="formSubmit" enctype="multipart/form-data" action="sub-category/update"
                method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" readonly value="{{ $subcategory['ID'] }}">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" aria-label="category" id="category" name="category">
                            <option value="">Select Category</option>
                            @foreach($category as $c)
                            <option value="{{ $c['ID'] }}" {{($c['ID']==$subcategory['ID_Category'] ? 'selected' : '' );
                                }}>
                                {{ $c['Category'] }}
                            </option>
                            @endforeach
                        </select>
                        <div id="errCategory" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="sub_category">Sub Category</label>
                        <input type="text" class="form-control" id="sub_category" name="sub_category"
                            placeholder="Nama Category" value="{{ $subcategory['Sub_Category'] }}">
                        <div id="errSubCategory" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <input type="file" class="form-control" id="icon" name="icon" placeholder="Icon"
                            onchange="preview()">
                        <div id="errIcon" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        @if ($subcategory['Icon'] <> '')
                            <img src="{{ asset('storage/'.$subcategory['Icon']) }}" style="width: 80px" id="frame"
                                class="img-fluid">
                            @else
                            <img src="" style="width: 80px" id="frame" class="img-fluid" style="display: none">
                            @endif
                    </div>
                    <div class="form-check">
                        <div class="col-sm-10">
                            <input class="form-check-input" type="checkbox" id="cbxEditActiveF" name="activeF"
                                <?=($subcategory['ActiveF']==1) ? 'checked' : '' ; ?>>
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
    function preview() {
        frame.src = URL.createObjectURL(event.target.files[0]);
        $('#frame').show()
    }

    $(document).ready(function () {
        $('#formSubmit').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btnProcess').attr('disabled', 'disabled');
                    $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(response) {
                console.log(response)
                    if(response.success){
                        $('#modalEditSubCategory').modal('hide');
                        getDataSubCategory();
                    }
                },
                error: function(response) {
                    $('#btnProcess').removeAttr('disabled');
                    $('#btnProcess').html('Submit');

                    if (response.responseJSON.errors.category) {
                        $('#category').addClass('is-invalid');
                        $('#errCategory').html(response.responseJSON.errors.category);
                    } else {
                        $('#category').removeClass('is-invalid');
                        $('#errCategory').html('')
                    }

                    if (response.responseJSON.errors.sub_category) {
                        $('#sub_category').addClass('is-invalid');
                        $('#errSubCategory').html(response.responseJSON.errors.sub_category);
                    } else {
                        $('#sub_category').removeClass('is-invalid');
                        $('#errSubCategory').html('')
                    }

                    if (response.responseJSON.errors.icon) {
                        $('#icon').addClass('is-invalid');
                        $('#errIcon').html(response.responseJSON.errors.icon);
                    } else {
                        $('#icon').removeClass('is-invalid');
                        $('#errIcon').html('')
                    }
                    
                }
            })
        })

    })
</script>