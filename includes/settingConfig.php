<?php

/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 * 
 * Setting Config
 * 
 * @author Bhao
 * @link https://dwd.moe/
 * @version 1.0.3
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class Cuckoo_Setting
{

  private $form;
  private $security;

  public function __construct($form)
  {
    $this->form = $form;
    Typecho_Widget::widget('Widget_Security')->to($security);
    $this->security = $security;
  }

  public function themePanel()
  {
    echo '<link rel="stylesheet" href="' . staticFiles('assets/css/mdui.min.css', 1) . '" />';
    echo '<script src="' . staticFiles('assets/js/mdui.min.js', 1) . '"></script>' .
      '<link rel="stylesheet" href="' . staticFiles('assets/css/setting.min.css', 1) . '" />' .
      '<script src="' . staticFiles('assets/js/jquery.min.js', 1) . '"></script>' .
      '<script src="' . staticFiles('assets/js/setting.min.js', 1) . '"></script>';


    $string = '<div class="backgroud"></div>
    <div class="mdui-appbar mdui-shadow-0">
    <div class="mdui-toolbar">
      <a class="mdui-btn mdui-btn-icon" mdui-drawer="{target: \'#drawer\', swipe: \'true\', overlay: \'false\'}"><i class="mdui-icon material-icons">menu</i></a>
      <a class="mdui-typo-title">Cuckoo</a>
      <div class="mdui-toolbar-spacer"></div>
      <a href="' . Helper::options()->adminUrl . 'themes.php"><button class="mdui-btn">主题列表</button></a>
      <a href="' . Helper::options()->adminUrl . 'theme-editor.php"><button class="mdui-btn">编辑主题</button></a>
    </div>
  </div>';

    $string .= '<div class="setting-container">';

    $string .= '
    <div class="mdui-card">
      <div class="mdui-card-media index-card">
        <div class="mdui-card-media-covered">
          <div class="mdui-card-primary">
            <div class="mdui-card-primary-title">欢迎使用「Cuckoo」主题</div>
          </div>
        </div>
      </div>
    </div>
    <div id="data" data-update="'.base64_encode('theme='.THEME_NAME.'&site='.$_SERVER['HTTP_HOST'].'&version='.THEME_VERSION.'&token='.md5('cuckoo@'.THEME_VERSION)).'"></div>
    <div class="mdui-table-fluid">
      <table class="mdui-table">
        <tbody>
          <tr>
            <td>当前版本</td>
            <td>' . THEME_VERSION . '</td>
          </tr>
          <tr>
            <td>云端版本</td>
            <td><span id="verison"></span></td>
          </tr>
          <tr>
            <td>公告</td>
            <td><span id="notice"></span></td>
          </tr>
          <tr>
            <td>功能</td>
            <td>
              <form class="protected" action="?CuckooBackup" method="post" style="display: block!important">
                <button class="mdui-btn mdui-btn-raised mdui-color-pink setting-button" type="submit" name="type" value="备份模板数据">备份数据</button>
                <button class="mdui-btn mdui-btn-raised mdui-color-pink setting-button" type="submit" name="type" value="还原模板数据">还原数据</button>
                <button class="mdui-btn mdui-btn-raised mdui-color-pink setting-button" type="submit" name="type" value="删除备份数据">删除备份</button>
              </form>
            </td>
        </tr>
        </tbody>
      </table>
    </div>';
    $string .= '';
    echo $string;
  }

  public function form($content = "")
  {
    echo '<form class="mdui-typo" action="' . $this->security->getIndex('/action/themes-edit?config') . '" method="post" enctype="application/x-www-form-urlencoded" style="display: block!important">
            <div class="setting-form">
              <div class="mdui-tab mdui-tab-full-width" mdui-tab>
                <a href="#setting-1" class="mdui-ripple">基础设置</a>
                <a href="#setting-2" class="mdui-ripple">功能</a>
                <a href="#setting-3" class="mdui-ripple">独立页面</a>
              </div>';
    echo $content;
    echo '</div><button class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme"><i class="mdui-icon material-icons">save</i></button>';
    echo '</form></div><footer class="footer"><p>&copy; '.date("Y").' <a href="'.Helper::options()->siteUrl.'">'.Helper::options()->title.'</a><br><br>Theme <a href="">Cuckoo</a> by <a href="https://dwd.moe/">Bhao</a>｜Powered By <a href="http://www.typecho.org">Typecho</a></p></footer>';
  }

  public function module($content = "", $type = "")
  {
    return '<div id="setting-' . $type . '" class="mdui-p-a-2">' . $content . '</div>';
  }

  public function text($content = "")
  {
    return $content;
  }

  public function input($name, $display = NULL, $description = NULL, $default = NULL, $desType = NULL)
  {
    $string = '';
    if ($desType === true) {
      $description = $description . '<br/>';
    } else {
      $description = ($description) ? '<div class="mdui-textfield-helper">' . $description . '</div>' : NULL;
    }
    $userOption = themeOptions($name);
    $string .= '<div class="mdui-textfield">';
    $string .= '<label class="mdui-textfield-label">' . $display . '</label>';
    $string .= '<input class="mdui-textfield-input" type="text" name="' . $name . '" value="' . htmlspecialchars($userOption) . '" />';
    $string .= $description;
    $string .= '</div>';

    $$name = new Typecho_Widget_Helper_Form_Element_Text($name, NULL, $default, $display, $description);
    $this->form->addInput($$name);
    return $string;
  }

  public function select($name, $display = NULL, $description = NULL, $options, $default = NULL, $desType = NULL)
  {
    $string = '';
    if ($desType === true) {
      $description = $description . '<br/>';
    } else {
      $description = ($description) ? '<div class="mdui-textfield-helper">' . $description . '</div>' : NULL;
    }
    $userOption = themeOptions($name);
    if ($userOption === NULL) {
      $userOption = $default;
    }
    $string .= '<div class="mdui-textfield">';
    $string .= '<label class="mdui-textfield-label">' . $display . '</label>';
    $string .= '</div>';
    $string .= '<select class="mdui-select" name="' . $name . '" mdui-select="{position: \'bottom\'}">';
    foreach ($options as $id => $value) {
      $check = ($id == $userOption) ? ' selected="true"' : NULL;
      $string .= '<option value="' . $id . '"' . $check . '>' . $value . '</option>';
    }
    $string .= "</select>";
    $string .= '<div class="mdui-textfield">';
    $string .= $description;
    $string .= '</div>';
    $$name = new Typecho_Widget_Helper_Form_Element_Select($name, $options, $default, _t($display), _t($description));
    $this->form->addInput($$name);
    return $string;
  }

  public function checkbox($name, $display = NULL, $description = NULL, $options, $default = NULL)
  {
    $string = "";
    $userOptions = themeOptions($name);
    $string .= '<ul style="list-style: none!important;padding:0">';
    foreach ($options as $option => $value) {
      $checked = "";
      if ($userOptions !== null && in_array($option, $userOptions)) $checked = "checked";
      $string .= '<li><label class="mdui-checkbox"><input type="checkbox" name="' . $name . '[]" value="' . $option . '" ' . $checked . '/><i class="mdui-checkbox-icon"></i>' . $value . '</label></li>';
    }
    $string .= "</ul>";
    $$name = new Typecho_Widget_Helper_Form_Element_Checkbox($name, $options, $default, _t($display), _t($description));
    $this->form->addInput($$name->multiMode());
    return $string;
  }

  public function textarea($name, $display = NULL, $description = NULL, $default = NULL, $rows = NULL)
  {
    $string = "";
    $rows = ($rows) ? ' rows="' . $rows . '" ' : NULL;
    $userOption = themeOptions($name);
    $description = ($description) ? '<div class="mdui-textfield-helper">' . $description . '</div>' : NULL;
    $floatingLabel = ($userOption == "") ? " mdui-textfield-floating-label" : NULL;
    $string .= '<div class="mdui-textfield"><label class="mdui-textfield-label">' . $display . '</label><textarea class="mdui-textfield-input" type="text" name="' . $name . '"' . $rows . '/>' . htmlspecialchars($userOption) . '</textarea>' . $description . '</div>';
    $$name = new Typecho_Widget_Helper_Form_Element_Textarea($name, null, _t($default), _t($display), _t($description));
    $this->form->addInput($$name);
    return $string;
  }

  public function isPluginAvailable($name)
  {
    $plugins = Typecho_Plugin::export();
    $plugins = $plugins['activated'];
    if(array_key_exists($name, $plugins)){
      return '<p class="setting-normal">检测到您已经安装“'.$name.'”插件 请仔细填写好下面的内容哦～</p>';
    }else{
      return '<p class="setting-error">检测到您还未安装“'.$name.'”插件，请安装该插件，否则将无法正常运行其功能</p>';
    }
  }
}
