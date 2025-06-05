<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Payment Receipt</title>
    <style>
        @media only screen and (max-width: 620px) {
            table.body h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }
            table.body p,
            table.body ul,
            table.body ol,
            table.body td,
            table.body span,
            table.body a {
                font-size: 16px !important;
            }
            table.body .wrapper,
            table.body .article {
                padding: 10px !important;
            }
            table.body .content {
                padding: 0 !important;
            }
            table.body .container {
                padding: 0 !important;
                width: 100% !important;
            }
            table.body .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }
            table.body .btn table {
                width: 100% !important;
            }
            table.body .btn a {
                width: 100% !important;
            }
            table.body .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }
        @media all {
            .ExternalClass {
                width: 100%;
            }
            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }
            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }
            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
            }
            .btn-primary table td:hover {
                background-color: #0069d9 !important;
            }
            .btn-primary a:hover {
                background-color: #0069d9 !important;
                border-color: #0069d9 !important;
            }
        }
    </style>
</head>
<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6; width: 100%;" width="100%" bgcolor="#f6f6f6">
        <tr>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
            <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; margin: 0 auto;" width="580" valign="top">
                <div class="content" style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <table role="presentation" class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;" width="100%">
                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;" valign="top">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" width="100%">
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                                                <h1 style="color: #000000; font-family: sans-serif; font-weight: 300; line-height: 1.4; margin: 0; margin-bottom: 30px;">Receipt</h1>
                                                <div style="text-align: right; color: #999999;">
                                                    <p>Invoice #{{ $charge->invoice_number }}</p>
                                                    <p>{{ $paymentDate }}</p>
                                                </div>
                                            </div>
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Dear {{ $customerName }},</p>
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Thank you for your payment. This email confirms that your payment has been processed successfully.</p>
                                            
                                            <!-- Payment Details Table -->
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; margin-bottom: 20px;" width="100%">
                                                <tr>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #f8f9fa; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top" bgcolor="#f8f9fa" colspan="2">
                                                        <strong>Payment Details</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6; width: 50%;" valign="top" width="50%">Amount</td>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6; width: 50%;" valign="top" width="50%"><strong>${{ number_format($charge->amount / 100, 2) }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top">Description</td>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top"><strong>{{ $charge->description }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top">Payment Method</td>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top"><strong>{{ ucfirst($charge->payment_method_details->type ?? 'Card') }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top">Transaction ID</td>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top"><strong>{{ $charge->id }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top">Date</td>
                                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding: 10px; border-bottom: 1px solid #dee2e6;" valign="top"><strong>{{ \Carbon\Carbon::createFromTimestamp($charge->created)->toDateTimeString() }}</strong></td>
                                                </tr>
                                            </table>
                                            
                                            @if ($receiptUrl)
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%;" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;" valign="top">
                                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center; background-color: #007bff;" valign="top" align="center" bgcolor="#007bff"> <a href="{{ $receiptUrl }}" target="_blank" style="border: solid 1px #007bff; border-radius: 5px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-decoration: none; text-transform: capitalize; background-color: #007bff; border-color: #007bff; color: #ffffff;">View Receipt in Browser</a> </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif
                                            
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 20px;">If you have any questions regarding this receipt or your purchase, please don't hesitate to contact our support team at <a href="mailto:{{ $companyEmail }}" style="color: #007bff; text-decoration: underline;">{{ $companyEmail }}</a>.</p>
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Thank you for your business!</p>
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Sincerely,<br>The {{ $companyName }} Team</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- END MAIN CONTENT AREA -->
                    </table>
                    <!-- END CENTERED WHITE CONTAINER -->

                    <!-- START FOOTER -->
                    <div class="footer" style="clear: both; margin-top: 10px; text-align: center; width: 100%;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" width="100%">
                            <tr>
                                <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;" valign="top" align="center">
                                    <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">{{ $companyName }}</span>
                                    <br>This is an automated email. Please do not reply directly to this message.
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- END FOOTER -->
                </div>
            </td>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
        </tr>
    </table>
</body>
</html>
