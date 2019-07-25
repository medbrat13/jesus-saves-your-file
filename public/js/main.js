// ========== CSS CLASSES ==========
/**
 * Класс, скрывающий элемент
 * @type {string}
 */
const collapsingClass = 'element-is-collapsed';

/**
 * Класс, окрашивающий активную радиокнопку
 * @type {string}
 */
const checkedClass = 'files__filters__list__item__label--checked';

/**
 * Класс активной ссылки главного меню
 * @type {string}
 */
const activeClass = 'main-nav__nav__item--active';



// ========== ELEMENTS ==========

/**
 * Главная
 * @type {HTMLElement}
 */
const index = document.getElementById('index');

/**
 * Главное меню
 * @type {HTMLElement}
 */
const nav = document.getElementById('nav');

/**
 * Скрытое главное меню
 * @type {HTMLElement}
 */
const collapsedNav = document.getElementById('collapsed-nav');

/**
 * Скрытый список фильтров
 * @type {HTMLElement}
 */
const filters = document.getElementById('filters-list');

/**
 * Модальное окно с информацией о файле
 * @type {HTMLElement}
 */
const popupContainer = document.getElementById('popup-container');

/**
 * Темный фон
 * @type {HTMLElement}
 */
const blurBg = document.getElementById('blur-bg');

/**
 * Модальное окно для создания альбома
 * @type {HTMLElement}
 */
const createAlbumPopup = document.getElementById('create-album-popup');

/**
 * Модальное окно для показа ошибки при загрузке файла
 * @type {HTMLElement}
 */
const uploadErrorPopup = document.getElementById('upload-error');

/**
 * Радиокнопки
 * @type {HTMLCollectionOf<Element>}
 */
const inputs = document.getElementsByClassName('files__filters__list__item__input');

/**
 * Прогресс-бар
 * @type {HTMLElement}
 */
const progressBar = document.getElementById('progress-bar');

/**
 * Подмена прогресс-бара для стилизации
 * @type {HTMLElement}
 */
const virtualProgressBar = document.getElementById('virtual-progress-bar');

/**
 * Поле для загрузки файла
 * @type {HTMLElement}
 */
const input = document.getElementById('file-input');

/**
 * Анимированный грузовик в прогресс-баре
 * @type {HTMLElement}
 */
const car = document.getElementById('progress-bar-car');

/**
 * Список ссылок из главного меню
 * @type {HTMLCollectionOf<Element>}
 */
const links = document.getElementsByClassName('main-nav__nav__item');

/**
 * Список файловых альбомов
 * @type {HTMLElement}
 */
const albums = document.getElementById('albums');

/**
 * Пункт меню "создать альбом"
 * @type {HTMLElement}
 */
const createAlbumOption = document.getElementById('create-album-option');

/**
 * Комментарий к файлу
 * @type {HTMLElement}
 */
const comment = document.getElementById('comment');

/**
 * Поле загрузки файла
 * @type {HTMLElement}
 */
const dropBox = document.getElementById('drop-box');

/**
 * Часть URL после site.com/
 * @type {string}
 */
const activeLinkPath = location.pathname;

/**
 * URL
 * @type {Url}
 * @type {Url}
 */
const baseUrl = new Url();


// ========== OPEN & CLOSE FUNCTIONS
/**
 * Переключатель видимости элемента по клику на его кнопку
 * @param elem Элемент, по которому кликнули
 */
const showNHide = (elem)  => {

    const addOrRemove = (elem, className) => {
        if (elem.classList.contains(className)) {
            elem.classList.remove(className);
        } else if (!elem.classList.contains(className)) {
            elem.classList.add(className);
        }
    };

    if (elem.id === 'nav-toggler') {
        addOrRemove(collapsedNav, collapsingClass);
    } else if (elem.id === 'filters-list-toggler') {
        addOrRemove(filters, collapsingClass);
    }

};

/**
 * Открывает окно с ошибкой загрузки
 * @param text
 */
const openUploadErrorPopup = text => {
    document.getElementById('error-text').innerText = text;
    uploadErrorPopup.classList.remove(collapsingClass);
};

/**
 * Открывает модальное файловое окно и фон позади него
 * и выводит информацию о файле
 * @param file
 */
