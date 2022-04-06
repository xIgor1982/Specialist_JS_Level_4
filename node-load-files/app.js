const express = require('express')
const fileUpload = require('express-fileupload')

const app = express()

app.use(fileUpload({}))

app.use(express.static('public'))

app.get('/', function (req, res) {
	console.log('Переход на - "/"')
	return res.sendFile('./05-load-files.html', {
		root: __dirname
	})
})

app.post('/upload', function (req, res) {
	req.files.myfile.mv('public/file/' + req.files.myfile.name)
	res.setHeader('content-type', 'text/html;charset=utf-8')
	res.end(req.files.myfile.name)

	// res.setHeader('Content-Type', 'application/json;charset=utf-8')
	// res.setHeader('Access-Control-Allow-Origin', '*')
	// res.setHeader('Access-Control-Allow-Credentials', 'true')

	// let message = {
	// 	status: 'File uploaded!',
	// 	myfile: req.files.myfile.name,
	// }
	// res.json(message)
	// res.end()
	console.log(req.files.myfile) // the uploaded file object
})

const server = app.listen(3000, function () {
	var host = server.address().address
	var port = server.address().port

	console.log('Server listening ' + port)
})
