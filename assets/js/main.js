//Функция для отображения модального окна
(function( $ ){
    $.fn.showModalForm = function() {
        var windowWidth = $(window).width();
        var modalWidth = this.width();
        var modalTop = $(window).scrollTop() + 150;
        var modalLeft = ( windowWidth - modalWidth )/2;
        this.css({'left': modalLeft, 'top': modalTop});
        this.removeClass('hidden');
    };
})( jQuery );

$(function () {
    //Функция для отображения темной подложки
    function blackoutOn() {
        var blackoutHeight = document.documentElement.scrollHeight > screen.height ?
            document.documentElement.scrollHeight : screen.height;
        $( 'body' ).append( '<div class="blackout"></div>' );
        $( '.blackout' ).animate( {height: blackoutHeight}, 300 );
    }

    //Функция для скрытия темной подложки
    function blackoutOff() {
        $( '.blackout' ).remove();
    }

    //Получаем список свободных курьеров и добавляем их в селект формы
    function getFreeCouriers() {
        $.get('/mission/get_free_courier', function (request) {
            var append = '';//Накопительная переменная для option
            if(request && request.length > 0) {
                //Формируем список оптионов для добалвления в селект
                for (var i =0; i < request.length; ++i) {
                    append += '<option value="'+ request[i]['id']+'">'+request[i]['courier']+'</option>'
                }
                $('#couriers_select option').remove();//Удаляем имеющиеся оптионы
                $('#couriers_select').append(append);//Добавляем полученные из БД
                $('#couriers_select').prop('disabled', false);//Разрешаем выбрать курьера
                $('#no_couriers').addClass('hidden');//Прячем предупреждение об отсутствии свободных курьеров (если раньше оно добавлялось)
            } else {
                $('#couriers_select').prop('disabled', true);//Запрещаем выбирать курьера
                $('#no_couriers').removeClass('hidden');//Показываем предупреждение
            }
        }, 'json')
    }

    //Вешаем обработчик на клик по кнопке "Отправить курьера"
    $('#create_travel').click(function () {
        $('#travel_create_form').showModalForm();//Отображаем модальное окно
        getFreeCouriers();//Получаем свободных курьеров
        blackoutOn();//Темная подложка
    });

    //Вешаем обработчик на клик по подложке (т.к. на момент запуска скрипта подложка не существует, вешаем обработчик на document и делегируем событие на подложку
    $(document).delegate('.blackout','click', function () {
        blackoutOff();//Убирает темную подложку
        $('#travel_create_form').addClass('hidden');//Прячем форму
    });

    //Ajax отправка формы
    $('#travel_create_form form').submit(function (e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function (request) {
            if(request) {
               var append = '<tr class="new">\n' +
                                '<td>'+request['id']+'</td>\n' +
                                '<td>'+request['courier']+'</td>\n' +
                                '<td>'+request['city']+'</td>\n' +
                                '<td>'+request['begin_dt']+'</td>\n' +
                                '<td>'+request['travel_time']+'</td>\n' +
                                '<td>'+request['arrival_dt']+'</td>\n' +
                                '<td>'+request['time_relax']+'</td>\n' +
                                '<td>Будет занят до: '+request['busy_until']+'</td>\n' +
                                '\n' +
                            '</tr>';
                $($('.missions-table tr')[1]).before(append);//Добавляем полученную поездку в таблицу
            }
            //После отправки формы, скрываем ее
            blackoutOff();
            $('#travel_create_form').addClass('hidden');
        }, 'json');
    });

    //При клике на крестик, кнопку "отменить", закрываем форму
    $('.close-btn').click(function () {
        $('#travel_create_form').addClass('hidden');
        blackoutOff();
    });
    $('#cancel').click(function () {
        $('#travel_create_form').addClass('hidden');
        blackoutOff();
    });

    //Очистка истории записей
    $('#clear_travel_list').click(function () {
        if (!confirm('Созданные записи будут удалены. Продолжить?')) return;
        $.post('/mission/clear_history', function () {
             location.reload();//После выполнения просто перезагружаем страницу (для простоты)
        });
    });

    //Создание случайной истории записей с июня 2015 года (дата зашита в код)
    $('#create_travel_list').click(function () {
        if (!confirm('Созданные записи будут удалены. Продолжить?')) return;
        $.post('/mission/create_random_history', function () {
            location.reload();//После выполнения просто перезагружаем страницу (для простоты)
        });
    });

    //Отправка запроса при выборе дат
    $('#check_date').submit(function (e) {
        e.preventDefault();
        var beginDt = $('input[name=begin_dt]').val();
        var endDt = $('input[name=end_dt]').val();
        var url = location.origin + '/mission/index';
        url += beginDt ? '/begin_dt/' + beginDt : '';
        url += endDt ? '/end_dt/' + endDt : '';
        url.replace('//', '/');
        location.assign(url);
    });

    $('.date-input').datepicker({
        dateFormat: 'dd.mm.yy',
        dayNames: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
        dayNamesMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        dayNamesShort: ["Вск", "Пон", "Втр", "Срд", "Чтв", "Птн", "Суб"],
        monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
        monthNamesShort: ["Янв", "Фев", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сент", "Окт", "Ноя", "Дек"],
        showMonthAfterYear: true,
        changeYear: true,
        changeMonth: true,
        yearRange: "-100:+10",
        firstDay: 1,
        gotoCurrent: true

    });

});