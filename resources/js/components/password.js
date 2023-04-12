$(() => {

    $("[type='password']").each(function() {
        const passwordInput = $(this);
        const togglePasswordButton = $(this).siblings('.password-toggle');
        passwordInput.addClass("input-password");
        togglePasswordButton.removeClass("d-none");
        togglePasswordButton.on("click", togglePassword);
        function togglePassword() {

            if (passwordInput.attr('type') === "password") {
                passwordInput.attr('type', "text");
            } else {
                passwordInput.attr('type','password');
            }
        }
    });

});
