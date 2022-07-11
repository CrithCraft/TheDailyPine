// Прочее - обьявление переменных
var active = document.activeElement; // активный элемент
var upload_active = false; // активирован ли блок для добавления изображений
var remove_active = false;
var preview_active = false;
var article;
var left_nav_open = false;
var page_state = "review";
document.getElementById("myArticle").style.display = "flex";
var selectedType = "post";
var removeEvent = false;

// Функция закрытия окна
function closewindow() {
    // цепляем поле окна
    let secPage = document.getElementById("second-page")
    // Скрываем окно через стили
    secPage.style.opacity="0";
    secPage.style.visibility="hidden";
    // Отмечаем выключенное состояние окна в переменной
    preview_active = false;
}

// Проверка на поддержку определенных CSS параматров
function cssPropertyValueSupported(prop, value) {
    var d = document.createElement('div');
    d.style[prop] = value;
    return d.style[prop] === value;
}

// Оптимизация для старых браузеров
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

// Убираем экран закрузки после загрузки основной страницы
window.addEventListener('load', function () {
    document.getElementById("loading-screen").style.animation = "2s open 1";
});

// Функция для показа превью фото поста
function showPreviewField(src) {
    // Если не запущено событие удаления, то
    if (removeEvent == false){
        // Цепляем в переменные нужные нам элементы
        let secPage = document.getElementById("second-page")
        let secPageArea = document.getElementsByClassName("area")[0];
        // Проверка на остутсвие события показа
        if(preview_active == false){
            // Показываем поле для показа с помощью стилей
            secPage.style.opacity="1";
            secPage.style.visibility="visible";
            secPageArea.innerHTML = "";
            // Подставляем изображение
            secPageArea.appendChild(document.createElement("img"));
            secPageArea.firstElementChild.src = src;
            secPageArea.firstElementChild.setAttribute("class", "preview-image")
            secPageArea.setAttribute("class", "area preview-mode")
            preview_active = true;
        }
    }
}

// Опции в боковом меню
let optionAdmin = document.getElementById("option-admin");
let optionFavorite = document.getElementById("option-favorite");
let optionUser = document.getElementById("option-user");
let optionAddPhoto = document.getElementById("option-add-photo");
// Кнопка выхода
let logoutButton = document.getElementsByClassName("logout-button")[0];

// Если пользователь не зарегистрировался стираем текст с личного кабинета
if (!(typeof(logoutButton) != 'undefined' && logoutButton != null)) {
    optionFavorite.style.display = "none";
    optionUser.style.display = "none";
    optionAddPhoto.style.display = "none";
}

