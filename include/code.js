// формы для отправки фото и его данных
var upload_file = document.getElementById('upload-photo-input');
var upload_name = document.getElementById('upload-input-name');
var upload_author = document.getElementById('upload-input-author');
var upload_description = document.getElementById('upload-input-description');

// Прочее
var active = document.activeElement; // активный элемент
var upload_active = false; // активирован ли блок для добавления изображений
var remove_active = false;
var preview_active = false;

var article;
var title;

function showAddField() {
    // Выдвигаем поле добавления фото в группу фотографий
    if(upload_active == false){
        document.getElementById("add-photo-block").style.height="320px";
        document.getElementById("add-photo-block").style.margin="15px 0px";
        document.getElementById("add-photo-block").style.marginBottom="34px";
        document.getElementById("add-photo-block").style.padding="18px 23px";
        document.getElementById("add-photo-block").style.opacity="1";
        document.getElementById("add-photo-block").style.visibility="visible";
        upload_active = true;
    }
    else {
        document.getElementById("add-photo-block").style.opacity="0";
        document.getElementById("add-photo-block").style.visibility="hidden";
        document.getElementById("add-photo-block").style.height="0px";
        document.getElementById("add-photo-block").style.margin="0px";
        document.getElementById("add-photo-block").style.padding="0px";
        upload_active = false;
    }
}

function showRemoveField(id) {
    if(remove_active == false){
        document.getElementById("second-level").style.opacity="1";
        document.getElementById("second-level").style.visibility="visible";
        document.getElementById("dialog-box").style.visibility="visible";
        document.getElementById("trash-accept").value=id;
        remove_active = true;
    }
    else {
        document.getElementById("second-level").style.opacity="0";
        document.getElementById("second-level").style.visibility="hidden";
        document.getElementById("dialog-box").style.visibility="hidden";
        document.getElementById("trash-accept").value=0;
        remove_active = false;
    }
}

function showPreviewField(image) {
    var img =  document.getElementById("photo-image");
    var width = img.offsetWidth;
    var height = img.offsetHeight;


    if(preview_active == false){
        document.getElementById("second-level").style.opacity="1";
        document.getElementById("second-level").style.visibility="visible";
        document.getElementById("preview-field").style.visibility="visible";
        document.getElementById("photo-image").src=image;
        if (width >= height){
            document.getElementById("photo-image").width = "100%";
            document.getElementById("photo-image").height = "auto";
        }
        else {
            document.getElementById("photo-image").height = "100%";
            document.getElementById("photo-image").width = "auto";
        }
        preview_active = true;
    }
    else {
        document.getElementById("second-level").style.opacity="0";
        document.getElementById("second-level").style.visibility="hidden";
        document.getElementById("preview-field").style.visibility="hidden";
        document.getElementById("photo-image").src=image;
        preview_active = false;
    }
}

function openPicBlock(obj) {
    var picture_block = obj.parentNode;
    var picture_image = picture_block.childNodes[0]; 
    var picture_text = picture_block.childNodes[1];
    if (picture_text.style.height == "378.3px") {
        picture_text.style.height = "0px";
        picture_block.style.boxShadow = "unset";
        picture_image.style.boxShadow = "unset";
    }
    else {
        picture_text.style.height = "378.3px";
        picture_block.style.boxShadow = "0px 4px 16px 8px rgba(0,0,0,0.08)";
        picture_image.style.boxShadow = "0px 8px 32px 8px rgba(0,0,0,0.23)";
    }
        

}

$(window).scroll(function () {
    if ($(window).scrollTop() >= 700) {
        article = document.body.childNodes[21];
        title = document.body.childNodes[19];
        article.style.padding = "0px";
        title.style.padding = "0px";
    }
    else {
        article = document.body.childNodes[21];
        title = document.body.childNodes[19];
        article.style.padding = "0px 100px";
        title.style.padding = "0px 100px";
    }
});