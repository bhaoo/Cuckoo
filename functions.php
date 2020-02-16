<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 * 
 * Functions
 * 
 * @author Bhao
 * @link https://dwd.moe/
 * @version 0.0.6(Beta)
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
require_once("includes/setting.php");
require_once("includes/owo.php");

define("THEME_NAME", "Cuckoo");
define("THEME_VERSION", "0.0.6");

function themeFields($layout) { 
  /* 文章封面设置  */
?>
  <style>#custom-field input{ width:100%; }textarea{ height: 180px; width: 100%;}</style>
<?php 
  $wzimg = new Typecho_Widget_Helper_Form_Element_Text('wzimg', NULL, NULL, _t('文章/独立页面封面图'), _t('如果不填将显示随机封面图'));
  $layout->addItem($wzimg);
  $articleType = new Typecho_Widget_Helper_Form_Element_Select('articleType',array('article' => '文章', 'daily' => '日常'), 'article', _t('文章类型'), _t('如果不填将显示随机封面图'));
  $layout->addItem($articleType);
  $catalog = new Typecho_Widget_Helper_Form_Element_Select('catalog',array('false' => '关闭', 'true' => '启用'), 'false', _t('文章目录'), _t('默认关闭，启用则显示“文章目录”'));
  $layout->addItem($catalog);
}

function themeInit($archive){
  Helper::options()->commentsOrder = 'DESC';
  Helper::options()->commentsMaxNestingLevels = 9999;
  Helper::options()->commentsAntiSpam = false;
  Helper::options()->commentsMarkdown = true;
  Helper::options()->commentsHTMLTagAllowed = '<a href=""> <img src=""> <img src="" class=""> <code> <del>';

  if(@$_GET['action'] == 'ajax_avatar_get' && 'GET' == $_SERVER['REQUEST_METHOD'] ){
    $host = 'https://cdn.v2ex.com/gravatar/';
    $email = strtolower( $_GET['email']);
    $hash = md5($email);
    $qq = str_replace('@qq.com','',$email);
    $sjtx = 'mm';
    if(strstr($email,"qq.com") && is_numeric($qq) && strlen($qq) < 11 && strlen($qq) > 4) {
      $geturl = 'http://ptlogin2.qq.com/getface?&imgtype=1&uin='.$qq;
      $qquser = file_get_contents($geturl);
      $str1 = explode('sdk&k=', $qquser);
      $str2 = explode('&s=', $str1[1]);
      $k = $str2[0];
      $avatar = 'https://q1.qlogo.cn/g?b=qq&k='.$k.'&s=100';
    }else{
      $avatar = $host.$hash.'?d='.$sjtx;
    }
    echo $avatar;
    die();
  }
}

/* 个人头像 */
function logo(){
  $setting = Helper::options()->logoUrl;
  if(empty($setting)){
    staticFiles('assets/images/head.png');
  }else{
    echo $setting;
  }
}

/* 友链独立页个人介绍 */
function linksDescribe(){
  $setting = Helper::options()->linksDescribe;
  if(!empty($setting)){
    echo $setting;
  }
}

/* 判断是否为好丽友 */
function get_comment_prefix($mail){
  $db = Typecho_Db::get();
  $prefix = $db->getPrefix();
  $result = $db->fetchAll($db->query("SHOW TABLES LIKE '".$prefix."links'"));
  if('1' == count($result)){
    $number = $db->fetchAll($db->query("SELECT user FROM ".$prefix."links WHERE user = '$mail'"));
    if($number){
      ?><img src="<?php staticFiles('assets/images/grade/friend.png'); ?>" class="comment-prefix" mdui-tooltip="{content: '好朋友'}"/><?php
    }
  }
}

/* 联系方式 */
function contact(){
  $setting = Helper::options()->drawerContact;
  $output = json_decode($setting, true);
  foreach($output as $key => $value){
    if($key == "qq"){
      $website = "//wpa.qq.com/msgrd?uin=";
    }elseif($key == "weibo"){
      $website = "//weibo.com/";
    }elseif($key == "bilibili"){
      $website = "//space.bilibili.com/";
    }elseif($key == "github"){
      $website = "//github.com/";
    }elseif($key == "twitter"){
      $website = "//twitter.com/";
    }elseif($key == "telegram"){
      $website = "//t.me/";
    }elseif($key == "email"){
      $website = "mailto:";
    }
    print_r('<a target ="_blank" href="'.$website.$value.'"><button class="mdui-btn mdui-btn-icon mdui-ripple"><i class="iconfont icon-'.$key.'"></i></button></a>');
  }
}

 /* 静态文件源 */
