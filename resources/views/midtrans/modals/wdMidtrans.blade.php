<div class="modal fade" id="modalMidtransWD" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Withdraw</h5>
            </div>
            <form id="formSubmit">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Saldo</label>
                        <input type="text" class="form-control" readonly value="Rp. 1.000.000">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Nominal</label>
                        <input type="number" class="form-control" id="Jumlah" name="Jumlah"
                            placeholder="Jumlah Nominal">
                        <div id="errJumlah" class="invalid-feedback"></div>
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
    $(document).ready(function () {

    })
</script>