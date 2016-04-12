var filtergrid = "#authitemgrid_gw";
var filtermodal = "#AuthitemFilter";
var filtersearch = "AuthitemSearch";

$(document).on('ready pjax:success', function () {
    $('.filter_button').click(function (e) {
        e.preventDefault(); //for prevent default behavior of <a> tag.
        $(filtermodal).modal('show').find('.modal-body').load($(this).attr('href'));
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