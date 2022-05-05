<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div style="margin:0;padding:0">
        <table border="0" cellpadding="0" cellspacing="0" style="width:750px;margin:40px auto;padding:0">
            <colgroup>
                <col style="width:100%">
            </colgroup>
            <tbody>
                <tr style="margin:0;padding:0">
                    <td style="margin:0;padding:0">
                        <table border="0" cellpadding="0" cellspacing="0"
                            style="width:100%;margin:0;padding:0;table-layout:fixed">
                            <colgroup>
                                <col style="width:100%">
                            </colgroup>
                            <tbody>
                                <tr style="margin:0;padding:0">
                                    <td style="width:100%;height:13px;margin:0;padding:0;font-size:0;line-height:0">
                                        <img src="https://ci5.googleusercontent.com/proxy/x2xeRM7-0xRn7SKnsyV91dgYiRMjNnkc5mmEUpsLwnEckncNQKZcxJlmOQ2d86slTEFYGc8_FJvPgXRGnJaNSYro5oEfU9ETds47TYiLkQ=s0-d-e1-ft#http://www.lottecinemavn.com/LCHS/Image/Mail/bg_mail_top.gif"
                                            alt="" class="CToWUd">
                                    </td>
                                </tr>
                                <tr style="margin:0;padding:0">
                                    <td
                                        style="width:100%;height:auto;margin:0;padding:20px 30px 27px;background:url(https://ci6.googleusercontent.com/proxy/5QylrUPNxHbZAWzWplpnumcOqKk_ymglu6gfZIzYT9SBY5CYGKeqAse0YuWH1cFKTDCMDB6d8eNKfpjNJGtJJYdY9tcHHzxh7RRsdSzk2Qg=s0-d-e1-ft#http://www.lottecinemavn.com/LCHS/Image/Mail/bg_mail_body.gif) repeat-y">
                                        <table border="0" cellpadding="0" cellspacing="0"
                                            style="width:100%;margin:0;padding:0">
                                            <colgroup>
                                                <col style="width:100%">
                                            </colgroup>
                                            <tbody>
                                                <tr style="margin:0;padding:0">
                                                    <td style="height:20px;margin:0;padding:0;font-size:0">&nbsp;</td>
                                                </tr>
                                                <tr style="margin:0;padding:0">
                                                    <td
                                                        style="text-align:center;margin:0;padding:0 0 19px 0;font-size:22px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;color:#000">
                                                        Order info</td>
                                                </tr>
                                                <tr style="margin:0;padding:0">
                                                    <td style="margin:0;padding:0;vertical-align:top">
                                                        <table border="0" cellpadding="0" cellspacing="0"
                                                            style="width:690px;margin:0;padding:0;border-top:1px solid #999">
                                                            <colgroup>
                                                                <col style="width:108px">
                                                                <col style="width:582px">
                                                            </colgroup>
                                                            <thead>
                                                                <tr style="margin:0;padding:0">
                                                                    <th colspan="2"
                                                                        style="height:50px;margin:0;padding:5px 20px;margin:0;line-height:25px;font-size:16px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;color:#231f20;text-align:left">
                                                                        Date order: {{ $data['date'] }}<span
                                                                            class="im"><br>Status :
                                                                            <span style="color:#cd190b">Available</span>
                                                                        </span></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr style="margin:0;padding:0">
                                                                    <td
                                                                        style="padding:25px 0;margin:0;border-top:1px solid #dedede;text-align:center;vertical-align:top">
                                                                        <img src="{{ $data['image'] }}"
                                                                            alt="{{ $data['name'] }}" border="0"
                                                                            style="border:1px solid #e7e7e7"
                                                                            width="100%" height="100%"
                                                                            class="CToWUd">
                                                                    </td>
                                                                    <td
                                                                        style="padding:25px 30px;margin:0;border-top:1px solid #dedede;vertical-align:top">
                                                                        <table border="0" cellpadding="0"
                                                                            cellspacing="0"
                                                                            style="margin:0;padding:0;width:100%;font-size:14px;color:#231f20;font-family:Arial,Helvetica,sans-serif;line-height:18px;table-layout:fixed">
                                                                            <colgroup>
                                                                                <col style="width:100%">
                                                                            </colgroup>
                                                                            <tbody>
                                                                                <tr style="margin:0;padding:0">
                                                                                    <td
                                                                                        style="margin:0;padding:0;vertical-align:top;font-weight:bold;color:#231f20;font-size:16px">
                                                                                        <div style="margin:0;padding:0">
                                                                                            <span class="im">
                                                                                                <strong
                                                                                                    style="display:block;margin:0;padding:0 0 5px 0">{{ $data['name'] }}</strong>
                                                                                            </span>
                                                                                            <ul
                                                                                                style="list-style:none;margin:0;padding:0;font-weight:normal;font-size:14px;line-height:20px">
                                                                                                <span
                                                                                                    class="im">
                                                                                                    <li
                                                                                                        style="margin:0;padding:0">
                                                                                                        <span
                                                                                                            style="margin:0;padding:0;color:#666">Price:</span>
                                                                                                        <span
                                                                                                            style="margin:0;padding:0;color:#231f20">{{ $data['total'] }}</span>
                                                                                                    </li>
                                                                                                </span>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="margin:0;padding:0">
                                    <td style="width:100%;height:33px;margin:0;padding:0"> <img
                                            src="https://ci4.googleusercontent.com/proxy/poT1MMRiQ3gdJsfThuI886IxF7nI33NbCAeuD10ofsKaMrsA_fSLWZEYjUajTaMEA8_DcnFeYLQ5FGxMWBYawl93ZLTIj5X7tcc1HoEqFrV2aw=s0-d-e1-ft#http://www.lottecinemavn.com/LCHS/Image/Mail/bg_mail_bottom.gif"
                                            alt="" class="CToWUd"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
