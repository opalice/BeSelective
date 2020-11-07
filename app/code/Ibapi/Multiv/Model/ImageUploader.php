<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Model;

use Magento\Framework\File\UploaderFactory;
use Magento\MediaStorage\Model\File\Uploader;
use function Magento\Framework\Image\Adapter\getimagesize;
use Magento\Catalog\Helper\Image;
/**
 * Catalog image uploader
 */
class ImageUploader
{
    /**
     * Core file storage database
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $coreFileStorageDatabase;

    
    protected $filesystem;
    /**
     * Media directory object (writable).
     *
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * Uploader factory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Base tmp path
     *
     * @var string
     */
    protected $baseTmpPath;

    /**
     * Base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * Allowed extensions
     *
     * @var string
     */
    protected $allowedExtensions;

    
    protected $helper;
    
    protected  $dirlist;
    
    public function __construct(
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Ibapi\Multiv\Helper\Data $helper,
         \Magento\Framework\App\Filesystem\DirectoryList $dirlist
///        \Psr\Log\LoggerInterface $logger
//        $baseTmpPath,
  //      $basePath,
    //    $allowedExtensions
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $helper->getStoreManager();
        $this->helper=$helper;
//        $this->logger = $logger;
        $this->baseTmpPath = $helper->getTmpFolder();
        $this->basePath = $helper->getImgFolder();
        $this->dirlist=$dirlist;
        
        $this->filesystem=$filesystem;
        if(!$this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->isExist($this->basePath)){
            $this->mediaDirectory->create($this->basePath);
        }
        
        $this->allowedExtensions = ['jpg','png','jpeg','JPG'];
    }

    /**
     * Set base tmp path
     *
     * @param string $baseTmpPath
     *
     * @return void
     */
    public function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    public function isNewFile($url){
try{
    	if(strstr($url, $this->baseTmpPath)){
                    return true;
    	    /*'
            $u=parse_url($url);

    		$name= pathinfo($u['path'],PATHINFO_FILENAME);
    		$ext= pathinfo($u['path'],PATHINFO_EXTENSION);
    		return [$name,$ext];
    		*/

    	}
}catch(\Exception $e){

}
    	return false;

    }

    /**
     * Set base path
     *
     * @param string $basePath
     *
     * @return void
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Set allowed extensions
     *
     * @param string[] $allowedExtensions
     *
     * @return void
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Retrieve base tmp path
     *
     * @return string
     */
    public function getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    /**
     * Retrieve base path
     *
     * @return string
     */
    public function getBasePath()
    {
        
        return $this->basePath;
    }

    /**
     * Retrieve base path
     *
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     *
     * @return string[]
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveFileToDir($fileId,$name='',$type='')
    {
    	$baseTmpPath = $this->getBaseTmpPath();



    	$uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
    	$uploader->setAllowedExtensions($this->getAllowedExtensions());

    	$ext=$uploader->getFileExtension();

    	$uploader->setAllowRenameFiles(false);
    	$filename=($name?$name:uniqid()).".".$ext;


    	if(file_exists($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename)){
    		try{

    			$this->coreFileStorageDatabase->deleteFile($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename);

    			$this->mediaDirectory->delete($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename);

    		}catch(\Exception $e){

    		}
    	}

    	$result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath),$filename);



    	if (!$result||!isset($result['file'])) {
    		throw new \Magento\Framework\Exception\LocalizedException(
    				__('File can not be saved to the destination folder.')
    		);
    	}

    	/**
    	 * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
    	 */
    	$result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
    	$result['path'] = str_replace('\\', '/', $result['path']);

    	$im=new \Imagick($result['path'].'/'. $result['file']);



    	$result['size']=$im->getimagewidth();

    	if($result['size']>1000){
    		if($type=='main'){
    			$type='hd';
    		}
    		else if($type=='back'){
    			$type='backhd';
    		}
    		else if($type=='m' || $type=='mb'){
    			
    		}

    		else{


    			throw new \Magento\Framework\Exception\LocalizedException(
    					__('Too big.')
    			);

    		}



    	}else{


    	}



    	$result['url'] = $this->storeManager
    	->getDefaultStoreView()
    	->getBaseUrl(
    			\Magento\Framework\UrlInterface::URL_TYPE_MEDIA
    	) . $this->getFilePath($baseTmpPath, $result['file']);
    	$result['name'] = $result['file'];

    	if (isset($result['file'])) {
    		try {
    			$relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
    			$this->coreFileStorageDatabase->saveFile($relativePath);
    		} catch (\Exception $e) {
    			$this->logger->critical($e);
    			throw new \Magento\Framework\Exception\LocalizedException(
    					__('Something went wrong while saving the file(s).')
    			);
    		}
    	}


