@extends('layout.main')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="text-primary">Banner</h2>
                <button class="btn btn-primary" type="button" id="newBanner">New Banner</button>
            </div>
            <div class="card-body">
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
    $("#newBanner").on("click", function(e){
        e.preventDefault()
        $.ajax({
            url : "/banner/newBanner",
            type : "GET",
            dataType : "JSON",
            success: function(response){
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalNewBanner").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        })
    })

    function getDataBanner() {
        $.ajax({
            url: '/banner/getData',
            data: {
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

        
    function editBanner(id) {
        $.ajax({
            url: '/banner/editBanner',
            data: {
                id
            },
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalEditBanner").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    function deleteBanner(id) {
        $.ajax({
            url: '/banner/deleteBanner',
            data: {
                id
            },
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalDeleteBanner").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    $(document).ready(function () {
        getDataBanner();

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    });
</script>
@endsection