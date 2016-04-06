jQuery(document).ready(function ($) {
    $(document).on('ready pjax:success', function () {
        $('.filter_button').click(function (e) {
            e.preventDefault(); //for prevent default behavior of <a> tag.
            var tagname = $(this)[0].tagName;
            //   $('#Authitemfilter').unbind('show.bs.modal');

            $('#Authitemfilter')/*.on('shown.bs.modal', function () {})*/.modal('show')
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
        console.debug(form.attr("action"))

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

        console.debug("1");
        return false;
    });

    $("#authitemfilter_close").on('click', function (event) {
        $("#Authitemfilter").modal("hide");
    });
});