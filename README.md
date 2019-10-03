Здесь лежит учебный проект — [файлообменник](https://gist.github.com/codedokode/9424217) под кодовым именем **Jesus saves your file**.

В качестве так называемых фронтенд технологий был использован css-фреймворк [bootstrap 4](https://github.com/twbs/bootstrap/tree/v4-dev), 
а так же javascript-библиотеки: [plyr (видеоплеер)](https://github.com/sampotts/plyr), [simple light box](https://github.com/andreknieriem/simplelightbox), и 
утилита для работы с браузерной строкой и get параметрами [domurl.js](https://github.com/Mikhus/domurl).

Из бекэнд технологий использовался php микрофреймворк [Slim 3](https://github.com/slimphp/Slim), 
входящий в него [DI-контейнер Pimple](https://github.com/silexphp/Pimple) и 
[роутер](https://github.com/nikic/FastRoute). Также в работе участвовали библиотеки поменьше: [fig-cookies](https://github.com/dflydev/dflydev-fig-cookies) для работы с куками, 
[getid3](https://github.com/JamesHeinrich/getID3) для извлечения мета-данных файлов, query builder [Pixie](https://github.com/usmanhalalit/pixie) 
и шаблонизатор [Twig](https://github.com/twigphp/Twig), а так же [расширение](https://github.com/slimphp/Twig-View) для интеграции этого шаблонизатора с фреймворком Slim.
Ну и конечно же менеджер зависимостей [Composer](https://github.com/composer/composer).

Наряду с технологиями выше удостоим чести серверные технологии, без которых бы не было этого проекта: веб-сервер Nginx со своим верным товарищем php-fpm,
база данных Postgreql и сервер полнотекстового поиска Sphinx.

Проект был написан с использованием схемы MVC, а так же шаблонами проектирования Factory и Data Mapper.

Дизайн и оформление — h1w3ghj7.
