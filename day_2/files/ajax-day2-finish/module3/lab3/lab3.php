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
      const $ = selector => document.querySelectorAll(selector);

      function go(){ 
        let login    = $('#login')[0];
        let password = $('#password')[0];
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'create_token.php', true)
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

        let str = `login=${login.value}&password=${password.value}`;
        xhr.send(str);

        xhr.onreadystatechange = function(){
          if(xhr.readyState == 4 && xhr.status == 200){
            let form     = $('#form')[0];
            let result   = $('#result')[0];
            console.log(xhr.responseText);
            form.parentNode.removeChild(form);
            result.innerHTML = xhr.responseText;
            sessionStorage.setItem('key', xhr.responseText)
          }
        }

      }

      /* Опишите фунцию show(), которая должна сделать запрос методом POST на страницу validating.php, при это отправив параметр token со значением из сессионного хранилища sessionStorage.getItem('параметр'), если этот параметр действительно существует 
      
      Внимание: после получения токена нужно подождать минуту перед нажатием на кнопку с обработчиком show(), потому что в 21 строке create_token.php указано это время

        ->canOnlyBeUsedAfter($time + 60) // настраивает время, в течение 

      или закомментировать/удалить в create_token.php эту 21 строку
      
      */
      function show(){ 
        
        const token = sessionStorage.getItem('key')
        console.log(token);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'validating.php', true)
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

        let str = `token=${token}`;
        if( token ){
          xhr.send(str);
        }

        xhr.onreadystatechange = function(){
          if(xhr.readyState == 4 && xhr.status == 200){
            let result   = $('#result')[0];
            console.log(xhr.responseText);            
            result.innerHTML += xhr.responseText;
          }
        }

      }

      (function(){
        //получаем токен из хранилища
        const token = sessionStorage.getItem('key')

        if( token ){
          //отправить запрос на validating.php
          //если ответ будет не 401, то удалить форму
          const xhr = new XMLHttpRequest();
          xhr.open('POST', 'validating.php', true)
          xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
          xhr.send(`token=${token}`);
          xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 && xhr.status == 200){
              let form   = $('#form')[0];
              form.remove()
            }
          }
        }

      })()
    </script>

  
    <hr>
  

  </div>

  
</body>
</html>