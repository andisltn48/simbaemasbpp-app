<!doctype html>
<html lang="en">
  <head>
  	<title>{{$title}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="/authtemplate/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			{{$slot}}
		</div>
	</section>

  <script src="/authtemplate/js/jquery.min.js"></script>
  <script src="/authtemplate/js/popper.js"></script>
  <script src="/authtemplate/js/bootstrap.min.js"></script>
  <script src="/authtemplate/js/main.js"></script>
  
  @if (session('ERR'))
    <script>
      Swal.fire({
        title: "ERROR!",
        text: "{{ session('ERR') }}",
        icon: "error",
        confirmButtonClass: "btn btn-primary",
      });
    </script>
    @endif
    @if (session('OK'))
        <script>
        Swal.fire({
            title: "OK!",
            text: "{{ session('OK') }}",
            icon: "success",
            confirmButtonClass: "btn btn-primary",
        });
        </script>
    @endif
	</body>
</html>

