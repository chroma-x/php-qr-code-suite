<?php

namespace Markenwerk\QrCodeSuite\QrRender;

use Markenwerk\CommonException\IoException;
use Markenwerk\QrCodeSuite\QrEncode\QrCode\QrCode;
use Markenwerk\QrCodeSuite\QrRender\Color\CmykColor;
use Markenwerk\QrCodeSuite\QrRender\PathFinder\PathPoint;
use Markenwerk\QrCodeSuite\QrRender\PathFinder\QrCodePathFinder;

/**
 * Class QrCodeRendererEps
 *
 * @package Markenwerk\QrCodeSuite\QrRender
 */
class QrCodeRendererEps implements Base\QrCodeRendererInterface
{

	const POINTS_PER_BLOCK = 5;

	/**
	 * @var CmykColor
	 */
	private $foregroundColor;

	/**
	 * @var int
	 */
	private $epsHeight;

	/**
	 * @return CmykColor
	 */
	public function getForegroundColor()
	{
		return $this->foregroundColor;
	}

	/**
	 * @param CmykColor $foregroundColor
	 * @return $this
	 */
	public function setForegroundColor(CmykColor $foregroundColor)
	{
		$this->foregroundColor = $foregroundColor;
		return $this;
	}

	/**
	 * @param QrCode $qrCode
	 * @param string $filename
	 * @throws IoException\FileWritableException
	 */
	public function render(QrCode $qrCode, $filename)
	{
		if (!is_dir(dirname($filename)) || !is_writable(dirname($filename))) {
			throw new IoException\FileWritableException('QR code path not writable.');
		}

		$pathFinder = new QrCodePathFinder();
		$paths = $pathFinder->perform($qrCode);

		$epsSource = array();
		$width = $qrCode->getWidth();
		$height = $qrCode->getHeight();

		$epsWidth = self::POINTS_PER_BLOCK * ($width + 2 * self::MARGIN);
		$epsHeight = self::POINTS_PER_BLOCK * ($height + 2 * self::MARGIN);
		$this->epsHeight = $epsHeight;

		$epsSource[] = '%!PS-Adobe-2.0 EPSF-2.0';
		$epsSource[] = '%%BoundingBox: 0 0 ' . ceil($epsWidth) . ' ' . ceil($epsHeight) . '';
		$epsSource[] = '%%HiResBoundingBox: 0 0 ' . $epsWidth . ' ' . $epsHeight . '';
		$epsSource[] = '%%Creator: tk | markenwerk';
		$epsSource[] = '%%CreationDate: ' . time();
		$epsSource[] = '%%DocumentData: Clean7Bit';
		$epsSource[] = '%%LanguageLevel: 2';
		$epsSource[] = '%%Pages: 1';
		$epsSource[] = '%%EndComments';
		$epsSource[] = '%%Page: 1 1';
		$epsSource[] = '/m/moveto load def';
		$epsSource[] = '/l/lineto load def';
		$epsSource[] = $this->foregroundColor->getEpsNotation() . ' setcmykcolor';

		foreach ($paths as $path) {
			$pointCount = $path->countPoints();
			for ($i = 0; $i < $pointCount; $i++) {
				if ($i == 0) {
					$epsSource[] = $this->convertPoint($path->getFirstPoint()) . ' m';
				} else {
					$epsSource[] = $this->convertPoint($path->getPoint($i)) . ' l';
				}
			}
			$epsSource[] = 'closepath';
		}

		$epsSource[] = 'eofill';
		$epsSource[] = '%%EOF';

		$bytes = @file_put_contents($filename, implode("\n", $epsSource));
		if ($bytes === false) {
			throw new IoException\FileWritableException('QR code output file not writable.');
		}
	}

	/**
	 * @param PathPoint $point
	 * @return string
	 */
	private function convertPoint(PathPoint $point)
	{
		$xPosition = self::POINTS_PER_BLOCK * ($point->getXPosition() + self::MARGIN);
		$yPosition = $this->epsHeight - self::POINTS_PER_BLOCK * ($point->getYPosition() + self::MARGIN);
		return $xPosition . ' ' . $yPosition . ' ';
	}

}
