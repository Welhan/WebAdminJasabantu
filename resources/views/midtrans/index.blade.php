@extends('layout.main')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="text-primary">Midtrans</h2>
                <button class="btn btn-primary" type="button" id="newMidtransWD">Withdraw</button>
            </div>
            <div class="card-body">
                <p class="card-title">Saldo Midtrans</p>
                <p class="font-weight-500">Rp. 1.000.000</p>
            </div>
        </div>
    </div>
</div>
<div id="viewModal" style="display: none"></div>
@endsection
@section('script')
<script>
    $("#newMidtransWD").on("click", function(e){
        e.preventDefault()
        $.ajax({
            url : "/midtrans/newMidtransWD",
            type : "GET",
            dataType : "JSON",
            success: function(response){
                if(response.view){
                    $("#viewModal").show()
                    $("#viewModal").html(response.view)
                    $("#modalMidtransWD").modal('show')
                }
            },
            error : function(xhr, res, error){
                alert(error)
            }
        })
    })
    $(document).ready(function () {

    });
</script>
@endsection