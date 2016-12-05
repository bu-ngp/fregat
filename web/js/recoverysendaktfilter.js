var filtergrid = "#recoverysendaktgrid_gw";
var filtermodal = "#RecoverysendaktFilter";
var filtersearch = "RecoverysendaktSearch";

$(document).ready(function () {
    $(document).on('ready pjax:success', function () {
        $('.filter_button').click(function (e) {
            e.preventDefault(); //for prevent default behavior of <a> tag.
            $(filtermodal).modal('show').find('.modal-body').html('<div style="height: 150px; width: 100%; background: url(' + baseUrl + 'images/progress.svg) center center no-repeat; background-size: 20px;"></div>');
            $(filtermodal).modal('show').find('.modal-body').load($(this).attr('href'), function () {
                SetStyleFilterBehavior();
                GetScrollFilter("div.insideforms");

                $("div.insideforms").scroll(function () {
                    SetScrollFilter(this);
                });

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

    $(document).on('click', filtermodal + "_resetfilter, " + filtermodal + "_reset", function (event) {
        bootbox.confirm("Вы уверены, что хотите сбросить дополнительный фильтр?", function (result) {
            if (result) {
                $(filtermodal).modal("hide");
                $(filtermodal)[0].statusform = 0;
                $(filtergrid).yiiGridView("applyFilter");
            }
        });

    });

    $(document).on('click', filtermodal + "_close", function (event) {
        $(filtermodal).modal("hide");
    });

    $(document).on("beforeFilter", filtergrid, function (event) {
        $("div.insideforms input[type='text'].form-control").each(function (key, value) {
            $(value).val(($(value).val()).toUpperCase());
        });

        var formcontain = $("form" + filtermodal + "-form").serialize();
        var formgrid = $("form.gridview-filter-form");
        var input = $("input[name='" + filtersearch + "[_filter]']");

        if ($(filtermodal).length && $(filtermodal)[0].statusform === 1) {
            if (!input.length)
                formgrid.append($('<input type="hidden" class="form-control" name="' + filtersearch + '[_filter]" />').val(formcontain));
            else
                input.val(formcontain);
        } else if (input.length)
            input.remove();

        return true;
    });

    $(document).on("keyup", "input.searchfilterform", function () {
        $("div.insideforms").find("div.panelblock").hide();

        $("label").each(function (key, value) {
            var searchinput = ($("input.searchfilterform").val()).toUpperCase();
            var labelinput = $.trim(($(value).text()).toUpperCase());

            if ((labelinput).indexOf(searchinput) < 0)
                $(value).parent("div").hide();
            else {
                $(value).parentsUntil("div.insideforms", "div.panelblock").show();
                $(value).parent("div").show();
            }
        })
    });

});