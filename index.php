<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- title -->
    <title>QR Code Generator</title>

    <!-- style -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

</head>

<body style="background: #b2bbd9;">
    <div class="login-form">
        <div class="content">
            <div class="input-field">
                <select name="from" id="from">
                    <option value="" selected disabled>FROM ID</option>
                </select>
                <select name="to" id="to">
                    <option value="" selected disabled>TO ID</option>
                </select>
            </div>
        </div>
        <div class="action"><button type="submit" id="btn">Generate QR Code</button></div>
    </div>
    <div class="login-form" style="width:1200px;">
        <div class="content">
            <div class="input-field">
                <div class="qr" id="print"></div>
            </div>
        </div>
        <div class="action"><button id="gen_pdf" onclick="generatePDF()" disabled="disabled">Generate PDF File</button></div>
    </div>

    <!-- script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="js/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
</body>
<script>
    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "get_from_dropdownval.php",
            dataType: 'json',
            success: function(response) {

                var len = response.length;

                for (var i = 0; i < len; i++) {

                    var unique_id = response[i]['unique_id'];

                    $("#from").append("<option value='" + unique_id + "'>" + unique_id + "</option>");

                }
            }
        });

        $("#from").change(function() {

            var val = $(this).val();

            $.ajax({
                url: 'get_dropdownval.php',
                type: 'post',
                data: {from: val},
                dataType: 'json',
                success: function(response) {

                    var len = response.length;

                    $("#to").empty();

                    for (var i = 0; i < len; i++) {

                        var unique_id = response[i]['unique_id'];

                        $("#to").append("<option value='" + unique_id + "'>" + unique_id + "</option>");

                    }
                }
            });
        });

        $("#btn").click(function() {

            if ($("#to").val() != null) {
                $("#gen_pdf").attr("disabled", false);
            } else {
                $("#gen_pdf").attr("disabled", true);
            }

            var from = $("#from").val();
            var to = $("#to").val();

            $.ajax({
                url: 'generate_qr.php',
                type: 'post',
                data: {from: from,to: to},
                dataType: 'json',

                success: function(response) {

                    var len = response.length;

                    $("#print").empty();

                    for (var i = 0; i < len; i++) {

                        var unique_id = response[i]['unique_id'];
                        var name = response[i]['name'];
                        var domain = response[i]['domain'];

                        $("#print").append('<div style="text-align: center;" class="qrCode qr" data-qrcodeval="ID : ' + unique_id + '&#10;NAME: ' + name + '&#10;DOMAIN: ' + domain + '">'+
                                            '<div style="font-weight:bold;padding-bottom: 20px;">' + name + '</div>');

                    }

                    $(".qrCode").each(function() {
                        $(this).qrcode({

                            // render method: 'canvas', 'image' or 'div'
                            render: 'div',

                            // version range somewhere in 1 .. 40
                            minVersion: 1,
                            maxVersion: 40,

                            // error correction level: 'L', 'M', 'Q' or 'H'
                            ecLevel: 'L',

                            // offset in pixel if drawn onto existing canvas
                            left: 0,
                            top: 0,
                            // size in pixel
                            size: 100,

                            // code color or image element
                            fill: '#fff',

                            // background color or image element, null for transparent background
                            background: null,

                            // content
                            text: $(this).data('qrcodeval'),

                            // corner radius relative to module width: 0.0 .. 0.5
                            radius: 0,

                            // quiet zone in modules
                            quiet: 0,

                            // modes
                            // 0: normal
                            // 1: label strip
                            // 2: label box
                            // 3: image strip
                            // 4: image box
                            mode: 0,

                            mSize: 0.1,
                            mPosX: 0.5,
                            mPosY: 0.5,

                            label: 'no label',
                            fontname: 'sans',
                            fontcolor: '#fff',

                            image: null
                        });
                    });
                }
            });

        });

    });

    function generatePDF() {

        var element = document.getElementById('print').innerHTML;

        var opt = {
            margin: [38, 80, 38, 0], //top, left, buttom, right
            filename: 'QR_CODE.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                dpi: 192,
                scale: 4,
                letterRendering: true
            },
            jsPDF: {
                unit: 'pt',
                format: 'a4',
                orientation: 'portrait'
            },
            pageBreak: {
                mode: 'css',
                after: '.break-page'
            }
        };
        html2pdf()
            .set(opt)
            .from(element)
            .toPdf()
            .get('pdf')
            .save();
    }
</script>


</html>