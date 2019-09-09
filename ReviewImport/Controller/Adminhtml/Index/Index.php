<?php

namespace Magenest\ReviewImport\Controller\Adminhtml\Index;

use Magento\Backend\App\Response\Http\FileFactory;
use Magento\Framework\Filesystem\DirectoryList;

class Index extends \Magento\Backend\App\Action {

        protected $resultPageFactory;

        protected $downloader;

        protected $csvProcessor;

        protected $directory;

        protected $directoryList;


        public function __construct(
            \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
            FileFactory $fileFactory,
            \Magento\Framework\File\Csv $csvProcessor,
            DirectoryList $directory
        ) {
            parent::__construct($context);
            $this->resultPageFactory = $resultPageFactory;
            $this->csvProcessor = $csvProcessor;
            $this->directoryList = $directoryList;
            $this->downloader = $fileFactory;
            $this->directory = $directory;
        }

        public function execute()
        {

            if( isset($this->getRequest()->getParams()['download_sample']) ){
                $heading = array(
                    'ID',
                    'PRODUCT',
                    'SKU',
                    'EMAIL',
                    'NICKNAME',
                    'RATING',
                    'TITLE',
                    'DETAIL',
                    'DATE',
                    'STATUS'
                );

                $filename = 'review_importer_sample.csv';
                $this->downloadCsv( $filename );
            }

            return  $resultPage = $this->resultPageFactory->create();
        }

        public function downloadCsv( $filename ){
            if (file_exists($filename)) {
                $filePath = $this->directory->getPath("pub") . DIRECTORY_SEPARATOR . $filename;

                return $this->downloader->create($filename, @file_get_contents($filePath));
            }
        }

        public function getSampleData(){
            $data = array(
                array(
                    '1',
                    '13',
                    '24-WB204',
                    'hasemail@mail.com',
                    'Emily',
                    '2',
                    'Not Good Enough!',
                    'Missing something',
                    '08/13/2016',
                    '1'
                ),
                array(
                    '2',
                    '',
                    '24-WB204',
                    'roni_cost@example.com',
                    'Roni',
                    '5',
                    'Amazing!',
                    'Excellent product',
                    '12/13/2017',
                    '2'
                ),
                array(
                    '3',
                    '243',
                    '24-WB204',
                    '',
                    'Jamie',
                    '3',
                    'Almost!',
                    'Would have given it 5 stars if not for the damage',
                    '12/25/2017',
                    '3'
                ),
            );
            return $data;
        }
}