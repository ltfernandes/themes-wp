$ = jQuery.noConflict();

(function(window, document, $, undefined) {
    "use strict";
    $(document).ready(function() {
        console.log('Frameticket 5.0 - Pagamento');
        $("input[name='fpgto']").change(function()
        {
            $('#div-dados-pagador').show();
            $('#div-submit').show();
            $('#paypal-button-container').hide();
            var fpgto = $(this).val();
            
            if (fpgto == 'CREDITO')   {
                $('#div-cartao').show();

            } else if (fpgto == 'PAYPAL') {
                $('#div-dados-pagador').hide();
                $('#div-cartao').hide();
                $('#div-submit').hide();
                $('#paypal-button-container').show();
            } else {
                $('#div-cartao').hide();
            }
        });
        $("#cartao_num").on("blur", function () {
            var bin = $(this).val().replace(/\s/g, '');
            var id_conta = $(this).attr('id-conta');
            getBrand(bin, id_conta);
        });
    });
}(window, document, jQuery));

function showMsgSuccess(msg){
    $('#msg-callback').html('<div class="alert alert-success">'+msg+'</div>');
}

function showMsgTransaction(msg){
    $('#msg-callback').html('<div class="alert alert-warning">'+msg+'</div>');
}

function showMsgError(msg){
    $('#msg-callback').html('<div class="alert alert-danger">'+msg+'</div>');
}

function clearMsg() {
    $('#msg-callback').html('');
}

function efetuarPagamento() {
    console.log('FT PAYMENT DEFAULT ');
    continuaPagamento();
}

function continuaPagamento(){
        //Verifica se foi tudo preenchido
        var fpgto = $('input:radio[name=fpgto]:checked').val();
        
        if (!fpgto) {
            showMsgError('Por favor, informe a forma de pagamento!')
        }
        //Inicia a transação
        else {
            var valida_termo = ($('#termo_compra').length) ? 'S' : 'N';
            
            if(valida_termo=='S' && $('#termo_compra:checked').val() != 'S'){
                showMsgError('Para prosseguir com o pagamento, é necessário aceitar o termo de compra acima!');
            }
            else{
                $('#btn-pagar').attr("disabled", true);
                var $btn = $('#btn-pagar');
                $btn.button('loading');
                showMsgTransaction('Processo de pagamento iniciado, por favor, aguarde ...');
                initPaymentGW();
            }
        }
}

/**
* Metodo deve ser sobrescrito para alguns gateways
*/
function initPaymentGW(){
    console.log('init payment default');
    initPayment('OK','');
}

/**
* Metodo deve ser sobrescrito para alguns gateways
*/
function getBrand(bin,id_conta){
    if (bin) {
        x_getBrand(bin,id_conta, function(res) {
            
            if (res.brand) {
                $('#bandeira-img').html("<img src='https://frameticket.com.br/bandeiras/" + res.brand + ".png'>");
                $('#bandeira').val(res.brand);
            }
        });
    }
}

function initPayment(status, msg) {
    var $btn = $('#btn-pagar');
    
    if (status=='OK') {
        showMsgTransaction('Processando pagamento ...');
        var post = $('#frm-pagar').serialize();
        x_efetuarPagamento(post, function(res) {
            console.log('status:',res);
            var fpgto = $("input[name='fpgto']:checked").val();
            pagamentoGA(fpgto);
            pagamentoFBPixel();
            
            if (res.status == 'OK') {
                compraFinalizadaGA(res.order_id);
                compraFinalizadaFBPixel(res.order_id);
                showMsgSuccess('Parabéns! Seu pedido foi aprovado, aguarde redirecionar a tela ...');
            
            } else if (res.status == 'ANALISE') {
                showMsgTransaction('Pedido em Análise! aguarde redirecionar a tela ...');
            
            } else if (res.status == 'PENDENTE' && res.qrcode) {
                showMsgTransaction('Redirecionando a tela para pagamento com PIX ...');
            
            } else if (res.status == 'EXPIRADO') {
                showMsgTransaction('Ops! esse pedido expirou, você precisa realizar outro pedido.');
                setTimeout(function(){
                    redirect('/');
                }, 2000);
            //Erro:
            } else {
                showMsgError(res.msg);
                $btn.button('reset');
                $('#btn-pagar').attr("disabled", false);
            }
            
            //Redireciona:
            if (res.url) {
                setTimeout(function(){
                    redirect(res.url);
                }, 3000);
            }
        });
    }
    else{
        showMsgError(msg);
        $btn.button('reset');
        $('#btn-pagar').attr("disabled", false);
    }
}