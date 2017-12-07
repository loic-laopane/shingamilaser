$(function()
{
    $('.perso-left').animate({
        left : '150px'
    }, 'slow', function()
    {

        $('.perso-right').animate({
            right : '150px'
        }, 'slow', function()
        {
            $('.main-jumbotron').animate({
                'opacity' : 1,
                'top' : '120px'
            },'slow', function(){

            });
        });
    });
});