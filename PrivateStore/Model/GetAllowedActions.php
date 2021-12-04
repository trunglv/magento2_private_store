<?php
namespace Betagento\PrivateStore\Model;

use Betagento\PrivateStore\Model\Config as PrivateStoreConfig;
use Betagento\PrivateStore\Model\Constant;

class GetAllowedActions {

    /**
     * @var array<int,string>
     */
    protected $_defaultAllowActions = [
        'customer_account_login', 
        'customer_account_loginpost',
        'customer_account_create',
        'customer_account_createpost',
        'cms_noroute_index',
        'customer_section_load',
        'customer_account_createpassword',
        'customer_account_resetpasswordpost'
    ]; 
    /**
     * @var boolean|array<int,string>
     */
    protected $_allowedActions = false;

    /**
     * Some allowed actions will be injected from DI Configuration by developers
     *
     * @var array<string,string>
     */
    protected $allowedActionsFromDiConfig;

    /**
     * @param array<string,string> $allowedActionsFromDiConfig
     */
    public function __construct(
        array $allowedActionsFromDiConfig = []
    )
    {
        $this->allowedActionsFromDiConfig = $allowedActionsFromDiConfig;
    }
    

    /**
     * Get all allowed actions
     *
     * @return array<int,string>
     */
    public function execute(){
        if($this->_allowedActions != false)
            return $this->_allowedActions;

        $allowedActions = $this->_defaultAllowActions;
        foreach($this->allowedActionsFromDiConfig as $diConfigAction){
            $allowedActions[] = $diConfigAction;
        }
        if(PrivateStoreConfig::getConfigValue(Constant::XML_PATH_IS_ALLOWED_PRODUCT_PAGE)){
            $allowedActions[] = 'catalog_product_view';
        }
        if(PrivateStoreConfig::getConfigValue(Constant::XML_PATH_IS_ALLOWED_CATEGORY_PAGE)){
            $allowedActions[] = 'catalog_category_view';
        }
        if(PrivateStoreConfig::getConfigValue(Constant::XML_PATH_IS_ALLOWED_CMS_PAGE)){
            $allowedActions[] = 'cms_index_index';
        }
        if(PrivateStoreConfig::getConfigValue(Constant::XML_PATH_IS_ALLOWED_CMS_PAGE)){
            $allowedActions[] = 'cms_index_index';
        }
        return $this->_allowedActions = $allowedActions;
    }
}