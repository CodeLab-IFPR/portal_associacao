// Apenas dígitos
export function onlyDigits(value = '') {
    return (value || '').toString().replace(/\D+/g, '');
}

// Máscara CPF: 000.000.000-00
export function maskCPF(value = '') {
    let v = onlyDigits(value).slice(0, 11);
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    return v;
}

// Máscara Telefone
// (DD) 9999-9999 (até 10 dígitos)
// (DD) 99999-9999 (11 dígitos)
export function maskPhone(value = '') {
    let v = onlyDigits(value).slice(0, 11);
    if (v.length <= 2) return v;
    if (v.length <= 6) {
        return v.replace(/(\d{2})(\d+)/, '($1) $2');
    }
    if (v.length <= 10) {
        return v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    }
    return v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
}

// Desformata (para enviar ao backend se quiser manualmente)
export function unmask(value = '') { return onlyDigits(value); }

// Aplica máscara mantendo posição aproximada do cursor
function applyWithCursor(el, formatter) {
    const start = el.selectionStart;
    const before = el.value;
    el.value = formatter(el.value);
    // Ajuste simples do cursor: tenta manter perto do fim da digitação
    const diff = el.value.length - before.length;
    el.setSelectionRange(Math.max(0, start + diff), Math.max(0, start + diff));
}

export function bindMask(input, type) {
    const formatter = type === 'cpf' ? maskCPF : type === 'phone' ? maskPhone : type === 'rg' ? onlyDigits : null;
    if (!formatter) return;

    // Bloqueia caracteres não numéricos (exceto control keys)
    input.addEventListener('keypress', e => {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });

    input.addEventListener('input', () => applyWithCursor(input, formatter));
    // Inicial
    input.value = formatter(input.value);
}

// Inicialização automática via data-mask
export function initMasks(root = document) {
    root.querySelectorAll('[data-mask]')
        .forEach(el => bindMask(el, el.getAttribute('data-mask')));
}

// Auto init ao carregar DOM
if (typeof document !== 'undefined') {
if (document.readyState === 'loading') {
document.addEventListener('DOMContentLoaded', () => initMasks());
} else {
initMasks();
}
}

// Export default opcional
export default {
    onlyDigits,
    maskCPF,
    maskPhone,
    unmask,
    bindMask,
    initMasks
};
