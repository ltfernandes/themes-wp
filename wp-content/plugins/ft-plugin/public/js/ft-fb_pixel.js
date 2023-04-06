jQuery(document).ready(function($) {

    var tag = getFBPixel_ID();
    if(tag){
        console.log('Frameticket/Facebook Pixel');

        //Busca
        $(".ga-search").on("submit", function() {
            var busca = $('#q').val();
            if ( busca ) {
                console.log('FB-PIXEL Search');
                fbq('track', 'Search', {search_string : busca});
            }
        });

        //Login no Portal
        $(".ga-login").on("submit", function() {
            console.log('FB-PIXEL Lead');
            fbq('track', 'Lead');
        });

        //Cadastro no Portal
        $(".ga-register").on("submit", function() {
            console.log('FB-PIXEL CompleteRegistration');
            fbq('track', 'CompleteRegistration');
        });
    }
});

function getFBPixel_ID(){
    return ($('#frameticket-mktplace-fb_pixel-js').attr('FBPixel_ID')) ? $('#frameticket-mktplace-fb_pixel-js').attr('FBPixel_ID') : '';
}


function impressaoFBPixel() {
   return false;
}


function eventViewFBPixel(name_event) 
{        
    var tag = getFBPixel_ID();
    if(tag){
        console.log('FB-PIXEL ViewContent-Evento');
        fbq('track', 'ViewContent', { 
            content_name: name_event,
            content_category: 'Evento',
        });
    }
}


function selecionadoFBPixel(id) 
{    
    var tag = getFBPixel_ID();
    if(tag){
        addCarrinhoFBPixel();
    }
}

/**
 * Botão adicionar no carrinho
 */
function addCarrinhoFBPixel() 
{    
    var tag = getFBPixel_ID();
    if(tag){
        console.log('FB-PIXEL AddToCart');
        var itens = [];
        var posicao = 0;
        var total = 0;
        var evento = $('#ide').attr('rel-title');
        var data = $('#data_visita').val();

        $(".products").each(function() {
            posicao++;
            var id = $(this).attr('ga-id');
            var name = $(this).attr('ga-name');
            var price = parseFloat($(this).attr('ga-price'));
            var quant = parseInt($('#quant_' + id).val());
            
            if (quant >= 1) {
                total += (price * quant);
                itens[posicao] = {
                    "id": (id) ? id : 0,
                    "price": price,
                    "quantity": quant,
                };
            }
        });

        fbq('track', 'AddToCart', { 
            value: parseFloat(total),
            items: itens,
            currency: 'BRL',
            inStock : 'Yes',
            content_name: evento,
            content_type: 'product', // Required for Dynamic Product Ads
        });
    }
}

/**
 * botão para acrescentar no carrinho
 * @param string id Id do evento-index 
 */
function addCarrinhoItemFBPixel(id_event, id) 
{
    var tag = getFBPixel_ID();
    if(tag){    
        console.log('FB-PIXEL AddToCart');
        var itens = [];

        var evento = $('#cart-event-' + id_event).attr('ga-title');
        var visita = $('#cart-event-' + id_event).attr('ga-date');

        var preco = parseFloat($('#item-cart-' + id_event + '-' + id).attr('ga-price'));
        var quant = parseInt($('#item-cart-' + id_event + '-' + id).html());
        var nome = $('#item-cart-' + id_event + '-' + id).attr('ga-name');
        
        var total = 0;
        if (quant >= 1) {
            total += (preco * quant);
            itens[0] = {
                "id": (id) ? id : 0,
                "price": preco,
                "quantity": quant,
            };
        }

        fbq('track', 'AddToCart', { 
            value: parseFloat(total),
            currency: 'BRL',
            inStock : 'Yes',
            content_name: evento,
            content_type: 'product', // Required for Dynamic Product Ads
            content_ids: id_event, // Required for Dynamic Product Ads
            contents: itens,
        });
    }
}




function initCheckoutFBPixel() 
{    
    var tag = getFBPixel_ID();
    if(tag){
        console.log('FB-PIXEL initCheckout');

        var itens = [];
        var total = 0;
        var posicao = 0;
        var evento = '';
        $(".cart-event").each(function() {

            id_event = $(this).attr('ga-id-event');
            evento = $("#cart-event-"+id_event).attr('ga-event-title');

            $(".cart-event-products-"+id_event).each(function() {
                var id = ($(this).attr('ga-id')) ? $(this).attr('ga-id') : 0;
                var quant = parseInt($('#item-cart-' + id_event+'-'+id).html());
                var preco = parseFloat($(this).attr('ga-price'));
                var nome = $(this).attr('ga-name');

                if (quant >= 1) {
                    total += (preco * quant);
                    itens[posicao] = {
                        "id": (id) ? id : 0,
                        "price": preco,
                        "quantity": quant
                    };
                    posicao++;
                }
            });
        });
               
        fbq('track', 'InitiateCheckout', {             
            value: total,
            currency: 'BRL',
            content_name: evento,
            content_ids: id_event,
            num_items: posicao,
            contents: itens,

        });
    }

}




function pagamentoFBPixel() 
{   
    var tag = getFBPixel_ID();
    if(tag){
        console.log('FB-PIXEL AddPaymentInfo');
        var itens = [];
        var total = 0;
        var posicao = 0;

        $(".cart-event").each(function() {

            var id_event = $(this).attr('ga-id-event');

            var evento = $("#cart-event-"+id_event).attr('ga-event-title');
            var visita = $("#cart-event-"+id_event).attr('ga-event-date');

            $(".cart-event-products-"+id_event).each(function() {
                var id = ($(this).attr('ga-id')) ? $(this).attr('ga-id') : 0;
                var quant = parseInt($('#item-cart-' + id_event+'-'+id).html());
                var preco = parseFloat($(this).attr('ga-price'));
                var nome = $(this).attr('ga-name');

                if (quant >= 1) {
                    total += (preco * quant);
                    itens[posicao] = {
                    "id": (id) ? id : 0,
                    "quantity": quant
                    };
                    posicao++;
                }
            });
        });

        fbq('track', 'AddPaymentInfo', { 
            value: total,
            currency: 'BRL',
            contents: itens,
        });
    }
}



function compraFinalizadaFBPixel(id_pedido) 
{   
    var tag = getFBPixel_ID();
    if(tag){
        console.log('FB-PIXEL Purchase');
        var itens = [];
        var total = 0;
        var posicao = 0;

        $(".cart-event").each(function() {

            id_event = $(this).attr('ga-id-event');
            evento = $("#cart-event-"+id_event).attr('ga-event-title');

            $(".cart-event-products-"+id_event).each(function() {
                var id = ($(this).attr('ga-id')) ? $(this).attr('ga-id') : 0;
                var quant = parseInt($('#item-cart-' + id_event+'-'+id).html());
                var preco = parseFloat($(this).attr('ga-price'));
                var nome = $(this).attr('ga-name');

                if (quant >= 1) {
                    total += (preco * quant);
                    itens[posicao] = {
                        "id": (id) ? id : 0,
                        "price": preco,
                        "quantity": quant
                    };
                    posicao++;
                }
            });
        });

        fbq('track', 'Purchase', { 
            value: total,
            currency: 'BRL',
            content_type: 'product',
            content_name: evento,
            content_ids: id_pedido,
            num_items: posicao,
            contents: itens,
        });
    }
}