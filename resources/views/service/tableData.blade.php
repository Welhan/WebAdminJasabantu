@if (Session::get('message'))
<div class="alert {{ Session::get('alert') }} fade show" role="alert">
    <strong>{{ Session::get('message') }}</strong>
</div>
@endif

<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Service</th>
            <th width="100px">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            info : true,
            lengthChange : false,
            searching : false,
            ajax: {
                url: "/service/tableData",
                data: {
                },
                type: "GET",
                dataType: "json"
            },
            columns: [{
                    data: 'ID',
                },
                {
                    data: 'Service',
                },
            ]
        });

        window.setTimeout(function() {
            $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
                $(this).remove();
            });
        }, <?= 2 * 1000; ?>);
    })
</script>