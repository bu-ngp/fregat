var ChangeBuildMolDialog = "#ChangeBuildMolDialog";

$(document).ready(function () {
    $(document).on('ready pjax:success', function () {
        $('#ChangeBuildMOL').click(function (e) {
            e.preventDefault(); //for prevent default behavior of <a> tag.
 var employee_id =$('select[name="Mattraffic[id_mol]"]').val();
            if (employee_id !== "") {
                console.debug($(this).attr('href') + "?employee_id=" + employee_id);
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

        var dopparams;
        dopparams = {id: $("#osmotrakt-osmotrakt_id").val()};
        console.debug($("#organ-organ_id").val())
        if ($("#organ-organ_id").val() != "")
            $.ajax({
                url: baseUrl + "Fregat/osmotrakt/osmotrakt-send",
                type: "post",
                data: {
                    osmotrakt_id: $("#osmotrakt-osmotrakt_id").val(),
                    organ_id: $("#organ-organ_id").val(),
                    dopparams: JSON.stringify(dopparams),
                    buttonloadingid: "ChangeBuildMolDialog_apply"
                },
                success: function (data) {
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
            $("div.errordialog").text("Не выбрана организация");
        }

        return false;
    });

    $(document).on('click', ChangeBuildMolDialog + "_close", function (event) {
        $(ChangeBuildMolDialog).modal("hide");
    });

});