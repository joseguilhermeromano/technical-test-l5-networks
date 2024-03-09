$.ajax({
    url: "http://callcenter.app.br/api/ramais",
    type: "GET",
    success: function(data){                
        for(let i in data){
            $('#cartoes').append(`<div class="cartao">
                                <div>${data[i].nome}</div>
                                <span class="${data[i].status} icone-posicao"></span>
                              </div>`)
        }
        
    },
    error: function(){
        console.log("Errouu!")
    }
});