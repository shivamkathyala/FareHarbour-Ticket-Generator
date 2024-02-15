console.log("hello");
function exportHTMLtoPDF() {
    let htmlElement = document.getElementById('content');

    html2pdf().from(htmlElement).save('exported_file.pdf');
 }

 document.getElementById('download-ticket').addEventListener('click', exportHTMLtoPDF);