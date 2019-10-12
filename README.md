# ZWebPHP 框架

设计目的：标准化、体验统一、简单可靠、易于扩展

## 后端PHP框架

* 基于`PHP`、`smarty` 构建。
* 基于`composer`自动加载。
* 完全基于API接口设计，API文档自动生成。
* 权限包含菜单访问权限和API接口访问权限。
* 工具类、数据库操作封装。
* 数据库设计使用 power designer

## 前端H5框架

* 兼容webkit内核最新浏览器，不兼容老式浏览器。
* 基于`jquery`、`Bootstrap4`、`layer 弹出层`
* 设计基于原始HTML5组件，自定义的表格和表单控件。
* 前后端交互标准封装，细节优化实现快速开发。
* 降低前端开发难度

## 使用方法

* 使用 `DB/create.sql` 创建mysql数据库
* 配置 `WEB/app/config.php` 数据库连接
* 配置 `WEB/` 为网站根目录
* 运行 `WEB/localhost-8000.bat` 启动网站
* 浏览器访问 `http://localhost:8000` 访问
* 默认用户：`super_admin` 密码：域名