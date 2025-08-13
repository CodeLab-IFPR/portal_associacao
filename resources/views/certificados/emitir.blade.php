@extends('layouts.portal')

@section('title')
Emitir Certificado
@endsection

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-50">
    <div class="text-center">
        <h1>Emitir Certificados</h1>

        <form id="cpfForm" class="mb-2">
            <div class="mb-3">
                <label for="cpf" class="form-label">Digite seu CPF:</label>
                <input type="text" id="cpf" name="cpf" class="form-control rounded" required>
            </div>
            <button type="submit" class="btn btn-primary rounded">Buscar Certificados</button>
        </form>

        <div id="certificadosList"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function aplicarMascaraCPF(valor) {
            valor = valor.replace(/\D/g, '');
            valor = valor.substring(0, 11);
            if (valor.length > 9) {
                valor = valor.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
            } else if (valor.length > 6) {
                valor = valor.replace(/(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3');
            } else if (valor.length > 3) {
                valor = valor.replace(/(\d{3})(\d{1,2})/, '$1.$2');
            } else {
                valor = valor.replace(/(\d{1,2})/, '$1');
            }
            return valor;
        }

        const cpfInput = document.getElementById('cpf');
        cpfInput.addEventListener('input', function () {
            this.value = aplicarMascaraCPF(this.value);
        });

        document.getElementById('cpfForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let cpf = document.getElementById('cpf').value; 

            fetch('{{ route('certificados.buscar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cpf: cpf
                    })
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    let certificadosList = document.getElementById('certificadosList');
                    certificadosList.innerHTML = '';

                    if (data.certificados && data.certificados.length > 0) {
                        let table = '<table class="table"><thead><tr><th>Descrição</th><th>Horas</th><th>Ações</th></tr></thead><tbody>';
                        data.certificados.forEach(certificado => {
                            table += `<tr>
                                <td>${certificado.descricao}</td>
                                <td>${certificado.horas}</td>
                                <td>
                                    <a href="/certificados/${certificado.id}/view" target="_blank">Visualizar</a> |
                                    <a href="/certificados/${certificado.id}/download">Baixar</a>
                                </td>
                            </tr>`;
                        });
                        table += '</tbody></table>';
                        certificadosList.innerHTML = table;
                    } else {
                        certificadosList.innerHTML = '<p>Nenhum certificado encontrado para esse CPF.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar certificados:', error); 
                });
        });
    });
</script>
@endsection
