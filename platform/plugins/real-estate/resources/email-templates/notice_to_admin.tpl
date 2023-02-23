{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" cellpadding="0" cellspacing="0" width="700" bgcolor="#f8f8f8">
                    <tr>
                        <td class="column" align="right">
                            <table>
                                <tbody>
                                <tr>
                                    <td align="right" style="padding: 20px 50px;">
                                        <p><strong>مرحبا, تم ارسال طلب حجزك وفقا للتفاصيل التالية وسوف يصلك بريد اخر عند تاكيد حجزك من المالك </strong></p>
                                        <p><img src="{{ site_url }}/vendor/core/core/base/images/emails/person.png"
                                                alt="From" width="20" style="margin-right: 10px;" /> {{ consult_name }}</p>
                                        <p><img src="{{ site_url }}/vendor/core/core/base/images/emails/email.png"
                                                alt="Email" width="20" style="margin-right: 10px;" /> {{ consult_email }}</p>
                                        <p><img src="{{ site_url }}/vendor/core/core/base/images/emails/phone.png"
                                                alt="Phone" width="20" style="margin-right: 10px;" /> {{ consult_phone }}</p>
                                        <p><img src="{{ site_url }}/vendor/core/core/base/images/emails/phone.png"
                                                alt="Link" width="20" style="margin-right: 10px;" /> <a href="{{ consult_link }}">{{ consult_subject }}</a></p>
                                        <p><img src="{{ site_url }}/vendor/core/core/base/images/emails/message.png"
                                                alt="Message" width="20" style="margin-right: 10px;" /> {{ consult_content }}</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
{{ footer }}
