const getStatus = (status) => {
    switch(status)
    {
        case 'available':
            return 'Disponível';
        case 'calling':
            return 'Chamando';
        case 'busy': 
            return 'Ocupado';
        case 'paused': 
            return 'Pausado';
        case 'unavailable': 
            return 'Indisponível';
    }
};

function fetchAndUpdate() {
    $.ajax({
        url: "http://callcenter.app.br/api/ramais",
        type: "GET",
        success: function(data) {
            $('#cards').empty();
            for (let i in data) {
                let grayClass = !data[i].online ? 'grey' : '';
                let agentClass = (data[i].agent).toLowerCase();
                $('#cards').append(`<div class="card ${grayClass}">
                                        <span class="${data[i].status} icon-position"></span>
                                        <div class="agent-info">
                                            <div class="circle ${agentClass}"></div>
                                            <div class='description'>
                                                <h2>${data[i].name}</h2>
                                                <h6>${data[i].agent} | ${getStatus(data[i].status)}</h6>
                                            </div>
                                        </div>   
                                  </div>`)
            }

        },
        error: function() {
            console.log("Error!")
        }
    });
}

fetchAndUpdate();

setInterval(fetchAndUpdate, 10000); 