// Функция открытия страниц в боковом меню
function opensidebar(option) {
    // Цепляем нужные нам элементы
    let left_nav = document.getElementById("left-nav");
    let tumb = document.getElementById("tumb");
    let article = document.getElementById("myArticle");
    let title_area = document.getElementById("title");
    let load_data = document.getElementById("articles");
    let user_page = document.getElementById("user-page");
    let add_photo_area = document.getElementById("add-photo-area");
    let search_area = document.getElementById("search-area");
    let page_title = document.getElementById("page-title");
    let more = document.getElementById("more");

    // Скрываем все элементы сайта
    title_area.style.display = "none";
    load_data.style.display = "none";
    load_data.style.overflowX = "unset";
    user_page.style.display = "none";
    add_photo_area.style.display = "none";
    search_area.style.display = "none";
    more.style.display = "none";

    // Обесцвечиваем все опции в боковом поле навигации
    document.getElementById("option-review").style.filter = "grayscale(100%)";
    document.getElementById("option-favorite").style.filter = "grayscale(100%)";
    document.getElementById("option-search").style.filter = "grayscale(100%)";
    document.getElementById("option-user").style.filter = "grayscale(100%)";
    document.getElementById("option-add-photo").style.filter = "grayscale(100%)";

    // Если есть опция кабинета администратора, то обесцвечиваем и её
    if(typeof(optionAdmin) != 'undefined' && optionAdmin != null){
        optionAdmin.style.filter = "grayscale(100%)";
    }

    // Если выбрана опция
    switch (option) {
        case 'review': { // Опция просмотр, загружаем фотографии сайта
            // Настраиваем стиль
            document.getElementById("option-review").style.filter = "none";
            selectedType = "post";
            document.getElementById("tumb-info").style.display = "none";
            title_area.style.display = "grid";
            load_data.style.display = "flex";
            more.style.display = "block";
            // Указываем заголовок
            page_title.firstElementChild.innerHTML = "Новое на сегодня";
            tumb.style.background = null;
            // Очищаем поле загрузки
            load_data.innerHTML = "";
            // Грузим посты
            load_posts("post", true);
            // Указываем состояние страницы
            page_state = 'review';
            // Завершаем условие
            break;
        }
        case 'favorite': { // Опция избранное, загружаем избранные посты пользователя
            document.getElementById("option-favorite").style.filter = "none";
            selectedType = "favorite";
            document.getElementById("tumb-info").style.display = "none";
            title_area.style.display = "grid";
            load_data.style.display = "flex";
            more.style.display = "block";
            page_title.firstElementChild.innerHTML = "Избранное вами";
            tumb.style.background = null;
            articles.innerHTML = "";
            load_posts("favorite", true);
            // Указываем состояние страницы
            page_state = 'favorite';
            break;
        }
        case 'search': { // Опция поиска, отображаем поле поиска
            // Настраиваем стиль
            document.getElementById("option-search").style.filter = "none";
            title_area.style.display = "grid";
            page_title.firstElementChild.innerHTML = "Поиск по тегам";
            document.getElementById("tumb-info").style.display = "none";
            tumb.style.background = null;
            search_area.style.display = "block";
            // Указываем состояние страницы
            page_state = 'search';
            // Подготавливаем поле для результатов поиска и выполняем поиск по полю поиска
            load_data.style.display = "flex";
            load_data.innerHTML = "";
            searchOutput(document.getElementById("search-input").value);
            break;
        }
        case 'user': { // Опция личный кабинет, отображаем поле личного кабинета
            document.getElementById("option-user").style.filter = "none";
            // Указываем состояние страницы
            selectedType = "uploaded";
            // Вызываем функцию для загрузки личного кабинета
            loadUserPage();
            break;
        }
        case 'add-photo': { // Опция добавить новый пост, отображаем форму для нового поста
            // Настраиваем стиль
            document.getElementById("option-add-photo").style.filter = "none";
            document.getElementById("tumb-info").style.display = "none";
            add_photo_area.style.display = "flex";
            tumb.style.background = null;
            title_area.style.display = "grid";
            page_title.firstElementChild.innerHTML = "Создание нового поста";
            // Указываем состояние страницы
            page_state = 'settings';
            break;
        }
        case 'admin': { // Опция кабинета администратора, отображаем кабинет администратора
            // Настраиваем стиль
            document.getElementById("option-admin").style.filter = "none";
            selectedType = "admin";
            document.getElementById("tumb-info").style.display = "none";
            title_area.style.display = "grid";
            page_title.firstElementChild.innerHTML = "Панель администратора";
            tumb.style.background = null;
            load_data.style.display = "flex";
            load_data.style.overflowX = "scroll";
            articles.innerHTML = "";
            // Создаем основу для таблицы
            articles.appendChild(document.createElement("table"));
            articles.firstElementChild.setAttribute("id", "article-table");
            // Загружаем таблицу
            loadTable("photos");
            // Указываем состояние страницы
            page_state = 'admin';
            break;
        }
        case 'moderator': { // Опция кабинета модератора, отображаем кабинет модератора
            // Настраиваем стиль
            document.getElementById("option-admin").style.filter = "none";
            selectedType = "admin";
            document.getElementById("tumb-info").style.display = "none";
            title_area.style.display = "grid";
            page_title.firstElementChild.innerHTML = "Панель модератора";
            tumb.style.background = null;
            load_data.style.display = "flex";
            articles.innerHTML = "";
            // Вызываем функцию для загрузки кабинета модератора
            loadModeratorPage();
            // Указываем состояние страницы
            page_state = 'admin';
            break;
        }
    }
}

