@extends('layout.main')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="text-primary">Mitra Management</h2>
                <button class="btn btn-primary" type="button" id="newMitra">New Mitra</button>
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
                <div class="table table-responsive">
                    <table class="dataTableMitra table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>UniqueID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="viewModal" style="display: none"></div>
@endsection
@section('script')
<script>
    $("#newMitra").on("click", function(e){
        e.preventDefault()
        $.ajax({
            url : "/mitra-management/newMitra",
            type : "GET",
            dataType : "JSON",
            success: function(response){
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalNewMitra").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        })
    })
    $(document).ready(function () {
        $('#loader').show(); 
        $('.dataTableMitra').hide(); 
        $('.dataTableMitra').DataTable({
            "info": false,
            "lengthChange": false,
            "searching": false,
            "serverSide": true,
            "ajax": {
                "url": "/mitra-management/getDataMitra",
                "type": "GET",
                "dataType": "json"
            },
            "columns": [
                { "data": "UniqueID" },
                { "data": "Name" },
                { "data": "Name" },
                { "data": "Name" },
                { 
                    "data": null,
                    "render": function(data, type, row) {
                        var dropdownHtml = '<div class="dropdown">' +
                                            '<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>' +
                                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                                '<a class="dropdown-item" href="#">Action 1</a>' +
                                                '<a class="dropdown-item" href="#">Action 2</a>' +
                                            '</div>' +
                                        '</div>';
                        return dropdownHtml;
                    }
                }
            ],
            "initComplete": function(settings, json) {
                $('#loader').hide();
                $('.dataTableMitra').show();
            }
        });
    });
</script>
@endsection