( function( $ ) {
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00');
    $('.form3').mask('000.000.000-00');
    $('.form3').attr('maxlength','14');
    var options = {
        onKeyPress: function (cpf, ev, el, op) {
            var masks = ['000.000.000-000', '00.000.000/0000-00'];
            $('.cpfOuCnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
        }
    }
    $('.cpfOuCnpj').length > 11 ? $('.cpfOuCnpj').mask('00.000.000/0000-00', options) : $('.cpfOuCnpj').mask('000.000.000-00#', options);
    $('.cep').mask('00000-000');
    $( ".date" ).datepicker({
        altFormat: "dd/mm/yy",
        dateFormat: "dd/mm/yy",
    });
    if($('#form-dev').is(":checked")){
        $('.local-dev').show();
    }
    $('#form-dev').on('click',function(){
        $('.local-dev').toggle();
        if(!$('#form-dev').is(":checked")){
            $('.local-dev-msg').html("");
            $('.local-dev-msg-border').css("border","0px");
            return;
        }
    });
    $('.buscar-reserva').on('click',function(){
        var msgerror = "";
        if($('#form-dev').is(":checked")){
            if($('.lcretorno').val() == '0'){
                msgerror += "Selecione o local ou desmarque<br>"; 
            }
        }
        var dtretirada = $('.dtretirada').val();
        var dtretirada_parts = dtretirada.split("/");
        var hrretirada = $('.hrretirada').val();

        data = Date.parse(dtretirada_parts[2]+"-"+dtretirada_parts[1]+"-"+dtretirada_parts[0]+" "+hrretirada);

        if(Number.isNaN(data)){
            msgerror += "Data de retirada inválida<br>"; 
        }
        var dtretorno = $('.dtretorno').val();
        var dtretorno_parts = dtretorno.split("/");
        var hrretorno  = $('.hrretorno').val();

        data = Date.parse(dtretorno_parts[2]+"-"+dtretorno_parts[1]+"-"+dtretorno_parts[0]+" "+hrretorno);
        if(Number.isNaN(data)){
            msgerror += "Data de retorno inválida<br>"; 
        }
        if(msgerror != ""){
            $('.msg-error-js').show();
            $('.msg-error-js').html(msgerror)
            return;
        }
        $('#header').submit();
    });
    $('.veiculos-grupo').on('click',function(){
        id = $(this).data( "id" );
        if($('.show-'+id).is(":hidden")){
            $('.show-'+id).show();
            $('.hide-'+id).hide();
        }else{
            $('.show-'+id).hide();
            $('.hide-'+id).show();
        }
    });
    $('.btn-veiculo').on('click',function(e){
        e.preventDefault();
        grupo = $(this).data('grupo');
        tarifa = $(this).data('tarifa');
        $('.idgrupo').val(grupo);
        $('.idtarifa').val(tarifa);
        $('#form-values').submit();
    });
    $('.alterar-veiculo').on('click',function(e){
        e.preventDefault();
        $('.idgrupo').remove();
        $('.idtarifa').remove();
        $('#form-values').submit();
    });
    $('[data-toggle="popover"]').popover();

    $('.opcheck, .opcheckb').on('click',function(){
        opcoes = $('.opcheck');
        opcoesb = $('.opcheckb');
        adicionais_lista = '';
        protecoes_lista = '';
        valortotal = 0;
        adicionaistotal = 0;
        protecaototal = parseFloat($('.protecao-total').data('valor'));
        adicionais_input = '';
        protecoes_input = '';
        dias = parseFloat($('.totaldiaria').data('dias'));

        for(i = 0;i < opcoes.length ;i++){
            if(opcoes.eq(i).is(':checked')){
                valortotal += parseFloat(opcoes.eq(i).data('valor'));
                adicionaistotal += parseFloat(opcoes.eq(i).data('valor'))*dias;
                adicionais_input += opcoes.eq(i).val()+',';
                adicionais_lista += "<div class='row'><div class='col-7'>"+opcoes.eq(i).data('descricao')+"</div><div class='col-5'>R$"+opcoes.eq(i).data('valor').replace('.',',')+"/dia</div></div>";
            }
        }

        for(i = 0;i < opcoesb.length ;i++){
            if(opcoesb.eq(i).is(':checked')){
                valortotal += parseFloat(opcoesb.eq(i).data('valor'));
                protecaototal +=  parseFloat(opcoesb.eq(i).data('valor'))*dias;
                protecoes_input += opcoesb.eq(i).val()+',';
                protecoes_lista += "<div class='row'><div class='col-7'>"+opcoesb.eq(i).data('descricao')+"</div><div class='col-5'>R$"+opcoesb.eq(i).data('valor').replace('.',',')+"/dia</div></div>";
            }
        }
        totaldiaria = parseFloat($('.totaldiaria').data('valor'));
        valortotal = parseFloat(valortotal);
        $('.adicionais-total').html(adicionaistotal.toFixed(2).replace('.',','));
        $('.protecao-total').html(protecaototal.toFixed(2).replace('.',','));
        $('.adicionais-input').val(adicionais_input.slice(0, -1));
        $('.protecoes-input').val(protecoes_input.slice(0, -1));
        $('.totaldiaria').html("R$"+(valortotal+totaldiaria).toFixed(2).replace('.',','));
        $('.total').html("R$"+((valortotal+totaldiaria)*dias).toFixed(2).replace('.',','));
        $('.adicionais-lista').html(adicionais_lista);
        $('.protecoes-lista').html(protecoes_lista);
    });
    $('.btn-adicionais').on('click',function(e){
        e.preventDefault();
        if($('.check-aprovacao').is(':checked')){
            $('.passo-1').hide('slow');
            $('.passo-2').show('slow');
        }else{
            alert('É necessário aceitar os termos para prosseguir!');
        }
    });
    $('.nacionalidade').on('click',function(){
        if($(this).val() != 'S'){
            $('.brasileiro').show('slow');
            $('.nbrasileiro').hide('slow');
        }else{
            $('.brasileiro').hide('slow');
            $('.nbrasileiro').show('slow');
        }
    });
    $('.form1').on('change',function(){
        if($(this).val() == 'F'){
            $('.form3').mask('000.000.000-00');
            $('.form3').attr('maxlength','14');
        }else{
            $('.form3').mask('00.000.000/0000-00');
            $('.form3').attr('maxlength','18');
        }
    })
    $('.reservar').on('click',function(){
        var error = false;
        $('.alerta-msg').html('');
        if($('.form2').val() == ''){
            $('.alerta-msg').append('Nome cliente é obrigatório<br>');
            error = true;
        }
        if($('.form3').val() == ''){
            $('.alerta-msg').append('CNPJ/CPF cliente é obrigatório<br>');
            error = true;
        }
        if($('.form4').val() == ''){
            $('.alerta-msg').append('E-mail cliente é obrigatório<br>');
            error = true;
        }
        if($('.form5').val() == ''){
            $('.alerta-msg').append('Telefone cliente é obrigatório<br>');
            error = true;
        }

        if($('.form8').val() == ''){
            $('.alerta-msg').append('CNH cliente é obrigatório<br>');
            error = true;
        }
        if(!error){
            $('.reserv').val("1");
            $('#form-values').submit();
        }else{
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('.alerta-erro').show();
        }
    });
    $('.dtretirada-icon').on('click',function(){
        $('.dtretirada').focus();
    });
    $('.hrretirada-icon').on('click',function(){
        $('.hrretirada').focus();
    });
    $('.dtretorno-icon').on('click',function(){
        $('.dtretorno').focus();
    });
    $('.hrretorno-icon').on('click',function(){
        $('.hrretorno').focus();
    });
    $('.lcretirada-icon').on('click',function(){
        $('.lcretirada').focus();
    });
    $('.lcretorno-icon').on('click',function(){
        $('.lcretorno').focus();
    });
    $('.owl-carousel').owlCarousel({
        loop:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        margin:10,
        nav:false,
        responsive:{
            0:{
                items:1
            }
        }
    });
} )( jQuery );