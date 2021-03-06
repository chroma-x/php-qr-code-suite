# QR Code Suite

[![Code Climate](https://codeclimate.com/github/chroma-x/php-qr-code-suite/badges/gpa.svg)](https://codeclimate.com/github/chroma-x/php-qr-code-suite)
[![Latest Stable Version](https://poser.pugx.org/chroma-x/qr-code-suite/v/stable)](https://packagist.org/packages/chroma-x/qr-code-suite)
[![Total Downloads](https://poser.pugx.org/chroma-x/qr-code-suite/downloads)](https://packagist.org/packages/chroma-x/qr-code-suite)
[![License](https://poser.pugx.org/chroma-x/qr-code-suite/license)](https://packagist.org/packages/chroma-x/qr-code-suite)

A collection of classes to QR enccode strings and render them as PNG, TIFF and vectorized EPS.

## Requirements

To use the QR Code Suite [`qrencode`](https://wiki.ubuntuusers.de/qrencode/) and [`imagick`](http://php.net/manual/de/book.imagick.php) have to be available. 

## Installation

```{json}
{
   	"require": {
        "chroma-x/qr-code-suite": "~2.0"
    }
}
```

## Usage

### Autoloading and namesapce

```{php}  
require_once('path/to/vendor/autoload.php');
```

### Encoding data as QR code block data

```{php}
use QrCodeSuite\QrEncode\QrEncoder;

// Encode the data as QR code block data
$encoder = new QrEncoder();
$qrCodeData = $encoder
	->setLevel(QrEncoder::QR_CODE_LEVEL_LOW)
	->setTempDir('path/to/writable/directory')
	->encodeQrCode('https://github.com/chroma-x/php-qr-code-suite');
```

### Render encoded QR code block data as image

```{php}
use QrCodeSuite\QrRender;

// Render the encoded QR code block data as RGB PNG
$renderer = new QrRender\QrCodeRendererPng();
$renderer->render($qrCodeData, 'path/to/qr-code.png');

// Render the encoded QR code block data as CMYK TIFF
$renderer = new QrRender\QrCodeRendererTiff();
$renderer->render($qrCodeData, 'path/to/qr-code.tif');

// Render the encoded QR code block data as CMYK vectorized EPS
$renderer = new QrRender\QrCodeRendererEps();
$renderer->render($qrCodeData, 'path/to/qr-code.eps');
```

---

## License

QR Code Suite is under the MIT license.
