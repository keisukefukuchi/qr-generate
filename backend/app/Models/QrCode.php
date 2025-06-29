<?php

namespace App\Models;

use Illuminate\Support\Str;
use Imagick;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

/**
 * QRコード生成クラス
 *
 * @property Imagick $qrImage QRコード画像
 * @property Imagick $patternImage パターン画像
 * @property Imagick $background 背景画像
 */
class QrCode
{
    private string $url;

    private int $size;

    private string $storagePath = 'public/qrcodes';

    public function __construct(string $url = 'https://estra.jp/', int $size = 200)
    {
        $this->url = $url;
        $this->size = $size;
    }

    private function generateAndSave(callable $generator): string
    {
        $filename = Str::random(40).'.png';
        $path = storage_path('app/'.$this->storagePath.'/'.$filename);

        if (! file_exists(storage_path('app/'.$this->storagePath))) {
            mkdir(storage_path('app/'.$this->storagePath), 0755, true);
        }

        $qrCode = $generator();
        file_put_contents($path, $qrCode);

        return asset('storage/qrcodes/'.$filename);
    }

    public function normal(): string
    {
        return $this->generateAndSave(function () {
            return QrCodeGenerator::format('png')
                ->size($this->size)
                ->generate($this->url);
        });
    }

    public function custom(): string
    {
        return $this->generateAndSave(function () {
            return QrCodeGenerator::format('png')
                ->size($this->size)
                ->style('round')
                ->eye('circle')
                ->color(0, 0, 255)
                ->backgroundColor(255, 255, 255)
                ->margin(1)
                ->generate($this->url);
        });
    }

    public function design(): string
    {
        return $this->generateAndSave(function () {
            return QrCodeGenerator::format('png')
                ->size($this->size)
                ->style('dot')
                ->eye('square')
                ->backgroundColor(255, 255, 204)
                ->color(51, 153, 255)
                ->gradient(51, 153, 255, 255, 102, 102, 'diagonal')
                ->margin(2)
                ->generate($this->url);
        });
    }

    public function imagePattern(): string
    {
        // 通常のQRコードを生成して一時ファイルに保存
        $qrCode = QrCodeGenerator::format('png')
            ->size($this->size)
            ->margin(1)
            ->generate($this->url);

        $tempQrPath = tempnam(sys_get_temp_dir(), 'qr_');
        file_put_contents($tempQrPath, $qrCode);

        // パターンとして使用する画像のパス
        $patternPath = public_path('images/qr.png');

        try {
            // QRコード画像を読み込み
            $qrImage = new Imagick($tempQrPath);

            // パターン画像を読み込み
            $patternImage = new Imagick($patternPath);

            // 白背景を作成
            $background = new Imagick;
            $background->newImage($this->size, $this->size, 'white');
            $background->setImageFormat('png');

            // QRコードを2値化
            $qrImage->thresholdImage(0.5 * $qrImage->getQuantum());

            // QRコードの黒い部分のみを抽出するマスクを作成
            $blackMask = clone $qrImage;
            $blackMask->negateImage(false);

            // パターン画像をQRコードと同じサイズにリサイズ
            $patternImage->resizeImage($this->size, $this->size, Imagick::FILTER_LANCZOS, 1);

            // パターン画像の明るさとコントラストを調整
            $patternImage->modulateImage(80, 120, 100); // 明るさ80%, コントラスト120%
            $patternImage->contrastImage(true);

            // パターン画像をQRコードの黒い部分だけに適用
            $patternImage->compositeImage($blackMask, Imagick::COMPOSITE_COPYOPACITY, 0, 0);

            // 背景と合成
            $background->compositeImage($patternImage, Imagick::COMPOSITE_OVER, 0, 0);

            // 結果を保存
            $filename = Str::random(40).'.png';
            $path = storage_path('app/'.$this->storagePath.'/'.$filename);

            if (! file_exists(storage_path('app/'.$this->storagePath))) {
                mkdir(storage_path('app/'.$this->storagePath), 0755, true);
            }

            $background->writeImage($path);

            // 一時ファイルを削除
            unlink($tempQrPath);

            return asset('storage/qrcodes/'.$filename);
        } catch (\Exception $e) {
            if (file_exists($tempQrPath)) {
                unlink($tempQrPath);
            }
            throw $e;
        }
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
