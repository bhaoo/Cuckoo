<?php
/**
 * B站追番列表
 *
 * @package custom
 * @author Seirin
 * @link https://qwq.best/
 * @version 1.0.0
 */


$bilibiliUser = $this->options->BilibiliUid;
$cacheTime = $this->options->CacheTime;
$amout = $this->options->Amout;
$hideMedia = $this->options->HideMedia;

//没有报错就不会有BUG
error_reporting(E_ALL ^ E_NOTICE);
if ($bilibiliUser == "" || $bilibiliUser == null) {
    $bilibiliUser = '174471710';
}
if ($cacheTime == "" || $cacheTime == null) $cacheTime = 86400;
if ($amout == "" || $amout == null || $amout > 100) $amout = 100;

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
        echo updateDate($bilibiliUser, $cacheTime, $amout, $hideMedia);
        return;
    }

    //读取缓存文件
    $fileCache = fopen($filePath, "r");
    $contents = fread($fileCache, filesize($filePath));
    fclose($fileCache);
    $data = json_decode($contents, true);
    if (time() - $data['time'] > $cacheTime || $data['BilibiliUid'] != $bilibiliUser || $data['amout'] != $amout) {
        //缓存过期或B站UID更新或输出数量更新
        echo updateDate($bilibiliUser, $cacheTime, $amout, $hideMedia);
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
function updateDate($userID, $cacheTime, $amout, $hideMedia)
{
    $dirPath = __DIR__ . '/assets/cache/BilibiliFollow';
    $filePath = $dirPath . '/BilibiliFollow.json';
    //执行更新程序的前提是缓存数据文件都已经存在了
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.bilibili.com/x/space/bangumi/follow/list?type=1&follow_status=0&pn=1&ps=" . $amout . "&ts=" . rand(9999999, 99999999) . "&vmid=" . $userID);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Origin: https://space.bilibili.com", "Referer: https://space.bilibili.com/" . $userID . "/bangumi"));
    $output = curl_exec($ch);
    curl_close($ch);

    $hideMedia = explode(",", $hideMedia);
    $data = json_decode($output, true);

    $i = 0;
    //开始苦逼的缓存图片
    foreach ($data['data']['list'] as $value) {
        $imgUrl = $value['cover'];
        $imgPath = $dirPath . '/' . $value['media_id'] . '.jpg';

        if (file_exists($imgPath) == false) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $imgUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Origin: https://space.bilibili.com", "Referer: https://space.bilibili.com/" . $userID . "/bangumi"));
            $file_content = curl_exec($ch);
            curl_close($ch);
            $img_file = fopen($imgPath, 'w');
            fwrite($img_file, $file_content);
            fclose($img_file);
        }

        if (in_array($value['media_id'], $hideMedia)) {
            $display = '0';
        } else {
            $display = '1';
        }
        $data['data']['list'][$i]['display'] = $display;
        $i++;
    }

    $data['time'] = time();
    $data['BilibiliUid'] = $userID;
    $data['amout'] = $amout;

    $data = json_encode($data);
    $file = fopen($filePath, "w");
    fwrite($file, $data);
    fclose($file);

    return $data;
}

?>

