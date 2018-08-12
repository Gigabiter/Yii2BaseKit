(function($) {
    $('body').on('click', '.lsv-value', function (e) {
        e.preventDefault();
        var container = $(this).closest('.lsv-container');
        console.log(container.find('.modal'));
        container.find('.modal').modal('show');
    });

    $('body').on('click', '.lsv-save', function (e) {
        e.preventDefault();
        var container = $(this).closest('.lsv-container');
        var labelSyncPoint = container.attr('data-label-sync-point');
        var saveTo = container.attr('data-save-to');
        var saveToRelations = JSON.parse(container.attr('data-save-to-relations'));
        var data = getFormDataFromArray(container.find('.modal :input').serializeArray());
        var value = JSON.stringify(data);
        $.ajax({
            method: 'POST',
            url: labelSyncPoint,
            data: {value: value},
            success: function (data) {
                container.find('.lsv-value').html(data.label);
                for (var i in saveToRelations) {
                    if (data[i]) {
                        container.find('.lsv-save-to-'+i).val(data[i]).trigger('change');
                    }
                }
                container.find('.lsv-save-to').val(data.value);
                setTimeout(function () {
                    container.find('.lsv-save-to').trigger('change');
                }, 100);
            }
        });
        container.find('.modal').modal('hide');
    });
})(jQuery);