$(document).ready(function (){

    // Linear Order

    let url = window.location.pathname;
    let urlOrderRecap = window.location.href.indexOf('recapitulatif');
    let urlOrderValidate = window.location.href.indexOf('confirmation');
    let urlDelivery = '/commande/livraison/type';
    let urlAdress = '/commande/informations-adresse';
     

    if (url == urlDelivery || url == urlAdress || urlOrderRecap > -1 || urlOrderValidate > -1)
    {
        console.log('zut');
    $('.circle-order').eq(0).addClass('circle-active');
    $('.text-circle').eq(0).addClass('text-circle-active');
    }
    if(url == urlAdress || urlOrderRecap > -1 || urlOrderValidate > -1){
    $('.circle-order').eq(1).addClass('circle-active');
    $('.text-circle').eq(1).addClass('text-circle-active');
    $('.line-order').eq(0).addClass('line-order-active');
    }
    if(urlOrderRecap > -1 || urlOrderValidate > -1){
        $('.circle-order').eq(2).addClass('circle-active');
        $('.text-circle').eq(2).addClass('text-circle-active');
        $('.line-order').eq(1).addClass('line-order-active');
    }
    if(urlOrderValidate > -1){
        $('.circle-order').eq(3).addClass('circle-active');
        $('.text-circle').eq(3).addClass('text-circle-active');
        $('.line-order').eq(2).addClass('line-order-active');
    }


    // Summary

    $('#btn_delivery').on('click', function(){

    })

    // Adress-Order

    let formAdress = $('#adress_form');
    let adressOther = $('.not-checked');
    let adressClient = $('.checked');
    let checkedBlock = $('.checked-block');
    let notCheckedBlock = $('.not-checked-block')

    $(adressOther).on('click', function(){
        $(formAdress).show();
        $(this).removeClass('not-checked');
        $(this).addClass('checked');
        $(adressClient).removeClass('checked');
        $(adressClient).addClass('not-checked');
        $(checkedBlock).removeClass('checked-block');
        $(checkedBlock).addClass('not-checked-block');
        $(notCheckedBlock).removeClass('not-checked-block');
        $(notCheckedBlock).addClass('checked-block');

    })
    $(adressClient).on('click', function(){
        $(formAdress).hide()
        $(this).removeClass('not-checked');
        $(this).addClass('checked');
        $(adressOther).removeClass('checked');
        $(adressOther).addClass('not-checked');
        $(checkedBlock).removeClass('not-checked-block');
        $(checkedBlock).addClass('checked-block');
        $(notCheckedBlock).removeClass('checked-block');
        $(notCheckedBlock).addClass('not-checked-block');
    })
    $('#order_info_supp_commentGiftCard').attr("disabled","disabled")
    $('#order_info_supp_giftCard').on('click', function(){
            if($(this).prop("checked") == true){
                $('#order_info_supp_commentGiftCard').removeAttr('disabled');
                console.log("checked true");

            }
            else if($(this).prop("checked") == false){
                // $('#order_info_supp_commentGiftCard').val("");
                $('#order_info_supp_commentGiftCard').prop("disabled",true);
                $('#order_info_supp_commentGiftCard').val("");
                console.log("checked False");

            }
    })
})