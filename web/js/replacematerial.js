function FillTrOsnov() {
    if ($("#trosnov-id_mattraffic").val() != "undefined") {
        $.ajax({
            url: "?r=Fregat%2Ftr-osnov%2Ffilltrosnov",
            type: "post",
            data: {mattraffic_id: $("#trosnov-id_mattraffic").val()},
            success: function (data) {
                var obj = JSON.parse(data);
                $("#material-material_tip").val(obj.material_tip);
                $("#material-material_name").val(obj.material_name);
                $("#material-material_writeoff").val(obj.material_writeoff);
                $("#authuser-auth_user_fullname").val(obj.auth_user_fullname);
                $("#dolzh-dolzh_name").val(obj.dolzh_name);
                $("#podraz-podraz_name").val(obj.podraz_name);
                $("#build-build_name").val(obj.build_name);
                $("#mattraffic_number_max").text("Не более " + obj.mattraffic_number);

                $('#material-material_tip, #material-material_name, #material-material_writeoff, #authuser-auth_user_fullname, #dolzh-dolzh_name, #podraz-podraz_name, #build-build_name').change(function () {
                    SetSession(this);
                });
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
    $("#authuser-auth_user_fullname").val('');
    $("#dolzh-dolzh_name").val('');
    $("#podraz-podraz_name").val('');
    $("#build-build_name").val('');
    $("#mattraffic_number_max").text('');

    $('#material-material_tip, #material-material_name, #material-material_writeoff, #authuser-auth_user_fullname, #dolzh-dolzh_name, #podraz-podraz_name, #build-build_name').change(function () {
        SetSession(this);
    });
}