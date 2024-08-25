<?php

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

            if (!empty($erros)) {
                $output = $this->renderErrors($erros);
            } else {
                $output = $this->postProcess();
            }
        }

        return $output . $this->renderForm();
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


    protected function postProcess() {}


    protected function postValidation()
    {
        $errors = array();
        return $erros;
    }

    protected function renderErrors($errors)
    {
        return (!empty($erros)) ? $this->displayError(implode("<br/>", $erros)) : '';
    }
}
