function fetchAndUpdate() {
    $.ajax({
        url: "http://callcenter.app.br/api/ramais",
        type: "GET",
        success: function(data) {
            $('#cards').empty();
            for (let i in data) {
                let grayClass = !data[i].online ? 'grey' : '';
                $('#cards').append(`<div class="card ${grayClass}">
                                    <div>${data[i].name}</div>
                                    <div>${data[i].agent}</div>
                                    <span class="${data[i].status} icon-position"></span>
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