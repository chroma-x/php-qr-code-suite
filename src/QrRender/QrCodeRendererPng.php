<?php

namespace Markenwerk\QrCodeSuite\QrRender;

use Markenwerk\CommonException\IoException;
use Markenwerk\QrCodeSuite\QrEncode\QrCode\QrCode;
use Markenwerk\QrCodeSuite\QrRender\Color\RgbColor;

/**
 * Class QrCodeRendererPng
 *
 * @package Markenwerk\QrCodeSuite\QrRender
 */
class QrCodeRendererPng implements Base\QrCodeRendererInterface
{

	const MARGIN = 2;

	/**
	 * @var RgbColor
	 */
	private $foregroundColor;

	/**
	 * @var RgbColor
	 */
	private $backgroundColor;

	/**
	 * @var int
	 */
	private $approximateSize = 1000;

	/**
	 * @var int
	 */
	private $width;

	/**
	 * @var int
	 */
	private $height;

	/**
	 * QrCodeRendererPng constructor.
	 */
	public function __construct()
	{
		$this->foregroundColor = new RgbColor(0, 0, 0);
		$this->backgroundColor = new RgbColor(255, 255, 255);
	}

	/**
	 * @return RgbColor
	 */
	public function getForegroundColor()
	{
		return $this->foregroundColor;
	}

	/**
	 * @param RgbColor $foregroundColor
	 * @return $this
	 */
	public function setForegroundColor(RgbColor $foregroundColor)
	{
		$this->foregroundColor = $foregroundColor;
		return $this;
	}

	/**
	 * @return RgbColor
	 */
	public function getBackgroundColor()
	{
		return $this->backgroundColor;
	}

	/**
	 * @param RgbColor $backgroundColor
	 * @return $this
	 */
	public function setBackgroundColor(RgbColor $backgroundColor)
	{
		$this->backgroundColor = $backgroundColor;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getApproximateSize()
	{
		return $this->approximateSize;
	}

	/**
	 * @param int $approximateSize
	 * @return $this
	 */
	public function setApproximateSize($approximateSize)
	{
		if (!is_int($approximateSize) || $approximateSize < 0 || $approximateSize > 5000) {
			throw new \InvalidArgumentException('Approximate size has to be a positive integer less than 5000');
		}
		$this->approximateSize = $approximateSize;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->height;
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

		// Get basic info
		$width = $qrCode->getWidth();
		$height = $qrCode->getHeight();

		// Calculate params
		$blockSize = round($this->approximateSize / ($width + 2 * self::MARGIN));
		$this->width = (int)($width + 2 * self::MARGIN) * $blockSize;
		$this->height = (int)($height + 2 * self::MARGIN) * $blockSize;

		// Define colors
		$black = new \ImagickPixel($this->foregroundColor->getHex());
		$white = new \ImagickPixel($this->backgroundColor->getHex());

		// Prepare canvas
		$canvas = new \Imagick();
		$canvas->newImage($this->width, $this->height, $white, "png");
		$canvas->setImageColorspace(\Imagick::COLORSPACE_RGB);
		$canvas->setImageDepth(8);

		// Draw blocks
		$draw = new \ImagickDraw();
		$draw->setFillColor($black);
		$top = self::MARGIN * $blockSize;
		for ($h = 1; $h <= $height; $h++) {
			$left = self::MARGIN * $blockSize;
			$qrCodePointRow = $qrCode->getRow($h);
			for ($w = 1; $w <= $width; $w++) {
				$qrCodePpoint = $qrCodePointRow->getPoint($w);
				if ($qrCodePpoint->isActive()) {
					$draw->rectangle($left, $top, $left + $blockSize, $top + $blockSize);
				}
				$left += $blockSize;
			}
			$top += $blockSize;
		}
		$canvas->drawImage($draw);

		// Write out the image
		$writeSuccess = @$canvas->writeImage($filename);
		$canvas->clear();
		$canvas->destroy();

		if ($writeSuccess !== true) {
			throw new IoException\FileWritableException('QR code output file not writable.');
		}
	}

}
