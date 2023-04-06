$ = jQuery;

$(function () {
    
    $("#cep").blur(function () {
        var cep = $(this).val();
        buscaEndereco(cep);
    });

    setMascaras();

});

function setMascaras() {

    $('.cpf').mask('999.999.999-99');
    $('.cnpj').mask('99.999.999/9999-99');
    $('.data').mask('99/99/9999');
    $('.cep').mask('99999-999');
    $('.tel').mask('(99)9 999-9999?9');
    $('.hora').mask('99:99');
    $('.float,.moeda>input').maskMoney({ 'thousands': '.', 'decimal': ',' });
    $('.cartao').mask('9999 9999 9999 999?9');
    $('.cvv').mask('999?9');
    $('.data').datepicker({
        format: 'dd/mm/yyyy',
        language: "pt-BR",
        autoclose: true,
        todayHighlight: true
    });
    $(".cnpj").blur(function () {
        valida_CPF_CNPJ(this);
    });
    $(".cpf").blur(function () {
        valida_CPF_CNPJ(this);
    });
}

function verifyEmail(email) {
    if (email) {
        $('.load-email').show();
        var id = $('#id_cliente').val();
        x_verifyEmail(String(email), id, function (res) {
            if (res.id_cliente) {
                alert('E-mail já está em uso por outro usuário.');
                $('#email').val('');
                $('#email').focus();
            }
            $('.load-email').hide();
        });
    }
}

function buscaEndereco(cep) {
    if (cep) {
        $('.load-busca-cep').show();
        x_buscaEndereco(String(cep), function (res) {
            $('#bairro').val(res.bairro);
            $('#cidade').val(res.localidade);
            $('#endereco').val(res.logradouro);
            $('#uf').val(res.uf);
            $('.load-busca-cep').hide();
        });
    }
}