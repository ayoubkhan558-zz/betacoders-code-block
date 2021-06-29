<?php
if (!defined('ABSPATH')) exit;

class AYOUB_BCCB_Activation
{

  private function __construct()
  {
  }

  /**
   * Function for the plugin activated.
   */
  public static function plugin_activate()
  {

    if (get_option(AYOUB_BCCB::DB_NAME['installed']) === false) {

      update_option(AYOUB_BCCB::DB_NAME['installed'], 1);

      //初回・再インストール時 -> デフォルト設定
      $settings = AYOUB_BCCB::DEFAULT_SETTINGS;
    } else {

      //データが残っている場合 -> 既存設定
      $settings = get_option(AYOUB_BCCB::DB_NAME['settings']);
    }

    update_option(AYOUB_BCCB::DB_NAME['settings'], $settings);
  }

  /**
   * Function for the plugin uninstalled.
   */
  public static function plugin_uninstall()
  {
    foreach (AYOUB_BCCB::DB_NAME as $db_name) {
      delete_option($db_name);
    }
  }
}