// Функция для загрузки кабинета модератора
function loadModeratorPage() {
    var inProgress = false; // идет процесс загрузки
    if (!inProgress) {
        $.ajax({
            url: 'include/moderator.php', // путь к ajax-обработчику
            method: 'POST',
            beforeSend: function () {
                inProgress = true;
            }
        }).done(function (data) {
            data = jQuery.parseJSON(data); // данные в json
            if (data.length > 0) {
                document.getElementById("articles").innerHTML = "";
                // добавляем записи в блок в виде html
                $.each(data, function (index, data) {
                    $("#articles").append('<div class="big-photo-block" onclick="removePhotoSelected(this, ' + data.post_id +');">'
                        + '<div class="header">'
                        + '<div class="name">' + data.name + '</div>'
                        + '<button onclick="addFavorite('+data.post_id+');" class="add"></button>'
                        + '</div>'
                        + '<div class="image" style="background-image:url(/images/backgrounds/low_' + data.img + ') !important" onclick="showPreviewField('+"'"+'/images/backgrounds/' + data.img+"')"+'")"></div>'
                        + '<div class="sublabel">'
                        + 'Неопознано<div class="date">' + data.date + '</div>'
                        + '</div>'
                        + '<div class="hashlabel">'
                        + data.hashtag
                        + '</div>'
                        + '<div class="bottom-label">'
                        + '<div class="left">АВТОР: </div>'
                        + '<div class="author" onclick="loadUserData('+"'"+data.author+"'"+');">'
                        + data.author + '<div class="more"> больше...</div>'
                        + '</div>'
                        + '</div>'
                        + '<a href="/images/backgrounds/' + data.img + '" target="_blank"><button class="see-more">Загрузить</button></a>'
                        + '<button class="see-more" onClick="completeCheckPost('+ data.post_id +')">Допустить</button>'
                        + '</div>');
                });
                inProgress = false; // Заверщаем процесс загрузки
            }
        });
    }
}

// Функция для пропуска постов модератору
function completeCheckPost(id) {
    $.ajax({
        url: 'include/completeCheckPost.php', // путь к ajax-обработчику
        method: 'POST',
        data: {
            "id": id
        },
        error: function (jqXHR, exception) { // Если Ajax подхватит ошибку
            // Отправляем сообщение об ошибке
            popUpMessage("Что-то пошло не так, повторите попытку позже.", "console.png");
        },
        success: function() { // Если Ajax успешно выполнит запрос
            // Отправляем сообщение об успехе
            popUpMessage("Пост был успешно добавлен.", "console.png");
            // Перезагружаем кабинет модератора
            opensidebar('moderator');
        }
    });
}

// Загрузка пользовательской страницы.
function loadUserPage() {
    // Цепляем нужные нам элементы
    let article = document.getElementById("myArticle");
    let title_area = document.getElementById("title");
    let load_data = document.getElementById("articles");
    let posts = document.getElementById("articles");
    let user_page = document.getElementById("user-page");
    let tumb = document.getElementById("tumb");
    let title = document.getElementById("page-title");
    let nick_field = document.getElementById("myHeader").children[1].children[0].children[0];
    let more = document.getElementById("more");
    let hello_time_msg = "Приветствую, ";
    let nick = "Незарегистрированный";
    // Проверка времени
    let objDate = new Date();
    let hours = objDate.getHours();
    // Генерируем текст приветсвия
    if (hours >= 5 && hours < 13) {
        hello_time_msg = "Доброе утро, @";
    }
    else if (hours >= 13 && hours < 18) {
        hello_time_msg = "Добрый день, @";
    }
    else if (hours >= 18 && hours < 24) {
        hello_time_msg = "Добрый вечер, @";
    }
    else if (hours >= 0 && hours < 5) {
        hello_time_msg = "Спокойной ночи, @";
    }

    document.getElementById('hiMessage').innerHTML = hello_time_msg;
    // Получаем никнейм из шапки
    if (nick_field.innerHTML != "Вход") {
        nick = nick_field.innerHTML;
    }
    // Cтилизация
    title_area.style.display = "grid";
    load_data.style.display = "flex";
    user_page.style.display = "block";
    more.style.display = "block";
    // Заголовок
    title.firstElementChild.innerHTML = "Ваши посты";
    // Шапка
    document.getElementById("tumb-info").style.display = "flex";
    // Очистка и замена загруженных постов на нужные странице
    posts.innerHTML = "";
    load_posts("uploaded", true);
    // Устанавливаем состояние страницы на актуальное
    page_state = 'user';
}

