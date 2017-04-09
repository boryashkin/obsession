$('.toggle-done').click(function () {
    var $this = $(this),
        id = $this.parent().parent().data('key'),
        $parent = $this.parent().parent();
    $.ajax({
        type: "POST",
        url: '/budget/ajax/toggle-done?budgetId=' + id,
        data: {"budgetId":id},
        success: function (response) {
            if (response.status) {
                if (response.value) {
                    $this.css('color', 'green');
                    $this.text('Yes');
                    $parent.css('opacity', 0.5);
                } else {
                    $this.css('color', 'red');
                    $this.html('No');
                    $parent.css('opacity', 1);
                }
            }
        },
        dataType: 'json'
    });
});