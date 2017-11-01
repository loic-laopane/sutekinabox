$(function(){
    $('.panel-change').on('click', function (e) {
        var checkbox = $(this).find('input[type="checkbox"]');
        if(checkbox.is(':checked'))
        {
            checkbox.prop('checked', false);
            $(this).removeClass('panel-checked');
        }
        else {
            checkbox.prop('checked', true);
            $(this).addClass('panel-checked');

        }

    }) ;
});