function staticFiles($content, $type = 0){
  $setting = Helper::options()->staticFiles;
  $setting_cdn = Helper::options()->staticCdn;
  if($setting == 'local') {
    $output = Helper::options()->themeUrl.'/'.$content;
  }elseif($setting == 'jsdelivr') {
    $output = 'https://cdn.jsdelivr.net/gh/Bhaoo/Cuckoo@'.THEME_VERSION.'/'.$content;
  }elseif($setting == 'cdn') {
    $output = $setting_cdn.'/'.$content;
  }
  if($type == 0){
    print_r($output);
  }elseif($type == 1){  
    return($output);
  }
}

/* 背景图片 */
function bgUrl(){
  $setting = Helper::options()->bgUrl;
  $setting_phone = Helper::options()->bgphoneUrl;
  ?><style>.comment-textarea{background-image: url("<?php staticFiles('assets/images/qh.png'); ?>");}
  .page-img{background-image: url("<?php staticFiles('assets/images/loading.gif'); ?>");}
  .article-pic{background-image: url("<?php staticFiles('assets/images/loading.gif'); ?>");}</style><?php
  if(empty($setting) && empty($setting_phone)){
    ?><style>.body{background-image: url("<?php staticFiles('assets/images/bg.png'); ?>");}</style><?php
  }else{
    if(empty($setting_phone)){
      echo "<style>.body{background-image: url('$setting');}@media(max-width: 900px){.body{background-image: url('$setting');}}</style>";
    }elseif(empty($setting)){
      echo "<style>.body{background-image: url('$setting_phone');}@media(max-width: 900px){.body{background-image: url('$setting_phone');}}</style>";
    }else{
      echo "<style>.body{background-image: url('$setting');}@media(max-width: 900px){.body{background-image: url('$setting_phone');}}</style>";
    }
  }
}

function parsePicture($content){
  $pattern = '/<img(.*?)src="(.*?)"(.*?)>/s';
  $pattern_2 = '/<a\b([^>]+?)\bhref="((?!'.addcslashes(Helper::options()->index, '/._-+=#?&').'|\#).*?)"([^>]*?)>/i';
  $text = '<img${1}src="${2}" class="article-page-img">';
  $text_2 = '<a\1href="\2"\3 target="_blank">';
  $content = preg_replace($pattern, $text, $content);
  $content = preg_replace($pattern_2, $text_2, $content);
  return $content;
}

/* 表情包解析  感谢ohmyga */
function parseBiaoQing($content){
  $content = preg_replace_callback('/\:\s*(a|bishi|bugaoxing|guai|haha|han|hehe|heixian|huaji|huanxin|jingku|jingya|landeli|lei|mianqiang|nidongde|pen|shuijiao|suanshuang|taikaixin|tushe|wabi|weiqu|what|what|wuzuixiao|xiaoguai|xiaohonglian|xiaoniao|xiaoyan|xili|yamaidei|yinxian|yiwen|zhenbang|aixin|xinsui|bianbian|caihong|damuzhi|dangao|dengpao|honglingjin|lazhu|liwu|meigui|OK|shafa|shouzhi|taiyang|xingxingyueliang|yaowan|yinyue)\s*\:/is',
   'parsePaopaoBiaoqingCallback', $content);
  $content = preg_replace_callback('/\:\s*(huaji1|huaji2|huaji3|huaji4|huaji5|huaji6|huaji7|huaji8|huaji9|huaji10|huaji11|huaji12|huaji13|huaji14|huaji15|huaji16|huaji17|huaji18|huaji19|huaji20|huaji21|huaji22|huaji23|huaji24|huaji25|huaji26|huaji27)\s*\:/is',
   'parseHuajibiaoqingCallback', $content);
  $content = preg_replace_callback('/\:\s*(qwq1|qwq2|qwq3|qwq4|qwq5|qwq6|qwq7|qwq8|qwq9|qwq10|qwq11|qwq12|qwq13|qwq14|qwq15|qwq16|qwq17|qwq18|qwq19|qwq20|qwq21|qwq22|qwq23|qwq24|qwq25|qwq26)\s*\:/is',
   'parseqwqbiaoqingCallback', $content);
  return $content;
 }
