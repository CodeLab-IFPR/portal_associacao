<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartaoAssociadoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . 
                 urlencode(route('cartao-associado.validar', ['user' => $user->id, 'hash' => md5($user->email . $user->created_at)]));
        
        return view('cartao-associado.index', compact('user', 'qrUrl'));
    }

    public function downloadImage()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->back()->with('error', 'Usuário não encontrado.');
            }

            // Dimensões iguais ao layout web
            $width = 600;
            $height = 380;
            $img = imagecreatetruecolor($width, $height);
            
            // Cores exatas do CSS
            $fundoCartao = imagecolorallocate($img, 248, 249, 250);      // #f8f9fa
            $azulPrimario = imagecolorallocate($img, 30, 136, 229);      // #1e88e5
            $azulClaro = imagecolorallocate($img, 66, 165, 245);         // #42a5f5
            $branco = imagecolorallocate($img, 255, 255, 255);           // #ffffff
            $verde = imagecolorallocate($img, 76, 175, 80);              // #4caf50
            $preto = imagecolorallocate($img, 51, 51, 51);               // #333333
            $cinzaTexto = imagecolorallocate($img, 102, 102, 102);       // #666666
            $cinzaFoto = imagecolorallocate($img, 245, 245, 245);        // #f5f5f5
            $cinzaIcon = imagecolorallocate($img, 153, 153, 153);        // #999999
            
            // Preencher fundo
            imagefill($img, 0, 0, $fundoCartao);
            
            // Header com gradiente igual ao CSS (135deg, #1e88e5, #42a5f5)
            for ($y = 0; $y <= 70; $y++) {
                $ratio = $y / 70;
                // Simular gradiente diagonal
                $r = (int)(30 + ($ratio * 36));
                $g = (int)(136 + ($ratio * 29));
                $b = (int)(229 + ($ratio * 16));
                $corGradiente = imagecolorallocate($img, $r, $g, $b);
                imageline($img, 0, $y, $width, $y, $corGradiente);
            }
            
            // Logo container - fundo branco com transparência (aumentando a área)
            imagefilledrectangle($img, 25, 15, 140, 55, $branco);
            
            // Carregar logo
            $logoPath = public_path('img/logos/logo-amaer2.png');
            if (file_exists($logoPath)) {
                $logo = @imagecreatefrompng($logoPath);
                if ($logo) {
                    // Área disponível para logo (mais espaço)
                    $logoAreaWidth = 105;  // 140 - 25 - 10 (margem interna)
                    $logoAreaHeight = 32;  // 55 - 15 - 8 (margem interna)
                    
                    // Dimensões originais da logo
                    $logoOriginalWidth = imagesx($logo);
                    $logoOriginalHeight = imagesy($logo);
                    
                    // Calcular escala mantendo proporção
                    $scaleX = $logoAreaWidth / $logoOriginalWidth;
                    $scaleY = $logoAreaHeight / $logoOriginalHeight;
                    $scale = min($scaleX, $scaleY);
                    
                    $logoWidth = (int)($logoOriginalWidth * $scale);
                    $logoHeight = (int)($logoOriginalHeight * $scale);
                    
                    // Criar imagem redimensionada
                    $logoResized = imagecreatetruecolor($logoWidth, $logoHeight);
                    imagealphablending($logoResized, false);
                    imagesavealpha($logoResized, true);
                    $transparent = imagecolorallocatealpha($logoResized, 255, 255, 255, 127);
                    imagefill($logoResized, 0, 0, $transparent);
                    
                    imagecopyresampled($logoResized, $logo, 0, 0, 0, 0, 
                                     $logoWidth, $logoHeight, $logoOriginalWidth, $logoOriginalHeight);
                    
                    // Centralizar logo na área disponível
                    $logoX = 25 + (($logoAreaWidth - $logoWidth) / 2) + 5;
                    $logoY = 15 + (($logoAreaHeight - $logoHeight) / 2) + 4;
                    
                    imagecopy($img, $logoResized, $logoX, $logoY, 0, 0, $logoWidth, $logoHeight);
                    
                    imagedestroy($logo);
                    imagedestroy($logoResized);
                }
            } else {
                // Fallback para texto AMAER
                imagestring($img, 5, 60, 28, 'AMAER', $azulPrimario);
            }
            
            // Status ATIVO - formato arredondado
            imagefilledrectangle($img, 520, 23, 575, 47, $verde);
            imagestring($img, 3, 535, 30, 'ATIVO', $branco);
            
            // Foto circular (120x120 como no CSS)
            $fotoX = 50;
            $fotoY = 110;
            $fotoSize = 120;
            $fotoRadius = $fotoSize / 2;
            
            // Criar máscara circular
            $maskImg = imagecreatetruecolor($fotoSize, $fotoSize);
            $maskBg = imagecolorallocate($maskImg, 0, 0, 0);
            $maskWhite = imagecolorallocate($maskImg, 255, 255, 255);
            imagefill($maskImg, 0, 0, $maskBg);
            imagefilledellipse($maskImg, $fotoRadius, $fotoRadius, $fotoSize-8, $fotoSize-8, $maskWhite);
            
            // Desenhar círculo de fundo da foto
            imagefilledellipse($img, $fotoX + $fotoRadius, $fotoY + $fotoRadius, $fotoSize, $fotoSize, $cinzaFoto);
            imagefilledellipse($img, $fotoX + $fotoRadius, $fotoY + $fotoRadius, $fotoSize-8, $fotoSize-8, $branco);
            
            // Ícone de pessoa (placeholder)
            imagestring($img, 5, $fotoX + 45, $fotoY + 50, 'FOTO', $cinzaIcon);
            
            imagedestroy($maskImg);
            
            // Nome (posição igual ao CSS)
            $nome = $user->name;
            if (strlen($nome) > 18) {
                $nome = substr($nome, 0, 15) . '...';
            }
            imagestring($img, 5, 195, 110, $nome, $preto);
            
            // Grid de dados (replicando o layout CSS)
            $baseY = 140;
            
            // Primeira linha - Matrícula e Categoria
            imagestring($img, 2, 195, $baseY, 'Matricula', $cinzaTexto);
            imagestring($img, 2, 320, $baseY, 'Categoria', $cinzaTexto);
            
            $matricula = $user->matricula ?: 'AS-' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            $categoria = $user->categoria ?: 'Aeromodelismo';
            
            imagestring($img, 4, 195, $baseY + 12, $matricula, $preto);
            imagestring($img, 4, 320, $baseY + 12, $categoria, $preto);
            
            // Segunda linha - Ingresso e Validade
            $baseY += 55;
            imagestring($img, 2, 195, $baseY, 'Ingresso', $cinzaTexto);
            imagestring($img, 2, 320, $baseY, 'Validade', $cinzaTexto);
            
            $ingresso = $user->created_at->format('d/m/Y');
            $validade = $user->created_at->addYears(2)->format('d/m/Y');
            
            imagestring($img, 4, 195, $baseY + 12, $ingresso, $preto);
            imagestring($img, 4, 320, $baseY + 12, $validade, $preto);
            
            // QR Code container (igual ao CSS)
            $qrX = 485;
            $qrY = 110;
            $qrContainerSize = 100;
            
            // Fundo branco do QR container
            imagefilledrectangle($img, $qrX, $qrY, $qrX + $qrContainerSize, $qrY + $qrContainerSize, $branco);
            
            // QR Code
            $qrData = route('cartao-associado.validar', [
                'user' => $user->id, 
                'hash' => md5($user->email . $user->created_at)
            ]);
            
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=" . urlencode($qrData);
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 3,
                    'method' => 'GET',
                    'header' => 'User-Agent: Mozilla/5.0'
                ]
            ]);
            
            $qrImageData = @file_get_contents($qrUrl, false, $context);
            if ($qrImageData) {
                $qrImage = @imagecreatefromstring($qrImageData);
                if ($qrImage) {
                    imagecopy($img, $qrImage, $qrX + 15, $qrY + 10, 0, 0, 70, 70);
                    imagedestroy($qrImage);
                }
            }
            
            // Texto do QR (igual ao CSS)
            imagestring($img, 1, $qrX + 15, $qrY + 85, 'Validar em:', $cinzaTexto);
            imagestring($img, 1, $qrX + 5, $qrY + 93, 'amaer.com.br/validar', $cinzaTexto);
            
            // Footer (igual ao CSS)
            imagefilledrectangle($img, 0, 320, $width, $height, $azulPrimario);
            
            // Assinatura eletrônica
            $assinatura = 'Assinatura eletronica: ' . substr(md5($user->email . $user->created_at), 0, 12);
            imagestring($img, 2, 25, 340, $assinatura, $branco);
            
            // Data de emissão
            $dataEmissao = 'Emitido em ' . now()->format('d/m/Y');
            imagestring($img, 2, 450, 340, $dataEmissao, $branco);
            
            // Gerar imagem PNG
            ob_start();
            imagepng($img, null, 8);
            $imageData = ob_get_clean();
            imagedestroy($img);
            
            $fileName = 'cartao-associado-' . preg_replace('/[^A-Za-z0-9\-]/', '', $user->name) . '.png';
            
            Log::info('Cartão gerado com layout web idêntico', [
                'user_id' => $user->id,
                'file_name' => $fileName,
                'size' => strlen($imageData)
            ]);
            
            return response($imageData)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
                
        } catch (\Exception $e) {
            Log::error('Erro ao gerar imagem do cartão', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'Erro ao gerar cartão: ' . $e->getMessage());
        }
    }

    public function validar(Request $request, $user, $hash)
    {
        // Redireciona para a página de validação com a assinatura já processada
        $assinatura = substr($hash, 0, 12); // Pega os primeiros 12 caracteres do hash
        
        // Processa a validação diretamente
        $resultado = 'invalido';
        $usuario = null;
        
        try {
            $usuario = \App\Models\User::findOrFail($user);
            $hashValido = md5($usuario->email . $usuario->created_at);
            
            if ($hash === $hashValido) {
                $resultado = 'valido';
            }
        } catch (\Exception $e) {
            // Usuário não encontrado, resultado já é 'invalido'
        }
        
        // Retorna a view de validação com o resultado
        return view('cartao-associado.validacao', [
            'resultado' => $resultado,
            'usuario' => $usuario,
            'assinatura' => $assinatura
        ]);
    }

    public function paginaValidacao()
    {
        return view('cartao-associado.validacao');
    }

    public function procesarValidacao(Request $request)
    {
        $request->validate([
            'assinatura' => 'required|string|min:12|max:12'
        ], [
            'assinatura.required' => 'A assinatura eletrônica é obrigatória.',
            'assinatura.min' => 'A assinatura eletrônica deve ter exatamente 12 caracteres.',
            'assinatura.max' => 'A assinatura eletrônica deve ter exatamente 12 caracteres.'
        ]);

        $assinatura = strtolower($request->assinatura);
        
        // Buscar usuário pela assinatura eletrônica
        $usuarios = \App\Models\User::all();
        $usuarioEncontrado = null;
        
        foreach ($usuarios as $usuario) {
            $assinaturaGerada = substr(md5($usuario->email . $usuario->created_at), 0, 12);
            if (strtolower($assinaturaGerada) === $assinatura) {
                $usuarioEncontrado = $usuario;
                break;
            }
        }

        if ($usuarioEncontrado) {
            return view('cartao-associado.validacao', [
                'resultado' => 'valido',
                'usuario' => $usuarioEncontrado,
                'assinatura' => $assinatura
            ]);
        } else {
            return view('cartao-associado.validacao', [
                'resultado' => 'invalido',
                'assinatura' => $assinatura
            ]);
        }
    }
}
