function myFunction() {
    $.ajax({
        type: "POST",
        url: "teach06.php",
        data: "",
        cache: false,
        success: function (html) {
            alert(html);
        }
    });
}