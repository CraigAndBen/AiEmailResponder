<!DOCTYPE html>
<html style="font-size: 16px" lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta charset="utf-8" />
  <meta name="keywords" content="AI Auto Email Responder" />
  <meta name="description" content="" />
  <title>AI Auto Email Responder</title>
  <link rel="stylesheet" href="CSS/nicepage.css" media="screen" />
  <link rel="stylesheet" href="CSS/index.css" media="screen" />
  <script
    class="u-script"
    type="text/javascript"
    src="js/jquery.js"
    defer=""></script>
  <script
    class="u-script"
    type="text/javascript"
    src="js/nicepage.js"
    defer=""></script>
  <meta name="generator" content="Nicepage 5.6.2, nicepage.com" />
  <meta name="referrer" content="origin" />
  <link
    id="u-theme-google-font"
    rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" />
  <link
    id="u-page-google-font"
    rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Organization",
      "name": ""
    }
  </script>
  <meta name="theme-color" content="#478ac9" />
  <meta property="og:title" content="Home" />
  <meta property="og:type" content="website" />
  <meta data-intl-tel-input-cdn-path="intlTelInput/" />
</head>

<body class="u-body u-xl-mode" data-lang="en">

  <section class="u-clearfix u-custom-color-1 u-section-1" id="sec-0180">
    <div class="u-clearfix u-sheet u-sheet-1">
      <h2 class="u-text u-text-default u-text-1">AI Auto Email Responder</h2>
      <div class="u-custom-color-2 u-form u-radius-10 u-form-1">
        <form
          id="myForm"
          class="u-clearfix u-form-spacing-15 u-form-vertical u-inner-form"
          style="padding: 21px"
          name="form">

          <div class="u-form-group u-form-name u-label-top u-form-group-1">
            <label
              for="name-6797"
              class="u-custom-font u-font-montserrat u-label">Name</label>
            <input
              type="text"
              placeholder="Name"
              id="name-6797"
              name="name"
              class="u-grey-5 u-input u-input-rectangle u-radius-10 u-text-black"
              required="" />
          </div>
          <div class="u-form-email u-form-group u-label-top u-form-group-2">
            <label
              for="email-6797"
              class="u-custom-font u-font-montserrat u-label">Email</label>
            <input
              type="email"
              placeholder="Email"
              id="email-6797"
              name="email"
              class="u-grey-5 u-input u-input-rectangle u-radius-10 u-text-black"
              required="" />
          </div>
          <div class="u-form-group u-form-message u-label-top u-form-group-3">
            <label
              for="message-6797"
              class="u-custom-font u-font-montserrat u-label">Message</label>
            <textarea
              placeholder="Address"
              rows="4"
              cols="50"
              id="message-6797"
              name="message"
              class="u-grey-5 u-input u-input-rectangle u-radius-10 u-text-black"
              required=""></textarea>
          </div>
          <div class="u-align-left u-form-group u-form-submit u-label-top u-form-group-4">
            <button
              type="submit" id="submitBtn"
              class="u-border-none u-btn u-btn-submit u-button-style u-custom-color-1 u-custom-font u-font-montserrat u-text-body-alt-color u-btn-1">
              Submit
            </button>
          </div>

        </form>
      </div>
    </div>
  </section>

  <script>
    $(document).ready(function() {
      $("#submitBtn").on("click", function() {
        let formData = $("#myForm").serializeArray(); // get form data
        let data = {};

        $.each(formData, function(i, field) {
          data[field.name] = field.value;
        });

        $.ajax({
          url: "controller/process.php",
          type: "POST",
          data: formData,
          success: function(response) {
            console.log("Server Response:", response);
            alert("✅ Form submitted successfully!");
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("❌ Something went wrong. Please try again.");
          }
        });
      });
    });
  </script>



</body>

</html>