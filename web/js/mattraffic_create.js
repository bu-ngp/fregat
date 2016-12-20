function DialogBuildAddOpen() {
    $("#ChangeBuildMolDialog").modal('show');
}

$(document).on('click', "Build_close", function (event) {
    console.debug("GGG")
    $("#ChangeBuildMolDialog").modal("hide");
});

$(document).ready(function () {

});