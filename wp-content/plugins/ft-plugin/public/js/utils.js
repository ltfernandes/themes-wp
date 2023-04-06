$ = jQuery.noConflict();

(function(window, document, $, undefined) {
    "use strict";
    $(document).ready(function() {
        console.log('Frameticket 5.0 - Utils');

        $.fn.button = function(action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    });

}(window, document, jQuery));

function validaNomeCompleto(obj) {
    var valor = obj.value.trim();
    var total_nomes = valor.split(' ').length;
    if (total_nomes == 1 && valor) {
        alert('Por favor, informe o nome completo');
        obj.value = "";
        return false;
    }
}

function comboboxJsonParam(campo, lista) {
    $('#' + campo + ' option').remove();
    $('#' + campo).append($('<option>', {
        value: '',
        text: 'Selecione'
    }));
    if (lista.length >= 1) {
        $.each(lista, function(i) {
            $('#' + campo).append($('<option>', lista[i]));
        });
    }
}


function valida_CPF(obj) {
    valor = obj.value;
    valor = replaceAll(valor, '.', '');
    valor = replaceAll(valor, '-', '');
    valor = replaceAll(valor, '_', '');
    obj.value = valor;

    if (obj.value != '') {
        s = obj.value;

        var i;
        var c = s.substr(0, 9);
        var dv = s.substr(9, 2);
        var d1 = 0;
        for (i = 0; i < 9; i++) {
            d1 += c.charAt(i) * (10 - i);
        }
        if (d1 == 0) {
            alert('CPF INCORRETO, POR FAVOR, INFORME NOVAMENTE!');
            obj.value = '';
            obj.focus();
            return false;
        }
        d1 = 11 - (d1 % 11);
        if (d1 > 9)
            d1 = 0;
        if (dv.charAt(0) != d1) {
            alert('CPF INCORRETO, POR FAVOR, INFORME NOVAMENTE!');
            obj.value = '';
            obj.focus();
            return false;
        }
        d1 *= 2;
        for (i = 0; i < 9; i++) {
            d1 += c.charAt(i) * (11 - i);
        }
        d1 = 11 - (d1 % 11);
        if (d1 > 9)
            d1 = 0;
        if (dv.charAt(1) != d1) {
            alert('CPF INCORRETO, POR FAVOR, INFORME NOVAMENTE!');
            obj.value = '';
            obj.focus();
            return false;
        }
        return true;
    } else {
        return true;
    }
}

function replaceAll(string, token, newtoken) {
    while (string.indexOf(token) != -1) {
        string = string.replace(token, newtoken);
    }
    return string;
}

function redirect(url) {
    if (url) {
        window.location = url;
    } else {
        window.location = './';
    }
}

function formatMoeda(number) {
    var negative = false;
    if (number !== undefined) {
        if (number < 0) {
            number *= -1; //transforma em positivo
            negative = true;
        }
        new_number = number.toFixed(2).replace('.', ',').replace(/./g, function(c, i, a) {
            return i && c !== "," && ((a.length - i) % 3 === 0) ? '.' + c : c;
        });
        if (negative) {
            new_number = '-' + new_number; //transforma de novo em negativo
        }
        return new_number;
    }
}

function formatDouble(number, dec) {
    if (!dec) {
        dec = 10;
    }
    if (number === parseInt(number, 10)) { //é inteiro
        return number;
    }
    if (number !== undefined) {
        return number.toFixed(dec).replace('.', ',');
    }
}

function removeFormat(string) {
    if (string !== undefined) {
        if (string.indexOf(',') > -1 && string.indexOf('.') > -1) {
            return parseFloat(replaceAll(replaceAll(string, '.', ''), ',', '.'));
        }
        return parseFloat(replaceAll(string, ',', '.'));
    }
}
