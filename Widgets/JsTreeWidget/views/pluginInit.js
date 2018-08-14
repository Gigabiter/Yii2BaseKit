/**
 * Этот объект управляет деревом,
 * чтобы управлять деревом извне виджета
 * передайте любое имя для этого менеджера
 * и вызывайте его по имени по умолчанию объект
 * называется TreeManager. Если на странице больше
 * одного виджета JsTreeWidget нужно передать
 * каждому уникальное имя для TreeManager
 *
 * @type {{}}
 */
window.__widgetName__ = {
    init: function() {
        this.build(__initialDataToServer__);
    },
    /**
     * Построить менеджер
     */
    build: function(dataToServer) {
        $('__id__').jstree('destroy');
        $('__id__').text('__labels.loading__');
        $.ajax({
            url: '__dataUrl__',
            method: 'GET',
            data: dataToServer,
            success: function(treeData) {
                $('__id__').jstree({
                    "core" : {
                        "multiple": __options.multiple__,
                        'data' : treeData
                    },
                    "search": {
                        "case_insensitive": true,
                        "show_only_matches" : true
                    },
                    "plugins": __plugins__
                });
                $('__searchId__').unbind('keyup');
                var to = false;
                $('__searchId__').on('keyup', function () {
                    if(to) { clearTimeout(to); }
                    to = setTimeout(function () {
                        var v = $('__searchId__').val();
                        $('__id__').jstree(true).search(v);
                    }, 250);
                });
            },
            fail: function() {
                console.error('JsTreeWidget ошибка не удалось получить данные');
            }
        });
    },
    /**
     * Вернуть выбранный пользователем узел
     */
    oneSelectedNode: function () {
        var result = $('__id__').jstree('get_selected');
        if (result.length) {
            return result.pop();
        }

        return false;
    },
    /**
     * Вернуть все выбранные пользователем узелы
     */
    allSelectedNode: function () {
        return $('__id__').jstree('get_selected');
    }
};

/**
 * Первое построение дерева
 */
window.__widgetName__.init();