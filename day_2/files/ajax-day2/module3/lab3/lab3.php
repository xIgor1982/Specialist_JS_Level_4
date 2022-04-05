<?php


?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>JWT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div class="row">
      <h1 class="display-4">JWT логинизация</h1>      
    </div>

    <div class="row" id="result" >   

    <form method=POST id=form >  
      <input type="text" name="login" id="login" autocomplete=off class="form-control" />
      <input type="password" name="password" id="password" autocomplete=off class="form-control" />
      <input type="button" value="Войти" onclick="go()" class="btn btn-primary" />
      <!-- <input type="submit" > -->
      <div id="error"></div>
    </form>
    </div>
    
    <div class="row">
        <input type="button" value="Показать" onclick="show()" class="btn btn-primary" />
     </div>

    <script>
    /* Создайте функцию go(), которая отправляет методом POST параметры login и password из формы на адрес create_token.php. Посмотрите содержимое create_token.php, там логин и пароль жёстко зашиты в код
    
    При 200 статусе ответа, установите пришедший токен в сессионное хранилище sessionStorage.setItem('параметр', 'значение') под именем key

    Удалите форму
    */
      function go(){ 
        let login = document.querySelector('#login').value;
        let password = document.querySelector('#password').value;

        const xhr = new XMLHttpRequest();
        const url = 'create_token.php';
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(`login=${login}&password=${password}`);

        xhr.onreadystatechange = () => {
          if(xhr.readyState == 4){
           if(xhr.status == 200){
            console.log('Статус: ',xhr.status);
            console.log('Ответ: ',xhr.responseText);
            let form = document.querySelector('#form');
            let result = document.querySelector('#result');
            form.remove();
            result.innerHTML = xhr.responseText;
            sessionStorage.setItem('key', xhr.responseText);

          }

          }
        }

      }

      /* Опишите фунцию show(), которая должна сделать запрос методом POST на страницу validating.php, при это отправив параметр token со значением из сессионного хранилища sessionStorage.getItem('параметр'), если этот параметр действительно существует */
      function show(){ 
 
        let result = '';
        const xhr = new XMLHttpRequest();
        const url = 'validating.php';
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

        if(sessionStorage.getItem('key')){
          xhr.send(`token=${sessionStorage.getItem('key')}`);
        }
        xhr.onreadystatechange = () => {
          if(xhr.readyState == 4){
           if(xhr.status == 200){
            console.log('Статус: ',xhr.status);
            console.log('Ответ: ',xhr.responseText);
            let result = document.querySelector('#result');
            result.innerHTML += xhr.responseText;
          }

          }
        }

      }

    </script>

  
    <hr>
  

  </div>

  
</body>
</html>