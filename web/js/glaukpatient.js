function InitAddress() {

    if (!$('select[name="Fias[AOGUID]"]').prop("disabled"))
        $.ajax({
            url: "?r=Base%2Ffias%2Fcheckstreets",
            type: "post",
            data: {city_AOGUID: $('select[name="Fias[AOGUID]"]').val()},
            success: function (data) {
                $('select[name="Patient[id_fias]"]').prop("disabled", !(data && data != '0'));

                if (data && data != '0')
                    $('div.form-group.field-patient-id_fias').addClass("required");
                else
                    $('div.form-group.field-patient-id_fias').removeClass("required");

                if (data || data == '0') {
                    $('div.form-group.field-patient-patient_dom').addClass("required");
                    $('div.form-group.field-patient-patient_kvartira').addClass("required");

                } else {
                    $('div.form-group.field-patient-patient_dom').removeClass("required");
                    $('div.form-group.field-patient-patient_kvartira').removeClass("required");
                }

                $('input[name="Patient[patient_dom]').prop("disabled", !(data || data == '0'));
                $('input[name="Patient[patient_korp]').prop("disabled", !(data || data == '0'));
                $('input[name="Patient[patient_kvartira]').prop("disabled", !(data || data == '0'));

            },
            error: function (data) {
                console.error("Ошибка FillCity()");
            }
        });
}

function FillCity() {
    $('select[name="Patient[id_fias]"]').select2('val', '');
    InitAddress();
}

function ClearCity() {
    $('select[name="Patient[id_fias]"]').prop("disabled", true);
    $('div.form-group.field-patient-id_fias').removeClass("required has-success has-error").children("p").html("");
    $('select[name="Patient[id_fias]"]').select2('val', '');

    $('div.form-group.field-patient-patient_dom').removeClass("required has-success has-error").children("p").html("");
    $('input[name="Patient[patient_dom]').val("");

    $('div.form-group.field-patient-patient_korp').removeClass("has-success has-error").children("p").html("");
    $('input[name="Patient[patient_korp]').val("");

    $('input[name="Patient[patient_kvartira]').val("");
    $('div.form-group.field-patient-patient_kvartira').removeClass("required has-success has-error").children("p").html("");

    $('input[name="Patient[patient_dom]').prop("disabled", true);
    $('input[name="Patient[patient_korp]').prop("disabled", true);
    $('input[name="Patient[patient_kvartira]').prop("disabled", true);

    SetSessionEach([
        $('select[name="Patient[id_fias]"]'),
        $('input[name="Patient[patient_dom]'),
        $('input[name="Patient[patient_korp]'),
        $('input[name="Patient[patient_kvartira]')
    ]);
}

function f_scrollTop() {
    return f_filterResults(
            window.pageYOffset ? window.pageYOffset : 0,
            document.documentElement ? document.documentElement.scrollTop : 0,
            document.body ? document.body.scrollTop : 0
            );
}
function f_filterResults(n_win, n_docel, n_body) {
    var n_result = n_win ? n_win : 0;
    if (n_docel && (!n_result || (n_result > n_docel)))
        n_result = n_docel;
    return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
}

$(document).ready(function () {
    InitAddress();
    $('input[name="patient_dr-patient-patient_dr"').mask('99.99.9999');
    $('input[name="glaukuchet_uchetbegin-glaukuchet-glaukuchet_uchetbegin"').mask('99.99.9999');
    $('input[name="glaukuchet_lastvisit-glaukuchet-glaukuchet_lastvisit"').mask('99.99.9999');
    $('input[name="glaukuchet_operdate-glaukuchet-glaukuchet_operdate"').mask('99.99.9999');
    $('input[name="glaukuchet_lastmetabol-glaukuchet-glaukuchet_lastmetabol"').mask('99.99.9999');
    $('input[name="glaukuchet_deregdate-glaukuchet-glaukuchet_deregdate"').mask('99.99.9999');
})

/*$("body").scroll(function () {
    console.debug($("body").scrollTop());
});*/