<html>
   <head>
      <title>Login</title>
      
      <link rel="stylesheet" type="text/css" href="style.css" />
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   

   </head>
   <body>
      <div class="login">
         <h3 4><strong>Register</strong></h3><br>
         <form action="add_user.php" >
            <div class="row mb-3">
               <label for="input-username" class="col-sm-2 col-form-label">Username</label><br>
               <div class="col-sm-10">
                  <input type="text" class="form-control" id="input-username">
               </div>
            </div>
            <div class="row mb-3">
               <label for="input-email" class="col-sm-2 col-form-label">Email</label><br>
               <div class="col-sm-10">
                  <input type="email" class="form-control" id="input-email">
               </div>
            </div>
            <div class="row mb-3">
               <label for="input-password" class="col-sm-2 col-form-label">Password</label><br>
               <div class="col-sm-10">
                  <input type="password" class="form-control" id="input-password">
               </div>
            </div>

            <div class="row mb-3">
               <div class="col-sm-10 offset-sm-2">
                  <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="gridCheck1">
                  <label class="form-check-label" for="gridCheck1">I am 18 years old</label>
                  </div>
               </div>
            </div>
            Already have an account ? Login <a href="login.php">here</a><br><br>
            <button type="submit" class="btn btn-primary">Register</button>
         </form>
      </div>
   </body>


</html>