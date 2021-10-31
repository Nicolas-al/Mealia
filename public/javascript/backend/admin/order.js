$(document).ready(function(){
    let caretDownHide = $('.order-caret-down-hide');
    let caretDownShow = $('.order-caret-down');

    // On affiche la div avec les infos de commandes
    $('.order-caret-down-hide').click(function(){ 
        let id = $(this).attr('id');
        idInfo = '#order_info' + id;
        $(idInfo).show();
        $(this).hide();
        $(this).next('div').show();
    })
    // On cache la div avec les infos de commandes
    $('.order-caret-down').click(function(){ 
        let id = $(this).attr('id');
        idInfo = '#order_info' + id;
        $(idInfo).hide();
        $(this).hide();
        $(this).prev('div').show();
    })
    $('.off').hide();
    $('.on').addClass('d-flex');
    $('.on').on('click', function(){
        $(this).siblings('.comment-gift').show();
        $(this).hide();
        $(this).removeClass('d-flex');
        $(this).siblings('.off').addClass('d-flex');
        $(this).siblings('.off').show();
    })
    $('.off').on('click', function(){ 
        $(this).siblings('.comment-gift').hide();
        $(this).hide();
        $(this).removeClass('d-flex')
        $(this).siblings('.on').addClass('d-flex');
        $(this).siblings('.on').show();
    })

    $('.three-points-not-active').on('click', function(){
        $(this).siblings('.block_three_points').show();
        $(this).hide();  
    })
    $('.three-points').on('click', function(){
        $(this).parent().hide();
        $(this).parent().parent().find('.three-points-not-active').show();  
    })
    

    // quand on click sur expedier la commande, un form apparait pour inscrire le numéro de suivi
    $('.block_three_points > div:nth-child(2) > p:first-child').on('click', function(){
        console.log($(this).parent().find('#form_order_expedition'));
       $(this).parent().find('#form_order_expedition').show();
    })

   
})
// function refundOrder($id){
//     $.ajax({
//         url: '/admin/commandes/order/refund/' + $id,
//         type: 'GET',
//         dataType: 'html',
//         success: function (code_html, statut) {
//         }
//     })
// }