// Функция показа краткой информации о пользователе
function loadUserData(user){
    // Если не включено состояние удаления
    if (removeEvent == false){
        // Показываем верхнее поле
        let secPage = document.getElementById("second-page")
        secPage.style.opacity="1";
        secPage.style.visibility="visible";
        // Стилизуем поле для показа данных
        let secPageArea = secPage.firstElementChild;
        secPageArea.setAttribute("class", "area");
        secPageArea.innerHTML = "";
        secPageArea.setAttribute("style","display: flex; height: min-content; flex-wrap: wrap; justify-content: space-between;");
        secPageArea.appendChild(document.createElement("div"));
        secPageArea.firstElementChild.setAttribute("class", "second-user-image-area");
        secPageArea.firstElementChild.appendChild(document.createElement("img"));
        secPageArea.firstElementChild.firstElementChild.setAttribute("id", "second-user-image");
        secPageArea.firstElementChild.setAttribute("class", "second-user-image-area");
        secPageArea.appendChild(document.createElement("div"));
        secPageArea.lastElementChild.setAttribute("id", "second-user-data");
        secPageArea.lastElementChild.setAttribute("style", "width: 50%; margin: auto;");
        document.getElementById("second-user-data").innerHTML = "";

        var inProgress = false; // идет процесс загрузки
        if (!inProgress) {
            $.ajax({
                url: 'include/userData.php', // путь к ajax-обработчику
                method: 'POST',
                data: {
                    "user": user
                },
                beforeSend: function () {
                    inProgress = true;
                }
            }).done(function (data) {
                data = jQuery.parseJSON(data); // данные в json
                if (data.length > 0) {
                    // добавляем записи в блок в виде html
                    $.each(data, function (index, data) {
                        $("#second-user-data").append('<h1>'+data.name+'</h1>'
                        +'<label>Роль:</label>'
                        +'<p>'+data.role+'</p>'
                        +'<label>Электронная почта:</label>'
                        +'<p>'+data.email+'</p>'
                        +'<label>Статус:</label>'
                        +'<p>'+data.status+'</p>'
                        +'</br>');
                        document.getElementById("second-user-image").src = data.avatar;
                    });
                    inProgress = false;
                }
            });
        }
    }
}

// Смена информации о пользователе
function changeBio() {
    // Цепляем нужные нам элементы
    let desc = document.getElementById("user-desc").value;
    let avatar = document.getElementById("user-avatar").value;
    let back = document.getElementById("user-back").value;
    $.ajax({
        url: 'include/bio.php', // путь к ajax-обработчику
        method: 'POST',
        data: {
            "desc": desc,
            "avatar": avatar,
            "back": back
        },
        error: function (jqXHR, exception) {
            popUpMessage("Что-то пошло не так, повторите попытку позже.", "console.png");
        },
        success: function() {
            // Отправляем сообщение об успехе
            popUpMessage("Для того чтобы изменения вступили в силу, мы перезагрузим страницу...", "user_icon_nav.png");
            // Перезагружаем страницу через 3,5 секунды
            setTimeout(function(){
                document.location.reload(true);
            }, 3500);
        }
    });
}

// Фцнкция для добавления в избранное
function addFavorite(id) {
    $.ajax({
        url: 'include/favorite.php', // путь к ajax-обработчику
        method: 'POST',
        data: {
            "id": id
        },
        error: function (jqXHR, exception) {
            popUpMessage("Что-то пошло не так, повторите попытку позже.", "console.png");
        },
        success: function(data) {
            data = jQuery.parseJSON(data);
            if(data.error != "") {
                popUpMessage(data.error, "favorite_icon.png");
            } else {
                popUpMessage("Пост был успешно добавлен в избраное.", "favorite_icon.png");
            }
        }
    });
}

// Фцнкция для удаления из избранного
function removeFavorite(id) {
    $.ajax({
        url: 'include/rm_favorite.php', // путь к ajax-обработчику
        method: 'POST',
        data: {
            "id": id
        },
        error: function (jqXHR, exception) {
            popUpMessage("Что-то пошло не так, повторите попытку позже.", "console.png");
        },
        success: function() {
            opensidebar('favorite');
            popUpMessage("Пост был успешно удален из избраного.", "favorite_icon.png");
        }
    });
}

// Функция для поиска выбранного слова
function searchSelect(input) {
    opensidebar('search');
    document.getElementById("search-input").value = input;
    searchOutput(input);
}

