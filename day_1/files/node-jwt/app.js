const express = require('express');
const app = express();
const jwt = require('jsonwebtoken');
const users = require('./users');
const path = require('path');

const host = '127.0.0.1';
const port = 3000;

const tokenKey = 'jjktU5yqdh';

app.use(express.json())
app.use(express.urlencoded({ extended: true }));


app.use((req, res, next) => {
   if (req.headers.authorization) {
    jwt.verify(
      req.headers.authorization.split(' ')[1],
      tokenKey,
      (err, payload) => {
        if (err) next()
        else if (payload) {
          for (let user of users) {
            if (user.id === payload.id) {
              req.user = user
              next()
            }
          }

          if (!req.user) next()
        }
      }
    )
  }

  next()
})

app.post('/api/auth', (req, res) => {
    console.log(req.body);
  for (let user of users) {
    if (
      req.body.login === user.login &&
      req.body.password === user.password
    ) {
      return res.status(200).json({
        id: user.id,
        login: user.login,
        token: jwt.sign({ id: user.id }, tokenKey),
      })
    }
  }

  return res.status(404).json({ message: 'User not found' })
})


app.get('/login', (req, res) => {
    var options = {
        root: path.join(__dirname, 'public'),
        dotfiles: 'deny',
        headers: {
          'x-timestamp': Date.now(),
          'x-sent': true
        }
      }

    if (req.user) return res.status(200).json(req.user)
    else
      return res.sendFile('./login.html',options)
  })

app.get('/user', (req, res) => {
  if (req.user) {
    // return res.status(200).json(req.user)
    return res.status(200).json(({ message: 'You authorized!' }))
  }
  else
    return res
      .status(401)
      .json({ message: 'Not authorized' })
})

app.listen(port, host, () =>
  console.log(`Server listens http://${host}:${port}`)
)