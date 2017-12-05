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
        div_response.html('Loading <i class="fa fa-spinner fa-spin"></i><span class="sr-only">Loading...</span>');
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
                    div_response.html('');
                }

            },
            error: function(xhr, status, error)
            {
                console.log(error);
            }
        });
    });

    $('.block-avatar').on('mouseenter', function(event) {
        $(this).find('.remove-avatar').show('fast');
    });

    $('.block-avatar').on('mouseleave', function(event) {
        $(this).find('.remove-avatar').hide('fast');
    });
    $('.btn-remove-avatar').on('click', function(event) {
        var btn = $(this);
        event.preventDefault();
        event.stopPropagation();
        $.ajax({
            url: btn.attr('href'),
            method: 'POST',
            dataType: 'json',
            success: function(response)
            {
                if(response.status)
                {
                    btn.parent().hide('fast');
                    $('.avatar > img').fadeOut('fast');

                }

            }

        })
    });

    //Ajax Search
    $('#form_customer_quick_create').on('submit', function(event){

        var modal = $(this);
        var div_errors= modal.find('#alert-modal');
        div_errors.html('').removeClass('alert alert-danger');

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
                    div_errors.addClass('alert alert-success');
                    div_errors.html(response.message);
                    modal.find('#nickname').val('');
                    modal.find('#email').val('');
                }
                else {
                    //display error
                    console.log(response);
                    div_errors.addClass('alert alert-danger');
                    div_errors.html(response.message);
                    //div_response.html('');
                }

            },
            error: function(xhr, status, error)
            {
                console.log(error);
            }
        });
    });
});