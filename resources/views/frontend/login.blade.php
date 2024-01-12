<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexo - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Bitter&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Bitter', serif;
            background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            margin: 0;
            padding: 0;
        }

        img {
            width: 100%;
            height: 100%;
        }

        a {
            text-decoration: none;
        }

        #preloader {
            backdrop-filter: blur(20px);
            z-index: 20;
            width: 100%;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #preloader .loader {
            width: 150px;
            height: 150px;
            background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            border-radius: 50%;
            position: relative;
            box-shadow: 0 0 30px 4px rgba(0, 0, 0, 0.5) inset,
                0 5px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        #preloader .loader:before,
        #preloader .loader:after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 45%;
            top: -40%;
            background-color: #fff;
            animation: wave 5s linear infinite;
        }

        #preloader .loader:before {
            border-radius: 30%;
            background: rgba(255, 255, 255, 0.4);
            animation: wave 5s linear infinite;
        }

        @keyframes wave {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #login-container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #login-container .login-form {
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            background: rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        #login-container .login-form .image-block {
            width: 100%;
            aspect-ratio: 1/1.3;
            height: 100%;
        }

        #login-container .login-form .form-block {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #login-container .login-form .form-block .logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #login-container .login-form .form-block .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        #login-container .login-form .form-block .head {
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        #login-container .login-form .form {
            width: 100%;
            display: block;
        }

        #login-container .login-form .form-block label {
            font-size: 0.9rem;
            margin-bottom: 3px;
            color: rgb(0, 0, 0);
            display: block;
            font-weight: bold;
        }

        #login-container .login-form .form-block input,
        #login-container .login-form .form-block select {
            display: block;
            width: 100%;
            max-width: 560px;
            height: 100%;
            margin-bottom: 20px;
            font-size: 0.9rem;
            padding: 3px 0px;
            max-height: 32px;
            background: white;
        }

        #login-container .login-form .form-block input[type="submit"] {
            margin-bottom: 0;
            background: #4274da;
            border: 1px solid #4274da;
            color: white;
            font-weight: bold;
            padding: 7px 10px;
            transition: all 0.3s linear;
            height: auto;
            font-family: 'Cinzel', serif;
            text-transform: uppercase;
            cursor: pointer;
            letter-spacing: 1px;
        }

        #login-container .login-form .form-block input[type="submit"]:hover {
            letter-spacing: 4px;
        }

        #login-container .login-form .form-block .forgot {
            font-size: 0.85rem;
            color: black;
            display: block;
            margin-bottom: 10px;
            text-align: center;
            max-width: 560px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .skiptranslate.goog-te-gadget {
            height: 34px;
            margin-bottom: 20px;
            overflow: hidden;
        }
    </style>
</head>