const viewDetails = file => {
    const insertFileIcon = () => {
        const fileFormatIconsDir = '/images/file-format-icons';
        const ext = file.name.split('.').pop();

        const formData = new FormData();
        formData.append('icon', fileFormatIconsDir + '/' + ext + '.png');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://jsyf.ru/files' + location.search);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.send(formData);

        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {

                if (xhr.responseText === '1') {
                    document.getElementById('file-icon').innerHTML =
                        '<img class="file-popup__file-icon__img" src="' + fileFormatIconsDir + '/' + ext + '.png' + '" alt="file-format-icon">';
                } else {
                    document.getElementById('file-icon').innerHTML =
                        '<img class="file-popup__file-icon__img" src="' + fileFormatIconsDir + '/' + '.png' + '" alt="file-format-icon">';
                }
            }
        }
    };

    const getFileName = () => {
        return prepareName(file.name);
    };

    const getSize = () => {
        return prepareSize(file.size);
    };

    insertFileIcon();

    document.getElementById('file-info').innerHTML = '' +
        '<p class="file-popup__file-info__name">' + getFileName() + '</p>' +
        '<p class="file-popup__file-info__info">' +
        '   <span>'+ getSize() +'</span><br>' +
        '</p>';

    popupContainer.classList.remove(collapsingClass);
};

/**
 * Открывает модальное окно для создания альбома и задает фону больший z-index
 * @param albumsList Список существующих альбомов
 */
const openCreateAlbumPopup = albumsList => {
    if (albumsList.value === 'create-album') {
        blurBg.style.zIndex = '215';
        blurBg.onclick = closeCreateAlbumPopup;
        createAlbumPopup.classList.remove(collapsingClass);
    }
};

/**
 * Закрывает модальное файловое окно и фон позади него
 */
const closePopup = () => {
    popupContainer.classList.add(collapsingClass);
};

/**
 * Закрывает модальное окно создания альбома
 */
const closeCreateAlbumPopup = () => {
    blurBg.style.zIndex = '205';
    blurBg.onclick = closePopup;
    createAlbumPopup.classList.add(collapsingClass);
};

/**
 * Закрывает модальное окно с ошибкой загрузки
 */
const closeUploadErrorPopup = () => {
    uploadErrorPopup.classList.add(collapsingClass);
};


// ========== UTILS ==========

/**
 * Подготавливает имя файла к выводу на экран
 * @param name
 * @return {string|*}
 */
const prepareName = name => {
    if (name.length <= 34) return name;

    let preparedName = name.split('');

    for (;;) {
        preparedName = preparedName.join('');
        preparedName = preparedName.split('');
        let middleLetter = Math.round(preparedName.length / 2) - 1;

        if (preparedName.length <= 34) {
            preparedName[middleLetter] = preparedName[middleLetter].replace(/./, '...');
            preparedName = preparedName.join('');
            break;
        } else {
            delete preparedName[middleLetter];
            delete preparedName[middleLetter + 1];
        }
    }

    return preparedName;
};

/**
 * Подготавливает размер файла к выводу на экран
 * @param size
 * @return {string}
 */
const prepareSize = size => {

    let unit = '';
    let preparedSize = 0;

    if (size < 100) {
        preparedSize = size;
        unit = 'Bytes';
    } else if (size < 1000000) {
        preparedSize = (size / 1000).toFixed(1);
        unit = 'KB';
    } else {
        preparedSize = (size / 1000000).toFixed(1);
        unit = 'MB';
    }

    return 'Размер ' + preparedSize + ' ' + unit;
};


// ========== MAIN ==========

// Навигация

/**
 * Установка активной ссылки
 */
for (let i = 0; i < links.length; i++) {
    let link = links[i];
    let linkHref = link.firstElementChild.pathname;
    if (activeLinkPath === linkHref) {
        link.classList.add(activeClass);
    }
}


// Фильтрация

/**
 * Добавление активного класса радиокнопкам, отмеченным checked, на основе get-параметров при загрузке страницы
 */
