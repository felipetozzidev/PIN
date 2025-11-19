<div id="accessibility-widget" class="accessibility-widget">
    <button id="accessibility-toggle" class="accessibility-btn main-btn" title="Opções de Acessibilidade" aria-label="Abrir menu de acessibilidade">
        <i class="ri-function-line"></i>
    </button>

    <div class="accessibility-menu">
        <button id="a11y-increase-font" class="accessibility-btn" title="Aumentar Fonte (+)">
            <i class="ri-zoom-in-line"></i>
        </button>
        
        <button id="a11y-decrease-font" class="accessibility-btn" title="Diminuir Fonte (-)">
            <i class="ri-zoom-out-line"></i>
        </button>
        
        <button id="a11y-contrast" class="accessibility-btn" title="Alto Contraste">
            <i class="ri-contrast-drop-line"></i>
        </button>

        <button id="a11y-reset" class="accessibility-btn" title="Resetar Padrões">
            <i class="ri-refresh-line"></i>
        </button>
    </div>
</div>

<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>