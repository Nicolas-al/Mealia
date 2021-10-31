$(document).ready(function(){

    // Menu 
 

    $("#menu_order li a").each(function()
    {
        if(this.href==window.location.href)
        {
            $(this).addClass('current');
            $(this).removeAttr('href');
        }
    });
    $("#menu_info li a").each(function()
    {
        if(this.href==window.location.href)
        {
            $(this).addClass('current');
            $(this).removeAttr('href');
        }
    });
    
  

    // afficher le suivi de commande au click
    let btnDetailsOrder = $('.btn-details-order');
    let btnDetailsOrderHide = $('.btn-details-order-hide');
    
    btnDetailsOrder.on('click', function(){
        let index = $('.btn-details-order').index(this);
        let detailsOrder = $('.details-order').eq(index);
        $(detailsOrder).show();
        $(btnDetailsOrderHide).eq(index).show();
        $(this).hide()
    })
    btnDetailsOrderHide.on('click', function(){
        let index = $('.btn-details-order-hide').index(this);
        let detailsOrder = $('.details-order').eq(index);
        $(detailsOrder).hide();
        $(this).hide();
        $(btnDetailsOrder).eq(index).show();
    })
    

    $('#registration_email').val("");
    console.log($('#registration_email')) ;

    // deconnexion après quelques secondes on revient à la page principale
    let url = window.location.pathname;
    let urlLogout = "/compte/deconnexion"
        if (url == urlLogout){
        $(function () {
            setTimeout(function() {
            window.location.replace("/logout");
            }, 6000);
        });
    }

    // notation étoile avis client
    let avis = $('.average-rating-star');
    avis.each(function(index){
        var avisVal = $(this).find('.avis-number').val();
        $(this).find('.avis-rating-star').addClass('fas fa-star')
        $(this).find('.avis-rating-' + $(this).find('.avis-number').val() + ' ~ .avis-rating-star').removeClass('fas fa-star').addClass('far fa-star');
        console.log($(this).find('.avis-rating-star.avis-rating-' + $(this).find('.avis-number').val() + ' ~ .avis-rating-star'));
  
      })

})