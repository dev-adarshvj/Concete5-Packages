<?php
namespace Concrete\Package\StudioTestimonialsPro;
use Package;
use BlockType;
use SinglePage;
use Loader;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'studio_testimonials_pro';
	protected $appVersionRequired = '5.7.0.4';
	protected $pkgVersion = '0.9.9';

	public function getPackageDescription() {
		return t("Manage and display testmonials.");
	}

	public function getPackageName() {
		return t("Studio Testimonials Pro");
	}

	public function install() {
		$pkg = parent::install();

		$manage_page = SinglePage::add('/dashboard/studio_testimonials_pro', $pkg);

		// install block
		BlockType::installBlockTypeFromPackage('studio_testimonials_pro', $pkg);
	}

}
