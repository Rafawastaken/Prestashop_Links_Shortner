<?php

/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */



if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(__DIR__ . "/classes/R_LinkShortnerClass.php");

class R_LinkShortner extends Module
{
    public function __construct()
    {
        $this->name = 'r_linkshortner';
        $this->author = 'Rafawastaken';
        $this->version = '1.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->displayName = $this->trans('Link shortner', [], 'Modules.R_LinkShortner.Admin');
        $this->description = $this->trans('Link shortner for advertisement purposes', [], 'Modules.R_LinkShortner.Admin');
    }

    public function install()
    {
        return parent::install() && R_LinkShortnerClassModel::createTable();
    }

    public function uninstall()
    {
        return parent::uninstall() && R_LinkShortnerClassModel::deleteTable();
    }

    public function getContent()
    {
        $output = "";

        if (Tools::isSubmit('submitLink')) {
            $errors = $this->postValidation();

            if (!empty($errors)) {
                $output = $this->renderErrors($errors);
            } else {
                $output = $this->postProcess();
            }
        }

        // Assign data to the Smarty template
        $this->context->smarty->assign([
            'links' => R_LinkShortnerClassModel::getAllLinks(),
            'translations' => [
                'created_links' => $this->trans('Created Links', [], 'Modules.R_LinkShortner.Admin'),
                'id' => $this->trans('ID', [], 'Modules.R_LinkShortner.Admin'),
                'target' => $this->trans('Target', [], 'Modules.R_LinkShortner.Admin'),
                'campaign_id' => $this->trans('Campaign ID', [], 'Modules.R_LinkShortner.Admin'),
                'campaign_source' => $this->trans('Campaign Source', [], 'Modules.R_LinkShortner.Admin'),
                'campaign_medium' => $this->trans('Campaign Medium', [], 'Modules.R_LinkShortner.Admin'),
                'campaign_name' => $this->trans('Campaign Name', [], 'Modules.R_LinkShortner.Admin'),
                'randomId' => $this->trans('Random Id', [], 'Modules.R_LinkShortner.Admin'),
                'finalLink' => $this->trans('Final Link', [], 'Modules.R_LinkShortner.Admin'),
                'views' => $this->trans('Views', [], 'Modules.R_LinkShortner.Admin')
            ],
        ]);

        // Render the form and the links list template
        $output .= $this->renderForm();
        $output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/links_list.tpl');

        return $output;
    }

    protected function renderForm()
    {
        $t = $this->getTranslator();
        $translationLocation = "Modules.R_LinkShortner.Admin";

        $form = [
            'form' => [
                'legend' => [
                    'title' => $t->trans("Link Shortner", [], $translationLocation),
                    'icon' => 'icon-cogs'
                ],
                'input' => [
                    // Target
                    [
                        "type" => "text",
                        "label" => $t->trans("Link target", [], $translationLocation),
                        "name" => 'target',
                        "lang" => false,
                        "required" => true,
                        "placeholder" => "Main Link"
                    ],
                    // Campaign Id
                    [
                        "type" => "text",
                        "label" => $t->trans("campaignId", [], $translationLocation),
                        "name" => "campaignId",
                        "lang" => false,
                        "required" => false,
                        "placeholder" => "Value for campaignId tag"
                    ],
                    [
                        "type" => "text",
                        "label" => $t->trans("campaignSource", [], $translationLocation),
                        "name" => "campaignSource",
                        "lang" => false,
                        "required" => false,
                        "placeholder" => "Value for campaignSource tag"
                    ],
                    [
                        "type" => "text",
                        "label" => $t->trans("campaignMedium", [], $translationLocation),
                        "name" => "campaignMedium",
                        "lang" => false,
                        "required" => false,
                        "placeholder" => "Value for campaignMedium tag"
                    ],
                    [
                        "type" => "text",
                        "label" => $t->trans("campaignName", [], $translationLocation),
                        "name" => "campaignName",
                        "lang" => false,
                        "required" => false,
                        "placeholder" => "Value for campaignName tag"
                    ],
                ],
                "submit" => [
                    "title" => $t->trans("Save", [], $translationLocation)
                ]
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $ctx = Context::getContext();

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->table = $this->table;
        $helper->identifier = $this->identifier;

        $helper->show_toolbar = false;
        $helper->submit_action = "submitLink";
        $helper->override_folder = "/";

        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->currentIndex = $ctx->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name
            . '&tab_module=' . $this->tab
            . '&module_name=' . $this->name;

        $helper->tpl_vars = [
            'base_url' => $ctx->shop->getBaseURL(),
            'language' => [
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code
            ],
            // 'fields_value' => $this->getAddFieldsValues(),
            'languages' => $ctx->controller->getLanguages(),
            'id_language' => $ctx->language->id,
            'modulePath' => $this->_path,
        ];

        return $helper->generateForm([$form]);
    }



    protected function postProcess()
    {
        $t = $this->getTranslator();
        $ctx = Context::getContext();

        if (Tools::isSubmit("submitLink")) {
            $errors = [];
            $link = new R_LinkShortnerClassModel();

            $link->target = Tools::getValue("target");
            $link->campaignId = Tools::getValue("campaignId");
            $link->campaignSource = Tools::getValue("campaignSource");
            $link->campaignMedium = Tools::getValue("campaignMedium");
            $link->campaignName = Tools::getValue("campaignName");
            $link->randomId = $this->generateRandomString();
            $link->views = 0;
            $shopUrl =  $ctx->shop->getBaseURL();
            $link->finalLink = $shopUrl . "link/" . $link->randomId;

            if (empty($errors)) {
                if (!$link->add()) {
                    $errors[] = $this->displayError(
                        $t->trans("The link could not be added.", [], "Modules.R_LinkShortner.Admin")
                    );
                }
            }

            if (count($errors)) {
                return $this->displayError(implode("<br/>", $errors));
            } else {
                Tools::redirectAdmin(
                    $ctx->link->getAdminLink('AdminModules', true) .
                        '&conf=3&configure=' . $this->name .
                        '&tab_module=' . $this->tab .
                        '&module_name=' . $this->name
                );
            }
        }
    }

    protected function postValidation()
    {
        $t = $this->getTranslator();
        $errors = [];

        $target = Tools::getValue("target");
        if (Tools::strlen($target) > 255 || !Validate::isUrl($target)) {
            $errors[] = $t->trans("Target Links is not a valid URL", [], "Modules.R_LinkShortner.Admin");
        }

        return $errors;
    }

    protected function renderErrors($errors)
    {
        return (!empty($errors)) ? $this->displayError(implode("<br/>", $errors)) : '';
    }

    /**
     * Helper function to generate random string
     * @return string random string id to identify link
     */
    protected function generateRandomString()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
