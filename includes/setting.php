<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 *
 * Setting
 *
 * @author Bhao
 * @link https://dwd.moe/
 * @date 2023-11-27
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once(__DIR__ ."../../libs/setting.php");

function setting($name, $content, $type = 0) {
  if($type == 0){
    $type = staticFiles($content, 1);
  }elseif($type == 1){
    $type = $content;
  }
  echo (Helper::options()->$name) ? Helper::options()->$name : $type;
}

function getTheme() {
  static $themeName = NULL;
  if ($themeName === NULL) {
    $db = Typecho_Db::get();
    $query = $db->select('value')->from('table.options')->where('name = ?', 'theme');
    $result = $db->fetchAll($query);
    $themeName = $result[0]["value"];
  }
  return $themeName;
}

function themeOptions($name) {
  static $themeOptions = NULL;
  if ($themeOptions === NULL) {
    $db = Typecho_Db::get();
    $query = $db->select('value')->from('table.options')->where('name = ?', 'theme:'.getTheme());
    $result = $db->fetchAll($query);
    $themeOptions = unserialize($result[0]["value"]);
  }

  return ($name === NULL) ? $themeOptions : (isset($themeOptions[$name]) ? $themeOptions[$name] : NULL);
}

function themeConfig($form) {
  require_once(__DIR__ ."/setting/header.php");
  $config = new Cuckoo_Setting($form); ?>
  <div id="message">
    <div class="setting-title">基础信息</div>
    <div id="data" data-update="<?php echo base64_encode('theme='.THEME_NAME.'&site='.$_SERVER['HTTP_HOST'].'&version='.THEME_VERSION.'&token='.md5('cuckoo@'.THEME_VERSION)) ?>"></div>
    <div class="mdui-table-fluid">
      <table class="mdui-table">
        <tbody>
        <tr>
          <td>当前版本</td>
          <td><?php echo THEME_VERSION; ?></td>
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
              <input class="mdui-btn mdui-btn-raised mdui-color-theme setting-button" type="submit" name="type" value="备份数据">
              <input class="mdui-btn mdui-btn-raised mdui-color-theme setting-button" type="submit" name="type" value="还原数据">
              <input class="mdui-btn mdui-btn-raised mdui-color-theme setting-button" type="submit" name="type" value="删除备份">
            </form>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="setting-title log-title">更新日志</div>
    <div class="mdui-panel log" mdui-panel>
      <div class="mdui-panel-item">
        <div class="mdui-panel-item-header">
          <div class="mdui-panel-item-title">加载中...</div>
          <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
        </div>
        <div class="mdui-panel-item-body">
          <p>在火速加载力！不要急嘛！</p>
        </div>
      </div>
    </div>
  </div>
  <form id="cuckoo-form" class="mdui-typo" action="<?php echo $config->security->getIndex('/action/themes-edit?config') ?>" method="post" enctype="application/x-www-form-urlencoded" style="display: block!important">
    <div id="basic">
      <div class="setting-title">基础设置</div>
      <div class="setting-content">
        <?php echo $config->input('title', '昵称', '在这里填入你的昵称就会在侧边栏显示啦!。', 'Cuckoo').
        $config->input('webTitle', '网站名称', '在这里填入网站名称就会在标题和顶部显示啦!。', 'Cuckoo').
        $config->input('describe', '个人描述', '在这里填写个人描述，以在侧边栏显示(若空则自动填充 一言)').
        $config->textarea('drawerContact', '联系方式', '在此填写您的联系方式，最多仅能展示6个。').
        $config->select('primaryColor',
          ['red'        => 'Red',
            'pink'        => 'Pink',
            'purple'      => 'Purple',
            'deep-purple' => 'Deep Purple',
            'indigo'      => 'Indigo',
            'blue'        => 'Blue',
            'light-blue'  => 'Light Blue',
            'cyan'        => 'Cyan',
            'teal'        => 'Teal',
            'green'       => 'Green',
            'light-green' => 'Light Green',
            'lime'        => 'Lime',
            'yellow'      => 'Yellow',
            'amber'       => 'Amber',
            'orange'      => 'Orange',
            'deep-orange' => 'Deep Orange',
            'brown'       => 'Brown',
            'grey'        => 'Grey',
            'blue-grey'   => 'Blue Grey'
          ], '主题主色', '默认为 Pink', 'pink').
        $config->select('accentColor',
          ['red'        => 'Red',
            'pink'        => 'Pink',
            'purple'      => 'Purple',
            'deep-purple' => 'Deep Purple',
            'indigo'      => 'Indigo',
            'blue'        => 'Blue',
            'light-blue'  => 'Light Blue',
            'cyan'        => 'Cyan',
            'teal'        => 'Teal',
            'green'       => 'Green',
            'light-green' => 'Light Green',
            'lime'        => 'Lime',
            'yellow'      => 'Yellow',
            'amber'       => 'Amber',
            'orange'      => 'Orange',
            'deep-orange' => 'Deep Orange'
          ],'主题强调色', '默认为 Pink', 'pink').
        $config->input('favicon', '网站图标', '在这里填入一个图片 URL 地址, 以加上一个 Favicon图标，没有则不填。').
        $config->input('logoUrl', 'LOGO', '在这里填入一个图片 URL 地址, 以加上一个 LOGO, 此LOGO也展示在侧边栏中。').
        $config->input('beian', '备案号', '无需输入a标签，只需输入备案号即可。').
        $config->input('gabeian', '公安联网备案号', '无需输入a标签，只需输入备案号即可。').
        $config->input('moebei', '萌国ICP备案', '无需输入a标签，只需输入备案号即可。').
        $config->input('sidebarBg', '侧边栏背景图', '在这里填入一个图片 URL 地址, 以加上一个 侧边栏背景图。').
        $config->input('bgUrl', '背景图片(电脑)', '在这里填入一个图片 URL 地址, 以设置博客电脑背景图片').
        $config->input('bgphoneUrl', '背景图片(手机)', '在这里填入一个图片 URL 地址, 以设置博客手机背景图片').
        $config->input('bgFilter', '背景图片高斯模糊', '选择"0"即为关闭高斯模糊哦！若网站动画较卡可设置为"0"', '0').
        $config->input('loadingUrl', '首页文章 Loading 图', '默认则为玉子哦！').
        $config->textarea('textareaBG', '评论框侧边图', '输入图片地址即可，默认为泠鸢yousa哦！').
        $config->checkbox('showComments', ['open' => '默认开启，将会在文章中显示评论'], '显示评论模块', '默认开启，将会在文章中显示评论', ['open']) ?>
      </div>
    </div>
    <div id="menu">
      <div class="setting-title">更多设置</div>
      <div class="setting-content">
        <?php echo $config->select('staticFiles',
            ['local'       => '本地',
              'jsdelivr'   => 'JsDelivr',
              'fastly'   => 'Fastly',
              'gcore'   => 'Gcore',
              'jsdmirror'   => 'JsdMirror',
              'cdnjs'      => 'cdnjs',
              'snrat' => 'snrat',
              'zstatic'    => 'zstatic',
              'baomitu'    => 'Baomitu',
              'cdn'        => '自定义 CDN'
            ], '静态文件源', '推荐选择 “JsDelivr源”', 'local').
        $config->input('staticCdn', '自定义静态文件CDN', '在这里填写你自己的CDN(如 api.xxx.xxx)，以获取静态文件(需在上方选择自定义CDN)').
        $config->select('randimg',
          [
            'local'   => '本地',
            'custom'  => '自定义',
            'dmoe.cc' => 'dmoe.cc'
          ], '随机文章图源', '在这里可以设置随机文章图源，仅当文章没有设置图片时引用。”', 'dmoe.cc').
        $config->input('randimgCdn', '自定义随机文章图源', '在这里填写你自己的图源(如 api.xxx.xxx)，以获取随机图片(需在上方选择自定义)，若出现完全重复可以在结尾加上 ?rand={rand}').
        $config->select('gravatar',
          ['cravatar' => 'Cravatar',
           'geekzu' => '极客族',
           'qiniu'  => '七牛',
           'cdn'    => '自定义 CDN'
          ], 'Gravatar头像源', '在这里可以设置Gravatar头像源(Cravatar 支持 QQ头像)','cravatar').
        $config->input('gravatarCdn', '自定义Gravatar头像源CDN', '在这里填写你自己的CDN(如 api.xxx.xxx)，以获取随机图片(需在上方选择自定义CDN)').
        $config->input('sticky', '置顶文章', '置顶的文章cid，按照排序输入, 请以半角逗号或空格分隔。').
        $config->input('statisticsBaidu', '百度统计', '仅需要输入"https://hm.baidu.com/hm.js?xxxxxx"中的"xxxxxx部分即可"').
        $config->checkbox('qrcode', ['open' => '默认开启，将会在文章&页面导航栏中显示按钮'], '跨设备阅读', '默认开启，将会在文章&页面导航栏中显示按钮', ['open']).
        $config->input('tagCloud', '标签云', '请根据自己所需填写展示数量，输入“0”则不显示标签云', '0').
        $config->textarea('brightTime', '定时开/关暗色模式', '填写格式(24H)：开启时间,关闭时间,输出信息 默认为空即为不开启 例: 22,6,深色模式开启').
        $config->textarea('Footer', '底部信息', '在这里填写的信息将在底部显示哦～ 温馨提示：若发现顶头可以用 p 标签包括你的内容哦！').
        $config->textarea('copy', '站点版权', '可用变量 | {site}: 站点链接 | {name}: 基础设置-昵称 | {year}: 当前年份', '&copy; {year} <a href="{site}">{name}</a>').
        $config->input('globalFontWeight', '全局字体粗细', '用于设置全局字体粗细，无需填写全局字体也可以进行设置')
        ?>
      </div>
    </div>
    <div id="advanced">
      <div class="setting-title">高级设置</div>
      <div class="setting-content">
        <?php echo $config->textarea('otherMenu', '自定义抽屉', '在这里可以自行添加更多的菜单，请遵循JSON语法进行使用。',
          '[{
            "name":"分类",
            "link":"#",
            "icon":"view_list",
            "type":"2"
          },{
            "name":"归档",
            "link":"#",
            "icon":"access_time",
            "type":"1"
          },{
            "name":"页面",
            "link":"#",
            "icon":"view_carousel",
            "type":"3"
          },{
            "type":"5"
          },{
            "type":"6"
          }]').
        $config->textarea('katexOption', 'Katex 选项配置', '在这里可以自定义 Katex 选项配置，可以看看这里哦！<a href="https://katex.org/docs/autorender.html">Katex Auto-render Extension</a>',
        "{
          delimiters: [
            {left: '$$', right: '$$', display: true},
            {left: '$', right: '$', display: false},
          ]
        }").
        $config->textarea('drawerBottom', '抽屉底部功能', '可以在此往抽屉底部添加按钮，最多仅能展示6个。').
        $config->textarea('drawerFooter', '抽屉底部引用', '输入你所想要添加的内容即可添加至抽屉底部哦！如需推荐看看主题文档哦！').
        $config->textarea('otherHeader', '顶部引用', '输入你所想要添加的内容即可哦！').
        $config->textarea('otherFooter', '底部引用', '输入你所想要添加的内容即可哦！').
        $config->textarea('otherCss', '更多CSS', '输入你所想要添加的CSS即可哦！').
        $config->textarea('otherJs', '更多JS', '输入你所想要添加的JS即可哦！').
        $config->textarea('otherPjax', 'PJAX回调', '在这里可以自行添加PJAX回调内容,引号需用“单引号”').
        $config->input('fontUrl', '字体CSS链接', '填写即启用了自定义字体功能，不填写仅能使用部分字体').
        $config->input('globalFont', '全局字体', '填写可使用的字体，否则无法显示所填字体').
        $config->input('logoFont', 'LOGO字体', '填写可使用的字体，否则无法显示所填字体')
        ?>
      </div>
    </div>
    <div id="plugins">
      <div class="setting-title">插件扩展</div>
      <?php $config->plugin('Links', '友链插件',
      $config->input('linksIndexNum', '主页友链展示个数', '在这里填写主页友链最多展示个数，默认为 0（则不显示），推荐设置为 10 个', '0').
      $config->checkbox('linksshuffle', ['open' => '默认开启，开启将会打乱顺序展示'], '是否打乱顺序展示', '默认开启，开启将会打乱顺序展示', ['open'])).
      $config->page('Bilibili 追番', '需创建独立页面才会展示哦',
      $config->input('BilibiliUid', 'B站UID', '请认真填写好，记得检查别填错啦！').
      $config->input('CacheTime', '缓存时间', '单位为“秒”，不会填写可留空，默认为一天。').
      $config->input('Amout', '展示数量', '需要展示的番剧数量，默认为 10 个').
      $config->textarea('HideMedia', '隐藏番剧', '输入番剧id，多个id用英文逗号隔开，就可以隐藏你所不想展示的番剧啦！').
      $config->input('Sessdata', 'Sessdate（选填）', '如需获取观看进度需填写 Sessdata，详细请看文档说明')).
      $config->page('Mermaid', '一个开源的、基于JavaScript的绘图库',
        $config->checkbox('mermaidStatus', ['open' => '开启'], '总开关', '默认关闭，开启以支持 Mermaid', ['close'])
        )
      ?>
    </div>
    <button class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme"><i class="mdui-icon material-icons">save</i></button>
  </form>
