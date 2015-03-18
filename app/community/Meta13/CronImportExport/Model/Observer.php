<?php

class Meta13_CronImportExport_Model_Observer { 

    const EXPORT_FILE_LOCATION = 'var/export/products.csv';
    const IMPORT_FILE_LOCATION = 'var/import/products.csv';

    public function ExportProducts()
    {
        $userModel = Mage::getModel('admin/user');
        $userModel->setUserId(0);
        Mage::getSingleton('admin/session')->setUser($userModel);

        echo "Started File Export at " . date('Y-m-d H:i:s') . " ... ";

        $exportFile = Mage::getBaseDir() . '/' . self::EXPORT_FILE_LOCATION;
        $fHandle = fopen($exportFile, 'w+');

        if (!$fHandle)
            throw new Exception("Could not open {$exportFile} for writing.");

        $model = Mage::getModel('importexport/export');
        $model->setData($this->getExportData());
        fwrite($fHandle, $model->export());
        fclose($fHandle);

        echo "Finished File Import at " . date('Y-m-d H:i:s');
    }

    public function ImportProducts()
    {
        $userModel = Mage::getModel('admin/user');
        $userModel->setUserId(0);
        Mage::getSingleton('admin/session')->setUser($userModel);

        echo "Started File Import at " . date('Y-m-d H:i:s') . "\n";

        $importFile = Mage::getBaseDir() . '/' . self::IMPORT_FILE_LOCATION;
        $fHandle = fopen($importFile, 'r');

        if (!$fHandle)
            throw new Exception("Could not open {$importFile} for reading.");

        $import = Mage::getModel('importexport/import');
        $import->setData($this->getImportData());
        $validationResult = $import->validateSource($importFile);

        if (!$import->getProcessedRowsCount())
            throw new Exception("File does not have any data in it.");

        if (!$validationResult) {
            $error = '';
            // errors info
            foreach ($import->getErrors() as $errorCode => $rows) {
                $error .= $errorCode . ' in rows: ' . implode(', ', $rows) . "\n";
                $resultBlock->addError($error);
            }

            throw new Exception($error);
        } else {
            echo 'File is valid, processing file.' . "\n";

            $import->importSource();
            $import->invalidateIndex();
        }

        echo "Finished File Import at " . date('Y-m-d H:i:s') . "\n";
    }

    private function getExportData()
    {
        return array(
            //'key' => '711eaecc5c353f6a3a7075e1881da3fc',
            'entity' => 'catalog_product',
            'file_format' => 'csv',
            //'form_key' => 'QZB7zhYmTDI6lzkH',
            'frontend_label' => null,
            'attribute_code' => null,
            'export_filter' => array(
                'color' => '',
                'cost' => Array(null, null),
                'country_of_manufacture' => null,
                'created_at' => array(null, null),
                'custom_design' => null,
                'custom_design_from' => array(null, null),
                'custom_design_to' => array(null, null),
                'custom_layout_update' => null,
                'description' => null,
                'gallery' => null,
                'gift_message_available' => null,
                'groupscatalog2_groups' => null,
                'has_options' => null,
                'image' => null,
                'image_label' => null,
                'label_made_in_usa' => null,
                'label_on_sale' => null,
                'length' => null,
                'media_gallery' => null,
                'meta_description' => null,
                'meta_keyword' => null,
                'meta_title' => null,
                'minimal_price' => array(null, null),
                'msrp' => array(null, null),
                'msrp_display_actual_price_type' => null,
                'msrp_enabled' => null,
                'name' => null,
                'news_from_date' => array(null, null),
                'news_to_date' => array(null, null),
                'options_container' => null,
                'page_layout' => null,
                'price' => array(null, null),
                'required_options' => null,
                'shirt_size' => null,
                'short_description' => null,
                'size' => null,
                'sku' => null,
                'small_image' => null,
                'small_image_label' => null,
                'special_from_date' => array(null, null),
                'special_price' => array(null, null),
                'special_to_date' => array(null, null),
                'status' => null,
                'tax_class_id' => null,
                'thumbnail' => null,
                'thumbnail_label' => null,
                'updated_at' => array(null, null),
                'url_key' => null,
                'url_path' => null,
                'visibility' => null,
                'waist' => null,
                'weight' => array(null, null)
            )
        );
    }

    private function getImportData()
    {
        return array(
            'entity' => 'catalog_product',
            'behavior' => 'replace'
        );
    }

}