@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Events') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="input-group">
                        <select id="sportSelect" class="form-control" name="sportSelect">
                            <option>Select</option>
                        </select>
                        <select id="groupSelect" class="form-control" name="groupSelect">
                            <option>Select</option>
                        </select>
                    </div>

                    <table id="table" class="display table-bordered table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Start Date</th>
                                <th>Name</th>
                                <th>Country Name</th>
                                <th>League Name</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    const datatable = $('#table').DataTable({
        ajax: {
            url: '/events',
            dataSrc: ""
        },
        columnDefs:[
            {
                target:1,
                className:'cell-border'
            }
        ],
        select: {
            style: 'multi'
        },
        // buttons: [
        //     // { extend: 'create', editor: editor },
        //     // { extend: 'edit', editor: editor },
        //     {
        //         extend: 'selectedSingle',
        //         text: 'Salary +250',
        //         action: function (e, dt, node, config) {
        //             // Immediately add `250` to the value of the salary and submit
        //             editor
        //                 .edit(table.row({ selected: true }).index(), false)
        //                 .set('salary', editor.get('salary') * 1 + 250)
        //                 .submit();
        //         }
        //     },
        //     // { extend: 'remove', editor: editor }
        // ],
        // buttons: [
        //     'copy', 'excel', 'aaaa'
        // ],
        columns: [
            { data: 'id' },
            { data: 'start_date' },
            { data: 'name' },
            { data: 'country_name' },
            { data: 'league_name' },
        ],
        responsive: true,
        aaSorting: [],
        drawCallback: function () {
          rows = document.querySelectorAll('#membertable tbody tr');
            [].forEach.call(rows, function(row) {
              
            });
        }
    });

    $('#sportSelect').change(function(){
        console.log(this.value);
        datatable.ajax.url(`/eventsBySportId/${this.value}`).load();
    });
});
</script>

<script>
$(document).ready(function () {
    axios.get('/sports')
        .then(function (response) {
            const data = response.data;
            const select = document.getElementById('sportSelect');
            select.innerHTML = "";
            data.forEach((option) => {
                select.insertAdjacentHTML('beforeend', `<option value='${option.id}'>${option.name_gr}</option>`);
            });
        });
});

</script>
<script>
$(document).ready(function () {
    axios.get('/groups')
        .then(function (response) {
            const data = response.data;
            const select = document.getElementById('groupSelect');
            select.innerHTML = "";
            data.forEach((option) => {
                select.insertAdjacentHTML('beforeend', `<option>${option.name}</option>`);
            });
        });
});
</script>
@endsection