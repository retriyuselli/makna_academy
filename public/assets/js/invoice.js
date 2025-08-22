(function ($) {
    "use strict";

    /*=================================
      Invoice PDF Download & Print Functions
    ==================================*/

    /*----------- PDF Download Function ----------*/
    $("#download_btn").on("click", function () {
        var downloadSection = $("#download_section");
        var cWidth = downloadSection.width();
        var cHeight = downloadSection.height();
        var topLeftMargin = 40;
        var pdfWidth = cWidth + topLeftMargin * 2;
        var pdfHeight = pdfWidth * 1.5 + topLeftMargin * 2;
        var canvasImageWidth = cWidth;
        var canvasImageHeight = cHeight;
        var totalPDFPages = Math.ceil(cHeight / pdfHeight) - 1;

        // Show loading
        $(this)
            .find(".btn-text")
            .text("Generating PDF...")
            .prop("disabled", true);
        $(this).prop("disabled", true);

        html2canvas(downloadSection[0], {
            allowTaint: true,
            scale: 2,
            useCORS: true,
        })
            .then(function (canvas) {
                canvas.getContext("2d");
                var imgData = canvas.toDataURL("image/jpeg", 1.0);
                var pdf = new jsPDF("p", "pt", [pdfWidth, pdfHeight]);

                pdf.addImage(
                    imgData,
                    "JPG",
                    topLeftMargin,
                    topLeftMargin,
                    canvasImageWidth,
                    canvasImageHeight
                );

                for (var i = 1; i <= totalPDFPages; i++) {
                    pdf.addPage(pdfWidth, pdfHeight);
                    pdf.addImage(
                        imgData,
                        "JPG",
                        topLeftMargin,
                        -(pdfHeight * i) + topLeftMargin * 0,
                        canvasImageWidth,
                        canvasImageHeight
                    );
                }

                // Generate filename
                var invoiceNumber =
                    $("[data-invoice-number]").data("invoice-number") ||
                    "Invoice";
                var filename = "Invoice-" + invoiceNumber + ".pdf";

                pdf.save(filename);

                // Reset button
                $("#download_btn").find(".btn-text").text("Download PDF");
                $("#download_btn").prop("disabled", false);
            })
            .catch(function (error) {
                console.error("Error generating PDF:", error);
                alert("Error generating PDF. Please try again.");
                $("#download_btn").find(".btn-text").text("Download PDF");
                $("#download_btn").prop("disabled", false);
            });
    });

    /*----------- Print Function ----------*/
    $(".print_btn").on("click", function () {
        window.print();
    });
})(jQuery);
