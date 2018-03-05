var ChangeBuildMolDialog = "#ChangeBuildMolDialog";

$(document).ready(function () {
    $(document).on('ready pjax:success', function () {
        $('#ChangeBuildMOL').click(function (e) {
            e.preventDefault(); //for prevent default behavior of <a> tag.
            var employee_id = $('select[name="Mattraffic[id_mol]"]').val();
            if (employee_id !== "") {
                $(ChangeBuildMolDialog).modal('show').find('.modal-body').html('<div style="height: 150px; width: 100%; background: url(' + baseUrl + 'images/progress.svg) center center no-repeat; background-size: 20px;"></div>');
                $(ChangeBuildMolDialog).modal('show').find('.modal-body').load($(this).attr('href') + "?employee_id=" + employee_id, function () {

                });
            } else
                bootbox.alert('Необходимо выбрать материально-ответственное лицо, которому будет добавлено здание.');

        });
    });

    $(document).on("click", ChangeBuildMolDialog + "_apply", function () {
        if ($("form.gridview-filter-form").find(".has-error").length)
            return false;

        if ($("#employee-id_build").val() != "")
            $.ajax({
                url: baseUrl + "Fregat/employee/add-employee",
                type: "post",
                data: {
                    employee_id: $("#mattraffic-id_mol").val(),
                    build_id: $("#employee-id_build").val(),
                    buttonloadingid: "ChangeBuildMolDialog_apply"
                },
                success: function (data) {
                    $("#mattraffic-id_mol").append("<option value='" + data.id + "'>" + data.text + "</option>");
                    $("#mattraffic-id_mol").val(data.id).trigger('change');
                    $(ChangeBuildMolDialog).modal("hide");
                },
                error: function (err) {
                    $("div.errordialog").show();
                    if ((err.responseText).indexOf("Internal Server Error (#500): ") >= 0)
                        $("div.errordialog").text((err.responseText).substring(30));
                    else
                        $("div.errordialog").text(err.responseText);
                }
            });
        else {
            $("div.errordialog").show();
            $("div.errordialog").text("Не выбрано здание.");
        }

        return false;
    });

    $(document).on('click', ChangeBuildMolDialog + "_close", function (event) {
        $(ChangeBuildMolDialog).modal("hide");
    });

});