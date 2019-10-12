<?php
// 自动生成于 2019-10-11 16:13:59
namespace app;

class EnumConst
{
	// 状态
	const STATUS_DISABLED = 0;	// 禁用
	const STATUS_NORMAL = 1;	// 正常
	const STATUS_DELETED = 2;	// 删除

	// 返回状态
	const RESULT_EXCEPTION = -90;	// 处理异常
	const RESULT_POWER = -31;	// 无权限
	const RESULT_OFFLINE = -30;	// 未登录
	const RESULT_PARAM = -20;	// 参数缺失
	const RESULT_VIEW = -11;	// view页面不存在
	const RESULT_API = -10;	// 接口不存在
	const RESULT_FAIL = -1;	// 操作失败
	const RESULT_ERROR = 0;	// 错误
	const RESULT_SUCCESS = 1;	// 成功
	const RESULT_CUSTOM = 2;	// 自定义返回

	// 在线状态
	const ONLINE_STATUS_NULL = 0;	// 未知
	const ONLINE_STATUS_ONLINE = 1;	// 在线
	const ONLINE_STATUS_OFFLINE = 2;	// 离线
	const ONLINE_STATUS_CANCEL = 3;	// 作废

	// 客户类型
	const CLIENT_TYPE_PERSON = 1;	// 个人
	const CLIENT_TYPE_COMPANY = 2;	// 公司
	const CLIENT_TYPE_GROUP = 3;	// 集团

}