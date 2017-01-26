
window.onload = function() {

    var controll = false;

    function logo(image) {

        var path = (document.getElementById('logo-img').src).split('/');
        path[path.length - 1] = image;
        return path.join('/');

    }

    function menu() {

        if(document.body.scrollTop > 1 && (document.getElementsByClassName('white-nav')).length > 0 && !controll) {

            controll = true;

            document.getElementById('nav').className = '';

            document.getElementById('logo-img').src = logo('barcamp_black.svg');

        } else if (document.body.scrollTop < 1 && controll) {

            controll = false;

            document.getElementById('nav').className = 'white-nav';

            document.getElementById('logo-img').src = logo('barcamp_white.svg');

        }

    }

    window.onscroll = function() { menu() }

    menu();

    if(document.getElementById('arrow')) {
        document.getElementById('arrow').addEventListener('click', function () {

            // TODO: scroll down not working
        });
    }

    $(".show-faq").on('click', function() {

        var ps = $(this).next('p');
        var span = $(this).prev('span');

        console.log(span);

        if(ps.is(':hidden')) {
            ps.slideDown(300);
            span[0].innerText = '-';
        } else {
            ps.slideUp(300);
            span[0].innerText = '+';
        }

    });

}