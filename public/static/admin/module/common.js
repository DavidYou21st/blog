/**
 * Created by 1971599474@qq.com
 * Desc: 公共js
 * User: 智轩
 * Date: 2023/06/02
 */


layui.define(["layer", "jquery"], function (exports) {
    var layer = layui.layer,
        $ = layui.$;
    C =
        //定义一个名字为C的类
        function (){
            //成员属性host:默认的接口请求地址
            this.host = 'http://127.0.0.1';
            this.url = function (url) {
                return '/admin/' + url;
            },
                this.config =  {
                    shade: [0.02, '#000'],
                },
                _this = this;
            this.msg = {
                // 成功消息
                success: function (msg, callback) {
                    if (callback === undefined) {
                        callback = function () {
                        }
                    }
                    var index = layer.msg(msg, {icon: 1, shade: _this.config.shade, scrollbar: false, time: 2000, shadeClose: true}, callback);
                    return index;
                },
                // 失败消息
                error: function (msg, callback) {
                    if (callback === undefined) {
                        callback = function () {
                        }
                    }
                    var index = layer.msg(msg, {icon: 2, shade: _this.config.shade, scrollbar: false, time: 3000, shadeClose: true}, callback);
                    return index;
                },
                // 警告消息框
                alert: function (msg, callback) {
                    var index = layer.alert(msg, {end: callback, scrollbar: false});
                    return index;
                },
                // 对话框
                confirm: function (msg, ok, no) {
                    var index = layer.confirm(msg, {title: '操作确认', btn: ['确认', '取消']}, function () {
                        typeof ok === 'function' && ok.call(this);
                    }, function () {
                        typeof no === 'function' && no.call(this);
                        self.close(index);
                    });
                    return index;
                },
                // 消息提示
                tips: function (msg, time, callback) {
                    var index = layer.msg(msg, {time: (time || 3) * 1000, shade: this.shade, end: callback, shadeClose: true});
                    return index;
                },
                // 加载中提示
                loading: function (msg, callback) {
                    var index = msg ? layer.msg(msg, {icon: 16, scrollbar: false, shade: this.shade, time: 0, end: callback}) : layer.load(2, {time: 0, scrollbar: false, shade: this.shade, end: callback});
                    return index;
                },
                // 关闭消息框
                close: function (index) {
                    return layer.close(index);
                }
            };

            this.request = {
                post: function (option, ok, no, ex) {
                    return _this.request.ajax('post', option, ok, no, ex);
                },
                get: function (option, ok, no, ex) {
                    return _this.request.ajax('get', option, ok, no, ex);
                },
                ajax: function (type, option, ok, no, ex) {
                    type = type || 'get';
                    option.url = option.url || '';
                    option.data = option.data || {};
                    option.prefix = option.prefix || false;
                    option.statusName = option.statusName || 'code';
                    option.statusCode = option.statusCode || 0;
                    option.headers = option.headers || '';
                    ok = ok || function (res) {
                    };
                    no = no || function (res) {
                        var msg = res.msg == undefined ? '返回数据格式有误' : res.msg;
                        _this.msg.error(msg);
                        return false;
                    };
                    ex = ex || function (res) {
                    };
                    if (option.url == '') {
                        _this.msg.error('请求地址不能为空');
                        return false;
                    }
                    if (option.prefix == true) {
                        option.url = _this.url(option.url);
                    }
                    var index = _this.msg.loading('加载中');
                    $.ajax({
                        url: option.url,
                        type: type,
                        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                        dataType: "json",
                        headers:option.headers,
                        data: option.data,
                        timeout: 60000,
                        success: function (res) {
                            _this.msg.close(index);
                            if (eval('res.' + option.statusName) == option.statusCode) {
                                return ok(res);
                            } else {
                                return no(res);
                            }
                        },
                        error: function (xhr, textstatus, thrown) {
                            _this.msg.error('Status:' + xhr.status + '，' + xhr.statusText + '，请稍后再试！', function () {
                                ex(this);
                            });
                            return false;
                        }
                    });
                }
            };

            this.checkMobile = function () {
                var userAgentInfo = navigator.userAgent;
                var mobileAgents = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
                var mobile_flag = false;
                //根据userAgent判断是否是手机
                for (var v = 0; v < mobileAgents.length; v++) {
                    if (userAgentInfo.indexOf(mobileAgents[v]) > 0) {
                        mobile_flag = true;
                        break;
                    }
                }
                var screen_width = window.screen.width;
                var screen_height = window.screen.height;
                //根据屏幕分辨率判断是否是手机
                if (screen_width < 600 && screen_height < 800) {
                    mobile_flag = true;
                }
                return mobile_flag;
            };

            this.open = function (title, url, width, height, isResize) {
                isResize = isResize === undefined ? true : isResize;
                var index = layer.open({
                    title: title,
                    type: 2,
                    area: [width, height],
                    content: url,
                    maxmin: true,
                    moveOut: true,
                    success: function (layero, index) {
                        var body = layer.getChildFrame('body', index);
                        if (body.length > 0) {
                            $.each(body, function (i, v) {

                                // todo 优化弹出层背景色修改
                                $(v).before('<style>\n' +
                                    'html, body {\n' +
                                    '    background: #ffffff;\n' +
                                    '}\n' +
                                    '</style>');
                            });
                        }
                    }
                });
                if (this.checkMobile() || width === undefined || height === undefined) {
                    layer.full(index);
                }
                if (isResize && index !== undefined ) {
                    $(window).on("resize", function () {
                        layer.full(index);
                    })
                }
            };
        }

    /*
    * 获取接口请求服务器地址
    * @param void
    * @return string
    */
    C.prototype.getUrl = function (){
        return this.host;
    }

    /*
    * 获取设备类型
    * @param void
    * @return string
    */
    C.prototype.getDevice = function(){
        var u = navigator.userAgent;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if (isAndroid == true) {
            return 'android';
        } else if(isiOS == true){
            return 'ios';
        }
        return 'ios';
    }

    /*
    * 获取地址栏参数
    * @param string name
    * @return mixed
    */
    C.prototype.getQueryString = function(name){
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }


    /**
     * 本地存储扩展函数,添加过期时间
     * @param string key 存储key
     * @param string value 存储值
     * @param int expire 过期时间 默认1天
     * @return void
     */
    Storage.prototype.setExpire = function(key,value,expire){
        var now = Date.parse(new Date())/1000 + 28800;
        var obj={
            data:value,
            time:now,
            expire:expire?expire:86400
        };
        localStorage.setItem(key,JSON.stringify(obj));
    }

    /**
     * 本地存储扩展函数,获取存储值带过期时间
     * @param string key 存储key
     * @param string value 存储值
     * @param int expire 过期时间
     * @return string 存储值
     */
    Storage.prototype.getExpire= function(key){
        var val =localStorage.getItem(key);
        var now = Date.parse(new Date())/1000 + 28800;
        if(!val){
            return val;
        }
        val =JSON.parse(val);

        if(val.time+val.expire < now){
            localStorage.removeItem(key);
            return null;
        }
        return val.data;
    }


    /**
     * 验证登录
     * @param void
     * @return boolen
     */
    C.prototype.verfity_login = function(){

        if(localStorage.getExpire('access_token') == null){
            return false;
        }

        //这里填写项目的真实地址
        $.ajax({
            url:this.getUrl()+'api/',
            type:'post',
            data:{
                token:localStorage.getExpire('access_token'),
            },
            dataType:'json',
            success:function(res){
                if(res.code !=200){
                    localStorage.setExpire('access_token',null);
                    return false;
                }
            },
        });
        if(localStorage.getExpire('access_token') == null){
            return false;
        }

        return true;
    }


    /**
     * 检测登录状态跳转
     * @param action 跳转网址
     * @param login 跳转登录
     * @return mixed
     */
    C.prototype.check_login = function(action,login){
        var index = login?login:'login.html';
        var access_token = localStorage.getExpire('access_token');
        if(access_token == null){
            window.location.href = index;
            return false;
        }
        window.location.href = action;
    }

    /**
     * 退出登录函数
     * @param void
     * @return mixed
     */
    C.prototype.login_out = function(){
        localStorage.removeItem('access_token');
        window.location.href = 'index.html';
    }

    /**
     * 返回日期格式：xxxx-xx-xx  HH:MM:SS
     * @param {Object} value
     */
    C.prototype.getStandardDT = function(value){
        if(!value || typeof(value) == "undefined" || value == ""||value=="null"||value==null){
            return '';
        }else{
            var v_date = new Date(value);
            var year = v_date.getFullYear();
            var month = v_date.getMonth()+1;
            var day = v_date.getDate();
            var hour = v_date.getHours();
            var minute = v_date.getMinutes();
            var second = v_date.getSeconds();
            day = (day.toString()).length<2?("0"+day):day;
            month = (month.toString()).length<2?("0"+month):month;
            hour = (hour.toString()).length<2?("0"+hour):hour;
            minute = (minute.toString()).length<2?("0"+minute):minute;
            second = (second.toString()).length<2?("0"+second):second;
            return year + '-' + month + '-' + day + '  ' + hour + ':' + minute + ':' + second;
        }
    }



    /**
     * 返回标准的时间格式：时:分:秒
     * @param {Object} value
     */
    C.prototype.getStandardTime = function(value){
        if(!value || typeof(value) == "undefined" || value == ""||value=="null"||value==null){
            return '';
        }else{
            var v_date = new Date(value);
            var hour = v_date.getHours();
            var minute = v_date.getMinutes();
            var second = v_date.getSeconds();

            hour = (hour.toString()).length<2?("0"+hour):hour;
            minute = (minute.toString()).length<2?("0"+minute):minute;
            second = (second.toString()).length<2?("0"+second):second;

            return hour + ':' + minute + ':' + second;
        }
    }



    /**
     * 返回标准的日期格式：年-月-日
     * @param {Object} value
     */
    C.prototype.getStandardDate = function(value){
        if(!value || typeof(value) == "undefined" || value == ""||value=="null"||value==null){
            return '';
        }else{
            var v_date = new Date(value);
            var day = v_date.getDate();
            var month = v_date.getMonth()+1;
            var year = v_date.getFullYear();

            day = (day.toString()).length<2?("0"+day):day;
            month = (month.toString()).length<2?("0"+month):month;

            return year + '-' + month + '-' + day;
        }
    }

    /**
     * 检测是否是正确的手机号
     * @param phoneInput string 手机号
     * @return mixed
     */
    C.prototype.isPoneAvailable = function(poneInput) {
        //正则检测规则
        var myreg=/^[1][3,4,5,6,7,8,9][0-9]{9}$/;
        if (!myreg.test(poneInput.val())) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检测是否是正确的邮箱
     * @param email string 邮箱地址
     * @return boolean
     */
    C.prototype.checkEmail = function(email){
        var reg = /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/;
        return 	reg.test(email)?true:false;
    }

    exports("common", new C());
});
