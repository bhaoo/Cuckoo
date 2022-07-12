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
 * @version 2.0.1
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class Cuckoo_Setting {

  private $form;
  public $security;

  public function __construct($form) {
    $this->form = $form;
    Typecho_Widget::widget('Widget_Security')->to($security);
    $this->security = $security;
  }

  public function input($name, $display = NULL, $description = NULL, $default = NULL, $desType = NULL) {
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
  public function select($name, $options, $display = NULL, $description = NULL, $default = NULL, $desType = NULL) {
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

  public function textarea($name, $display = NULL, $description = NULL, $default = "", $rows = NULL) {
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

  public function checkbox($name, $options, $display = NULL, $description = NULL, $default = NULL) {
    $string = "";
    $description = ($description) ? '<div class="mdui-textfield-helper">' . $description . '</div>' : NULL;
    $userOptions = themeOptions($name);
    $string .= '<div class="mdui-textfield">';
    $string .= '<label class="mdui-textfield-label">' . $display . '</label>';
    $string .= '</div>';
    foreach ($options as $option => $value) {
      $checked = "";
      if ($userOptions !== null && in_array($option, $userOptions)) $checked = "checked";
      $string .= '<label style="margin-bottom:10px" class="mdui-switch"><input type="checkbox" name="' . $name . '[]" value="' . $option . '" ' . $checked . '/><i class="mdui-switch-icon"></i></label>';
    }
    $string .= '<div class="mdui-textfield">';
    $string .= $description;
    $string .= '</div>';
    $$name = new Typecho_Widget_Helper_Form_Element_Checkbox($name, $options, $default, _t($display), _t($description));
    $this->form->addInput($$name->multiMode());
    return $string;
  }

  public function plugin($name, $description = NULL, $content = NULL) {
    $plugins = Typecho_Plugin::export();
    $plugins = $plugins['activated'];
    $desc = ($description) ? '<div class="mdui-panel-item-summary">'.$description.'</div>' : '';
    if(!array_key_exists($name, $plugins)){
      $content = '检测到您还未安装“' . $name . '”插件，请安装该插件，否则将无法正常运行其功能';
    }
    echo '<div class="mdui-panel" mdui-panel>
              <div class="mdui-panel-item">
                <div class="mdui-panel-item-header">
                  <div class="mdui-panel-item-title">'.$name.'</div>'.$desc.'
                  <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                </div>
                <div class="mdui-panel-item-body">
                  '.$content.'
                </div>
              </div>
            </div>';
  }

  public function page($name, $description = NULL, $content = NULL) {
    $desc = ($description) ? '<div class="mdui-panel-item-summary">'.$description.'</div>' : '';
    echo '<div class="mdui-panel" mdui-panel>
              <div class="mdui-panel-item">
                <div class="mdui-panel-item-header">
                  <div class="mdui-panel-item-title">'.$name.'</div>'.$desc.'
                  <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                </div>
                <div class="mdui-panel-item-body">
                  '.$content.'
                </div>
              </div>
            </div>';
  }
}