function parsePaopaoBiaoqingCallback($match){
  return '<img class="emoji-img-tieba" src="'.Helper::options()->themeUrl.'/assets/images/OwO/tieba/'.$match[1].'.png">';
 }
function parseHuajibiaoqingCallback($match){
  return '<img class="emoji-img-hj" src="'.Helper::options()->themeUrl.'/assets/images/OwO/huaji/'.$match[1].'.gif">';
 }
function parseqwqbiaoqingCallback($match){
  return '<img class="emoji-img-qwq" src="'.Helper::options()->themeUrl.'/assets/images/OwO/qwq/'.$match[1].'.png">';
 }
function commentsReply($comment) {
  $db = Typecho_Db::get();
  $parentID = $db->fetchRow($db->select('parent')->from('table.comments')->where('coid = ?', $comment->coid));
  $parentID=$parentID['parent'];
  if($parentID=='0'){
   return '';
  }else {
   $author=$db->fetchRow($db->select()->from('table.comments')->where('coid = ?', $parentID));
   if (!array_key_exists('author', $author) || empty($author['author']))
   $author['author'] = '已删除的评论';
   return '<span class="comment-reply-name">@'.$author['author'].'</span>';
  }
 }

/* 随机文章背景图 */
function randPic(){
  //$setting = Helper::options()->randimg;
  $setting = "api.ohmyga.cn";
  $rand = mt_rand(0,99);
  if ($setting == 'api.ohmyga.cn') {
   $output = 'https://api.ohmyga.cn/wallpaper/?rand='.$rand;
  }elseif ($setting == 'local') {
   $openfile = glob(Helper::options()->themeFile(getTheme(), "random/*"), GLOB_BRACE);
   $img = array_rand($openfile);
   preg_match('/\/random\/\S*\.(jpg|png|gif)/', $openfile[$img], $out);
   $output = Helper::options()->siteUrl.'usr/themes/'.getTheme().$out[0];
  }elseif ($setting == 'cdn'){
   $output = adSetting('randPic', 'url').'?rand='.$rand;
  }elseif ($setting == 'cdnno'){
   $output = adSetting('randPic', 'url');
  }
  print_r($output);
}

/* 百度统计 */
function statisticsBaidu(){
  $setting = Helper::options()->statisticsBaidu;
  if(!empty($setting)){
    echo '<script>var _hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "https://hm.baidu.com/hm.js?'.$setting.'";var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hm, s);})();</script>';
  }
}

/* 更多Pjax回调 */
function otherPjax(){
  $setting = Helper::options()->otherPjax;
  $setting_baidu = Helper::options()->statisticsBaidu;
  if(!empty($setting)){
    if(!empty($setting_baidu)){
      $output_baidu = "if(typeof _hmt !== 'undefined'){ _hmt.push(['_trackPageview', location.pathname + location.search]);}";
    }
    echo "<script>function otherPjax() {".$output_baidu.$setting."}</script>";
  }
}

/* 底部信息*/
function Footer(){
  $setting = Helper::options()->Footer;
  if(!empty($setting)){ 
    $setting = '<p>'.$setting.'</p>';
    echo $setting.'<p>&copy; '.date("Y").' <a href="'.Helper::options()->siteUrl.'">'.Helper::options()->title.'</a>｜Theme <a href="">Cuckoo</a> by <a href="https://dwd.moe/">Bhao</a>｜Powered By <a href="http://www.typecho.org">Typecho</a></p>'; 
  }else{
    echo '<p>&copy; '.date("Y").' <a href="'.Helper::options()->siteUrl.'">'.Helper::options()->title.'</a><br><br>Theme <a href="">Cuckoo</a> by <a href="https://dwd.moe/">Bhao</a>｜Powered By <a href="http://www.typecho.org">Typecho</a></p>';
  }
}

/* 个人描述 */
function describe(){
  $setting = Helper::options()->describe;
  if(empty($setting)){
    echo "<script src='https://v1.hitokoto.cn/?encode=js&select=%23hitokoto' defer></script>";
    echo '<p id="hitokoto">:D 获取中...</p>';
  }else{
    echo '<p>'.$setting.'</p>';
  }
}

