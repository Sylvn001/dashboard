$(document).ready(() => {

    $('#documentacao').on('click' , () => {
        $.get('documentacao.html' , response => {
            $('#pagina').html(response)
        })
    })

    $('#suporte').on('click', () => {
        $.get('suporte.html', response => {
            $('#pagina').html(response)
        })
    })	


    $('#competencia').on('change', e => {

        let competencia = $(e.target).val(); 
        
        //console.log(competencia);

        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`, //x-www-form-urlencoded
            dataType: 'json',
            success: dados =>{
                console.log(dados)
                $('#numVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
                $('#activeCli').html(dados.clientesAtivos)
                $('#inativeCli').html(dados.clientesInativos)
                $('#reclamacoes').html(dados.reclamacoes)
                $('#elogios').html(dados.elogios)
                $('#sugestoes').html(dados.sugestoes)
                $('#despesas').html(dados.despesas)
            },
            error: erro =>{console.log(erro)}
        })

        //method, url, data, success or error

    });

})