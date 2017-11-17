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

    //Ajax Search
    $('#form_search').on('submit', function(event){

        var div_errors= $('#errors');
        div_errors.html('').removeClass('alert alert-danger');
        var div_response = $('#response');
        div_response.html('');
        event.preventDefault();

        $.ajax({
            url : $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response)
            {
                if(response.status)
                {
                    //Display
                    //console.log(response.data);
                    //console.log(response.message);

                    div_response.html(response.data)
                }
                else {
                    //display error
                    //console.log(response);
                    div_errors.addClass('alert alert-danger');
                    div_errors.html(response.message);
                }
            },
            error: function(xhr, status, error)
            {
                console.log(error);
            }
        });
    })
});