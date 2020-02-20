<?php

namespace PitchPrintInc\PitchPrint\Controller\Adminhtml\Configuration;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $keysModel;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest()->getParams();

        try{
            $this->saveCredentials($request);
            $this->resultPageFactory->create();
            $this->messageManager->addSuccessMessage(__('PitchPrint Credentials Updated.'));
            return $resultRedirect->setPath('pitchprint/configuration/index');
        }catch (\Exception $e){
            print_r($e);die();
            $this->messageManager->addExceptionMessage($e, __('We can\'t submit your request, Please try again.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            return $resultRedirect->setPath('pitchprint/configuration/index');
        }
    }
    private function saveCredentials($request)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $resource       = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $db             = $resource->getConnection();

        $api_key    = $db->quote( trim($request['api_key'], ' ') );
        $secret_key = $db->quote( trim($request['secret_key'], ' ') );
      
        $tableName      = $resource->getTableName(\PitchPrintInc\PitchPrint\Config\Constants::TABLE_CONFIG);
        $data           = $db->query("REPLACE INTO $tableName VALUES (1, $api_key, $secret_key)");
    }

}
?>