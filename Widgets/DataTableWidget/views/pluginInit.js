$(document).ready(function() {
    $('$id$').DataTable( {
        "ajax": 'http://yii2project.local/api/v1/json/petService?plain=1',
        "language": $i18n$
    } );
} );