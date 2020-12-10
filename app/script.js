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


})