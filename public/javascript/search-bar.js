
    var searchRequest = null;
    var result = $('.result');

    var searchBlock = $('#block_search');
    var btnSearch = $('#btn_search');
    var closeBlock = $('.fa-times');
    
    btnSearch.on('click', function(){
        searchBlock.css({
            'display' : 'block'
        })
    })
    closeBlock.on('click', function(){
        searchBlock.css({
            'display' : 'none'
        })
    })

    $("#search").keyup(function() {
        var minlength = 3;
        var that = this;
        var value = $(this).val();
        var entitySelector = $("#entitiesNav");
            if (searchRequest != null)
                searchRequest.abort();
                searchRequest = $.ajax({
                type: "GET",
                url: "/search",
                data: {
                    'product' : value
                },
                dataType: "text",
                success: function(msg){
                    //we need to check if the value is the same
                    if (value == $(that).val()) {
                        var result = JSON.parse(msg);
                        $.each(result, function(key, arr) {

                            $.each(arr, function(id, value) {
                                if (key == 'entities') {
                                    if (id != 'error') {
                                        if ($(that).val() != ""){ 
                                            var li = document.getElementsByClassName('result');
                                            if (li.length >= 0){
                                                for (var i=0; i <= li.length ;i++){
                                                    if ($(li[i]).attr('id') == id){
                                                        $(li[i]).siblings().remove();
                                                        $(li[i]).attr('id', id).remove();
                                                    }
                                                }
                                               
                                            }
                                            entitySelector.append('<li class="result" id="'+id+'">'+value+'</li>');
                                            $('.errorLi').remove();
                                            $('.result').mouseenter(function(){
                                                $(this).css({
                                                    'background' : '#100f0f1c'
                                                });
                                                $(this).siblings().css({
                                                    'background' : 'white'
                                                })
                                            })
                                            $('#side_menu').mouseleave(function(){
                                                console.log('yesssss');
                                                $('.result').css({
                                                    'background' : 'white'
                                                })
                                            })
                                            $('.result').on('click', function(){
                                                $('#search').val($(this).html());
                                                $('#search_path').attr("href", window.location.origin + '/admin/fiches-produits/produit/' + id);
                                                $('.result').remove();
                                            })

                                        }
                                        else{
                                        $('#entitiesNav > li').remove();
                                        }
                                    }                                              
                                    else{
                                        $('.result').remove();
                                        entitySelector.append('<li class="errorLi">'+value+'</li>');
                                    }
                                }
                            });
                        });
                    }
                 }
            });
    });