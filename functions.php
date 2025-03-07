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
 * @date 2024-01-02
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

define("THEME_NAME", "Cuckoo");
define("THEME_VERSION", "2.1.0");

require_once("includes/setting.php");
require_once("includes/owo.php");

// 文章自定义设置
function themeFields($layout) {
  $articleType = new Typecho_Widget_Helper_Form_Element_Select('articleType',array('article' => '文章', 'daily' => '日常', 'normal' => '无封面'), 'article', _t('文章类型'));
  $layout->addItem($articleType);
  $wzimg = new Typecho_Widget_Helper_Form_Element_Text('wzimg', NULL, NULL, _t('文章/独立页面封面图'), _t('如果不填将显示随机封面图'));
  $layout->addItem($wzimg);
  $catalog = new Typecho_Widget_Helper_Form_Element_Select('catalog',array('false' => '关闭', 'true' => '启用'), 'false', _t('文章目录'), _t('默认关闭，启用则显示“文章目录”'));
  $layout->addItem($catalog);
  $remark = new Typecho_Widget_Helper_Form_Element_Text('remark', NULL, NULL, _t('吐槽'), _t('仅文章类型为 无封面 时有效'));
  $layout->addItem($remark);
}

// Typecho 设置
function themeInit($archive){
  Helper ::options() -> pageSize = 5;
  Helper ::options() -> commentsOrder = 'DESC';
  Helper ::options() -> commentsMaxNestingLevels = 9999;
  Helper ::options() -> commentsAntiSpam = false;
  Helper ::options() -> commentsMarkdown = true;
  Helper ::options() -> commentsHTMLTagAllowed = '<a href=""> <img src=""> <img src="" class=""> <code> <del>';
}

/* 联系方式 */
function contact(){
  $setting = Helper::options()->drawerContact;
  $output = json_decode($setting, true);
  if(is_array($output)){
    foreach($output as $key => $value){
      if($key == "qq"){
        $website = "//wpa.qq.com/msgrd?v=3&site=qq&menu=yes&uin=";
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
      }elseif($key == "netease-music"){
        $website = "//music.163.com/#/user/home?id=";
      }elseif($key == "zhihu") {
        $website = "//www.zhihu.com/people/";
      }elseif($key == "tencent-kg") {
        $website = "//static-play.kg.qq.com/node/personal_v2?uid=";
      }elseif($key == "douyin") {
        $website = "//v.douyin.com/";
      }
      print_r('<a target ="_blank" href="'.$website.$value.'"><button class="mdui-btn mdui-btn-icon mdui-ripple"><i class="iconfont icon-'.$key.'"></i></button></a>');
    }
  }
}

function drawerBottom(){
  $setting = Helper::options()->drawerBottom;
  $output = json_decode($setting, true);
  if(is_array($output)){
    echo '<div class="drawer-bottom">';
    foreach($output as $key => $value){
      print_r('<a target ="_blank" href="'.$value.'"><button class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">'.$key.'</i></button></a>');
    }
    echo '</div>';
  }
  echo (!empty(Helper::options()->drawerFooter)) ? Helper::options()->drawerFooter : '';
}

// 文章短代码
function parseContent($content) {
  $patt_table = '/<table>(.*?)<\/table>/s';
  $text_table = '<div class="mdui-table-fluid"><table class="mdui-table mdui-table-hoverable">${1}</table></div>';
  $content = preg_replace($patt_table, $text_table, $content);
  $patt_panel = '/\[pl.*?title="(.*?)".*?summary="(.*?)".*?class="(.*?)".*?\](.*?)\[\/pl\]/s';
  $text_panel = '<div class="mdui-panel" mdui-panel>
                   <div class="mdui-panel-item ${3}">
                     <div class="mdui-panel-item-header">
                       <div class="mdui-panel-item-title">${1}</div>
                       <div class="mdui-panel-item-summary">${2}</div>
                       <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                     </div>
                     <div class="mdui-panel-item-body">${4}</div>
                   </div>
                 </div>';
  $content = preg_replace($patt_panel, $text_panel, $content);
  $patt_hr = '/<hr(.*?)>/s';
  $text_hr = '<div class="mdui-divider"></div>';
  $content = preg_replace($patt_hr, $text_hr, $content);
  $patt_bili = '/\[bili.*?av="(.*?)".*?bv="(.*?)".*?\]/s';
  $text_bili = '<div class="post-bili"><iframe src="//player.bilibili.com/player.html?aid=${1}&bvid=${2}&high_quality=1" class="bili-player" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe></div>';
  $content = preg_replace($patt_bili, $text_bili, $content);
  $patt_timeline = '/\[timeline\](.*?)\[\/timeline\]/s';
  $text_timeline = '<div class="post-timeline">${1}</div>';
  $content = preg_replace($patt_timeline, $text_timeline, $content);
  $patt_timeline_item = '/\[item.*?time="(.*?)"\](.*?)\[\/item\]/s';
  $text_timeline_item = '<div class="post-timeline-item">
                           <div class="post-timeline-time">${1}</div>
                           <div class="post-timeline-content">${2}</div>
                         </div>';
  $content = preg_replace($patt_timeline_item, $text_timeline_item, $content);
  if (Helper::options()->isImageRewrite && in_array('open', Helper::options()->isImageRewrite)) {
    $patt_img = '/<img(.*?)src="\.(.*?)"(.*?)>/s';
    $imageURL = Helper::options()->imageRewriteUrl;
    $text_image = '<img${1}src="'.$imageURL.'${2}"${3}>';
    $content = preg_replace($patt_img, $text_image, $content);
  }
  echo $content;
}

