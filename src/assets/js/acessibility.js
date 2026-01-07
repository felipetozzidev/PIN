document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('accessibility-widget');
    const toggleBtn = document.getElementById('accessibility-toggle');
    const increaseBtn = document.getElementById('a11y-increase-font');
    const decreaseBtn = document.getElementById('a11y-decrease-font');
    const contrastBtn = document.getElementById('a11y-contrast');
    const resetBtn = document.getElementById('a11y-reset');

    if (!widget) return;

    // 1. Toggle do Menu (Abrir/Fechar)
    toggleBtn.addEventListener('click', () => {
        widget.classList.toggle('active');
    });

    // --- 2. Controle de Zoom (COM TRAVA DE SEGURANÇA) ---
    let currentZoom = parseFloat(localStorage.getItem('a11y_zoom')) || 1;
    
    // Configurações de Limite
    const ZOOM_STEP = 0.1; // Aumenta de 10% em 10%
    const MAX_ZOOM = 1.5;  // MÁXIMO DE 50% (Seguro para mobile)
    const MIN_ZOOM = 0.8;  // Mínimo de 80%

    function applyZoom(zoom) {
        // Garante que o zoom nunca ultrapasse os limites
        if (zoom > MAX_ZOOM) zoom = MAX_ZOOM;
        if (zoom < MIN_ZOOM) zoom = MIN_ZOOM;
        
        // Arredonda para 1 casa decimal para evitar erros (ex: 1.100000002)
        zoom = Math.round(zoom * 10) / 10;

        document.documentElement.style.fontSize = `${zoom * 100}%`;
        localStorage.setItem('a11y_zoom', zoom);
        currentZoom = zoom; // Atualiza a variável de estado
    }

    // Aplica o zoom salvo ao carregar a página
    if (currentZoom !== 1) applyZoom(currentZoom);

    increaseBtn.addEventListener('click', () => {
        if (currentZoom < MAX_ZOOM) {
            applyZoom(currentZoom + ZOOM_STEP);
        }
    });

    decreaseBtn.addEventListener('click', () => {
        if (currentZoom > MIN_ZOOM) {
            applyZoom(currentZoom - ZOOM_STEP);
        }
    });

    // --- 3. Alto Contraste ---
    let isHighContrast = localStorage.getItem('a11y_contrast') === 'true';

    function applyContrast(enable) {
        if (enable) {
            document.body.classList.add('high-contrast');
        } else {
            document.body.classList.remove('high-contrast');
        }
        localStorage.setItem('a11y_contrast', enable);
    }

    // Aplica contraste salvo
    if (isHighContrast) applyContrast(true);

    contrastBtn.addEventListener('click', () => {
        isHighContrast = !isHighContrast;
        applyContrast(isHighContrast);
    });

    // --- 4. Resetar Tudo ---
    resetBtn.addEventListener('click', () => {
        currentZoom = 1;
        isHighContrast = false;
        applyZoom(1);
        applyContrast(false);
        localStorage.removeItem('a11y_zoom');
        localStorage.removeItem('a11y_contrast');
    });
});