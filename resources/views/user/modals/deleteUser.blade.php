<div class="modal fade" id="modalDeleteUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
            </div>
            <form action="" id="formSubmit">
                <div class="modal-body">
                    @csrf

                    <input type="hidden" id="id" name="id">

                    <table class="table table-bordered">
                        <thead>
                            <th>Unique ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" id="btnProcess">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
    })
</script>