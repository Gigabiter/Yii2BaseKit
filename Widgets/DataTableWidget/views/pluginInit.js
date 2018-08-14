$(document).ready(function() {
    $('$id$').DataTable( {
        "ajax": '/api/v1/json/petService/all?plain=1',
        "language": $i18n$
    } );
} );