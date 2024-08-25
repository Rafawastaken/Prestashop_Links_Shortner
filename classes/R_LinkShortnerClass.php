<?php

class R_LinkShortnerClassModel extends ObjectModel
{
    public $target;
    public $campaignId;
    public $campaignSource;
    public $campaignMedium;
    public $campaignName;

    public static $definition = [
        'table' => 'r_linkshortner',
        'primary' => 'id',
        'multilang' => false,
        'fields' => [
            'target' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isUrl',
                'required' => true,
                'size' => 255,
            ],
            'campaignId' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml',
                'required' => false,
                'size' => 255,
            ],
            'campaignSource' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml',
                'required' => false,
                'size' => 255,
            ],
            'campaignMedium' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml',
                'required' => false,
                'size' => 255,
            ],
            'campaignName' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml',
                'required' => false,
                'size' => 255,
            ],
        ],
    ];

    /**
     * Create table
     * @return bool true if created, false if not
     */
    public static function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "r_linkshortner` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `target` varchar(255) NOT NULL,
            `campaignId` varchar(255),
            `campaignSource` varchar(255),
            `campaignMedium` varchar(255),
            `campaignName` varchar(255),
            PRIMARY KEY (`id`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql);
    }

    /**
     * Delete database table
     * @return bool true if deleted false if not
     */
    public static function deleteTable()
    {
        return Db::getInstance()->execute("DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "r_linkshortner`");
    }

    // /**
    //  * Query data
    //  * @return mixed false if no data, array with data
    //  */
    // public static function getData()
    // {
    //     return Db::getInstance()->executeS("SELECT * FROM `" . _DB_PREFIX_ . "r_linkshortner`");
    // }

    // /**
    //  * Delete entry
    //  * @return bool true if deleted false if not
    //  */
    // public static function deleteEntry($id)
    // {
    //     return Db::getInstance()->execute("DELETE FROM `" . _DB_PREFIX_ . "r_linkshortner` WHERE id = $id");
    // }

}