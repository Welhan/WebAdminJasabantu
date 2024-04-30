@extends('layout.main')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="text-primary">Merchant</h2>
                <button class="btn btn-primary" type="button" id="newMerchant">New Merchant</button>
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
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="btnFilter">Filter</button>
                        <button type="button" class="btn btn-danger" id="btnReset">Reset Filter</button>
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
    $("#newMerchant").on("click", function(e){
        e.preventDefault()
        $.ajax({
            url : "/merchant-management/newMerchant",
            type : "GET",
            dataType : "JSON",
            success: function(response){
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalNewMerchant").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        })
    })

    function getDataMerchant() {
        let email = document.querySelector('#email');
        let phone = document.querySelector('#phone');
        let name = document.querySelector('#name');
        $.ajax({
            url: '/merchant-management/getData',
            data: {
                email : email.value,
                phone : phone.value,
                name : name.value,
            },
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

        
    function editMerchant(id) {
        $.ajax({
            url: '/merchant-management/editMerchant',
            data: {
                id
            },
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalEditMerchant").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    function deleteMerchant(id) {
        $.ajax({
            url: '/merchant-management/deleteMerchant',
            data: {
                id
            },
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalDeleteMerchant").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    $(document).ready(function () {
        getDataMerchant();

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $('#btnFilter').on('click', function(e) {
            e.preventDefault();
            getDataMerchant();
        })

        $('#btnReset').on('click', function(e) {
            $('#email').val('');
            $('#name').val('');
            $('#phone').val('');

            e.preventDefault();
            getDataMerchant();
        })
    });
</script>
@endsection