<?php
/**
 * Bangumi
 *
 * @package custom
 * @author Seirin, Bhao
 * @link https://owomoe.net/, https://dwd.moe/
 * @date 2023-07-19
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once(__DIR__ ."/libs/curl.php");

$bilibiliUser = $this->options->BilibiliUid;
$cacheTime = $this->options->CacheTime;
$amout = $this->options->Amout;
$hideMedia = $this->options->HideMedia;
$token = $this->options->Sessdata;

if ($bilibiliUser == "" || $bilibiliUser == null) {
  $bilibiliUser = '174471710';
}
if ($cacheTime == "" || $cacheTime == null) $cacheTime = 86400;
if ($amout == "" || $amout == null || $amout > 100) $amout = 10;

/**
 * 作为异步接口时的操作
 */
if (isset($_POST['post']) == true && $_POST['post'] == '1') {
  $dirPath = __DIR__ . '/assets/cache/BilibiliFollow';
  $filePath = $dirPath . '/BilibiliFollow.json';
  //检测缓存目录是否存在，不存在则创建
  if (is_dir($dirPath) == false) {
    mkdir($dirPath, 0777, true);
    $update = true;
  }
  //检测缓存文件是否存在，不存在则创建
  if (file_exists($filePath) == false) {
    fopen($filePath, "w");
    $update = true;
  }
  //若初步检查就要更新文件，则直接返回更新程序返回的缓存内容
  if ($update == true) {
    echo updateDate($bilibiliUser, $cacheTime, $amout, $hideMedia, $token);
    return;
  }

  //读取缓存文件
  $fileCache = fopen($filePath, "r");
  $contents = fread($fileCache, filesize($filePath));
  fclose($fileCache);
  $data = json_decode($contents, true);
  if (time() - $data['time'] > $cacheTime || $data['BilibiliUid'] != $bilibiliUser || $data['amout'] != $amout) {
    //缓存过期或B站UID更新或输出数量更新
    echo updateDate($bilibiliUser, $cacheTime, $amout, $hideMedia, $token);
    return;
  }
  echo $contents;
  return;
}

/**
 * 更新缓存数据
 * @param $userID
 * @param $cacheTime
 * @param $amout
 * @return mixed
 */
function updateDate($userID, $cacheTime, $amout, $hideMedia, $token) {
  $dirPath = __DIR__ . '/assets/cache/BilibiliFollow';
  $filePath = $dirPath . '/BilibiliFollow.json';
  //执行更新程序的前提是缓存数据文件都已经存在了

  $apiUrl = 'https://api.bilibili.com/x/space/bangumi/follow/list';
  $SESSDATA = ($token) ? 'Cookie: SESSDATA=' . $token . ';' : '';
  $header = [
    $SESSDATA,
    'Referer: https://space.bilibili.com/' . $userID . '/bangumi',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36'
  ];
  $getData = [
    'type'          => '1',
    'follow_status' => '0',
    'pn'            => '1',
    'ps'            => $amout,
    'ts'            => rand(9999999, 99999999),
    'vmid'          => $userID
  ];

  $data = curl::get($apiUrl, $getData, $header);
  $hideMedia = explode(",", $hideMedia);
  $data = json_decode($data, true);

  //开始苦逼的缓存图片

  foreach($data['data']['list'] as $key => $bangumi) {
    preg_match_all('/第(\d+)话/', $bangumi['progress'], $getProgress);
    if (in_array($bangumi['media_id'], $hideMedia)) {
      $display = '0';
    } else {
      $display = '1';
    }
    $array[] = [
      'name'     =>  $bangumi['title'],
      'id'       =>  $bangumi['media_id'],
      'cover'    =>  [
        'large'  => preg_replace('/http/', 'https', $bangumi['cover']),
        'square' => preg_replace('/http/', 'https', $bangumi['square_cover'])
      ],
      'url'      => $bangumi['url'],
      'type'     => $bangumi['season_type_name'],
      'area'     => $bangumi['areas'][0]['name'],
      'show'     => (isset($bangumi['new_ep']['index_show'])) ? $bangumi['new_ep']['index_show'] : '即将开播',
      'evaluate' => $bangumi['evaluate'],
      'display'  => $display,
      'progress' => ($bangumi['progress']) ? " | ".$bangumi['progress'] : ''
    ];
  }

  $bangumi_data[] = [
    'bangumi'     => $array,
    'time'        => time(),
    'BilibiliUid' => $userID,
    'amout'       => $amout,

  ];
  $bangumi_data = json_encode($bangumi_data);
  $file = fopen($filePath, "w");
  fwrite($file, $bangumi_data);
  fclose($file);

  return $bangumi_data;
}

$this->need('includes/header.php'); ?>
<div class="index-container">
  <div class="mdui-col-md-8 page-content">
    <div class="bangumi-page mdui-typo">
      <div class="mdui-card bangumi-panel-loading mdui-shadow-10" id="panel-loading">
        <p style="margin:0;">正在加载噢，请稍等qwq~</p>
      </div>
      <div class="mdui-card bangumi-panel-loading mdui-shadow-10" id="panel-fail"
           style="display: none;">
        <p style="margin:0;">加载失败，请刷新页面重试QAQ</p>
      </div>
      <script type="text/javascript">
        var bilibiliItemTemple = '<div class="mdui-card bangumi-panel mdui-shadow-10">\n' +
          '                            <div class="bangumi-img-dad">\n' +
          '                                <img src="{cover}" referrerpolicy="no-referrer" class="bangumi-img bangumi-img-pc">\n' +
          '                                <img src="{coverPe}" referrerpolicy="no-referrer" class="bangumi-img">\n' +
          '                            </div>\n' +
          '                            <div class="bangumi-content">\n' +
          '                                <a class="bangumi-title bangumi-t" href="https://www.bilibili.com/bangumi/media/md{media_id}" target="_blank">{title}</a>\n' +
          '                                <p class="bangumi-info">{season_type_name} | {areas_0_name} | {new_ep_index_show}{progress}</p>\n' +
          '                                <p class="bangumi-text">{evaluate}</p>\n' +
          '                            </div>\n' +
          '                    </div>';
        open = function () {
          var $ = mdui.$;
          var devContainer = $('.bangumi-page');
          var loadingContainer = $("#panel-loading");
          var errorContainer = $("#panel-fail");
          $.ajax({
            url: "<?=$_SERVER['REQUEST_URI']?>",
            async: true,
            method: 'POST',
            data: 'post=1',
            dataType: 'json',
            success: function (data) {
              loadingContainer.attr("style","display:none;");
              var list = data[0]['bangumi'];
              for (var i in list) {
                var now = list[i];
                if (now['display'] == '1') {
                  //匹配替换
                  var item = bilibiliItemTemple.replace("{title}", now['name'])
                                               .replace("{media_id}", now['id'])
                                               .replace("{evaluate}", now['evaluate'])
                                               .replace("{season_type_name}", now['type'])
                                               .replace("{areas_0_name}", now['area'])
                                               .replace("{new_ep_index_show}", now['show'])
                                               .replace("{cover}", now['cover']['large'])
                                               .replace("{coverPe}", now['cover']['square'])
                                               .replace("{progress}", now['progress']);
                  devContainer.append(item);
                }
              }
            },
            error: function () {
              loadingContainer.attr("style","display:none;");
              errorContainer.attr("style","display:block;");
            }
          });
        };
        window.onload = function () {
          open();
        }
      </script>
    </div>
    <?php $this->need('includes/comments.php'); ?>
  </div>
  <?php $this -> need('includes/sidebar.php'); ?>
</div>
<?php $this -> need('includes/footer.php'); ?>
