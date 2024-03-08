    //fix menu overflow under the responsive table 
// hide menu on click... (This is a must because when we open a menu )
$(document).click(function (event) {
    //hide all our dropdowns
    $('.dropdown-menu[data-parent]').hide();
});
$(document).on('click', '.table-responsive [data-toggle="dropdown"]', function () {
    // if the button is inside a modal
    $buttonGroup = $(this).parent();
    if (!$buttonGroup.attr('data-attachedUl')) {
        var ts = +new Date;
        $ul = $(this).siblings('ul');
        $ul.attr('data-parent', ts);
        $buttonGroup.attr('data-attachedUl', ts);
        $(window).resize(function () {
            $ul.css('display', 'none').data('top');
        });
    } else {
        $ul = $('[data-parent=' + $buttonGroup.attr('data-attachedUl') + ']');
    }
    if (!$buttonGroup.hasClass('open')) {
        $ul.css('display', 'none');
        return;
    }
    dropDownFixPosition($(this).parent(), $ul);
    function dropDownFixPosition(button, dropdown) {
        var dropDownTop = button.offset().top + button.outerHeight();
        dropdown.css('top', dropDownTop + "px");
        dropdown.css('left', button.offset().left + "px");
        dropdown.css('position', "absolute");

        dropdown.css('width', dropdown.width());
        dropdown.css('heigt', dropdown.height());
        dropdown.css('display', 'block');
        dropdown.appendTo('body');
    }
});