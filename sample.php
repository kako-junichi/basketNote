body {
margin: 0;
padding: 0;
line-height: 150%;
font-family: 'Cormorant Garamond', serif;
background-image: url("/baskenote/img/sabri-tuzcu-190600-unsplash.jpg");
background-size: cover;
}

h1,
h2,
h3,
h4,
h5,
h6,
p {
margin: 0;
padding: 0;
}

/*=================================
リンク
=================================*/

a {
color: white;
}

a:hover {
text-decoration: none;
}

/*=================================
ヘッダー
==================================*/

header {
margin-bottom: 30px;
width: 100%;
height: 80px;
}



header h1 {
margin: 0;
float: left;
padding-left: 10px;
font-size: 30px;
line-height: 80px;
}

header h1 a {
text-decoration: none;
color: white;
text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.5);
}

.site-width img {
width: 10%;
height: 85px;
float: left;
}

/*===================================
ナビゲーション
====================================*/
#top-nav {
position: relative;
float: right;
width: 500px;
height: 90px;
}

nav a {
padding: 10px 15px;
color: white;
text-decoration: none;
font-size: 25px;
font-weight: bold;
text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.5);
}

nav a:hover {
text-decoration: underline;
}

#top-nav ul {
position: absolute;
top: 0;
right: 0;
bottom: 0;
margin: 0;
width: 450px;
height: 80px;
list-style: none;
line-height: 80px;
}

#top-nav ul li {
float: right;
height: 80px;
}

.page-1colum #main {
width: 100%;
}

#contents {
overflow: hidden;
margin: 20px auto;
}

#main #top-nav {
margin-top: 300px;
margin-left: 130px;
float: none;
}



#main #top-nav ul li a {
padding: 0.5em 1em;
height: 30%;
margin-left: 45px;
text-decoration: none;
background: white;
color: #b2b2b2;
box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.29);
border-bottom: solid 3px #627295;
border-radius: 10px;
font-weight: bold;
text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.5);
}

#main #top-nav ul li a:active {
transform: translateY(4px);
box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);
border-bottom: none;
}

/*==================================
レイアウト
==================================*/

.site-width {
margin: 0 auto;
width: 980px;
}

#main {
float: left;
min-height: 600px;
}

.page-1colum #main {
width: 100%;
}

.page-2colum #main {
width: 760px;
}

.page-logined #main {
box-sizing: border-box;
border: 2px solid #F68655;
background: white;
opacity: 0.9;
}

.page-1colum .form-container {
margin: 80px auto;
}

.page-main #main h1 {
text-align: center;
font-size: 60px;
margin-top: 200px;
}

.page-main #main h1 a {
text-decoration: none;
text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.5);
}

.page-title {
margin-bottom: 50px;
padding-top: 20px;
text-align: center;
font-size: 32px;
}

/*===================================
サイドバー
===================================*/

#sidebar {
float: left;
box-sizing: border-box;
margin-right: 20px;
padding: 15px;
min-height: 600px;
width: 200px;
background: #f17816;
opacity: 0.8;
}

#sidebar .title {
text-align: left;
font-size: 14px;
}

#sidebar .selectbox {
position: relative;
}

#sidebar select {
padding: 10px;
width: 100%;
border-color: #ccc;
border-radius: 0;
font-size: 14px;

-webkit-appearance: none;
-moz-appearance: none;
appearance: none;
}

#sidebar .icn_select:after {
position: absolute;
top: 15px;
right: 10px;
display: block;
width: 0;
height: 0;
border-top: 10px solid #333;
border-right: 7px solid transparent;
border-bottom: 10px solid transparent;
border-left: 7px solid transparent;
content: "";
}

.page-logined #sidebar {
margin: 0 0 0 20px;
}

.page-logined #sidebar > a {
display: block;
margin-bottom: 15px;
}

/*===================================
フォーム
===================================*/
label {
display: block;
color: #696969;
font-weight: bold;
}

label.err input,
label.err select {
background: #f7dcd9;
}

input[type="text"],
input[type="password"],
input[type="number"],
.form select,
textarea {
margin-bottom: 20px;
font-size: 18px;
box-sizing: border-box;
padding: 10px;
width: 100%;
height: 60px;
border: none;
display: block;
border-bottom: 2px solid #ffb23b;
}

input[type="checkbox"] {
width: 18px;
height: 18px;
background: #f6f5f5;
vertical-align: middle;
}

input[type="number"] {
width: 100px;
text-align: left;
}

input[type="text"]:focus,
input[type="password"]:focus,
input[type="number"] {
outline: 0;
border-color: #F17816;
}

input[type="submit"] {
font-size: 14px;
margin: 15px 0;
padding: 15px 30px;
width: 100%;
border: none;
background: #F17816;
color: white;
}

select:hover,
input[type="submit"]:hover {
cursor: pointer;
}

.form-container {
margin: 0 auto;
}

.form {
margin: 0 auto;
padding: 30px;
width: 400px;
background: white;
border: 2px solid #F68655;
opacity: 0.9;
}

.form .label-require {
margin-left: 5px;
background: #fe8a8b;
font-size: 14px;
padding: 3px 5px;
color: white;
vertical-align: bottom;
}

.page-logined .form {
border: none;
}

.form .title {
margin-bottom: 40px;
text-align: center;
color: #696969;
}

.form .area-msg {
padding: 0 0 15px 0;
color: red;
}

.form .btn-container {
overflow: hidden;
}

.form .btn {
float: right;
}

.form a {
color: #2a2a2a;
}

.form .area-drop {
margin-bottom: 15px;
width: 100%;
height: 140px;
background: #f6f5f4;
color: #ccc;
text-align: center;
line-height: 150px;
position: relative;
box-sizing: border-box;
}

.form .input-file {
opacity: 0;
width: 100%;
height: 140px;
position: absolute;
top: 0;
left: 0;
z-index: 2;
}

.form .prev-img {
width: 100%;
position: absolute;
top: 0;
left: 0;
}

.form .imgDrop-container {
width: 33.333%;
float: left;
padding-right: 15px;
box-sizing: border-box;
}

.form .counter-text {
text-align: right;
}

/*フォームグループ
=====================================*/
.form-group {
position: relative;
overflow: hidden;
}

.form-group > input {
float: left;
}

.form-gorup > .option {
position: relative;
top: 40px;
display: block;
float: left;
padding-left: 10px;
}

/*====================================
その他
=====================================*/
.msg-slide {
position: fixed;
top: 0;
width: 100%;
height: 40px;
background: #F17816;
opacity: 0.8;
color: white;
text-align: center;
font-size: 16px;
line-height: 40px;
}

/*====================================
フッター
====================================*/
footer {
padding: 15px;
font-size: 16px;
width: 100%;
color: white;
text-align: center;
position: fixed;
bottom: 0;
}

footer a {
color: white;
text-decoration: underline;

}

.footer {
color: #5f5f5f;
font-weight: bold;
background: transparent;
}

.footer a {
color: #5f5f5f;
}

/*================================
ボタン
================================*/
.btn {
padding: 15px 20px;
text-decoration: none;
}

.btn:hover {
text-decoration: none;
}

.btn.btn-mid {
max-width: 150px;
}
