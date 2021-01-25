layui.define(["jquery", "form", "larryTab", "laytpl", "larry"], function (o) {
    var a = layui.$, e = layui.form, l = layui.larryTab({tab_elem: "#larry_tab", tabMax: 30}), r = layui.layer,
        i = layui.laytpl, s = layui.larry, n = layui.larryms, f = a(window), m = a("body");
    var d = layui.data("larryms").lockscreen;

    if (d === "locked") {
        y()
    }
    l.menuSet({type: "POST", url: layui.cache.menusUrl, left_menu: "#larryms_left_menu", leftFilter: "LarrySide"});
    l.menu();
    if (l.config.tabSession) {
        l.session(function (o) {
            if (o.getItem("tabMenu")) {
                a("#larry_tab_title li.layui-this").trigger("click")
            }
        })
    }
    a("#larryms_version").text(n.version);
    a("#menufold").on("click", function () {
        if (a("#larry_layout").hasClass("larryms-fold")) {
            a("#larry_layout").addClass("larryms-unfold").removeClass("larryms-fold");
            a(this).children("i").addClass("yun-fold-left").removeClass("yun-icon-collapse-left")
        } else {
            a("#larry_layout").addClass("larryms-fold").removeClass("larryms-unfold");
            a(this).children("i").addClass("yun-icon-collapse-left").removeClass("yun-fold-left")
        }
    });
    a("#larryTheme").on("click", function () {
        var o = r.open({
            type: 1,
            id: "larry_theme_R",
            title: false,
            anim: Math.ceil(Math.random() * 6),
            offset: "r",
            closeBtn: false,
            shade: .2,
            shadeClose: true,
            skin: "layui-anim layui-anim-rl larryms-layer-right",
            area: "320px",
            success: function (o, t) {
                var e = layui.cache.base + "templets/style/theme.css";
                layui.link(e);
                n.htmlRender("templets/theme", o)
            }
        })
    });
    a("#clearCached").off("click").on("click", function () {
        n.cleanCached("larry_menu");
        r.alert("缓存清除完成!本地存储数据也清理成功！", {
            icon: 1, title: "系统提示", end: function () {
                top.location.reload()
            }
        })
    });
    a("#logout").off("click").on("click", function () {
        var o = a(this).data("url");
        n.logOut(o)
    });
    a("#fullScreen").bind("click", function () {
        n.fullScreen(a(this))
    });
    a("#lock").mouseover(function () {
        r.tips("请按Alt+L快速锁屏！", a(this), {tips: [1, "#FF5722"], time: 1500})
    });
    a("#lock").off("click").on("click", function () {
        y()
    });

    function y() {
        var o = a("#user_photo").attr("src"), t = a("#uname").text();
        h({Display: "block", UserPhoto: o, UserName: t});
        layui.data("larryms", {key: "lockscreen", value: "locked"});
        p()
    }

    function c() {
        var o = a("#user_photo").attr("src"), t = a("#uname").text();
        var pass = a("#unlock_pass").val();
        // let username = document.getElementById("username");
        if (pass === "njxz") {
            h({Display: "none", UserPhoto: o, UserName: t})
        } else {
            r.tips("请输入正确的密码解锁", a("#unlock"), {tips: [2, "#FF5722"], time: 1e3});
        }
    }

    a(document).keydown(function () {
        return u(arguments[0])
    });

    function u(o) {
        var t;
        if (window.event) {
            t = o.keyCode
        } else if (o.which) {
            t = o.which
        }
        if (o.altKey && t == 76) {
            y()
        }
    }

    function h(o) {
        var t = "larry_lock_screen", e = document.createElement("div"),
            l = i(['<div class="lock-screen" style="display: {{d.Display}};">', '<div class="lock-wrapper" id="lock-screen">', '<div id="time"></div>', '<div class="lock-box">', '<img src="{{d.UserPhoto}}" alt="">', "<h1>{{d.UserName}}</h1>", '<form action="" class="layui-form lock-form">', '<div class="layui-form-item">', '<input type="password" id="unlock_pass" name="lock_password" lay-verify="pass" placeholder="锁屏状态，请输入密码解锁" autocomplete="off" class="layui-input"  autofocus="">', "</div>", '<div class="layui-form-item">', '<span class="layui-btn larry-btn" id="unlock">立即解锁</span>', "</div>", "</form>", "</div>", "</div>", "</div>"].join("")).render(o),
            r = document.getElementById(t);
        e.id = t;
        e.innerHTML = l;
        r && m[0].removeChild(r);
        if (o.Display !== "none") {
            m[0].appendChild(e)
        } else {
            a("#larry_lock_screen").empty()
        }
        a("#unlock").off("click").on("click", function () {
            c();
            layui.data("larryms", {key: "lockscreen", value: "unlock"})
        });
        a("#unlock_pass").keypress(function (o) {
            if (window.event && window.event.keyCode == 13) {
                a("#unlock").click();
                return false
            }
        })
    }

    function p() {
        var o = new Date;
        var e = o.getHours();
        var l = o.getMinutes();
        var r = o.getSeconds();
        l = l < 10 ? "0" + l : l;
        r = r < 10 ? "0" + r : r;
        a("#time").html(e + ":" + l + ":" + r);
        t = setTimeout(function () {
            p()
        }, 500)
    }

    var v = function () {
        this.themeColor = {
            default: {
                topColor: "#1b8fe6",
                topThis: "#1958A6",
                topBottom: "#01AAED",
                leftColor: "#2f3a4f",
                leftRight: "#258ED8",
                navThis: "#1492DD",
                titBottom: "#1E9FFF",
                footColor: "#245c87",
                name: "default"
            },
            deepBlue: {
                topColor: "#1b8fe6",
                topThis: "#1958A6",
                topBottom: "#01AAED",
                leftColor: "#2f3a4f",
                leftRight: "#258ED8",
                navThis: "#1492DD",
                titBottom: "#1E9FFF",
                footColor: "#245c87",
                name: "deepBlue"
            },
            green: {
                topColor: "#2a877b",
                topThis: "#5FB878",
                topBottom: "#50A66F",
                leftColor: "#343742",
                leftRight: "#50A66F",
                navThis: "#56a66c",
                titBottom: "#50A66F",
                footColor: "#3e4e63",
                name: "green"
            },
            navy: {
                topColor: "#2f4056",
                topThis: "#0d51a9",
                topBottom: "#01AAED",
                leftColor: "#393d49",
                leftRight: "#1E9FFF",
                navThis: "#1E9FFF",
                titBottom: "#01AAED",
                footColor: "#343742",
                name: "navy"
            },
            orange: {
                topColor: "#F39C34",
                topThis: "#CD7013",
                topBottom: "#FF5722",
                leftColor: "#1d1f26",
                leftRight: "#FFB800",
                navThis: "#df7700",
                titBottom: "#FFB800",
                footColor: "#f2f2f2",
                footFont: "#666",
                name: "orange"
            },
            pink: {
                topColor: "#ff1493",
                topThis: "#ed1188",
                topBottom: "#ed1188",
                leftColor: "#1d1f26",
                leftRight: "#ed1188",
                navThis: "#ed1188",
                titBottom: "#ff1493",
                footColor: "#f2f2f2",
                footFont: "#666",
                name: "pink"
            },
            purple: {
                topColor: "#912cee",
                topThis: "#8927e4",
                topBottom: "#8927e4",
                leftColor: "#1d1f26",
                leftRight: "#912cee",
                navThis: "#912cee",
                titBottom: "#912cee",
                footColor: "#f2f2f2",
                footFont: "#666",
                name: "purple"
            },
            lightgreen: {
                topColor: "#20B2AA",
                topThis: "#37C6C0",
                topBottom: "#37C6C0",
                leftColor: "#1d1f26",
                leftRight: "#20B2AA",
                navThis: "#20B2AA",
                titBottom: "#20B2AA",
                footColor: "#f2f2f2",
                footFont: "#666",
                name: "lightgreen"
            },
            lightred: {
                topColor: "#d969e9",
                topThis: "#b943ca",
                topBottom: "#b943ca",
                leftColor: "#1d1f26",
                leftRight: "#d969e9",
                navThis: "#b943ca",
                titBottom: "#d969e9",
                footColor: "#f2f2f2",
                footFont: "#666",
                name: "lightred"
            },
            navyblue: {
                topColor: "#252b75",
                topThis: "#252961",
                topBottom: "#2f3367",
                leftColor: "#1d1f26",
                leftRight: "#252b75",
                navThis: "#252961",
                titBottom: "#252b75",
                footColor: "#f2f2f2",
                footFont: "#666",
                name: "navyblue"
            },
            dangreen: {
                topColor: "#7ac372",
                topThis: "#3baf2e",
                topBottom: "#3baf2e",
                leftColor: "#1d1f26",
                leftRight: "#7ac372",
                navThis: "#7ac372",
                titBottom: "#7ac372",
                footColor: "#f2f2f2",
                footFont: "#666",
                name: "dangreen"
            },
            skyblue: {
                topColor: "#7e9cfe",
                topThis: "#6b88fe",
                topBottom: "#6b88fe",
                leftColor: "#1d1f26",
                leftRight: "#7e9cfe",
                navThis: "#7e9cfe",
                titBottom: "#7e9cfe",
                footColor: "#f2f2f2",
                footFont: "#666",
                name: "skyblue"
            }
        }
    };
    v.prototype.theme = function (o) {
        var t = "Larryms_theme_style", e = document.createElement("style"), l = layui.data("larryms"),
            r = i([".layui-header{background-color:{{d.topColor}} !important;border-bottom:3px solid {{d.topBottom}};}", ".larryms-extend{border-left:1px solid {{d.topThis}} }", ".larryms-nav-bar{background-color:{{d.topBottom}} !important;}", ".larryms-extend .larryms-nav li.larryms-this{background:{{d.topThis}} !important; }", ".larryms-extend .larryms-nav li.larryms-nav-item:hover{background:{{d.topThis}} !important; }", ".larryms-extend .larryms-nav li.larryms-this:hover{background:{{d.topThis}} }", ".larryms-fold .larryms-header .larryms-topbar-left .larryms-switch{border-left:1px solid {{d.topThis}} !important;}", ".larryms-extend  ul.layui-nav li.layui-nav-item:hover{background:{{d.topThis}} !important;}", ".larryms-topbar-right .layui-nav-bar{background-color: {{d.navThis}} !important;}", ".larryms-nav-tree .larryms-this,", ".larryms-nav-tree .larryms-this>a{background-color:{{d.navThis}} !important;}", ".larryms-body .larryms-left{border-right:2px solid {{d.leftRight}} !important;}", ".layui-bg-black{background-color:{{d.leftColor}} !important;}", ".larryms-body .larryms-left{background:{{d.leftColor}} !important;}", "ul.larryms-tab-title .layui-this{background:{{d.navThis}} !important;}", ".larryms-right .larryms-tab .larryms-title-box{border-bottom:1px solid  {{d.titBottom}};}", ".larryms-right .larryms-tab .larryms-title-box .larryms-tab-title{border-bottom:1px solid  {{d.titBottom}};}", ".larryms-layout .larryms-footer{background:{{d.footColor}} !important;color:{{d.footFont}} !important;}"].join("")).render(o),
            a = document.getElementById(t);
        if ("styleSheet" in e) {
            e.setAttribute("type", "text/css");
            e.styleSheet.cssText = r
        } else {
            e.innerHTML = r
        }
        e.id = t;
        a && m[0].removeChild(a);
        m[0].appendChild(e);
        l.theme = l.theme || {};
        layui.each(o, function (o, t) {
            l.theme[o] = t
        });
        layui.data("larryms", {key: "theme", value: l.theme})
    };
    v.prototype.init = function () {
        var o = this, t = layui.data("larryms").theme, e = layui.data("larryms").systemSet;
        if (t !== undefined) {
            console.log(t.name);
            o.theme(t);
            if (t.name == "default") {
                a("#Larryms_theme_style").empty()
            }
        }
        if (e !== undefined) {
            l.tabSet({tabSession: e.tabCache, autoRefresh: e.tabRefresh});
            a("#larry_footer").data("show", e.footSet)
        } else {
            layui.data("larryms", {
                key: "systemSet",
                value: {
                    tabCache: l.config.tabSession,
                    tabRefresh: l.config.autoRefresh,
                    fullScreen: false,
                    footSet: a("#larry_footer").data("show")
                }
            })
        }
        b()
    };
    v.prototype.footInit = function (o) {
        a("#larry_footer").data("show", o);
        b()
    };

    function b() {
        if (a("#larry_footer").data("show") !== "on") {
            a("#larry_footer").hide();
            a("#larry_right").css({bottom: "0px"})
        } else {
            a("#larry_footer").show();
            a("#larry_right").css({bottom: "40px"})
        }
    }

    a(window).on("resize", function () {
        var o = a(window).width();
        if (o >= 1200) {
            a("#larry_layout").removeClass("larryms-mobile-layout");
            a("#larry_layout").addClass("larryms-unfold").removeClass("larryms-fold");
            a("#menufold").children("i.larry-icon").addClass("yun-fold-left").removeClass("yun-icon-collapse-left")
        } else if (o > 767 && o < 1200) {
            a("#larry_layout").removeClass("larryms-mobile-layout");
            a("#larry_layout").addClass("larryms-fold").removeClass("larryms-unfold");
            a("#menufold").children("i.larry-icon").addClass("yun-icon-collapse-left").removeClass("yun-fold-left")
        } else if (o <= 767 && o > 319) {
            a("#larry_layout").removeClass("larryms-fold");
            a("#larry_layout").removeClass("larryms-unfold")
        } else if (o <= 319) {
            n.error("主人别拖了，没有屏幕宽度小于320的，布局会乱的！", n.tit[1])
        }
    }).resize();
    var C = new v;
    C.init();
    o("Index", C)
});