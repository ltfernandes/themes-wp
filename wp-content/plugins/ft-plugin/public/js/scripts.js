$ = jQuery.noConflict();

(function(window, document, $, undefined) {
    "use strict";
    $(document).ready(function() {
        console.log('Frameticket 5.0 - Scripts');

        $('.cel').mask('(99) 9 9999-999?9');
        $('.tel').mask('(99) 9999-9999');
        $('.date').mask('99/99/9999');
        $('.cep').mask('99999-999');
        $('.cpf').mask('999.999.999-99');
        $('.cartao').mask('9999 9999 9999 999?9');
        $('.cvv').mask('999?9');
        $('.fancy').fancybox();
        $('.fancybox').fancybox();
        $('.fancybox-wrap').css('z-index', 99999);
        $('.fancybox-overlay').css('z-index', 99999);

        $(".plano_quant").change(function() {
            calcTotalcompra();
        });

        $("#cep").on("blur", function() {
            var cep = $(this).val();
            if (cep) {
                x_getEndereco(cep, function(res) {
                    if (res) {
                        var d = JSON.parse(res);

                        if (d.endereco) {
                            $('#endereco').val(d.endereco);
                        }
                        if (d.bairro) {
                            $('#bairro').val(d.bairro);
                        }
                        if (d.fk_uf) {
                            $('#estado').val(d.fk_uf);
                            setCidades(d.fk_uf, d.fk_cidade);
                        }
                    }
                });
            }
        });

        $('#loginr').on('keydown', function(e) {
            if (e.which == 13) {
                var code = $(this).val();
                if (code) {
                    $( "#btn-recuperar" ).trigger( "click" );
                } else {
                    responde('Código não informado!', 'red', 1000);
                }
                e.preventDefault();
            }
        });
        
        $('#nova-senha').on('keydown', function(e) {
            if (e.which == 13) {
                var code = $(this).val();
                if (code) {
                    $( "#btn-salvar-senha" ).trigger( "click" );
                } else {
                    responde('Código não informado!', 'red', 1000);
                }
                e.preventDefault();
            }
        });
        
        $("#cob_cep").on("blur", function() {
            var cep = $(this).val();
            if (cep) {
                x_getEndereco(cep, function(res) {
                    if (res) {
                        var d = JSON.parse(res);
                        if (d.endereco) {
                            $('#cob_endereco').val(d.endereco);
                        }
                        if (d.bairro) {
                            $('#cob_bairro').val(d.bairro);
                        }
                        if (d.fk_uf) {
                            $('#cob_estado').val(d.fk_uf);
                            setCidades(d.fk_uf, d.fk_cidade);
                        }
                    }
                });
            }
        });

        $(".email-confere").on("blur", function() {
            var email = $(this).val();
            var id_public = $(this).attr('id_public');
            if (email) {
                $('#resValidaMail').html('<i class="fa fa-spinner"></i>');
                x_verifyEmail(email, id_public, function(res) {
                    console.log(res);
                    if (res == 'OK') {
                        $('#resValidaMail').html('<i class="fa fa-check" style="color:green"></i>');
                    } else {
                        $('#resValidaMail').html('<i class="fa fa-ban" style="color:red"></i>');
                        alert('Este e-mail já está em uso por outro usuário!');
                        $(this).val("");
                        $(this).focus();
                    }
                });
            }
        });

        $("#estado").on("change", function() {
            var id_estado = $(this).val();
            if (id_estado) {
                setCidades(id_estado, 0);
            }
        });

        $("#verifyLogin").on("click", function() {
            var $btn = $(this);
            $btn.button('loading');
            var login = $('#logar-login').val();
            var senha = $('#logar-senha').val();
            verifyLogin(login,senha,'',$btn);
        });

        $("#verifyLogin-mobile").on("click", function() {
            var $btn = $(this);
            $btn.button('loading');
            var login = $('#logar-login-mobile').val();
            var senha = $('#logar-senha-mobile').val();
            verifyLogin(login,senha,'',$btn);
        });

        $("#verifyLogin-back").on("click", function() {
            var $btn = $(this);
            $btn.button('loading');
            var login = $('#logar-login-back').val();
            var senha = $('#logar-senha-back').val();
            verifyLogin(login,senha,'',$btn);
        });

        $("#verifyLogin-payment").on("click", function() {
            var $btn = $(this);
            $btn.button('loading');
            var login = $('#logar-login-payment').val();
            var senha = $('#logar-senha-payment').val();
            verifyLogin(login,senha,'pagamento',$btn);
        });


        $("#btn-recuperar").click(function() {
            var login = $('#loginr').val();
            if (login) {
                var $btn = $(this);
                $btn.button('loading');
                x_recuperarSenha(login, function(res) {
                    if(res.status=='OK'){
                        $('#resultado').html('<div class="alert alert-success">'+res.msg+'</div>');
                    }
                    else{
                        $('#resultado').html('<div class="alert alert-danger">'+res.msg+'</div>');
                    }
                    $btn.button('reset');
                });
            } else {
                alert('Por favor, informe seu login!');
                $('#loginr').focus();
            }
        });

        $("#btn-salvar-senha").click(function() {
            var senha = $('#nova-senha').val();
            var key = $('#nova-senha').attr('data-key');
            if (senha && key) {
                var $btn = $(this);
                $btn.button('loading');
                x_salvarSenha(senha, key, function(res) {
                    if (res.status == 'OK') {
                        $('#resultado').html(res.msg);
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 2000);
                    } else {
                        $('#resultado').html('<div class="alert alert-danger">'+res.msg+'</div>');
                    }
                    $btn.button('reset');
                });
            } else {
                alert('Por favor, informe sua nova senha!');
                $('#nova-senha').focus();
            }
        });

        $("#cob_estado").on("change", function() {
            var id_estado = $(this).val();
            if (id_estado) {
                setCidades(id_estado, 0);
            }
        });

        $(".btn-cartao").on("click", function() {
            $('#dados-titular').collapse('show');
            $('#parcelamento').show();
        });

        $(".btn-boleto").on("click", function() {
            $('#dados-titular').collapse('hide');
            $('#cond_1').prop('checked', true);
            $('#parcelamento').hide();
        });

        var doc_principal = $("#doc_principal").val();
        if (doc_principal == 'CPF') {
            setMascaraCpf('S');
        }

        $("#doc_principal").on("change", function() {
            doc_principal = $(this).val();
            if (doc_principal == 'CPF') {
                setMascaraCpf('S');
            } else {
                setMascaraCpf('N');
            }
        });

        
        $('#calendario-agendamento').on('changeDate', function (e) {
            var data = e.format(0, "dd/mm/yyyy");
            console.log('Change Calendar (FT)',data);
            changeDate(data);
           
        });
        
    });

    

}(window, document, jQuery));

