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

/**
 * Класс кнопки плеера, отвечающий за кнопку воспроизведения
 * @type {string}
 */
const toPlayClass = 'play-pause-btn__icon--play';

/**
 * Класс кнопки плеера, отвечающий за кнопку воспроизведения
 * @type {string}
 */
const toPauseClass = 'play-pause-btn__icon--pause';


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
 * Кнопка-сендвич меню
 * @type {HTMLElement}
 */
const navToggler = document.getElementById('nav-toggler');

/**
 * Скрытый список фильтров
 * @type {HTMLElement}
 */
const filters = document.getElementById('filters-list');

/**
 * Варианты фильтров
 * @type {HTMLCollectionOf<Element>}
 */
const filtersOptions = document.getElementsByClassName('files__filters__list__item__label');

/**
 * Кнопка-сендвич фильтров
 * @type {HTMLElement}
 */
const filtersListToggler = document.getElementById('filters-list-toggler');

/**
 * Модальное окно с информацией о файле
 * @type {HTMLElement}
 */
const popupContainer = document.getElementById('popup-container');

/**
 * Темный фон за модальным окном загрузки файла
 * @type {HTMLElement}
 */
const blurBg = document.getElementById('blur-bg');

/**
 * Темный фон за модальным окном удаления файла
 * @type {HTMLElement}
 */
const deleteFileBlurBg = document.getElementById('delete-file-blur-bg');

/**
 * Крестик для закрытия главного всплывающего окна
 * @type {HTMLElement}
 */
const controlClose = document.getElementById('control-close-main');

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
 * Кнопка загрузки файла
 * @type {HTMLElement}
 */
const uploadBtn = document.getElementById('upload-btn');

/**
 * Поле загрузки файла
 * @type {HTMLElement}
 */
const dropBox = document.getElementById('drop-box');

/**
 * Кнопки для удаления файлов
 * @type {HTMLCollectionOf<Element>}
 */
const deleteFileBtns = document.getElementsByClassName('files__list__file__delete-btn');

/**
 * Контейнер модального окна с подтверждением удаления файла
 * @type {HTMLElement}
 */
const deleteFileContainer = document.getElementById('delete-file-popup-container');

/**
 * Крестик закрытия модального окна удаления файла
 * @type {HTMLElement}
 */
const controlCloseDeleteFile = document.getElementById('control-close-delete-file');

/**
 * Кнопка подтверждения удаления файла
 * @type {HTMLElement}
 */
const deleteFileYes = document.getElementById('delete-file-yes');

/**
 * Кнопка отмены удаления файла
 * @type {HTMLElement}
 */
const deleteFileNo = document.getElementById('delete-file-no');

/**
 * Контейнер плеера
 * @type {HTMLElement}
 */
const playerParent = document.getElementById('player-parent');

/**
 * Элемент плеера
 * @type {HTMLElement}
 */
const player = document.getElementById('player');

/**
 * Кнопка старт-пауза плеера, содержащая в себе путь к текущему аудио
 * @type {HTMLElement}
 */
const playerButton = document.getElementById('player-play-pause-btn');

/**
 * Иконка на кнопке старт-пауза плеера
 * @type {ChildNode}
 */
let playerIcon;

if (playerButton !== null) {
    playerIcon = playerButton.childNodes[1];
}

/**
 * Кнопки старт-пауза песен на странице, содержащие в себе пути к аудио
 * @type {HTMLCollectionOf<Element>}
 */
let songsButtons = document.getElementsByClassName('song__play-pause-btn');

/**
 * Имя песни, отображаемое в плеере
 * @type {HTMLElement}
 */
const playerSongName = document.getElementById('player-song-name');

/**
 * Ползунок громкости плеера
 * @type {HTMLElement}
 */
const playerVolumeControl = document.getElementById('player-volume');

/**
 * Время плеера
 * @type {HTMLElement}
 */
const playerTime = document.getElementById('player-time');

/**
 * Поле поиска
 * @type {HTMLElement}
 */
