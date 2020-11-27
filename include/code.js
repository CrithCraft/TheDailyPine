 // Прочее
var active = document.activeElement; // активный элемент
var upload_active = false; // активирован ли блок для добавления изображений
var remove_active = false;
var preview_active = false;

var article;
var left_nav;
var left_nav_open = false;

var page_state = "review";

function cssPropertyValueSupported(prop, value) {
    var d = document.createElement('div');
    d.style[prop] = value;
    return d.style[prop] === value;
}

if (cssPropertyValueSupported('display', 'flex') == false) {
    document.getElementById("myHeader").style.backgroundColor = "white";
    document.getElementById("blur-header").style.display = "none";
    document.getElementById("tumb").style.width = "100%";
    document.getElementById("tumb").style.height = "300px";
    document.getElementById("myArticle").style.marginLeft = "70px";
    document.getElementById("myFooter").style.marginLeft = "70px";
    document.getElementById("myAside").style.marginTop = "0px";
    document.getElementById("left-nav").style.marginTop = "0px";
    document.getElementById("add-photo-area").style.marginLeft = "70px";
    document.getElementById("loading-screen").style.display = "none";
}

window.addEventListener('load', function () {
    document.getElementById("loading-screen").style.animation = "2s open 1";
});

// function showRemoveField(id) {
//     if(remove_active == false){
//         document.getElementById("second-level").style.opacity="1";
//         document.getElementById("second-level").style.visibility="visible";
//         document.getElementById("dialog-box").style.visibility="visible";
//         document.getElementById("trash-accept").value=id;
//         remove_active = true;
//     }
//     else {
//         document.getElementById("second-level").style.opacity="0";
//         document.getElementById("second-level").style.visibility="hidden";
//         document.getElementById("dialog-box").style.visibility="hidden";
//         document.getElementById("trash-accept").value=0;
//         remove_active = false;
//     }
// }

// function showPreviewField(image) {
//     var img =  document.getElementById("photo-image");
//     var width = img.offsetWidth;
//     var height = img.offsetHeight;
//
//     if(preview_active == false){
//         document.getElementById("second-level").style.opacity="1";
//         document.getElementById("second-level").style.visibility="visible";
//         document.getElementById("preview-field").style.visibility="visible";
//         document.getElementById("photo-image").src=image;
//         if (width >= height){
//             document.getElementById("photo-image").width = "100%";
//             document.getElementById("photo-image").height = "auto";
//         }
//         else {
//             document.getElementById("photo-image").height = "100%";
//             document.getElementById("photo-image").width = "auto";
//         }
//         preview_active = true;
//     }
//     else {
//         document.getElementById("second-level").style.opacity="0";
//         document.getElementById("second-level").style.visibility="hidden";
//         document.getElementById("preview-field").style.visibility="hidden";
//         document.getElementById("photo-image").src=image;
//         preview_active = false;
//     }
// }

function opensidebar(option) {
    left_nav = document.getElementById("left-nav");
    if(left_nav_open == false){
        left_nav.style.width = "289px";
        left_nav.style.padding = "9px 30px";
        left_nav.style.borderRight = "solid 1px #DDDDDD";
        left_nav_open = true;
    }
    else {
        left_nav.style.width = "0px";
        left_nav.style.padding = "9px 0px";
        left_nav.style.borderRight = "0px";
        left_nav_open = false;
    }
    switch (option) {
        case 'review': {
            if (page_state == 'review') {

            }
            page_state = 'review';
        }
        case 'favorite': {
            if (page_state == 'favorite') {

            }
            page_state = 'favorite';
        }
        case 'search': {
            if (page_state == 'search') {

            }
            page_state = 'search';
        }
        case 'user': {
            if (page_state == 'user') {

            }
            page_state = 'user';
        }
        case 'settings': {
            if (page_state == 'settings') {

            }
            page_state = 'settings';
        }
    }
}

function load_posts(type) {
    var inProgress = false; // идет процесс загрузки
    var startFrom = 0; // позиция с которой начинается вывод данных
    first_run = true;

    if(first_run){
    if (!inProgress) {
        $.ajax({
            url: 'include/ajax.items.php', // путь к ajax-обработчику
            method: 'POST',
            data: {
                "start" : startFrom,
                "type": type
            },
            beforeSend: function() {
                inProgress = true;
            }
        }).done(function(data){
            data = jQuery.parseJSON(data); // данные в json
            if (data.length > 0){
                // добавляем записи в блок в виде html
                $.each(data, function(index, data){
                    $("#articles").append('<div class="big-photo-block">'
                    +'<div class="header">'
                        +'<div class="name">'+data.name+'</div>'
                        +'<button class="add"></button>'
                    +'</div>'
                    +'<div class="image" style="background-image:url(/images/backgrounds/low_'+data.image+') !important"></div>'
                    +'<div class="sublabel">'
                        +'Неопознано<div class="date">'+data.date+'</div>'
                    +'</div>'
                    +'<div class="hashlabel">'
                        +'<div class = "hashtag">#mechanic</div>'
                        +'<div class = "hashtag" style="background-color: #389B88;">#air</div>'
                    +'</div>'
                    +'<div class="bottom-label">'
                        +'<div class="left">AUTHOR: </div>'
                        +'<div class="author">'
                            +data.author+'<div class="more"> more...</div>'
                        +'</div>'
                    +'</div>'
                    +'<a href="/images/backgrounds/'+data.image+'"><button class="see-more">Загрузить</button></a>'
                +'</div>');
                });
                inProgress = false;
                startFrom += 10;
            }
        });
        first_run = false;
    }
    }

    $('#more').click(function() {
        if (!inProgress) {
            $.ajax({
                url: 'include/ajax.items.php', // путь к ajax-обработчику
                method: 'POST',
                data: {
                    "start" : startFrom,
                    "type": type
                },
                beforeSend: function() {
                    inProgress = true;
                }
            }).done(function(data){
                data = jQuery.parseJSON(data); // данные в json
                if (data.length > 0){
                    // добавляем записи в блок в виде html
                    $.each(data, function(index, data){
                        $("#articles").append('<div class="big-photo-block">'
                        +'<div class="header">'
                            +'<div class="name">'+data.name+'</div>'
                            +'<button class="add"></button>'
                        +'</div>'
                        +'<div class="image" style="background-image:url(/images/backgrounds/low_'+data.image+') !important"></div>'
                        +'<div class="sublabel">'
                            +'Неопознано<div class="date">'+data.date+'</div>'
                        +'</div>'
                        +'<div class="hashlabel">'
                            +'<div class = "hashtag">#mechanic</div>'
                            +'<div class = "hashtag" style="background-color: #389B88;">#air</div>'
                        +'</div>'
                        +'<div class="bottom-label">'
                            +'<div class="left">AUTHOR: </div>'
                            +'<div class="author">'
                                +data.author+'<div class="more"> more...</div>'
                            +'</div>'
                        +'</div>'
                        +'<a href="/images/backgrounds/'+data.image+'"><button class="see-more">Загрузить</button></a>'
                    +'</div>');
                    });
                    inProgress = false;
                    startFrom += 10;
                }
            });
        }
    });
}

// $(window).scroll(function () {
//     if ($(window).scrollTop() >= 700) {
//         article = document.body.childNodes[21];
//         title = document.body.childNodes[19];
//         article.style.padding = "0px";
//         title.style.padding = "0px";
//     }
//     else {
//         article = document.body.childNodes[21];
//         title = document.body.childNodes[19];
//         article.style.padding = "0px 100px";
//         title.style.padding = "0px 100px";
//     }
// });
