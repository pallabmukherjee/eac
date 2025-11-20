<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Certificate</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 0; padding: 0; font-size: 12pt; }
        .container { padding: 40px; }
        .header, .footer { text-align: center; }
        .header h1 { font-size: 16pt; font-weight: bold; text-decoration: underline; }
        .content { margin-top: 20px; }
        .pensioner-details, .certification { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px; vertical-align: top; }
        .photo-box { border: 1px solid #000; width: 120px; height: 150px; text-align: center; float: right; }
        .signature-box { border-top: 1px solid #000; width: 250px; margin-top: 40px; }
        .office-seal { border: 1px solid #000; width: 150px; height: 70px; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LIFE CERTIFICATE FOR PENSIONER</h1>
        </div>

        <div class="content">
            <div class="photo-box">
                <p style="padding-top: 50px;">Passport size photo of the Pensioner to be affixed here</p>
            </div>

            <p>Certified that I have seen the Pensioner, <strong>{{ $pensioner->pensioner_name }}</strong>, holder of Pension Payment Order (PPO) No. <strong>{{ $pensioner->ppo_number }}</strong> on this day of {{ date('d-m-Y') }} and that he/she is alive.</p>

            <div class="pensioner-details">
                <table>
                    <tr>
                        <td width="30%">Name of Pensioner</td>
                        <td width="70%">: <strong>{{ $pensioner->pensioner_name }}</strong></td>
                    </tr>
                    <tr>
                        <td>PPO No.</td>
                        <td>: <strong>{{ $pensioner->ppo_number }}</strong></td>
                    </tr>
                    <tr>
                        <td>Savings Bank A/c No.</td>
                        <td>: <strong>{{ $pensioner->savings_account_number }}</strong></td>
                    </tr>
                    <tr>
                        <td>Date of Birth</td>
                        <td>: <strong>{{ \Carbon\Carbon::parse($pensioner->dob)->format('d/m/Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 40px;">
                            <div class="signature-box">
                                (Signature of the Pensioner)
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <hr style="margin-top: 40px;">

            <div class="certification">
                <h2 class="header"><u>CERTIFICATE BY AN AUTHORISED OFFICER</u></h2>
                <p>This is to certify that the above mentioned Pensioner has appeared before me on this day, {{ date('d-m-Y') }}.</p>

                <table style="margin-top: 50px;">
                    <tr>
                        <td width="50%">
                            <div class="signature-box">
                                (Signature of the Authorised Officer)
                            </div>
                            <p>Name:</p>
                            <p>Designation:</p>
                        </td>
                        <td width="50%" style="text-align: right;">
                            <div class="office-seal">
                                <p style="padding-top: 20px;">Office Seal</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: 40px; font-size: 10pt;">
                <p style="text-align: left; font-weight: bold;"><u>Persons authorised to sign Life Certificate:</u></p>
                <ol>
                    <li>A person exercising the power of a Magistrate under the criminal procedure code.</li>
                    <li>A Registrar or sub-Registrar appointed under the Indian Registration Act.</li>
                    <li>A Gazetted Government Servant.</li>
                    <li>A Police Officer not below the rank of Sub-Inspector in-charge of a Police Station.</li>
                    <li>A Post Master, Departmental Sub-Post Master or an Inspector of Post Office.</li>
                    <li>A Class-I Officer of the Reserve Bank of India, an Officer (Grade-II) of the State Bank of India or an Officer of its subsidiary.</li>
                    <li>The Justice of Peace.</li>
                    <li>A Block Development Officer, Munsiff, Tehsildar or Naib Tehsildar.</li>
                    <li>A Head of a Village Panchayat, Gram Panchayat, Gaon Panchayat or an Executive Committee of a Village.</li>
                    <li>A Member of Parliament, of State Legislatures or of legislatures of Union Territory Government / Administration.</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>