const searchField = document.getElementById('search-field');

/**
 * Кнопка поиска
 * @type {HTMLElement}
 */
const searchButton = document.getElementById('search-btn');

/**
 * Часть URL после site.com/
 * @type {string}
 */
const activeLinkPath = location.pathname;

/**
 * URL
 * @type {Url}
 */
const baseUrl = new Url();


// ========== OPEN & CLOSE FUNCTIONS

/**
 * Скрывает или показывает меню
 */
const showNHideNav = () => {
    classToggler(collapsedNav, collapsingClass);
};

/**
 * Скрывает или показывает фильтры
 */
const showNHideFilters = () => {
    classToggler(filters, collapsingClass);
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
        const ext = file.name.split('.').pop().toLowerCase();

        const formData = new FormData();
        formData.append('icon', fileFormatIconsDir + '/' + ext + '.png');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/files' + location.search);
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
        return file.name;
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
 * Закрывает модальное файловое окно и фон позади него
 */
const closePopup = () => {
    popupContainer.classList.add(collapsingClass);
};

/**
 * Закрывает модальное окно с ошибкой загрузки
 */
const closeUploadErrorPopup = () => {
    uploadErrorPopup.classList.add(collapsingClass);
};


// ========== UTILS ==========

/**
 * Переключалка классов css: если нет - добавляет, если нет - убирает
 * @param elem
 * @param className
 */
const classToggler = (elem, className) => {
    if (elem.classList.contains(className)) {
        elem.classList.remove(className);
    } else if (!elem.classList.contains(className)) {
        elem.classList.add(className);
    }
};

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

const formatDateTime = datetime => {
    let userDateTime = new Date(datetime);

    const prepareDay = day => {
        let result = day.toString();

        if (result.length === 1) {
            result = '0' + result[0];
        }

        return result;
    };

    return 'Загружено ' + userDateTime.getHours()
        + ':' + userDateTime.getMinutes()
        + ' ' + prepareDay(userDateTime.getDate())
        + '-' + (userDateTime.getMonth() + 1)
        + '-' + userDateTime.getFullYear();
};

// ========== MAIN ==========

/////// ПЛЕЕР ///////

/**
 * Требуемая ширина плеера, подгоняемая под родителя
 * Нужно, потому что left, right и прочие штуки fixed позиционируемого плеера считаются от окна браузера
 * @type {number}
 */
let playerParentWidth;

/**
 * Текущая песня
 */
let currentSong;

/**
 * Текущая кнопка старт-пауза проигрываемой песни
 */
let currentSongButton;

/**
 * Обработчик для кнопки плеера: старт или пауза для текущей песни.
 */
if (playerButton !== null) {
    playerButton.addEventListener('click', () => {
        if (currentSong !== undefined) {
            if (currentSong.paused) {
                playerIcon.classList.remove(toPlayClass);
                playerIcon.classList.add(toPauseClass);
                currentSongButton.childNodes[1].classList.remove(toPlayClass);
                currentSongButton.childNodes[1].classList.add(toPauseClass);
                currentSong.play();
            } else {
                playerIcon.classList.remove(toPauseClass);
                playerIcon.classList.add(toPlayClass);
                currentSongButton.childNodes[1].classList.remove(toPauseClass);
                currentSongButton.childNodes[1].classList.add(toPlayClass);
                currentSong.pause();
            }
        }
    });
}

/**
 * Раздача обработчиков кнопкам песен
  */
const setEventListenersToSongs = () => {
    for (let songButton of songsButtons) {
        songButton.addEventListener('click', () => {

            playerParentWidth = playerParent.offsetWidth - 30;
            playerSongName.setAttribute('style', 'width:' + (playerParentWidth - 170) + 'px');

            /**
             * Показываем плеер по клику на кнопку песни,
             * устанавливаем ширину плеера, спозиционированного как fixed, в зависимости от ширины его родителя,
             */
            player.setAttribute('style',
                'width:' + playerParentWidth + 'px;' +
                'display: flex !important;' +
                'position: fixed;'
            );

            player.classList.remove(collapsingClass);

            /**
             * Добавляет иконки воспроизведения плееру и песням
             */
            const setToPlayIcons = () => {
                playerIcon.classList.remove(toPlayClass);
                playerIcon.classList.add(toPauseClass);
                songIcon.classList.remove(toPlayClass);
                songIcon.classList.add(toPauseClass);
            };

            /**
             * Добавляет иконки паузы плееру и песням
             */
            const setToPauseIcons = () => {
                playerIcon.classList.remove(toPauseClass);
                playerIcon.classList.add(toPlayClass);
                songIcon.classList.remove(toPauseClass);
                songIcon.classList.add(toPlayClass);
            };

            /**
             * Иконка кнопки старт-пауза песни
             * @type {ChildNode}
             */
            let songIcon = songButton.childNodes[1];

            /**
             * URL песни
             * @type {string}
             */
            let songURL = songButton.getAttribute('data-song');

            /**
             * Имя песни
             * @type {string}
             */
            let songName = songButton.parentNode.nextSibling.nextSibling.textContent;

            /**
             * Текущий прогресс песни
             * @type {number}
             */
            let currentProgress;

            /**
             * Кружочек установки времени
             * @type {HTMLElement}
             */
            const setTimeCircle = document.getElementById('set-time');

            /**
             * Полоса прогресса песни
             * @type {HTMLElement}
             */
            const progressLine = document.getElementById('progress-line');

            // если путь проигрываемой песни в плеере (если она там вообще есть) совпадает с путем песни нажатой кнопки,
            // что фактически означает, что пользователь захотел включить другую песню...
            if (playerButton.getAttribute('data-song') !== songURL) {
                // ...и если какая-то песня все-таки играет в плеере, то ставим ее на паузу и меняем иконки
                if (currentSong !== undefined && !currentSong.paused) {
                    currentSong.pause();
                    currentSongButton.childNodes[1].classList.remove(toPauseClass);
                    currentSongButton.childNodes[1].classList.add(toPlayClass);
                }

                // создаем объект новой песни
                currentSong = new Audio(songURL);
                currentSong.volume = 0.5;

                // добавляем ей обработчик, который сработает, когда песня окончится
                currentSong.addEventListener('ended', () => {
                    playerIcon.classList.remove(toPauseClass);
                    playerIcon.classList.add(toPlayClass);
                    currentSongButton.childNodes[1].classList.remove(toPauseClass);
                    currentSongButton.childNodes[1].classList.add(toPlayClass);
                    setTimeCircle.setAttribute('style', 'left:0');
                    progressLine.setAttribute('style', 'width:0');
                    playerTime.textContent = '0:00';
                });

                // и еще один обработчик, который будет рисовать полосу загрузки и полосу текущего состояния
                currentSong.addEventListener('timeupdate', () => {
                    currentProgress = Math.floor(
                        ((100 * (currentSong.currentTime + currentSong.duration)) / currentSong.duration) - 100
                    );

                    setTimeCircle.setAttribute('style', 'left:' + currentProgress + '%');
                    progressLine.setAttribute('style', 'width:' + currentProgress + '%');

                    let seconds = currentSong.currentTime.toFixed();
                    const zero = x => x < 10 ? '0' : '';

                    playerTime.textContent = (Math.floor(seconds / 60) - (Math.floor(seconds / 3600) * 60)) +
                        ":" + zero(seconds % 60) + (seconds % 60);
                });

                // а также обработчик управления громкостью
                playerVolumeControl.addEventListener('input', () => {
                    currentSong.volume = playerVolumeControl.value;
                });

                // и добавляем путь к песне в плеер
                playerButton.setAttribute('data-song', songURL);
                playerSongName.textContent = songName;

                // запускаем песню
                const promise = currentSong.play();

                // устанавливаем нужные иконки
                setToPlayIcons();

                // при подгрузке новых песен перестают работать обработчики на старых
                // этот кусок кода решает эту проблему
                // не знаю, как это работает, но оно работает
                // возможно, дело в асинхронности
                if (promise !== null) {
                    promise.catch(() => {
                        currentSong.play();
                        setToPlayIcons();
                        currentSongButton = songButton;
                    });
                }

                // перезаписываем текущую кнопку в глобальной области видимости
                currentSongButton = songButton;
            }
            // тут просто воспроизводим текущую песню, когда она на паузе
            else if (playerButton.getAttribute('data-song') === songURL && currentSong.paused) {
                setToPlayIcons();
                currentSong.play();
                currentSongButton = songButton;
            }
            // а тут ставим на паузу, если она уже воспроизведена
            else if (playerButton.getAttribute('data-song') === songURL && !currentSong.paused) {
                setToPauseIcons();
                currentSong.pause();
                currentSongButton = songButton;
            }
        });
    }
};

/**
 * Обновляет настройки плеера при перезагрузке DOM-дерева ajax-ом.
 * А именно: при сортировке слетают иконки у кнопки текущей проигрываемой песни,
 * функция их возвращает обратно из глобальной переменной, куда сохранено состояние этой кнопки.
 */
const reloadPlayerSettingsIfAjaxReloadDOM = () => {
    songsButtons = document.getElementsByClassName('song__play-pause-btn');

    for (let button of songsButtons) {
        if (button.getAttribute('data-song') === playerButton.getAttribute('data-song')) {
            currentSongButton = button;
            let songIcon = currentSongButton.childNodes[1];
            if (!currentSong.paused) {
                currentSongButton.childNodes[1].classList.remove(toPlayClass);
                currentSongButton.childNodes[1].classList.add(toPauseClass);
            }
        }
    }
};


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

                // init
                lightbox.destroy();
                lightbox = new SimpleLightbox({elements: '.files__list__file>a'});
                players = Array.from(document.querySelectorAll('.js-player')).map(p => new Plyr(p));
                reloadPlayerSettingsIfAjaxReloadDOM();
                setEventListenersToSongs();
                formateDateAndTimeInElements();
                for (let deleteFileBtn of deleteFileBtns) {
                    deleteFileBtn.addEventListener('click', (event) => {
                        event.preventDefault();
                        deleteFile(event.currentTarget);
                    });
                }
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
    const searchUrl = new Url();
    const query = searchField.value.trim();

    searchUrl.query.search = query;

    const formData = new FormData();
    const xhr = new XMLHttpRequest();

    history.pushState(null, null, searchUrl.toString());

    formData.append('search', query);
    xhr.open('GET', location.href);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send(formData);

    xhr.onreadystatechange = () => {

        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('files-list').innerHTML = xhr.responseText;

            // init
            lightbox.destroy();
            lightbox = new SimpleLightbox({elements: '.files__list__file>a'});
            players = Array.from(document.querySelectorAll('.js-player')).map(p => new Plyr(p));
            reloadPlayerSettingsIfAjaxReloadDOM();
            setEventListenersToSongs();
            formateDateAndTimeInElements();
            for (let deleteFileBtn of deleteFileBtns) {
                deleteFileBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    deleteFile(event.currentTarget);
                });
            }
        }
    }
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

    formData.append('file', file);

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

    xhr.open('POST', '/files' + location.search);
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

                    // init
                    lightbox.destroy();
                    lightbox = new SimpleLightbox({elements: '.files__list__file>a'});
                    players = Array.from(document.querySelectorAll('.js-player')).map(p => new Plyr(p));
                    setEventListenersToSongs();
                    formateDateAndTimeInElements();
                    for (let deleteFileBtn of deleteFileBtns) {
                        deleteFileBtn.addEventListener('click', (event) => {
                            event.preventDefault();
                            deleteFile(event.currentTarget);
                        });
                    }
                }, 500);
            }
        }
    };
};


