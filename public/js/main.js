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
 * Анимация: сохранение
 * @type {string}
 */
const savingAnimation = 'controls__saving';

/**
 * Анимация: сохранение завершено
 * @type {string}
 */
const savedAnimation = 'controls__saved';

/**
 * Класс активной ссылки главного меню
 * @type {string}
 */
const activeClass = 'main-nav__nav__item--active';



// ========== ELEMENTS ==========
/**
 * Скрытое главное меню
 * @type {HTMLElement}
 */
const nav = document.getElementById('collapsed-nav');

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
 * Блок анимаций сохранения файла
 * @type {HTMLElement}
 */
const survivor = document.getElementById('survivor');

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


// ========== OPEN & CLOSE FUNCTIONS
/**
 * Переключатель видимости элемента по клику на его кнопку
 *
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
        addOrRemove(nav, collapsingClass);
    } else if (elem.id === 'filters-list-toggler') {
        addOrRemove(filters, collapsingClass);
    }

};

/**
 * Открывает окно с ошибкой загрузки
 */
const openUploadErrorPopup = (text) => {
    document.getElementById('error-text').innerText = text;
    uploadErrorPopup.classList.remove(collapsingClass);
};

/**
 * Открывает модальное файловое окно и фон позади него
 * и выводит информацию о файле
 *
 * @param details Информация
 */
const viewDetails = (details = null) => {

    const getFileIconPath = (preview = null) => {

        if (preview === null) {
            const fileFormatIconsDir = '/images/file-format-icons';
            const extension = input.files[0].name.split('.').pop();
            return fileFormatIconsDir + '/' + extension + '.png';
        } else {

        }
    };

    const getFileName = () => {
        return prepareName(input.files[0].name);
    };

    const getDate = (date = false) => {
        if (date === false) {
            return false;
        } else {
            return prepareDate(date);
        }
    };

    const getSize = () => {
        return prepareSize(input.files[0].size);
    };

    document.getElementById('file-icon').innerHTML =
        '<img class="file-popup__file-icon__img" src="' + getFileIconPath() + '" alt="file-format-icon">';


    document.getElementById('file-info').innerHTML = '' +
        '<p class="file-popup__file-info__name">' + getFileName() + '</p>' +
        '<p class="file-popup__file-info__info">' +
        (getDate() ? ('<span>'+ getDate() + '</span><br>') : '') +
        '   <span>'+ getSize() +'</span><br>' +
            // (jsonData.resolution ? ('<span>' + prepareRes(jsonData.resolution) + '</span><br>') : '') +
            // (jsonData.duration   ? ('<span>' + prepareDur(jsonData.duration)   + '</span><br>') : '') +
        '</p>';


    popupContainer.classList.remove(collapsingClass);
};

/**
 * Открывает модальное окно для создания альбома и задает фону больший z-index
 *
 * @param albumsList Список существующих альбомов
 */