/**
 * Lista os planos conforme horário da visita escolhido
 * @param string hora_visita 
 * @param object obj objeto do botão do horário
 */
function showProductsHour(hora_visita, obj){
    if(hora_visita){
        $('.btn-hour').removeClass('active');
        $(obj).addClass('active');
        $('#hora_visita_view').html(hora_visita);
        $('#hora_visita').val(hora_visita);

        showPlanos('unidade');
    }
}

function changeDate(data) {
    console.log('Data (FT): ',data);
    $('#data_visita').val(data);
    let ide = $('#calendario-agendamento').attr('data-ide');
    let timetable = $('#calendario-agendamento').attr('data-timetable');
    
    let cupom = $('#cupom').val();
    let id_comissario = ($('#id_comissario').val()) ? $('#id_comissario').val() : 0;

    let convenio = '';
    if($('#convenio').val()){
        convenio = $('#convenio').attr('data-id')+'|'+$('#convenio').val();
    }
    
    $('#agenda-horarios').html('Consultando ...');
    
    x_consultaData('unidade',ide, data, timetable, cupom, id_comissario, convenio, function (res) {
        if(res.type=='hours'){
            $('#agenda-horarios').html(res.html);
            $('#product-list').html('<div class="alert alert-info msg-horario">Por favor, informe os dados para agendamento!</div>');
            $(".horario").click(function () {
                let h = $(this).val();
                $('#hora_visita').val(h);
            });
        }
        else{
            $('#product-list').html(res.html);
        }
        $('#data_visita_view').html(data);
    });
}

function copyClip(element) {
    let copyText = document.getElementById(element);
    navigator.clipboard.writeText(copyText.innerHTML);
    alert("Texto copiado com sucesso!");
}

