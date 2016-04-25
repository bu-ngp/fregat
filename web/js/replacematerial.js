function FillTrOsnov() {
    if ($("#trosnov-id_mattraffic").val() != "undefined") {
        $.ajax({
            url: "?r=Fregat%2Ftr-osnov%2Ffilltrosnov",
            type: "post",
            data: {mattraffic_id: $("#trosnov-id_mattraffic").val()},
            //  async: false,
            success: function (data) {
                var obj = JSON.parse(data);
                $("#material-material_tip").val(obj.material_tip);
                $("#material-material_name").val(obj.material_name);
                $("#material-material_writeoff").val(obj.material_writeoff);
                $("#employee-id_person").val(obj.auth_user_fullname);
                $("#employee-id_dolzh").val(obj.dolzh_name);
                $("#employee-id_podraz").val(obj.podraz_name);
                $("#employee-id_build").val(obj.build_name);
                $("#mattraffic_number_max").text("Не более " + Math.round(obj.mattraffic_number));

                SetSessionEach([
                    $("#material-material_tip"),
                    $("#material-material_name"),
                    $("#material-material_writeoff"),
                    $("#employee-id_person"),
                    $("#employee-id_dolzh"),
                    $("#employee-id_podraz"),
                    $("#employee-id_build")
                ]);
            },
            error: function (data) {
                console.error("Ошибка FillTrOsnov()");
            }
        });
    }
}

function ClearTrOsnov() {
    $("#material-material_tip").val(0);
    $("#material-material_name").val('');
    $("#material-material_writeoff").val('');
    $("#employee-id_person").val('');
    $("#employee-id_dolzh").val('');
    $("#employee-id_podraz").val('');
    $("#employee-id_build").val('');
    $("#mattraffic_number_max").text('');

    SetSessionEach([
        $("#material-material_tip"),
        $("#material-material_name"),
        $("#material-material_writeoff"),
        $("#employee-id_person"),
        $("#employee-id_dolzh"),
        $("#employee-id_podraz"),
        $("#employee-id_build")
    ]);

    /*  SetSession($("#material-material_tip"));
     SetSession($("#material-material_name"));
     SetSession($("#material-material_writeoff"));
     SetSession($("#employee-id_person"));
     SetSession($("#employee-id_dolzh"));
     SetSession($("#employee-id_podraz"));
     SetSession($("#employee-id_build"));*/
}