/* 文章阅读次数(含Cookie) */
function get_post_view($archive){
  $cid = $archive->cid;
  $db = Typecho_Db::get();
  $prefix = $db->getPrefix();
  if(!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))){
    $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
    echo 0;
    return;
  }
  $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
  if($archive->is('single')){
    $views = Typecho_Cookie::get('extend_contents_views');
    if(empty($views)){
      $views = array();
    }else{
      $views = explode(',', $views);
    }
    if(!in_array($cid,$views)){
      $db->query($db->update('table.contents')->rows(array('views' => (int) $row['views'] + 1))->where('cid = ?', $cid));
      array_push($views, $cid);
      $views = implode(',', $views);
      Typecho_Cookie::set('extend_contents_views', $views); //记录查看cookie
    }
  }
  echo $row['views'];
}

/* 获取评论者头像 */
function get_comment_avatar($moe=NULL){
  $host = 'https://cdn.v2ex.com/gravatar';
  $hash = md5(strtolower($moe));
  $email = strtolower($moe);
  $qq = str_replace('@qq.com','',$email);
  if(strstr($email,"qq.com") && is_numeric($qq) && strlen($qq) < 11 && strlen($qq) > 4){
   $geturl = 'http://ptlogin2.qq.com/getface?&imgtype=1&uin='.$qq;
   $qquser = file_get_contents($geturl);
   $str1 = explode('sdk&k=', $qquser);
   $str2 = explode('&s=', $str1[1]);
   $k = $str2[0];
   $avatar = 'https://q1.qlogo.cn/g?b=qq&k='.$k.'&s=100';
  }else{
   $avatar = $host.'/'.$hash.'?s=640';
  }
  echo $avatar;
}

