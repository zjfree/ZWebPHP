/// 系统基础JS zjf@2018-09-28
console.time("加载时间");
var Z = {

    // 自动ID编号
    idIndex:0,

    createId:function(){
        this.idIndex++;
        return 'zid_' + this.idIndex;
    },

    // 配置
    config:{
        isDebug:false,  // 是否开启调试
        tableFixTop:0,  // 表头固定距顶距离
        navMenuId:'NavMenu_index', // 当前激活的导航菜单编号
        navBreadcrumb:'', // 面包屑名称
        browser:'pc',
		ajaxTimeout:5000,
    },

    // 调试输出
    debug:function(s){
        if (!this.config.isDebug) return;
        console.info('DEBUG', s);
    },

    // 初始化
    init:function(){
        console.timeEnd("加载时间");
        console.time("JS启动时间");
        // bootscript 初始化
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();

        // 导航菜单二级控制
        $('#' + Z.config.navMenuId).addClass('active');
        $('.nav-menu .level-1>a').each(function(){
            $(this).append('<span class="nav-next"><i class="fa fa-angle-down"></i></span>');
        }).click(function(){
            if ($(this).parent().hasClass('open'))
            {
                $(this).parent().removeClass('open');
                $(this).find('.nav-next').html('<i class="fa fa-angle-down"></i>');
            }
            else
            {
                $(this).parent().addClass('open');
                $(this).find('.nav-next').html('<i class="fa fa-angle-up"></i>');
            }
        });

        // 构建面包屑
        var curNav = $('#' + Z.config.navMenuId);
        if (curNav.length > 0)
        {
            document.title = curNav.text() + ' | ' + document.title;

            var breadcrumbActive = Z.config.navBreadcrumb == '' ? 'active' : '';
            $('.z-breadcrumb .breadcrumb li').remove();
            if (Z.config.browser == 'mobile')
            {
                $('.z-breadcrumb .breadcrumb').append('<li class="breadcrumb-item"><a href="/index.php?_=sys::phone_index"><i class="fa fa-home"></i>首页</a></li>');
            }
            else
            {
                $('.z-breadcrumb .breadcrumb').append('<li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i>首页</a></li>');
            }
            if (curNav.attr('id') == 'NavMenu_index')
            {
                // 首页菜单
            }
            else if (curNav.parent().attr('id') == 'divMainMenu')
            {
                // 顶级菜单
                $('.z-breadcrumb .breadcrumb').append('<li class="breadcrumb-item ' + breadcrumbActive + '"><a href="' + curNav.attr('href') + '">' + curNav.html() + '</a></li>');
            }
            else
            {
                // 二级菜单
                var firstNav = curNav.parents('.level-1').find('>a').eq(0);
                $('.z-breadcrumb .breadcrumb').append('<li class="breadcrumb-item">' + firstNav.html() + '</li>');
                $('.z-breadcrumb .breadcrumb').append('<li class="breadcrumb-item ' + breadcrumbActive + '"><a href="' + curNav.attr('href') + '">' + curNav.html() + '</a></li>');
                $('.z-breadcrumb .breadcrumb .nav-next').remove();
            }
            if (Z.config.navBreadcrumb != '')
            {
                $('.z-breadcrumb .breadcrumb').append('<li class="breadcrumb-item active">' + Z.config.navBreadcrumb + '</li>');
            }
        }
        else
        {
            $('.z-breadcrumb').hide();
        }

        // 展开选中菜单
        $('.nav-menu .active').parents('.level-1').addClass('open')
            .find('.nav-next').html('<i class="fa fa-angle-up"></i>');
            
        // SELECT设置默认值
        $('select[data-select-val]').each(function(){
            var val = $(this).data('select-val');
            if (val == '@top')
            {
                $(this).find('option').eq(0).prop('selected', true);
            }
            else
            {
                $(this).val(val);
            }
        });

        // 但改变时自动提交表单
        $('.auto-submit').change(function(){
            var frm = $(this).parents('form').eq(0);
            if (frm.length == 0)
            {
                return;
            }
            frm.find('input[name=page]').val('1');
            frm.submit();
        });

        // 显示range的值
        $('input[type=range]').each(function(){
            var id = $(this).attr('id') + '_val';
            $('#' + id).html(this.value);
        }).on('input', function(){
            var id = $(this).attr('id') + '_val';
            $('#' + id).html(this.value);
        });

        // checkbox默认选中
        $('[data-checkbox-val]').each(function(){
            var val = '' + $(this).data('checkbox-val');
            var arr = val.split(',');
            $(this).find('input[type=checkbox]').each(function(){
                var v = $(this).prop('value');
                $(this).prop('checked', $.inArray(v, arr) !== -1);
            });
        });
        
        // radio默认选中
        $('[data-radio-val]').each(function(){
            var val = $(this).data('radio-val');
            $(this).find('input[type=radio]').each(function(){
                var v = $(this).attr('value');
                $(this).prop('checked', val == v);
            });
        });

        // checked选中高亮
        $('.checked-active').each(function(){
            $(this).find(':checked').each(function(){
                $(this).parent().addClass('active');
            });
            $(this).find('input[type=radio],input[type=checkbox]').change(function(){
                $(this).parents('.checked-active').find('label').removeClass('active');
                $(this).parents('.checked-active').find(':checked').each(function(){
                    $(this).parent().addClass('active');
                });
            });
        });

        // 启用/禁用 切换按钮
        $(document).on("click", '.z-status span', function(){
            $(this).parent().find('span').removeClass('active');
            $(this).addClass('active');

            // 提交更新
            var status = $(this).html() == '启用' ? 1 : 0;
            Z.ajax({
                url:$(this).parent().data('url'),
                data: {id:$(this).parents('tr').eq(0).data('id'), status:status },
            });
        });

        // combox组件
        $('input[data-combox]').each(function(){
            var strs = $(this).data('combox');
            var list = strs.split(',');
            var html = '<datalist>';
            for (var k in list)
            {
                var r = list[k];
                var arr = r.split('::');
                if (arr.length == 1)
                {
                    html += '<option value="' + r + '">';
                }
                else
                {
                    html += '<option label="' + arr[0] + '" value="' + arr[1] + '">';
                }
            }
            html += '</datalist>';
            var el = $(html);
            var id = Z.createId();
            el.attr('id', id);
            $('body').append(el);
            $(this).attr('list', id);

            return el;
        });

        // 自动ajax提交表单
        $('.ajax-submit').submit(function(){
            Z.ajaxSubmit(this);
            return false;
        });

        
		// 表格处理
		$('.tbList').each(function(){
			let _tb = $(this);

			// 合并底部
            if (_tb.find('tfoot td').length == 1)
			{
				let c = _tb.find('thead th').length;
				_tb.find('tfoot td').attr('colspan', c);
			}
			_tb.find('tfoot').show();

			// 文本对齐
			_tb.find('thead th').each(function(index){
				let str = $(this).text();
				if (str.length == 0) return;

				if (str[0] == '*') {
					$(this).html(str.substr(1));
					$(this).css('text-align', 'left');
					_tb.find('tbody tr').each(function(){
						$(this).find('td').eq(index).css('text-align', 'left');
					});
				}
				else if (str.substr(-1) == '*') {
					$(this).html(str.substr(0, str.length - 1));
					$(this).css('text-align', 'right');
					_tb.find('tbody tr').each(function(){
						$(this).find('td').eq(index).css('text-align', 'right');
					});
				}
			});
				
			// 表格全选
			_tb.find('.chkAll').change(function(){
				_tb.find('tbody input[type=checkbox]').prop('checked', this.checked);
			});

			// 表格编辑框处理
			_tb.find('td[contenteditable]').each(function(){
				$(this).data('old-value', $(this).html());
			}).blur(function(){
				Z.tbEdit(this);
			}).keydown(function(event){ 
				if (event.which == 13){
					Z.tbEdit(this);
					return false;
				}
			});

		});

        // 滚动
        $(window).scroll(function() {
            var winTop = $(window).scrollTop();
            if (winTop > 100)
                $('#toPageTop').show();
            else
                $('#toPageTop').hide();
            
            // 固定表头
            $('.tbList.fix-header').each(function(){
                var t = $(this).offset().top - Z.config.tableFixTop;
                var scrollTop = winTop - t - 2;
                scrollTop = Math.max(0, scrollTop);
                if (scrollTop == 0 || Z.config.browser == 'mobile')
                {
                    $(this).find('thead tr').css({
                        '-webkit-transform': '',
                        '-ms-transform': '',
                        'transform': '',
                    });
                    $(this).find('thead tr').removeClass('fixed');
                }
                else
                {
                    $(this).find('thead tr').css({
                        '-webkit-transform': 'translate(0, '+scrollTop+'px)',
                        '-ms-transform': 'translate(0, '+scrollTop+'px)',
                        'transform': 'translate(0, '+scrollTop+'px)',
                    });
                    $(this).find('thead tr').addClass('fixed');
                }
            });
        });

        // 返回顶部
        $('#toPageTop').click(function(){
            $('html,body').animate({scrollTop: 0}, 300);
        });

        // 弹出框关闭
        $('.z-form.form-frame .cancel').click(function(){
            top.Z.alertFrameClose();
            return false;
        });

        // layer弹出框默认配置
        layer.config({
            skin: 'layui-layer-rim',
            offset: '10%',
        });

        // 生成分页代码
        $('.tbList tfoot .page, .phone-card-page .page').each(function(){
            var $form = $(this).data('form');
            var $total = $(this).data('total');
            var $pageSize = $(this).data('page-size');
            var $page = $(this).data('page');

            var $page_html = Z.getPageHtml($form, $total, $pageSize, $page);
            if ($page_html != '')
            {
                $(this).html($page_html).show();
            }
        });

        // 日期范围快速选择
        $('.dt-range').each(function(){
            var html = '';
            html += '<div class="input-group-append">';
            html += '    <button class="input-group-text dropdown-toggle" type="button" data-toggle="dropdown" style="border-top-right-radius: 3px; border-bottom-right-radius: 3px;"></button>';
            html += '    <div class="dropdown-menu dropdown-menu-right">';
            html += '    <a class="dropdown-item" href="#">本月 <small>2018年10月</small></a>';
            html += '    <a class="dropdown-item" href="#">上月 <small>2018年09月</small></a>';
            html += '    <a class="dropdown-item" href="#">前月 <small>2018年08月</small></a>';
            html += '    <div role="separator" class="dropdown-divider"></div>';
            html += '    <a class="dropdown-item" href="#">今年 <small>2018年</small></a>';
            html += '    <a class="dropdown-item" href="#">去年 <small>2017年</small></a>';
            html += '    </div>';
            html += '</div>';
            $(this).append(html);
        });

        // 跳出连接的安全处理 https://juejin.im/post/5a9f8425f265da239a5f57f8
        $('a[target=_blank]').prop('rel', 'noopener noreferrer nofollow');

        // 列表操作按钮 自动添加tooltip
        $('.td-btn a[title]').tooltip();
        
        // 删除按钮处理
        $('.tbList a.btn-del, .phone-card-btn a.btn-del').click(function(){
            $(this).tooltip('hide');
            var btn = this;
            Z.confirm('确认删除吗？', function(){
				Z.delTr(btn);
            });
            return false;
        });
        
        // ajax操作确认
        $('.tbList a.btn-ajax, .phone-card-btn a.btn-ajax').click(function(){
            var btn = this;
            var str = $(this).attr('title') || '确认执行此操作吗？';
            Z.confirm(str, function(){
                Z.ajax({
                    url:$(btn).attr('href'),
                    data:{
                        id:$(btn).parents('.phone-card-item').eq(0).data('id') || $(btn).parents('tr').eq(0).data('id'),
                    },
                });
            });
            return false;
        });
        
        // 编辑按钮处理
        $('.tbList a.btn-edit, .phone-card-btn a.btn-edit').each(function(){
            var url = $(this).attr('href');
            var id = $(this).parents('.phone-card-item').eq(0).data('id') || $(this).parents('tr').eq(0).data('id');
            if (url.indexOf('?') === -1)
            {
                url += '?id=' + id;
            }
            else
            {
                url += '&id=' + id;
            }
            $(this).attr('href', url);
        });

        // 编辑按钮处理
        $('.tbList a.btn-frame-edit, .phone-card-btn a.btn-frame-edit').each(function(){
            var url = $(this).attr('href');
            var id = $(this).parents('.phone-card-item').eq(0).data('id') || $(this).parents('tr').eq(0).data('id');
            if (url.indexOf('?') === -1)
            {
                url += '?id=' + id;
            }
            else
            {
                url += '&id=' + id;
            }

            var frameSize = $(this).data('frame-size');
            var title = $(this).data('alert-title') || $(this).data('original-title') || '弹出框';
            if (frameSize != '')
            {
                var arr = frameSize.split(',');
                url = "javascript:Z.alertFrame('" + url + "', '" + title + "', ['" + arr[0] + "', '" + arr[1] + "']);";
            }
            else
            {
                url = "javascript:Z.alertFrame('" + url + "', '" + title + "');";
            }
            $(this).attr('href', url);
        });

        // 表格排序
        $('.z-tb-sort').each(function(){
            var val = $(this).data('sort-val');
            $(this).find('.dropdown-item[data-val=' + val + ']').addClass('active');
            $(this).find('.dropdown-item[data-val=' + val + ']>i').addClass('fa-check');
            var frm = $(this).parents('form').eq(0);
            if (frm.find('input[name=sort]').length == 0)
            {
                frm.append('<input type="hidden" name="sort" value="' + val + '" />');
            }
            else
            {
                frm.find('input[name=sort]').val(val);
            }
            $(this).find('.dropdown-item').click(function(){
                $(this).parents('form').eq(0).find('input[name=sort]').val($(this).data('val'));
                $(this).parents('form').eq(0).find('input[name=page]').val('1');
                $(this).parents('form').eq(0).submit();
            });
        });

        // 表格搜索框默认全选
        $('.table-tool input[type=search]').select().keydown(function(){
            if(event.keyCode == 13){
                $(this).parents('form').eq(0).submit();
                return false;
            }
        });

        // 悬停显示图片功能
        Z.img_hover_trigger = null;
        $('.img_hover').hover(function(){
            if ($('#imgHover').length == 0)
            {
                $('body').append('<img id="imgHover" style="display:none; position:absolute; width:400px; box-shadow: 2px 2px 10px #000;" />');
            }
            var _this = $(this);
            Z.img_hover_trigger = setTimeout(function(){
                var top = _this.offset().top + _this.outerHeight() + 5;
                if (top + 300 > $(window).height())
                {
                    top = _this.offset().top - 305;
                }
                $('#imgHover').css({
                    'top': top,
                    'left': _this.offset().left + _this.outerWidth() - 10,
                });
                $('#imgHover').attr('src', _this.data('img')).show();
            },300);
        }, function(){
            clearTimeout(Z.img_hover_trigger);
            $('#imgHover').attr('src', '').hide();
        });

        // 背景百分比
        $('[data-background-progress]').each(function(){
            //background: linear-gradient(90deg, blue 0%, blue 10%,transparent 10%, transparent 90%);
            var precent = '' + $(this).data('background-progress');
            var arr = precent.split('|');
            if (arr.length == 1)
            {
                arr.push('blue');
            }
            if (arr[0] == 0)
            {
                return;
            }
            var css = 'linear-gradient(90deg, ' + arr[1] + ' 0%, ' + arr[1] + ' ' + arr[0] + '%,transparent ' + arr[0] + '%, transparent ' + (100 - arr[0]) + '%)';
            $(this).css('background', css);
        });

        // 小饼图
        $('.pie').each(function(){
            // background-color:blue; animation-delay:-45s;
            var precent = $(this).attr('title');
            precent = precent.replace('%', '');
            precent = parseFloat(precent);

            var color = $(this).data('color');
            if (!color)
            {
                color = 'blue';
                if (precent < 10)
                {
                    color = 'red';
                }
                else if (precent < 90)
                {
                    color = 'blue';
                }
                else
                {
                    color = 'green';
                }
            }

            $(this).css('background-color', color);
            $(this).css('animation-delay', '-' + (100 - precent) + 's');
        });

        // 验证码
        $('.vcode').click(function(){
            if (!$(this).data('src'))
            {
                $(this).attr('title', '看不清？点击刷新验证码。');
                $(this).data('src', $(this).attr('src'));
            }
            var src = $(this).data('src');
            if (src.indexOf('?') == -1)
            {
                $(this).attr('src', src + '?r=' + Math.random());
            }
            else
            {
                $(this).attr('src', src + '&r=' + Math.random());
            }
        });

        // 日期选择控件
        if ($.fn.datepicker)
        {
            $('input.datepicker').each(function(){
                let thisDate = this;
                $(this).datepicker({
                    language: "zh-CN",
                    orientation: "auto auto",
                    keyboardNavigation: false,
                    forceParse: false,
                    autoclose: true,
                    todayHighlight: true,
                    beforeShowDay: function(date){
                        let dateList = $(thisDate).data('date-list');
                        if (!dateList) return;
                        dateList = dateList.split(/,/ig);
                        let dt = Z.dateFormat(date);
                        if ($.inArray(dt, dateList) != -1)
                        {
                            return "hot";
                        }
                        return true;
                    },
                });
            });
        }

        // 表格内部滚动条
        Z.tbResize();
        $('.divTbScroll').scroll(function(){
            Z.tbScroll(this);
        });
        $('.divTbScroll').each(function(){
            Z.tbScroll(this);
        });
        
        // 手机CARD展开
        $('.phone-card-item').click(function(){
            if ($(this).hasClass('active')) return;

            $('.phone-card-item').removeClass('active');
            $(this).addClass('active');
        });

        $(window).resize(Z.tbResize);

        console.timeEnd("JS启动时间");
    },

    dateFormat:function(date){
        let month = '0' + (date.getMonth() + 1);
        month = month.substr(month.length - 2, 2);
        let day = '0' + date.getDate();
        day = day.substr(day.length - 2, 2);
        let val = '' + date.getFullYear() + '-' + month + '-' + day;

        return val;
    },

    // 删除表格行
    delTr:function(o){
        var url = $(o).attr('href');
        if (url==''||url=='#')
        {
            alert('未指定删除处理');
            return;
        }
        if (url.indexOf('javascript:') == 0)
        {
            window.location.href = url;
            return;
        }

        var id = $(o).data('id') || $(o).parents('.phone-card-item').eq(0).data('id') || $(o).parents('tr').eq(0).data('id');
        var data = {};
        if (id)
        {
            data.id = id;
        }

        Z.ajax({
            url:url,
            data:data,
            success:function(){
                if ($(o).parents('.phone-card-item').length > 1)
                {
                    $(o).parents('.phone-card-item').eq(0).remove();
                }
                else
                {
                    $(o).parents('tr').eq(0).remove();
                }
            }
        });
    },

    // 获取表格选中ID
    getTableCheckList:function(tbId){
        if (tbId === void 0) { tbId = '.tbList'; }
        var idList = [];
        $(tbId).find('tbody input[type=checkbox]:checked').each(function(){
            var id = $(this).val()=='on' ? $(this).parent().parent().data('id') : $(this).val();
            idList.push(id);
        });

        return idList;
    },

    // 表格编辑
    tbEdit:function(o){
        var oldVal = $(o).data('old-value');
        var val = $(o).html();
        if (val == oldVal)
        {
            return;
        }
        var id = $(o).parent().data('id');
        var table = $(o).parents('.tbList').data('table');
        var field = $(o).data('field');
        Z.ajax({
            url:'/index.php?_=sys_base::update_text',
            data:{
                id:id,
                table:table,
                field:field,
                val:val,
            },
            success:function(res){
                $(o).data('old-value', val);
            },
            error:function(res){
                $(o).html(oldVal);
                Z.alertMsg(res.error, 2);
            },
        });
    },

    // 弹出信息提示框
    // 图标：0-信息 1-完成 2-错误 3-询问 4-锁定 5-失败 6-成功
    alertMsg:function(str, icon)
    {
        icon = icon || 0;
        layer.msg(str, {
            icon:icon,
        });
    },
    alert:function(str, title)
    {
        title = title || '提示信息';
        layer.open({
            icon:0,
            shadeClose: true,
            skin: 'layui-layer-rim',
            title: title,
            content: str,
        });
    },
    alertError:function(str, title)
    {
        title = title || '错误信息';
        layer.open({
            icon:2,
            shadeClose: true,
            title: title,
            content: str,
        });
    },
    confirm:function(str, fn)
    {
        layer.confirm(str, {
            icon:3,
            title: '操作确认',
            shadeClose: true,
            btn: ['确定', '取消'] //按钮
        }, function(){
            layer.closeAll();
            if (!fn) return;
            if (typeof fn === 'string')
            {
                window.location.href = fn;
            }
            else if(typeof fn === 'function')
            {
                fn();
            }
        });
    },
    prompt:function(str, fn)
    {
        layer.prompt({
            title:str,
        },function(val, index){
            layer.close(index);
            if(typeof fn === 'function')
            {
                fn(val);
            }
        });
    },
    alertFrame: function(url, title, area){
        title = title || false;
        area = area || ['600px', '80%'];
        if (area[0].indexOf('px'))
        {
            if (parseInt(area[0].replace('px', '')) > $(document).width())
            {
                area[0] = '100%';
            }
        }
        if (area[1].indexOf('px'))
        {
            if (parseInt(area[1].replace('px', '')) > $(document).height())
            {
                area[1] = '100%';
            }
        }
        if (Z.config.browser == 'mobile')
        {
            area = ['100%', '100%'];
        }
        layer.open({
            type: 2,
            offset:'auto',
            title: title,
            shadeClose: false,
            area: area,
            content: url,
        }); 
    },
    alertFrameClose: function(){
        layer.closeAll();
    },

    // ajax封装
    ajax : function(option)
    {
        option.error = option.error||function(res){
            Z.alertError(res.error, 'ajax 操作失败');
        };
        option.success = option.success||function(){};

        $.ajax({
            url: option.url,
            type: 'POST',
            data: option.data,
            dataType: 'json',
            timeout: Z.config.ajaxTimeout,
            success: function (res)
            {
                Z.debug(res);
                if (res.result != 1)
                {
                    option.error(res);
                    return;
                }
                
                if (res.reload)
                {
                    top.window.location.reload();
                }
                else if(res.url)
                {
                    top.window.location.href = res.url;
                }
                else
                {
                    option.success(res);
                }
            },
            error: function(xmlobj, err){
                console.error(option, xmlobj, err);
                option.error({
                    result:-2,
                    error:err,
                    content:xmlobj.responseText,
                });
            },
        });
    },
    
    //将form转为AJAX提交
    ajaxSubmit: function(frm, fnSuccess, fnError)
    {
        // 2秒内禁止提交
        if ($(frm).data('submit_wait'))
        {
            console.info('form 重复提交');
            return;
        }
        $(frm).data('submit_wait', true);
        window.setTimeout(function(){
            $(frm).data('submit_wait', false);
        }, 2000);

        $(frm).find('.form-error').html('').hide();
        if (!fnError)
        {
            fnError = function(res){
                $(frm).find('.form-error').html('(' + res.result + ') ' + res.error).show();
            };
        }

        this.ajax({
            url: $(frm).prop('action'),
            data: $(frm).serializeArray(), 
            success: fnSuccess, 
            error: fnError,
        });
    },
    
    // 记录服务器日志
    addLog: function(s){
        if (typeof(s) !== 'String')
        {
            s = JSON.stringify(s);
        }
        $.ajax({
            url: './tool/web_log.php',
            type: 'POST',
            data: {content:s},
            dataType: 'json',
        });
    },

    // 对象变量解析
    getObjVal: function(obj, key){
        var keys = key.split('.');
        var obj1 = obj;
        var res = null;
        for (var k in keys)
        {
            var kk = keys[k];
            if (kk in obj1)
            {
                res = obj1[kk];
                obj1 = obj1[kk];
            }
            else
            {
                res = null;
                break;
            }
        }

        return res;
    },

    // 绑定数据
    bindData: function(obj){
        $('[data-bind-html]').each(function(){
            var k = $(this).data('bind-html');
            var v = Z.getObjVal(obj, k);
            if (v != null)
            {
                $(this).html(v);
            }
        });
        $('[data-bind-val]').each(function(){
            var k = $(this).data('bind-val');
            var v = Z.getObjVal(obj, k);
            if (v != null)
            {
                $(this).val(v);
            }
        });
    },

    // 分页跳转
    goPage: function(page, frm){
        $('#'+frm).find('input[name=page]').val(page);
        $('#'+frm).submit();
    },

    // 获取分页HTML代码
    getPageHtml: function($form, $total, $pageSize, $page){
        if ($total < $pageSize)
        {
            return '';
        }

        $strForm = ",'" + $form + "'";

        $page_html = '';
        $max_page = Math.ceil($total / $pageSize);
        $page_html += '<a href="javascript:Z.goPage(' + Math.max(1, $page-1) + $strForm + ');">&laquo;</a>';
        $last = 1;
        for (var $i=1; $i<=$max_page; $i++)
        {
            if ($i > 1 && $i < $page - 4)
            {
                continue;
            }
            if ($i < $max_page && $i > $page + 4)
            {
                continue;
            }
            if ($i-$last > 1)
            {
                $page_html += '<span>…</span>';
            }
            $last = $i;
            $page_html += '<a' + ($i==$page?' class="active"':'') + ' href="javascript:Z.goPage(' + $i + $strForm + ');">' + $i + '</a>';
        }
        $page_html += '<a href="javascript:Z.goPage(' + Math.min($max_page, $page+1) + $strForm + ');">&raquo;</a>';

        return $page_html;
    },

    // 显示加载层
    loading: function(txt, mask){
        if (txt === void 0) { txt = '加载中…'; }
        if (mask === void 0) { mask = true; }

        if ($('.z-loading').length == 0)
        {
            $('body').append('<div class="z-loading-mask"></div><div class="z-loading"></div><div class="z-loading-text"></div>');
        }
        $('.z-loading').show();
        $('.z-loading-text').html(txt).show();
        if (mask)
        {
            $('.z-loading-mask').show();
        }
        else
        {
            $('.z-loading-mask').hide();
        }
    },

    // 隐藏加载层
    loadingHide: function(){
        $('.z-loading').hide();
        $('.z-loading-text').hide();
        $('.z-loading-mask').hide();
    },

    // 节流函数
    throttle: function(fn, threshhold){
        var timeout
        var start = new Date
        var threshhold = threshhold || 160
      
        return function () {
          var context = this, args = arguments,
            curr = new Date() - 0
      
          clearTimeout(timeout)
      
          if (curr - start >= threshhold) {
            fn.apply(context, args) //只执行一部分方法，这些方法是在某个时间段内执行一次
            start = curr
          } else {
            timeout = setTimeout(function () {
              fn.apply(context, args)
            }, threshhold)
          }
        }
    },

    // 函数防抖
    debounce: function(func, delay) {
        var timeout
        return function(e) {
            clearTimeout(timeout)
            var context = this, args = arguments
            
            timeout = setTimeout(function(){
              func.apply(context, args)
            },delay)
        }
    },

    // 导出EXCEL
	tableToExcel : function(id)
	{
		var tb = document.getElementById(id);
        var html = '';
        if (tb.tagName == 'TABLE')
        {
            html = '<html><head><meta charset="UTF-8"></head><body><table>' + tb.innerHTML + '</table></body></html>';
        }
        else
        {
            html = '<html><head><meta charset="UTF-8"></head><body>' + tb.innerHTML + '</body></html>';
        }
		
		html = window.btoa(unescape(encodeURIComponent(html)));
		
		var uri = 'data:application/vnd.ms-excel;base64,' + html;
		window.location.href = uri;
    },
    
    // 表格内部滚动条
    tbScroll:function(o)
    {
        let scrollTop = o.scrollTop;
        if (scrollTop == 0)
        {
            o.querySelector('thead').style.transform = '';
        }
        else
        {
            o.querySelector('thead').style.transform = 'translateY(' + (scrollTop - 1) + 'px)';
        }

        let h = $(o).find('.tbList').height() - $(o).height() - scrollTop;
        let scrollbarHeight = o.offsetHeight - o.clientHeight;
        h = 0-h-scrollbarHeight;
        o.querySelector('tfoot').style.transform = 'translateY(' + h + 'px)';
    },

    tbResize:function()
    {
        let winHeight = $(window).height();
        $('.divTbScroll').each(function(){
            let h = $(this).find('.tbList').height();
            if (h + $(this).offset().top > winHeight)
            {
                h = winHeight - $(this).offset().top - 10;
            }

            $(this).css('height', h + 'px');
            Z.tbScroll(this);
        });
    }
};
