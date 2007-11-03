var testcase = 0;

function init() {
    // get the endpoint
    schoorbsREST.endPoint = prompt('The HTTP-location of your Schoorbs installation', 
        'http://schoorbs.hsg-kl.de', 'Schoorbs-JS-REST-Client library TEST');
    nextTest();
}

function test() {
    switch(testcase) {
    case 1:
        if (schoorbsREST.login()) {
            testSucceded();
            nextTest();    
        } else {
            testFailed();
            alert('Proceeding impossible: login failed!');    
        }
        break;
    case 2:
        alert('Add addtional tests if problems occur.');
        nextTest();
        break;
    case 3: 
        alert('All tests finished');
        break;
    }
}

function nextTest() {
        testcase++;
        setTimeout('test()', 500);
}

function testSucceded() {
    document.getElementById('test-result-' + testcase.toString()).setAttribute('class', 'succeded');
    document.getElementById('test-result-' + testcase.toString()).setAttribute('value', 'succeded');
}

function testFailed() {
    document.getElementById('test-result-' + testcase.toString()).setAttribute('class', 'failed');
    document.getElementById('test-result-' + testcase.toString()).setAttribute('value', 'failed');
}