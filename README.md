# Overview
Simple PHP script to unlock a SchlageLink lock.

# Installation
1. Pull all the files down to a folder on your server.
2. Set up a redis server, modify config.php to reflect the connection criteria of the redis instance.
3. Give "cookies.txt" read write permissions.
4. Register a number on twilio and set the callbacks to hit [DOCUMENT_ROOT]/sms for incoming SMS and [DOCUMENT_ROOT]/call for incoming calls.
5. Test!



# License 

(The MIT License)

Copyright (c) 2011 Rishi Ishairzay &lt;rishi [at] ishairzay [dot] com&gt;

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
'Software'), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.