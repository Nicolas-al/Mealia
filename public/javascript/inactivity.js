
    $url = window.location.pathname;

    console.log($url);

    $(function(){

    setTimeout(function(){
        $.ajax({
            url: '/ajax?inactivité=oui',
            method: "POST",
            data: { 'msg' : 'inactivité'},
            dataType: "text", 
        success: function(){
            location.reload();
        }
        })
    }, 1800000);

    })
    
    