/* 表情包解析  感谢ohmyga */
function parseBiaoQing($content) {
  $owoList = (Smile::getOwOList()) ? Smile::getOwOList() : NULL;
  //如果没有表情包则不解析
  if (!$owoList) {
    return $content;
  }
  for ($owoNum = 0; $owoNum < count($owoList); $owoNum++) {
    //如果是 Text 或者 emoji //没必要返回
    if ($owoList[$owoNum]['type'] == 'text' || $owoList[$owoNum]['type'] == 'emoji') {
      $content = $content;
    }
    //如果是图片
    if ($owoList[$owoNum]['type'] == 'picture') {
      //判断有无图片
      if ($owoList[$owoNum]['content']) {
        for ($i = 0; $i < count($owoList[$owoNum]['content']); $i++) {
          $new = '<img src="' . $owoList[$owoNum]['dir'] . $owoList[$owoNum]['content'][$i]['file'] . '" class="emoji-img-'.$owoList[$owoNum]['css'].'" />';
          $content = str_replace($owoList[$owoNum]['content'][$i]['data'], $new, $content);
        }
      }
    }
  }
  return $content;
}

// 判断是否为好丽友
function get_comment_prefix($mail){
  $db = Typecho_Db::get();
  $type = explode('_', $db->getAdapterName());
  $type = array_pop($type);
  $prefix = $db->getPrefix();
  if(array_key_exists('Links', Typecho_Plugin::export()['activated'])){
    if ($type == 'Pgsql')
      $number = $db->fetchAll($db->query("SELECT \"user\" FROM ".$prefix."links WHERE \"user\" = '$mail'"));
    else
      $number = $db->fetchAll($db->query("SELECT user FROM ".$prefix."links WHERE user = '$mail'"));
    if($number){
      ?><img src="<?php staticFiles('images/friend.png'); ?>" class="comment-prefix" mdui-tooltip="{content: '好朋友'}"/><?php
    }
  }
}

// 静态文件源
function staticFiles($content, $type = 0, $isExternal = 0) {
  $setting = Helper::options()->staticFiles;
  if($isExternal){
    if($setting == 'local' || $setting == 'cdn'){
      $setting = Helper::options()->staticFiles;
    }else{
      $setting = 'jsdelivr';
    }
  }
  switch($setting){
    case 'jsdelivr':
      $output = 'https://cdn.jsdelivr.net/gh/Bhaoo/Cuckoo@'.THEME_VERSION.'/assets/'.$content;
      break;
    case 'fastly':
      $output = 'https://fastly.jsdelivr.net/gh/Bhaoo/Cuckoo@'.THEME_VERSION.'/assets/'.$content;
      break;
    case 'gcore':
      $output = 'https://gcore.jsdelivr.net/gh/Bhaoo/Cuckoo@'.THEME_VERSION.'/assets/'.$content;
      break;
    case 'jsdmirror':
      $output = 'https://cdn.jsdmirror.com/gh/Bhaoo/Cuckoo@'.THEME_VERSION.'/assets/'.$content;
      break;
    case 'cdn':
      $output = Helper::options()->staticCdn.'/'.$content;
      break;
    case 'cdnjs':
      $output = 'https://cdnjs.cloudflare.com/ajax/libs/Cuckoo/'.THEME_VERSION.'/'.$content;
      break;
    case 'snrat':
      $output = 'https://cdnjs.snrat.com/ajax/libs/Cuckoo/'.THEME_VERSION.'/'.$content;
      break;
    case 'zstatic':
      $output = 'https://s4.zstatic.net/ajax/libs/Cuckoo/'.THEME_VERSION.'/'.$content;
      break;
    case 'baomitu':
      $output = 'https://lib.baomitu.com/Cuckoo/'.THEME_VERSION.'/'.$content;
      break;
    case 'local':
    default:
      $output = Helper::options()->themeUrl.'/assets/'.$content;
      break;
  }
  if($type == 0){
    print_r($output);
  }elseif($type == 1){
    return($output);
  }
}