    	return $this->moveFileFromTmp($result['file'], $type);


    }

    /**
     * Checking file for moving and move it
     *
     * @param string $imageName
     *
     * @return string
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function moveFileFromTmp($imageName,$type='')
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath($type);

        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (\Exception $e) {

          throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while saving the file(s).'.$e->getMessage())

           );
        }

        $url = $this->storeManager
        ->getDefaultStoreView()
        ->getBaseUrl(
        		\Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . $baseImagePath;

        return [$url,$baseImagePath];
    }
    public function getImg($nm,$type='review'){
        
        if($type=='review'){
            $basePath=$this->helper->getReviewFolder();
        }
        $baseImagePath = $this->getFilePath($basePath, $nm);
        if( $this->mediaDirectory->isExist($baseImagePath)){
         $imurl=   $this->storeManager
            ->getDefaultStoreView()
            ->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($basePath, $nm);
            
            return $imurl;
        }
        return '';
    }
    public function delUrl($name,$type=''){
    	$name=pathinfo($name,PATHINFO_BASENAME);

    	$baseTmpPath = $this->getBaseTmpPath();
    	$basePath = $type=='new'?$baseTmpPath:$this->getBasePath();
        
    	if($type=='review'){
    	    $basePath=$this->helper->getReviewFolder();
    	}

    	$baseImagePath = $this->getFilePath($basePath, $name);
    	if($this->mediaDirectory->isExist($baseImagePath)){
    	    $this->mediaDirectory->delete($baseImagePath);
    	$this->coreFileStorageDatabase->deleteFile($baseImagePath);
    	return $baseImagePath;
    	}else{
    	    return false;
    	}

    }
    public function getTPath($name){
        $ext=pathinfo($name,PATHINFO_EXTENSION);
        $baseImagePath = $this->getFilePath($this->getBaseTmpPath(), $name);
        return $baseImagePath;
    }
    

    public function copyFileR($name,$id){
        $uid= uniqid();
        
        $ext=pathinfo($name,PATHINFO_EXTENSION);

        if($ext=='JPG'|| $ext=='JPEG'||$ext=='jpeg'){
            $ext='jpg';
        }
        $ton='rev'.$id.'.'.$ext;
        
        
        $basePath =$this->helper->getReviewFolder();
        
        
        $baseImagePath = $this->getFilePath($this->getBaseTmpPath(), $name);
        
        $dest=$this->getFilePath($basePath, $ton);
        
        $this->mediaDirectory->copyFile($baseImagePath, $dest);
        $this->coreFileStorageDatabase->copyFile($baseImagePath, $dest);
        return $dest;
        
    }
    public function copyFile2($name){
        $uid= uniqid();
        
    	$ext=pathinfo($name,PATHINFO_EXTENSION);

    	$ton=$uid.'.'.$ext;


    	$basePath =$this->getBasePath();


    	$baseImagePath = $this->getFilePath($this->getBaseTmpPath(), $name);
    	$dest=$this->getFilePath($basePath, $ton);

    	$this->mediaDirectory->copyFile($baseImagePath, $dest);
    	$this->coreFileStorageDatabase->copyFile($baseImagePath, $dest);
    	return $dest;

    }
    public function getFullPath($p){
       return $this->mediaDirectory->getAbsolutePath($p);
    }



    public function delFile2($name,$type=''){


    	$basePath =$this->getBasePath($type);


    	$baseImagePath = $this->getFilePath($basePath, $name);
    	if(!is_file($baseImagePath)) return;

    	$this->mediaDirectory->delete($baseImagePath);
    	$this->coreFileStorageDatabase->deleteFile($baseImagePath);

    }

    public function delFile($name,$type=''){

    	$baseTmpPath = $this->getBaseTmpPath();
    	$basePath = $type=='new'?$baseTmpPath:$this->getBasePath();


    	$baseImagePath = $this->getFilePath($basePath, $name);

    	    	if(!is_file($baseImagePath)) return;

    	$this->mediaDirectory->delete($baseImagePath);
    	$this->coreFileStorageDatabase->deleteFile($baseImagePath);


    }

    public function saveFileToTmpDir($fileId,$name='')
    {
    	$baseTmpPath = $this->getBaseTmpPath();

    	$uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
    	$uploader->setAllowedExtensions($this->getAllowedExtensions());
    	$ext=$uploader->getFileExtension();

    	

    	$uploader->setAllowRenameFiles(false);
    	$filename=($name?$name:uniqid()).".".$ext;


    	if(file_exists($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename)){
    		try{

    			$this->coreFileStorageDatabase->deleteFile($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename);

    			$this->mediaDirectory->delete($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename);

    		}catch(\Exception $e){

    		}
    	}

    	$result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath),$filename);

    	if (!$result) {
    		throw new \Magento\Framework\Exception\LocalizedException(
    				__('File can not be saved to the destination folder.')
    		);
    	}

    	/**
    	 * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
    	 */
    	$result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
    	$result['path'] = str_replace('\\', '/', $result['path']);




    	$result['url'] = $this->storeManager
    	->getDefaultStoreView()
    	->getBaseUrl(
    			\Magento\Framework\UrlInterface::URL_TYPE_MEDIA
    	) . $this->getFilePath($baseTmpPath, $result['file']);
    	$result['name'] = $result['file'];

    	if (isset($result['file'])) {
    		try {
    			$relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
    			$this->coreFileStorageDatabase->saveFile($relativePath);
    		} catch (\Exception $e) {
    			$this->logger->critical($e);
    			throw new \Magento\Framework\Exception\LocalizedException(
    					__('Something went wrong while saving the file(s).')
    			);
    		}
    	}

    	return $result;
    }


    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     *
     * @return string[]
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function copyFileToTmpDir($file,$name='')
    {
        $baseTmpPath = $this->getBaseTmpPath();

        $ext=pathinfo($file,PATHINFO_EXTENSION);

        $filename=($name?$name:uniqid()).".".$ext;

        $dest=$this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename;


        if(file_exists($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename)){
        	try{

        	$this->coreFileStorageDatabase->deleteFile($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename);

        	$this->mediaDirectory->delete($this->mediaDirectory->getAbsolutePath($baseTmpPath).'/'.$filename);

        	}catch(\Exception $e){

        	}
        }

        $this->mediaDirectory->copyFile($file, $dest);
        $this->coreFileStorageDatabase->copyFile($file, $dest);






///        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath),$filename);


        return $name;
    }
}