if (baseUrl.query.files || baseUrl.query.sort) {
    for (let i = 0; i < inputs.length; i++) {

        let input = inputs.item(i);
        input.checked = false;

        const filesParam = baseUrl.query.files;
        const sortParam  = baseUrl.query.sort;

        if (input.value === filesParam|| input.value ===  sortParam) {
            input.checked = true;
        }

        if (inputs.item(i).checked) {
            inputs.item(i).parentElement.classList.add(checkedClass);
        }
    }
} else {
    for (let i = 0; i < inputs.length; i++) {
        if (inputs.item(i).checked) {
            inputs.item(i).parentElement.classList.add(checkedClass);
        }
    }
}

/**
 * Переключатель активного класса для радиокнопок
 * @param elem Элемент, по которому кликнули
 */
const selectOption = elem => {
    const name = elem.childNodes[1].name;
    const options = document.getElementsByName(name);

    if (!elem.classList.contains(checkedClass) && elem.childNodes[1].checked) {

        for (let i = 0; i < options.length; i++) {
            options.item(i).parentElement.classList.remove(checkedClass);
        }

        elem.classList.add(checkedClass);
    }
};

/**
 * Формирует GET-параметризованную часть строки URI в зависимости от нажатого фильтра,
 * отправляет запрос, получает отсортированный список файлов и выводит его на экран
 */
const sort = () => {
    const sortingUrl = new Url();
    const getData = () => {
        const xhr = new XMLHttpRequest();

        xhr.open('GET', location.href);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.send();

        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('files-list').innerHTML = xhr.responseText;
            }
        }
    };

    if (!sortingUrl.query.files || !sortingUrl.query.sort) {

        for (let i = 0; i < inputs.length; i++) {
            if (inputs.item(i).checked) {
                inputs.item(i).parentElement.classList.add(checkedClass);
                sortingUrl.query[inputs.item(i).name] = inputs.item(i).value;
            }
        }

        history.pushState(null, null, sortingUrl.toString());
        getData();
        return;
    }

    const checkedRadio = document.querySelectorAll('input[type=radio]:checked');

    for (let i = 0; checkedRadio.length > i; i++) {
        let radioName = checkedRadio.item(i).name;
        let radioValue = checkedRadio.item(i).value;

        if (sortingUrl.query[radioName] && sortingUrl.query[radioName] !== radioValue) {
            sortingUrl.query[radioName] = radioValue;
        }
    }

    history.pushState(null, null, sortingUrl.toString());
    getData();
};


// Поиск

const search = () => {

};


// Drag and Drop

/**
 * Анимация при подносе файла в зону загрузки
 */
const droppingIsAvailable = (event) => {
    event.preventDefault();
    dropBox.setAttribute('style', 'background-color: #ff3546; transition: .15s linear; font-size: 0;');
    nav.setAttribute('style', 'background-color: #b93547; transition: .15s linear');
    index.setAttribute('style', 'background-color: #b93547; transition: .15s linear');
    document.getElementById('drop-box-text').setAttribute('style', 'font-size: 0; transition: .15s linear');
};

/**
 * Анимация при подносе файла в зону загрузки
 */
const droppingIsUnavailable = (event) => {
    event.preventDefault();
    dropBox.setAttribute('style', 'background-color: #dc3546; transition: .15s linear;');
    nav.setAttribute('style', 'background-color: #dc3546; transition: .15s linear');
    index.setAttribute('style', 'background-color: #dc3546; transition: .15s linear');
    document.getElementById('drop-box-text').setAttribute('style', 'font-size: 13; transition: .15s linear');
};


// Создание альбома

/**
 * Максимальная длина имени альбома
 * @type {number}
 */
const maxAlbumNameLength = 50;

/**
 * Добавляет альбом в список DOM элементов-альбомов
 * @return {boolean}
 */
const createAlbum = () => {
    const albumName = document.getElementById('album-name').value.trim();

    if (albumName.length > maxAlbumNameLength) {
        openUploadErrorPopup('Имя альбома не должно превышать ' + maxAlbumNameLength +  ' символов');
        return false;
    }

    if (albumName === '') {
        openUploadErrorPopup('Вы ничего не ввели');
        return false;
    }

    for (let i = 0; albums.children.length > i; i++) {
        if (albums.children[i].innerText === albumName) {
            openUploadErrorPopup('Альбом "' + albumName + '" уже существует');
            return false;
        }
    }

    const album = document.createElement('option');
    album.text = albumName;
    album.value = albumName;
    albums.insertBefore(album, createAlbumOption).selected = true;

    closeCreateAlbumPopup();
    return true;
};


