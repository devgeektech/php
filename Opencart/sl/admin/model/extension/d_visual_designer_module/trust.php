<?php
class ModelExtensionDVisualDesignerModuleTrust extends Model
{

    public function getImages(){

        $dir = DIR_IMAGE.'catalog/d_visual_designer_landing/trust_icon/';
        $result = array();
        if(is_dir($dir)){
            $files = scandir($dir);
            foreach($files as $file){
                if(strlen($file) > 1 && (strpos( $file, '.svg') || strpos( $file, '.jpg') || strpos( $file, '.png'))){
                    $name = substr($file, 0, -4);
                    $result[$name] = 'catalog/d_visual_designer_landing/trust_icon/'.$file;
                }
            }
            
        }
        return $result;
    }
}
