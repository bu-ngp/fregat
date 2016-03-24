jQuery(document).ready(function ($) {
    $("body").on("beforeSubmit", "form#authitemfilter-form", function () {
        var form = $(this);
        var formcontain = form.serialize();
        // return false if form still have some validation errors
        if (form.find(".has-error").length)
        {
            return false;
        }
        // submit form
        var k = true;
        $.ajax({
            url: form.attr("action"),
            type: "post",
            data: formcontain,
            success: function (response)
            {
                $("#Authitemfilter").modal("hide");
                // $.pjax.reload({container:"#dynagrid-1-pjax"});  //Reload GridView
                $("body").on("beforeFilter", "#authitemgrid", function (event) {
                    if (k) {
                        var formgrid = $("form.gridview-filter-form");
                        var input = $("input[name='AuthitemSearch[_filter]']");
                        console.debug(formcontain)
                        if (!input.length)
                            formgrid.append($('<input type="hidden" class="form-control" name="AuthitemSearch[_filter]" value="" />').val(formcontain));
                        else
                            input.val(formcontain);

                        $("#Authitemfilter")[0].formcontain = formcontain;
                        k = false;
                    }
                    return true;
                });
                $("#authitemgrid").yiiGridView("applyFilter");
            },
            error: function ()
            {
                console.log("internal server error");
            }
        });
        return false;
    });

    $("#authitemfilter_close").on('click', function (event) {
        $("#Authitemfilter").modal("hide");
    });

});

