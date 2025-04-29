let tamanhoCabecalho = document.querySelector("header").clientHeight
console.log(tamanhoCabecalho);

document.querySelector("main").style.marginTop = parseInt(tamanhoCabecalho) + "px";