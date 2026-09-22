const semlogincontainer = document.getElementById('semloginContainer');
const newButton = document.getElementById('newButton');
const h1 = document.getElementById('h1');
const pcontainer = document.getElementById('pcontainer');

if (newButton && semlogincontainer && h1 && pcontainer) {
    newButton.addEventListener('click', () => {
        if (semlogincontainer.classList.contains("irparaesquerda")) {
            semlogincontainer.classList.remove("irparaesquerda");
            semlogincontainer.classList.add("irparadireita");
            h1.innerText = "Ainda não tem cadastro?";
            newButton.innerText = "Cadastre-se";
            pcontainer.innerText = "Registre-se com seus dados pessoais para usar todos os recursos do nosso site.";
        } else {
            semlogincontainer.classList.remove("irparadireita");
            semlogincontainer.classList.add("irparaesquerda");
            h1.innerText = "Já tem Cadastro?";
            newButton.innerText = "Entrar";
            pcontainer.innerText = "Faça login com seus dados pessoais para acessar todos os recursos do nosso site.";
        }
    });
}

const loginContainer = document.getElementById('loginContainer');
const signContainer = document.getElementById('signContainer');
const btnircadastro = document.getElementById("ir-para-cadastro");
const btnirlogin = document.getElementById("ir-para-login");

if (loginContainer && signContainer && btnircadastro && btnirlogin) {
    function iniciar() {
      loginContainer.classList.add("ativo");
      signContainer.classList.remove("ativo");
    }

    btnircadastro.addEventListener("click", () => {
      loginContainer.classList.remove("ativo");
      signContainer.classList.add("ativo");
    });

    btnirlogin.addEventListener("click", () => {
      signContainer.classList.remove("ativo");
      loginContainer.classList.add("ativo");
    });

    iniciar();
}

// Dropdown do perfil
const perfilItem = document.getElementById('perfilItem');
const perfilToggle = document.getElementById('perfilToggle');

if (perfilToggle && perfilItem) {
    perfilToggle.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        perfilItem.classList.toggle('open');
    });

    // Fecha o dropdown ao clicar fora dele
    document.addEventListener('click', (event) => {
        if (!perfilItem.contains(event.target)) {
            perfilItem.classList.remove('open');
        }
    });

    // Fecha o dropdown ao clicar Esc
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            perfilItem.classList.remove('open');
        }
    });
}

// PÁGINA DE PERFIL
const btnEditarPerfil = document.getElementById('btnEditarPerfil');
const btnSalvarPerfil = document.getElementById('btnSalvarPerfil');
const perfilForm = document.querySelector('.perfil-form');

if (btnEditarPerfil && perfilForm) {
    btnEditarPerfil.addEventListener('click', () => {
        // Destrava todos os inputs, steppers e pills de dor
        perfilForm.querySelectorAll('input:not([type="hidden"]), .dor-pill').forEach(el => {
            el.disabled = false;
        });
        btnSalvarPerfil.disabled = false;
        btnEditarPerfil.textContent = 'Editando...';
        btnEditarPerfil.disabled = true;
    });
}

// Steppers (idade, altura, peso) com botões + / -
document.querySelectorAll('.step-btn').forEach(botao => {
    botao.addEventListener('click', () => {
        const alvo = document.getElementById(botao.dataset.target);
        if (!alvo || alvo.disabled) return;

        const passo = parseFloat(botao.dataset.step || '1');
        const valorAtual = parseFloat(alvo.value) || 0;

        if (botao.classList.contains('plus')) {
            alvo.value = (valorAtual + passo).toFixed(passo < 1 ? 1 : 0);
        } else {
            const novoValor = valorAtual - passo;
            alvo.value = Math.max(0, novoValor).toFixed(passo < 1 ? 1 : 0);
        }
    });
});

// Pills de "Principais dores" — clique alterna seleção
const doresInput = document.getElementById('doresInput');
document.querySelectorAll('.dor-pill').forEach(pill => {
    pill.addEventListener('click', () => {
        if (pill.disabled) return;
        pill.classList.toggle('selected');

        const selecionados = Array.from(document.querySelectorAll('.dor-pill.selected'))
            .map(p => p.dataset.id);
        doresInput.value = selecionados.join(',');
    });
});

// ===================================================
// LÓGICA DE BUSCA E ROLAGEM SUAVE DOS EXERCÍCIOS
// ===================================================
document.addEventListener('DOMContentLoaded', function () {
    const inputBusca = document.getElementById('inputBusca');
    const listaSugestoes = document.getElementById('listaSugestoes');
    const cards = document.querySelectorAll('.card-exercicio');

    if (!inputBusca) return;

    // Escuta a digitação na barra de busca
    inputBusca.addEventListener('input', function () {
        const termo = this.value.trim().toLowerCase();
        listaSugestoes.innerHTML = '';

        if (termo === '') {
            listaSugestoes.style.display = 'none';
            return;
        }

        let encontrou = false;

        cards.forEach(card => {
            const nomeExercicio = card.getAttribute('data-nome');
            const idExercicio = card.getAttribute('data-id');
            const tituloOriginal = card.querySelector('h3').innerText;

            if (nomeExercicio.includes(termo)) {
                encontrou = true;

                // Cria o item da lista suspensa
                const li = document.createElement('li');
                li.textContent = tituloOriginal;

                // Ao clicar em uma opção da busca
                li.addEventListener('click', function () {
                    inputBusca.value = tituloOriginal;
                    listaSugestoes.style.display = 'none';

                    const cardAlvo = document.getElementById(`exercicio-${idExercicio}`);

                    if (cardAlvo) {
                        // Rola suavemente até o elemento centralizando na tela
                        cardAlvo.scrollIntoView({ behavior: 'smooth', block: 'center' });

                        // Aplica o efeito visual de destaque
                        cardAlvo.classList.add('destaque');
                        setTimeout(() => {
                            cardAlvo.classList.remove('destaque');
                        }, 2000);
                    }
                });

                listaSugestoes.appendChild(li);
            }
        });

        listaSugestoes.style.display = encontrou ? 'block' : 'none';
    });

    // Fecha a lista suspensa se clicar em qualquer área fora da busca
    document.addEventListener('click', function (e) {
        if (!inputBusca.contains(e.target) && !listaSugestoes.contains(e.target)) {
            listaSugestoes.style.display = 'none';
        }
    });
}); 
