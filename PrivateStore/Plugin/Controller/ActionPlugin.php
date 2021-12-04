<?php

declare(strict_types=1);

namespace Betagento\PrivateStore\Plugin\Controller;

use Closure;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Betagento\PrivateStore\Model\Config as PrivateStoreConfig;
use Magento\Framework\Controller\ResultFactory;
use Betagento\PrivateStore\Model\GetAllowedActions;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Controller\Result\Redirect as RedirectResult; 

class ActionPlugin {
    
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Request;
     */
    private $request;

    /**
     * @var GetAllowedActions
     */
    protected $getAllowedActions;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * We will use later, if someone want to inject a url model for it
     *
     * @var mixed
     */
    protected $redirectToAction;

    /**
     * @param Request $request
     * @param Session $customerSession
     * @param GetAllowedActions $getAllowedActions
     * @param ResultFactory $resultFactory
     * @param CustomerUrl $customerUrl
     * @param mixed $redirectToAction
     */
    public function __construct(
        Request $request,
        Session $customerSession,
        GetAllowedActions $getAllowedActions,
        ResultFactory $resultFactory,
        CustomerUrl $customerUrl,
        $redirectToAction = ''
       
    ) {
        $this->request = $request;
        $this->session = $customerSession;
        $this->getAllowedActions = $getAllowedActions;
        $this->redirectToAction = $redirectToAction;
        $this->resultFactory = $resultFactory;
        $this->customerUrl = $customerUrl;
    }

    /**
     * Executes original method if allowed, otherwise - redirects to log in
     *
     * @param \Magento\Customer\Controller\AccountInterface $controllerAction
     * @param Closure $proceed
     * @return ResultInterface|ResponseInterface|void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundExecute($controllerAction, Closure $proceed)
    {
        if(PrivateStoreConfig::isEnabled()){
            if ($this->isActionAllowed() || $this->session->authenticate()) {
                return $proceed();
            }
            //redirect to login page
            /**
             * @var RedirectResult $result
             */
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setUrl($this->customerUrl->getLoginUrl());
            return $result;
        }else{
            return $proceed();
        }
    }

    /**
     * Validates whether currently requested action is one of the allowed
     *
     * @return bool
     */
    public function isActionAllowed(): bool
    {   
        
        $action = strtolower($this->request->getFullActionName());
        $allowedActions = $this->getAllowedActions->execute();
        if(in_array($action, $allowedActions))
            return true;
        return false;
    }
}