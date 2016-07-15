<?php

namespace Markenwerk\QrCodeSuite\QrRender;

/**
 * Class AbstractPixelRenderer
 *
 * @package Markenwerk\QrCodeSuite\QrRender
 */
abstract class AbstractPixelRenderer
{

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
	 * @param int $width
	 * @return $this
	 */
	protected function setWidth($width)
	{
		$this->width = $width;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @param int $height
	 * @return $this
	 */
	protected function setHeight($height)
	{
		$this->height = $height;
		return $this;
	}

}
