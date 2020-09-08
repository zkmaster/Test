# BlogService

本项目由lumen58开发 <br>
>@author: CooperZhang

**--------------------------------------------------------------------------------------------------------------**<br>
## 项目描述

**--------------------------------------------------------------------------------------------------------------**<br>
## 开发规范

### 一、文件目录
**------------------------------------------------------**<br>

* app ：应用程序的核心代码。
* .env ：项目配置文件。

**------------------------------------------------------**<br>

### 二、代码风格
**------------------------------------------------------**<br>

* 代码风格 必须 严格遵循 [PSR-2](https://www.php-fig.org/psr/psr-2/) 规范。

**------------------------------------------------------**<br>

### 三、数据库设计与使用规范
**------------------------------------------------------**<br>
#### 设计
* 编码统一使用 `utf8mb4`。

* 每张表都需要 id 自动增长。

* 时间字段统一使用 `int` (create_time, update_time)。

* 状态统一 `tinyint` 类型。

* 索引添加可以根据实际业务进行优化。

* 数据查询尽量不要联表查询。

* 同时更新两张以上数据表 需添加事务。

* 完善表字段与表注释。

* 字段都不可以为 `null`，`int` 一般默认为 0 ，状态根据实际业务设置默认值，`varchar` 默认统一使用 `EMPTY STRING`。

#### 使用

* 不建议使用 `laravel` 内置 `Model`。

* 不建议使用连表查询。（管理后台可酌情使用）。

* 

**------------------------------------------------------**<br>
### 四、缓存使用
**------------------------------------------------------**<br>
#### Redis

* 必须设置密码、禁用 `flushall` 等危险命令。

* 以英文字母开头，命名中只能出现小写字母、数字、英文点号 `.` 和英文半角冒号 `:`。

* 数据类型以业务为准。

* 若业务类型多，按业务分库。

* 以 英文字符 `:` 为分隔符、以 `.` 为单词链接符。

* 键值单词尽量精简，可以使用缩写。

**------------------------------------------------------**<br>
