=== iMoney ===
Contributors: itex
Donate link: http://itex.name/donation/
Author link: http://itex.name/
Tags: automatic, link, links, seo, widget, sidebar, plugin, google, adsense, tnx, sape, html, php, linkfeed, xap, mainlink, txt, begun, trustlink, adskape, admitad
Requires at least: 2.3
Tested up to: 3.3.2
Stable tag: 0.34

== Description ==

Plugin iMoney is meant for monetize your blog using Adsense, sape.ru, tnx.net and other systems.

Features:

Placing Ads or links up to the text of page, after page of text in the widget and footer.

Widget course customizable.

Automatic installation of a plug and the rights to the sape and tnx folder on request.

Adjustment of amount of displayed links depending on the location.



== Installation ==

Requirements:
Wordpress 2.3 or higher
PHP5, PHP4
Widget compatible theme, to use the links in widgets.

Copy the file iMoney.php in wp-content/plugins .

In Plugins activate iMoney.

In settings-> iMoney.



Adsense - enter your Adsense id.

Customize the size, position and channel ads blocks as you want.



Sape - enter your Sape Uid.

If you want to create a Sape folder automatically, coinciding with your Sape Uid.

Allow work Sape links, to specify how many references to the use of text after text, widget and footer.

Allow Sape context.

If you are adding content frequently , then content links of main page, tags and categories can return error.

If you frequently add content, the content of the main links, tags, categories can fly out in error.

For preventation of it switch on the option "Show context only on Pages and Posts".

As required switch on Check - a verification code.



Tnx/xap - enter your Tnx Uid.

If you want to create a Tnx folder automatically, coinciding with your Tnx Uid.

Allow work Tnx links, to specify how many references to the use of text after text, widget and footer.

If you are adding content frequently , then content links of main page, tags and categories can return error.

As required switch on Check - a verification code.



Html - Enter your html code in the right place



For activating widget you shall go to a design-> widgets, activate the widget and point its title.

If define ('WPLANG', 'ru_RU'); in wp-config.php then russian language;



== Frequently Asked Questions ==

Visit the <a href="http://itex.name/plugins/faq-po-imoney-i-isape.html">iMoney FAQ</a> for more information.

== Screenshots ==
1. screenshot-1.png

Thanks http://mywordpress.ru/ for the screenshot.


== Arbitrary section ==

-

== A brief Markdown Example ==

-

== Changelog ==

Ru
0.35
исправлен баг добавления кода для бирж

0.34
Добавлен admitad.com.
Мелкие правки.

0.33
Добавлен параметр принудительного показа кода и ссылок, полезно при кешировании.
Мелкие правки.

0.31-0.32
Правки для выполнения требований репозитария wordpress.org. Переписан код, подозрительный для статических анализаторов.

0.30 16-06-2011
Добавлены прототипы работы с beta.serpzilla.com
Подправлена работа с sape
Мелкое переписывание админской части

0.29
Подправлен код для работы с трастлинк
Сделано обновление кода трастлинка из 

0.28
Сделаны первые шаги к портированию на джумлу и друпал
Решена мелкая проблема с token_get_all

0.27
Решена  мелкая проблема с кодировкой в sape articles
Добавлен trustlink
Добавлено поздравление с новым годом
Обновлен файл sape.php

0.26
Переделан вывод sape articles учтены проблемы у пользователей.
Добавлен шаблон sape articles.
Дабавлены анонсы sape articles.
Добавлен тестовый вариант sape articles.
Решены проблемы с индексацией при кеше, нужно включать дебуг и чеккоды быдут всегда показаны.
Локализация переведена на po-mo файлы.
исправдены мелкие баги.

0.25
Добавлен Teasernet.
Заменены ereg на preg_match.
Добавлено поздравление с 9 Мая.

0.24
Добавлен Setkinks от Zia

0.23
Времмено решено с StringReader в WP2.9

0.22
Решена проблема с StringReader в WP2.8

0.21
Поправлены мелкие баги с секции мейнлинка
Добавляен параметр paged в safe_url

0.20
Добавлены слова в перевод.
Добавлена поддержка PHP вставок.

0.19
Исправлен баг с регистрацией динамического виджета.
Исправлен баг показа предложения создать папку в сапе
Добавлено редактирование заголовка динамических виджетов

0.18
Добвален adskape.ru
Сделан дебаг только зарегистрировавшимся пользователям. Есть параметр, чтоб всем разрешить смотреть.
Переделана система виджетов
Протестирована совместимость с 2.7.1.

0.17
Добавлен linkfeed.
Добавлено использование памяти плагином и файлами бирж ссылок.
Исправлен баг с контекстом сапы.
Добавлен разделитель ссылок из сапы.
Подправлена админка.

0.16
Переработан дизайн настроек, добавлено верхнее меню и сворачивание настроек в более компактный вид.
Вынесена маскировка ссылок в глобальный параметр, маскировка должна работать со всеми биржами.
Добавлен дебаг с выводом в футер.

== Upgrade Notice ==
-
