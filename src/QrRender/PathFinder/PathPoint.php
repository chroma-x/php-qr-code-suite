<?php

namespace QrCodeSuite\QrRender\PathFinder;

/**
 * Class PathPoint
 *
 * @package QrCodeSuite\QrRender\PathFinder
 */
class PathPoint
{

	/**
	 * @var int
	 */
	private $xPosition;

	/**
	 * @var int
	 */
	private $yPosition;

	/**
	 * PathPoint constructor.
	 *
	 * @param int $xPosition
	 * @param int $yPosition
	 */
	public function __construct($xPosition, $yPosition)
	{
		$this->xPosition = $xPosition;
		$this->yPosition = $yPosition;
	}

	/**
	 * @return int
	 */
	public function getXPosition()
	{
		return $this->xPosition;
	}

	/**
	 * @return int
	 */
	public function getYPosition()
	{
		return $this->yPosition;
	}

}