const openCreateAlbumPopup = (albumsList) => {
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
 *
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
 *
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

/**
 * Подготавливает дату к выводу на экран
 *
 * @param date
 * @return {string}
 */
const prepareDate = date => {
    const now = Math.floor(Date.now() / 10000);
    const uploaded = Math.floor(date / 10000);
    const difference = now - uploaded;

    const getTime = (minutes) => {
        if (minutes === 0) {
            return 'только что';
        } else if (minutes < 60) {
            return getDeclensionOfNum(minutes, 'm') + ' назад';
        } else if (minutes >= 60) {
            return getDeclensionOfNum(Math.floor(minutes / 60), 'h') + ' назад';
        }
    };

    const getDeclensionOfNum = (num, str) => {
        const registry = () => num.length === 1 ? num : Number(String(num).substr(-2, 2));
        const len = String(registry()).length;
        const lastDigit = Number(String(registry())[len - 1]);

        if (len > 1) {
            if (registry() >= 11 && registry() <= 14) {
                if (str === 'm') return num + ' минут';
                if (str === 'h') return num + ' часов';
                if (str === 'd') return num + ' дней';
                if (str === 'y') return num + ' лет';
            }
        }

        if (lastDigit === 1) {
            if (str === 'm') return num + ' минуту';
            if (str === 'h') return num + ' час';
            if (str === 'd') return num + ' день';
            if (str === 'y') return num + ' год';
        } else if (lastDigit < 5) {
            if (str === 'm') return num + ' минуты';
            if (str === 'h') return num + ' часа';
            if (str === 'd') return num + ' дня';
            if (str === 'y') return num + ' года';
        } else if (lastDigit >= 5 || lastDigit === 0) {
            if (str === 'm') return num + ' минут';
            if (str === 'h') return num + ' часов';
            if (str === 'd') return num + ' дней';
            if (str === 'y') return num + ' лет';
        }
    };

    return 'Загружено ' + getTime(difference);
};

/**
 * Подготавливает разрешение файла к выводу на экран, если оно есть
 *
 * @param res
 * @return {string}
 */
const prepareRes = res => {
    return 'Разрешение ' + res;
};

/**
 * Подготавливает длительность файла к выводу на экран, если оно есть
 *
 * @param dur
 * @return {string}
 */
const prepareDur = dur => {
    return 'Длительность ' + dur;
};


// ========== MAIN ==========

// Навигация

/**
 * Часть URL после site.com/
 * @type {string}
 */
const activeLinkPath = window.location.pathname;

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
 * Добавление активного класса радиокнопкам, отмеченным checked по умолчанию
 */
for (let i = 0; i < inputs.length; i++) {
    if (inputs.item(i).checked) {
        inputs.item(i).parentElement.classList.add(checkedClass);
    }
}

/**
 * Переключатель активного класса для радиокнопок
 *
 * @param elem Элемент, по которому кликнули
 */
const selectOption = (elem) => {
    const name = elem.childNodes[1].name;
    const options = document.getElementsByName(name);

    if (!elem.classList.contains(checkedClass) && elem.childNodes[1].checked) {

        for (let i = 0; i < options.length; i++) {
            options.item(i).parentElement.classList.remove(checkedClass);
        }

        elem.classList.add(checkedClass);
    }
};


// Создание альбома

/**
 * Максимальная длина имени альбома
 * @type {number}
 */
const maxAlbumNameLength = 50;

/**
 * Добавляет альбом в список DOM элементов-альбомов
 *
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
    album.text = name;
    album.value = name;
    albums.insertBefore(album, createAlbumOption).selected = true;

    closeCreateAlbumPopup();
    return true;
};


// Загрузка файла

/**
 * Максимальный размер файла в байтах
 * @type {number}
 */
const maxFileSize = 52428800;

/**
 * Ищет файл и производит загрузку файла или выводит ошибку
 */
const uploadFile = () => {
    closePopup();

    const file = input.files[0];
    if (file.size >= maxFileSize) {
        openUploadErrorPopup('Файл должен быть не более 50 Мбайт');
        return false;
    } else if (file) {
        virtualProgressBar.setAttribute('style', 'display:block');
        setTimeout(() => {

            doUpload(file);
        }, 300)

    }
};

/**
 * Загружает файл на сервер
 *
 * @param file
 */
const doUpload = file => {
    const formData = new FormData();
    const xhr = new XMLHttpRequest();

    /**
     * Возвращает название альбома
     *
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
     *
     * @return {*}
     */
    const getComment = () => comment.value;

    formData.append('file', file);
    formData.append('album', getAlbum());
    formData.append('comment', getComment());

    /**
     * Анимирует виртуальный прогресс-бар
     *
     * @param event
     */
    xhr.upload.onprogress = function(event) {
        let percents = (event.loaded * 100) / event.total;
        car.setAttribute('style', 'background-position: ' + percents + '% 0')
        progressBar.setAttribute('max', event.total);
        progressBar.value = event.loaded;

    };

    xhr.open('POST', 'http://jsyf.ru/');
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send(formData);

    /**
     * Перенаправляет юзера на страницу всех файлов после загрузки файла
     */
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            setTimeout(() => {
               window.location.replace('/files');
            }, 1500);

        }
    };
};