// Подгрузка файлов

/**
 * Производит форматирование даты и времени из атрибута data-datetime и вставку выходных данных в тег
 */
const formateDateAndTimeInElements = () => {

    /**
     * Список элементов с датой, требущей форматирования
     * @type {NodeListOf<Element>}
     */
    const dateTimeOfFileList = document.querySelectorAll('[data-datetime]');

    for (let elem of dateTimeOfFileList) {
        elem.textContent = formatDateTime(elem.getAttribute('data-datetime'));;
    }
};

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

            // init
            lightbox.destroy();
            lightbox = new SimpleLightbox({elements: '.files__list__file>a'});
            players = Array.from(document.querySelectorAll('.js-player')).map(p => new Plyr(p));
            reloadPlayerSettingsIfAjaxReloadDOM();
            setEventListenersToSongs();
            formateDateAndTimeInElements();
            for (let deleteFileBtn of deleteFileBtns) {
                deleteFileBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    deleteFile(event.currentTarget);
                });
            }

            delete showingMoreFilesUrl.query.offset;
            history.pushState(null, null, showingMoreFilesUrl.toString());
        }
    };
};


// Удаление файла

/**
 * Выводит модальное окно с подтверждением на удаление файла
 * @param fileElement
 */
const deleteFile = (fileElement) => {
    deleteFileContainer.classList.remove(collapsingClass);
    deleteFileBlurBg.classList.remove(collapsingClass);
    deleteFileYes.setAttribute('data-file-id', fileElement.getAttribute('id'));
};

