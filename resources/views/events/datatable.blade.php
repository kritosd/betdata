@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="input-group">
                        <select id="sportSelect" class="form-control" name="sportSelect">
                            <option>Select</option>
                        </select>
                        <div class="btn-group col-4">
                            <select id="groupSelect" class="form-control" name="groupSelect">
                                <option>Select</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table id="table" class="display table-bordered table-striped table-hover text-nowrap" style="width:100%">
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

<style>
    div.dataTables_wrapper div.dataTables_info {
        display: flex;
        flex-direction: column;
    }
    table.dataTable thead > tr > th.sorting {
        padding: 10px;
    }
</style>
<script>
$(document).ready(function () {
    let groups = null;
    var APP_URL = {!! json_encode(url('/')) !!}
    const default_Sport = 17 // select Soccer as default sport 

    console.log(APP_URL);
    const datatable = $('#table').DataTable({
        ajax: {
            url: `${APP_URL}/events/${default_Sport}`,
            dataSrc: ""
        },
        columnDefs:[
            {
                target:[0,1,3,4],
                className:'cell-border',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).css('padding', '10px')
                },
            },
            {
                target: 2,
                className:'cell-border',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).css('padding', '10px')
                    $(td).css('max-width', '300px')
                    $(td).css('overflow', 'hidden')
                    $(td).css('text-overflow', 'ellipsis')
                },
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
        drawCallback: function (data) {
            const results = data.json;
          rows = document.querySelectorAll('#membertable tbody tr');
            [].forEach.call(rows, function(row) {
              
            });
        },
        pageLength: 25,
        language: {
            search: "Αναζήτηση:",
            lengthMenu: 'Εμφάνισε <select name="table_length" aria-controls="table" class="custom-select custom-select-sm form-control form-control-sm">' +
            '<option value="25">25</option>' +
            '<option value="50">50</option>' +
            '<option value="100">100</option>' +
            '<option value="-1">All</option>' +
            '</select> εγγραφές',
            paginate: {
                first:      "Πρώτο",
                previous:   "Προηγούμενο",
                next:       "Επόμενο",
                last:       "Τελευταίο"
            },
            info:           "Εμφάνιση _START_ εως _END_ των _TOTAL_ εγγραφών",
            select: {
                rows: {
                    _: "%d επιλεγμένες εγγραφές",
                    0: "Κάντε κλικ σε μια σειρά για να την επιλέξετε",
                    1: "1 επιλεγμένη εγγραφή"
                }
            }
        }
    });

    datatable.on( 'xhr', function () {
        const sportId = document.getElementById('sportSelect').value;
        axios.get(`${APP_URL}/groups/${sportId}`)
            .then(function (response) {
                groups = response.data;
                const select = document.getElementById('groupSelect');
                select.innerHTML = "";
                groups.forEach((option) => {
                    select.insertAdjacentHTML('beforeend', `<option value="${option.id}">${option.name}</option>`);
                });

                setTimeout(() => {
                    addRemoveSelections(document.getElementById('groupSelect').value);
                }, 500);
            });
    } );

    function addRemoveSelections(groupId) {
        datatable.rows().deselect();
        const selectedGroup = groups.find(g => g.id == groupId);
        console.log(groups);
        // const events_list = selectedGroup.events_list.split(',');
        const events_list = selectedGroup.events.map(e => e.id);
        console.log(selectedGroup.events);
        var indexes = datatable.rows().eq( 0 ).filter( function (rowIdx) {
            const a = events_list.includes(datatable.cell( rowIdx, 0 ).data()) ? true : false;
            if (a) {
                datatable.rows().eq( 0 ).row(`:eq(${rowIdx})`).select();
            }
            return a;
        } );
    };

    $('#sportSelect').change(function(){
        datatable.ajax.url(`${APP_URL}/events/${this.value}`).load();
    });

    $('#groupSelect').change(function(){
        console.log(document.getElementById('groupSelect').value);
        addRemoveSelections(this.value);
    });

    datatable.on('click', 'tbody tr', function (e) {
        setTimeout(() => {
            const sportId = document.getElementById('sportSelect').value;
            const groupId = document.getElementById('groupSelect').value;
            const row = datatable.row( this ).data();
            var element = e.currentTarget;
            if (element.classList.contains('selected')) {
                axios.post(`${APP_URL}/group/` + groupId, {
                    sportId: parseInt(sportId),
                    eventId: row.id
                })
                .then(function (response) {
                    groups = response.data;
                });
            } else {
                axios.delete(`${APP_URL}/group/` + groupId, {
                    data: {
                        sportId: parseInt(sportId),
                        eventId: row.id
                    }
                })
                .then(function (response) {
                    groups = response.data;
                });
            }
        }, 50);
    });

    axios.get(`${APP_URL}/sports`)
        .then(function (response) {
            const data = response.data;
            const select = document.getElementById('sportSelect');
            select.innerHTML = "";
            data.forEach((option) => {
                select.insertAdjacentHTML('beforeend', `<option value='${option.id}'>${option.name_gr}</option>`);
            });
            // select Soccer by default
            select.value = default_Sport;
    });
    // axios.get(`${APP_URL}/groups`)
    //     .then(function (response) {
    //         const data = response.data;
    //         const select = document.getElementById('groupSelect');
    //         select.innerHTML = "";
    //         data.forEach((option) => {
    //             select.insertAdjacentHTML('beforeend', `<option>${option.name}</option>`);
    //         });
    //     });
});
</script>
@endsection