// 随机文章封面
function randPic(){
  $setting = Helper::options()->randimg;
  $setting_cdn = Helper::options()->randimgCdn;
  $rand = mt_rand(0,999);
  if ($setting == 'local') {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $dirPath = getcwd() . "/usr/themes/Cuckoo/random/";
    $files = scandir($dirPath);
    $images = array_filter($files, function ($file) use ($dirPath, $allowedExtensions) {
      $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      return in_array($extension, $allowedExtensions) && is_file($dirPath . $file);
    });
    if (!empty($images)) {
      if (!empty($setting_cdn)) {
        $output = $setting_cdn.'/'.$images[array_rand($images)];
      } else {
        $output = Helper::options()->siteUrl.'usr/themes/Cuckoo/random/'.$images[array_rand($images)];
      }
    } else {
      $output = "Error, please check /random.";
    }
  }elseif ($setting == 'custom'){
    $output = preg_replace('/{rand}/', $rand, $setting_cdn);
  }else if ($setting == 'dmoe.cc'){
    $output = 'https://www.dmoe.cc/random.php?rand='.$rand;
  }
  print_r($output);
}

// 阅读时间
function readingTime($cid){
  $db=Typecho_Db::get ();
  $arr=$db->fetchRow($db->select('table.contents.text')->from('table.contents')->where('table.contents.cid=?',$cid)->order('table.contents.cid',Typecho_Db::SORT_ASC)->limit(1));
  $text = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $arr['text']);
  $text_word = mb_strlen($text,'utf-8');
  echo '约 '.ceil($text_word / 400).' 分钟';
}

// 阅读数量
function get_post_view($archive){
  $cid = $archive->cid;
  $db = Typecho_Db::get();
  $type = explode('_', $db->getAdapterName());
  $type = array_pop($type);
  $prefix = $db->getPrefix();
  if(!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))){
    if ($type == 'Pgsql')
      $db->query('ALTER TABLE "' . $prefix . 'contents" ADD "views" bigint DEFAULT 0;');
    else
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
      Typecho_Cookie::set('extend_contents_views', $views);
    }
  }
  echo $row['views'];
}

// 获取评论者头像
function get_comment_avatar($moe = NULL){
  $gravatar = Helper::options()->gravatar;
  if($gravatar == 'geekzu'){
    $host = 'https://sdn.geekzu.org/avatar/';
  }elseif($gravatar == 'qiniu'){
    $host = 'https://dn-qiniu-avatar.qbox.me/avatar';
  }elseif($gravatar == 'cravatar'){
    $host = 'https://cravatar.cn/avatar';
  }elseif($gravatar == 'weavatar'){
    $host = 'https://weavatar.com/avatar';
  }elseif($gravatar == 'cdn'){
    $host = Helper::options()->gravatarCdn;
  }
  $hash = md5(strtolower($moe));
  $avatar = $host.'/'.$hash.'?s=100';
  echo $avatar;
}

// 更多CSS
function otherCss(){
  echo (Helper::options()->otherCss) ?  '<style>'.Helper::options()->otherCss.'</style>' : '';
  echo (Helper::options()->otherHeader) ?? '';
}

