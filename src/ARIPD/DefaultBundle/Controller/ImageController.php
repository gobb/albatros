<?php
namespace ARIPD\DefaultBundle\Controller;

use ARIPD\AdminBundle\Util\ARIPDString;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDDefaultBundle:Default
 *
 * @Route("/image")
 */
class ImageController extends Controller {
	
	private function getFont() {
		return $this->get('kernel')->getRootDir() . '/../web/bundles/aripddefault/font/ARIAL.TTF';
	}
	
	/**
	 * @Route("/{width}x{height}/{bgColor}/{color}", requirements={"width" = "\d+"}, defaults={"height" = null, "bgColor" = "f", "color" = "0"}, name="aripd_default_image_index")
	 * @Template()
	 */
	public function indexAction($width, $height=null, $bgColor=null, $color=null) {
		
		$font = $this->getFont();
		$font_size = 30;
		
		// The text to draw
		$text = ($this->getRequest()->get('text')==null)?$width."x".$height:$this->getRequest()->get('text');
				
		$size = imagettfbbox($font_size, 0, $font, $text);
		$xsize = abs($size[0]) + abs($size[2]);
		$ysize = abs($size[5]) + abs($size[1]);
		
		$src = imagecreate($xsize, $ysize);
		
		$c_cccccc = imagecolorallocate($src, 204, 204, 204);
		$c_969696 = imagecolorallocate($src, 150, 150, 150);
		
		imagettftext($src, $font_size, 0, abs($size[0]), abs($size[5]), $c_969696, $font, $text);
		
		header("content-type: image/png");
		imagepng($src);
		imagedestroy($src);
		exit;
	}
	
	public function index13Action($width, $height=null, $bgColor=null, $color=null) {
		$rootdir = $this->get('kernel')->getRootDir() . '/Resources/public/img/';
		
		$dest = imagecreatefromjpeg($rootdir.'1.jpg');
		$src = imagecreatefromjpeg($rootdir.'2.jpg');
		
		// Copy and merge
		imagecopymerge($dest, $src, 100, 100, 0, 0, 295, 499, 75);
		
		// Output and free from memory
		header('Content-Type: image/jpg');
		imagejpeg($dest);
		imagedestroy($dest);
		imagedestroy($src);
		exit;
	}
	
	public function index12Action($width, $height=null, $bgColor=null, $color=null) {
		
		$font = $this->getFont();
		$font_size = 30;
		$text = "Hello, öçşığü ÖÇŞİĞÜ!";
		
		$size = imagettfbbox($font_size, 0, $font, $text);
		$xsize = abs($size[0]) + abs($size[2]);
		$ysize = abs($size[5]) + abs($size[1]);
		
		$im = imagecreate($xsize, $ysize);
		
		$c_cccccc = imagecolorallocate($im, 204, 204, 204);
		$c_969696 = imagecolorallocate($im, 150, 150, 150);
		
		imagettftext($im, $font_size, 0, abs($size[0]), abs($size[5]), $c_969696, $font, $text);
		
		header("content-type: image/png");
		imagepng($im);
		imagedestroy($im);
		exit;
	}
	
	/**
	 * Check: http://dummyimage.com/
	 */
	public function index11Action($width, $height=null, $bgColor=null, $color=null) {
		
		// Font
		$font = $this->getFont();
		$font_size = 30;
		$angle = 0;
		$shadow = false;
		
		// Set width and height
		$height = ($height==null)?$width:$height;
		
		// The text to draw
		$text = ($this->getRequest()->get('text')==null)?$width."x".$height:$this->getRequest()->get('text');
		
		// Create the image
		$im = imagecreatetruecolor($width, $height);
		
		// Create some colors
		$c_ffffff = imagecolorallocate($im, 255, 255, 255);
		$c_808080 = imagecolorallocate($im, 128, 128, 128);
		$c_000000 = imagecolorallocate($im, 0, 0, 0);
		$c_cccccc = imagecolorallocate($im, 204, 204, 204);
		$c_969696 = imagecolorallocate($im, 150, 150, 150);
		
		imagefilledrectangle($im, 0, 0, $width-1, $height-1, $c_cccccc);
		
		// Font dimensions
		$dimensions = imagettfbbox($font_size, $angle, $font, $text);
		$textWidth  = abs($dimensions[4] - $dimensions[0]);
		$textHeight = abs($dimensions[7] - $dimensions[1]);
		
		// Calculation of text alignment
		$x = ceil((imagesx($im) - $textWidth) / 2);
		$y = ceil((imagesy($im) - $textHeight) / 2) + $font_size;
		
		// Add some shadow to the text
		if ($shadow) {
			imagettftext($im, $font_size, $angle, $x+1, $y+1, $c_808080, $font, $text);
		}
		
		// Add the text
		imagettftext($im, $font_size, $angle, $x, $y, $c_969696, $font, $text);
		
		// Set the header
		header('Content-Type: image/png');
		// Using imagepng() results in clearer text compared with imagejpeg()
		imagepng($im);
		imagedestroy($im);
		exit;
	}
	
	public function index1Action($width, $height = null, $bgColor = null,
			$color = null) {
		
		// Font
		$font = $this->getFont();
		$size = "60";
		
		// Set width and height
		$height = ($height==null)?$width:$height;
		
		// The text to draw
		$text = 'öçşığü ÖÇŞİĞÜ';//($this->getRequest()->get('text')==null)?$width."x".$height:$this->getRequest()->get('text');

		$bbox = imagettfbbox($size, 0, $font, $text);

		//$width = abs($bbox[2] - $bbox[0]);
		//$height = abs($bbox[7] - $bbox[1]);

		$image = imagecreatetruecolor($width, $height);

		$bgcolor = imagecolorallocate($image, 204, 204, 204);
		$color = imagecolorallocate($image, 150, 150, 150);

		$x = $bbox[0] + ($width / 2) - ($bbox[4] / 2);
		$y = $bbox[1] + ($height / 2) - ($bbox[5] / 2);

		imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $bgcolor);
		imagettftext($image, $size, 0, $x, $y, $color, $font, $text);

		$last_pixel = imagecolorat($image, 0, 0);

		for ($j = 0; $j < $height; $j++) {
			for ($i = 0; $i < $width; $i++) {
				if (isset($blank_left) && $i >= $blank_left) {
					break;
				}

				if (imagecolorat($image, $i, $j) !== $last_pixel) {
					if (!isset($blank_top)) {
						$blank_top = $j;
					}
					$blank_left = $i;
					break;
				}

				$last_pixel = imagecolorat($image, $i, $j);
			}
		}

		$x -= $blank_left;
		$y -= $blank_top;

		imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $bgcolor);
		imagettftext($image, $size, 0, $x, $y, $color, $font, $text);

		// Using imagepng() results in clearer text compared with imagejpeg()
		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
	}

}
