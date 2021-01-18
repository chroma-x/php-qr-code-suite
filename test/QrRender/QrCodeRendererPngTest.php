<?php

namespace Markenwerk\QrCodeSuite\QrRender;

use Markenwerk\QrCodeSuite\QrEncode\QrEncoder;
use Markenwerk\QrCodeSuite\QrRender\Color\RgbColor;

/**
 * Class QrCodeRendererPngTest
 *
 * @package Markenwerk\QrCodeSuite\QrRender
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
		$qrCodeRendererPng
			->setApproximateSize(800)
			->setForegroundColor(new RgbColor(255, 0, 255))
			->setBackgroundColor(new RgbColor(0, 0, 0))
			->render($qrCode, $qrCodeOutputPath);

		// Test QR code output file exists
		$this->assertFileExists($qrCodeOutputPath);

		// Test QR code output file mesaurement
		$imageSize = getimagesize($qrCodeOutputPath);
		$this->assertEquals($qrCodeRendererPng->getWidth(), $imageSize[0]);
		$this->assertEquals($qrCodeRendererPng->getHeight(), $imageSize[1]);

		// Remove test QR code output file
		unlink($qrCodeOutputPath);
	}

}