/**
 * Закрывает модальное окно с подтверждением на удаление файла
 */
const closeDeleteFilePopup = () => {
    deleteFileBlurBg.classList.add(collapsingClass);
    deleteFileContainer.classList.add(collapsingClass);
};


/////// INIT ///////

/**
 * Создаем объект галереи
 */
let lightbox = new SimpleLightbox({elements: '.files__list__file>a'});

/**
 * Создаем видеоплееры
 * @type {Plyr[]}
 */
let players = Array.from(document.querySelectorAll('.js-player')).map(p => new Plyr(p));

/**
 * Устанавливаем обработчики
 */
setEventListenersToSongs();
formateDateAndTimeInElements();

navToggler.addEventListener('click', showNHideNav);
blurBg.addEventListener('click', closePopup);
controlClose.addEventListener('click', closePopup);
uploadBtn.addEventListener("click", uploadFile);
dropBox.addEventListener("change", event => catchFileOnChange(event));

for (let option of filtersOptions) {
    option.addEventListener('click', event => selectOption(event.currentTarget));
    option.addEventListener('change', sort);
}

if (baseUrl.path === '/files') {
    if (baseUrl.query.search !== '' && baseUrl.query.search !== undefined) {
        searchField.value = baseUrl.query.search;
    }

    if (searchField.value !== '') {
        search();
    }

    searchField.addEventListener('input', event => {
        event.preventDefault();
        setTimeout(() => {
            search();
        }, 600);

    });

    searchButton.addEventListener('click', event => {
        event.preventDefault();
        search();
    });

    filtersListToggler.addEventListener('click', showNHideFilters);

    for (let deleteFileBtn of deleteFileBtns) {
        deleteFileBtn.addEventListener('click', (event) => {
            event.preventDefault();
            deleteFile(event.currentTarget);
        });
    }

    controlCloseDeleteFile.addEventListener("click", closeDeleteFilePopup);
    deleteFileBlurBg.addEventListener("click", closeDeleteFilePopup);

    deleteFileNo.addEventListener("click", closeDeleteFilePopup);
    deleteFileYes.addEventListener("click", (event) => {
        const deletingBtn = event.currentTarget;
        const fileId = deletingBtn.getAttribute('data-file-id');
        deletingBtn.removeAttribute('data-file-id');

        const formData = new FormData();
        const xhr = new XMLHttpRequest();

        formData.append('delete-file-id', fileId);
        xhr.open('POST', '/files' + location.search);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.send(formData);

        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                closeDeleteFilePopup();
                document.querySelector('button[id="' + fileId + '"]').parentNode.parentNode.remove();
                console.log(xhr.responseText);
            }
        };
    });
}
