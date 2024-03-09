$.ajax({
    url: "http://callcenter.app.br/api/ramais",
    type: "GET",
    success: function(data){                
        for(let i in data){
            $grayClass = !data[i].online ? 'cinza' : '';
            $('#cartoes').append(`<div class="cartao ${$grayClass}">
                                <div>${data[i].nome}</div>
                                <div>${data[i].agente}</div>
                                <span class="${data[i].status} icone-posicao"></span>
                              </div>`)
        }
        
    },
    error: function(){
        console.log("Errouu!")
    }
});