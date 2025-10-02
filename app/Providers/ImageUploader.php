<?php

namespace App\Providers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\File;

class ImageUploader
{
    protected $resolution;
    protected $compression;
    protected $destinationPath;

    // Construtor que recebe os parâmetros de resolução, compressão e caminho de destino da imagem padrão
    public function __construct($resolution = [1280, null], $compression = 75, $destinationPath = 'imagens/')
    {
        $this->resolution = $resolution;
        $this->compression = $compression;
        $this->destinationPath = $destinationPath;
    }

    public function setResolution($width, $height = null)
    {
        $this->resolution = [$width, $height];
    }

    public function setCompression($compression)
    {
        $this->compression = $compression;
    }

    public function setDestinationPath($path)
    {
        $this->destinationPath = ($this->destinationPath) . $path;

        $destinationPath = 'imagens\\'.$path.'\\';
        if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
        }
    }

    public function upload($image, $oldImagePath = null)
    {
        $oldImagePath = public_path($this->destinationPath) . $oldImagePath;
        if ($oldImagePath && File::exists($oldImagePath)) {
            File::delete($oldImagePath);
        }

        $profileImage = date('YmdHis') . ".jpg";
        $manager = new ImageManager(new GdDriver());

        $image = $manager->read($image->getRealPath())
                         ->resize($this->resolution[0], $this->resolution[1]);

        $image->toJpeg($this->compression)->save(public_path($this->destinationPath) . '/' . $profileImage);

        return $profileImage;
    }
}