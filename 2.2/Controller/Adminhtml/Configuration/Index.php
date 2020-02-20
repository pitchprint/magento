<?php

    namespace PitchPrintInc\PitchPrint\Controller\Adminhtml\Configuration;

    class Index extends \Magento\Backend\App\Action
    {
        
        protected $resultPageFactory;
      
        
        public function __construct(
            \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory
        ) {
            parent::__construct($context);
            $this->resultPageFactory = $resultPageFactory;
        }

        public function execute()
        {
            $resultPage = $this->resultPageFactory->create();
            
            $dataOut = ['api_key' => '', 'secret_key' => ''];
            
            $dataIn = $this->ppGetCreds();
            
            if (isset($dataIn[0])) {
                $dataOut = $dataIn[0];
            }
            
            $resultPage->getLayout()->getBlock('pitch_print_conf')->setName($dataOut);
            return $resultPage;
        }
        
        private function ppGetCreds()
        {
            $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
            $resource       = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $db             = $resource->getConnection();
            $tableName      = $resource->getTableName('pitch_print_config');
            
            return $db->fetchAll("SELECT * FROM $tableName");
        }
    }
?>
