$(document).ready(function(){
    let categories = $('.option-cat');
    console.log(categories.length);
    
    // for (var i=0 ; i < categories.length ; i++){
        $('#categorie.option').on('click', function(){ 
        // $('#categorie option').on('click', function(){
        console.log('salut');
		// e.preventDefault();
        // var optionValue = $('#option-' + i).val();
        // console.log(optionValue)
        // var saisieInput = $('#saisie');
        // saisieInput.text = optionOValue;
		// var concept = $(this).text();
        // });
    });
    // }
});

    function updateStock(i){
        let stockElt = document.getElementById('stock-' + i);
        console.log(stockElt);
        // btnStockElt.disabled = true;
                    let inputStock = document.createElement('input');
                    inputStock.type = "number";
                    inputStock.id = 'input_stock_' + i;
                    console.log(stockElt.textContent);
                    inputStock.value = stockElt.textContent;
                    stockElt.textContent = "";
                    stockElt.append(inputStock);
                    inputStock.style.width = "50px";
                    inputStock.style.textAlign = "center";
                    let btnStock = document.getElementById('update_stock_' + i)
                    btnStock.classList.add('d-none');
                    let btnSave = document.getElementById('save_stock_' + i);
                    btnSave.classList.remove('d-none');
                    console.log(btnStock);
                    btnStock.classList.remove('update-stock');
    }

    function saveStock(i, productId){
        let inputStock = $('#input_stock_' + i);
        let stockElt = $('#stock-' + i);
        console.log(inputStock.val());
        $.ajax({
            url: '/admin/products/stock/?action=updateStock&product=' + i + '&id-product=' + productId + '&stock=' + inputStock.val(),
            type: 'GET',
            dataType: 'html',
            success: function (code_html, statut) { // success est toujours en place, bien sûr !
                console.log(inputStock.val());
                // console.log(inputStock.val())
                let btnSaveStock = $('#save_stock_' + i);
                btnSaveStock.addClass('d-none');
                let btnStock = $('#update_stock_' + i)
                btnStock.removeClass('d-none');
                console.log(stockElt);

                stockElt.text(inputStock.val()); 
                console.log(stockElt);

                if (stockElt.html != ""){ 
                $(inputStock).remove();
                console.log('zut');
            }
                console.log(stockElt);
                
            },
            error: function() {
                console.log('erreur');
            }
        })
     
        // console.log(inputStock.value);
        // console.log(stockElt);
        // console.log(stockElt.textContent);
    }
    

