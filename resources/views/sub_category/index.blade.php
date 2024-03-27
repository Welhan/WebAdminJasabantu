@extends('layout.main')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="text-primary">Sub Category</h2>
                <button class="btn btn-primary" type="button" id="newSubCategory">New Sub Category</button>
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
    $("#newSubCategory").on("click", function(e){
        e.preventDefault()
        $.ajax({
            url : "/sub-category/newSubCategory",
            type : "GET",
            dataType : "JSON",
            success: function(response){
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalNewSubCategory").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        })
    })

    function getDataSubCategory() {
        $.ajax({
            url: '/sub-category/getData',
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

        
    function editCategory(id) {
        $.ajax({
            url: '/sub-category/editSubCategory',
            data: {
                id
            },
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalEditSubCategory").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    function deleteCategory(id) {
        $.ajax({
            url: '/sub-category/deleteSubCategory',
            data: {
                id
            },
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalDeleteSubCategory").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    $(document).ready(function () {
        getDataSubCategory();

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    });
</script>
@endsection