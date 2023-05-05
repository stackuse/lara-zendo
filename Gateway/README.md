# Gateway 网关组件

基于 kong 的网关组件

## 主要作用

- 认证 Authentication
  验证用户身份的过程，通常是基于用户提供的凭证（如用户名和密码）来确定用户是否有权访问系统
  guard，passport

- 授权 Authorization
  确定用户是否有权访问系统资源的过程
  policy，gate,permit


鉴权流程：
- 认证 client:xxx



* 用户登录
* 用户授权
* 用户鉴权

* id：用户ID
* role：角色
* scopes：权限范围

X-Authenticated-Userid

* Authorized 批准，授权
* Authenticated 认证，验证

* Gate 认证控制

## 权限控制思路

* Action 动作访问控制（RBAC）
  通过route生成 + middleware实现

* Object 对象访问控制（更细的，同一对象下不同的条目的访问控制）
  通过model的scope控制

## 参考：

https://casbin.org/docs/zh-CN/rbac
https://juejin.cn/post/6941734947551969288
https://juejin.cn/post/6951712306598248485

https://help.aliyun.com/document_detail/93740.html

$pass

user_id string
roles array
actions array