/* 获取浏览器信息 */
function getBrowser($agent) {
  if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
   $name = 'IE';
   $icon = 'icon-IE';
  }elseif (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
   $name = 'Firefox';
   $icon = 'icon-firefox';
  }elseif (preg_match('/Maxthon([\d]*)\/([^\s]+)/i', $agent, $regs)) {
   $name = 'Aoyou';
   $icon = 'icon-Aoyou_Browser';
  }elseif (preg_match('#SE 2([a-zA-Z0-9.]+)#i', $agent, $regs)) {
   $name = 'Sougou';
   $icon = 'icon-Sougou_Browser';
  }elseif (preg_match('#360([a-zA-Z0-9.]+)#i', $agent, $regs)) {
   $name = '360';
   $icon = 'icon-360_Browser';
  }elseif (preg_match('/Edge([\d]*)\/([^\s]+)/i', $agent, $regs)) {
   $name = 'Edge';
   $icon = 'icon-edge';
  }elseif (preg_match('/QQ/i', $agent, $regs)||preg_match('/QQBrowser\/([^\s]+)/i', $agent, $regs)) {
   $name = 'QQ';
   $icon = 'icon-QQBrowser';
  }elseif (preg_match('/UC/i', $agent)) {
   $name = 'UC';
   $icon = 'icon-UC_Browser';
  }elseif (preg_match('/UBrowser/i', $agent, $regs)) {
   $name = 'UC';
   $icon = 'icon-UC_Browser';
  }elseif (preg_match('/MicroMesseng/i', $agent, $regs)) {
   $name = 'WeChat';
   $icon = 'icon-wechat';
  }elseif (preg_match('/WeiBo/i', $agent, $regs)) {
   $name = 'Weibo';
   $icon = 'icon-weibo';
  }elseif (preg_match('/BIDU/i', $agent, $regs)) {
   $name = 'Baidu';
   $icon = 'icon-Baidu_Browser';
  }elseif (preg_match('/LBBROWSER/i', $agent, $regs)) {
   $name = 'LB';
   $icon = 'icon-LBBROWSER';
  }elseif (preg_match('/TheWorld/i', $agent, $regs)) {
   $name = 'TheWorld';
   $icon = 'icon-TheWorld_Browser';
  }elseif (preg_match('/XiaoMi/i', $agent, $regs)) {
   $name = 'Xiaomi';
   $icon = 'icon-xiaomi';
  }elseif (preg_match('/2345Explorer/i', $agent, $regs)) {
   $name = '2345';
   $icon = 'icon-2345_Browser';
  }elseif (preg_match('/YaBrowser/i', $agent, $regs)) {
   $name = 'Yandex';
   $icon = 'icon-Yandex_Browser';
  }elseif (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
   $name = 'Opera';
   $icon = 'icon-Opera_Browser';
  }elseif (preg_match('/Thunder/i', $agent, $regs)) {
   $name = 'Xunlei';
   $icon = 'icon-xunlei';
  }elseif (preg_match('/Chrome([\d]*)\/([^\s]+)/i', $agent, $regs)) {
   $name = 'Chrome';
   $icon = 'icon-chrome';
  }elseif (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
   $name = 'Safari';
   $icon = 'icon-safari';
  }else{
   $name = 'Other';
   $icon = 'icon-Browser';
  }
  echo '<i class="iconfont '.$icon.' comment-ua" mdui-tooltip="{content: \''.$name.'\'}"></i>';
 }
 
 /* 获取操作系统 */
 function getOs($agent) {
  if (preg_match('/win/i', $agent)) {
   if (preg_match('/nt 5.1/i', $agent)) {
    $name = 'Windows XP';
    $icon = 'icon-windows_old';
   }elseif (preg_match('/nt 6.0/i', $agent)) {
    $name = 'Windows Vista';
    $icon = 'icon-windows_old';
   }elseif (preg_match('/nt 6.1/i', $agent)) {
    $name = 'Windows 7';
    $icon = 'icon-windows_old';
   }elseif (preg_match('/nt 6.2/i', $agent)) {
    $name = 'Windows 8';
    $icon = 'icon-windows';
   }elseif (preg_match('/nt 6.3/i', $agent)) {
    $name = 'Windows 8.1';
    $icon = 'icon-windows';
   }elseif (preg_match('/nt 10.0/i', $agent)) {
    $name = 'Windows 10';
    $icon = 'icon-windows';
   }else{
    $name = 'Windows XP';
    $icon = 'icon-windows';
   }
  }elseif (preg_match('/android/i', $agent)) {
   if (preg_match('/android 5/i', $agent)) {
    $name = 'Android';
    $icon = 'icon-android';
   }elseif (preg_match('/android 6/i', $agent)) {
    $name = 'Android';
    $icon = 'icon-android';
   }elseif (preg_match('/android 7/i', $agent)) {
    $name = 'Android';
    $icon = 'icon-android';
   }elseif (preg_match('/android 8/i', $agent)) {
    $name = 'Android';
    $icon = 'icon-android';
   }elseif (preg_match('/android 9/i', $agent)) {
    $name = 'Android';
    $icon = 'icon-android';
   }else{
    $name = 'Android';
    $icon = 'icon-android';
   }
  }elseif (preg_match('/linux/i', $agent)) {
   $name = 'Linux';
   $icon = 'icon-linux';
  }elseif (preg_match('/iPhone/i', $agent)) {
   $name = 'IPhone';
   $icon = 'icon-ios';
  }elseif (preg_match('/iPad/i', $agent)) {
   $name = 'IPad';
   $icon = 'icon-ios';
  }elseif (preg_match('/mac/i', $agent)) {
   $name = 'Mac';
   $icon = 'icon-ios';
  }else{
   $name = 'other';
   $icon = 'icon-os';
  }
  echo '<i class="iconfont '.$icon.' comment-ua" mdui-tooltip="{content: \''.$name.'\'}"></i>';
 }

/* 友链页面地址 */
function links(){
  $setting = Helper::options()->links;
  if(!empty($setting)){
    echo "
    <a href='$setting'><li class='mdui-list-item mdui-ripple'>
    <i class='mdui-list-item-icon mdui-icon material-icons'>link</i>
    <div class='mdui-list-item-content'>友链</div>
    </li></a>
    ";
  }
}

/* Favicon图标 */
function favicon(){
  $setting = Helper::options()->favicon;
  if(empty($setting)){
    staticFiles('assets/images/favicon.ico');
  }else{
    echo $setting;
  }
}

 /* 检查更新&获取公告 */
function themeUpdate(){
  $output = 'https://api.qwq.asia/typecho/theme/?s='.$_SERVER['HTTP_HOST'].'&v='.THEME_VERSION;
  return $output;
}
?>