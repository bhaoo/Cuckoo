<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 * 
 * OwO Class
 * 
 * @author Bhao
 * @link https://dwd.moe/
 * @version 2.0.0
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class Smile {

  public static function getTitle() {
    $getJson =file_get_contents(__DIR__ .'/owo.json');
    $owoArray = json_decode($getJson, true);
    $owoName = array_keys($owoArray);
    for ($i=0; $i<count($owoName); $i++) {
      echo '<a href="#'.$owoName[$i].'" class="mdui-ripple" no-pgo>'.$owoName[$i].'</a>';
    }
  }

  /**
   * 获取表情包列表
   */
  public static function getOwOList() {
    //打开文件
    $owoFile = file_get_contents(__DIR__ .'/owo.json');
    //判断是否有表情文件
    if(!file_exists(__DIR__ .'/owo.json')) return false;
    //判断是否标准 JSON
    if (!is_array(json_decode($owoFile, true))) return false;
    $owoJson = json_decode($owoFile, true);
    $owo = [];
    //键名
    $owoNames = array_keys($owoJson);
    for($owoNum=0; $owoNum<count($owoJson); $owoNum++) {
      $owoName = $owoNames[$owoNum];

      //如果是图片
      if ($owoJson[$owoName]['type'] == 'picture') {
        $owo[] = [
          'id' => $owoName,
          'name' => $owoJson[$owoName]['title'],
          'css' => $owoJson[$owoName]['css'],
          'type' => $owoJson[$owoName]['type'],
          'dir' => staticFiles('images/OwO/'.$owoName.'/', 1, 1),
          'content' => $owoJson[$owoName]['content']
        ];
      }

      //如果是文字/Emoji
      if ($owoJson[$owoName]['type'] == 'text' || $owoJson[$owoName]['type'] == 'emoji') {
        $owo[] = [
          'id' => $owoName,
          'name' => $owoJson[$owoName]['title'],
          'type' => $owoJson[$owoName]['type'],
          'content' => $owoJson[$owoName]['content']
        ];
      }

      //如果是外部图片
      if ($owoJson[$owoName]['type'] == 'external') {
        $owo[] = [
          'id' => $owoName,
          'name' => $owoJson[$owoName]['title'],
          'css' => $owoJson[$owoName]['css'],
          'type' => 'picture',
          'dir' => ($owoJson[$owoName]['url']) ?: false,
          'content' => $owoJson[$owoName]['content']
        ];
      }
    }

    return $owo;
  }

  public static function getOwO() {
    $owoArray = self::getOwOList();
    $owoName = array_keys($owoArray);
    for ($i=0; $i<count($owoName); $i++) {
      $smileName = $owoName[$i];
      $smileId = $owoArray[$smileName]['id'];
      $smileType = $owoArray[$smileName]['type'];
      if ($smileType == 'text') {
        echo '<div id="'.$smileId.'" class="mdui-p-a-2">';
        for ($to=0; $to<count($owoArray[$smileName]['content']); $to++) {
          $text = $owoArray[$smileName]['content'][$to]['text'];
          echo '<a no-go href="javascript:Smilies.grin(\''.htmlspecialchars($text).'\');"><span class="emoji-text mdui-btn mdui-card mdui-shadow-2">'.$text.'</span></a>';
        }
        echo '</div>';
      }elseif ($smileType == 'emoji') {
        echo '<div id="'.$smileId.'" class="mdui-p-a-2">';
        for ($to=0; $to<count($owoArray[$smileName]['content']); $to++) {
          $text = $owoArray[$smileName]['content'][$to]['text'];
          echo '<a no-go href="javascript:Smilies.grin(\''.htmlspecialchars($text).'\');"><span class="emoji-emoji mdui-btn mdui-card mdui-shadow-2">'.$text.'</span></a>';
        }
        echo '</div>';
      }elseif ($smileType == 'picture') {
        echo '<div id="'.$smileId.'" class="mdui-p-a-2">';
        for ($to=0; $to<count($owoArray[$smileName]['content']); $to++) {
          $data = $owoArray[$smileName]['content'][$to]['data'];
          $file = $owoArray[$smileName]['content'][$to]['file'];
          echo '<a no-go href="javascript:Smilies.grin(\''.$data.'\');"><div class="emoji-bq mdui-card mdui-btn"><img src="'.$owoArray[$smileName]['dir'].$file.'" loading="lazy"/></div></a>';
        }
        echo '</div>';
      }
    }
  }
}