<?php $this->need('includes/header.php'); ?>
    <div class="container">
        <?php $this->need('includes/sidebar.php'); ?>
        <div class="left">


            <style>
                .seirin-bilibili-follow-panel-all {
                    margin-top: 20px;
                }

                .seirin-bilibili-page {
                    /*margin-bottom: 20px;*/
                }

                .seirin-bilibili-follow-panel {
                    padding: 0 !important;
                    border-radius: 10px;
                    background-color: rgba(255, 255, 255, 0.8);
                    transition: all .3s;
                    margin-bottom: 20px;
                }

                .seirin-bilibili-follow-panel:hover {
                    background-color: rgba(255, 255, 255, 0.93);
                    transition: all .3s;
                }

                .seirin-bilibili-follow-panel-loading {
                    padding: 10px !important;
                    border-radius: 10px;
                    background-color: rgba(255, 255, 255, 0.8);
                    margin-bottom: 20px;
                }

                .seirin-bilibili-follow-img-dad {
                    padding-right: 2px;
                    max-width: 145px !important;
                }

                .seirin-bilibili-follow-img {
                    width: 95%;
                    max-width: 140px !important;
                    margin: 0 !important;
                    padding: 0 !important;

                }

                .seirin-bilibili-follow-content {
                    padding-top: 15px;
                    padding-right: 20px;
                    padding-left: 10px;
                    padding-bottom: 15px;
                }

                .seirin-bilibili-follow-title {
                    font-weight: 400;
                    line-height: 24px;
                    font-size: 22px;
                    margin-bottom: 5px !important;
                    margin-top: 2px !important;

                }

                .seirin-bilibili-follow-text {
                    margin-bottom: 10px !important;
                    font-size: 14px;
                }

                .seirin-bilibili-follow-info {
                    font-size: 12px;
                    margin-bottom: 0 !important;
                }
                @media screen and (max-width:1016px) {
                    .seirin-bilibili-follow-img {
                        display:none;
                    }
                    .seirin-bilibili-follow-content {
                        padding-left:25px;
                        padding-top:10px;
                        width: 100%!important;
                    }
                }
                @media screen and (max-width:900px) {
                    .seirin-bilibili-follow-img {
                        display:block;
                    }
                    .seirin-bilibili-follow-content {
                        padding-top: 15px;
                        padding-right: 20px;
                        padding-left: 10px;
                        padding-bottom: 15px;
                        width: 75%!important;
                    }
                }
                @media screen and (max-width:706px) {
                    .seirin-bilibili-follow-img {
                        display:none;
                    }
                    .seirin-bilibili-follow-content {
                        padding-left:25px;
                        padding-top:10px;
                        width: 100%!important;
                    }
                }
            </style>


            <div class="seirin-bilibili-follow-panel-all mdui-typo">
                <div class="seirin-bilibili-page">

                    <div class="mdui-card seirin-bilibili-follow-panel-loading mdui-shadow-10" id="panel-loading">
                        <p style="margin:0;">正在加载噢，请稍等qwq~</p>
                    </div>

                    <div class="mdui-card seirin-bilibili-follow-panel-loading mdui-shadow-10" id="panel-fail"
                         style="display: none;">
                        <p style="margin:0;">加载失败，请刷新页面重试QAQ</p>
                    </div>
                    <script type="text/javascript">
                        var bilibiliItemTemple = '<div class="mdui-card seirin-bilibili-follow-panel mdui-shadow-10">\n' +
                            '                        <div class="mdui-row">\n' +
                            '                            <div class="mdui-col-xs-3 seirin-bilibili-follow-img-dad">\n' +
                            '                                <img id="img" src="//<?=$_SERVER['SERVER_NAME']?>/usr/themes/Cuckoo/assets/cache/BilibiliFollow/{media_id}.jpg" alt="{title}" class="seirin-bilibili-follow-img block">\n' +
                            '                            </div>\n' +
                            '\n' +
                            '                            <div class="mdui-col-xs-9 seirin-bilibili-follow-content">\n' +
                            '                                <a class="seirin-bilibili-follow-title" href="https://www.bilibili.com/bangumi/media/md{media_id}" target="_blank">{title}</a>\n' +
                            '                                <p class="seirin-bilibili-follow-text">{evaluate}</p>\n' +
                            '                                <p class="seirin-bilibili-follow-info">{season_type_name} | {areas_0_name} | {new_ep_index_show}</p>\n' +
                            '                            </div>\n' +
                            '\n' +
                            '                        </div>\n' +
                            '                    </div>';

                        open = function () {
                            var $ = jQuery;
                            var devContainer = $('.seirin-bilibili-page');
                            var loadingContainer = $("#panel-loading");
                            var errorContainer = $("#panel-fail");
                            $.ajax({
                                url: "<?=$_SERVER['REQUEST_URI']?>",
                                async: true,
                                type: 'POST',
                                data: 'post=1',
                                dataType: 'json',
                                success: function (data) {
                                    loadingContainer.attr("style","display:none;");

                                    var list = data['data']['list'];
                                    for (var i in list) {
                                        var now = list[i];

                                        if (now['display'] == '1') {
                                            //匹配替换
                                            var item = bilibiliItemTemple.replace("{title}", now['title']).replace("{title}", now['title'])
                                                .replace("{media_id}", now['media_id'])
                                                .replace("{media_id}", now['media_id'])
                                                .replace("{evaluate}", now['evaluate'])
                                                .replace("{season_type_name}", now['season_type_name'])
                                                .replace("{areas_0_name}", now['areas'][0]['name'])
                                                .replace("{new_ep_index_show}", now['new_ep']['index_show']);
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
            </div>

            <?php $this->need('includes/comments.php'); ?>
        </div>
    </div>
<?php $this->need('includes/footer.php'); ?>