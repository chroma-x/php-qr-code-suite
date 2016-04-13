<?php

namespace QrCodeSuite\QrRender;

use QrCodeSuite\QrEncode\QrEncoder;

/**
 * Class QrCodeRendererEpsTest
 *
 * @package QrCodeSuite\QrRender
 */
class QrCodeRendererEpsTest extends \PHPUnit_Framework_TestCase
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
		$qrCodeOutputPath = __DIR__ . '/tmp/qrcode-test.eps';
		$qrCodeRendererPng = new QrCodeRendererEps();
		$qrCodeRendererPng->render($qrCode, $qrCodeOutputPath);

		// Test QR code output file exists
		$this->assertFileExists($qrCodeOutputPath);

		// Remove test QR code output file
		unlink($qrCodeOutputPath);
	}

}
