<?php
/**
   author: seek@youku.com
   last modified: 2007-03-21
   Usage:
	Image::convert("src.jpg","dest.jpg",300,400);
 */
use PHPImageWorkshop\ImageWorkshop;

class Image {
	const SAVE_QUALITY = 100;

	public static function convert($srcFile=null,$destFile=null,$width=null,$height=null){
		try {
			ImageWorkshopLoader::init();
			$layer = ImageWorkshop::initFromPath($srcFile);
			if(($width == $layer->getWidth() && $height == $layer->getHeight()) or (is_null($width) && is_null($height))) {
	            /** force image type from extension **/
	            $imageSizeInfos = @getImageSize($srcFile);
	            $mimeContentType = explode('/', $imageSizeInfos['mime']);
	            $mimeContentType = $mimeContentType[1];
	            if($mimeContentType == 'jpeg')
	            	$mimeContentType ='jpg';
                $extension = explode('.', $srcFile);
                $extension = strtolower($extension[count($extension) - 1]);
	            if($extension == 'jpeg')
	            	$extension ='jpg';
                if($extension != $mimeContentType)
					$layer->save(dirname($destFile), basename($destFile), false, null, self::SAVE_QUALITY);
				/* end image type */
				if($srcFile == $destFile)
					return true;
				return copy($destFile, $destFile);
			}
			$layer->resizeInPixel($width, $height, true, 0, 0, 'MM');
			$layer->save(dirname($destFile), basename($destFile), false, null, self::SAVE_QUALITY);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}

	public static function toBase64($srcFile=null,$width=null,$height=null){
		try {
			ImageWorkshopLoader::init();
			$layer = ImageWorkshop::initFromPath($srcFile);
			if(($width != $layer->getWidth() || $height != $layer->getHeight()) && (!is_null($width) || !is_null($height))) {
				$dest_height = $layer->getWidth() * floatval($height / $width);
				$posX = ($layer->getHeight() - $dest_height) / 2;
				$layer->cropInPixel($layer->getWidth(), $dest_height, 0, (int)$posX);
				$layer->resizeInPixel($width, $height, false, 0, 0, 'MM');
			}
			ob_start();
			imagepng($layer->getResult());
			$stringdata = ob_get_contents(); // read from buffer
			ob_end_clean(); // delete buffer
			return base64_encode($stringdata);
		} catch(Exception $e) {
			return null;
		}
	}
}