// 更多JS、百度统计、跨设备阅读、Katex
function otherJs(){
  $string = "";
  if(Helper::options()->brightTime || Helper::options()->statisticsBaidu || (Helper::options()->qrcode && in_array('open', Helper::options()->qrcode)) || Helper::options()->otherJs || !Helper::options()->describe){
    $brightTime_arr = (Helper::options()->brightTime) ? explode(',', Helper::options()->brightTime) : '';
    $string = '<script>';
    $string .= (Helper::options()->statisticsBaidu) ? "var _hmt = _hmt || [];(function() {var hm = document.createElement('script');hm.src = 'https://hm.baidu.com/hm.js?". Helper::options()->statisticsBaidu ."';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(hm, s);})();" : '';
    $string .= (Helper::options()->qrcode && in_array('open', Helper::options()->qrcode)) ? "qrcode(true);" : '';
    $string .= ($brightTime_arr) ? "var nowHour=new Date().getHours();if(nowHour>".$brightTime_arr[0]." && nowHour<".$brightTime_arr[1]."){darkContent('".$brightTime_arr[2]."')};" : '';
    $string .= (Helper::options()->katexOption) ? 'renderMath=function(className){renderMathInElement(className,'. Helper::options()->katexOption .')};if($(".post-content").length){renderMath($(".post-content")[0])}' : '';
    $string .= (Helper::options()->otherJs) ? Helper::options()->otherJs : '';
    $string .= '</script>';
    $string .= (!Helper::options()->describe) ? "<script>Hitokoto();</script>" : '';
    $string .= (Helper::options()->otherFooter) ?? '';
  }
  if(Helper::options()->mermaidStatus && in_array('open', Helper::options()->mermaidStatus)){
    $staticFiles = Helper::options()->staticFiles;
    $string .= '<script src="';
    switch($staticFiles){
      case 'jsdelivr':
        $string .= '//cdn.jsdelivr.net/npm/mermaid@11.4.0/dist/mermaid.min.js';
        break;
      case 'fastly':
        $string .= '//fastly.jsdelivr.net/npm/mermaid@11.4.0/dist/mermaid.min.js';
        break;
      case 'jsdmirror':
        $string .= '//cdn.jsdmirror.com/npm/mermaid@11.4.0/dist/mermaid.min.js';
        break;
      case 'cdnjs':
        $string .= '//cdnjs.cloudflare.com/ajax/libs/mermaid/11.4.0/mermaid.min.js';
        break;
      case 'snrat':
        $string .= '//cdnjs.snrat.com/ajax/libs/mermaid/11.4.0/mermaid.min.js';
        break;
      case 'gcore':
        $string .= '//gcore.jsdelivr.net/npm/mermaid@11.4.0/dist/mermaid.min.js';
        break;
      case 'zstatic':
      case 'cdn':
      case 'baomitu':
      case 'local':
      default:
        $string .= '//s4.zstatic.net/ajax/libs/mermaid/11.4.0/mermaid.min.js';
        break;
    }
    $string .= '"></script>';
  }
  echo $string;
}

// 更多PJAX、百度统计、跨设备阅读
function otherPjax(){
  if(Helper::options()->statisticsBaidu || (Helper::options()->qrcode && in_array('open', Helper::options()->qrcode)) || Helper::options()->otherPjax || (Helper::options()->mermaidStatus && in_array('open', Helper::options()->mermaidStatus))){
    $string = "<script>$(document).on('pjax:complete',function(){";
    $string .= (Helper::options()->statisticsBaidu) ? "if(typeof _hmt !== 'undefined'){ _hmt.push(['_trackPageview', location.pathname + location.search])};" : '';
    $string .= (Helper::options()->qrcode && in_array('open', Helper::options()->qrcode)) ? "if(!$('.post-content').length){ $('.qrcode').css('display', 'none')}else{ $('.qrcode').css('display', 'block')};" : '';
    $string .= (!Helper::options()->describe) ? "Hitokoto();" : '';
    $string .= (Helper::options()->katexOption) ? 'if($(".post-content").length){renderMath($(".post-content")[0])}' : '';
    $string .= (Helper::options()->mermaidStatus && in_array('open', Helper::options()->mermaidStatus)) ? "mermaid.init(undefined, '.mermaid');" : '';
    $string .= (Helper::options()->otherPjax) ? Helper::options()->otherPjax : '';
    $string .= "});</script>";
    echo $string;
  }
}

// 背景图片
function bgUrl(){
  $setting = Helper::options()->bgUrl;
  $setting_phone = Helper::options()->bgphoneUrl;
  $setting_bgf = Helper::options()->bgFilter;
  $textareaBG = (Helper::options()->textareaBG) ?  Helper::options()->textareaBG : staticFiles('images/textarea.png',1);
  $loadingUrl = (Helper::options()->loadingUrl) ?  Helper::options()->loadingUrl : staticFiles('images/loading.gif', 1);
  if($setting_bgf > 0){
    echo "<style>.index-filter{backdrop-filter: blur(".$setting_bgf."px);}</style>";
  }else{
    echo "<style>.index-filter{display: none}</style>";
  }
  ?><style>.comment-textarea{background-image: url("<?php echo $textareaBG ?>");}.index-img{background-image: url("<?php echo $loadingUrl ?>")}</style><?php
  if(empty($setting) && empty($setting_phone)){
    ?><style>.background{background-image: url("<?php staticFiles('images/bg.jpg'); ?>");}</style><?php
  }else{
    if(empty($setting_phone)){
      echo "<style>.background{background-image: url('$setting');}@media(max-width: 900px){.background{background-image: url('$setting');}}</style>";
    }elseif(empty($setting)){
      echo "<style>.background{background-image: url('$setting_phone');}@media(max-width: 900px){.background{background-image: url('$setting_phone');}}</style>";
    }else{
      echo "<style>.background{background-image: url('$setting');}@media(max-width: 900px){.background{background-image: url('$setting_phone');}}</style>";
    }
  }
}

