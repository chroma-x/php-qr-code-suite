<?php

namespace Markenwerk\QrCodeSuite\QrEncode;

/**
 * Class QrEncoderTest
 *
 * @package Markenwerk\QrCodeSuite\QrEncode
 */
class QrEncoderTest extends \PHPUnit_Framework_TestCase
{

	const QR_CODE_CONTENT = 'Commodo Adipiscing Justo Vehicula Tellus';

	public function testEncodeQrCode()
	{
		$qrEncoder = new QrEncoder();
		$qrCode = $qrEncoder
			->setLevel(QrEncoder::QR_CODE_LEVEL_LOW)
			->setTempDir(__DIR__ . '/tmp/')
			->encodeQrCode(self::QR_CODE_CONTENT);
		$this->assertEquals(31, $qrCode->countRows());
		$this->assertEquals(31, $qrCode->getRow(0)->countPoints());
		$qrCode = $qrEncoder
			->setLevel(QrEncoder::QR_CODE_LEVEL_MEDIUM)
			->encodeQrCode(self::QR_CODE_CONTENT);
		$this->assertEquals(31, $qrCode->countRows());
		$this->assertEquals(31, $qrCode->getRow(0)->countPoints());
		$qrCode = $qrEncoder
			->setLevel(QrEncoder::QR_CODE_LEVEL_QUALITY)
			->encodeQrCode(self::QR_CODE_CONTENT);
		$this->assertEquals(35, $qrCode->countRows());
		$this->assertEquals(35, $qrCode->getRow(0)->countPoints());
		$qrCode = $qrEncoder
			->setLevel(QrEncoder::QR_CODE_LEVEL_HIGH)
			->encodeQrCode(self::QR_CODE_CONTENT);
		$this->assertEquals(39, $qrCode->countRows());
		$this->assertEquals(39, $qrCode->getRow(0)->countPoints());
	}

}
