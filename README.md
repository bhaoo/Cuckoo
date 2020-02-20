# Cuckoo

> 「Cuckoo」翻译为中文即为“布谷鸟”，其寓意是“不咕”～又因为咱叫布好，因此这个名字便诞生啦！接下来，将是对主题的具体介绍qwq！
>
> 「Cuckoo」主题演示站：[https://dwd.moe][3]

## 特点✨

### 图片懒加载

博客的封面显示使用了JQuery.Lazyload，仅当图片进入可视区域再进行加载，这样可以减少服务端的压力，可以让其按需加载。

### 文章目录树

<center><img src="https://cdn.jsdelivr.net/gh/bhaoo/Bhao-s-Blog@latest/images/uploads/2020/02/3301945240.png"/></center><center><img src="https://cdn.jsdelivr.net/gh/bhaoo/Bhao-s-Blog@latest/images/uploads/2020/02/1326135897.png"/></center>

在“文章编辑->自定义字段->文章目录”中，可按需开启或关闭文章的目录树哦～

### 用户UA以及仿Biibili认证


<center><img src="https://cdn.jsdelivr.net/gh/bhaoo/Bhao-s-Blog@latest/images/uploads/2020/02/186469640.png"/></center>

黄色小闪电：代表的是自己的好朋友～

蓝色小闪电：代表的是自己哦～

另外在名字后面的即是用户UA的显示！

### 静态资源优化

当静态资源获得CDN的BUFF加成时，网站的速度也会增快。因此咱也把静态文件丢到了 [jsDeliver](https://www.jsdelivr.com/) 上，在模版设置页面中可以进行设置，对于自己准备好了CDN的小伙伴可以在“自定义CDN源”处填写好即可哦～

### 评论表情

<center><img src="https://cdn.jsdelivr.net/gh/bhaoo/Bhao-s-Blog@latest/images/uploads/2020/02/856444410.png"/></center>

在“发表评论”的右上角点击即可弹出表情选择框，目前支持的有”Emoji“、“颜文字”、“贴吧”等，详细的大家可以亲自去看看，顺便别忘了评论一下哦（嘿嘿

### 奇妙的评论输入框

<center><img src="https://cdn.jsdelivr.net/gh/bhaoo/Bhao-s-Blog@latest/images/uploads/2020/02/3943972505.png"/></center>

看到右下角的书记了嘛！之后咱会再做一些别的（只要咱布谷23333

### [更新] 友人帐

咱专门给“友人帐”做了独立页面，需要的小伙伴请撰写一个独立页面（内容可为空），把“自定义模版”设置成“Links”并且在高级设置里把“公开度”设置为“隐藏”！最后在“模版设置”中的“自定义导航栏”填写以下内容：

```
[{
 "name":"友人帐",
 "link":"填写为你的独立页面地址",
 "icon":"links",
 "type":"0"
}]
```



**注意⚠️：使用“友人帐”需要安装“Links”插件，否则就会出现报错（咚咚咚**

### PJAX & AJAX

主题已使用全站PJAX，也实现了AJAX评论，优化用户体验，也优化了网站速度。进度条使用的是“NProgress”，我尽量的修改让它向框架靠近？？？如果在使用期间出现BUG欢迎通过下文向我提出！

### 联系方式

联系方式目前支持QQ、Github、Telegram、Twitter、Bilibili、Email，后续会根据需求逐步添加，添加的例子：（最多添加5个，多余的无法显示哦～）

{"qq":"xxxxxxx", "github":"xxxxxxx", "telegram":"xxxxxxx", "twitter":"xxxxxxx",
"bilibili":"xxxxxxx","email":"xxxxxxx"}

## 写在最后

- 以上的介绍还不完整，有其他问题可以群里找咱！文档正在肝啦！
- **模版QQ群#1054724682**
- **主题食用文档正在编写，稍安勿躁哦～**
- **删除版权者无权进行提问，保留版权是对作者的尊重**


[1]: https://ohmyga.cn/
[2]: https://qwq.best/
[3]:https://dwd.moe