<body>

    {{-- ======================================
                    PRELOADER
    ======================================= --}}
    <div id="preloader">
        <span class="loader"></span>
    </div>

    {{-- ======================================
                    LOGIN FORM
    ======================================= --}}
    <div id="login-container">
        <div class="login-form">
            <div class="image-block">
                <img src="{{ asset('user/images/login.jpg') }}" alt="..." class="w-100 h-100">
            </div>
            <div class="form-block">
                <div class="logo-container">
                    <div class="logo">
                        <img src="{{ asset('user/images/logo.png') }}" alt="..." class="w-100 h-100">
                    </div>
                    <div class="logo">
                        <img src="{{ asset('user/images/logo1.png') }}" alt="..." class="w-100 h-100">
                    </div>
                </div>
                <div class="head">
                    Welcome to DMS
                </div>
                <div class="form">
                    <form action="{{ url('logincheck') }}" method="POST">
                        @csrf
                        <div class="group-input">
                            <label for="username">Username</label>
                            <input type="text" name="email">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span> <br> <br>
                        @enderror

                        <div class="group-input">
                            <label for="password">Password</label>
                            <input type="password" name="password">
                        </div>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            <br><br>
                        @enderror

                        <div class="group-input">
                            <label for="language">Language</label>
                            <select class="goog-te-combo">
                                <option value="">Select Language</option>
                                <option value="af">Afrikaans</option>
                                <option value="sq">Albanian</option>
                                <option value="ar">Arabic</option>
                                <option value="hy">Armenian</option>
                                <option value="az">Azerbaijani</option>
                                <option value="eu">Basque</option>
                                <option value="be">Belarusian</option>
                                <option value="bn">Bengali</option>
                                <option value="bg">Bulgarian</option>
                                <option value="ca">Catalan</option>
                                <option value="zh-CN">Chinese (Simplified)</option>
                                <option value="zh-TW">Chinese (Traditional)</option>
                                <option value="hr">Croatian</option>
                                <option value="cs">Czech</option>
                                <option value="da">Danish</option>
                                <option value="nl">Dutch</option>
                                <option value="en" selected>English</option>
                                <option value="eo">Esperanto</option>
                                <option value="et">Estonian</option>
                                <option value="tl">Filipino</option>
                                <option value="fi">Finnish</option>
                                <option value="fr">French</option>
                                <option value="gl">Galician</option>
                                <option value="ka">Georgian</option>
                                <option value="de">German</option>
                                <option value="el">Greek</option>
                                <option value="gu">Gujarati</option>
                                <option value="ht">Haitian Creole</option>
                                <option value="iw">Hebrew</option>
                                <option value="hi">Hindi</option>
                                <option value="hu">Hungarian</option>
                                <option value="is">Icelandic</option>
                                <option value="id">Indonesian</option>
                                <option value="ga">Irish</option>
                                <option value="it">Italian</option>
                                <option value="ja">Japanese</option>
                                <option value="kn">Kannada</option>
                                <option value="ko">Korean</option>
                                <option value="la">Latin</option>
                                <option value="lv">Latvian</option>
                                <option value="lt">Lithuanian</option>
                                <option value="mk">Macedonian</option>
                                <option value="ms">Malay</option>
                                <option value="mt">Maltese</option>
                                <option value="no">Norwegian</option>
                                <option value="fa">Persian</option>
                                <option value="pl">Polish</option>
                                <option value="pt">Portuguese</option>
                                <option value="ro">Romanian</option>
                                <option value="ru">Russian</option>
                                <option value="sr">Serbian</option>
                                <option value="sk">Slovak</option>
                                <option value="sl">Slovenian</option>
                                <option value="es">Spanish</option>
                                <option value="sw">Swahili</option>
                                <option value="sv">Swedish</option>
                                <option value="ta">Tamil</option>
                                <option value="te">Telugu</option>
                                <option value="th">Thai</option>
                                <option value="tr">Turkish</option>
                                <option value="uk">Ukrainian</option>
                                <option value="ur">Urdu</option>
                                <option value="vi">Vietnamese</option>
                                <option value="cy">Welsh</option>
                                <option value="yi">Yiddish</option>
                            </select>
                        </div>

                        <div class="group-input">
                            <label for="timezone">Time Zone</label>
                            <select name="timezone">
                                @foreach ($timezones as $key => $value)
                                    <option value="{{ $key }}" {{ $key == 'Asia/Kolkata' ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="forgot">
                            <a href="forgot-password">Forgot Password</a>
                        </div>

                        <div class="group-input">
                            <input type="submit" value="Login">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>



    {{-- ======================================
                    SCRIPT TAGS
    ======================================= --}}
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        function googleTranslateElementInit() {
            setCookie('googtrans', '/en/pt', 1);
            new google.translate.TranslateElement({
                pageLanguage: 'en'
            }, 'google_translate_element');
        }

        window.onload = function() {
            document.querySelector("#preloader").style.display = "none";
        }
    </script>
    
    @toastr_js
    @toastr_render
    @jquery

</body>

</html>
