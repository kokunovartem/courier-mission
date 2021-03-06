# courier-mission
Тестовый проект при трудоустройстве на работу

Задачи:

1. Вывести расписание поездок в регионы за выбираемый период. +
2. Рабочая форма для внесения данных в расписание с полями:
1) Регион +
2) Дата выезда из Москвы +
3) ФИО курьера +
4) Информационное поле: Дата прибытия в регион (рассчитывается на основе данных по региону) +
Еще добавил поля (время в пути), время отдыха курьера
Требования к форме:
1) Одновременно курьер может быть только в одной поездке в регион. +
2) Длительность поездки (туда/обратно) задаётся в таблице БД регионов. +
3. Заполнить данные по поездкам с июня 2015 года (скрипт заполнения прислать с остальными скриптами веб-приложения) +


Пояснения к проекту

Проект реализован с использованием концепции MVC. Написан с нуля.

В файле .htaccess я переадресую все запросы к приложению кроме js, css и картинок на index.php

В index.php у меня подключен autoloader и config. Здесь же создается объект «основного» класса приложения и выполняется его метод run(). Метод run инициализирует роутинг, рендеринг видов (за это отвечает класс FrontController) и записывает в свое статическое свойство $app экземпляр класса Application (чтобы к нему было удобно обращаться из любой части приложения)

В этой реализации класс Application реализован как синглтон и не делает больше ничего, кроме как создает подключение к базе данных :). 

Функция роутинга описана в классe FrontController, который реализует интерфейс IController. От FrontController наследуют остальные контроллеры. 
Интрефейс IController оставил пустым. Он просто выполняет функцию метки для проверки существования контроллера, к которому нужно обратиться.
Приложение понимает урлы вида 
/контроллер/action контроллера/название_параметра/значение параметра
При попытке вызвать несуществующий контроллер, будет отправлен заголовок "HTTP/1.0 404 Not Found" (первоначально сделал, чтобы выбрасывалось исключение, но потом подумал, неизвестно, сколько времени у Вас будет на тестирование моего приложения: увидите ошибку, закроете и дальше смотреть не будете).
Далее реализованы 4 контроллера, 4 модели и 5 папок с видами (+1 для слоев (header и footer). Во всех контроллерах реализован метод indexAction, вызывающийся при обращении к контроллеру по умолчанию.
В каждом методе Action, предполагающем рендеринг вида, мы получаем экземпляр класса FrontController (синглтон). Модель рендерит вид и передает результат контроллеру, который выводит этот результат на экран.
Реализовано 4 экрана. Стартовый. «Курьеры». «Регионы». «Поездки».
В «Курьерах» и «Регионах» - списки курьеров и регионов.
Требуемый функционал реализован на экране «Поездки»
Сделал дополнительные поля, сделал время отдыха курьера (задал 24 часа – график работы сутки через сутки получается). Курьер считается занятым и его нельзя еще раз отправить в регион, пока он не отдохнул :).
Новая поездка создается: клик на «Отправить курьера» -> отправка формы.
Можно очистить историю записей. Это удалит все записи из таблицы с поездками и сделает всех курьеров свободными.
Можно создать случайную историю записей с июня 2015 (дата зашита в код), в принципе можно сделать форму, в которой эта дата задается. На локальной машине скрипт выполняется около 1.5 секунд. Создается около 9200 записей с учетом, что курьер может быть только в одной поездке и должен отдыхать.
По коду есть комментарии. Готов ответить на любые вопросы.
