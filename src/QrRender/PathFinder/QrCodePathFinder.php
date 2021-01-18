<?php

namespace ChromaX\QrCodeSuite\QrRender\PathFinder;

use ChromaX\QrCodeSuite\QrEncode\QrCode\QrCode;

/**
 * Class QrCodePathFinder
 *
 * @package ChromaX\QrCodeSuite\QrRender\PathFinder
 */
class QrCodePathFinder
{

	const DIRECTION_RIGHT = 0;
	const DIRECTION_DOWN = 1;
	const DIRECTION_LEFT = 2;
	const DIRECTION_UP = 3;

	/**
	 * @var QrCode
	 */
	private $qrCode;

	/**
	 * @var Path[]
	 */
	protected $paths;

	/**
	 * @var array
	 */
	protected $visited;

	/**
	 * @param QrCode $qrCode
	 * @return Path[]
	 */
	public function perform(QrCode $qrCode)
	{
		$this->qrCode = $qrCode;
		$this->paths = array();
		$this->visited = array();
		for ($h = 1; $h < $qrCode->getHeight() - 1; $h++) {
			$this->visited[$h] = array();
		}

		$this->findPaths();
		return $this->paths;
	}

	private function findPaths()
	{
		for ($y = 1; $y <= $this->qrCode->getHeight(); $y++) {
			$qrCodePointRow = $this->qrCode->getRow($y);
			if ($qrCodePointRow !== null) {
				for ($x = 1; $x <= $this->qrCode->getWidth(); $x++) {
					if (!isset($this->visited[$y][$x])) {
						$qrCodePoint = $qrCodePointRow->getPoint($x);
						if ($qrCodePoint !== null && $this->isCorner($x, $y)) {
							$this->paths[] = $this->traceComposite($x, $y, $qrCodePoint->isActive());
							$this->visited[$y][$x] = true;
						}
					}
				}
			}
		}
	}

	/**
	 * @param int $xPosition
	 * @param int $yPosition
	 * @return bool
	 */
	private function isCorner($xPosition, $yPosition)
	{
		$qrCodePointRow = $this->qrCode->getRow($yPosition);
		$previouseQrCodePointRow = $this->qrCode->getRow($yPosition - 1);
		if ($qrCodePointRow === null || $previouseQrCodePointRow === null) {
			return false;
		}
		$currentPoint = $qrCodePointRow->getPoint($xPosition);
		$neighbourPoint = $previouseQrCodePointRow->getPoint($xPosition - 1);
		if ($neighbourPoint === $qrCodePointRow->getPoint($xPosition - 1) && $neighbourPoint === $previouseQrCodePointRow->getPoint($xPosition)) {
			if ($neighbourPoint !== $currentPoint) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param int $startXPosition
	 * @param int $startYPosition
	 * @param bool $active
	 * @return Path
	 */
	private function traceComposite($startXPosition, $startYPosition, $active)
	{

		$xPosition = $startXPosition;
		$yPosition = $startYPosition;
		$switched = !$active;

		$direction = self::DIRECTION_RIGHT;

		$path = new Path();
		$path->addPoint($this->getPoint($xPosition, $yPosition));

		do {
			switch ($direction) {
				case self::DIRECTION_RIGHT:
					$current = $this->qrCode->getRow($yPosition)->getPoint($xPosition);
					$other = $this->qrCode->getRow($yPosition - 1)->getPoint($xPosition);
					if ($current !== $other) {
						$switched = $other;
						$xPosition++;
					} else {
						$path->addPoint($this->getPoint($xPosition, $yPosition));
						$direction = ($switched === $other) ? self::DIRECTION_DOWN : self::DIRECTION_UP;
					}
					break;
				case self::DIRECTION_UP:
					$current = $this->qrCode->getRow($yPosition - 1)->getPoint($xPosition);
					$other = $this->qrCode->getRow($yPosition - 1)->getPoint($xPosition - 1);
					if ($current !== $other) {
						$switched = $other;
						$yPosition--;
					} else {
						$path->addPoint($this->getPoint($xPosition, $yPosition));
						$direction = ($switched === $other) ? self::DIRECTION_RIGHT : self::DIRECTION_LEFT;
						if ($direction === self::DIRECTION_RIGHT && $this->isCorner($xPosition, $yPosition)) {
							$this->visited[$yPosition][$xPosition] = true;
						}
					}
					break;
				case self::DIRECTION_LEFT:
					$current = $this->qrCode->getRow($yPosition - 1)->getPoint($xPosition - 1);
					$other = $this->qrCode->getRow($yPosition)->getPoint($xPosition - 1);
					if ($current !== $other) {
						$switched = $other;
						$xPosition--;
					} else {
						$path->addPoint($this->getPoint($xPosition, $yPosition));
						$direction = ($switched === $other) ? self::DIRECTION_UP : self::DIRECTION_DOWN;
						if ($direction === self::DIRECTION_DOWN && $this->isCorner($xPosition, $yPosition)) {
							$this->visited[$yPosition][$xPosition] = true;
						}
					}
					break;
				case self::DIRECTION_DOWN:
					$current = $this->qrCode->getRow($yPosition)->getPoint($xPosition - 1);
					$other = $this->qrCode->getRow($yPosition)->getPoint($xPosition);
					if ($current !== $other) {
						$switched = $other;
						$yPosition++;
					} else {
						$path->addPoint($this->getPoint($xPosition, $yPosition));
						$direction = ($switched === $other) ? self::DIRECTION_LEFT : self::DIRECTION_RIGHT;
					}
					break;
			}
		} while (!($xPosition === $startXPosition && $yPosition === $startYPosition));

		return $path;

	}

	/**
	 * @param int $xPosition
	 * @param int $yPosition
	 * @return PathPoint
	 */
	private function getPoint($xPosition, $yPosition)
	{
		return new PathPoint($xPosition - 1, $yPosition - 1);
	}

}




