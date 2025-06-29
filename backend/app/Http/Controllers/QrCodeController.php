<?php

namespace App\Http\Controllers;

use App\Models\QrCode;

class QrCodeController extends Controller
{
    public function index()
    {
        $qrCode = new QrCode;
        $normalQr = $qrCode->normal();
        $customQr = $qrCode->custom();
        $designQr = $qrCode->design();
        $imagePatternQr = $qrCode->imagePattern();
        $size = $qrCode->getSize();

        return view('index', compact('normalQr', 'customQr', 'designQr', 'imagePatternQr', 'size'));
    }
}
