function refreshPage() {
    $.ajax({
        url: "tasks.json",
        dataType: "json",
        cache: false
    }).done(function(json){
        $('.task-lists .row').remove();
        $.each(json, function(key, value){
            var $singlePost =
                '<ul class="row">' +
                '<li><button class="redact">Redact</button></li>' +
                '<li><button class="remove">Remove</button></li>' +
                '<li class="id">' + value.id + '</li>' +
                '<li class="task">' + value.task + '</li>' +
                '<li class="status">' + value.status + '</li>' +
                '</ul>';
            $('.task-lists').append($singlePost);
        });
        $('.redact').on('click', function(){
            //var $numOrder = $(this).parent().parent('ul').index();
            $('.new-task h2').text('Edit task');
            $('#sub-form').attr('method', 'PUT');
            $('#id').val($(this).closest('ul').find('.id').text());
            $('#name').val($(this).closest('ul').find('.task').text());
            $('#status').val($(this).closest('ul').find('.status').text());

        });
        $('.remove').on('click', function(){
            var $numOrder = $(this).parent().siblings('.id').text();
            $.ajax({
                url: '/todoappajax/test-func.php',
                type: 'DELETE',
                data: 'id=' + $numOrder
            }).then(refreshPage);
        });
    });
}

jQuery(function($){
    $('#sub-form').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: '/todoappajax/test-func.php',
            type: $form.attr('method'),
            data: $form.serialize()
        }).then(refreshPage);
    });

    $('#reset-form').on('click', function (e) {
        e.preventDefault();
        $('.new-task h2').text('New task');
        var $form = $('#sub-form').get(0);
        $form.method = 'POST';
        $form.reset();
    });
    refreshPage();
});