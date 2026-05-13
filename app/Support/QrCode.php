<?php

namespace App\Support;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCode
{
    public static function svgDataUri(string $contents, int $size = 160): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new SvgImageBackEnd()
        );

        $svg = (new Writer($renderer))->writeString($contents);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
