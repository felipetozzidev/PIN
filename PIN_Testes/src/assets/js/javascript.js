// --- objeto que será usado no decorrer do arquivo

const objetos = {
    navbar: document.querySelector(".navbar_container"),
    nav: document.querySelector("nav"),
    main: document.querySelector("main"),
    footer: document.querySelector("footer"),
<<<<<<< HEAD
=======
    community_cards_container: document.querySelector("div.community_cards_container"),
>>>>>>> 7fc9a5a528a9cfe8cab2e3603e2c128b08acb1ba
    tamanhoCabecalho: function() {
        return this.nav.clientHeight;
    },
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
objetos.main.style.marginBottom = `${objetos.tamanhoFooter()}px`;
<<<<<<< HEAD
=======
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

