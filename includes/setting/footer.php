<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 *
 * Setting Footer
 *
 * @author Bhao
 * @link https://dwd.moe/
 * @date 2023-11-26
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
</div>
</body>

<script src="<?php staticFiles('js/mdui.min.js') ?>"></script>
<script src="<?php staticFiles('js/setting.min.js') ?>"></script>
<script>const isRunUrl=function(url){return new Promise(function(resolve,reject){const ele=document.createElement('link');ele.href=url;ele.rel='stylesheet';document.head.appendChild(ele);ele.onload=function(){document.head.removeChild(ele);resolve()};ele.onerror=reject})};window.onload=()=>{isRunUrl("<?php staticFiles('css/cuckoo.min.css') ?>").then(null,function(){const form=document.getElementById("cuckoo-form");const elements=form.elements;const formData=[];for(let i=0;i<elements.length;i++){const element=elements[i];if(element.name){const item={name:element.name,value:element.value};if(item.name==="staticFiles"){item.value="local"}formData.push(item)}}$.ajax({method:'POST',url:form.action,data:formData,error:function(){console.log("错误")},success:function(){alert("检测到静态文件未正常加载, 已自动将静态文件源修改为本地, 点击确认后将刷新页面");setTimeout(function(){location.reload()},1000)}})})}</script>