function atualizaStatusPix(id){
    
    setTimeout(function(){ 
        x_verifyPaymentPix(String(id),'', function(res) {
            console.log(id,res.status);
            if(res.status=='OK'){
                document.location.reload(true);
            }
            else{
                atualizaStatusPix(String(id));
            }
        });
    }, 10000);
}

function validaCupom(type){
    showPlanos(type);
}

function showPlanos(type){
    type = (type) ? type : 'unidade';
    var ide = $('#ide').val();
    var idp = ($('#idp').val())?$('#idp').val():0;
    
    if (idp && type.toLowerCase() == 'evento') {
        ide = idp;
    }
    var cupom = ($('#cupom').val())? $('#cupom').val() : '';
    var id_comissario = ($('#id_comissario').val()) ? $('#id_comissario').val() : 0;
    var data = $('#data_visita').val();
    var hora_visita = $('#hora_visita').val();

    if ($('#convenio').val()) {
        var convenio = $('#convenio').attr('data-id')+'|'+$('#convenio').val();
    } else {
        convenio = '';
    }
    $('#product-list').html('Consultando planos, aguarde ...');
    x_showPlanos(type, ide, data, cupom, id_comissario, hora_visita, convenio, function (res) {
        $('#product-list').html(res);
    });
}

function setPlans(type){
    type = (type) ? type : 'unidade';
    var ide = $('#ide').val();
    var cupom = ($('#cupom').val())? $('#cupom').val() : '';
    var id_comissario = ($('#id_comissario').val()) ? $('#id_comissario').val() : 0;
    var data = $('#data_visita').val();
    var hora_visita = $('#hora_visita').val();

    if($('#convenio').val()){
        var convenio = $('#convenio').attr('data-id')+'|'+$('#convenio').val();
    }
    else{
        convenio = '';
    }

    x_showPlanos(type,ide, data, cupom, id_comissario,hora_visita, convenio, function (res) {
        calcTotalcompra();
    });
}

function setCidades(id_estado, id_cidade) {
    console.log('Define cidades');
    if (id_estado) {
        x_getCidades(id_estado, function(res) {
            if (res) {
                var items_selected = JSON.parse(res);
                comboboxJsonParam('cidade', items_selected);
                if (id_cidade) {
                    $('#cidade').val(id_cidade);
                }
            }
        });
    }
}

function validarDoc(obj) {
    var doc_principal = $('#doc_principal').val();
    if (doc_principal == 'CPF') {
        valida_CPF(obj);
    }
}

function setMascaraCpf(opcao) {
    if (opcao == 'S') {
        $('#documento_num').mask('999.999.999-99');
    } else {
        $('#documento_num').val('');
        $('#documento_num').unmask();
    }
}


function showMsg(status, msg) {
    return '<div style="margin-top:5px" class="alert alert-' + status + ' text-center">' + msg + '</div>';
}

function verifyLogin(login='', senha='', ambiente='',btn='') {
    if(login && senha){
        x_verifyLogin(login, senha, ambiente, function(res) {
            if (res.status == 'FAIL') {
                alert(res.msg);
            } else if (res.url) {
                redirect(res.url);
            } else {
                location.reload();
            }
        });
    }
    else{
        alert('Por favor, informe o login e a senha!');
    }
    btn.button('reset');
}


function calcTotalcompra() {
    console.log('Calcula JS');

    var ide = $('#ide').val();
    if(ide){
        var itens_selecionados = [];
        $('.plano_quant').each(function() {
            var id = $(this).attr('rel-id');
            var quant = ($(this).val()) ? parseInt($(this).val()) : 0;
            if(quant >= 1){
                itens_selecionados[id] = quant;
            }
        });
        
        x_getPlans(ide, function(res) {
            //console.log('Plans ',res);
            if(res !== null){
                //console.log('Res ',res);
                var result = JSON.parse(res);
                var index = '';
                var total = 0;
                var total_compra = 0;
                var total_taxa = 0;
                
                $.each(result, function(id, item) {
                    index = id;
                    if(itens_selecionados[index]){
                        total_compra += itens_selecionados[index] * parseFloat(item.price);
                        total_taxa += itens_selecionados[index] * parseFloat(item.rate);
                    }
                });
                    
                total = (total_compra > 0)?total_compra + total_taxa:0;
                $('#valor_compra').html(formatMoeda(total_compra));
                $('#total_taxa').html(formatMoeda(total_taxa));
                $('#total_compra').html(formatMoeda(total));
            }
            else{
                //console.log('Atualiza lista ...');
                var type = $('#type_event').val();
                setPlans(type);
            }
        });
    }
}

