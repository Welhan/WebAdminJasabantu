@extends('layout.main')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="text-primary">Web Config</h2>
                <button class="btn btn-primary" type="button" id="newConfig">New Config</button>
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
    $("#newConfig").on("click", function(e){
        e.preventDefault()
        $.ajax({
            url : "/web-config/newConfig",
            type : "GET",
            dataType : "JSON",
            success: function(response){
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalNewConfig").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        })
    })

    function getDataConfig() {
        $.ajax({
            url: '/web-config/getData',
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

        
    function editConfig(id) {
        $.ajax({
            url: '/web-config/editConfig',
            data: {
                id
            },
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalEditConfig").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    function deleteConfig(id) {
        $.ajax({
            url: '/web-config/deleteConfig',
            data: {
                id
            },
            success: function(response) {
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalDeleteConfig").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        });
    }

    $(document).ready(function () {
        getDataConfig();

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    });
</script>
@endsection