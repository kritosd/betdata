@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="input-group">
                        <select id="sportSelect" class="form-control col-2" name="sportSelect">
                        </select>
                        <div class="btn-group col-3">
                            <select id="groupSelect" class="form-control" name="groupSelect">
                            </select>
                            @include('events.modal')
                        </div>
                        <form class="form-inline col-4">
                            <div class="form-group col-12">
                                <label class="form-label" for="typeNumber">Limit: </label>
                                <input type="number" min="0" id="limitInput" class="form-control col-3" />
                                <label class="form-label" for="typeNumber">Days: </label>
                                <input type="number" min="0" id="daysInput" class="form-control col-3" />
                            </div>
                        </form>
                        <div class="btn-group col-3">
                            <button id="selectAll" type="button" class="btn btn-primary">Select All</button>
                            <button id="unselectAll" type="button" class="btn btn-secondary">Unselect All</button>
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
    window.groups = null;
    var APP_URL = {!! json_encode(url('/')) !!}
    const default_Sport = 17 // select Soccer as default sport 

    // console.log(APP_URL);
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
            
            // getGroups();
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
        },
        initComplete: function(settings, json) {
            getGroups();
            // alert( 'DataTables has finished its initialisation.' );
        }
    });
window.datatable=datatable;
    datatable.on( 'xhr', function () {
        
    } );
    
    function getGroups() {
        const sportId = document.getElementById('sportSelect').value;
        if (!sportId) {
            setTimeout(() => {
                getGroups();
            }, 1000);
            return;
        }
        axios.get(`${APP_URL}/groups/${sportId}`)
            .then(function (response) {
                window.groups = response.data;
                const select = document.getElementById('groupSelect');
                select.innerHTML = "";
                window.groups.forEach((option) => {
                    select.insertAdjacentHTML('beforeend', `<option value="${option.id}">${option.name}</option>`);
                });

                setTimeout(() => {
                    addRemoveSelections(document.getElementById('groupSelect').value);
                    changeInputFields(document.getElementById('groupSelect').value);
                }, 500);
            });
    }

    function changeInputFields(groupId) {
        changeNumberOfSelections();
        const selectedGroup = window.groups.find(g => g.id == groupId);
        if (!selectedGroup) return;

        const limitInput = document.getElementById('limitInput');
        limitInput.value = selectedGroup.visible_events;
        
        const daysInput = document.getElementById('daysInput');
        daysInput.value = selectedGroup.next_days;
    }

    function addRemoveSelections(groupId) {
        datatable.rows().deselect();
        const selectedGroup = window.groups.find(g => g.id == groupId);
        if (!selectedGroup) return;
        // console.log(groups);
        // const events_list = selectedGroup.events_list.split(',');
        const events_list = selectedGroup.events.map(e => e.id);
        // console.log(selectedGroup.events);
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
        getGroups();
    });

    $('#groupSelect').change(function(){
        addRemoveSelections(this.value);
        changeInputFields(this.value);
    });

    $('#limitInput,#daysInput').change(function(){
        const sportId = document.getElementById('sportSelect').value;
        const groupId = document.getElementById('groupSelect').value;
        const limitValue = document.getElementById('limitInput').value;
        const daysValue = document.getElementById('daysInput').value;
        let value = this.value;
        if (!value) {
            value = 0;
        }
        axios.put(`${APP_URL}/group/` + groupId, {
                    sportId: parseInt(sportId),
                    visible_events: limitValue,
                    next_days: daysValue,
                })
                .then(function (response) {
                    window.groups = response.data;
                });
    });

    function changeNumberOfSelections() {
        const selectedNum = datatable.rows( { selected: true } ).count();
        const el = document.getElementById('countButton');
        el.innerHTML = selectedNum;
    };

    datatable.on('click', 'tbody tr', function (e) {
        setTimeout(() => {
            changeNumberOfSelections();
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
                    window.groups = response.data;
                });
            } else {
                axios.delete(`${APP_URL}/group/` + groupId, {
                    data: {
                        sportId: parseInt(sportId),
                        eventId: row.id
                    }
                })
                .then(function (response) {
                    window.groups = response.data;
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

    $('#selectAll').click(function(){
        // console.log(datatable);
        const a = datatable.rows({ filter: 'applied' }).select();
        // console.log(a);
        setTimeout(() => {
            changeNumberOfSelections();
            const sportId = document.getElementById('sportSelect').value;
            const groupId = document.getElementById('groupSelect').value;
            // const row = datatable.row( this ).data();
            const ids = a.eq( 0 ).map((o) => {
                return datatable.cell( o, 0 ).data();
            }).toArray();
            // console.log(ids);
            axios.post(`${APP_URL}/group/` + groupId, {
                    sportId: parseInt(sportId),
                    eventId: ids
                })
                .then(function (response) {
                    window.groups = response.data;
                });
        }, 50);
    });

    $('#unselectAll').click(function(){
        const a = datatable.rows({ filter: 'applied' }).deselect();
        // console.log(a);
        setTimeout(() => {
            changeNumberOfSelections();
            const sportId = document.getElementById('sportSelect').value;
            const groupId = document.getElementById('groupSelect').value;
            // const row = datatable.row( this ).data();
            const ids = a.eq( 0 ).map((o) => {
                return datatable.cell( o, 0 ).data();
            }).toArray();
            // var element = e.currentTarget;
            axios.delete(`${APP_URL}/group/` + groupId, {
                data: {
                    sportId: parseInt(sportId),
                    eventId: ids
                }
            })
            .then(function (response) {
                window.groups = response.data;
            });
        }, 50);
    });
});
</script>
@endsection