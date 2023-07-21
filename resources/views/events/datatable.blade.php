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
                    <div class="btn-group py-2 col-4 row">
                        <select id="sportSelect" class="form-control" name="sportSelect">
                            <option>Select</option>
                        </select>
                    </div>
                    <div class="input-group py-2 row">
                        <div class="btn-group col-4">
                            <select id="groupSelect" class="form-control" name="groupSelect">
                                <option>Select</option>
                            </select>
                        </div>
                        <button id="Addbutton" class="btn btn-primary">Update List</button></p>
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
        drawCallback: function (data) {
            const results = data.json;
          rows = document.querySelectorAll('#membertable tbody tr');
            [].forEach.call(rows, function(row) {
              
            });
        }
    });
    
    //To pre-select the first row
    

    datatable.on( 'xhr', function () {
        // setTimeout(() => {
        //     var json = datatable.ajax.json();
        //     console.log('now selecting');
        //     // Find indexes of rows which have `Yes` in the second column
        //     console.log(json);
        //     var indexes = datatable.rows().eq( 0 ).filter( function (rowIdx) {
        //         return datatable.cell( rowIdx, 0 ).data() === 116513 ? true : false;
        //     } );
            
        //     // Add a class to those rows using an index selector
        //     datatable.rows( indexes )
        //         .nodes()
        //         .to$()
        //         .addClass( 'selected' );
        //     console.log('finish selecting');
        // }, 500);
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
                    // var json = groups;
                    // // Find indexes of rows which have `Yes` in the second column
                    // const events_list = json[0].events_list.split(',');
                    // var indexes = datatable.rows().eq( 0 ).filter( function (rowIdx) {
                    //     const a = events_list.includes(datatable.cell( rowIdx, 0 ).data().toString()) ? true : false;
                    //     if (a) {
                    //         datatable.rows().eq( 0 ).row(`:eq(${rowIdx})`).select();
                    //     }
                    //     return a;
                    // } );
                }, 500);
            });
    } );

    function addRemoveSelections(groupId) {
        datatable.rows().deselect();
        const selectedGroup = groups.find(g => g.id == groupId);
        console.log(groups);
        const events_list = selectedGroup.events_list.split(',');
        var indexes = datatable.rows().eq( 0 ).filter( function (rowIdx) {
            const a = events_list.includes(datatable.cell( rowIdx, 0 ).data().toString()) ? true : false;
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
        buttonDisableCheck();
    });

    datatable.on('click', 'tbody tr', function (e) {
        buttonDisableCheck();
    });

    function buttonDisableCheck() {
        setTimeout(() => {
            const groupId = document.getElementById('groupSelect').value;
            console.log(groupId);
            const groupSelected = groups.find(g => g.id == groupId);
            const events_list = groupSelected.events_list;
            let new_events_list = null;
            datatable.rows('.selected').eq( 0 ).filter( function (rowIdx) {
                const a = datatable.rows().eq( 0 ).row(`:eq(${rowIdx})`);
                if (new_events_list === null) {
                    new_events_list = a.data().id;
                } else {
                    new_events_list = new_events_list + ',' + a.data().id;
                }
                return true;
            } );
            console.log(groupSelected);
            console.log(events_list);
            console.log(new_events_list);
            if (events_list.toString() === new_events_list.toString()) {
                console.log('Same');
                document.querySelector('#Addbutton').disabled = true;
            } else {
                console.log('Not Same');
                document.querySelector('#Addbutton').disabled = false;
            }
        }, 50);
    };

    $('#Addbutton').click(function () {
        // alert(datatable.rows('.selected').data().length + ' row(s) selected');
        const groupId = document.getElementById('groupSelect').value;
        console.log(datatable.rows('.selected').data());
        let new_events_list = null;
        datatable.rows('.selected').eq( 0 ).filter( function (rowIdx) {
            const a = datatable.rows().eq( 0 ).row(`:eq(${rowIdx})`);
            console.log(a.data().id);
            if (new_events_list === null) {
                new_events_list = a.data().id;
            } else {
                new_events_list = new_events_list + ',' + a.data().id;
            }
            return true;
        } );
            console.log(new_events_list);
        axios.post(`${APP_URL}/group/` + groupId, {
            events_list: new_events_list,
        })
        .then(function (response) {
            groups = response.data;
            console.log(groups);
            buttonDisableCheck();
        });
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