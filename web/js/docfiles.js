/**
 * Created by sysadmin on 11.10.2016.
 */
function UploadedFiles(gridid, event, data) {
    $('#'+ event.target.id).fileinput('clear');
    $('#'+ event.target.id).fileinput('unlock');

    $.pjax.reload({container: "#"+gridid+"-pjax"});
}