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
 * @version 1.0.0
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class Smile {

 public static function owo($icon, $text) {
  $output = '<a no-go href="javascript:Smilies.grin(\''.htmlspecialchars($text).'\');"><span class="emoji-text mdui-btn mdui-card mdui-shadow-2">'.$icon.'</span></a>';
  return $output;
 }
 
 public static function tieba($icon, $img) {
  $output = '<a no-go href="javascript:Smilies.grin(\''.$icon.'\');"><div class="emoji-tieba mdui-card mdui-btn"><img src="'.staticFiles('assets/images/OwO/tieba/'.$img, 1).'"></img></div></a>';
  return $output;
 }
 
 public static function huaji($icon, $img) {
  $output = '<a no-go href="javascript:Smilies.grin(\''.$icon.'\');"><div class="emoji-hj mdui-card mdui-btn"><img src="'.staticFiles('assets/images/OwO/huaji/'.$img, 1).'"></div></a>';
  return $output;
 }
 
 public static function qwq($icon, $img) {
  $output = '<a no-go href="javascript:Smilies.grin(\''.$icon.'\');"><div class="emoji-qwq mdui-card mdui-btn"><img src="'.staticFiles('assets/images/OwO/qwq/'.$img, 1).'"></div></a>';
  return $output;
 }
 
 public static function emoji($icon, $text) {
  $output = '<a no-go href="javascript:Smilies.grin(\''.$icon.'\');"><span class="emoji-emoji mdui-btn mdui-card mdui-shadow-2" mdui-dialog-close>'.$text.'</span></a>';
  return $output;
 }

 public static function getOwO() {
  $getJson =file_get_contents(staticFiles('assets/json/owo.json', 1));
  $owoArray = json_decode($getJson, true);
  $owoName = array_keys($owoArray);
  for ($i=0; $i<count($owoName); $i++) {
   $smileName = $owoName[$i];
   $smileType = $owoArray[$smileName]['type'];
   if ($smileType == 'tieba') {
    echo '<div id="'.$owoName[$i].'" class="mdui-p-a-2">';
    for ($to=0; $to<count($owoArray[$smileName]['content']); $to++) {
     echo self::tieba($owoArray[$smileName]['content'][$to]['icon'], $owoArray[$smileName]['content'][$to]['img']);
    }
	echo '</div>';
   }elseif ($smileType == 'owo') {
    echo '<div id="'.$owoName[$i].'" class="mdui-p-a-2">';
	for ($to=0; $to<count($owoArray[$smileName]['content']); $to++) {
     echo self::owo($owoArray[$smileName]['content'][$to]['icon'], $owoArray[$smileName]['content'][$to]['text']);
    }
	echo '</div>';
   }elseif ($smileType == 'huaji') {
    echo '<div id="'.$owoName[$i].'" class="mdui-p-a-2">';
	for ($to=0; $to<count($owoArray[$smileName]['content']); $to++) {
     echo self::huaji($owoArray[$smileName]['content'][$to]['icon'], $owoArray[$smileName]['content'][$to]['img']);
    }
	echo '</div>';
   }elseif ($smileType == 'qwq') {
    echo '<div id="'.$owoName[$i].'" class="mdui-p-a-2">';
	for ($to=0; $to<count($owoArray[$smileName]['content']); $to++) {
     echo self::qwq($owoArray[$smileName]['content'][$to]['icon'], $owoArray[$smileName]['content'][$to]['img']);
    }
	echo '</div>';
   }elseif ($smileType == 'emoji') {
    echo '<div id="'.$owoName[$i].'" class="mdui-p-a-2">';
	for ($to=0; $to<count($owoArray[$smileName]['content']); $to++) {
     echo self::emoji($owoArray[$smileName]['content'][$to]['icon'], $owoArray[$smileName]['content'][$to]['text']);
    }
	echo '</div>';
   }
  }
 }
 
 public static function getTitle() {
  $getJson =file_get_contents(staticFiles('assets/json/owo.json', 1));
  $owoArray = json_decode($getJson, true);
  $owoName = array_keys($owoArray);
  for ($i=0; $i<count($owoName); $i++) {
   echo '<a href="#'.$owoName[$i].'" class="mdui-ripple" no-pgo>'.$owoName[$i].'</a>';
  }
 }
 
 public static function randIcon() {
  $randNum = rand(0,10);
  $iconName = array('tag_faces',
   'face',
   'favorite',
   'insert_emoticon',
   'mood',
   'mood_bad',
   'sentiment_satisfied',
   'sentiment_very_dissatisfied',
   'sentiment_very_satisfied',
   'sentiment_dissatisfied',
   'sentiment_neutral');
   $output = $iconName[$randNum];
  return $output;
 }

}