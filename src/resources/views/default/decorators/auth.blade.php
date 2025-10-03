<style>
    .userMenu {
        display: inline-block;
        float: right;
        margin-top: 10px;
        margin-right: 10px;
        cursor: pointer;
        position: relative;
    }

    .userMenu a {
        color: #fff;
    }

    .userMenu a:hover {
        color: #fff;
    }

    .userMenu a:visited {
        color: #fff;
    }

    #docsearch_form {
        display: inline-block;
        width: calc(100% - 100px)
    }

    .profile_settings_links {
        padding: 0;
        margin: 0;
        list-style: none;
        text-align: center;
    }

    .profile_settings_wrap {
        position: relative;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 1px 2px 8px 0 rgba(0, 0, 0, 0.05);
        border-radius: 14px;
        padding-top: 27px;
        padding-bottom: 9px;
    }

    .dropdown-pane#sub_menu {
        background-color: transparent;
        border: none;
        width: 115px;
        padding: 0;
    }

    .dropdown_active {
        visibility: visible;
        position: absolute;
        top: 30px;
        right: -5px;
        z-index: 4;
    }

    .profile_settings_wrap::before {
        content: '';
        position: absolute;
        right: 28px;
        top: -15px;
        border: 8px solid transparent;
        border-bottom: 8px solid #fff;
    }

    .profile_settings_links a span {
        display: block;
        font-size: 10px;
        color: #2b2c2f;
        text-align: left;
    }

    .profile_settings_links span {
        cursor: pointer;
    }
    .profile_settings_links button {
        padding: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border: 0;
        border-radius: 0;
        background: transparent;
        line-height: 1;
        cursor: auto;
        color: #2b2c2f;
        font-size: 10px;
        font-family: "ArialNova";
    }

    .profile_settings_links li {
        padding: 0 20px;
    }

    .userMail {
        color: #2b2c2f;
        font-size: 10px;
        font-family: "ArialNova";
    }

    .ico_exit {
        display: block;
        width: 33px;
        height: 33px;
        background-image: url(/wi_attemps/images/logout.png);
        background-size: cover;
        margin: 0 auto;
        margin-bottom: 13px;
    }

    .ico_settings {
        display: block;
        width: 31px;
        height: 31px;
        background-image: url(/wi_attemps/images/user.png);
        background-size: cover;
        margin: 0 auto;
        margin-bottom: 13px;
        margin-right: 4px;
    }

</style>

<script>
    $("#loadingElement").css("display","block");
    setTimeout(() => {
        $(".userMenu").click(function() {
            if ($("#sub_menu").css("display") == "none") {
                $("#sub_menu").css("display","block")
            } else {
                $("#sub_menu").css("display","none")
            }
        })
    },100)
</script>

@guest
    <div id="userMenu" class="userMenu" style="display: inline-block;">
        <a href="{{ route('auth.loginForm') }}" itemprop="url">
            <span itemprop="title">Вход / Регистрация</span>
        </a>
    </div>
@else
    <div id="userMenu" class="userMenu" style="display: inline-block;">Профиль<div class="dropdown-pane dropdown_active" id="sub_menu" data-position="bottom" data-alignment="center" data-dropdown="" data-auto-focus="true" style="padding-top: 0; width: auto; display: none">
            <div class="profile_settings_wrap">
                <ul class="profile_settings_links">
                    <li>
                        <a href="https://hub.exponenta.ru/profile">
                            <i class="ico_settings"></i>
                            <span>Профиль</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('auth.logout') }}">
                            <i class="ico_exit"></i>
                            <span style="text-align: center">Выход</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div></div>
@endguest
