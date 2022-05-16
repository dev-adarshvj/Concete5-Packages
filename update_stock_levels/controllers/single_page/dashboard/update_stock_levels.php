<?php namespace Concrete\Package\UpdateStockLevels\Controller\SinglePage\Dashboard;

use \Concrete\Core\Page\Controller\DashboardPageController;

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductList as StoreProductList;

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductVariation\ProductVariation as StoreProductVariation;

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;

use \Concrete\Package\UpdateStockLevels\Src\Options;

//import
use Concrete\Core\File\File;

use Concrete\Core\User\User;

use PageType;

use GroupList;

use Events;

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOption as StoreProductOption;

use \Concrete\Package\CommunityStore\Src\Attribute\Key\StoreProductKey;

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductEvent as StoreProductEvent;

use Loader;

use PageList;

use PageTemplate;

use Page;

use Config;

use Core;

use \Concrete\Core\File\EditResponse as FileEditResponse;

use Concrete\Core\Support\Facade\Application;

use FilePermissions;

use FileImporter;

class UpdateStockLevels extends DashboardPageController
{

    public function on_start()
    {
    }

    public function view()
    {
    }

    public function replaceTO($data, $replace, $to)
    {
        return preg_replace("'.$replace.'", "$to", $data);
    }

    public function trimTO($data)
    {
        return trim(preg_replace('/\s\s+/', ' ', $data));
    }

    public function export_stock_levels()
    {
        include ('include/export/export_stock_levels.php');
    }

    function readCsv($csvFile)
    {

        $row = 1;

        $csvData = array();

        if (($handle = fopen($csvFile, "r")) !== false)
        {

            while (($data = fgetcsv($handle, 1000, ",")) !== false)
            {

                /* echo '<pre>';

                                               print_r($data);

                                                                echo '</pre>';

                                                                                echo $data[0];

                                                                                			 exit();

                */
                $num = count($data);

                $row++;

                array_push($csvData, $data);

            }

            fclose($handle);

        }

        return $csvData;

    }

    public function import_stocklist()
    {
        $docRoot = $_SERVER['DOCUMENT_ROOT'];
        $errors = $this->validate($check = $_REQUEST['csv']);
        $this->error = null; //clear errors
        $this->error = $errors;
        if (!$errors->has())
        {
            $valid_data = '';
            $f = File::getByID($_REQUEST['csv']);
            $csvFilePath = $f->getRelativePath();
            $service = \Core::make('helper/file');
            $extension = $service->getExtension($csvFilePath);
            $errors = $this->validate($check = $extension);
            $this->error = null; //clear errors
            $this->error = $errors;
            if (!$errors->has())
            {
                if (is_object($f))
                {
                    $csvData = $this->readCsv($docRoot . $csvFilePath);

                    $fields = $csvData[0];
                    unset($csvData[0]);
                    $storeTable = 'CommunityStoreProducts';
                    $variationTable = 'CommunityStoreProductVariations';
                    $db = Loader::db();
                    /*echo '<pre>';
                    print_r($csvData);
                    echo '</pre>';*/

                    foreach ($csvData as $key => $data)
                    {

                        $header_count = count($data);

                        if ($header_count == 3)
                        {

                            $pdata = array(
                                'pSKU' => $data[0],

                                'pvSKU' => $data[0],

                                'pName' => $data[1],

                                'pQty' => $data[2],

                                'pvQty' => $data[2]
                            );

                        }
                        else
                        {
                            $pdata = array(
                                'pSKU' => $data[0],

                                'pvSKU' => $data[0],

                                'pName' => $data[19],

                                'pQty' => $data[32],

                                'pvQty' => $data[32]
                            );
                        }
                        $pSKU_value = $pdata['pSKU'];
                        $pvSKU_value = $pdata['pvSKU'];
                        $pSKU = $db->GetOne("select pSKU from $storeTable where pSKU = ?", array(
                            $pSKU_value
                        ));
                        $pvSKU = $db->GetOne("select pvSKU from $variationTable  where pvSKU = ?", array(
                            $pvSKU_value
                        ));

                        if ($pdata['pSKU'])
                        {
                            $db->update($storeTable, array(
                                'pName' => $pdata['pName'],
                                'pQty' => $pdata['pQty']
                            ) , array(
                                'pSKU' => $pdata['pSKU']
                            ));
                            $db->update($variationTable, array(
                                'pvQty' => $pdata['pvQty']
                            ) , array(
                                'pvSKU' => $pdata['pvSKU']
                            ));
                        }

                        if ($pSKU == '')
                        {
                            $db->insert($storeTable, array(
                                'pName' => $pdata['pName'],
                                'pSKU' => $pdata['pSKU'],
                                'pQty' => $pdata['pQty']
                            ));
                        }

                    }

                    $this->redirect('/dashboard/update_stock_levels', 'imported');
                }
            }
        }
    }
    public function imported()
    {
        $this->set("success", t("Stock Levels Successfully Imported"));
        $this->view();
    }
    public function validate($check_data)
    {
        $e = Core::make('helper/validation/error');
        if (is_numeric($check_data) && $check_data == 0)
        {
            $e->add(t('Please choose the CSV file'));
        }
        elseif (!is_numeric($check_data) && trim($check_data) != 'csv')
        {
            $e->add(t("Please choose the CSV file"));
        }
        return $e;
    }
}
