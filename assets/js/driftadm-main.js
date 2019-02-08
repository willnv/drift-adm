jQuery(function($) {
    $("a.wp-has-submenu").click(function(e){
        e.preventDefault();

        $(this).toggleClass("wp-menu-is-open");
    })
});