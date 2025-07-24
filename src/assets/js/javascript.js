// --- objeto que será usado no decorrer do arquivo

const objetos = {
    navbar: document.querySelector(".navbar_container"),
    nav: document.querySelector("nav"),
    main: document.querySelector("main"),
    footer: document.querySelector("footer"),
<<<<<<< HEAD
    body: document.querySelector("body"),
=======
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
    community_cards_container: document.querySelector("div.community_cards_container"),
    tamanhoCabecalho: function() {
        return this.nav.clientHeight;
    },
<<<<<<< HEAD
    tamanhoMain: function () {
        return this.main.clientHeight  
    },
    tamanhoBody: function () {
        return this.body.clientHeight
    },
=======
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
    paddingCabecalho: function() {
        return getComputedStyle(this.main).paddingTop;
    },
    tamanhoFooter: function() {
        return this.footer.clientHeight;
    },
    dropdownItens: function() {
        return document.querySelectorAll(".dropdown_item");
    }
}

// --- Tamanho do cabecalho

objetos.main.style.marginTop = `${objetos.tamanhoCabecalho()}px`;
<<<<<<< HEAD
if (typeof(objetos.community_cards_container) != 'undefined' && objetos.community_cards_container != null)
{
  // exists.
  objetos.community_cards_container.style.top = `calc(${objetos.tamanhoCabecalho()}px + 20px)`;
}

console.log(objetos.tamanhoCabecalho() + objetos.tamanhoBody() + " | " + window.screen.height);

if(objetos.tamanhoCabecalho() + objetos.tamanhoBody() < window.screen.height - objetos.tamanhoFooter()) {
    objetos.footer.style = `position: fixed; bottom: 0;`;
}
=======
objetos.main.style.marginBottom = `${objetos.tamanhoFooter()}px`;
objetos.community_cards_container.style.top = `calc(${objetos.tamanhoCabecalho()}px + 20px)`;
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
objetos.navbar.style.height = `calc(100% - ${objetos.tamanhoCabecalho()}px - ${objetos.tamanhoFooter()}px - ${objetos.paddingCabecalho()})`;
console.log(objetos.paddingCabecalho());


// --- Dropdown arrows (automação)

objetos.dropdownItens().forEach(element => {
    const createIconDropdown = document.createElement("img");
    const tag_a_selection = element.querySelector("a");
    createIconDropdown.setAttribute("src", "../src/assets/icons/arrow_down.svg");
    tag_a_selection.appendChild(createIconDropdown);
    element.style.height = `${tag_a_selection.clientHeight}px`;

    element.querySelector("a").addEventListener("click", () => {
        element.classList.toggle("active");
        if(element.className.includes("active")){
            element.style.height = `${tag_a_selection.clientHeight + element.querySelector("ul.dropdown_list").clientHeight}px`;
        } else {
            element.style.height = `${tag_a_selection.clientHeight}px`;
        }
    })

});

