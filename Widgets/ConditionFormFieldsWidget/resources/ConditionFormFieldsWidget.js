// TODO it is need to rebuild this object in function
var ConditionFormFieldsWidget = {
    init: function() {
        var self = this;
        $(function() {
            self.handleForms();
        });
    },

    handleForms: function() {
        var self = this;
        $.initialize('.condition-form-fields', function(e, o) {
            $(o).hide();
            var conditionField = $(o).attr('data-condition');
            var conditionValue = $(o).attr('data-condition-value');
            var wrapper = $(o).attr('data-wrapper');
            var conditionFieldObject = self.buildConditionFieldObject(conditionField, wrapper, o);
            self.showForm(conditionFieldObject.val(), conditionValue, o);
            self.handleConditionFieldChange(o, conditionField, conditionValue);
        });
    },

    handleConditionFieldChange: function(o, conditionField, conditionValue) {
        var self = this;
        $(conditionField).on('change', function(e) {
            $(o).parent().find('.condition-form-fields').hide();
            if (self.showForm($(this).val(), conditionValue, o)) {
                e.stopImmediatePropagation();
            }
        });
    },

    buildConditionFieldObject: function(conditionField, wrapper, o) {
        var conditionFieldObject = $(conditionField);
        if (conditionFieldObject.is(':radio') || conditionFieldObject.is(':checkbox')) {
            conditionField += ':checked';
            conditionFieldObject = $(conditionField);
        }
        if (wrapper) {
            conditionFieldObject = $(o).closest(wrapper).find(conditionField);
        }
        return conditionFieldObject;
    },

    showForm: function (realValue, conditionValue, form) {
        console.log('realValue = '+realValue);
        console.log('conditionValue = '+conditionValue);
        if (realValue == conditionValue) {
            $(form).show();
            return true;
        }
        return false;
    }

};

ConditionFormFieldsWidget.init();

