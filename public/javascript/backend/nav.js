$(document).ready(function(e){
    let toggle = $(".dropdown-toggle");
    let menu = $(".drop-menu");

    $(".dropdown-toggle").on("click", function(e) { 
        let index = toggle.index(this);
        $(menu[index]).show(); 
    });
   

    $(".drop-menu").on("mouseleave", function(e){
        $(this).hide();
    })
});
