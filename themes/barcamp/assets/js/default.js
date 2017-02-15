
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
            $("#burger-img").attr("src", logo("burger.svg", $("#burger-img").attr("src")));

        } else if (document.body.scrollTop < 1 && controll) {

            controll = false;
            $("#nav").addClass("white-nav");
            $("#logo-img").attr("src", logo("barcamp_white.svg", $("#logo-img").attr("src")));
            $("#burger-img").attr("src", logo("burger-w.svg", $("#burger-img").attr("src")));

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

    $(".talk-trigger").on("click", function() {
        var hash = $(this).attr("data-hash");

        $.get("/ajax/talk/" + hash)
            .done(function(data) {
                swal({
                    html: data,
                    width: "700px",
                    showConfirmButton: false
                });
            });
    });

    $("#file-trigger").on("click", function () {
        $("#photo").click();
    });

    $("body").on("change", "#photo", function () {

        var image = document.getElementById("photo");
        var file = image.files;

        if(file.length > 0) {
            $("#file-name").text(file[0].name);
        }

    });

    $("body").on("change", "#registration-type", function () {

        if($("#registration-type").is(":checked")) {
            $("#switch-title").text("workshop");
            $("#switch-name label").html("Název workshopu<sup>*</sup>");
            $("#switch-name input").attr("placeholder", "Název workshopu");
            $("#annotation").attr("placeholder", "O čem je váš workshop");
            $("#type").val("workshop");
        } else {
            $("#switch-title").text("speaker");
            $("#switch-name label").html("Název speaku<sup>*</sup>");
            $("#switch-name input").attr("placeholder", "Název speaku");
            $("#annotation").attr("placeholder", "O čem je váš speak");
            $("#type").val("speaker");
        }
    });

    if(document.getElementById("bgvid")) {

        var actual = 1;
        var changer = $("#changer div");
        var height = (changer.height() / 5);

        setTimeout(function () {

            $("#bgvid")[0].play();

            setInterval(function(){

                if(5 > actual) {
                    actual++;
                } else {
                    actual = 1;
                }

                $(".fallback-image").attr("src", logo(actual + ".png", $(".fallback-image").attr("src") ));

                changer.animate({
                    bottom: -height
                }, 400, function () {
                    changer.prepend( changer.find("h1").last() );
                    changer.css({bottom: 0});
                });
            }, 2000);

        }, 200);

    }

    $("#menu-trigger").on("click", function() {

        $(".mobile-nav").animate({
            "opacity" : 1,
            "z-index" : 999
        }, 100);

    });

    $(".close-nav").on("click", function () {

        $(".mobile-nav").animate({
            "opacity" : 0,
            "z-index" : -999
        }, 100);

    });

    $("#talks-trigger").on("click", function() {
        $(".talks").css({height: "auto"});
        $("#talks-trigger").fadeOut(100);
    });

});
