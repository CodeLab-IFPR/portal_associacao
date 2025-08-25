document.addEventListener('DOMContentLoaded', function () {
    const cepInput = document.getElementById('cep');
    let hasEdited = false;
    if (!cepInput) return;

    cepInput.addEventListener('input', function(e) {
        hasEdited = true;
    });

    // Buscar endereço quando sair do campo
    cepInput.addEventListener('blur', function () {
        if(!hasEdited) return
        const cep = cepInput.value;
        hasEdited = false;

        if (cep.length !== 9) {
            console.log('não 9');
            return;
        }
        
        showLoading();
        
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            hideLoading();

            if (data.erro) {
                    showError('CEP não encontrado');
                    fillAddressFields('')
                    return;
                }
                
                fillAddressFields(data);
            })
            .catch(error => {
                hideLoading();
                showError('Erro ao buscar CEP');
                fillAddressFields('')
                console.error('Erro:', error);
            });
    });

    function fillAddressFields(data) 
    {
        const fields = {
            'logradouro': data.logradouro || '',
            'bairro': data.bairro || '',
            'cidade': data.localidade || '',
            'estado': data.uf || ''
        };
        
        Object.keys(fields).forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.value = fields[fieldName];
                field.readOnly = false;
            }
        });
    }

    function showLoading() {
        const loadingEl = document.getElementById('cep-loading');
        if (loadingEl) {
            loadingEl.style.display = 'block';
        }
    }

    function hideLoading() {
        const loadingEl = document.getElementById('cep-loading');
        if (loadingEl) {
            loadingEl.style.display = 'none';
        }
    }

    function showError(message) {
        const errorEl = document.getElementById('cep-error');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.display = 'block';
            setTimeout(() => {
                errorEl.style.display = 'none';
            }, 3000);
        }
    }
});