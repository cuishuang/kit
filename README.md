# kit
some useful tools for developer

<br>

### 查看数据库字段&索引简明信息的工具

#### 功能简介

[在线预览](http://db.dashen.tech/)

<br>

- 可以快速查看数据库中各表的字段名/字段类型/默认值及描述

- 可以快速查看各字段是否有索引及索引名称,类型等相关信息

- 可以快速查看每张表的行数

*目前支持mysql和pg*



<br>

#### 安装使用

所需环境:

- php 5.6以上

<br>

填充

```s
const HOST = "";
const DBNAME = "";
const USER = "";
const PASSWORD = "";
```

如果想绑定域名，或远程使用，可使用nginx反向代理