// Функция для загрузки таблицы
function loadTable(table){
    var inProgress = false; // идет процесс загрузки
    if (!inProgress) {
        $.ajax({
            url: 'include/table.php', // путь к ajax-обработчику
            method: 'POST',
            data: {
                "table": table,
            },
            beforeSend: function () {
                inProgress = true;
            }
        }).done(function (data) {
            data = jQuery.parseJSON(data); // данные в json
            if (data.length > 0) {
                // добавляем записи в блок в виде html
                $("#article-table").append('<tr></tr>');
                let data_row = data[0];

                $.each(Object.getOwnPropertyNames(data_row), function (index, value) {
                    $("#article-table tr:last-child").append('<th>'+value+'</th>');
                });
                $.each(data, function (index, data) {
                    $("#article-table").append('<tr></tr>');

                    $.each(data, function (index, value) {
                        $("#article-table tr:last-child").append('<td>'+value+'</td>');
                    });
                });
                inProgress = false;
            }
        });
    }
}

// Функция для поиска по определенному слову
function searchOutput(input){
    if(input != ""){
        if(input.charAt(0) != "#"){
            input = "#"+input;
        }
    }
    var inProgress = false; // идет процесс загрузки
    if (!inProgress) {
        $.ajax({
            url: 'include/search.php', // путь к ajax-обработчику
            method: 'POST',
            data: {
                "search": input
            },
            beforeSend: function () {
                inProgress = true;
            }
        }).done(function (data) {
            data = jQuery.parseJSON(data); // данные в json
            if (data.length > 0) {
                if(data.length > 1) {
                    popUpMessage("По вашему запросу, было надено "+data.length+" поста(ов).", "search_icon.png");
                }
                else {
                    popUpMessage("По вашему запросу, был найден всего один пост.", "search_icon.png");
                }
                document.getElementById("articles").innerHTML = "";
                // добавляем записи в блок в виде html
                $.each(data, function (index, data) {
                    $("#articles").append('<div class="big-photo-block" onclick="removePhotoSelected(this, ' + data.post_id +');">'
                        + '<div class="header">'
                        + '<div class="name">' + data.name + '</div>'
                        + '<button onclick="addFavorite('+data.post_id+');" class="add"></button>'
                        + '</div>'
                        + '<div class="image" style="background-image:url(/images/backgrounds/low_' + data.img + ') !important" onclick="showPreviewField('+"'"+'/images/backgrounds/' + data.img+"')"+'")"></div>'
                        + '<div class="sublabel">'
                        + 'Неопознано<div class="date">' + data.date + '</div>'
                        + '</div>'
                        + '<div class="hashlabel">'
                        + data.hashtag
                        + '</div>'
                        + '<div class="bottom-label">'
                        + '<div class="left">АВТОР: </div>'
                        + '<div class="author" onclick="loadUserData('+"'"+data.author+"'"+');">'
                        + data.author + '<div class="more"> больше...</div>'
                        + '</div>'
                        + '</div>'
                        + '<a href="/images/backgrounds/' + data.img + '" target="_blank"><button class="see-more">Загрузить</button></a>'
                        + '</div>');
                });
                inProgress = false;
            }
        });
    }
}

// Функция для включения режима удаления постов
function removePhotoSelect() {
    if (removeEvent == false){
        popUpMessage("Будьте внимательны, вы можете удалять только ваши посты.", "trash_image.png");
        removeEvent = true;
    } else {
        removeEvent = false;
    }
    refreshRemovePhotoSelect();
}

// Функция для удаления выбранного поста
function removePhotoSelected(object, id) {
    if(removeEvent==true) {
        removePhotoAjax(id,object);
    }
}

// Фунция для послания запроса по удалению поста
function removePhotoAjax(id, object) {
    $.ajax({
        url: 'include/remove.php', // путь к ajax-обработчику
        method: 'POST',
        data: {
            "id": id
        },
        error: function (jqXHR, exception) {
            popUpMessage("Что-то пошло не так, повторите попытку позже.", "console.png");
        },
        success: function(data) {
            data = jQuery.parseJSON(data);
            if(data.error != "") {
                popUpMessage(data.error, "trash_image.png");
            } else {
                popUpMessage("Пост был успешно удален.", "trash_image.png");
                object.remove();
            }
        }
    });
}

