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

            // Dimensões em formato retrato otimizado para mobile
            $width = 450;  // Reduzido para formato retrato
            $height = 600; // Aumentado para formato vertical
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
            
            // Header com gradiente - ajustado para nova largura
            for ($y = 0; $y <= 80; $y++) { // Aumentado de 70 para 80
                $ratio = $y / 80;
                // Simular gradiente diagonal
                $r = (int)(30 + ($ratio * 36));
                $g = (int)(136 + ($ratio * 29));
                $b = (int)(229 + ($ratio * 16));
                $corGradiente = imagecolorallocate($img, $r, $g, $b);
                imageline($img, 0, $y, $width, $y, $corGradiente);
            }
            
            // Logo container - ajustado para nova largura
            imagefilledrectangle($img, 25, 20, 180, 65, $branco);
            
            // Carregar logo
            $logoPath = public_path('img/logos/logo-amaer2.png');
            if (file_exists($logoPath)) {
                $logo = @imagecreatefrompng($logoPath);
                if ($logo) {
                    // Área disponível para logo - ajustada para novo container
                    $logoAreaWidth = 140;  // 180 - 25 - 15 (margem interna)
                    $logoAreaHeight = 35;  // 65 - 20 - 10 (margem interna)
                    
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
                    
                    // Centralizar logo na área disponível - ajustado para novo container
                    $logoX = 25 + (($logoAreaWidth - $logoWidth) / 2) + 10;
                    $logoY = 20 + (($logoAreaHeight - $logoHeight) / 2) + 5;
                    
                    imagecopy($img, $logoResized, $logoX, $logoY, 0, 0, $logoWidth, $logoHeight);
                    
                    imagedestroy($logo);
                    imagedestroy($logoResized);
                }
            } else {
                // Fallback para texto AMAER
                imagestring($img, 5, 60, 28, 'AMAER', $azulPrimario);
            }
            
            // Status ATIVO - ajustado para nova largura
            imagefilledrectangle($img, $width - 80, 25, $width - 20, 50, $verde);
            imagestring($img, 3, $width - 65, 32, 'ATIVO', $branco);
            
            // Foto circular - centralizada e maior para formato retrato
            $fotoSize = 140; // Aumentado de 120 para 140
            $fotoX = ($width - $fotoSize) / 2; // Centralizada horizontalmente
            $fotoY = 120; // Posicionada abaixo do header
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
            
            // Nome centralizado abaixo da foto - fonte maior
            $nome = $user->name;
            if (strlen($nome) > 20) {
                $nome = substr($nome, 0, 17) . '...';
            }
            
            // Calcular posição centralizada do nome
            $nomeWidth = strlen($nome) * 12; // Aproximação da largura do texto
            $nomeX = ($width - $nomeWidth) / 2;
            imagestring($img, 5, $nomeX, $fotoY + $fotoSize + 20, $nome, $preto);
            
            // Informações em layout vertical centralizado - fontes maiores
            $infoStartY = $fotoY + $fotoSize + 60;
            $lineHeight = 40;
            
            // Matrícula
            $matricula = $user->matricula ?: 'AS-' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            imagestring($img, 3, 50, $infoStartY, 'Matricula:', $cinzaTexto);
            imagestring($img, 4, 50, $infoStartY + 15, $matricula, $preto);
            
            // Categoria
            $categoria = $user->categoria ?: 'Aeromodelismo';
            imagestring($img, 3, $width - 200, $infoStartY, 'Categoria:', $cinzaTexto);
            imagestring($img, 4, $width - 200, $infoStartY + 15, $categoria, $preto);
            
            // Ingresso
            $ingresso = $user->created_at->format('d/m/Y');
            imagestring($img, 3, 50, $infoStartY + $lineHeight, 'Ingresso:', $cinzaTexto);
            imagestring($img, 4, 50, $infoStartY + $lineHeight + 15, $ingresso, $preto);
            
            // Validade
            $validade = $user->created_at->addYears(2)->format('d/m/Y');
            imagestring($img, 3, $width - 200, $infoStartY + $lineHeight, 'Validade:', $cinzaTexto);
            imagestring($img, 4, $width - 200, $infoStartY + $lineHeight + 15, $validade, $preto);
            
            // QR Code maior no final do cartão - otimizado para mobile
            $qrSize = 120; // Tamanho maior para mobile
            $qrX = ($width - $qrSize) / 2; // Centralizado horizontalmente
            $qrY = $height - $qrSize - 80; // 80px do bottom para texto
            
            // Fundo branco do QR container
            imagefilledrectangle($img, $qrX - 10, $qrY - 10, $qrX + $qrSize + 10, $qrY + $qrSize + 10, $branco);
            
            // QR Code usando signature para validação
            $signature = md5($user->email . $user->created_at->format('Y-m-d'));
            $qrData = config('app.url') . "/validacao?signature=" . $signature;
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$qrSize}x{$qrSize}&data=" . urlencode($qrData);
            
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
                    imagecopy($img, $qrImage, $qrX, $qrY, 0, 0, $qrSize, $qrSize);
                    imagedestroy($qrImage);
                }
            }
            
            // Texto do QR centralizado - fonte maior
            $textoValidacao = 'Validar em: amaer.com.br/validacao';
            $textoWidth = strlen($textoValidacao) * 6;
            $textoX = ($width - $textoWidth) / 2;
            imagestring($img, 3, $textoX, $qrY + $qrSize + 15, $textoValidacao, $cinzaTexto);
            
            // Footer - ajustado para layout retrato
            imagefilledrectangle($img, 0, $height - 40, $width, $height, $azulPrimario);
            
            // Assinatura eletrônica centralizada
            $assinatura = 'Assinatura: ' . substr(md5($user->email . $user->created_at), 0, 10);
            $assWidth = strlen($assinatura) * 5;
            $assX = ($width - $assWidth) / 2;
            imagestring($img, 2, $assX, $height - 30, $assinatura, $branco);
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
