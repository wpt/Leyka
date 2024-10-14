<?php if( !defined('WPINC') ) die;
/** A hash of original/translated plugin lines to use when language pack files are not loaded yet. */

/** Get translated lines when locale hasn't been loaded yet */
function leyka_tmp__($line, $locale = false) {

    $locale = $locale === false ? get_locale() : $locale;

    if($line != __($line, 'leyka')) { // Locale already loaded // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
        return __($line, 'leyka'); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
    }

    $tmp_translations = [
        'Terms of donation service' => ['ru_RU' => 'Условия сервиса сбора пожертвований',],
        'Terms of personal data usage' => ['ru_RU' => 'Условия использования персональных данных'],
        'Thank you!' => ['ru_RU' => 'Спасибо!'],
        'Your donation completed. We are grateful for your help.' => ['ru_RU' => 'Ваше пожертвование завершено. Мы благодарим вас за помощь!'],
        'Payment failure' => ['ru_RU' => 'Ошибка платежа'],
        'We are deeply sorry, but for some technical reason we failed to receive your donation. Your money are intact. Please try again later!' => ['ru_RU' => 'Мы извиняемся, но по какой-то причине мы не смогли получить ваше пожертвование. Ваши деньги вернутся на ваш счёт. Пожалуйста, попробуйте ещё раз попозже!'],
//        '' => ['ru_RU' => ''],
        'Organization Terms of service text' => ['ru_RU' => 'Публичная оферта о заключении договора пожертвования<br>
<br>
#LEGAL_NAME# (#LEGAL_FACE_POSITION#: #LEGAL_FACE#),<br>
предлагает гражданам сделать пожертвование на ниже приведенных условиях:<br>
<br>
1. Общие положения<br>1.1. В соответствии с п. 2 ст. 437 Гражданского кодекса Российской Федерации данное предложение является публичной офертой (далее – Оферта).<br>
1.2. В настоящей Оферте употребляются термины, имеющие следующее значение:<br>«Пожертвование» - «дарение вещи или права в общеполезных целях»;<br>
«Жертвователь» - «граждане, делающие пожертвования»;<br>
«Получатель пожертвования» - «#LEGAL_NAME#».<br>
<br>
1.3. Оферта действует бессрочно с момента размещения ее на сайте Получателя пожертвования.<br>
1.4. Получатель пожертвования вправе отменить Оферту в любое время путем удаления ее со страницы своего сайта в Интернете.<br>
1.5. Недействительность одного или нескольких условий Оферты не влечет недействительность всех остальных условий Оферты.<br>
<br>
2. Существенные условия договора пожертвования:<br>
2.1. Пожертвование используется на содержание и ведение уставной деятельности Получателя пожертвования.<br>
2.2. Сумма пожертвования определяется Жертвователем.<br>
<br>
3. Порядок заключения договора пожертвования:<br>
3.1. В соответствии с п. 3 ст. 434 Гражданского кодекса Российской Федерации договор пожертвования заключается в письменной форме путем акцепта Оферты Жертвователем.<br>
3.2. Оферта может быть акцептована путем перечисления Жертвователем денежных средств в пользу Получателя пожертвования платежным поручением по реквизитам, указанным в разделе 5 Оферты, с указанием в строке «назначение платежа»: «пожертвование на содержание и ведение уставной деятельности», а также с использованием пластиковых карт, электронных платежных систем и других средств и систем, позволяющих Жертвователю перечислять Получателю пожертвования денежных средств.<br>
3.3. Совершение Жертвователем любого из действий, предусмотренных п. 3.2. Оферты, считается акцептом Оферты в соответствии с п. 3 ст. 438 Гражданского кодекса Российской Федерации.<br>
3.4. Датой акцепта Оферты – датой заключения договора пожертвования является дата поступления пожертвования в виде денежных средств от Жертвователя на расчетный счет Получателя пожертвования.<br>
<br>
4. Заключительные положения:<br>
4.1. Совершая действия, предусмотренные настоящей Офертой, Жертвователь подтверждает, что ознакомлен с условиями Оферты, целями деятельности Получателя пожертвования, осознает значение своих действий и имеет полное право на их совершение, полностью и безоговорочно принимает условия настоящей Оферты.<br>
4.2. Настоящая Оферта регулируется и толкуется в соответствии с действующим российском законодательством.<br>
<br>
5. Подпись и реквизиты Получателя пожертвования<br>
<br>
#LEGAL_NAME#<br>
<br>
ОГРН: #STATE_REG_NUMBER#<br>
ИНН/КПП: #INN#/#KPP#<br>
Адрес места нахождения: #LEGAL_ADDRESS#<br>
<br>
Банковские реквизиты:<br>
Номер банковского счёта: #BANK_ACCOUNT#<br>
Банк: #BANK_NAME#<br>
БИК банка: #BANK_BIC#<br>
Номер корреспондентского счёта банка: #BANK_CORR_ACCOUNT#<br>
<br>
<br>
#LEGAL_FACE_POSITION#<br>
#LEGAL_FACE#'],
        'Terms of donation service text for individual. Use <br> for line-breaks, please.' => ['ru_RU' => 'Публичная оферта о заключении договора пожертвования<br>
<br>
#LEGAL_NAME#,<br>
предлагает гражданам сделать пожертвование на ниже приведенных условиях:<br>
<br>
1. Общие положения<br>1.1. В соответствии с п. 2 ст. 437 Гражданского кодекса Российской Федерации данное предложение является публичной офертой (далее – Оферта).<br>
1.2. В настоящей Оферте употребляются термины, имеющие следующее значение:<br>«Пожертвование» - «дарение вещи или права в общеполезных целях»;<br>
«Жертвователь» - «граждане, делающие пожертвования»;<br>
«Получатель пожертвования» - «#LEGAL_NAME#».<br>
<br>
1.3. Оферта действует бессрочно с момента размещения ее на сайте Получателя пожертвования.<br>
1.4. Получатель пожертвования вправе отменить Оферту в любое время путем удаления ее со страницы своего сайта в Интернете.<br>
1.5. Недействительность одного или нескольких условий Оферты не влечет недействительность всех остальных условий Оферты.<br>
<br>
2. Существенные условия договора пожертвования:<br>
2.1. Пожертвование используется на содержание и ведение уставной деятельности Получателя пожертвования.<br>
2.2. Сумма пожертвования определяется Жертвователем.<br>
<br>
3. Порядок заключения договора пожертвования:<br>
3.1. В соответствии с п. 3 ст. 434 Гражданского кодекса Российской Федерации договор пожертвования заключается в письменной форме путем акцепта Оферты Жертвователем.<br>
3.2. Оферта может быть акцептована путем перечисления Жертвователем денежных средств в пользу Получателя пожертвования платежным поручением по реквизитам, указанным в разделе 5 Оферты, с указанием в строке «назначение платежа»: «пожертвование на содержание и ведение уставной деятельности», а также с использованием пластиковых карт, электронных платежных систем и других средств и систем, позволяющих Жертвователю перечислять Получателю пожертвования денежных средств.<br>
3.3. Совершение Жертвователем любого из действий, предусмотренных п. 3.2. Оферты, считается акцептом Оферты в соответствии с п. 3 ст. 438 Гражданского кодекса Российской Федерации.<br>
3.4. Датой акцепта Оферты – датой заключения договора пожертвования является дата поступления пожертвования в виде денежных средств от Жертвователя на расчетный счет Получателя пожертвования.<br>
<br>
4. Заключительные положения:<br>
4.1. Совершая действия, предусмотренные настоящей Офертой, Жертвователь подтверждает, что ознакомлен с условиями Оферты, целями деятельности Получателя пожертвования, осознает значение своих действий и имеет полное право на их совершение, полностью и безоговорочно принимает условия настоящей Оферты.<br>
4.2. Настоящая Оферта регулируется и толкуется в соответствии с действующим российском законодательством.<br>
<br>
5. Подпись и реквизиты Получателя пожертвования<br>
<br>
#LEGAL_NAME#<br>
<br>
ИНН: #INN#<br>
Адрес места нахождения: #LEGAL_ADDRESS#<br>
<br>
Банковские реквизиты:<br>
Номер банковского счёта: #BANK_ACCOUNT#<br>
Банк: #BANK_NAME#<br>
БИК банка: #BANK_BIC#<br>
Номер корреспондентского счёта банка: #BANK_CORR_ACCOUNT#<br>
<br>
#LEGAL_FACE#'],
        'Terms of personal data usage full text. Use <br> for line-breaks.' => ['ru_RU' => 'Согласие на обработку персональных данных

Пользователь, оставляя заявку, оформляя подписку, комментарий, запрос на обратную связь, регистрируясь либо совершая иные действия, связанные с внесением своих персональных данных на интернет-сайте #SITE_URL#, принимает настоящее Согласие на обработку персональных данных (далее – Согласие), размещенное по адресу #PD_TERMS_PAGE_URL#.

Принятием Согласия является подтверждение факта согласия Пользователя со всеми пунктами Согласия. Пользователь дает свое согласие организации «#LEGAL_NAME#», которой принадлежит сайт #SITE_URL# на обработку своих персональных данных со следующими условиями:

Пользователь дает согласие на обработку своих персональных данных, как без использования средств автоматизации, так и с их использованием.
Согласие дается на обработку следующих персональных данных (не являющимися специальными или биометрическими):
• фамилия, имя, отчество;
• адрес(а) электронной почты;
• иные данные, предоставляемые Пользователем.

Персональные данные пользователя не являются общедоступными.

1. Целью обработки персональных данных является предоставление полного доступа к функционалу сайта #SITE_URL#.

2. Основанием для сбора, обработки и хранения персональных данных являются:
• Ст. 23, 24 Конституции Российской Федерации;
• Ст. 2, 5, 6, 7, 9, 18–22 Федерального закона от 27.07.06 года №152-ФЗ «О персональных данных»;
• Ст. 18 Федерального закона от 13.03.06 года № 38-ФЗ «О рекламе»;
• Устав организации «#LEGAL_NAME#»;
• Политика обработки персональных данных.

3. В ходе обработки с персональными данными будут совершены следующие действия с персональными данными: сбор, запись, систематизация, накопление, хранение, уточнение (обновление, изменение), извлечение, использование, передача (распространение, предоставление, доступ), обезличивание, блокирование, удаление, уничтожение.

4. Передача персональных данных, скрытых для общего просмотра, третьим лицам не осуществляется, за исключением случаев, предусмотренных законодательством Российской Федерации.

5. Пользователь подтверждает, что указанные им персональные данные принадлежат лично ему.

6. Персональные данные хранятся и обрабатываются до момента ликвидации организации «#LEGAL_NAME#». Хранение персональных данных осуществляется согласно Федеральному закону №125-ФЗ «Об архивном деле в Российской Федерации» и иным нормативно правовым актам в области архивного дела и архивного хранения.

7. Пользователь согласен на получение автоматических (транзакционных) информационных сообщений с сайта #SITE_URL#. Персональные данные обрабатываются до отписки Пользователя от получения информационных сообщений.

8. Согласие может быть отозвано Пользователем либо его законным представителем, путем направления Отзыва согласия на электронную почту – #ADMIN_EMAIL# с пометкой «Отзыв согласия на обработку персональных данных». В случае отзыва Пользователем согласия на обработку персональных данных организация «#LEGAL_NAME#» вправе продолжить обработку персональных данных без согласия Пользователя при наличии оснований, указанных в пунктах 2 - 11 части 1 статьи 6, части 2 статьи 10 и части 2 статьи 11 Федерального закона №152-ФЗ «О персональных данных» от 27.07.2006 г. Удаление персональных данных влечет невозможность доступа к полной версии функционала сайта #SITE_URL#.

9. Настоящее Согласие является бессрочным, и действует все время до момента прекращения обработки персональных данных, указанных в п.7 и п.8 данного Согласия.

10. Место нахождения организации «#LEGAL_NAME#» в соответствии с учредительными документами: #LEGAL_ADDRESS#.'],
    ];

    return !empty($tmp_translations[$line]) && !empty($tmp_translations[$line][$locale]) ?
        $tmp_translations[$line][$locale] : __($line, 'leyka'); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText

}

__('Terms of personal data usage', 'leyka');
__('Terms of personal data usage full text. Use <br> for line-breaks.', 'leyka');
__('Terms of donation service', 'leyka');
__('Organization Terms of service text', 'leyka');
__('Thank you!', 'leyka');
__('Your donation completed. We are grateful for your help.', 'leyka');
__('Payment failure', 'leyka');
__('We are deeply sorry, but for some technical reason we failed to receive your donation. Your money are intact. Please try again later!', 'leyka');
__('Main statistics', 'leyka');
__('Recurring', 'leyka');
__('Donations dynamics', 'leyka');
__('Recurrings', 'leyka');
__('Recent donations', 'leyka');
__('Donation funded', 'leyka');
__('Donation refunded', 'leyka');
__('Donation failed', 'leyka');
__('Donation submitted', 'leyka');