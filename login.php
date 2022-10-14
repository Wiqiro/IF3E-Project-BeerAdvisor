<html>
   <head>
      <title>Login</title>
      
      <link rel="stylesheet" type="text/css" href="style.css" />
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   

   </head>
   <body>
      <div class="login">
         <form action="add_user.php">
         <h3 4><strong>Login</strong></h3><br>
            <div class="row mb-3">
               <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label><br>
               <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputEmail3">
               </div>
            </div>
            <div class="row mb-3">
               <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label><br>
               <div class="col-sm-10">
                  <input type="password" class="form-control" id="inputPassword3">
               </div>
            </div>
            No account ? Register <a href="register.php">here</a><br><br>
            <button type="submit" class="btn btn-primary">Login</button>
         </form>
      </div>
   </body>


</html>