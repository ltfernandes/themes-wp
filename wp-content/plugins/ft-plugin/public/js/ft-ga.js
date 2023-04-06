jQuery(document).ready(function($) {

    var tag = getGtag();

    if(tag){
        console.log('Frameticket/Analytics GA4');

        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
      
        gtag('config', tag);

        $(".ga-search").on("submit", function() {
            var busca = $('#q').val();
            if (busca) {
                console.log('GA Busca');
                gtag('event', 'search', {
                    search_term: busca
                });
            }
        });

        $(".ga-login").on("submit", function() {
            gtag('event', 'login', {
                method: 'Login no Portal'
            });
        });

        $(".ga-register").on("submit", function() {
            gtag('event', 'sign_up', {
                method: 'Novo Cadastro no Portal'
            });
        });
    }
   
});

function getGtag(){
    return ($('#frameticket-mktplace-ga-js').attr('gtag')) ? $('#frameticket-mktplace-ga-js').attr('gtag') : '';
}

function impressaoGA() {
    var tag = getGtag();

    if(tag){
        console.log('Impressão GA');
        var itens = [];
        var posicao = 0;
        var data = $('#data_visita').val();
        var cupom = $('#cupom').val();
        var title = $('#ide').attr('rel-title');

        $(".products").each(function() {
            posicao++;
            var id = $(this).attr('ga-id');
            var name = $(this).attr('ga-name');
            var price = $(this).attr('ga-price');
            
            itens[posicao] = {
                "item_id": (id)?id:0,
                "item_name": name,
                "coupon": cupom,
                "item_list_name": 'Lista de Produtos',
                "item_list_id": 'related_products',
                "item_brand": title,
                "item_category": "INGRESSO",
                "affiliation": title,
                "item_variant": data,
                "index": posicao,
                "price": parseFloat(price),
                "currency": 'BRL'
            };
        });
        
        gtag('event', 'view_item_list', {
            items: itens,
            item_list_name: 'Lista de Produtos',
            item_list_id: 'related_products'
        });
    }
}

function selecionadoGA(id) {
    var tag = getGtag();

    if(tag){
        console.log('GA Seleciona item');
        gtag('event', 'select_content', {
            content_type: 'product',
            item_id: id
        });
    }
}
/**
 * Botão adicionar no carrinho
 */
function addCarrinhoGA() {
    var tag = getGtag();

    if(tag){
        console.log('GA Add Carrinho');
        var itens = [];
        var posicao = 0;
        var cupom = $('#cupom').val();
        var total = 0;
        var title = $('#ide').attr('rel-title');
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
                    "item_id": (id) ? id : 0,
                    "item_name": name,
                    "coupon": cupom,
                    "item_brand": title,
                    "item_category": "INGRESSO",
                    "item_variant": data,
                    "item_list_name": 'Lista de Produtos',
                    "item_list_id": 'related_products',
                    "price": price,
                    "quantity": quant,
                    "currency": 'BRL'
                };
            }
        });
        
        gtag('event', 'add_to_cart', {
            currency: 'BRL',
            items: itens,
            value: parseFloat(total)
        });
    }
}

/**
 * botão para acrescentar no carrinho
 * @param string id Id do evento-index 
 */
function addCarrinhoItemGA(id_event, id) {
    var tag = getGtag();

    if(tag){
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
                "item_id": (id) ? id : 0,
                "item_name": nome,
                "item_brand": evento,
                "item_category": "INGRESSO",
                "item_variant": visita,
                "item_list_name": 'Carrinho',
                "item_list_id": 'carrinho',
                "price": preco,
                "quantity": quant,
                "currency": 'BRL'
            };
        }
        
        gtag('event', 'add_to_cart', {
            currency: 'BRL',
            items: itens,
            value: parseFloat(total)
        });
    }
}

function delCarrinhoItemGA(id_event, id) {
    var tag = getGtag();

    if(tag){
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
                "item_id": (id) ? id : 0,
                "item_name": nome,
                "item_brand": evento,
                "item_category": "INGRESSO",
                "item_variant": visita,
                "item_list_name": 'Carrinho',
                "item_list_id": 'carrinho',
                "price": preco,
                "quantity": quant,
                "currency": 'BRL'
            };
        }
        
        gtag('event', 'remove_from_cart', {
            currency: 'BRL',
            items: itens,
            value: parseFloat(total)
        });
    }
}

function initCheckoutGA() {
    var tag = getGtag();

    if(tag){
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
                        "item_id": (id) ? id : 0,
                        "item_name": nome,
                        "item_brand": evento,
                        "item_category": "INGRESSO",
                        "item_variant": visita,
                        "item_list_name": 'Carrinho',
                        "item_list_id": 'carrinho',
                        "price": preco,
                        "quantity": quant,
                        "currency": 'BRL'
                    };
                    posicao++;
                }
            });
        });
        
        gtag('event', 'begin_checkout', {
            currency: 'BRL',
            items: itens,
            value: total
        });
    }
}

function pagamentoGA(fpgto) {
    var tag = getGtag();

    if(tag){
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
                        "item_id": (id) ? id : 0,
                        "item_name": nome,
                        "item_brand": evento,
                        "item_category": "INGRESSO",
                        "item_variant": visita,
                        "item_list_name": 'Carrinho',
                        "item_list_id": 'carrinho',
                        "price": preco,
                        "quantity": quant,
                        "currency": 'BRL'
                    };
                    posicao++;
                }
            });
        });
        
        gtag('event', 'add_payment_info', {
            currency: 'BRL',
            items: itens,
            payment_type: fpgto,
            value: total
        });
    }
}

function compraFinalizadaGA(id_pedido) {
    var tag = getGtag();

    if(tag){
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
                        "item_id": (id) ? id : 0,
                        "item_name": nome,
                        "item_brand": evento,
                        "item_category": "INGRESSO",
                        "item_variant": visita,
                        "item_list_name": 'Carrinho',
                        "item_list_id": 'carrinho',
                        "price": preco,
                        "quantity": quant,
                        "currency": 'BRL'
                    };
                    posicao++;
                }
            });
        });
        
        gtag('event', 'purchase', {
            currency: 'BRL',
            items: itens,
            transaction_id: id_pedido,
            value: total
        });
    }
}