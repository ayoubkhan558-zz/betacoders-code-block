<?php
if (!defined('ABSPATH')) exit;

class AYOUB_BCCB_Scripts
{

  /**
   * The constructor
   */
  public function __construct()
  {
    add_action('init', ['AYOUB_BCCB_Scripts', 'hook_init']);
    add_action('wp_enqueue_scripts', ['AYOUB_BCCB_Scripts', 'hook_wp_enqueue_scripts'], 20);
    add_action('admin_enqueue_scripts', ['AYOUB_BCCB_Scripts', 'hook_admin_enqueue_scripts']);
    add_action('enqueue_block_editor_assets', ['AYOUB_BCCB_Scripts', 'hook_enqueue_block_editor_assets']);
    add_action('admin_head', ['AYOUB_BCCB_Scripts', 'hook_admin_head'], 1);
  }

  /**
   * Register Block
   */
  public static function hook_init()
  {

    //Block script registration
    $asset = include(AYOUB_BCCB_PATH . 'build/js/code-block/index.asset.php');
    wp_register_script(
      'hcb-code-block',
      AYOUB_BCCB_URL . 'build/js/code-block/index.js',
      $asset['dependencies'],
      $asset['version'],
      true
    );

    // Block registration
    $metadata = json_decode(file_get_contents(AYOUB_BCCB_PATH . 'src/js/code-block/block.json'), true);
    $metadata = array_merge($metadata, ['editor_script' => 'hcb-code-block']);
    register_block_type('ayoub-bccb/code-block', $metadata);
  }


  /**
   * Front Scripts
   */
  public static function hook_wp_enqueue_scripts()
  {

    // if (!is_page()) {
    //   echo "this is snippets page";
    // }
    // if (is_post_type_archive('snippet') || is_post_type_archive('post') || is_post_type_archive('cheatsheets')) {
    //   echo "<h1>this is snippets snippets page</h1>";
    // }
    if (is_singular()) {
      // echo "<h1>is_singular snippets</h1>";
    }
    if (is_singular('snippet')) {
      // echo "<h1>is_singular snippets</h1>";
    }

    // is_single post
    if (is_single()) {

      // if (is_post_type_archive()) {
      //   echo "<h1>this is is_post_type_archive page</h1>";
      // }
      // HCB style
      wp_enqueue_style('hcb-style', AYOUB_BCCB_URL . 'build/css/hcb_style.css', [], AYOUB_BCCB_VERSION);

      // Coloring style
      wp_enqueue_style('hcb-coloring', AYOUB_BCCB::$coloring_css_url, ['hcb-style'], AYOUB_BCCB_VERSION);
      // echo "<h1>is_single snippets</h1>";
      // Inline Style
      wp_add_inline_style('hcb-style', AYOUB_BCCB_Scripts::get_inline_style());

      // clipboard.js
      $is_show_copy = AYOUB_BCCB::$settings['show_copy'];
      if ($is_show_copy) {
        wp_enqueue_script('clipboard');
      }

      // Prism.js
      wp_enqueue_script('hcb-prism', AYOUB_BCCB::$prism_js_url, [], AYOUB_BCCB_VERSION, true);
      // wp_add_inline_script( 'hcb-prism', 'window.Prism = window.Prism || {}; Prism.manual = true;', 'before' );

      // HCB script
      wp_enqueue_script('hcb-script', AYOUB_BCCB_URL . 'build/js/hcb_script.js', ['hcb-prism'], AYOUB_BCCB_VERSION, true);

      // Global variables to pass to the script
      wp_localize_script('hcb-script', 'hcbVars', [
        'showCopy' => $is_show_copy,
      ]);
    }
  }


  /**
   * Admin Scripts
   */
  public static function hook_admin_enqueue_scripts($hook_suffix)
  {
    if ('settings_page_hcb_settings' === $hook_suffix) {
      wp_enqueue_style('hcb-admin', AYOUB_BCCB_URL . 'build/css/hcb_admin.css', [], AYOUB_BCCB_VERSION);
    }
  }


  /**
   * Block Scripts
   */
  public static function hook_enqueue_block_editor_assets()
  {

    // Editor Style
    wp_enqueue_style(
      'hcb-editor-style',
      AYOUB_BCCB_URL . 'build/css/hcb_editor.css',
      [],
      AYOUB_BCCB_VERSION
    );

    // Editor Coloring
    wp_enqueue_style(
      'hcb-gutenberg-style',
      AYOUB_BCCB::$editor_coloring_css_url,
      ['hcb-editor-style'],
      AYOUB_BCCB_VERSION
    );

    // Inline Style
    // wp_add_inline_style( 'hcb-gutenberg-style', AYOUB_BCCB_Scripts::get_inline_style( 'block' ) );

    // 翻訳登録用の空ファイル
    wp_enqueue_script(
      'hcb-blocks',
      AYOUB_BCCB_URL . 'assets/js/hcb.js',
      [],
      AYOUB_BCCB_VERSION,
      false
    );

    // 翻訳ファイルの読み込み
    wp_set_script_translations(
      'hcb-blocks',
      'ayoub-bccb',
      AYOUB_BCCB_PATH . 'languages'
    );

    // 管理画面側に渡すグローバル変数
    wp_localize_script('hcb-blocks', 'hcbVars', [
      'showLang'    => AYOUB_BCCB::$settings['show_lang'],
      'showLinenum' => AYOUB_BCCB::$settings['show_linenum'],
    ]);
  }


  /**
   * Inline style generation
   */
  public static function get_inline_style()
  {

    $inline_css = '';
    $hcb = AYOUB_BCCB::$settings;

    // Font size
    $inline_css .= '.bccb_wrap pre.prism{font-size: ' . $hcb['fontsize_pc'] . '}' .
      '@media screen and (max-width: 599px){.bccb_wrap pre.prism{font-size: ' . $hcb['fontsize_sp'] . '}}';
    // Code Lang
    if ('off' === $hcb['show_lang']) {
      $inline_css .= '.bccb_wrap pre:not([data-file]):not([data-show-lang])::before{ content: none;}';
    }
    // Font smoothing
    if ('on' === $hcb['font_smoothing']) {
      $inline_css .= '.bccb_wrap pre{-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;}';
    }
    // Font family
    if ($hcb['font_family']) {
      $inline_css .= '.bccb_wrap pre{font-family:' . $hcb['font_family'] . '}';
    }

    return $inline_css;
  }


  /**
   * Add code to Admin Head.
   * Since it is also necessary for TinyMCE, it is hooked to admin_head.
   */
  public static function hook_admin_head()
  {

    $langs = AYOUB_BCCB::$settings['support_langs'];
    $langs = mb_convert_kana($langs, 'as'); //全角の文字やスペースがあれば半角に直す
    $langs = str_replace(["\r\n", "\r", "\n"], '', $langs);
    $langs = trim($langs, ',');

    echo '<script id="hcb-langs">var hcbLangs = {' . trim($langs) . '};</script>' . PHP_EOL;
  }
}
