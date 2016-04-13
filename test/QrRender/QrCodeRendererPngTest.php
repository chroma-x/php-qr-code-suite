<?php

namespace QrCodeSuite\QrRender;

use QrCodeSuite\QrEncode\QrEncoder;

/**
 * Class QrCodeRendererPngTest
 *
 * @package QrCodeSuite\QrRender
 */
class QrCodeRendererPngTest extends \PHPUnit_Framework_TestCase
{

	const QR_CODE_CONTENT = 'Commodo Adipiscing Justo Vehicula Tellus';

	public function testRender()
	{
		// Encode content
		$qrEncoder = new QrEncoder();
		$qrCode = $qrEncoder
			->setLevel(QrEncoder::QR_CODE_LEVEL_LOW)
			->setTempDir(__DIR__ . '/tmp/')
			->encodeQrCode(self::QR_CODE_CONTENT);

		// Render image
		$qrCodeOutputPath = __DIR__ . '/tmp/qrcode-test.png';
		$qrCodeRendererPng = new QrCodeRendererPng();
		$qrCodeRendererPng->render($qrCode, $qrCodeOutputPath);

		// Test QR code output file exists
		$this->assertFileExists($qrCodeOutputPath);

		// Test QR code output file mesaurement
		$blockSize = ceil(1000 / ($qrCode->getWidth() + 2 * QrCodeRendererPng::MARGIN));
		$symbolWidth = ($qrCode->getWidth() + 2 * QrCodeRendererPng::MARGIN) * $blockSize;
		$symbolHeight = ($qrCode->getHeight() + 2 * QrCodeRendererPng::MARGIN) * $blockSize;
		$imageSize = getimagesize($qrCodeOutputPath);
		$this->assertEquals($symbolWidth, $imageSize[0]);
		$this->assertEquals($symbolHeight, $imageSize[1]);

		// Remove test QR code output file
		unlink($qrCodeOutputPath);
	}

}