<?php require_once(__DIR__ ."/setting/footer.php"); themeBackup();}

function themeBackup(){
  $db = Typecho_Db::get();
  $sjdq=$db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:'.getTheme()));
  $ysj = $sjdq['value'];
  if(isset($_POST['type'])){
    if($_POST["type"]=="备份数据"){
      if($db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:CuckooBackup'))){
        $update = $db->update('table.options')->rows(array('value'=>$ysj))->where('name = ?', 'theme:CuckooBackup');
        $updateRows= $db->query($update);
        echo '<script language="JavaScript">
                window.setTimeout("location=\''.Helper::options()->adminUrl.'options-theme.php\'", 2500);
                mdui.snackbar({
                message: "备份已更新，请等待自动刷新！如果等不到请点击",
                buttonText: "这里",
                onButtonClick: function(){
                  window.location.href = "'.Helper::options()->adminUrl.'options-theme.php";
                }});
              </script>';
      }else{
        if($ysj){
          $insert = $db->insert('table.options')->rows(array('name' => 'theme:CuckooBackup','user' => '0','value' => $ysj));
          $insertId = $db->query($insert);
          echo '<script language="JavaScript">
                window.setTimeout("location=\''.Helper::options()->adminUrl.'options-theme.php\'", 2500);
                mdui.snackbar({
                message: "备份已更新，请等待自动刷新！如果等不到请点击",
                buttonText: "这里",
                onButtonClick: function(){
                  window.location.href = "'.Helper::options()->adminUrl.'options-theme.php";
                }});
              </script>';
        }
      }
    }
    if($_POST["type"]=="还原数据"){
      if($db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:CuckooBackup'))){
        $sjdub=$db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:CuckooBackup'));
        $bsj = $sjdub['value'];
        $update = $db->update('table.options')->rows(array('value'=>$bsj))->where('name = ?', 'theme:'.getTheme());
        $updateRows= $db->query($update);
        echo '<script language="JavaScript">
                window.setTimeout("location=\''.Helper::options()->adminUrl.'options-theme.php\'", 2500);
                mdui.snackbar({
                message: "检测到模板备份数据，恢复完成，请等待自动刷新！如果等不到请点击",
                buttonText: "这里",
                onButtonClick: function(){
                  window.location.href = "'.Helper::options()->adminUrl.'options-theme.php";
                }});
              </script>';
      }else{
        echo '<script language="JavaScript">mdui.snackbar({message: "恢复失败，因为你没备份过设置哇（；´д｀）ゞ"})</script>';
      }
    }
    if($_POST["type"] == "删除备份"){
      if($db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:CuckooBackup'))){
        $delete = $db->delete('table.options')->where ('name = ?', 'theme:CuckooBackup');
        $deletedRows = $db->query($delete);
        echo '<script language="JavaScript">
                window.setTimeout("location=\''.Helper::options()->adminUrl.'options-theme.php\'", 2500);
                mdui.snackbar({
                message: "删除成功，请等待自动刷新，如果等不到请点击",
                buttonText: "这里",
                onButtonClick: function(){
                  window.location.href = "'.Helper::options()->adminUrl.'options-theme.php";
                }});
              </script>';
      }else{
        echo '<script language="JavaScript">mdui.snackbar({message: "删除失败，检测不到备份ㄟ( ▔, ▔ )ㄏ"})</script>';
      }
    }
  }
}
?>