function addCarrinho(){
    
    var ide = $('#ide').val();
    var title = $('#ide').attr('rel-title');
    if(ide){
        $('#btn-add-carrinho').button('loading');
        var post = $('#frm-event').serialize();
        x_addCarrinho(ide, title, post, function(res) {
            
            if(res.status=='FAIL'){
                alert('Nenhum item selecionado!');
            }
            else{
                $('.carrinho_total').html(res.total_carrinho);
                addCarrinhoGA();
                addCarrinhoFBPixel();
                alert('Adicionado ao carrinho com sucesso!');
            }
            $('#btn-add-carrinho').button('reset');
        });
    }
}

function nomearTicket(id){
    if(id){
        if(confirm('Tem certeza que deseja salvar os dados do ingresso?')){
            var post = $('#frm-ticket-edit-'+id).serialize();
            x_nomearTicket(id,post, function(res) {
                
                if(res.status=='OK'){
                    alert(res.msg);
                    location.reload();
                }
                else{
                    alert(res.msg);
                }
            });
        }
    }
}

function finalizarCompra(){
    
    var ide = $('#ide').val();
    var title = $('#ide').attr('rel-title');
    if(ide){
        $('#btn-finalizar-compra').button('loading');
        var post = $('#frm-event').serialize();
        x_finalizarCompra(ide, title, post, function(url) {
            redirect(url);
        });
    }
}

function maisCarrinho(ide,index){
    if(ide){
        var post = ($('#frm-event'))?$('#frm-event').serialize():'';

        $('#btnmais-cart-'+ide+'-'+index).button('loading');

        x_maisCarrinho(ide, index, post, function(res) {
            if(res.status=='OK'){
                $('#item-cart-'+ide+'-'+index).html(res.quant);
                $('#item-cart-total-'+ide+'-'+index).html(res.total_item);
                $('#total').html(res.total);
                $('#total-taxas').html(res.taxas);
                $('#total-compra').html(res.total_compra);
                $('.carrinho_total').html(res.total_carrinho);
                addCarrinhoItemGA(ide, index);
                addCarrinhoItemFBPixel(ide, index);
            }
            else{
                alert(res.msg);
            }
            $('#btnmais-cart-'+ide+'-'+index).button('reset');
        });
    }
}

function menosCarrinho(ide,index){
    if(ide){
        $('#btnmenos-cart-'+ide+'-'+index).button('loading');
        x_menosCarrinho(ide, index, function(res) {
            if(res.quant>=1){
                $('#item-cart-'+ide+'-'+index).html(res.quant);
                $('#item-cart-total-'+ide+'-'+index).html(res.total_item);
                $('#total').html(res.total);
                $('#total-taxas').html(res.taxas);
                $('#total-compra').html(res.total_compra);
                $('.carrinho_total').html(res.total_carrinho);

                $('#btnmenos-cart-'+ide+'-'+index).button('reset');
                delCarrinhoItemGA(ide, index);
            }
            else{
                location.reload();
            }
        });
    }
}

function limparCarrinho(){
    if(confirm('Tem certeza que deseja limpar seu carrinho de compras?')){
        $('#btn-clear-carrinho').button('loading');
        x_clearCarrinho(0, function() {
            location.reload();
        });
    }
}

/**
 * Metodo para ser manipulado no tema, se necessário
 * @param {*} index 
 * @param {*} action 
 * @returns 
 */
function otherChangeQuant(index,action){
    console.log('otherChangeQuant default');
    return [index,action];
}

function changeQuant(index,action){

    var quant = parseInt(($('#quant_'+index).val())?$('#quant_'+index).val():0);
    var max = parseInt($('#quant_'+index).attr('data-max'));
    var quant_new = 0;

    console.log(index,action,quant,max);

    if(action=='ADD'){
        quant_new = quant + 1;
        if(quant_new <= max){
            $('#quant_'+index).val(quant_new);
        }
    }
    else if(action=='DEL' && quant>=1){
        quant_new = quant - 1;
        $('#quant_'+index).val(quant_new);
    }
    calcTotalcompra();
    otherChangeQuant(index,action);
}