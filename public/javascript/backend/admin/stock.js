    function updateStock(i){
        let stockElt = document.getElementById('stock-' + i);
        let btnStock = document.getElementById('update_stock_' + i);
        let btnSave = document.getElementById('save_stock_' + i);
        let sizes = document.querySelectorAll('.stock-'+ i);
        let sizeDiv = document.getElementById('size_stock-'+ i);

       
        // stockElt.textContent = "";
        // stockElt.append(inputStock);
        // inputStock.style.width = "50px";
        // inputStock.style.textAlign = "center";
        btnStock.classList.add('d-none');
        btnSave.classList.remove('d-none');
        btnStock.classList.remove('update-stock');

        sizes.forEach(function(size){
            sizeElt = size.lastChild.textContent;
            console.log(sizeElt);
            let inputStock = document.createElement('input');
            inputStock.type = "number";
            inputStock.style.textAlign = "center";
            inputStock.style.width = "50px";
            inputStock.style.margin = 'auto';
            inputStock.id = 'input_stock_' + i;
            inputStock.value = sizeElt;
            size.childNodes[1].remove();
            size.append(inputStock);
        });
        if(sizes.length < 2){
            let inputStock = document.getElementById('input_stock_'+ i);
            inputStock.parentElement.style.width = '50%';
        }
        else{
            // console.log(sizeDiv.children.children[1].children[1]);
            // let input = sizeDiv.children.children[1].children[1];
            
            // if(input){
            //     input.style.marginLeft = "-1px";
            // }
        }
        
        
}

    function saveStock(i, productId){

        let sizeDiv = document.getElementById('size_stock-'+ i);
        let sizes = document.querySelectorAll('.stock-'+ i);
        let inputStock = $('#input_stock_' + i);
        let stockElt = $('#stock-' + i);
        let inputOneVal = "";
        let inputTwoVal = "";
        let inputThreeVal = "";
        if(sizes.length < 2){
            inputOneVal = sizes[0].children[1].value;
        }
        else if(sizes.length < 3){ 
            inputOneVal = sizes[0].children[1].value;
            inputTwoVal = sizes[1].children[1].value;
        }
        else{
            inputOneVal = sizes[0].children[1].value;
            inputTwoVal = sizes[1].children[1].value;
            inputThreeVal = sizes[2].children[1].value;
        }
       
        $.ajax({
            url: '/admin/produits/stock/?action=updateStock&product=' + i + '&id-product=' + productId + '&stockSizeOne=' + inputOneVal + '&stockSizeTwo=' + inputTwoVal + '&stockSizeThree=' + inputThreeVal,
            type: 'GET',
            dataType: 'html',
            success: function (code_html, statut) { // success est toujours en place, bien sûr !
                let btnSaveStock = $('#save_stock_' + i);
                btnSaveStock.addClass('d-none');
                let btnStock = $('#update_stock_' + i);
                btnStock.removeClass('d-none');
                let pOne = document.createElement('p');
                let pTwo = document.createElement('p');
                let pThree = document.createElement('p');
                if(sizes.length < 2){
                    pOne.textContent = inputOneVal;
                    pOne.style.marginBottom = '0px';
                    sizes[0].append(pOne);
                    if (sizes[0].children[1] != ""){ 
                        sizes[0].children[1].remove();
                    }
                }
                else if(sizes.length < 3){ 
                    let inputOneVal = sizes[0].children[1].value;
                    let inputTwoVal = sizes[1].children[1].value;
                    pOne.textContent = inputOneVal;
                    pTwo.textContent = inputTwoVal;
                    pOne.style.marginBottom = '0px';
                    pTwo.style.marginBottom = '0px';
                    sizes[0].append(pOne);
                    sizes[1].append(pTwo);
                    if (sizes[0].children[1] != ""){ 
                        sizes[0].children[1].remove();
                    }
                    if (sizes[1].children[1] != ""){
                        sizes[1].children[1].remove();
                    }
                }
                else{
                    let inputOneVal = sizes[0].children[1].value;
                    let inputTwoVal = sizes[1].children[1].value;
                    let inputThreeVal = sizes[2].children[1].value;
                    pOne.textContent = inputOneVal;
                    pTwo.textContent = inputTwoVal;
                    pThree.textContent = inputThreeVal;
                    pOne.style.marginBottom = '0px';
                    pTwo.style.marginBottom = '0px';
                    pThree.style.marginBottom = '0px';
                    sizes[0].append(pOne);
                    sizes[1].append(pTwo);
                    sizes[2].append(pThree);
                    if (sizes[0].children[1] != ""){ 
                        sizes[0].children[1].remove();
                    }
                    if (sizes[1].children[1] != ""){
                        sizes[1].children[1].remove();
                    }
                    if (sizes[2].children[1] != ""){
                        sizes[2].children[1].remove();
                    }
                }
              
                
            },
            error: function() {
            }
        })
    }
    

