@if (Session::get('message'))
<div class="alert {{ Session::get('alert') }} fade show" role="alert">
    <strong>{{ Session::get('message') }}</strong>
</div>
@endif

<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>Config</th>
            <th>Value</th>
            <th>Active</th>
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
                url: "/web-config/tableData",
                data: {
                },
                type: "GET",
                dataType: "json"
            },
            columns: [{
                    data: 'Config',
                },
                {
                    data: 'Value',
                },
                {
                    "render": function(data, type, row) {
                        let allowF = '<input type="checkbox" class="btn-check" disabled ';
                        if (row['ActiveF'] == 1) {
                            allowF += 'checked';
                        } else {
                            allowF += '';
                        }
                        allowF += '>';
                        allowF += '</div>';

                        return allowF;
                    }
                },
                {    data: "ID",
                    "render": function(data, type, row) {
                        id = row['ID']
                        var dropdownHtml = '<div class="dropdown">' +
                                            '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>' +
                                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                                '<a class="dropdown-item" onclick="editConfig('+ id +')">Edit</a>' +
                                                '<a class="dropdown-item" onclick="deleteConfig('+ id +')">Delete</a>' +
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