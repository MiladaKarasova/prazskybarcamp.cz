
$(document).ready(function() {

    var controll = false;

    function logo(image, old) {

        var path = old.split("/");
        path[path.length - 1] = image;
        return path.join("/");

    }

    function menu() {

        if(document.body.scrollTop > 1 && ($(".white-nav")).length > 0 && !controll) {

            controll = true;
            $("#nav").removeClass("white-nav");
            $("#logo-img").attr("src", logo("barcamp_black.svg", $("#logo-img").attr("src")));

        } else if (document.body.scrollTop < 1 && controll) {

            controll = false;
            $("#nav").addClass("white-nav");
            $("#logo-img").attr("src", logo("barcamp_white.svg", $("#logo-img").attr("src")));

        }

    }

    $(window).scroll(function () {
        menu();
    });

    menu();

    $("#arrow").on("click", function(){
        $("html,body").animate({ scrollTop: $("#where").offset().top-50}, 500);
    });

    $(".show-faq").on("click", function() {

        var ps = $(this).next("p");
        var span = $(this).prev("span");

        if(ps.is(":hidden")) {
            ps.slideDown(300);
            span[0].innerText = "-";
        } else {
            ps.slideUp(300);
            span[0].innerText = "+";
        }

    });

});
