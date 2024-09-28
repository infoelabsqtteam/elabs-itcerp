$(document).ready(function () {
    if ($("input").hasClass('bgwhite')) {
        $("input.bgwhite").attr("autocomplete", "off");
    }
});

$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});

//Header Menu Script
$(document).ready(function () {
    $('.dropdown-submenu a.test').on("click", function (e) {
        $(".dropdown-menu-hide").hide();
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
    $('body').hover(function (e) {
        $(".dropdown-menu-hide").hide();
        e.stopPropagation();
        e.preventDefault();
    });
});

$(document).ready(function () {
    $(".closeMessageAlert").hide();
    $(".closeMessageAlert").fadeTo(3000, 500).slideUp(500, function () {
        $(".closeMessageAlert").slideUp(500);
    });
});