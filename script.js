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

const semlogincontainer = document.getElementById('semloginContainer');
const newButton = document.getElementById('newButton');
const h1 = document.getElementById('h1');
const pcontainer = document.getElementById('pcontainer');

newButton.addEventListener('click',() => {
    if(semlogincontainer.classList.contains("irparaesquerda")){
        semlogincontainer.classList.remove("irparaesquerda");
        semlogincontainer.classList.add("irparadireita");//direita
        h1.innerText = "Ainda não tem cadastro?";
        newButton.innerText = "Cadastre-se";
        pcontainer.innerText = "Registre-se com seus dados pessoais para usar todos os recursos do nosso site.";
    }
    else{
        semlogincontainer.classList.remove("irparadireita");
        semlogincontainer.classList.add("irparaesquerda"); //esquerda
        h1.innerText = "Já tem Cadastro?";
        newButton.innerText = "Entrar";
        pcontainer.innerText = "Faça login com seus dados pessoais para acessar todos os recursos do nosso site.";
    }
});