// Функция для перезагрузки стиля удаления для постов
function refreshRemovePhotoSelect() {
    if (removeEvent == true) {
        $( ".big-photo-block").addClass("shake");
        $( ".big-photo-block").fadeTo(50,0.7);
    }
    else {
        $( ".big-photo-block").removeClass("shake");
        $( ".big-photo-block").fadeTo(50,1);
    }
}

// Переменная для отсчета загрузки постов
var startFrom = 0;

// Функция для загрузки большего количества постов
function loadMorePosts() {
    var inProgress = false;
    var selectedType;
    if(page_state == "review"){
        selectedType = "post";
    } else if(page_state == "favorite"){
        selectedType = "favorite";
    } else if(page_state == "user"){
        selectedType = "uploaded";
    }
    if (selectedType == "favorite") {
        fav_func = "removeFavorite";
        fav_type = "remove";
    } else {
        fav_func = "addFavorite";
        fav_type = "add";
    }
    if (!inProgress) {
        $.ajax({
            url: 'include/ajax.items.php', // путь к ajax-обработчику
            method: 'POST',
            data: {
                "start": startFrom,
                "type": selectedType
            },
            beforeSend: function () {
                inProgress = true;
            }
        }).done(function (data) {
            data = jQuery.parseJSON(data); // данные в json
            if (data.length > 0) {
                if (data.length < 10) document.getElementById("more").style.display = "none";
                // добавляем записи в блок в виде html
                $.each(data, function (index, data) {
                    $("#articles").append('<div class="big-photo-block" onclick="removePhotoSelected(this, ' + data.post_id +');">'
                        + '<div class="header">'
                        + '<div class="name">' + data.name + '</div>'
                        + '<button onclick="addFavorite('+data.post_id+');" class="'+fav_type+'"></button>'
                        + '</div>'
                        + '<div class="image" style="background-image:url(/images/backgrounds/low_' + data.img + ') !important" onclick="showPreviewField('+"'"+'/images/backgrounds/' + data.img+"')"+'")"></div>'
                        + '<div class="sublabel">'
                        + 'Неопознано<div class="date">' + data.date + '</div>'
                        + '</div>'
                        + '<div class="hashlabel">'
                        + data.hashtag
                        + '</div>'
                        + '<div class="bottom-label">'
                        + '<div class="left">АВТОР: </div>'
                        + '<div class="author" onclick="loadUserData('+"'"+data.author+"'"+');">'
                        + data.author + '<div class="more"> больше...</div>'
                        + '</div>'
                        + '</div>'
                        + '<a href="/images/backgrounds/' + data.img + '" target="_blank"><button class="see-more">Загрузить</button></a>'
                        + '</div>');
                });
                inProgress = false;
                startFrom += 10;
            }
            refreshRemovePhotoSelect();
        });
    }
};

