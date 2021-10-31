$(document).ready(function () {

    if(window.location.pathname === "/") // This doesn't work, any suggestions?
    {
        $('#home').addClass('pink-item');
        $('#home').removeClass('black-item');
    }
    else{
        $('#home').removeClass('pink-item');
        $('#home').addClass('black-item');

    }
    if(window.location.href.indexOf("boutique") > -1) // This doesn't work, any suggestions?
    {
        $('.boutique').addClass('pink-item');
        $('.boutique').removeClass('black-item');
    }
    else{
        $('.boutique').removeClass('pink-item');
        $('.boutique').addClass('black-item');
    }
    if(window.location.href.indexOf("apropos") > -1) // This doesn't work, any suggestions?
    {
        $('#apropos').addClass('pink-item');
        $('#apropos').removeClass('black-item');
    }
    else{
        $('#apropos').removeClass('pink-item');
        $('#apropos').addClass('black-item');
    }
    if(window.location.href.indexOf("points-de-vente") > -1) // This doesn't work, any suggestions?
    {
        $('#point_vente').addClass('pink-item');
        $('#point_vente').removeClass('black-item');
    }
    else{
        $('#point_vente').removeClass('pink-item');
        $('#point_vente').addClass('black-item');
    }
    if(window.location.href.indexOf("contact") > -1) // This doesn't work, any suggestions?
    {
        $('#contact').addClass('pink-item');
        $('#contact').removeClass('black-item');
    }
    else{
        $('#contact').removeClass('pink-item');
        $('#contact').addClass('black-item');
    };
})