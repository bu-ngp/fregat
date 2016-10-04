var filtergrid = "#materialgrid_gw";
var filtermodal = "#SendOsmotraktDialog";
var filtersearch = "MaterialSearch";

$(document).ready(function () {
    $(document).on('ready pjax:success', function () {
        $('.osmotraktsend').click(function (e) {
            e.preventDefault(); //for prevent default behavior of <a> tag.
            $(filtermodal).modal('show').find('.modal-body').html('<div style="height: 150px; width: 100%; background: url(images/progress.svg) center center no-repeat; background-size: 20px;"></div>');
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
        /*
         $(filtermodal)[0].statusform = 1;
         $(filtermodal).modal("hide");
         $(filtergrid).yiiGridView("applyFilter");

         */
        var dopparams;
        dopparams = {id: $("#osmotrakt-osmotrakt_id").val()};
        if ($("#organ-organ_id").val() != "")
            $.ajax({
                url: "?r=Fregat%2Fosmotrakt%2Fosmotrakt-send",
                type: "post",
                data: {
                    osmotrakt_id: $("#osmotrakt-osmotrakt_id").val(),
                    organ_id: $("#organ-organ_id").val(),
                    dopparams: JSON.stringify(dopparams),
                    buttonloadingid: "SendOsmotraktDialog_apply"
                },
                success: function (data) {
                    setTimeout(function () {
                        $.ajax({
                            url: "?r=site%2Fdelete-tmp-file",
                            type: "post",
                            data: {filename: response},
                            async: true
                        });
                    }, 5000);
                    $(filtermodal).modal("hide");
                },
                error: function (err) {
                    $("div.errordialog").show();
                    if ((err.responseText).indexOf("Internal Server Error (#500): ") >= 0)
                        $("div.errordialog").text((err.responseText).substring(30));
                    else
                        $("div.errordialog").text(err.responseText);
                }
            });
        else
            $("div.errordialog").text("Не выбрана организация");


        return false;
    });

    $(document).on('click', filtermodal + "_close", function (event) {
        $(filtermodal).modal("hide");
    });

    /*  $(document).on("beforeFilter", filtergrid, function (event) {
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
     });*/

});