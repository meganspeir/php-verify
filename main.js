$(document).ready(function(){
    $("#enter_number").submit(function(e) {
        e.preventDefault();
        initiateCall();
    });
});

function initiateCall() {
    $.post("call.php", { phone_number : $("#phone_number").val() }, null, "json")
        .fail(
            function(data) {
                showErrors(data.errors);
            })
        .done(
            function(data) {
                showCodeForm(data.verification_code);
            })
    ;
    checkStatus();
}

function showErrors(errors) {
    $("#errors").text(code);
}

function showCodeForm(code) {
    $("#verification_code").text(code);
    $("#verify_code").fadeIn();
    $("#enter_number").fadeOut();
}

function checkStatus() {
    $.post("status.php", { phone_number : $("#phone_number").val() },
        function(data) { updateStatus(data.status); }, "json");
}

function updateStatus(current) {
    if (current === "unverified") {
        $("#status").append(".");
        setTimeout(checkStatus, 3000);
    }
    else {
        success();
    }
}

function success() {
    $("#status").text("Verified!");
}