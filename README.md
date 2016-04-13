# QR Code Suite

[![Code Climate](https://codeclimate.com/github/markenwerk/php-qr-code-suite/badges/gpa.svg)](https://codeclimate.com/github/markenwerk/php-qr-code-suite)
[![Latest Stable Version](https://poser.pugx.org/markenwerk/qr-code-suite/v/stable)](https://packagist.org/packages/markenwerk/qr-code-suite)
[![Total Downloads](https://poser.pugx.org/markenwerk/qr-code-suite/downloads)](https://packagist.org/packages/markenwerk/qr-code-suite)
[![License](https://poser.pugx.org/markenwerk/qr-code-suite/license)](https://packagist.org/packages/markenwerk/qr-code-suite)

A collection of classes to QR enccode strings and render them as PNG, TIFF and vectorized EPS.

## Requirements

To use the QR Code Suite [`qrencode`](https://wiki.ubuntuusers.de/qrencode/) and [`imagick`](http://php.net/manual/de/book.imagick.php) have to be available. 

## Installation

```{json}
{
   	"require": {
        "markenwerk/qr-code-suite": "~2.0"
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
	->encodeQrCode('https://github.com/markenwerk/php-qr-code-suite');
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

## Contribution

Contributing to our projects is always very appreciated.  
**But: please follow the contribution guidelines written down in the `CONTRIBUTING.md` document.**

## TODOs

- Decorate the code base with some unit tests.
- Allow configuration of the QR codes like: 
  - Foreground color
  - Background color
  - Image size of PNG and TIFF

## License

QR Code Suite is under the MIT license.