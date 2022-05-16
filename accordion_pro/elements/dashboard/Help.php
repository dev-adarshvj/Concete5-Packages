<?php

/**
 * @project:   Accordion Pro
 *
 * @author     Fabian Bitter (fabianbitter@protonmail.com)
 * @copyright  (C) 2017 Bitter Webentwicklung
 * @version    1.0.0.5
 */
defined('C5_EXECUTE') or die('Access denied');

Core::make('help')->display(t("If you need support please click <a href=\"%s\">here</a>.", "https://bitbucket.org/fabianbitter/accordion_pro/issues/new"));