// Функция для загрузки постов
function load_posts(type, change_page) {
    var inProgress = false; // идет процесс загрузки
    startFrom = 0; // позиция с которой начинается вывод данных
    first_run = true;

    if (type == "favorite") {
        fav_func = "removeFavorite";
        fav_type = "remove";
    } else {
        fav_func = "addFavorite";
        fav_type = "add";
    }

    if (first_run) {
        if (!inProgress) {
            $.ajax({
                url: 'include/ajax.items.php', // путь к ajax-обработчику
                method: 'POST',
                data: {
                    "start": startFrom,
                    "type": selectedType
                },
                beforeSend: function () {
                    inProgress = true;
                }
            }).done(function (data) {
                data = jQuery.parseJSON(data); // данные в json
                if (data.length > 0) {
                    if (data.length < 10) document.getElementById("more").style.display = "none";
                    // добавляем записи в блок в виде html
                    $.each(data, function (index, data) {
                        $("#articles").append('<div class="big-photo-block" onclick="removePhotoSelected(this, ' + data.post_id +');">'
                            + '<div class="header">'
                            + '<div class="name">' + data.name + '</div>'
                            + '<button onclick="'+fav_func+'('+data.post_id+');" class="'+fav_type+'"></button>'
                            + '</div>'
                            + '<div class="image" style="background-image:url(/images/backgrounds/low_' + data.img + ') !important" onclick="showPreviewField('+"'"+'/images/backgrounds/' + data.img+"')"+'")"></div>'
                            + '<div class="sublabel">'
                            + 'Неопознано<div class="date">' + data.date + '</div>'
                            + '</div>'
                            + '<div class="hashlabel">'
                            + data.hashtag
                            + '</div>'
                            + '<div class="bottom-label">'
                            + '<div class="left">АВТОР: </div>'
                            + '<div class="author" onclick="loadUserData('+"'"+data.author+"'"+');">'
                            + data.author + '<div class="more"> больше...</div>'
                            + '</div>'
                            + '</div>'
                            + '<a href="/images/backgrounds/' + data.img + '" target="_blank"><button class="see-more">Загрузить</button></a>'
                            + '</div>');
                    });
                    inProgress = false;
                    startFrom += 10;
                }
            });
            first_run = false;
        }
    }

    if (change_page == true) {
        if (!inProgress) {
            $.ajax({
                url: 'include/ajax.items.php', // путь к ajax-обработчику
                method: 'POST',
                data: {
                    "start": startFrom,
                    "type": selectedType
                },
                beforeSend: function () {
                    inProgress = true;
                }
            }).done(function (data) {
                data = jQuery.parseJSON(data); // данные в json
                if (data.length > 0) {
                    if (data.length < 10) document.getElementById("more").style.display = "none";
                    // добавляем записи в блок в виде html
                    $.each(data, function (index, data) {
                        $("#articles").append('<div class="big-photo-block" onclick="removePhotoSelected(this, ' + data.post_id +');">'
                            + '<div class="header">'
                            + '<div class="name">' + data.name + '</div>'
                            + '<button class="add"></button>'
                            + '</div>'
                            + '<div class="image" style="background-image:url(/images/backgrounds/low_' + data.img + ') !important"></div>'
                            + '<div class="sublabel">'
                            + 'Неопознано<div class="date">' + data.date + '</div>'
                            + '</div>'
                            + '<div class="hashlabel">'
                            + '<div class = "hashtag">#mechanic</div>'
                            + '<div class = "hashtag" style="background-color: #389B88;">#air</div>'
                            + '</div>'
                            + '<div class="bottom-label">'
                            + '<div class="left">АВТОР: </div>'
                            + '<div class="author">'
                            + data.author + '<div class="more"> больше...</div>'
                            + '</div>'
                            + '</div>'
                            + '<a href="/images/backgrounds/' + data.img + '" target="_blank"><button class="see-more">Загрузить</button></a>'
                            + '</div>');
                    });
                    inProgress = false;
                    startFrom += 10;
                }
            });
        }
    };
}

// Цепляем элемент поля поиска
var input = document.getElementById("search-input");

// При написании чего-то в поле поиска выполняем следущее
input.oninput = function() {
    let load_data = document.getElementById("articles");
    load_data.innerHTML = "";
    searchOutput(input.value);
};

// Массив таблиц в БД
tables = [ "users", "photos", "hashtags", "favorite" ];
// Позиция загруженной таблицы
pos = 0;

// Если кнопка нажата
document.addEventListener('keydown', function(event) {
    // Если кнопка влево нажата загружаем таблицу предыдущую
    if(event.keyCode == 37) {
        if (pos > 0) pos-=1; else pos = 0;
        if (document.getElementById("article-table") != null){
            document.getElementById("article-table").innerHTML = "";
            loadTable(tables[pos]);
        }
    }
    // Если кнопка вправо нажата загружаем таблицу дальше
    if(event.keyCode == 39) {
        if (pos < 3) pos+=1; else pos = 3;
        if (document.getElementById("article-table") != null){
            document.getElementById("article-table").innerHTML = "";
            loadTable(tables[pos]);
        }
    }
});

// Инициализируем счетчик
initial = null;

// Функция для отправки сообщения
function popUpMessage(message, icon="none"){
    // Очищаем счетчик
    clearTimeout( initial );
    // Цепляем поле сообщения
    let messageBox = document.getElementById("message-box");
    // Ставим картинку если есть такая
    if(icon == "none") {
        messageBox.style.background = "whitesmoke";
    } else {
        messageBox.style.background = "url(/images/"+icon+"), whitesmoke";
    }
    // Подставляем сообщения
    messageBox.innerHTML = message;
    // Делаем поле не прозрачным
    messageBox.style.opacity = 1;
    // По истичению 3 секунд делаем поле прозрачным
    initial = setTimeout(function(){
        messageBox.style.opacity = 0;
    }, 3000);
}