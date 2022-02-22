$(document).ready(function () {
	
    $('#sample_areas').DataTable({
        "pagingType": "bootstrap_extended",
        columnDefs: [
            {'targets': 0, 'orderable': false},
            {'targets': 4, 'orderable': false}
        ],
        "order": [
            [1, 'asc']
        ]
    });
	
});