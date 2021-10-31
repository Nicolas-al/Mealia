$(document).ready(function (){

// pagination des produits
$('.pagination > li:first-child');
$('.pagination > li:first-child > a').html('');
$('.pagination > li:first-child > a').css({
    'height' : '100%'
})
$('.pagination > li:first-child > a').append('<i class="fas fa-angle-left"></i>')
$('.pagination > li:last-child > a').html('');
$('.pagination > li:last-child > a').css({
    'height' : '100%'
})
$('.pagination > li:last-child > a').append('<i class="fas fa-angle-right"></i>')


// On vérifie les paramètres de l'url pour attribuer le style 'selected' correspond au paramètre dans l'url
$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
       return null;
    }
    return decodeURI(results[1]) || 0;
}
    if ($.urlParam('filter') === 'asc-price'){
        console.log('opt');
        $('#opt_asc').attr('selected', 'selected');
        $('#opt_asc').siblings().removeAttr('selected');
        // $('#opt_none').removeAttr('selected');
    }else if($.urlParam('filter') === 'desc-price'){
        $('#opt_desc').attr('selected', 'selected');
        $('#opt_desc').siblings().removeAttr('selected');

    }else{
        $('#opt_none').attr('selected', 'selected');
        $('#opt_none').siblings().removeAttr('selected');

    }
})

