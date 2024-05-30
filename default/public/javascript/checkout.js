function removeCartFilm(event) {
    let cartBucket = event.target.closest('.cart-bucket');
    let film = cartBucket.getAttribute('data-film');
    $.post( "/chadfilms/customer/checkout",
        {
            remove: {
                item: film
            }
        },
        function (data) {
            if (data.count === 0) {
                document.querySelector('.left-column').remove();
                document.querySelector('.right-column').remove();
                alert('Carro vacío.\nRedirigiendo a página principal');
                window.location.href = "/chadfilms";
            } else {
                cartBucket.remove();
                document.querySelector('#items-subtotal').textContent = 'MXN $' + data.total;
                document.querySelector('#total').textContent = 'MXN $' + data.total;
                document.querySelector('#items-count').textContent = 'Items (' + data.count + ')';
            }
        },
        'json')
        .fail(function() {
            alert('Error al remover artículo');
        });
}

function pay(){
    $.post( "/chadfilms/customer/checkout",
        {
            pay: {
                this: 'doesn\'t matter'
            }
        },
        function(data) {
            if (data == 'Success'){
                alert('Pago exitoso');
                window.location.href = "/chadfilms";
            }
            else
                alert('Error al realizar el pago');
        },
        'text')
        .fail(function() {
            alert('Error al realizar el pago');
        });
}

const remove_buttons = document.querySelectorAll(".btn-remove");
for (const button of remove_buttons) {
    button.addEventListener("click", removeCartFilm);
}

const total_button = document.querySelector(".btn-pay");
total_button.addEventListener("click",pay);
