

$(document).ready(function () {
    

  $('.size_block').on('click', function(){
    $(this).css({
        'background' : 'pink'
    })

    $('#msg_insuffisant').hide();
    $('#nb-products').val('1');

    // $(this).attr('data-size', $(this).children('p').html());
    dataAction = $(this).data('action');
    $(this).siblings().css({
        'background' : 'white'
    })

    // on affiche le prix en fonction de la taille séléctionné
    if($(this).attr('data-size') == "stockSizeOne"){
      $('#priceSizeOne').parent().siblings().hide();
      $('.price-style').hide();
      $('#priceSizeOne').show();
    }
    else if($(this).attr('data-size') == "stockSizeTwo"){
      $('#priceSizeTwo').parent().siblings().hide();
      $('.price-style').hide();
      $('#priceSizeTwo').show();
    }
    else if($(this).attr('data-size') == "stockSizeThree"){
      $('#priceSizeThree').parent().siblings().hide();
      $('.price-style').hide();
      $('#priceSizeThree').show();
    }

    // console.log()
    $('#btn_add_cart').hide();
    $('#' + dataAction).show();
    $('#' + dataAction).siblings().hide();
    let input = $('#nb-products');
    $(input).attr("data-size", $(this).attr('data-size'));
    $dataThis = $('#nb-products').data('product-id');
      $inputData = $(this).attr('data-size');
      $value = $('#nb-products').val();
      $size = $('.size_block[data-size]');
      
      let nbProducts = $('.nb-products').val();
      if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
              type: "GET",
                url: "/produit/"+$dataThis+"/stock/verification",
                data: {
                    'input' : $value
                },
                dataType: "json",
                success: function(arr){
                  // if($size )
                  // console.log(size.attr('data-size'));
                  $.each(arr, function($name, $stock){
                    // console.log($name);
                    console.log(arr);
                    $value = parseInt($value);
                    if($name == $inputData){
                        if($stock > 1){
                          $('#good_stock').show();
                          $('#low_stock').hide();
                          $('#no_stock').hide();
                        }
                        else if($stock == 1){
                          console.log();
                            $('#low_stock').show();
                            $('#no_stock').hide();
                            $('#good_stock').hide();
                        }
                        else if($stock < 1){
                            $('#no_stock').show();
                            $('#low_stock').hide();
                            $('#good_stock').hide();
                        }
                        if($stock < $value){
                          $('#msg_insuffisant > span').html($stock);
                          $('#msg_insuffisant').show();
                          $('.btn_add_cart').attr('disabled', 'disabled');
                          $('#low_stock').hide();
                          $('#good_stock').hide();
                          $('#no_stock').hide();
                        }
                        else{
                          $('.btn_add_cart').removeAttr('disabled');
  
                        }
                      console.log($stock, parseInt($value));
                    }

                  })
                }
            })

})

    let searchRequest = null;
    // evenement pour connaitre la valeur de l'input qui ajoute le nombre de produits au panier
    $('#nb-products').on('keyup mouseup' ,function(){

      $('#low_stock').hide();
      $('#good_stock').hide();
      $('#no_stock').hide();


      $dataThis = $(this).data('product-id');
      $inputData = $(this).attr('data-size');
      $value = $(this).val();
      $size = $('.size_block[data-size]');
      
      let nbProducts = $('.nb-products').val();
      if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
              type: "GET",
                url: "/produit/"+$dataThis+"/stock/verification",
                data: {
                    'input' : $value
                },
                dataType: "json",
                success: function(arr){
                  // if($size )
                  // console.log(size.attr('data-size'));
                  $.each(arr, function($name, $stock){
                    // console.log($name);
                    console.log($value);
                    $value = parseInt($value);

                    if($name == $inputData){
                      console.log('OKAYYY');
                      if($stock > 1){
                        $('#good_stock').show();
                        $('#low_stock').hide();
                        $('#no_stock').hide();
                        $('#msg_insuffisant').hide();
                      }
                      else if($stock == 1){
                          $('#low_stock').show();
                          $('#no_stock').hide();
                          $('#good_stock').hide();
                          $('#msg_insuffisant').hide();
                      }
                      else if($stock < 1){
                        console.log('hey');
                          $('#no_stock').show();
                          $('#low_stock').hide();
                          $('#good_stock').hide();
                          $('#msg_insuffisant').hide();
                      }

                      if($stock < $value){
                        $('#msg_insuffisant > span').html($stock);
                        $('#msg_insuffisant').show();
                        $('.btn_add_cart').attr('disabled', 'disabled');
                        $('#low_stock').hide();
                        $('#good_stock').hide();
                        $('#no_stock').hide();
                      }
                      else{
                        $('.btn_add_cart').removeAttr('disabled');

                      }
                    console.log($stock, $value);
                  }

                  })
                  
                  
                }
            })
    })

   

    // Images multiples produit 
    $(function () {
        let productL = $('.product-L');
        let productSmall = $('.product-small');

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

    //  Affichage de la moyenne des avis representé par des étoiles 
    var averageStars = $('.average-rating-star');
    var averageRate = $('#average_number');
    var nearestNomber = Math.round(averageRate.val());
    if(!Number.isInteger(averageRate.val())){ 
      if(averageRate.val() == 0.0){
        $(averageStars).removeClass('fas fa-star').addClass('far fa-star');
      }else{ 
        $(averageStars).removeClass('far fa-star').addClass('fas fa-star');
        $('.average-rating-star#average-rating-' + nearestNomber + ' ~ .average-rating-star').removeClass('fas fa-star').addClass('far fa-star');
      }
    }
    if(averageRate.val() > nearestNomber){
        nearestNomber++
        $('#average-rating-' + nearestNomber).removeClass('far fa-star').addClass('fas fa-star-half');
    }
    else{
    }

    // notation par avis avec les étoiles
    var avis = $('.avis');
    var avisStars = $('.avis-rating-star');
    var avisRate = $('.avis-number');

    avis.each(function(index){
      var avisVal = $(this).find('.avis-number').val();
      $(this).find('.avis-rating-star').addClass('fas fa-star')
      $(this).find('.avis-rating-' + $(this).find('.avis-number').val() + ' ~ .avis-rating-star').removeClass('fas fa-star').addClass('far fa-star');
      console.log($(this).find('.avis-rating-star.avis-rating-' + $(this).find('.avis-number').val() + ' ~ .avis-rating-star'));

    })


    // message si la notation n'est pas rempli
    $('#avis_btn').on('click', function(){
        if ($('.number-rating').val() === "")
        {
          $('#msg_avis_required').fadeIn();
          setTimeout(function(){
            $('#msg_avis_required').fadeOut();
          }, 4000)
        }
        else{
        }
    })
   

    //message success formulaire Avis
    $('#success').delay(7000).fadeOut(2000);

    // affichage description et avis
    $('#avis').on('click', function(){
      $(this).addClass('black');
      $(this).removeClass('grey');
      $('#description_title').addClass('grey');
      $('#description_title').removeClass('black');
      $('#Avis').show();
      $('#p_description').hide();
      $('#p_dimensions').hide();
      $('#products_complements').hide();
    })
    $('#description_title').on('click', function(){
      $(this).addClass('black');
      $(this).removeClass('grey');
      $('#avis').addClass('grey');
      $('#avis').removeClass('black');
      $('#p_description').show();
      $('#p_dimensions').show();
      $('#Avis').hide();
      $('#products_complements').show();
    })

});

 //click sur le bouton de signalement zero stock du produit avec email
 $('#btn_stock_mail').on('click', function(){
   console.log('sa fonctionne');
  let id = $(this).data('id');
  let email = $('#zero_stock_mail').val();
  

  // Si l'adresse email est valid on fait une requête Ajax 
  if (emailIsValid(email) == true){
    $.ajax({
      type: "GET",
        url: "/ajax/produit/"+id+"/zero-stock/signalement?email="+email+"",
        data: {
            'email' : email
        },
        dataType: "text",
        success: function(){
          // si la requête ajax réussit on affiche le message qui indique l'envoi d'un email
          $('#zero_stock_msg_ok').fadeIn().delay(100000).fadeOut();
    }
  });
  }
  else{
    // On affiche le message d'erreur
    $('#zero_stock_msg_error').fadeIn().delay(4000).fadeOut();
  }
  
})

function emailIsValid(email){
return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
}

$('.slide').slick({
  centerMode: true,
        centerPadding: '60px',
        dots: true, 
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true
});

$('.slick-prev').html('');
$('.slick-prev').append('<i class="fas fa-angle-left"></i>');
$('.slick-next').html('');
$('.slick-next').append('<i class="fas fa-angle-right"></i>');

// ajout d'un produit -- click sur le bouton pour afficher input taille
$(document).ready(function () {
  let i = 0;
  $('#add_size').on('click', function(){
    i++;
    console.log($(this).index());
    let size = $('.form-size');
    // let indexSize = size.index();
    // indexSize++;
    console.log(size.index());
    $(size[i]).css({
      'display' : 'flex'
    })
  })
})
