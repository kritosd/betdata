<!-- Button trigger modal -->
<button id='countButton' type="button" class="btn btn-primary">0</button>
<button id="modalButton" type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" title="View selected events"><i class="fa fa-eye"></i></button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Selected events</h5>
        <button id='closeModal' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="table2" class="display table-bordered table-striped table-hover text-nowrap" style="width:100%">
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
      <div class="modal-footer">
        <button id='closeModalBtn' type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    
    $('#modalButton').click(function(){
        createDatatable();
    
    });
    $('#closeModal').on( 'click', function () {
        if (window.datatable2) {
            window.datatable2.destroy();
        }
    } );
    $('#closeModalBtn').on( 'click', function () {
        if (window.datatable2) {
            window.datatable2.destroy();
        }
    } );

    function createDatatable() {
        const groupId = document.getElementById('groupSelect').value;
        const selectedGroup = window.groups.find(g => g.id == groupId) ?? [];
        window.datatable2 = $('#table2').DataTable({
            data: selectedGroup.events,
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
            // select: {
            //     style: 'multi'
            // },
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
            // initComplete: function(settings, json) {
            //     getGroups();
            //     // alert( 'DataTables has finished its initialisation.' );
            // }
        });
    }
    

});
</script>