// 自定义字体
function fontFamily(){
  static $output;
  $output .= (Helper::options()->fontUrl) ? '<link rel="stylesheet" href="'.Helper::options()->fontUrl.'">' : '';
  $output .= (Helper::options()->globalFont) ? '<style>body{font-family:'.Helper::options()->globalFont.'}</style>' : '';
  $output .= (Helper::options()->globalFontWeight) ? '<style>body{font-weight:'.Helper::options()->globalFontWeight.'}</style>' : '';
  $output .= (Helper::options()->logoFont) ? '<style>.mdui-appbar .mdui-typo-title{font-family:'.Helper::options()->logoFont.'}</style>' : '';
  echo $output;
}

// 自定义抽屉
function otherMenu(){
  $data = Helper::options()->otherMenu;
  if (!empty($data)) {
    $json = json_decode($data, true);
    foreach($json as $i) {
      if ($i['type'] == '0') {
        echo '<a href="'.$i['link'].'" class="mdui-list-item mdui-ripple" mdui-drawer-close>
        <i class="mdui-icon material-icons mdui-list-item-icon">'.$i['icon'].'</i>
        <div class="mdui-list-item-content">'.$i['name'].'</div>
       </a>';
      }elseif ($i['type'] == '1') {
        echo '<li class="mdui-collapse-item">
          <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
            <i class="mdui-icon material-icons mdui-list-item-icon">'.$i['icon'].'</i>
            <div class="mdui-list-item-content">'.$i['name'].'</div>
            <i class="mdui-icon material-icons mdui-list-item-icon mdui-collapse-item-arrow">keyboard_arrow_down</i>
          </div>
          <ul class="mdui-collapse-item-body mdui-list mdui-list-dense">';
        Typecho_Widget::widget('Widget_Contents_Post_Date', 'type=month&format=Y 年 m 月')->parse('<a href="{permalink}" class="mdui-list-item mdui-ripple" mdui-drawer-close><div class="mdui-list-item-content">{date}</div><div class="drawer-item">{count}</div></a>');
        echo '</ul></li>';
      }elseif ($i['type'] == '2') {
        echo '<li class="mdui-collapse-item">
           <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
            <i class="mdui-icon material-icons mdui-list-item-icon">'.$i['icon'].'</i>
            <div class="mdui-list-item-content">'.$i['name'].'</div>
            <i class="mdui-icon material-icons mdui-list-item-icon mdui-collapse-item-arrow">keyboard_arrow_down</i>
           </div>
           <ul class="mdui-collapse-item-body mdui-list mdui-list-dense">';
        Typecho_Widget::widget('Widget_Metas_Category_List')->parse('<a href="{permalink}" class="mdui-list-item mdui-ripple" mdui-drawer-close><div class="mdui-list-item-content">{name}</div><div class="drawer-item">{count}</div></a>');
        echo '</ul>
          </li>';
      }elseif ($i['type'] == '3') {
        Typecho_Widget::widget('Widget_Contents_Page_List')->to($pages);
        echo '<li class="mdui-collapse-item">
         <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
          <i class="mdui-icon material-icons mdui-list-item-icon">'.$i['icon'].'</i>
          <div class="mdui-list-item-content">'.$i['name'].'</div>
          <i class="mdui-icon material-icons mdui-list-item-icon mdui-collapse-item-arrow">keyboard_arrow_down</i>
         </div>
         <ul class="mdui-collapse-item-body mdui-list mdui-list-dense">';
        while($pages->next()){
          echo '<a class="mdui-list-item mdui-ripple" href="'.$pages->permalink.'" mdui-drawer-close>'.$pages->title.'</a>';
        }
        echo '</ul></li>';
      }elseif($i['type'] == '4'){
        echo ' <div class="mdui-collapse" mdui-collapse>
        <li class="mdui-collapse-item">
          <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">'.$i['icon'].'</i>
            <div class="mdui-list-item-content">'.$i['name'].'</div>
            <i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
          </div>
          <ul class="mdui-collapse-item-body mdui-list mdui-list-dense">';
        foreach($i['list'] as $ii){ echo '<a class="mdui-list-item mdui-ripple" href="'.$ii['link'].'" mdui-drawer-close>'.$ii['name'].'</a>';}
        echo '</ul></li></div>';
      }elseif ($i['type'] == '5') {
        echo '<div class="mdui-divider"></div>';
      }elseif ($i['type'] == '6') {
        echo '<a href="'.Helper::options()->feedUrl.'" target="_blank" class="mdui-list-item mdui-ripple" mdui-drawer-close>
         <i class="mdui-icon material-icons mdui-list-item-icon">rss_feed</i>
         <div class="mdui-list-item-content">RSS订阅</div>
        </a>';
      }
    }
  }
}