// Загрузка файла

/**
 * Переменная для хранения файла
 * @type {File}
 */
let file;

/**
 * Максимальный размер файла в байтах
 * @type {number}
 */
const maxFileSize = 52428800;

/**
 * Принимает файл при переносе его в зону загрузки
 * @param event
 */
const catchFileOnDrop = event => {
    event.preventDefault();
    file = event.dataTransfer.files[0];
    viewDetails(file);
};

/**
 * Принимает файл при клике по полю загрузке и последующему выбору через диалоговое окно
 * @param event
 */
const catchFileOnChange = event => {
    event.preventDefault();
    file = input.files[0];
    viewDetails(file);
};

/**
 * Производит загрузку файла или выводит ошибку
 */
const uploadFile = () => {
    closePopup();

    if (file.size >= maxFileSize) {
        openUploadErrorPopup('Файл должен быть не более 50 Мбайт');
        return false;
    } else if (file) {
        virtualProgressBar.classList.remove(collapsingClass);
        setTimeout(() => {

            doUpload(file);
        }, 300)

    }
};

/**
 * Загружает файл на сервер
 * @param file
 */
const doUpload = file => {
    const formData = new FormData();
    const xhr = new XMLHttpRequest();

    /**
     * Возвращает название альбома
     * @return {string|string | *}
     */
    const getAlbum = () => {
        const albumsList = albums.childNodes;

        for (let i = 0; albumsList.length > i; i++) {

            if (albumsList[i].selected === true) {
                return albumsList[i].innerText;
            }
        }

        return 'По умолчанию';
    };

    /**
     * Возвращает комментарий
     * @return {*}
     */
    const getComment = () => comment.value;

    formData.append('file', file);
    formData.append('album', getAlbum());
    formData.append('comment', getComment());

    /**
     * Анимирует виртуальный прогресс-бар
     * @param event
     */
    xhr.upload.onprogress = function(event) {
        let percents = (event.loaded * 100) / event.total;
        car.setAttribute('style', 'background-position: ' + percents + '% 0')
        progressBar.setAttribute('max', event.total);
        progressBar.value = event.loaded;
    };

    xhr.open('POST', 'http://jsyf.ru/files' + location.search);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send(formData);

    /**
     * Перенаправляет юзера на страницу всех файлов после загрузки файла
     * или просто вставляет файл в список, если уже находится на этой странице
     */
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (window.location.pathname !== '/files') {
                setTimeout(() => {
                    window.location.replace('/files');
                }, 500);
            } else {
                setTimeout(() => {
                    virtualProgressBar.classList.add(collapsingClass);
                    car.setAttribute('style', 'background-position: 0');

                    const filesList = document.getElementById('files-list');
                    let file = document.createElement('div');

                    file.innerHTML = xhr.responseText.trim();
                    file = file.firstChild;
                    file.setAttribute('style', 'border-bottom: none');
                    filesList.insertBefore(file, filesList.firstChild);
                }, 500);
            }
        }
    };
};


// Подгрузка файлов

/**
 * Высчитывает offset, отправляет запрос, получает список файлов и выводит в низ списка,
 * отвечает за появление кнопки "показать больше" на странице
 */
const showMoreFiles = () => {
    const offset = String(document.getElementsByClassName('files__list__file').length);
    const filesList = document.getElementById('files-list');
    const showingMoreFilesUrl = new Url();
    const showMoreBtn = document.getElementById('show-more-btn');
    const formData = new FormData();
    const xhr = new XMLHttpRequest();

    showingMoreFilesUrl.query.offset = offset;
    history.pushState(null, null, showingMoreFilesUrl.toString());

    formData.append('offset', offset);
    xhr.open('GET', location.href);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send(formData);

    xhr.onreadystatechange = () => {

        if (xhr.readyState === 4 && xhr.status === 200) {
            showMoreBtn.parentNode.remove();

            let files = document.createElement('div');
            files.innerHTML = xhr.responseText.trim();
            files = files.firstChild;
            files.setAttribute('style', 'border-bottom: none');
            filesList.insertAdjacentHTML('beforeend', xhr.responseText);

            delete showingMoreFilesUrl.query.offset;
            history.pushState(null, null, showingMoreFilesUrl.toString());
        }
    };
};