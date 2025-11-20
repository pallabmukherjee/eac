<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krishnanagar Municipal Pension Form</title>
    <style>
        @page {
            /* Legal paper is 8.5in x 14in */
            margin: 0.3in; /* Uniform margin for top, right, bottom, left */
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            line-height: 1.4;
            margin: 0; /* No body margin */
            padding: 0; /* No body padding */
        }

        .page {
            /* Remove width and min-height, let it expand to legal page dimensions */
            background: white;
            margin: 0 auto; /* Center the content */
            padding: 0; /* Remove padding from .page, margins are controlled by @page */
            box-sizing: border-box;
            /* box-shadow: none; */ /* Remove shadow for print */
        }

        h1, h2, h3, h4 {
            text-align: center;
            margin: 5px 0;
        }

        h2 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .header .sub-text {
            font-size: 12px;
        }

        .section {
            margin-bottom: 5px;
        }


        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            height: 18px;
            margin: 0 5px;
        }

        .full-width {
            width: 100%;
        }

        ul {
            list-style-type: disc;
            padding-left: 20px;
            margin: 10px 0;
        }

        li {
            margin-bottom: 4px;
        }

        .flex-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
        }

        .officer-sign-block {
            text-align: left;
            width: 40%;
            margin-left: auto;
        }

        .non-employment-title {
            text-decoration: underline;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            font-size: 16px;
        }

        .declaration-text {
            text-align: justify;
            margin-bottom: 10px;
        }

        .signature-row {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .left-col {
            width: 40%;
        }

        .right-col {
            width: 40%;
            text-align: right;
        }

        .center-name {
            text-align: center;
            margin: 20px 0;
        }

        .family-pension-section {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 0;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }

        .witness-section {
            margin-top: 30px;
        }
        
        .witness-title {
            text-align: center; 
            font-weight: bold; 
            margin-bottom: 10px;
        }

        .witness-columns {
            /* display: flex; */ /* Dompdf might have issues with flex for columns */
            justify-content: space-between;
            margin-top: 20px;
        }

        .witness-col {
            width: 48%;
            display: inline-block; /* Use inline-block for columns */
            vertical-align: top;
        }

        .footer-note {
            text-align: center;
            margin-top: 40px;
            font-weight: bold;
            font-size: 13px;
        }

        /* Utility for filling space */
        .fill-space {
            flex-grow: 1;
            border-bottom: 1px dotted #000;
            margin-left: 5px;
        }
        
        /* Removed @media print specific styles as @page rules handle print styling */
    </style>
</head>
<body>

<div class="page">

    <div class="header">
        Category of Pension : Krishnanagar Municipal Pension<br>
        <span class="sub-text">**Please Tick Mark which is applicable</span><br>
        <u>ANNEXURE</u>
    </div>
    <div class="section">
        <ul>
            <li>
                Certified that I have seen the Pensioner Sri / Smt. {{ $pensioner->pensioner_name }}
            </li>
            <li>
                holder of P.P.O. No. {{ $pensioner->ppo_number }}<br>
                and that he / she is alive on this date {{ date('d-m-Y') }}
            </li>
        </ul>
        
        <div class="flex-row">
            <div style="width: 55%;">
                <ul>
                    <li>Aadhaar Card No. {{ $pensioner->aadhar_number ?? 'N/A' }}</li>
                    <li>Account No. {{ $pensioner->savings_account_number ?? 'N/A' }}</li>
                    <li>Name of the Bank <span class="dotted-line" style="width: 145px"></span></li>
                    <li>Name of the Branch <span class="dotted-line" style="width: 140px"></span></li>
                </ul>
            </div>
            <div class="officer-sign-block">
                <br>
                <i>Signature of the Officer</i><br>
                Designation - <br>
                Dated - 
            </div>
        </div>
    </div>

    <div class="non-employment-title">NON EMPLOYMENT CERTIFICATE</div>

    <div class="section">
        <p class="declaration-text">
            I declare that I have not received any remuneration for serving any establishment of the Central Govt. or State Govt. or Local Bodies undertaking or from Local Fund during the period from <span class="dotted-line" style="width: 100px"></span> to <span class="dotted-line" style="width: 100px"></span>.
        </p>
        <p class="declaration-text">
            **I declare that I have been employed re-employed in the Office of the <span class="dotted-line" style="width: 150px"></span> ................................................................................................................. and I was in receipt of the following emoluments during the period.
        </p>
        <p class="declaration-text">
            *I declare that I have accepted commercial employment after obtaining / without obtaining sanction of the Govt.
        </p>
        <p class="declaration-text">
            *I declare that I have / have not accepted any employment under any Govt. outside India after obtaining / without obtaining sanction of the Govt.
        </p>

        <div class="signature-row">
            <div class="left-col">
                Place <span class="dotted-line" style="width: 150px"></span><br>
                Date <span class="dotted-line" style="width: 150px"></span><br>
            </div>
            <div class="right-col">
                <br>
                <i>L. T. I. / Signature of the Pensioner</i>
            </div>
        </div>

        <div class="center-name">
            Full Name {{ $pensioner->pensioner_name }}
        </div>
    </div>

    <div class="family-pension-section">
        Yearly declaration be widow / widower / daughter son of the pensioner whose pension is terminable on their marriage / re-marriage (For Family Pensioner)
    </div>

    <div class="section">
        <p class="declaration-text">
            I have declared that I am not married / have not re-married during this year and I undertake to report such an event promptly to the Treasury.
        </p>

        <div class="signature-row">
            <div class="left-col">
                A/c No. {{ $pensioner->savings_account_number ?? 'N/A' }}<br>
                Name of the Bank <span class="dotted-line" style="width: 140px"></span><br>
                & Branch
            </div>
            <div class="right-col">
                <i>L. T. I. / Signature of the Pensioner</i>
            </div>
        </div>
    </div>

    <div class="witness-section">
        <div class="witness-title">** To be signed by two responsible officers or known persons :</div>
        <p style="text-align: center;">We certify that to the best of our knowledge and belief that the above declaration is correct.</p>

        <div class="witness-columns">
            <div class="witness-col">
                1) Signature ......................... <span class="dotted-line fill-space"></span><br><br>
                &nbsp;&nbsp;&nbsp;Designation .........................  <span class="dotted-line fill-space"></span><br><br>
                &nbsp;&nbsp;&nbsp;Place .............................  <span class="dotted-line fill-space"></span>
            </div>
            <div class="witness-col">
                2) Signature .........................  <span class="dotted-line fill-space"></span><br><br>
                &nbsp;&nbsp;&nbsp;Designation .........................  <span class="dotted-line fill-space"></span><br><br>
                &nbsp;&nbsp;&nbsp;Date .............................  <span class="dotted-line fill-space"></span>
            </div>
        </div>
    </div>
    <div class="footer-note">
        To be authenticated by any ward councillor / reputed Doctor having valid registration no.)
    </div>

</div>

</body>
</html>