/* 拒绝【删除】或【修改】版权，若删除或修改将不会提供主题相关服务。*/
function Footer(){
  $content = '';
  $footer = Helper::options()->Footer;
  $gabeian = Helper::options()->gabeian;
  $moebei = Helper::options()->moebei;
  $beian = Helper::options()->beian;
  $copy = Helper::options()->copy;
  $copy = preg_replace('/{site}/', Helper::options()->siteUrl, $copy);
  $copy = preg_replace('/{name}/', Helper::options()->title, $copy);
  $copy = preg_replace('/{year}/', date('Y'), $copy);
  if($gabeian){
    preg_match_all("/\d+/", $gabeian,$num);
    $num = $num[0][0];
  }
  if($moebei){
    preg_match_all("/\d+/", $moebei,$num2);
    $num2 = $num2[0][0];
  }
  if(($beian && $gabeian) || ($beian && $moebei) || ($gabeian && $moebei) || ($beian && $gabeian && $moebei)){
    $content .= '<br><br>';
  } elseif ($beian || $gabeian || $moebei) {
    $content .= '｜';
  }
  $content .= ($moebei) ? '<span><a href="https://icp.gov.moe/?keyword='.$num2.'" target="_blank">萌ICP备'.$num2.'号</a></span>' : '';
  $content .= ($beian) ? '<span><a href="//beian.miit.gov.cn">'.Helper::options()->beian.'</a></span>' : '';
  $content .= ($gabeian) ? '<span><img style="vertical-align:middle" src="'.staticFiles('images/beian.png', 1).'" height="20" width="20" alt="公安备案"> <a href="//www.beian.gov.cn/portal/registerSystemInfo?recordcode='.$num.'">'.Helper::options()->gabeian.'</a></span>' : '';
  echo $footer.'<p>'.$copy.$content.'<br><br><span id="cuckoo-copy">Theme <a href="https://github.com/bhaoo/cuckoo" target="_blank">Cuckoo</a> by <a href="https://dwd.moe/" target="_blank">Bhao</a>｜Powered By <a href="http://www.typecho.org" target="_blank">Typecho</a></span></p>';
}

// 友链插件
function Links($type = 0) {
  if(array_key_exists("Links", Typecho_Plugin::export()['activated'])){
    $shuffle = Helper::options()->linksshuffle;
    $link_limit = Helper::options()->linksIndexNum;
    if($type == 0) {
      $Links = Links_Plugin::output("
      <a target='_blank' class='links-url' href='{url}'>
        <div class='mdui-col-sm-6'>
          <div class='links-card mdui-shadow-10'>
            <div class='links-img'><img class='mdui-img-circle' src='{image}' loading='lazy'/></div>
            <div class='links-name links-text'>{name}</div>
            <div class='links-describe links-text'>{description}</div>
          </div>
        </div>
      </a>");
    }elseif($type == 1) {
      $Links = Links_Plugin::output("
      <div class='mdui-col'>
        <a target='_blank' href='{url}'>
         <li class='mdui-list-item mdui-ripple sidebar-module-list'>
            <div class='sidebar-reply-text'>{name}</div>
          </li>
        </a>
      </div>");
    }
    if($shuffle && in_array('open', $shuffle)){
      shuffle($Links);
    }
    $link_limit = (!$type || $link_limit > count($Links)) ? count($Links) : $link_limit;
    for($i = 0; $i < $link_limit; $i++){
      echo $Links[$i];
    }
  }
}

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
