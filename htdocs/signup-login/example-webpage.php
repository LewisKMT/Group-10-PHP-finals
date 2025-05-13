<!DOCTYPE html>
<html>
<head>
    <title>Example Webpage</title>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
      body {
        font-family: Poppins;
        height: 100vh;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
        margin: 0;
      }

      .background {
        height: 100vh;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: url('alden.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed; 
        background-size: 100% 100%;
        /* filter: blur(5px); */
      }

      #container {
        z-index: 10;
        height: 40rem;
        width: 30rem;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
        background-image: url('alden.jpg');
        background-repeat: no-repeat;
        padding: 2rem;
        color: white;

        .logout_button {
          height: 3rem;
          width: 8rem;
          border-radius: 15px;
          border: solid 1px;
          background-color: lightblue;
        }
      }
    </style>
</head>
<body>
  <div class="background">
    <div id="container">
      <h2>Welcome to example Webpage! You are logged in.</h2>

      <!-- Log Out button -->
      <form action="index.php" method="get">
          <button type="submit" class="logout_button">Log Out</button>
      </form>
    </div>
  </div>
</body>
</html>
