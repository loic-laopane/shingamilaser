$(function()
{
    $('.btn-action').on('click', function(event){
        event.preventDefault();
        var redirect = $(this).attr('data-href');
        if(redirect !== undefined)
        {
            window.location.href = redirect;
        }
        else {

           window.history.back();
        }
    });

    $('.btn-confirm').on('click', function(event)
    {
        event.preventDefault();
        if(confirm('Are you sure ?'))
        {
            window.location.href = $(this).attr('href');
        }
    })
});