@extends('layout.main')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="text-primary">Customer</h2>
            </div>
            <div class="card-body">
                <div class="formFilter mb-3">
                    <form>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">UniqueID</label>
                                    <input type="text" class="form-control" id="uniqueid">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div id="loader" style="display: none">
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-rounded" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    </div>
                </div>
                <div id="tableData"></div>
            </div>
        </div>
    </div>
</div>
<div id="viewModal" style="display: none"></div>
@endsection
@section('script')
<script>
    function getDataCustomer() {
        $.ajax({
            url: '/customer-management/getData',
            data: {},
            beforeSend: function() {
                $('#tableData').hide();
                $('#loader').show();
            },
            success: function(response) {
                $('#tableData').show();
                $('#loader').hide();
                $('#tableData').html(response);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

        
    // function editCustomer() {
    //     $.ajax({
    //         url: '/customer-management/editCustomer',
    //         success: function(response) {
    //             if(response.view){
    //                 $("#viewModal").show()
    //                 $("#viewModal").html(response.view)
    //                 $("#modalEditCustomer").modal('show')
    //             }
    //         },
    //         error : function(xhr, res, error){
    //             alert(error)
    //         }
    //     });
    // }

    function deleteCustomer() {
        $.ajax({
            url: '/customer-management/deleteCustomer',
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalDeleteCustomer").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    $(document).ready(function () {
        getDataCustomer();

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    });
</script>
@endsection