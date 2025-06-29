<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 *
 * Comments Libs
 *
 * @author Bhao
 * @link https://dwd.moe/
 * @date 2023-12-09
 */

use Typecho\Config;
use Typecho\Cookie;
use Typecho\Db;
use Typecho\Router;
use Typecho\Widget\Exception;
use Typecho\Widget\Helper\PageNavigator\Box;
use Utils\Helper;
use Widget\Comments\Archive;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 基本按照 Typecho 评论组件而来

/**
 * 评论归档组件
 *
 * 由于不继承 \Widget\Base\Comments 会导致 1.3.0 和 1.2.0 版本的 ___parentContent 方法返回类型不一致 (1.3.0 返回 \Widget\Base\Contents，1.3.0 以下返回数组)
 * 继承又会因为基本都要覆写 (因为有大量 protected 方法，若需调用子类 private 变量则需要进行覆写)
 * 没办法了，为了兼容新老版本只能继承 + 覆写了口牙! (这样就能不管 ___parentContent 方法到底给我了啥)
 *
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
class Cuckoo_Comments_Archive extends Archive {
  /**
   * 当前页
   *
   * @access private
   * @var integer
   */
  private int $currentPage;

  /**
   * 所有文章个数
   *
   * @access private
   * @var integer
   */
  private int $total = 0;

  /**
   * 子父级评论关系
   *
   * @access private
   * @var array
   */
  private array $threadedComments = [];

  /**
   * _singleCommentOptions
   *
   * @var Config|null
   * @access private
   */
  private ?Config $singleCommentOptions = NULL;

  /**
   * @param Config $parameter
   */
  protected function initParameter(Config $parameter) {
    $parameter->setDefault([
      'parentId' => 0,
      'respondId' => '',
      'commentPage' => 0,
      'commentsNum' => 0,
      'allowComment' => 1,
      'parentContent' => null,
    ]);
  }

  /**
   * 判断 Typecho 版本号
   *
   * @access public
   * @return bool 提取到的版本号是否大于或等于 1.3.0
   */
  public static function isTypechoVersion(): bool {
    $version = "1.0.0";

    if (preg_match('/Typecho\s+([\d.]+)/i', Helper::options()->generator, $matches)) {
      $version = $matches[1];
    }

    return (bool)version_compare($version, '1.3.0', '>=');
  }

  /**
   * 评论回调函数
   *
   * @access private
   * @return void
   * @throws Db\Exception
   */
  private function threadedCommentsCallback(): void {
    $singleCommentOptions = $this->singleCommentOptions;
    if (function_exists('threadedComments')) {
      threadedComments($this, $singleCommentOptions);
      return;
    }

    $commentClass = '';
    if ($this->authorId) {
      if ($this->authorId == $this->ownerId) {
        $commentClass .= ' comment-by-author';
      } else {
        $commentClass .= ' comment-by-user';
      }
    } ?>
    <div id="<?php $this->theId(); ?>" class="
      <?php if ($this->levels > 0) {
        echo ' comment-child';
        $this->levelsAlt(' comment-level-odd', ' comment-level-even');
      } else {
        echo ' comment-parent mdui-card comment-card';
      }
      $this->alt(' comment-odd', ' comment-even');
      echo $commentClass; ?>">
      <div class="comment-image">
        <img class="mdui-img-circle" src="<?php get_comment_avatar($this->mail); ?>" loading="lazy" alt="commenter-avatar" />
        <?php get_comment_prefix($this->mail);
        if ($this->authorId == $this->ownerId) { ?>
          <img src="<?php staticFiles('images/author.png') ?>" class="comment-prefix" mdui-tooltip="{content: '博主'}" alt="author-prefix" />
        <?php } ?>
      </div>
      <div class="mdui-card-header-title mdui-typo">
        <?php if (!empty($this->url)) {
          echo '<a href="' . $this->url . '" target="_blank">' . $this->author . '</a>';
        } else {
          echo '<span>' . $this->author . '</span>';
        }
        getBrowser($this->agent);
        getOs($this->agent); ?>
      </div>
      <div class="mdui-card-header-subtitle"><?php $this->date($singleCommentOptions->dateFormat); ?>
        <?php if ('waiting' == $this->status) { ?>
          <br>
          <div class="mdui-text-color-red-400 comment-waiting" style="font-weight:bold;">您的评论正在等待审核</div>
        <?php } ?>
      </div>
      <div class="mdui-card-menu comment-reply-box">
        <?php $this->reply('<button class="mdui-btn mdui-btn-dense mdui-ripple comment-reply mdui-text-color-theme-accent">回复</button>'); ?>
      </div>
      <div class="comment-content mdui-typo">
        <?php $this->commentsReply();
        echo preg_replace('#</?[p][^>]*>#', '', parseBiaoQing($this->content)); ?>
      </div>
      <?php if ($this->children) { ?>
        <div class="comment-children" itemprop="discusses">
          <?php $this->threadedComments(); ?>
        </div>
      <?php } ?>
    </div>
<?php
  }

  /**
   * 获取回复者名称
   *
   * @access private
   * @return void
   * @throws Db\Exception
   */
  private function commentsReply(): void {
    $db = Db::get();
    $parentID = $db->fetchRow($db->select('parent')->from('table.comments')->where('coid = ?', $this->coid));
    $parentID = $parentID['parent'];
    if ($parentID == '0') {
      return;
    } else {
      $author = $db->fetchRow($db->select()->from('table.comments')->where('coid = ?', $parentID));
      $link = 'href="#comment-' . $author['coid'] . '"';
      //如果是删除的评论
      if (!array_key_exists('author', $author) || empty($author['author'])) {
        $author['author'] = '已删除的评论';
        $link = '';
      }
      if (!$link) {
        echo '<a class="comment-reply-name" no-go>@' . $author['author'] . '</a> ';
      } else {
        echo '<a ' . $link . ' class="comment-reply-name" no-go>@' . $author['author'] . '</a> ';
      }
      //echo '<span class="comment-reply-name">@'.$author['author'].'</span>';
    }
  }

  /**
   * 获取当前评论链接
   * (Typecho 1.3.0 已移除该方法)
   *
   * @access protected
   * @return string
   */
  protected function ___permalink() : string {

    if ($this->options->commentsPageBreak) {
      $pageRow = array('permalink' => $this->parentContent['pathinfo'], 'commentPage' => $this->_currentPage);
      return Typecho_Router::url(
        'comment_page',
        $pageRow,
        $this->options->index
      ) . '#' . $this->theId;
    }

    return $this->parentContent['permalink'] . '#' . $this->theId;
  }

  /**
   * 子评论
   *
   * @access protected
   * @return array
   */
  protected function ___children(): array {
    return $this->options->commentsThreaded && !$this->isTopLevel && isset($this->threadedComments[$this->coid])
      ? $this->threadedComments[$this->coid] : [];
  }

  /**
   * 是否到达顶层
   *
   * @access protected
   * @return boolean
   */
  protected function ___isTopLevel(): bool {
    return $this->levels > $this->options->commentsMaxNestingLevels - 2;
  }

  /**
   *
   */
  }

  /**
   * 输出文章评论数
   *
   * @access public
   * @param mixed ...$args
   * @return void
   */
    $args = func_get_args();
    if (!$args) {
  public function num(...$args) {
    if (empty($args)) {
      $args[] = '%d';
    }

    $num = intval($this->_total);

    echo sprintf(isset($args[$num]) ? $args[$num] : array_pop($args), $num);
  }

  /**
   * 执行函数
   *
   * @access public
   * @return void
   * @throws Db\Exception
   */
  public function execute() {
    if (!$this->parameter->parentId) {
      return;
    }

    $commentsAuthor = Typecho_Cookie::get('__typecho_remember_author');
    $commentsMail = Typecho_Cookie::get('__typecho_remember_mail');
    $select = $this->select()->where('table.comments.cid = ?', $this->parameter->parentId)
      ->where('table.comments.status = ? OR (table.comments.author = ? AND table.comments.mail = ? AND table.comments.status = ?)', 'approved', $commentsAuthor, $commentsMail, 'waiting');
    $threadedSelect = NULL;

    if ($this->options->commentsShowCommentOnly) {
      $select->where('table.comments.type = ?', 'comment');
    }

    $select->order('table.comments.coid', 'ASC');
    $this->db->fetchAll($select, array($this, 'push'));

    /** 需要输出的评论列表 */
    $outputComments = [];

    /** 如果开启评论回复 */
    if ($this->options->commentsThreaded) {

      foreach ($this->stack as $coid => &$comment) {

        /** 取出父节点 */
        $parent = $comment['parent'];

        /** 如果存在父节点 */
        if (0 != $parent && isset($this->stack[$parent])) {

          /** 如果当前节点深度大于最大深度, 则将其挂接在父节点上 */
          if ($comment['levels'] >= $this->options->commentsMaxNestingLevels) {
            $comment['levels'] = $this->stack[$parent]['levels'];
            $parent = $this->stack[$parent]['parent'];     // 上上层节点
            $comment['parent'] = $parent;
          }

          /** 计算子节点顺序 */
          $comment['order'] = isset($this->_threadedComments[$parent])
            ? count($this->_threadedComments[$parent]) + 1 : 1;

          /** 如果是子节点 */
          $this->threadedComments[$parent][$coid] = $comment;
        } else {
          $outputComments[$coid] = $comment;
        }
      }

      $this->stack = $outputComments;
    }

    /** 评论排序 */
    if ('DESC' == $this->options->commentsOrder) {
      $this->stack = array_reverse($this->stack, true);
      $this->threadedComments = array_map('array_reverse', $this->threadedComments);
    }

    /** 评论总数 */
    $this->total = count($this->stack);

    /** 对评论进行分页 */
    if ($this->options->commentsPageBreak) {
      if ('last' == $this->options->commentsPageDisplay && !$this->parameter->commentPage) {
        $this->currentPage = ceil($this->total / $this->options->commentsPageSize);
      } else {
        $this->currentPage = $this->parameter->commentPage ? $this->parameter->commentPage : 1;
      }

      /** 截取评论 */
      $this->stack = array_slice(
        $this->stack,
        ($this->currentPage - 1) * $this->options->commentsPageSize,
        $this->options->commentsPageSize
      );

      /** 评论置位 */
      $this->length = count($this->stack);
      $this->row = $this->length > 0 ? current($this->stack) : array();
    }

    reset($this->stack);
  }

  /**
   * 将每行的值压入堆栈
   *
   * @access public
   * @param array $value 每行的值
   * @return array
   */
  public function push(array $value) : array {
    $value = $this->filter($value);

    /** 计算深度 */
    if (0 != $value['parent'] && isset($this->stack[$value['parent']]['levels'])) {
      $value['levels'] = $this->stack[$value['parent']]['levels'] + 1;
    } else {
      $value['levels'] = 0;
    }

    /** 重载push函数,使用coid作为数组键值,便于索引 */
    $this->stack[$value['coid']] = $value;
    $this->length++;

    return $value;
  }

  /**
   * 输出分页
   *
   * @access public
   * @param string       $prev      上一页文字
   * @param string       $next      下一页文字
   * @param int          $splitPage 分割范围
   * @param string       $splitWord 分割字符
   * @param string|array $template  展现配置信息
   * @return void
   * @throws Exception
   */
    if ($this->options->commentsPageBreak && $this->_total > $this->options->commentsPageSize) {
      $default = array(
        'wrapTag'       =>  'ol',
        'wrapClass'     =>  'page-navigator'
      );
  public function pageNav(string $prev = '&laquo;', string $next = '&raquo;', int $splitPage = 3, string $splitWord = '...', $template = '') {

      if (is_string($template)) {
        parse_str($template, $config);
      } else {
        $config = $template ?: [];
      }

      $template = array_merge($default, $config);

      $pageRow = $this->parameter->parentContent;
      $pageRow['permalink'] = $pageRow['pathinfo'];

      $query = Typecho_Router::url('comment_page', $pageRow, $this->options->index);

      /** 使用盒状分页 */
      $nav = new Typecho_Widget_Helper_PageNavigator_Box(
        $this->_total,
        $this->_currentPage,
        $this->options->commentsPageSize,
        $query
      );
      $nav->setPageHolder('commentPage');
      $nav->setAnchor('comments');

      echo '<' . $template['wrapTag'] . (empty($template['wrapClass'])
        ? '' : ' class="' . $template['wrapClass'] . '"') . '>';
      $nav->render($prev, $next, $splitPage, $splitWord, $template);
      echo '</' . $template['wrapTag'] . '>';
    }
  }

  /**
   * 递归输出评论
   *
   * @access public
   * @return void
   * @throws Db\Exception
   */
  public function threadedComments() {
    $children = $this->children;
    if ($children) {
      //缓存变量便于还原
      $tmp = $this->row;
      $this->sequence++;

      //在子评论之前输出
      echo $this->singleCommentOptions->before;

      foreach ($children as $child) {
        $this->row = $child;
        $this->threadedCommentsCallback();
        $this->row = $tmp;
      }

      //在子评论之后输出
      echo $this->singleCommentOptions->after;

      $this->sequence--;
    }
  }

  /**
   * 列出评论
   *
   * @access public
   * @param mixed $singleCommentOptions 单个评论自定义选项
   * @return void
   * @throws Db\Exception
   */
  public function listComments($singleCommentOptions = NULL)
  {
    //初始化一些变量
    $this->singleCommentOptions = Config::factory($singleCommentOptions);
    $this->singleCommentOptions->setDefault([
      'before'        =>  '',
      'after'         =>  '',
      'beforeAuthor'  =>  '',
      'afterAuthor'   =>  '',
      'beforeDate'    =>  '',
      'afterDate'     =>  '',
      'dateFormat'    =>  'Y-m-d H:i',
      'replyWord'     =>  _t('回复'),
      'commentStatus' =>  _t('您的评论正等待审核!'),
      'avatarSize'    =>  32,
      'defaultAvatar' =>  NULL
    ));
    self::pluginHandle()->trigger($plugged)->call('listComments', $this->singleCommentOptions, $this);

    if (!$plugged) {
      if ($this->have()) {
        echo $this->singleCommentOptions->before;

        while ($this->next()) {
          $this->threadedCommentsCallback();
        }

        echo $this->singleCommentOptions->after;
      }
    }
  }

  /**
   * 重载alt函数,以适应多级评论
   *
   * @access public
   * @return void
   */
  public function alt(...$args)
  {
    $args = func_get_args();
    $num = func_num_args();

    $sequence = $this->levels <= 0 ? $this->sequence : $this->order;

    $split = $sequence % $num;
    echo $args[(0 == $split ? $num : $split) - 1];
  }

  /**
   * 根据深度余数输出
   *
   * @access public
   * @param string $param 需要输出的值
   * @return void
   */
  public function levelsAlt()
  {
    $args = func_get_args();
    $num = func_num_args();
    $split = $this->levels % $num;
    echo $args[(0 == $split ? $num : $split) - 1];
  }

  /**
   * 评论回复链接
   *
   * @access public
   * @param string $word 回复链接文字
   * @return void
   */
  public function reply(string $word = '') {
    if ($this->options->commentsThreaded && !$this->isTopLevel && $this->parameter->allowComment) {
      $word = empty($word) ? _t('回复') : $word;
      $this->pluginHandle()->trigger($plugged)->reply($word, $this);

      if (!$plugged) {
        // TypechoComment 位于 comments.min.js
        echo '<a href="' . substr($this->permalink, 0, -strlen($this->theId) - 1) . '?replyTo=' . $this->coid .
          '#' . $this->parameter->respondId . '" rel="nofollow" onclick="return TypechoComment.reply(\'' .
          $this->theId . '\', ' . $this->coid . ');" no-pjax>' . $word . '</a>';
      }
    }
  }

  /**
   * 取消评论回复链接
   *
   * @access public
   * @param string $word 取消回复链接文字
   * @return void
   */
  public function cancelReply(string $word = '') {
    if ($this->options->commentsThreaded) {
      $word = empty($word) ? _t('取消回复') : $word;
      $this->pluginHandle()->trigger($plugged)->cancelReply($word, $this);

      if (!$plugged) {
        $replyId = $this->request->filter('int')->replyTo;
        echo '<a id="cancel-comment-reply-link" href="' . $this->parameter->parentContent['permalink'] . '#' . $this->parameter->respondId .
          '" rel="nofollow"' . ($replyId ? '' : ' style="display:none"') . ' onclick="return TypechoComment.cancelReply();">' . $word . '</a>';
        // TypechoComment 位于 comments.min.jst
      }
    }
  }

  /**
   * 评论反垃圾
   *
   * @access public
   */
  public static function AntiSpam($comment) {
    echo '<!--<nocompress>-->';
    echo '<script>(function(){var a=document.addEventListener?{add:"addEventListener",focus:"focus",load:"DOMContentLoaded"}:{add:"attachEvent",focus:"onfocus",load:"onload"};var c,d,e,f,b=document.getElementById("' . $comment->respondId . '");null!=b&&(c=b.getElementsByTagName("form"),c.length>0&&(d=c[0],e=d.getElementsByTagName("textarea")[0],f=!1,null!=e&&"text"==e.name&&e[a.add](a.focus,function(){if(!f){var a=document.createElement("input");a.type="hidden",a.name="_",d.appendChild(a),f=!0,a.value=' . Typecho_Common::shuffleScriptVar($comment->security->getToken($comment->request->getRequestUrl())) . '}})))})();</script>';
    echo '<!--</nocompress>-->';
  }
}
