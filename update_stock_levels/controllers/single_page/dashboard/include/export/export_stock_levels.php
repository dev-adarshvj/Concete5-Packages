<?php defined('C5_EXECUTE') or die("Access Denied.");
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductList as StoreProductList;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductVariation\ProductVariation as StoreProductVariation;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOption;

		$dh = Core::make('helper/date');
        $products = new StoreProductList();
        $products->setItemsPerPage(1000);
        $products->setGroupID($gID);
        $products->setActiveOnly(false);
        $products->setShowOutOfStock(true);

        if ($this->get('ccm_order_by')) {
            $products->setSortBy($this->get('ccm_order_by'));
            $products->setSortByDirection($this->get('ccm_order_by_direction'));
        } else {
            $products->setSortBy('date');
            $products->setSortByDirection('desc');
        }

        if ($this->get('keywords')) { $products->setSearch($this->get('keywords')); }

        $productList = $products;
        $paginator = $products->getPagination();
        $pagination = $paginator->renderDefaultView();
       	$products = $paginator->getCurrentPageResults();
		if(count($products)>0) {
		$date = date('Ymd');
		$fileName = 'stock_levels.csv';
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$fileName}");
		header("Expires: 0");
		header("Pragma: public");
		$fh = @fopen( 'php://output', 'w' );
		$headerDisplayed = false;
		$i=1;
		foreach ( $products as $product ) {
			if ( !$headerDisplayed ) {
			$key = array(
				t('PART'),
                t('DESCRIPTN'),
				t('QTYIN')
            );
				fputcsv($fh,$key);
				$headerDisplayed = true;
			}
            $getSKU = $product->getSKU();
            $productName = $product->getName();
			$stockLevel = $product->getQty();
            $dataContent = array(
                $getSKU,
                $productName,
                $stockLevel
            );
            fputcsv($fh,$dataContent);
            if ($product) {
            if ($product->hasVariations()) {
                $variations = $product->getVariations();
                foreach ($variations as $variation) {
                    $getSKU = $variation->getVariationSKU();
                    $productName = $product->getName();
                    $stockLevel = $variation->getVariationQty();
                    $dataContent = array(
                        $getSKU,
                        $productName,
                        $stockLevel
                    );
                    fputcsv($fh,$dataContent);
                }
            }
        }
			$i++;
		}
		fclose($fh);
		exit;	
		die;
		}