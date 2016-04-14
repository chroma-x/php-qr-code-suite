<?php

namespace QrCodeSuite\QrRender;

use QrCodeSuite\QrEncode\QrEncoder;
use QrCodeSuite\QrRender\Color\CmykColor;

/**
 * Class QrCodeRendererTiffTest
 *
 * @package QrCodeSuite\QrRender
 */
class QrCodeRendererTiffTest extends \PHPUnit_Framework_TestCase
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
		$qrCodeOutputPath = __DIR__ . '/tmp/qrcode-test.tif';
		$qrCodeRendererTiff = new QrCodeRendererTiff();
		$qrCodeRendererTiff
			->setApproximateSize(800)
			->setForegroundColor(new CmykColor(60, 80, 0, 0))
			->setBackgroundColor(new CmykColor(40, 100, 100, 70))
			->render($qrCode, $qrCodeOutputPath);

		// Test QR code output file exists
		$this->assertFileExists($qrCodeOutputPath);

		// Test QR code output file mesaurement
		$imageSize = getimagesize($qrCodeOutputPath);
		$this->assertEquals($qrCodeRendererTiff->getWidth(), $imageSize[0]);
		$this->assertEquals($qrCodeRendererTiff->getHeight(), $imageSize[1]);

		// Remove test QR code output file
		unlink($qrCodeOutputPath);
	}

}
