<?php

class FileUploader{
	const MAX_FILE_SIZE_BYTE = 10000000;
	const MAX_FILE_SIZE_MB = 10;
	
	public function uploadPolicy($offer_id, $policy){
		return $this->uploadFile($policy, __DIR__.'/policy/'.$offer_id.'_policy.pdf');
	}
	
	public function uploadMakbuz($offer_id, $makbuz){
		return $this->uploadFile($makbuz, __DIR__.'/makbuz/'.$offer_id.'_makbuz.pdf');
	}
	
	public function uploadImage($imageName, $image){
		return $this->uploadFile($image, __DIR__.'/report_images/'.$imageName);
	}
	
	public function uploadChatFile($filename, $file){
		return $this->uploadFile($file, __DIR__.'/chat/'.$filename);
	}
	
	private function uploadFile($file, $filepath){
		if(file_exists($filepath)){
			unlink($filepath);//Remove if exist
		}
		if(move_uploaded_file($file['tmp_name'], $filepath)){
			return $filepath;
		}else{
			return null;
		}
	}
}

?>