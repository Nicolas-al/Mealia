$(document).ready(function () {
    
    $('#boutique').on('mouseover', function(){
        $('#block_boutique').show();
        $('#block_boutique').css({
            'display' : 'flex'
        })
    })

    $('#block_boutique').on('mouseleave', function(){
        $('#block_next_papeterie').hide();
        $('#block_next_textil').hide();
        $(this).hide();
    })
    $('#all_products').on('mouseover', function(){
        $('#block_next_papeterie').hide();
        $('#block_next_textil').hide();
    })
    $('#gift_card_nav').on('mouseover', function(){
        $('#block_next_papeterie').hide();
        $('#block_next_textil').hide();
    })
    $('#create_textil').on('mouseover', function(){
        
        $('#block_next_textil').show();
        $('#block_next_papeterie').hide();
    })
    $('#small_papeterie').on('mouseover', function(){
        $('#block_next_textil').hide();
        $('#block_next_papeterie').show();
    })

    //background rose quand la souris est dans un autre élement
    $('#block_next_textil').on('mouseover', function(){
        $('#create_textil').css({
            'background' : '#fef1f0'
        })
    })
    $('#block_next_papeterie').on('mouseover', function(){
        $('#small_papeterie').css({
            'background' : '#fef1f0'
        })
    })
    //background rose quand la souris est dans l'élement
    $('.hover-nav').on('mouseover', function(){
        $(this).css({
            'background' : '#fef1f0'
        })
        $(this).addClass('hover-active');
    })
    $('.hover-nav').on('mouseleave', function(){
        $('.hover-active').css({
            'background' : 'white'
        })
        $('.hover-active').removeClass('hover-active');
    })

    
})
