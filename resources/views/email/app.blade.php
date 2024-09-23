<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>
    <meta name="viewport" content="width=600,initial-scale = 2.3,user-scalable=no">
    <!--[if !mso]>-->
    <link href='https://fonts.googleapis.com/css?Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i'
          rel="stylesheet">
    <!-- <![endif]-->

    <title></title>

    <style type="text/css">
        @font-face {
            font-family: 'dejavu_sansextralight';
            src: url('https://zoombus.net/fonts/dejavusans-extralight-webfont.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            font-family: 'Roboto', sans-serif;
            mso-margin-top-alt: 0px;
            mso-margin-bottom-alt: 0px;
            mso-padding-alt: 0px 0px 0px 0px;
        }

        table.language_ge {
            font-family: 'dejavu_sansextralight', sans-serif;
        }

        p,
        h1,
        h2,
        h3,
        h4 {
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        span.preheader {
            display: none;
            font-size: 1px;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }

        /* ----------- responsivity ----------- */

        @media only screen and (max-width: 640px) {
            /*------ top header ------ */
            .main-header {
                font-size: 20px !important;
            }

            .main-section-header {
                font-size: 28px !important;
            }

            .show {
                display: block !important;
            }

            .hide {
                display: none !important;
            }

            .align-center {
                text-align: center !important;
            }

            .no-bg {
                background: none !important;
            }

            /*----- main image -------*/
            .main-image img {
                width: 440px !important;
                height: auto !important;
            }

            /* ====== divider ====== */
            .divider img {
                width: 440px !important;
            }

            /*-------- container --------*/
            .container590 {
                width: 440px !important;
            }

            .container580 {
                width: 400px !important;
            }

            .main-button {
                width: 220px !important;
            }

            /*-------- secions ----------*/
            .section-img img {
                width: 320px !important;
                height: auto !important;
            }

            .team-img img {
                width: 100% !important;
                height: auto !important;
            }
        }

        @media only screen and (max-width: 479px) {
            /*------ top header ------ */
            .main-header {
                font-size: 18px !important;
            }

            .main-section-header {
                font-size: 26px !important;
            }

            /* ====== divider ====== */
            .divider img {
                width: 280px !important;
            }

            /*-------- container --------*/
            .container590 {
                width: 280px !important;
            }

            .container590 {
                width: 280px !important;
            }

            .container580 {
                width: 260px !important;
            }

            /*-------- secions ----------*/
            .section-img img {
                width: 280px !important;
                height: auto !important;
            }
        }
    </style>
    <!-- [if gte mso 9]>
    <style type=”text/css”>
        body {
            font-family: arial, sans-serif !important;
        }
    </style>
    <![endif]-->
</head>


<body class="respond" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- pre-header -->
<table style="display:none!important;">
    <tr>
        <td>
            <div
                style="overflow:hidden;display:none;font-size:1px;color:#ffffff;line-height:1px;font-family:Arial;maxheight:0px;max-width:0px;opacity:0;">
                {{ $pre_header ?? null }}
            </div>
        </td>
    </tr>
</table>
<!-- pre-header end -->
<!-- header -->
<table class="{{ $locale ?? null }}" border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff">

    <tr>
        <td align="center">
            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                <tr>
                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                </tr>

                <tr>
                    <td align="center">

                        <table border="0" align="center" width="590" cellpadding="0" cellspacing="0"
                               class="container590">

                            <tr>
                                <td align="center">
                                    <a href="#"
                                       style="display: block; border-style: none !important; border: 0 !important;"><img
                                            border="0" style="display: block;"
                                            src="{{ URL::asset('images/logo.svg') }}" alt=""/></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                </tr>

            </table>
        </td>
    </tr>
</table>
<!-- end header -->

<!-- big image section -->

<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color {{ $locale ?? null }}">

    <tr>
        <td align="center">
            <table border="0" width="590" cellpadding="0" cellspacing="0" class="container590">
                <tr>
                    <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center"
                        style="color: #343434; font-size: 24px; font-weight:700;letter-spacing: 3px; line-height: 35px;"
                        class="main-header">
                        <div style="line-height: 35px">
                            <span style="color: #f79904;">{!! $title ?? null  !!}</span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td height="40" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                </tr>

                <tr>
                    <td>
                        <table border="0" width="400" cellpadding="0" cellspacing="0"
                               class="container590">
                            <tr>
                                <td style="color: #888888; font-size: 16px; line-height: 24px;">

                                    <div style="line-height: 24px">
                                        {!! $text ?? null  !!}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                </tr>

                @isset($button_url)
                <tr>
                    <td align="center">
                        <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"
                               style="">

                            <tr>
                                <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td align="center" style="color: #ffffff; font-size: 14px; line-height: 26px;">
                                    <div style="line-height: 26px;">
                                        <a href="{{ $button_url ?? null }}"
                                           style="border-radius:3px; padding:10px 15px 13px; color: #ffffff; background-color:#f79904; text-decoration: none;">{{ $button_anchor ?? null }}</a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td height="40" style="font-size: 10px; line-height: 40px;">&nbsp;</td>
                            </tr>

                        </table>
                    </td>
                </tr>
                    @endisset


            </table>

        </td>
    </tr>

</table>

<!-- end section -->


<!-- footer ====== -->
<table class="{{ $locale ?? null }}" border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="f4f4f4">

    <tr>
        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
    </tr>

    <tr>
        <td align="center">

            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                <tr>
                    <td>
                        <table border="0" align="left" cellpadding="0" cellspacing="0"
                               style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                               class="container590">
                            <tr>
                                <td align="left" style="color: #aaaaaa; font-size: 14px; line-height: 24px;">
                                    <div style="line-height: 24px;">

                                        <span style="color: #333333;">Zoombus &copy; {{ date("Y") }}</span>

                                    </div>
                                </td>
                            </tr>
                        </table>

                        <table border="0" align="left" width="5" cellpadding="0" cellspacing="0"
                               style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                               class="container590">
                            <tr>
                                <td height="20" width="5" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                            </tr>
                        </table>

                        @isset($unsubscribe)
                            <table border="0" align="right" cellpadding="0" cellspacing="0"
                                   style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                   class="container590">
                                <tr>
                                    <td align="center">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center">
                                                    <a style="font-size: 14px; line-height: 24px;color: #f79904; text-decoration: none;font-weight:bold;"
                                                       href="{{ route('unsubscribe', ['id' => $user_id ?? 0]) }}">{{ $unsubscribe ?? Lang::get('misc.unsubscribe') }}</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>
                        @endisset
                    </td>
                </tr>

            </table>
        </td>
    </tr>

    <tr>
        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
    </tr>

</table>
<!-- end footer ====== -->

</body>

</html>
