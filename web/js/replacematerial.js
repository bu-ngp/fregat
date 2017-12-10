function FillTrOsnov() {
    if ($("#trosnov-id_mattraffic").val() != "") {
        $.ajax({
            url: baseUrl + "Fregat/tr-osnov/filltrosnov",
            type: "post",
            data: {mattraffic_id: $("#trosnov-id_mattraffic").val()},
            success: function (data) {
                var obj = JSON.parse(data);
                $("#trosnov-id_cabinet").prop("disabled", false);
                $("#trosnov-id_cabinet").next("span").next(".input-group-btn").find("a").removeClass("disabled");

                var href = $("#trosnov-id_cabinet").next("span").next(".input-group-btn").find("a").attr("href");
                href = href.match(/(&id_mattraffic=.*)/)
                    ? href.replace(/(&id_mattraffic=.*)/, '&id_mattraffic=' + $("#trosnov-id_mattraffic").val())
                    : href + '&id_mattraffic=' + $("#trosnov-id_mattraffic").val();
                $("#trosnov-id_cabinet").next("span").next(".input-group-btn").find("a").attr("href", href);

                $("#material-material_tip").val(obj.material_tip);
                $("#material-material_name").val(obj.material_name);
                $("#material-material_writeoff").val(obj.material_writeoff);
                $("#material-material_install_cabinet").val(obj.material_install_cabinet);
                $("#authuser-auth_user_fullname").val(obj.auth_user_fullname);
                $("#dolzh-dolzh_name").val(obj.dolzh_name);
                $("#podraz-podraz_name").val(obj.podraz_name);
                $("#build-build_name").val(obj.build_name);
                $("#mattraffic_number_max").text("Не более " + obj.mattraffic_number);
            },
            error: function (data) {
                console.error("Ошибка FillTrOsnov()");
            }
        });
    } else
        ClearTrOsnov()
}

function ClearTrOsnov() {
    $("#trosnov-id_cabinet").val('').trigger('change');
    $("#trosnov-id_cabinet").prop("disabled", true);
    $("#trosnov-id_cabinet").next("span").next(".input-group-btn").find("a").addClass("disabled");
    $("#material-material_tip").val(0);
    $("#material-material_name").val('');
    $("#material-material_writeoff").val('');
    $("#material-material_install_cabinet").val('');
    $("#authuser-auth_user_fullname").val('');
    $("#dolzh-dolzh_name").val('');
    $("#podraz-podraz_name").val('');
    $("#build-build_name").val('');
    $("#mattraffic_number_max").text('');
}

function MatvidCount() {
    if ($("#trosnov-id_mattraffic").val() != "" && $("#trosnov-id_cabinet").val() != "") {
        $.ajax({
            url: baseUrl + "Fregat/tr-osnov/matvid-count",
            type: "post",
            data: {mattraffic_id: $("#trosnov-id_mattraffic").val(), cabinet_id: $("#trosnov-id_cabinet").val()},
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.message != "") {
                    $(".alert-matvid").text(obj.message);
                }
            },
            error: function (data) {
                console.error("Ошибка MatvidCount()");
            }
        });
    }
}

$(document).ready(function () {
    FillTrOsnov();
    MatvidCount();

    $("#trosnov-id_cabinet").on("change", function (e) {
        if ($(this).val() == "") {
            $('.alert-matvid').text('После заполнения инвентарного номера и кабинета здесь будет отображаться количество установленного вида материальнной ценности в заданный кабинет');
        } else {
            MatvidCount();
        }
    })
});