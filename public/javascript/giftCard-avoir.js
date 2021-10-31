$(document).ready(function () {
    console.log('zut');
    // verification du code avoir ou carte cadeau pour payer la commande
    $('#avantage_btn').on('click', function(e){
        let promoCode = $('#promo_code').val();
        console.log(promoCode);
        $.ajax({ 
        url: '/ajax/code-avantage?',
        type: 'GET',
        data: {'code' : promoCode},
        dataType: "json",
        success: function(statutCode){
            console.log('no-load');

            console.log(statutCode.reponse);
            if(statutCode.reponse === 'code valide')
            {
                console.log('load');
                window.location.pathname = '/commande/livraison/type';
            }
            else{
                console.log('no-load');
            }

            },
        error: function(){
        }
        })
    });

    // Formulaire pour choisir le montant de la carte cadeau
    // on attribut la valeur des 
    $('.block-circle').on('click', function(){
        $(this).siblings().find('input').val("");
        $(this).siblings().find('input').prop("checked", false);
        $(this).siblings().find('div').css({
            'background' : 'black',
            'border' : 'none',
            'box-shadow': 'none',
            'width' : '45px',
            'height' : '45px'
        })
        $(this).find('input').prop("checked", true);
        $(this).find('div').css({
            'background' : '#ff9593',
            'border' : '2px solid white',
            'box-shadow': '0px 0px 5px',
            'width' : '48px',
            'height' : '48px'
        })

        console.log($(this).find('p').data('value'));
        $(this).find('input').val($(this).find('p').data('value'));

    })

})



    
