$(document).ready(function () {
    // Add regex validation method
    $.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            let re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Invalid format."
    );
    $.validator.addMethod(
        "notDefault",
        function (value, element) {
            return value !== "" && value !== "select";
        },
        "Please select a valid option."
    );
    $.validator.addMethod("noFutureDate", function (value, element) {
        let inputDate = new Date(value);
        let today = new Date();
        today.setHours(0, 0, 0, 0); // Remove time part

        return inputDate >= today; // Ensure input date is not in the future
    }, "Date cannot be a Past date.");

    $("#image").change(function (event) {
        var file = event.target.files[0]; // Get the uploaded file

        if (!file) {
            $("#error-message").text("Error: No file selected.");
            $("#submitBtn").prop("disabled", true);
            return;
        }

        var img = new Image();
        img.src = URL.createObjectURL(file); // Create a temporary image URL

        img.onload = function () {
            var width = this.width;
            var height = this.height;

            if (width === 1980 && height === 736) { // Ensure exact match
                $("#error-message").text(""); // Clear error message
                $("#submitBtn").prop("disabled", false); // Enable submit
            } else {
                $("#error-message").text("Error: Image must be exactly 1980x736 pixels.");
                $("#submitBtn").prop("disabled", true); // Disable submit
            }
        };
    });
    // Apply validation rules
    $("#form").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                regex: /^[a-zA-Z\s]+$/,
            },
            password: {
                required: true,
                regex: /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&+=!])[A-Za-z\d@#$%^&+=!]{6,10}$/
            },
            fname: {
                required: true,
                minlength: 2,
                regex: /^[a-zA-Z\s]+$/,
            },
            lname: {
                required: true,
                minlength: 2,
                regex: /^[a-zA-Z\s]+$/,
            },
            head_name: {
                required: true,
                minlength: 2,
                regex: /^[a-zA-Z\s]+$/,
            },

            dept: {
                required: true,
                minlength: 2,
                maxlength: 15,
                regex: /^[a-zA-Z\s]+$/,
            },
            img: {
                required: true,
                extension: "jpg|jpeg|png",
                imageDimensions: true // Custom image dimension validation
            },
            des: {
                required: true,
                minlength: 10
            },
            email: {
                required: true,
                email: true,
                regex: /^[a-zA-Z0-9._%+-]+@rku\.ac\.in$/,
            },
            club: {
                required: true,
                notDefault: true
            },
            url: {
                required: true,
            },
            date: {
                required: true,
                noFutureDate: true
            }

        },
        messages: {
            name: {
                required: "Name is required.",
                minlength: "Name must be at least 2 characters long.",
                regex: "Please enter a valid name (letters and spaces only).",
            },
            password: {
                required: "Password is required.",
                regex: "Password must be 6-10 characters long, with contains invalid characters. Only letters, numbers, and @#$%^&+=! are allowed ."
            },
            fname: {
                required: "First Name is required.",
                minlength: "First Name must be at least 2 characters long.",
                regex: "Please enter a valid First name (letters and spaces only).",
            },
            lname: {
                required: "Last Name is required.",
                minlength: "Last Name must be at least 2 characters long.",
                regex: "Please enter a valid Last N ame (letters and spaces only).",
            },
            head_name: {
                required: "Head Name is required.",
                minlength: "Head Name must be at least 2 characters long.",
                regex: "Please enter a valid Head name (letters and spaces only).",
            },
            dept: {
                required: "Deptment Name is required.",
                minlength: "Deptment Name must be at least 5 digits.",
                maxlength: "Deptment Name cannot exceed 15 digits.",
                regex: "Please enter a valid Department name (letters and spaces only)."
            },

            img: {
                required: "Please upload an image for the club.",
                extension: "Only image files are allowed (jpg, jpeg, png, gif).",
                imageDimensions: "Image dimensions must be 300x300px or greater."
            },

            des: {
                required: "Please provide a club description.",
                minlength: "Description must be at least 10 characters long."
            },
            email: {
                required: "Email address is required.",
                email: "Enter a valid email address.",
                regex: "Email must be in the format example@rku.ac.in.",
            },
            club: {
                required: "Please select a club.",
                notDefault: "Please select a valid Category from the dropdown.",
            },
            url: {
                required: "please Enter URL for form"
            },
            date: {
                required: "Date is required.",
                noFutureDate: "Date cannot be a Past date."
            }
        }
    });

    let table = $('#myTable').DataTable();

    // Ensure pagination works independently of form submission
    $('#myTable').on('page.dt', function () {
        console.log('Pagination triggered.');
    });

});
