var filtergrid = "#patientglaukgrid_gw";
var filtermodal = "#PatientFilter";
var filtersearch = "PatientSearch";

$(document).on('ready pjax:success', function () {
    $('.filter_button').click(function (e) {
        e.preventDefault(); //for prevent default behavior of <a> tag.
        $(filtermodal).modal('show').find('.modal-body').load($(this).attr('href'), function () {
            InitAddress();
            $('input[name="patient_dr-patientfilter-patient_dr"').mask('99.99.9999');
        });
    });
});

$(document).on("click", filtermodal + "_apply", function () {
    if ($("form.gridview-filter-form").find(".has-error").length)
        return false;

    $(filtermodal)[0].statusform = 1;
    $(filtermodal).modal("hide");
    $(filtergrid).yiiGridView("applyFilter");

    return false;
});

$(document).on('click', filtermodal + "_resetfilter", function (event) {
    bootbox.confirm("Вы уверены, что хотите сбросить дополнительный фильтр?", function (result) {
        if (result) {
            $(filtermodal)[0].statusform = 0;
            $(filtergrid).yiiGridView("applyFilter");
        }
    });

});

$(document).on('click', filtermodal + "_close", function (event) {
    $(filtermodal).modal("hide");
});

$(document).on("beforeFilter", filtergrid, function (event) {
    var formcontain = $("form" + filtermodal + "-form").serialize();
    var formgrid = $("form.gridview-filter-form");
    var input = $("input[name='" + filtersearch + "[_filter]']");

    if ($(filtermodal).length && $(filtermodal)[0].statusform === 1) {
        if (!input.length)
            formgrid.append($('<input type="hidden" class="form-control" name="' + filtersearch + '[_filter]" />').val(formcontain));
        else
            input.val(formcontain);
    } else
    if (input.length)
        input.remove();

    return true;
});

function InitAddress() {
    $.ajax({
        url: "?r=Base%2Ffias%2Fcheckstreets",
        type: "post",
        data: {city_AOGUID: $('select[name="PatientFilter[fias_city]"]').val()},
        success: function (data) {
            $('select[name="PatientFilter[fias_street]"]').prop("disabled", !(data && data != '0'));
            $('input[name="PatientFilter[patient_dom]').prop("disabled", !(data || data == '0'));
            $('input[name="PatientFilter[patient_korp]').prop("disabled", !(data || data == '0'));
            $('input[name="PatientFilter[patient_kvartira]').prop("disabled", !(data || data == '0'));
        },
        error: function (data) {
            console.error("Ошибка FillCity()");
        }
    });
}

function FillCity() {
    $('select[name="PatientFilter[fias_street]"]').select2('val', '');
    InitAddress();
}

function ClearCity() {
    $('select[name="PatientFilter[fias_street]"]').prop("disabled", true);
    $('select[name="PatientFilter[fias_street]"]').select2('val', '');

    $('input[name="PatientFilter[patient_dom]').prop("disabled", true);
    $('input[name="PatientFilter[patient_dom]').val("");

    $('input[name="PatientFilter[patient_korp]').prop("disabled", true);
    $('input[name="PatientFilter[patient_korp]').val("");

    $('input[name="PatientFilter[patient_kvartira]').prop("disabled", true);
    $('input[name="PatientFilter[patient_kvartira]').val("");
}