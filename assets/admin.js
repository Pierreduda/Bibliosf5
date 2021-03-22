import "./styles/admin.scss";

// alert("admin.js");

$(function(){
    $("[type='file']").on("change", function(){
        var fileName = $(this).val();
        $(this).next('.custom-file-label').html(fileName);    
    });    
})