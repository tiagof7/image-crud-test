

function abreModal(idElement){
	// Pega o modal
	var modal = document.getElementById('myModal');

	// Pega a imagem que foi clicada
	var img = document.getElementById(idElement);

	var modalImg = document.getElementById("foto-modal");
	var captionText = document.getElementById("caption");
    modal.style.display = "block";

    //Insere a imagem dentro do modal
    modalImg.src = img.src;

    //Insere a descrição da imagem no modal
    modalImg.alt = img.alt;
    captionText.innerHTML = img.alt;

    //Salva o id do elemento da imagem dentro do modal. Será usado na setinha.
    $('#foto-modal').data().id_image = idElement;

	// Elemento de fechar o modal
	var span = document.getElementsByClassName("close")[0];
	span.onclick = function() {
	    modal.style.display = "none";
	}
}

function proximaPagina(){
	//Pega o elemento da imagem atual via JQuery
	var idImagemSelecionada = $('#foto-modal').data().id_image;
	//Verifica qual a posição do elemento pai em relação ao seu elemento pai. Isso determina o index da imagem na tela.
	var posicaoImagemSelecionada = $('#'+idImagemSelecionada).closest('.col-xs-3').index();
	var qtdImagens = $('.col-xs-3').length;
	if (posicaoImagemSelecionada < (qtdImagens-1)) {
		//Proxima imagem. O onclick chama a função abreModal
		$('.col-xs-3 img').get(posicaoImagemSelecionada+1).click()
	}
}

function paginaAnterior(){
	//Pega o elemento da imagem atual via JQuery
	var idImagemSelecionada = $('#foto-modal').data().id_image;
	//Verifica qual a posição do elemento pai em relação ao seu elemento pai. Isso determina o index da imagem na tela.
	var posicaoImagemSelecionada = $('#'+idImagemSelecionada).closest('.col-xs-3').index();
	if (posicaoImagemSelecionada > 0) {
		//Imagem anterior. O onclick chama a função abreModal
		$('.col-xs-3 img').get(posicaoImagemSelecionada-1).click()
	}
}