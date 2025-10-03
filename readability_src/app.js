const { Readability } = require("@mozilla/readability")
const { JSDOM } = require("jsdom")
const http = require('node:http');


createServer = async function (req, res) {
    let body = await getRequestBody(req)
    let text = await createReadabilityText(body);

    res.writeHead(200, {'Content-Type': 'application/json'})
    res.end(JSON.stringify(text))
}

getRequestBody = async function (req) {
    let body = ''
    req.on('data', (chunk) => body += chunk.toString())
    await req.on('end', () => body)
    return body
}

createReadabilityText = async function (text) {
    const document = new JSDOM(text)
    const reader = new Readability(document.window.document)
    return reader.parse()
}

// Create a local server to receive data from
const server = http.createServer((req, res) => createServer(req, res));
server.listen(3000)