<?php
namespace Betagento\PrivateStore\Model;

use Magento\Framework\App\ObjectManager ;

class Config {
    
    /**
     * @var mixed
     */
    public static $scopeConfig = NULL;

    /**
     * Get config value
     *
     * @param string $pathValue
     * @return mixed
     */
    static public function getConfigValue($pathValue){
        if(self::$scopeConfig == NULL)
            self::$scopeConfig = ObjectManager::getInstance()->get("Magento\Framework\App\Config\ScopeConfigInterface");
        return self::$scopeConfig->getValue($pathValue);   
    }
    
    /**
     * Check the functionality is enabled or not
     *
     * @return boolean
     */
    static public function isEnabled(){
         return self::getConfigValue(\Betagento\PrivateStore\Model\Constant::XML_PATH_ENABLE);
    }     
}