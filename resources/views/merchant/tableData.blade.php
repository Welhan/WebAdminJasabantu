@if (Session::get('message'))
<div class="alert {{ Session::get('alert') }} fade show" role="alert">
    <strong>{{ Session::get('message') }}</strong>
</div>
@endif

<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Created Date</th>
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
                url: "/merchant-management/tableData",
                data: {
                    email: '<?= $email; ?>',
                    phone: '<?= $phone; ?>',
                    name: '<?= $name; ?>',
                },
                type: "GET",
                dataType: "json"
            },
            columns: [{
                    data: 'Name',
                },
                {
                    data: 'Email',
                },
                {
                    data: 'Phone',
                },
                {
                    data: 'Address',
                },
                {    data: "CreatedDate",
                    "render": function(data, type, row) {
                        if (row['CreatedDate']) {
                            let Date = moment(row['CreatedDate'])
                            return Date.locale('id').format('D MMM YYYY, HH:mm:ss');
                        }
                        return '';
                    }
                },
                {    data: "UniqueID",
                    "render": function(data, type, row) {
                        uniqueid = row['UniqueID'].toString()
                        var dropdownHtml = '<div class="dropdown">' +
                                            '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>' +
                                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                                '<a class="dropdown-item" onclick="editMerchant('+ uniqueid +')">Edit</a>' +
                                                '<a class="dropdown-item" onclick="deleteMerchant('+ uniqueid +')">Delete</a>' +
                                            '</div>' +
                                        '</div>';
                        return dropdownHtml;
                    }
                }
            ]
        });

        window.setTimeout(function() {
            $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
                $(this).remove();
            });
        }, <?= 2 * 1000; ?>);
    })
</script>