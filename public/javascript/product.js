$(document).ready(function () {
    // MDB Lightbox Init
    $(function () {
        let productL = $('.product-L');
        let productSmall = $('.product-small');
        console.log(productSmall);
        console.log(productL);

        //évenement permettant d'afficher les images produits au click sur la miniature produit
        for(var i=0 ; i<productSmall.length ; i++){ 
            $(productSmall).on("click", function(){
            let index = $(productSmall).index(this);
            let indexL = index + 1;        
                for(var j=0; j<productL.length ; j++){
                    if ($(productL[j]).css('display') === "block"){
                    $(productL[j]).hide();
                    }
                }
            $(productL[indexL]).show();
            });
        }

    });

    // formulaire étoiles avis
    function setRating(rating) {
        $('#rating-input').val(rating);
        // fill all the stars assigning the '.selected' class
        $('.rating-star').removeClass('far fa-star').addClass('selected');
        // empty all the stars to the right of the mouse
        $('.rating-star#rating-' + rating + ' ~ .rating-star').removeClass('selected').addClass('far fa-star');
      }
      
      console.log($('#rating-input').val())
      
      $('.rating-star')
      .on('mouseover', function(e) {
        var rating = $(e.target).data('rating');
        // fill all the stars
        $('.rating-star').removeClass('far fa-star').addClass('fas fa-star');
        // empty all the stars to the right of the mouse
        $('.rating-star#rating-' + rating + ' ~ .rating-star').removeClass('fas fa-star').addClass('far fa-star');
      })
      .on('mouseleave', function(e) {
        // empty all the stars except those with class .selected
        $('.rating-star').removeClass('fas fa-star').addClass('far fa-star');
      })
      .on('click', function(e) {
        $(this) .off('mouseenter').off('mouseleave'); 
        var rating = $(e.target).data('rating');
        setRating(rating);
        $('#rating-' + rating).css({
            'border' : 'none'
        })
      })
      .on('keyup', function(e){
        // if spacebar is pressed while selecting a star
        if (e.keyCode === 32) {
          // set rating (same as clicking on the star)
          var rating = $(e.target).data('rating');
          setRating(rating);
        }
      });

    //   Affichage des Etoiles avis

    var stars = $('.average-rating-star');
    var averageRate = $('#average_number');
    console.log(averageRate.val());
    console.log(Math.round(averageRate.val()));
    var nearestNomber = Math.round(averageRate.val());
    if(!Number.isInteger(averageRate.val())){ 
        console.log('superman')
    $(stars).removeClass('far fa-star').addClass('fas fa-star');
    $('.average-rating-star#average-rating-' + nearestNomber + ' ~ .average-rating-star').removeClass('fas fa-star').addClass('far fa-star');
    }
    if(averageRate.val() > nearestNomber){
        nearestNomber++
        $('#average-rating-' + nearestNomber).removeClass('far fa-star').addClass('fas fa-star-half');
    }
    else{
        $('#average-rating-' + nearestNomber).removeClass('fas fa-star').addClass('fas fa-star-half');
    }


    //message success formulaire Avis
    $('#success').delay(7000).fadeOut(2000);
});