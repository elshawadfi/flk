<?php

function erw_widget_language($lg)
{
    $lang = array();

    if ('en' == $lg) {
        $lang['preview'] = 'Preview';
        $lang['title'] = 'Exchange Rate';
        $lang['heading'] = 'Heading';
        $lang['base_currency'] = 'Base currency';
        $lang['сodes_currencies'] = 'Codes of currencies';
        $lang['сodes_currencies_open'] = 'new window will open';
        $lang['amount'] = 'Amount';
        $lang['language'] = 'Language';
        $lang['timezone'] = 'Timezone';
        $lang['theme'] = 'Theme';
        $lang['size_width'] = 'Size (width)';
        $lang['currency_code'] = 'Currency code';
        $lang['signature'] = 'Signed at bottom of widget';
        $lang['sizes']['100%'] = 'Auto size';
        $lang['generated_shortcode'] = 'Generated shortcode';
        // Themes
        $lang['themes']['primary'] = 'Dark blue';
        $lang['themes']['success'] = 'Green';
        $lang['themes']['info'] = 'Blue';
        $lang['themes']['warning'] = 'Yellow';
        $lang['themes']['danger'] = 'Red';
        $lang['themes']['default'] = 'Gray';
    } elseif ('ru' == $lg) {
        $lang['preview'] = 'Превью';
        $lang['title'] = 'Курс валют';
        $lang['heading'] = 'Заголовок';
        $lang['base_currency'] = 'Основная валюта';
        $lang['сodes_currencies'] = 'Коды валют';
        $lang['сodes_currencies_open'] = 'откроется в новом окне';
        $lang['amount'] = 'Сумма';
        $lang['language'] = 'Язык';
        $lang['timezone'] = 'Часовой пояс';
        $lang['theme'] = 'Цветовое оформление';
        $lang['size_width'] = 'Размер (ширина)';
        $lang['currency_code'] = 'Только коды валют';
        $lang['signature'] = 'Подпись внизу виджета';
        $lang['sizes']['100%'] = 'Авто размер';
        $lang['generated_shortcode'] = 'Шорткод для страниц';
        // Themes
        $lang['themes']['primary'] = 'Синий';
        $lang['themes']['success'] = 'Зеленый';
        $lang['themes']['info'] = 'Голубой';
        $lang['themes']['warning'] = 'Желтый';
        $lang['themes']['danger'] = 'Красный';
        $lang['themes']['default'] = 'Серый';
    } elseif ('it' == $lg) {
        $lang['preview'] = 'Anteprima';
        $lang['title'] = 'Tasso di cambio';
        $lang['heading'] = 'Intestazione';
        $lang['base_currency'] = 'Valuta di base';
        $lang['сodes_currencies'] = 'Codici di valute';
        $lang['сodes_currencies_open'] = 'si aprirà una nuova finestra';
        $lang['amount'] = 'Quantità';
        $lang['language'] = 'Linguaggio';
        $lang['timezone'] = 'Fuso orario';
        $lang['theme'] = 'Tema';
        $lang['size_width'] = 'Dimensioni (larghezza)';
        $lang['currency_code'] = 'Codice valuta';
        $lang['signature'] = 'Firmato in basso';
        $lang['sizes']['100%'] = 'Dimensione dell\'auto';
        $lang['generated_shortcode'] = 'Shortcode generato';
        // Themes
        $lang['themes']['primary'] = 'Blu scuro';
        $lang['themes']['success'] = 'Verde';
        $lang['themes']['info'] = 'Blu';
        $lang['themes']['warning'] = 'Giallo';
        $lang['themes']['danger'] = 'Rosso';
        $lang['themes']['default'] = 'Grigio';
    } elseif ('fr' == $lg) {
        $lang['preview'] = 'Aperçu';
        $lang['title'] = 'Taux de change';
        $lang['heading'] = 'Titre';
        $lang['base_currency'] = 'Devise de base';
        $lang['сodes_currencies'] = 'Codes de devises';
        $lang['сodes_currencies_open'] = 'nouvelle fenêtre s\'ouvrira';
        $lang['amount'] = 'Montant';
        $lang['language'] = 'La langue';
        $lang['timezone'] = 'Fuseau horaire';
        $lang['theme'] = 'Thème';
        $lang['size_width'] = 'Taille (largeur)';
        $lang['currency_code'] = 'Code de devise';
        $lang['signature'] = 'Signé en bas';
        $lang['sizes']['100%'] = 'Taille automatique';
        $lang['generated_shortcode'] = 'Généré Shortcode';
        // Themes
        $lang['themes']['primary'] = 'Bleu foncé';
        $lang['themes']['success'] = 'Vert';
        $lang['themes']['info'] = 'Bleu';
        $lang['themes']['warning'] = 'Jaune';
        $lang['themes']['danger'] = 'Rouge';
        $lang['themes']['default'] = 'Gris';
    } elseif ('es' == $lg) {
        $lang['preview'] = 'Avance';
        $lang['title'] = 'Tipo de cambio';
        $lang['heading'] = 'Título';
        $lang['base_currency'] = 'Moneda base';
        $lang['сodes_currencies'] = 'Códigos de monedas';
        $lang['сodes_currencies_open'] = 'se abrirá una nueva ventana';
        $lang['amount'] = 'Cantidad';
        $lang['language'] = 'Idioma';
        $lang['timezone'] = 'Zona horaria';
        $lang['theme'] = 'Tema';
        $lang['size_width'] = 'Tamaño (ancho)';
        $lang['currency_code'] = 'Código de moneda';
        $lang['signature'] = 'Firmado en la parte inferior';
        $lang['sizes']['100%'] = 'Tamaño automático';
        $lang['generated_shortcode'] = 'Código abreviado generado';
        // Themes
        $lang['themes']['primary'] = 'Azul oscuro';
        $lang['themes']['success'] = 'Verde';
        $lang['themes']['info'] = 'Azul';
        $lang['themes']['warning'] = 'Amarillo';
        $lang['themes']['danger'] = 'Rojo';
        $lang['themes']['default'] = 'Gris';
    } elseif ('de' == $lg) {
        $lang['preview'] = 'Vorschau';
        $lang['title'] = 'Wechselkurs';
        $lang['heading'] = 'Überschrift';
        $lang['base_currency'] = 'Hauptwährung';
        $lang['сodes_currencies'] = 'Codes von währungen';
        $lang['сodes_currencies_open'] = 'neues fenster wird geöffnet';
        $lang['amount'] = 'Menge';
        $lang['language'] = 'Sprache';
        $lang['timezone'] = 'Zeitzone';
        $lang['theme'] = 'Thema';
        $lang['size_width'] = 'Größe (Breite)';
        $lang['currency_code'] = 'Währungscode';
        $lang['signature'] = 'Unten signiert';
        $lang['sizes']['100%'] = 'Automatische skalierung';
        $lang['generated_shortcode'] = 'Generierter kurzwahlcode';
        // Themes
        $lang['themes']['primary'] = 'Dunkelblau';
        $lang['themes']['success'] = 'Grün';
        $lang['themes']['info'] = 'Blau';
        $lang['themes']['warning'] = 'Gelb';
        $lang['themes']['danger'] = 'Rot';
        $lang['themes']['default'] = 'Grau';
    } elseif ('cn' == $lg) {
        $lang['preview'] = '预习';
        $lang['title'] = '汇率';
        $lang['heading'] = '标题';
        $lang['base_currency'] = '基础货币';
        $lang['сodes_currencies'] = '货币代码';
        $lang['сodes_currencies_open'] = '新窗口将打开';
        $lang['amount'] = '量';
        $lang['language'] = '语言';
        $lang['timezone'] = '时区';
        $lang['theme'] = '颜色';
        $lang['size_width'] = '大小（宽度）';
        $lang['currency_code'] = '货币代码';
        $lang['signature'] = '在底部签名';
        $lang['sizes']['100%'] = '自动尺寸';
        $lang['generated_shortcode'] = '生成的简码';
        // Themes
        $lang['themes']['primary'] = '深蓝';
        $lang['themes']['success'] = '绿色';
        $lang['themes']['info'] = '蓝色';
        $lang['themes']['warning'] = '黄色';
        $lang['themes']['danger'] = '红';
        $lang['themes']['default'] = '灰色';
    } elseif ('id' == $lg) {
        $lang['preview'] = 'Preview';
        $lang['title'] = 'Kurs';
        $lang['heading'] = 'Heading';
        $lang['base_currency'] = 'Mata uang dasar';
        $lang['сodes_currencies'] = 'Kode mata uang';
        $lang['сodes_currencies_open'] = 'jendela baru akan terbuka';
        $lang['amount'] = 'Jumlah';
        $lang['language'] = 'Bahasa';
        $lang['timezone'] = 'Zona waktu';
        $lang['theme'] = 'Gaya';
        $lang['size_width'] = 'Ukuran (lebar)';
        $lang['currency_code'] = 'Kode mata uang';
        $lang['signature'] = 'Masuk di bagian bawah widget';
        $lang['sizes']['100%'] = 'Ukuran Otomatis';
        $lang['generated_shortcode'] = 'Hasilkan shortcode';
        // Themes
        $lang['themes']['primary'] = 'Biru tua';
        $lang['themes']['success'] = 'Hijau';
        $lang['themes']['info'] = 'Biru';
        $lang['themes']['warning'] = 'Kuning';
        $lang['themes']['danger'] = 'Merah';
        $lang['themes']['default'] = 'Kelabu';
    } elseif ('hi' == $lg) {
        $lang['preview'] = 'पूर्वावलोकन';
        $lang['title'] = 'विनिमय दर';
        $lang['heading'] = 'शीर्षक';
        $lang['base_currency'] = 'आधार मुद्रा';
        $lang['сodes_currencies'] = 'मुद्राओं के कोड';
        $lang['сodes_currencies_open'] = 'नई खिड़की खुल जाएगी';
        $lang['amount'] = 'रकम';
        $lang['language'] = 'भाषा';
        $lang['timezone'] = 'समय क्षेत्र';
        $lang['theme'] = 'डिज़ाइन';
        $lang['size_width'] = 'आकार (चौड़ाई)';
        $lang['currency_code'] = 'मुद्रा कोड';
        $lang['signature'] = 'विजेट के नीचे हस्ताक्षर किए';
        $lang['sizes']['100%'] = 'ऑटो साइज़';
        $lang['generated_shortcode'] = 'जेनरेटेड शोर्ट';
        // Themes
        $lang['themes']['primary'] = 'गहरा नीला';
        $lang['themes']['success'] = 'हरा';
        $lang['themes']['info'] = 'नीला';
        $lang['themes']['warning'] = 'पीला';
        $lang['themes']['danger'] = 'लाल';
        $lang['themes']['default'] = 'धूसर';
    } elseif ('ja' == $lg) {
        $lang['preview'] = 'プレビュー';
        $lang['title'] = '為替レート';
        $lang['heading'] = '見出し';
        $lang['base_currency'] = '基本通貨';
        $lang['сodes_currencies'] = '通貨コード';
        $lang['сodes_currencies_open'] = '新しいウィンドウが開きます';
        $lang['amount'] = '量';
        $lang['language'] = '言語';
        $lang['timezone'] = 'タイムゾーン';
        $lang['theme'] = 'スタイル';
        $lang['size_width'] = 'サイズ（幅）';
        $lang['currency_code'] = '通貨コード';
        $lang['signature'] = 'ウィジェットの下部に署名';
        $lang['sizes']['100%'] = '自動サイズ';
        $lang['generated_shortcode'] = '生成されたショートコード';
        // Themes
        $lang['themes']['primary'] = '濃紺';
        $lang['themes']['success'] = '緑';
        $lang['themes']['info'] = '青';
        $lang['themes']['warning'] = '黄';
        $lang['themes']['danger'] = '赤';
        $lang['themes']['default'] = 'グレー';
    } elseif ('pt' == $lg) {
        $lang['preview'] = 'Visualizar';
        $lang['title'] = 'Taxa de câmbio';
        $lang['heading'] = 'Título';
        $lang['base_currency'] = 'Moeda base';
        $lang['сodes_currencies'] = 'Códigos de Moedas';
        $lang['сodes_currencies_open'] = 'nova janela será aberta';
        $lang['amount'] = 'Montante';
        $lang['language'] = 'Língua';
        $lang['timezone'] = 'Fuso horário';
        $lang['theme'] = 'Estilo';
        $lang['size_width'] = 'Tamanho (largura)';
        $lang['currency_code'] = 'Código da moeda';
        $lang['signature'] = 'Assinado na parte inferior do widget';
        $lang['sizes']['100%'] = 'Tamanho automático';
        $lang['generated_shortcode'] = 'Código curto gerado';
        // Themes
        $lang['themes']['primary'] = 'Azul escuro';
        $lang['themes']['success'] = 'Verde';
        $lang['themes']['info'] = 'Azul';
        $lang['themes']['warning'] = 'Amarelo';
        $lang['themes']['danger'] = 'Vermelho';
        $lang['themes']['default'] = 'Cinzento';
    }

    $lang['sizes']['200px'] = '200px';
    $lang['sizes']['300px'] = '300px';

    return $lang;
}
