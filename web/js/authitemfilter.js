$(document).on('ready pjax:success', function () {
    $('.filter_button').click(function (e) {
        e.preventDefault(); //for prevent default behavior of <a> tag.
        var tagname = $(this)[0].tagName;

        $('#Authitemfilter')
                .modal('show')
                .find('.modal-body')
                .load($(this).attr('href'), function () {
                    var formcontain = $("#Authitemfilter")[0].formcontain;

                    if (typeof formcontain !== "undefined") {
                        var obj = $.deserialize(formcontain);
                        $('[name = "AuthitemFilter[onlyrootauthitems]"]').prop('checked', obj.AuthitemFilter.onlyrootauthitems === "1" ? true : false);
                    }
                });
    });
});

$(document).on("click", "#authitemfilter_apply", function () {

    if ($("form.gridview-filter-form").find(".has-error").length)
    {
        return false;
    }

    $("#Authitemfilter").modal("hide");

    $("#authitemgrid").yiiGridView("applyFilter");

    return false;
});

$(document).on('click', "#authitemfilter_close", function (event) {
    $("#Authitemfilter").modal("hide");
});

$(document).on("beforeFilter", "#authitemgrid", function (event) {
    var formcontain = $("form#authitemfilter-form").serialize();
    var formgrid = $("form.gridview-filter-form");
    var input = $("input[name='AuthitemSearch[_filter]']");
    console.debug(formcontain)
    if (!input.length)
        formgrid.append($('<input type="hidden" class="form-control" name="AuthitemSearch[_filter]" value="" />').val(formcontain));
    else
        input.val(formcontain);

    $("#Authitemfilter")[0].formcontain = formcontain;

    return true;
});