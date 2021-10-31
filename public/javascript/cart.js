//  Ajout au panier d'un produit

function addCart(id)
{
  let nbProduct = $('#nb-products');
  console.log($('btn_add_cart'));
  btn = $('btn_add_cart')
  
  $.ajax({
   url: '/panier/add/' + id + '/?action=addCart&size='+ nbProduct.attr('data-size') + '&nbProduct=' + nbProduct.val(),
   type: 'GET',
   dataType: 'html',
   success: function(msg) {
     let notif = $('.notification p');
     let notifVal = notif.html();
     notifVal = parseInt(notifVal) +  parseInt(nbProduct.val());
     notif.html(notifVal);
     console.log(msg);
     let value = $('#nb-products').val();
     let result = JSON.parse(msg);
   
     if (nbProduct.attr('data-size') == 'stockSizeOne'){
        if (value == result[0]){
           window.location.replace('https://localhost:8000/produit/'+id+'/');
        }
     }
     else if(nbProduct.attr('data-size') == 'stockSizeTwo'){
        if (value == result[1]){
           window.location.replace('https://localhost:8000/produit/'+id+'/');
        }
     }
     else if(nbProduct.attr('data-size') == 'stockSizeThree'){
         console.log(result[2]);
        if (value == result[2]){
            console.log('ouqqqhh');
           window.location.replace('https://localhost:8000/produit/'+id+'/');
        }
     }

     
    $('#load_button').load('https://localhost:8000/produit/'+id+'/ #load_button');
    $('#load_block').load('https://localhost:8000/produit/'+id+'/ #load_block');

     console.log(result);
     console.log(value);
     $('.addCart-msg').show();
     setTimeout(function(){
       $('.addCart-msg').hide();
     }, 4000)
   },
   error: function(){

   }
})
}

function removeCart(id, size)
{


  $.ajax({
   url: '/panier/remove/' + id + '/?action=removeCart&size='+size,
   type: 'GET',
   dataType: 'html',
   success: function (code_html, statut) {
    let trProduct = $('[data-product-id-size='+id+'-'+size+']');
    console.log(trProduct);
    trProduct.css({
        'display' : 'none'
    });
    // let trGiftCard = $('[data-giftCard-id='+id+']');
    // trGiftCard.css({
    //     'display' : 'none'
    // });

    // On cache le tfoot avec le total si il ne reste aucune ligne de tr visible
    let trCart = $('.tr-cart');
    $(trCart).each(function(index){ 
        let i = 0;
        index++;
        if($(this).css('display') === "none"){
            console.log($(trCart).length);
            if ($(trCart).length === index){
                $('tfoot').css({
                    'display' : 'none'
                })
                $('#btn_saveCart').css({
                    'display' : 'none'
                })
                $('#btn_validateOrder').css({
                    'display' : 'none'
                })
                $('#btn_continue').removeClass('disabled');
                $('#btn_continue').addClass('active');
            }

        }
    })
    // on enlève le quantité dans la notification du panier en haut de la page
    let nbProductsCart = $('#nb_notif').html();
    let nbTrQuantity = trProduct.children('.quantity').html()
    let nbCurrentCart = nbProductsCart - nbTrQuantity;
    $('#nb_notif').html(nbCurrentCart);
   },
   error: function(){

   }
})
}

$(document).ready(function (){
    $('.disabled').
    on('mouseover', function(){
        $(this).addClass("active");
        $(this).removeClass("disabled");
        $('#active').removeClass("active");

    })
    .on('mouseleave', function(){
        $(this).removeClass("active");
        $(this).addClass("disabled");
        $('#active').addClass("active")
    })
})
